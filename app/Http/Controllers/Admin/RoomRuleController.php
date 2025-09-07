<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomRule;
use Illuminate\Http\Request;

class RoomRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $room_rules = RoomRule::all();
        $rooms = Room::all();
        return view('dashboard.admin.room-rules.index', compact('rooms', 'room_rules'));
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
        $message = [
            'room_id.required' => 'Nama kamar wajib dipilih',
            // 'room_id.exists' => 'Data kamar harus tersedia',
            'icon.required' => 'Icon wajib dipilih',
            'rule.required' => 'Peraturan wajib diisi'
        ];
        $data = $request->validate([
            'room_id' => 'required',
            'icon' => 'required',
            'rule' => 'required'
        ], $message);
        if($data['room_id'] == 'Semua Kamar')  {
            $data['room_id'] = NULL;
        }
        RoomRule::create($data);
        return redirect()->route('dashboard.room-rules.index')->with('success', 'Berhasil menambah data!');
    }

    /**
     * Display the specified resource.
     */
    public function show(RoomRule $room_rule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RoomRule $room_rule)
    {  
        $rooms = Room::all();
        return view('dashboard.admin.room-rules.edit', compact('room_rule', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RoomRule $room_rule)
    {
        $message = [
            'room_id.required' => 'Nama kamar wajib dipilih',
            // 'room_id.exists' => 'Data kamar harus tersedia',
            'icon.required' => 'Icon wajib dipilih',
            'rule.required' => 'Peraturan wajib diisi'
        ];
        $data = $request->validate([
            'room_id' => 'required',
            'icon' => 'required',
            'rule' => 'required'
        ], $message);
        if($data['room_id'] == 'Semua Kamar')  {
            $data['room_id'] = NULL;
        }

        $room_rule->update($data);
        return redirect()->route('dashboard.room-rules.index')->with('success', 'Data berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomRule $room_rule)
    {
        $room_rule->delete();
        return redirect()->route('dashboard.room-rules.index')->with('success', 'Data berhasil dihapus!');
    }
}
