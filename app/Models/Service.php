<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'description', 'image', 'price', 'service_categories_id', 'cover'];

    public function serviceCategory() {
        return $this->belongsTo(ServiceCategory::class, 'service_categories_id', 'id');
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }
}
