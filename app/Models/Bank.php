<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $fillable = [
        'logo',
        'name'
    ];

    public function user() {
        return $this->hasMany(User::class);
    }
}
