<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelFacility extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
    ];
}
