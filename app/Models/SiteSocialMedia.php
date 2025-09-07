<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSocialMedia extends Model
{
    protected $fillable = ['icon', 'username', 'link', 'site_settings_id'];
    public function site_setting() {
        return $this->belongsTo(SiteSocialMedia::class);
    }
}
