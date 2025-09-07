<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = 
    [
        'user_id',
        'room_id',
        'accomdation_plan_id',
        'service_id',
        'name',
        'email',
        'phone',
        'check_in',
        'check_out',
        'notes',
        'total_price',
        'status',
    ];
}
