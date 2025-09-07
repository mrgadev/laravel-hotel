<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlipService
{
    private $secretKey;
    private $validationToken;
    private $baseUrl;

    public function __construct()
    {
        $this->secretKey = config('services.flip.secret_key');
        $this->validationToken = config('services.flip.validation_token');
        $this->baseUrl = config('services.flip.base_url');
    }

    /**
     * Create a payment bill with Flip
     */
    public function createBill(array $data)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->secretKey . ':'),
                'Content-Type' => 'application/x-www-form-urlencoded'
            ])->asForm()->post($this->baseUrl . '/pwf/bill', $data);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                Log::error('Flip API Error: ' . $response->body());
                return [
                    'success' => false,
                    'error' => 'Failed to create payment bill'
                ];
            }
        } catch (\Exception $e) {
            Log::error('Flip Service Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get bill status from Flip
     */
    public function getBillStatus($billId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->secretKey . ':'),
            ])->get($this->baseUrl . '/pwf/bill/' . $billId);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                Log::error('Flip API Error: ' . $response->body());
                return [
                    'success' => false,
                    'error' => 'Failed to get bill status'
                ];
            }
        } catch (\Exception $e) {
            Log::error('Flip Service Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Validate webhook signature
     */
    public function validateWebhookSignature($payload, $signature)
    {
        // Implementasi validasi signature jika Flip menyediakan
        // Ini tergantung pada dokumentasi Flip
        $expectedSignature = hash_hmac('sha256', $payload, $this->validationToken);
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Format data for bill creation
     */
    public function formatBillData($transaction, $totalAmount, $nights)
    {
        return [
            'title' => 'Pembayaran ' . $transaction->room->name . ' - UNS Inn',
            'amount' => $totalAmount,
            'type' => 'SINGLE',
            'expired_date' => $transaction->payment_deadline->toDateTimeString(),
            'redirect_url' => route('payment.success', $transaction->invoice),
            'sender_name' => $transaction->name,
            'sender_email' => $transaction->email,
            'sender_phone_number' => $transaction->phone,
        ];
    }
}