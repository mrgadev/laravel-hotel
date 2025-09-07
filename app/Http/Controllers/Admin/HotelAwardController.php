<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelAward;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class HotelAwardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hotel_awards = HotelAward::all();
        return view('dashboard.admin.hotel-award.index', compact('hotel_awards'));
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
            'name' => 'required|string',
            'badge' => 'required|image|mimes:jpg,jpeg,png,svg,webp,gif,avif'
        ]);

        if($request->file('badge')) {
            $badge_name = 'AWARD-'.Str::slug($data['name']).rand(000,999);
            $ext = strtolower($request->file('badge')->getClientOriginalExtension());
            $badge_full_name = $badge_name.'.'.$ext;
            $upload_path ='storage/hotel-award/';
            $badge_url = $upload_path.$badge_full_name;
            $request->file('badge')->move($upload_path, $badge_full_name);
            $data['badge']= $badge_url;
        }
        HotelAward::create($data);
        return redirect()->route('dashboard.hotel-awards.index')->with('success', 'Berhasil menambah data');
    }

    /**
     * Display the specified resource.
     */
    public function show(HotelAward $hotel_award)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HotelAward $hotel_award)
    {
        return view('dashboard.admin.hotel-award.edit', compact('hotel_award'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HotelAward $hotel_award)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'badge' => 'image|mimes:jpg,jpeg,png,svg,webp,gif,avif'
        ]);

        if($request->file('badge')) {
            $badge_name = 'AWARD-'.Str::slug($data['name']).rand(000,999);
            $ext = strtolower($request->file('badge')->getClientOriginalExtension());
            $badge_full_name = $badge_name.'.'.$ext;
            $upload_path ='storage/hotel-award/';
            $badge_url = $upload_path.$badge_full_name;
            $request->file('badge')->move($upload_path, $badge_full_name);
            if($hotel_award->badge && file_exists($hotel_award->badge)) {
                unlink($hotel_award->badge);
            }
            $data['badge']= $badge_url;
        } else {
            $data['badge'] = $hotel_award->badge;
        }
        $hotel_award->update($data);
        return redirect()->route('dashboard.hotel-awards.index')->with('success', 'Berhasil mengubah data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HotelAward $hotel_award)
    {
        if($hotel_award->badge && file_exists($hotel_award->badge)) {
            unlink($hotel_award->badge);
        }
        $hotel_award->delete();
        return redirect()->route('dashboard.hotel-awards.index')->with('success', 'Berhasil menghapus data');
    }
}
