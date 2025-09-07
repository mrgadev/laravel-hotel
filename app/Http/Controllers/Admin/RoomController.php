<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\RoomFacilities;
use App\Models\RoomFacility;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::all();
        return view('dashboard.admin.room.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $room_facilities = RoomFacility::all();
        return view('dashboard.admin.room.create', compact('room_facilities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $message = [
            'name.required' => 'Nama kamar wajib diisi',
            'price_numeric.required' => 'Harga wajib diisi',
            'price_numeric.integer' => 'Harga harus berupa angka',
            'price_numeric.min' => 'Harga tidak boleh negatif',
            'photos.required' => 'Gambar lainnya wajib diunggah',
            'room_facilities_id.required' => 'Fasilitas kamar wajib dipilih',
            'total_rooms.required' => 'Total kamar wajib diisi',
            'total_rooms.integer' => 'Total kamar harus berupa angka'
        ];

        $data = $request->validate([
            'name' => 'required',
            'price_numeric' => 'required|integer|min:0', // Changed from 'price' to 'price_numeric'
            'photos' => 'required|array',
            'photos.*' => 'image|mimes:png,jpg,jpeg,webp,avif',
            'room_facilities_id' => 'required|array',
            'room_facilities_id.*' => 'exists:room_facilities,id',
            'total_rooms' => 'integer|required',
            'description' => 'nullable'
        ], $message);

        $room_slug_name = Str::slug($data['name']);

        // Handle multiple photos
        $photos = [];
        if($files = $request->file('photos')) {
            foreach($files as $index => $file) {
                $image_name = $index.'-'.$room_slug_name.'-'.rand(000,999);
                $ext = strtolower($file->getClientOriginalExtension());
                $image_full_name = $image_name.'.'.$ext;
                $upload_path = 'storage/rooms/';
                
                // Create directory if not exists
                if (!file_exists($upload_path)) {
                    mkdir($upload_path, 0755, true);
                }
                
                $image_url = $upload_path.$image_full_name;
                $file->move($upload_path, $image_full_name);
                $photos[] = $image_url;
            }
        }

        $room = Room::create([
            'name' => $data['name'],
            'slug' => $room_slug_name,
            'price' => $data['price_numeric'], // Use the numeric value
            'photos' => $photos, // This will be cast to array automatically
            'description' => $data['description'],
            'total_rooms' => $data['total_rooms'],
            'available_rooms' => $data['total_rooms']
        ]);

        $room->room_facility()->sync($request->room_facilities_id);

        return redirect()->route('dashboard.room.index')
            ->with('success', 'Kamar berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        return view('frontpage.room-detail', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        $room_facilities = RoomFacility::all();
        return view('dashboard.admin.room.edit', compact('room', 'room_facilities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $message = [
            'name.required' => 'Nama kamar wajib diisi',
            'price_numeric.required' => 'Harga wajib diisi',
            'price_numeric.integer' => 'Harga harus berupa angka',
            'price_numeric.min' => 'Harga tidak boleh negatif',
            'room_facilities_id.required' => 'Fasilitas kamar wajib dipilih',
            'total_rooms.required' => 'Total kamar wajib diisi',
            'total_rooms.integer' => 'Total kamar harus berupa angka'
        ];

        $data = $request->validate([
            'name' => 'required',
            'price_numeric' => 'required|integer|min:0', // Changed from 'price' to 'price_numeric'
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:png,jpg,jpeg,webp,avif',
            'room_facilities_id' => 'required|array',
            'room_facilities_id.*' => 'exists:room_facilities,id',
            'total_rooms' => 'integer|required',
            'description' => 'nullable',
            'remove_photos' => 'nullable|array', // For photos to be removed
            'remove_photos.*' => 'string'
        ], $message);

        $room_slug_name = Str::slug($data['name']);

        // Handle photos update
        $existingPhotos = $room->photos ?? [];
        
        // Remove selected photos
        if ($request->has('remove_photos') && is_array($request->remove_photos)) {
            foreach ($request->remove_photos as $photoToRemove) {
                // Remove from filesystem
                if (file_exists($photoToRemove)) {
                    unlink($photoToRemove);
                }
                // Remove from array
                $existingPhotos = array_values(array_filter($existingPhotos, function($photo) use ($photoToRemove) {
                    return $photo !== $photoToRemove;
                }));
            }
        }

        // Add new photos if any
        if($request->hasFile('photos')) {
            foreach($request->file('photos') as $index => $file) {
                $image_name = (count($existingPhotos) + $index).'-'.$room_slug_name.'-'.rand(000,999);
                $ext = strtolower($file->getClientOriginalExtension());
                $image_full_name = $image_name.'.'.$ext;
                $upload_path = 'storage/rooms/';
                
                // Create directory if not exists
                if (!file_exists($upload_path)) {
                    mkdir($upload_path, 0755, true);
                }
                
                $image_url = $upload_path.$image_full_name;
                $file->move($upload_path, $image_full_name);
                $existingPhotos[] = $image_url;
            }
        }

        $room->update([
            'name' => $data['name'],
            'slug' => $room_slug_name,
            'price' => $data['price_numeric'], // Use the numeric value
            'photos' => $existingPhotos,
            'description' => $data['description'],
            'total_rooms' => $data['total_rooms'],
            'available_rooms' => $data['total_rooms']
        ]);

        $room->room_facility()->sync($request->room_facilities_id);

        return redirect()->route('dashboard.room.index')
            ->with('success', 'Kamar berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        try {
            DB::beginTransaction();

            // Delete photos from storage
            if (!empty($room->photos) && is_array($room->photos)) {
                foreach ($room->photos as $photo) {
                    if (file_exists($photo)) {
                        unlink($photo);
                    }
                }
            }

            // Delete cover image
            if (!empty($room->cover) && file_exists($room->cover)) {
                unlink($room->cover);
            }

            // Detach all facilities
            $room->room_facility()->detach();

            // Delete the room
            $room->delete();

            DB::commit();

            return redirect()->route('dashboard.room.index')
                ->with('success', 'Kamar berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal menghapus kamar: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete rooms
     */
    public function bulkDelete(Request $request)
    {
        try {
            $roomIds = $request->room_ids;
            
            if (empty($roomIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada kamar yang dipilih'
                ]);
            }

            DB::beginTransaction();

            $rooms = Room::whereIn('id', $roomIds)->get();

            foreach ($rooms as $room) {
                // Delete photos from storage
                if (!empty($room->photos) && is_array($room->photos)) {
                    foreach ($room->photos as $photo) {
                        if (file_exists($photo)) {
                            unlink($photo);
                        }
                    }
                }

                // Delete cover image
                if (!empty($room->cover) && file_exists($room->cover)) {
                    unlink($room->cover);
                }

                // Detach all facilities
                $room->room_facility()->detach();

                // Delete the room
                $room->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kamar berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kamar: ' . $e->getMessage()
            ]);
        }
    }
}