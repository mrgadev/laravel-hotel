<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Promo;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

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

        if($request->file('cover')) {
            $cover_name = 'PROMO-'.Str::slug($request->name).rand(000,999);
            $ext = strtolower($request->file('cover')->getClientOriginalExtension());
            $cover_full_name = $cover_name.'.'.$ext;
            $upload_path ='storage/promo/';
            $cover_url = $upload_path.$cover_full_name;
            $request->file('cover')->move($upload_path, $cover_full_name);

            $coverPath = $cover_url;
        }

        // Promo::create([
        //     'name' => $request->name,
        //     'code' => $request->code,
        //     'cover' => $coverPath,
        //     'amount' => $request->amount,
        //     'start_date' => $request->start_date,
        //     'end_date' => $request->end_date,
        //     'is_all' => $request->is_all,
        // ]);

        $promo = new Promo();
        $promo->name = $request->name;
        $promo->code = $request->code;
        $promo->cover = $coverPath;
        $promo->amount = $request->amount;
        $promo->start_date = $request->start_date;
        $promo->end_date = $request->end_date;
        $promo->is_all = $request->is_all;
        $promo->save();

        $promo->rooms()->sync($request->room_id);

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
            'cover' => 'nullable',
            'amount' => 'required|integer|max:95',
            'start_date' => 'required',
            'end_date' => 'required',
            'is_all' => 'required', // tambah boolean disini
            'room_id' => 'nullable|array',
            'room_id.*' => 'exists:rooms,id',
        ]);

        if($request->file('cover')) {
            $cover_name = 'PROMO-'.Str::slug($request->name).rand(000,999);
            $ext = strtolower($request->file('cover')->getClientOriginalExtension());
            $cover_full_name = $cover_name.'.'.$ext;
            $upload_path ='storage/promo/';
            $cover_url = $upload_path.$cover_full_name;
            $request->file('cover')->move($upload_path, $cover_full_name);
            if($promo->cover && file_exists($promo->cover)) {
                unlink($promo->cover);
            }
            $coverPath = $cover_url;
        } else {
            $coverPath  = $promo->cover;
        }

        // Ubah pengecekan is_all nya gini
        if ($request->boolean('is_all')) { // pake method boolean()
            $promo->rooms()->detach();
            $rooms = [];
        } else {
            if ($request->has('room_id')) {
                $rooms = $request->room_id;
            } else {
                $rooms = $promo->rooms->pluck('id')->toArray();
            }
        }

        $promo->rooms()->sync($rooms);

        // Convert is_all ke boolean sebelum update
        $promo->update([
            'name' => $request->name,
            'code' => $request->code,
            'cover' => $coverPath,
            'amount' => $request->amount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_all' => $request->boolean('is_all') // pake method boolean() disini juga
        ]);

        return redirect()->route('dashboard.promo.index')->with('success', 'Promo updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promo $promo)
    {
        if($promo->cover && file_exists($promo->cover)) {
            unlink($promo->cover);
        }
        $promo->delete();
        $promo->rooms()->detach();
        return redirect()->route('dashboard.promo.index')->with('success', 'Promo deleted successfully');
    }
}
