<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelService;
use Illuminate\Http\Request;

class HotelServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hotel_services = HotelService::all();
        return view('dashboard.admin.hotel-services.index', compact('hotel_services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.admin.hotel-services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $message = [
            'name.required' => 'Nama wajib diisi',
            'icon.required' => 'Icon wajib dipilih'
        ];
        $data = $request->validate([
            'name' => 'required|string',
            'icon' => 'required|string',
            'description' => 'nullable|string',
        ]);
        HotelService::create($data);
        return redirect()->route('dashboard.hotel-services.index')->with('success', 'Data berhasil ditambah');
    }

    /**
     * Display the specified resource.
     */
    public function show(HotelService $hotel_service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HotelService $hotel_service)
    {
        return view('dashboard.admin.hotel-services.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HotelService $hotel_service)
    {
        $message = [
            'name.required' => 'Nama wajib diisi',
        ];
        $data = $request->validate([
            'name' => 'required|string',
            'icon' => 'string',
            'description' => 'nullable|string',
        ]);
        $hotel_service->update($data);
        return redirect()->route('dashboard.hotel-services.index')->with('success', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HotelService $hotel_service)
    {
        $hotel_service->delete();
        return redirect()->route('dashboard.hotel-services.index')->with('success', 'Data berhasil dihapus');
    }
}
