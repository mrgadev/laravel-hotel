<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSettings extends Model
{
    protected $table = 'site_settings';
    protected $fillable = [
        'maps_link',
        'address',
        'phone',
        'phone_text',
        'payment_deadline',
        'checkin_time',
        'checkout_time',
    ];

    public function site_social_media() {
        return $this->hasMany(SiteSocialMedia::class);
    }
}
