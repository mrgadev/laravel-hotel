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
        'payment_method_detail', // New field for specific Flip payment method
        'total_price',
        'admin_fee', // New field for admin fee
        'created_at',   
        'promos_id',
        'payment_deadline',
        'checkin_date',
        'checkout_date',

        'flip_bill_id',
        'flip_response',
        'flip_expired_date'
    ];

    protected $casts = [
        'flip_response' => 'array',
        'flip_expired_date' => 'datetime',
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
     * Get the payment method display name
     */
    public function getPaymentMethodDisplayAttribute()
    {
        if ($this->payment_method_detail) {
            return $this->payment_method;
        }
        
        return $this->payment_method;
    }

    /**
     * Get payment method icon or color based on method
     */
    public function getPaymentMethodIconAttribute()
    {
        $icons = [
            'bca_va' => 'account_balance',
            'bni_va' => 'account_balance', 
            'bri_va' => 'account_balance',
            'mandiri_va' => 'account_balance',
            'permata_va' => 'account_balance',
            'cimb_va' => 'account_balance',
            'bsi_va' => 'account_balance',
            'qris' => 'qr_code',
            'shopeepay' => 'wallet',
            'gopay' => 'wallet',
            'ovo' => 'wallet',
            'dana' => 'wallet', 
            'linkaja' => 'wallet',
            'indomaret' => 'store',
            'alfamart' => 'store'
        ];

        return $icons[$this->payment_method_detail] ?? 'payment';
    }

    /**
     * Get payment method color for UI
     */
    public function getPaymentMethodColorAttribute()
    {
        $colors = [
            'bca_va' => 'blue',
            'bni_va' => 'orange', 
            'bri_va' => 'blue',
            'mandiri_va' => 'yellow',
            'permata_va' => 'green',
            'cimb_va' => 'red',
            'bsi_va' => 'green',
            'qris' => 'purple',
            'shopeepay' => 'orange',
            'gopay' => 'green',
            'ovo' => 'purple',
            'dana' => 'blue', 
            'linkaja' => 'red',
            'indomaret' => 'yellow',
            'alfamart' => 'red'
        ];

        return $colors[$this->payment_method_detail] ?? 'gray';
    }

    /**
     * Check if payment method is e-wallet
     */
    public function isEWalletPayment()
    {
        $ewalletMethods = ['qris', 'shopeepay', 'gopay', 'ovo', 'dana', 'linkaja'];
        return in_array($this->payment_method_detail, $ewalletMethods);
    }

    /**
     * Check if payment method is bank transfer
     */
    public function isBankTransferPayment()
    {
        $bankMethods = ['bca_va', 'bni_va', 'bri_va', 'mandiri_va', 'permata_va', 'cimb_va', 'bsi_va'];
        return in_array($this->payment_method_detail, $bankMethods);
    }

    /**
     * Check if payment method is retail
     */
    public function isRetailPayment()
    {
        $retailMethods = ['indomaret', 'alfamart'];
        return in_array($this->payment_method_detail, $retailMethods);
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