<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Promo;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promos = Promo::all();
        return view('dashboard.admin.promos.index', compact('promos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rooms = Room::all();
        return view('dashboard.admin.promos.create', compact('rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $message = [
            'name.required' => 'Judul promo wajib diisi',
            'code.required' => 'Kode promo wajib diisi',
            'cover.required' => 'Gambar sampul wajib diunggah',
            'amount.required' => 'Jumlah potongan wajib diisi',
            'start_date.required' => 'Tanggal awal wajib diisi',
            'end_date.required' => 'Tanggal akhir wajib diisi',
            'cover.mimes' => 'Format gambar tidak valid!'
        ];
        $request->validate([
            'name' => 'required',
            'code' => 'required',
            'cover' => 'required|mimes:jpeg,jpg,png,avif,webp',
            'amount' => 'required|integer|max:95',
            'start_date' => 'required',
            'end_date' => 'required',
            'is_all' => 'nullable',
            'room_id' => 'nullable|array',
            'room_id.*' => 'exists:rooms,id',
        ], $message);

        $coverPath = null;
        if($request->file('cover')) {
            // Gunakan Storage::disk('public') untuk konsistensi
            $cover_name = 'PROMO-'.Str::slug($request->name).'-'.rand(1000,9999);
            $ext = strtolower($request->file('cover')->getClientOriginalExtension());
            $cover_full_name = $cover_name.'.'.$ext;
            
            // Simpan ke storage/app/public/promo/
            $coverPath = $request->file('cover')->storeAs('promo', $cover_full_name, 'public');
        }

        $promo = new Promo();
        $promo->name = $request->name;
        $promo->code = $request->code;
        $promo->cover = $coverPath; // Simpan path relatif seperti 'promo/filename.jpg'
        $promo->amount = $request->amount;
        $promo->start_date = $request->start_date;
        $promo->end_date = $request->end_date;
        $promo->is_all = $request->boolean('is_all', false);
        $promo->save();

        // Sync rooms jika ada
        if (!$promo->is_all && $request->has('room_id')) {
            $promo->rooms()->sync($request->room_id);
        }

        return redirect()->route('dashboard.promo.index')->with('success', 'Promo created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Promo $promo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Promo $promo)
    {
        $rooms = Room::all();
        return view('dashboard.admin.promos.edit', compact('promo', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Promo $promo)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required',
            'cover' => 'nullable|mimes:jpeg,jpg,png,avif,webp',
            'amount' => 'required|integer|max:95',
            'start_date' => 'required',
            'end_date' => 'required',
            'is_all' => 'boolean',
            'room_id' => 'nullable|array',
            'room_id.*' => 'exists:rooms,id',
        ]);

        $coverPath = $promo->cover; // Gunakan cover lama sebagai default

        if($request->file('cover')) {
            // Hapus gambar lama jika ada
            if($promo->cover && Storage::disk('public')->exists($promo->cover)) {
                Storage::disk('public')->delete($promo->cover);
            }
            
            // Upload gambar baru
            $cover_name = 'PROMO-'.Str::slug($request->name).'-'.rand(1000,9999);
            $ext = strtolower($request->file('cover')->getClientOriginalExtension());
            $cover_full_name = $cover_name.'.'.$ext;
            
            $coverPath = $request->file('cover')->storeAs('promo', $cover_full_name, 'public');
        }

        // Update promo
        $promo->update([
            'name' => $request->name,
            'code' => $request->code,
            'cover' => $coverPath,
            'amount' => $request->amount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_all' => $request->boolean('is_all', false)
        ]);

        // Handle room associations
        if ($request->boolean('is_all')) {
            $promo->rooms()->detach(); // Hapus semua relasi room
        } else {
            if ($request->has('room_id')) {
                $promo->rooms()->sync($request->room_id);
            }
        }

        return redirect()->route('dashboard.promo.index')->with('success', 'Promo updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promo $promo)
    {
        // Hapus gambar jika ada
        if($promo->cover && Storage::disk('public')->exists($promo->cover)) {
            Storage::disk('public')->delete($promo->cover);
        }
        
        // Hapus relasi dengan rooms
        $promo->rooms()->detach();
        
        // Hapus promo
        $promo->delete();

        return redirect()->route('dashboard.promo.index')->with('success', 'Promo deleted successfully');
    }
}