<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class IPaymuService
{
    protected $apiKey;
    protected $va;
    protected $baseUrl;
    protected $isProduction;

    public function __construct()
    {
        $this->apiKey = config('services.ipaymu.api_key');
        $this->va = config('services.ipaymu.va');
        $this->isProduction = config('services.ipaymu.is_production', false);
        $this->baseUrl = $this->isProduction 
            ? 'https://my.ipaymu.com/api/v2' 
            : 'https://sandbox.ipaymu.com/api/v2';
    }

    /**
     * Generate signature for iPaymu API
     */
    private function generateSignature($method, $endpoint, $jsonBody = null)
    {
        // Hash body dengan SHA256, jika body kosong maka hash string kosong
        $bodyHash = $jsonBody ? strtolower(hash('sha256', $jsonBody)) : strtolower(hash('sha256', ''));
        
        // String to sign format: METHOD:VA:BODYHASH:APIKEY
        $stringToSign = strtoupper($method) . ':' . $this->va . ':' . $bodyHash . ':' . $this->apiKey;
        
        // Generate HMAC SHA256 signature
        return hash_hmac('sha256', $stringToSign, $this->apiKey);
    }

    /**
     * Create payment transaction
     */
    public function createTransaction($data)
    {
        try {
            $endpoint = '/payment/direct';
            
            $requestData = [
                'name' => $data['name'] ?? $data['buyerName'] ?? 'Customer',
                'phone' => $data['phone'] ?? $data['buyerPhone'] ?? '08123456789',
                'email' => $data['email'] ?? $data['buyerEmail'] ?? 'customer@example.com',
                'amount' => $this->formatAmount($data['amount']),
                'notifyUrl' => $data['notifyUrl'] ?? url('/payment/ipaymu/notify'),
                'returnUrl' => $data['returnUrl'] ?? url('/'),
                'cancelUrl' => $data['cancelUrl'] ?? url('/'),
                'expired' => $data['expired'] ?? 24,
                'expiredType' => $data['expiredType'] ?? 'hours',
                'comments' => $data['comments'] ?? 'Hotel Booking Payment',
                'referenceId' => $data['referenceId'] ?? uniqid('hotel_'),
                'paymentMethod' => $data['paymentMethod'],
                'paymentChannel' => $data['paymentChannel']
            ];

            if (isset($data['product']) && is_array($data['product'])) {
                $requestData['product'] = $data['product'];
            }

            $jsonBody = json_encode($requestData, JSON_UNESCAPED_SLASHES);
            $signature = $this->generateSignature('POST', $endpoint, $jsonBody);

            Log::info('iPaymu Request Details:', [
                'endpoint' => $this->baseUrl . $endpoint,
                'method' => 'POST',
                'headers' => [
                    'Content-Type' => 'application/json',
                    'va' => $this->va,
                    'timestamp' => time()
                ],
                'body_hash' => strtolower(hash('sha256', $jsonBody)),
                'signature' => $signature,
                'data' => $requestData
            ]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'va' => $this->va,
                'signature' => $signature,
                'timestamp' => time()
            ])->post($this->baseUrl . $endpoint, $requestData);

            Log::info('iPaymu API Response:', [
                'status_code' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                if (!is_array($responseData)) {
                    Log::error('iPaymu returned non-JSON response: ' . $response->body());
                    throw new Exception('Invalid response format from iPaymu API');
                }
                
                // Cek status response
                if (isset($responseData['Status']) && $responseData['Status'] == 200) {
                    if (!isset($responseData['Data'])) {
                        Log::error('iPaymu response missing Data field: ' . json_encode($responseData));
                        throw new Exception('Invalid response structure from iPaymu API');
                    }
                    
                    return $responseData;
                } else {
                    $errorMessage = $responseData['Keterangan'] ?? $responseData['Message'] ?? 'Unknown error from iPaymu';
                    Log::error('iPaymu API returned error: ' . json_encode($responseData));
                    throw new Exception($errorMessage);
                }
            } else {
                Log::error('iPaymu API HTTP Error:', [
                    'status_code' => $response->status(),
                    'response_body' => $response->body()
                ]);
                throw new Exception('Failed to create payment transaction: HTTP ' . $response->status());
            }

        } catch (Exception $e) {
            Log::error('iPaymu Service Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Check transaction status
     */
    public function checkTransactionStatus($transactionId)
    {
        try {
            $endpoint = '/transaction';
            $requestData = ['transactionId' => $transactionId];
            $jsonBody = json_encode($requestData);
            $signature = $this->generateSignature('POST', $endpoint, $jsonBody);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'va' => $this->va,
                'signature' => $signature,
                'timestamp' => time()
            ])->post($this->baseUrl . $endpoint, $requestData);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('iPaymu Check Status Error: ' . $response->body());
            throw new Exception('Failed to check transaction status');

        } catch (Exception $e) {
            Log::error('iPaymu Check Status Service Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Verify callback signature
     */
    public function verifyCallback($signature, $body)
    {
        $computedSignature = hash_hmac('sha256', $body, $this->apiKey);
        return hash_equals($signature, $computedSignature);
    }

    /**
     * Get available payment methods with channels
     */
    public function getPaymentMethods()
    {
        return [
            'va' => [
                'name' => 'Virtual Account',
                'channels' => [
                    'bni' => 'BNI Virtual Account',
                    'bri' => 'BRI Virtual Account', 
                    'bca' => 'BCA Virtual Account',
                    'mandiri' => 'Mandiri Virtual Account',
                    'cimb' => 'CIMB Niaga Virtual Account',
                    'danamon' => 'Danamon Virtual Account'
                ]
            ],
            'qris' => [
                'name' => 'QRIS',
                'channels' => [
                    'qris' => 'QRIS (Semua E-Wallet)'
                ]
            ],
            'banktransfer' => [
                'name' => 'Bank Transfer',
                'channels' => [
                    'bca' => 'BCA',
                    'bni' => 'BNI',
                    'bri' => 'BRI',
                    'mandiri' => 'Mandiri'
                ]
            ],
            'cstore' => [
                'name' => 'Convenience Store',
                'channels' => [
                    'indomaret' => 'Indomaret',
                    'alfamart' => 'Alfamart'
                ]
            ],
            'cc' => [
                'name' => 'Credit Card',
                'channels' => [
                    'visa' => 'Visa',
                    'mastercard' => 'Mastercard'
                ]
            ]
        ];
    }

    /**
     * Format amount for iPaymu (remove decimals)
     */
    public function formatAmount($amount)
    {
        return (int) round($amount);
    }

    /**
     * Calculate fee based on payment method
     */
    public function calculateFee($amount, $paymentMethod)
    {
        $feeRates = [
            'va' => 4000, // Flat fee
            'qris' => 0.007, // 0.7%
            'banktransfer' => 6500, // Flat fee
            'cstore' => 5000, // Flat fee
            'cc' => 0.029 // 2.9%
        ];

        $fee = $feeRates[$paymentMethod] ?? 0;
        
        if ($fee < 1) {
            // Percentage based fee
            return round($amount * $fee);
        } else {
            // Flat fee
            return $fee;
        }
    }

    /**
     * Get payment method display information
     */
    public function getPaymentMethodInfo($method)
    {
        $info = [
            'va' => [
                'name' => 'Virtual Account',
                'icon' => 'account_balance',
                'color' => 'blue'
            ],
            'qris' => [
                'name' => 'QRIS',
                'icon' => 'qr_code',
                'color' => 'purple'
            ],
            'banktransfer' => [
                'name' => 'Bank Transfer',
                'icon' => 'account_balance',
                'color' => 'green'
            ],
            'cstore' => [
                'name' => 'Convenience Store',
                'icon' => 'store',
                'color' => 'orange'
            ],
            'cc' => [
                'name' => 'Credit Card',
                'icon' => 'credit_card',
                'color' => 'indigo'
            ]
        ];

        return $info[$method] ?? [
            'name' => $method,
            'icon' => 'payment',
            'color' => 'gray'
        ];
    }
}