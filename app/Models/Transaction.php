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
        'total_price',
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
        return $query->where('payment_status', 'Sudah bayar');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'Belum bayar');
    }

    // Helper methods
    public function isPaid()
    {
        return $this->payment_status === 'Sudah bayar';
    }

    public function isExpired()
    {
        return $this->payment_deadline && Carbon::now()->gt($this->payment_deadline);
    }

    public function getFormattedTotalPriceAttribute()
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }
}
