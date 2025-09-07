<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrontpageSiteSetting extends Model
{
    protected $table = 'frontpage_site_settings';
    protected $fillable = [
        'tagline',
        'description',
        'our_service_title',
        'our_location_title',
        'our_location_desc',
        'our_facilities_title',
        'testimonial_title',
        'award_title',
        'award_desc',
        'cta_text',
        'cta_button_link',
        'cta_button_text',

        'hero_cover',
        'service_image',
        'faq_illustration',
        'cta_cover'
    ];
}
