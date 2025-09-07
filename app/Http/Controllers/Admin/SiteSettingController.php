<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\FrontpageSiteSetting;
use App\Models\SiteSettingPartner;
use App\Models\SiteSettings;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function edit() {
        $site_setting = SiteSettings::where('id',1)->firstOrFail();
        $partners = SiteSettingPartner::all();
        $banks = Bank::all();
        return view('dashboard.admin.site-settings.edit', compact('site_setting', 'partners', 'banks'));
    }

    public function frontpageEdit() {
        $frontpage_site_setting = FrontpageSiteSetting::where('id',1)->firstOrFail();
        $partners = SiteSettingPartner::all();
        $banks = Bank::all();
        return view('dashboard.admin.site-settings.frontpage', compact('frontpage_site_setting', 'partners', 'banks'));
    }

    public function update(Request $request, SiteSettings $site_setting) {
        $message = [
            'phone.required' => 'Nomor telepon wajib diisi',
            'phone_text.required' => 'Teks nomor telepon wajib diisi',
            'maps_link' => 'Tautan alamat wajib diisi',
            'address' => 'Alamat wajib diisi',
            'payment_deadline' => 'Durasi transaksi wajib diisi!',
            'checkin_time' => 'Durasi transaksi wajib diisi!',
            'checkout_time' => 'Durasi transaksi wajib diisi!'
        ];
        $data = $request->validate([
            'phone' => 'required',
            'phone_text' => 'required',
            'maps_link' => 'required',
            'address' => 'required',
            'payment_deadline' => 'required',
            'checkin_time' => 'required',
            'checkout_time' => 'required'
        ], $message);
        // dd($site_setting);
        $site_setting->update($data);
        return redirect()->route('dashboard.site.settings.edit', compact('site_setting'))->with('success','Pengaturan situs berhasil diperbarui!');
    }

    public function frontpageUpdate(Request $request, FrontpageSiteSetting $frontpage_site_setting) {
        $message = [
            'tagline.required' => 'Tagline wajib diisi',
            'description.required' => 'Deskripsi wajib diisi',
            'our_service_title.required' => 'Judul Layanan Kami wajib diisi',
            'our_location_title.required' => 'Judul bagian Lokasi wajib diisi',
            'our_facilities_title.required' => 'Judul Fasilitas Kami wajib diisi',
            'testimonial_title.required' => 'Judul testimonial wajib diisi',
            'award_title.required' => 'Judul penghargaan wajib diisi',
            'cta_text.required' => 'Judul Call to Action wajib diisi',
            'cta_button_text.required' => 'Tulisan tombol Call to Action wajib diisi',
            'cta_button_link.required' => 'Tautan tombol Call to Action wajib diisi',

            'hero_cover.image' => 'File harus berupa gambar',
            'hero_cover.mimes' => 'Format file tidak valid!',
            'service_image.image' => 'File harus berupa gambar',
            'service_image.mimes' => 'Format file tidak valid!',
            'faq_illustration.image' => 'File harus berupa gambar',
            'faq_illustration.mimes' => 'Format file tidak valid!',
            'cta_cover.image' => 'File harus berupa gambar',
            'cta_cover.mimes' => 'Format file tidak valid!',
        ];

        $data = $request->validate([
            'tagline' => 'required|string',
            'description' => 'required|string',
            'our_service_title' => 'required|string',
            'our_location_title' => 'required|string',
            'our_location_desc' => 'nullable',
            'our_facilities_title' => 'required|string',
            'testimonial_title' => 'required|string',
            'award_title' => 'required|string',
            'award_desc' => 'nullable',
            'cta_text' => 'required|string',
            'cta_button_text' => 'required|string',
            'cta_button_link' => 'required|string',

            'hero_cover' => 'nullable|image|mimes:jpeg,jpg,png,svg,webp,avif',
            'service_image' => 'nullable|image|mimes:jpeg,jpg,png,svg,webp,avif',
            'faq_illustration' => 'nullable|image|mimes:jpeg,jpg,png,svg,webp,avif',
            'cta_cover' => 'nullable|image|mimes:jpeg,jpg,png,svg,webp,avif',
        ],$message);

        // Cek apakah ada gambar yg masuk
        if($request->file('hero_cover')) {
            $hero_cover_name = 'HERO-'.rand(000,999);
            $ext = strtolower($request->file('hero_cover')->getClientOriginalExtension());
            $hero_cover_full_name = $hero_cover_name.'.'.$ext;
            $upload_path ='storage/site-settings/';
            $hero_cover_url = $upload_path.$hero_cover_full_name;
            $request->file('hero_cover')->move($upload_path, $hero_cover_full_name);
            if($frontpage_site_setting->hero_cover && file_exists($frontpage_site_setting->hero_cover)) {
                unlink($frontpage_site_setting->hero_cover);
            }
            $data['hero_cover']= $hero_cover_url;
        } else {
            $data['hero_cover'] = $frontpage_site_setting->hero_cover;
        }
        // dd($data['hero_cover']);

        if($request->file('service_image')) {
            $service_image_name = 'SERVICE-IMAGE-'.rand(000,999);
            $ext = strtolower($request->file('service_image')->getClientOriginalExtension());
            $service_image_full_name = $service_image_name.'.'.$ext;
            $upload_path ='storage/site-settings/';
            $service_image_url = $upload_path.$service_image_full_name;
            $request->file('service_image')->move($upload_path, $service_image_full_name);
            if($frontpage_site_setting->service_image && file_exists($frontpage_site_setting->service_image)) {
                unlink($frontpage_site_setting->service_image);
            }
            $data['service_image']= $service_image_url;
        } else {
            $data['service_image'] = $frontpage_site_setting->service_image;
        }

        if($request->file('faq_illustration')) {
            $faq_illustration_name = 'FAQ-'.rand(000,999);
            $ext = strtolower($request->file('faq_illustration')->getClientOriginalExtension());
            $faq_illustration_full_name = $faq_illustration_name.'.'.$ext;
            $upload_path ='storage/site-settings/';
            $faq_illustration_url = $upload_path.$faq_illustration_full_name;
            $request->file('faq_illustration')->move($upload_path, $faq_illustration_full_name);
            if($frontpage_site_setting->faq_illustration && file_exists($frontpage_site_setting->faq_illustration)) {
                unlink($frontpage_site_setting->faq_illustration);
            }
            $data['faq_illustration']= $faq_illustration_url;
        } else {
            $data['faq_illustration'] = $frontpage_site_setting->faq_illustration;
        }

        if($request->file('cta_cover')) {
            $cta_cover_name = 'CTA-'.'-'.rand(000,999);
            $ext = strtolower($request->file('cta_cover')->getClientOriginalExtension());
            $cta_cover_full_name = $cta_cover_name.'.'.$ext;
            $upload_path ='storage/site-settings/';
            $cta_cover_url = $upload_path.$cta_cover_full_name;
            $request->file('cta_cover')->move($upload_path, $cta_cover_full_name);
            if($frontpage_site_setting->cta_cover && file_exists($frontpage_site_setting->cta_cover)) {
                unlink($frontpage_site_setting->cta_cover);
            }
            $data['cta_cover']= $cta_cover_url;
        } else {
            $data['cta_cover'] = $frontpage_site_setting->cta_cover;
        }

        // dd($data);
        $frontpage_site_setting->update($data);
        return redirect()->route('dashboard.site.settings.frontpage.edit', compact('frontpage_site_setting'))->with('success', 'Data berhasil diperbarui');
    }

    public function editBank(Bank $bank) {
        
    }

    public function updateBank(Request $request, Bank $bank) {

    }
}

