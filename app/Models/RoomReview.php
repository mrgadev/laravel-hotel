<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomReview extends Model
{
    protected $fillable = [
        'title', 
        'description',
        'images',
        'rating',
        'rating_text',
        'user_id',
        'room_id',
        'transaction_id',
        'visibility'
    ];
    public function room() {
        return $this->belongsTo(Room::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function transaction() {
        return $this->belongsTo(Transaction::class);
    }
}