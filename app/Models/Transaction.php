<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasUuids;
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'room_id',
        'room_number',
        'check_in',
        'check_out',
        'accomodation_plans_id',
        'service_id',
        'notes',
        'checkin_status',

        'invoice',
        'payment_url',
        'payment_status',
        'payment_method',
        'payment_method_detail', // New field for specific iPaymu payment method
        'total_price',
        'admin_fee', // New field for admin fee
        'created_at',   
        'promos_id',
        'payment_deadline',
        'checkin_date',
        'checkout_date',

        'ipaymu_transaction_id',
        'ipaymu_session_id',
        'ipaymu_response',
        'ipaymu_expired_date'
    ];

    protected $casts = [
        'ipaymu_response' => 'array',
        'ipaymu_expired_date' => 'datetime',
        'check_in' => 'date',
        'check_out' => 'date',
        'checkin_date' => 'datetime',
        'checkout_date' => 'datetime',
        'payment_deadline' => 'datetime',
        'admin_fee' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function room() {
        return $this->belongsTo(Room::class);
    }

    public function accomodation_plans() {
        return $this->belongsToMany(AccomodationPlan::class, 'transaction_accomodation_plans');
    }

    public function service() {
        return $this->belongsTo(Service::class);
    }

    public function promos() {
        return $this->belongsToMany(Promo::class, 'transaction_promos');
    }

    public function room_reviews() {
        return $this->hasMany(Promo::class, 'room_reviews');
    }

    public function saldo(){
        return $this->hasMany(Saldo::class);
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'PAID');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'PENDING');
    }

    public function scopeCancelled($query)
    {
        return $query->where('payment_status', 'CANCELLED');
    }

    public function scopeFailed($query)
    {
        return $query->where('payment_status', 'FAILED');
    }

    public function scopeExpired($query)
    {
        return $query->where('payment_status', 'EXPIRED');
    }

    // Helper methods
    public function isPaid()
    {
        return $this->payment_status === 'PAID';
    }

    public function isPending()
    {
        return $this->payment_status === 'PENDING';
    }

    public function isCancelled()
    {
        return $this->payment_status === 'CANCELLED';
    }

    public function isFailed()
    {
        return $this->payment_status === 'FAILED';
    }

    public function isExpired()
    {
        return $this->payment_deadline && Carbon::now()->gt($this->payment_deadline);
    }

    public function getFormattedTotalPriceAttribute()
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    public function getFormattedAdminFeeAttribute()
    {
        return 'Rp ' . number_format($this->admin_fee, 0, ',', '.');
    }

    public function getBaseAmountAttribute()
    {
        return $this->total_price - $this->admin_fee;
    }

    public function getFormattedBaseAmountAttribute()
    {
        return 'Rp ' . number_format($this->getBaseAmountAttribute(), 0, ',', '.');
    }

    /**
     * Get the payment method display name for iPaymu
     */
    public function getPaymentMethodDisplayAttribute()
    {
        $methodNames = [
            'va' => 'Virtual Account',
            'qris' => 'QRIS',
            'banktransfer' => 'Bank Transfer',
            'cstore' => 'Convenience Store',
            'cod' => 'Cash on Delivery',
            'cc' => 'Credit Card'
        ];
        
        return $methodNames[$this->payment_method] ?? $this->payment_method;
    }

    /**
     * Get payment method icon for iPaymu methods
     */
    public function getPaymentMethodIconAttribute()
    {
        $icons = [
            'va' => 'account_balance',
            'qris' => 'qr_code',
            'banktransfer' => 'account_balance',
            'cstore' => 'store',
            'cod' => 'local_shipping',
            'cc' => 'credit_card'
        ];

        return $icons[$this->payment_method] ?? 'payment';
    }

    /**
     * Get payment method color for UI
     */
    public function getPaymentMethodColorAttribute()
    {
        $colors = [
            'va' => 'blue',
            'qris' => 'purple',
            'banktransfer' => 'green',
            'cstore' => 'orange',
            'cod' => 'gray',
            'cc' => 'indigo'
        ];

        return $colors[$this->payment_method] ?? 'gray';
    }

    /**
     * Check if payment method is virtual account
     */
    public function isVirtualAccountPayment()
    {
        return $this->payment_method === 'va';
    }

    /**
     * Check if payment method is QRIS
     */
    public function isQRISPayment()
    {
        return $this->payment_method === 'qris';
    }

    /**
     * Check if payment method is bank transfer
     */
    public function isBankTransferPayment()
    {
        return $this->payment_method === 'banktransfer';
    }

    /**
     * Check if payment method is convenience store
     */
    public function isConvenienceStorePayment()
    {
        return $this->payment_method === 'cstore';
    }

    /**
     * Check if payment method is cash on delivery
     */
    public function isCODPayment()
    {
        return $this->payment_method === 'cod';
    }

    /**
     * Check if payment method is credit card
     */
    public function isCreditCardPayment()
    {
        return $this->payment_method === 'cc';
    }

    /**
     * Get nights count
     */
    public function getNightsAttribute()
    {
        return $this->check_in->diffInDays($this->check_out);
    }

    /**
     * Get formatted check in date
     */
    public function getFormattedCheckInAttribute()
    {
        return $this->check_in->isoFormat('dddd, D MMM Y');
    }

    /**
     * Get formatted check out date  
     */
    public function getFormattedCheckOutAttribute()
    {
        return $this->check_out->isoFormat('dddd, D MMM Y');
    }
}