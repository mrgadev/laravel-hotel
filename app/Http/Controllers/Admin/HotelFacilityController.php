<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HotelFacility;
use Illuminate\Support\Facades\Log;

class HotelFacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hotel_facilities = HotelFacility::all();
        return view('dashboard.admin.hotel-facilities.index', compact('hotel_facilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.admin.hotel-facilities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // Log::info('Form submitted', $request->all());
        $message = [
            'name.required' => 'Nama fasilitas wajib diisi!',
            'icon.required' => 'Icon fasilitas wajib diupload!',
        ];

        $data = $request->validate([
            'name' => 'required',
            'icon' => 'required',
            'description' => 'nullable',
        ], $message);


        HotelFacility::create([
            'name' => $data['name'],
            'icon' => $data['icon'],
            'description' => $data['description'],
        ]);

        return redirect()->route('dashboard.hotel-facilities.index')->with('success', 'Fasilitas hotel berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(HotelFacility $hotel_facility)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HotelFacility $hotel_facility)
    {
        return view('dashboard.admin.hotel-facilities.edit', compact('hotel_facility'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HotelFacility $hotel_facility)
    {
        $message = [
            'name.required' => 'Nama fasilitas wajib diisi!',
        ];

        $data = $request->validate([
            'name' => 'required',
            'icon' => 'nullable',
            'description' => 'nullable',
        ], $message);

        $hotel_facility->update([
            'name' => $data['name'],
            'icon' => $data['icon'],
            'description' => $data['description'],
        ]);

        return redirect()->route('dashboard.hotel-facilities.index')->with('success', 'Fasilitas hotel berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HotelFacility $hotel_facility)
    {
        $hotel_facility->delete();
        return redirect()->route('dashboard.hotel-facilities.index')->with('success', 'Fasilitas hotel berhasil dihapus.');
    }
}
