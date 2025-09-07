<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::all();
        $service_categories = ServiceCategory::all();
        return view('dashboard.admin.services.index', compact('services', 'service_categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $service_categories = ServiceCategory::all();
        return view('dashboard.admin.services.create', compact('service_categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $message = [
            'name.required' => 'Nama layanan wajib diisi',
            'description.required' => 'Deskripsi layanan wajib diisi',
            'service_category_id.required' => 'Kategori layanan wajib dipilih',
            'image.required' => 'Gambar wajib diunggah',
            'image.mimes' => 'Format gambar tidak valid!',
            'cover.required' => 'Gambar sampul wajib diunggah',
            'cover.mimes' => 'Format gambar sampul tidak valid',
            'price.required' => 'Harga wajib diisi'
        ];
        
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'service_category_id' => 'required',
            'image' => 'required',
            'cover' => 'required|mimes:jpeg,png,jpg,avif,webp',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'price' => 'required',
        ], $message);

        // Handle multiple images
        $imagePaths = [];
        if($request->hasFile('image')){
            foreach ($request->file('image') as $key => $image) {
                $image_name = 'LAYANAN-'.$key.'-'.Str::slug($request->name).'-'.rand(1000,9999);
                $ext = strtolower($image->getClientOriginalExtension());
                $image_full_name = $image_name.'.'.$ext;
                
                // Store using Laravel Storage
                $imagePath = $image->storeAs('services', $image_full_name, 'public');
                $imagePaths[] = $imagePath;
            }
        }

        // Handle cover image
        $coverPath = null;
        if($request->file('cover')) {
            $cover_name = 'SAMPUL-LAYANAN-'.Str::slug($request->name).'-'.rand(1000,9999);
            $ext = strtolower($request->file('cover')->getClientOriginalExtension());
            $cover_full_name = $cover_name.'.'.$ext;
            
            // Store cover using Laravel Storage
            $coverPath = $request->file('cover')->storeAs('services', $cover_full_name, 'public');
        }

        Service::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'service_categories_id' => $data['service_category_id'],
            'cover' => $coverPath,
            'image' => json_encode($imagePaths),
            'price' => $data['price'],
        ]);

        return redirect()->route('dashboard.service.index')->with('success', 'Berhasil menambah data layanan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        $service_categories = ServiceCategory::all();
        return view('dashboard.admin.services.edit', compact('service', 'service_categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $message = [
            'name.required' => 'Nama layanan wajib diisi',
            'description.required' => 'Deskripsi layanan wajib diisi',
            'service_category_id.required' => 'Kategori layanan wajib dipilih',
            'image.mimes' => 'Format gambar tidak valid!',
            'cover.mimes' => 'Format gambar sampul tidak valid',
            'price.required' => 'Harga wajib diisi'
        ];
        
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'service_category_id' => 'required',
            'cover' => 'nullable|mimes:jpeg,png,jpg,avif,webp',
            'image' => 'nullable',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required',
        ], $message);

        // Handle multiple images
        $imagePaths = [];
        if($request->hasFile('image')) {
            // Delete old images if exist
            if($service->image) {
                $oldImages = json_decode($service->image, true);
                if(is_array($oldImages)) {
                    foreach($oldImages as $oldImage) {
                        if(Storage::disk('public')->exists($oldImage)) {
                            Storage::disk('public')->delete($oldImage);
                        }
                    }
                }
            }
            
            // Upload new images
            foreach ($request->file('image') as $key => $image) {
                $image_name = 'LAYANAN-'.$key.'-'.Str::slug($request->name).'-'.rand(1000,9999);
                $ext = strtolower($image->getClientOriginalExtension());
                $image_full_name = $image_name.'.'.$ext;
                
                $imagePath = $image->storeAs('services', $image_full_name, 'public');
                $imagePaths[] = $imagePath;
            }
        } else {
            // Keep existing images
            $imagePaths = json_decode($service->image, true) ?: [];
        }

        // Handle cover image
        $coverPath = $service->cover; // Keep existing cover as default
        if($request->file('cover')) {
            // Delete old cover if exists
            if($service->cover && Storage::disk('public')->exists($service->cover)) {
                Storage::disk('public')->delete($service->cover);
            }
            
            // Upload new cover
            $cover_name = 'SAMPUL-LAYANAN-'.Str::slug($request->name).'-'.rand(1000,9999);
            $ext = strtolower($request->file('cover')->getClientOriginalExtension());
            $cover_full_name = $cover_name.'.'.$ext;
            
            $coverPath = $request->file('cover')->storeAs('services', $cover_full_name, 'public');
        }

        // Update service
        $service->update([
            'name' => $data['name'],
            'description' => $data['description'],
            'service_categories_id' => $data['service_category_id'],
            'cover' => $coverPath,
            'price' => $data['price'],
            'image' => json_encode($imagePaths)
        ]);

        return redirect()->route('dashboard.service.index')->with('success', 'Berhasil mengubah data layanan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        // Delete images if exist
        if ($service->image) {
            $images = json_decode($service->image, true);
            if(is_array($images)) {
                foreach ($images as $image) {
                    if(Storage::disk('public')->exists($image)) {
                        Storage::disk('public')->delete($image);
                    }
                }
            }
        }

        // Delete cover if exists
        if($service->cover && Storage::disk('public')->exists($service->cover)) {
            Storage::disk('public')->delete($service->cover);
        }
        
        $service->delete();
        return redirect()->route('dashboard.service.index')->with('success', 'Layanan berhasil dihapus');
    }
}