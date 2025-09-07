<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NearbyLocation;
use Illuminate\Support\Facades\Storage;

class NearbyLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $nearby_locations = NearbyLocation::all();
        return view('dashboard.admin.nearby-location.index', compact('nearby_locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.admin.nearby-location.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $message = [
            'name.required' => 'Nama fasilitas wajib diisi!',
            'icon.required' => 'Icon fasilitas wajib dipilih!',
            'distance.required' => 'Jarak harus diisi!'
        ];

        $data = $request->validate([
            'name' => 'required',
            'icon' => 'required',
            'distance' => 'required'
        ], $message);

        NearbyLocation::create([
            'name' => $data['name'],
            'icon' => $data['icon'],
            'distance' => $data['distance'],
        ]);

        return redirect()->route('dashboard.nearby-location.index')->with('success', 'Lokasi terdekat berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(NearbyLocation $nearby_location)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NearbyLocation $nearby_location)
    {
        return view('dashboard.admin.nearby-location.edit', compact('nearby_location'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NearbyLocation $nearby_location)
    {
        $message = [
            'name.required' => 'Nama fasilitas wajib diisi!',
            'distance.required' => 'Jarak wajib diisi'
        ];

        $data = $request->validate([
            'name' => 'required',
            'icon' => 'nullable',
            'distance' => 'required'
        ], $message);

        $nearby_location->update($data);

        return redirect()->route('dashboard.nearby-location.index')->with('success', 'Data berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NearbyLocation $nearby_location)
    {
        $icon = $nearby_location->icon;
        $nearby_location->delete();

        return redirect()->route('dashboard.nearby-location.index')->with('success', 'Fasilitas hotel berhasil dihapus!');
    }
}
