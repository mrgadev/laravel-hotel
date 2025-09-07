<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    protected $fillable = [
        'amount',
        'credit',
        'debit',
        'description',
        'user_id',
        'transaction_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function transaction() {
        return $this->belongsTo(Transaction::class);
    }
}
