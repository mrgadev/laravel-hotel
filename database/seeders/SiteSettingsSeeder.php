<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSettings;

class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed SiteSettings
        SiteSettings::create([
            'maps_link' => 'https://maps.google.com/maps/search/UNS%20Inn/@-7.56335544586182,110.856002807617,17z',
            'address' => 'Jalan Ir Sutami 36A, Surakarta, Jawa Tengah 57126, ID',
            'phone' => '+62-21-1234-5678',
            'phone_text' => '(021) 1234-5678',
            'payment_deadline' => '24',
            'checkin_time' => '14:00',
            'checkout_time' => '12:00'
        ]);
    }
}