<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NearbyLocation extends Model
{
    protected $fillable = ['name', 'distance', 'icon'];
}
