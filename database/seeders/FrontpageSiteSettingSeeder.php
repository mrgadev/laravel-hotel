<?php

namespace Database\Seeders;

use App\Models\FrontpageSiteSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FrontpageSiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed FrontpageSiteSetting
        FrontpageSiteSetting::create([
            'tagline' => 'Hotel Terbaik di Kota Anda',
            'description' => 'Nikmati pengalaman menginap yang tak terlupakan dengan fasilitas modern dan pelayanan terbaik. Hotel kami menyediakan kenyamanan dan kemewahan untuk semua kebutuhan akomodasi Anda.',
            'our_service_title' => 'Layanan Unggulan Kami',
            'our_location_title' => 'Lokasi Strategis',
            'our_location_desc' => 'Terletak di jantung kota dengan akses mudah ke berbagai tempat wisata, pusat perbelanjaan, dan area bisnis. Lokasi yang sempurna untuk perjalanan bisnis maupun liburan.',
            'our_facilities_title' => 'Fasilitas Lengkap',
            'testimonial_title' => 'Apa Kata Tamu Kami',
            'award_title' => 'Penghargaan & Sertifikasi',
            'award_desc' => 'Kami bangga telah meraih berbagai penghargaan bergengsi dalam industri perhotelan dan berkomitmen untuk terus memberikan pelayanan terbaik.',
            'cta_text' => 'Siap untuk pengalaman menginap yang luar biasa? Pesan kamar Anda sekarang dan nikmati pelayanan terbaik dari kami.',
            'cta_button_link' => '/booking',
            'cta_button_text' => 'Pesan Sekarang',
            'hero_cover' => 'images/hero-cover.jpg',
            'service_image' => 'images/service-image.jpg',
            'faq_illustration' => 'images/faq-illustration.jpg',
            'cta_cover' => 'images/cta-cover.jpg'
        ]);
    }
}
