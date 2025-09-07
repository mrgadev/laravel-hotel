<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomRule extends Model
{
    protected $fillable = ['room_id', 'icon', 'rule'];

    public function room() {
        return $this->belongsTo(Room::class);
    }
}
