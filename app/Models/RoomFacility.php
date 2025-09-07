<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomFacility extends Model
{
    protected $fillable = ['icon', 'name', 'description', 'color'];

    public function rooms() {
        return $this->belongsToMany(Room::class);
    }
}
