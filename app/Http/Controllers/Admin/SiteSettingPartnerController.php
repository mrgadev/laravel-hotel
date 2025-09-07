<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SiteSettingPartner;
use Illuminate\Support\Facades\Storage;

class SiteSettingPartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'logo' => 'required',
            'name' => 'required',
            'link' => 'required',
        ]);

        if($request->file('logo')) {
            $logo_name = 'PARTNER-'.Str::slug($data['name']).'-'.rand(000,999);
            $ext = strtolower($request->file('logo')->getClientOriginalExtension());
            $logo_full_name = $logo_name.'.'.$ext;
            $upload_path ='storage/partners/';
            $logo_url = $upload_path.$logo_full_name;
            $request->file('logo')->move($upload_path, $logo_full_name);
            $logoPath = $logo_url;
        }

        SiteSettingPartner::create([
            'name' => $data['name'],
            'logo' => $logoPath,
            'link' => $data['link']
        ]);

        return redirect()->back()->with('success', 'Berhasil menambahkan data');
    }

    /**
     * Display the specified resource.
     */
    public function show(SiteSettingPartner $partner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SiteSettingPartner $partner)
    {
        return view('dashboard.admin.site-settings.edit_partner', compact('partner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SiteSettingPartner $partner)
    {
        $data = $request->validate([
            'logo' => 'nullable',
            'name' => 'required',
            'link' => 'required',
        ]);

        if($request->file('logo')) {
            $logo_name = 'PARTNER-'.Str::slug($data['name']).'-'.rand(000,999);
            $ext = strtolower($request->file('logo')->getClientOriginalExtension());
            $logo_full_name = $logo_name.'.'.$ext;
            $upload_path ='storage/partners/';
            $logo_url = $upload_path.$logo_full_name;
            $request->file('logo')->move($upload_path, $logo_full_name);
            if($partner->logo && file_exists($partner->logo)) {
                unlink($partner->logo);
            }
            $logoPath= $logo_url;
        } else {
            $logoPath = $partner->logo;
        }

        $partner->update([
            'logo' => $logoPath,
            'name' => $data['name'],
            'link' => $data['link'],
        ]);

        return redirect()->route('dashboard.site.settings.edit')->with('success', 'Berhasil mengubah data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SiteSettingPartner $partner)
    {
        if($partner->logo && file_exists($partner->logo)) {
            unlink($partner->logo);
        }
        $partner->delete();

        return redirect()->back()->with('success', 'Berhasil menghapus data!');
    }
}
