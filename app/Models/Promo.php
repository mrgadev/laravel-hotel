<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = ['name', 'code', 'cover', 'amount','start_date', 'end_date', 'is_all'];

    public function rooms(){
    return $this->belongsToMany(Room::class, 'promo_room', 'promo_id', 'room_id');
    }

    public function transactions() {
        return $this->belongsToMany(Transaction::class, 'transaction_promos');
    }
}
