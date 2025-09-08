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
        $stringToSign = $method . ':' . $this->va . ':' . strtolower(hash('sha256', $jsonBody ?: '')) . ':' . $this->apiKey;
        return hash_hmac('sha256', $stringToSign, $this->apiKey);
    }

    /**
     * Create payment transaction
     */
    public function createTransaction($data)
    {
        try {
            $endpoint = '/payment';
            $jsonBody = json_encode($data);
            $signature = $this->generateSignature('POST', $endpoint, $jsonBody);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'va' => $this->va,
                'signature' => $signature,
                'timestamp' => time()
            ])->post($this->baseUrl . $endpoint, $data);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('iPaymu API Error: ' . $response->body());
            throw new Exception('Failed to create payment transaction');

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
            $signature = $this->generateSignature('POST', $endpoint, json_encode(['transactionId' => $transactionId]));

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'va' => $this->va,
                'signature' => $signature,
                'timestamp' => time()
            ])->post($this->baseUrl . $endpoint, [
                'transactionId' => $transactionId
            ]);

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
     * Get available payment methods
     */
    public function getPaymentMethods()
    {
        return [
            'va' => [
                'name' => 'Virtual Account',
                'channels' => ['bni', 'bca', 'mandiri', 'bri', 'cimb', 'danamon', 'permata']
            ],
            'qris' => [
                'name' => 'QRIS',
                'channels' => ['qris']
            ],
            'banktransfer' => [
                'name' => 'Bank Transfer',
                'channels' => ['bni', 'bca', 'mandiri', 'bri']
            ],
            'cstore' => [
                'name' => 'Convenience Store',
                'channels' => ['indomaret', 'alfamart']
            ],
            'cc' => [
                'name' => 'Credit Card',
                'channels' => ['visa', 'mastercard']
            ]
        ];
    }

    /**
     * Format amount for iPaymu (remove decimals)
     */
    public function formatAmount($amount)
    {
        return (int) $amount;
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
            // Percentage fee
            return $amount * $fee;
        } else {
            // Flat fee
            return $fee;
        }
    }
}