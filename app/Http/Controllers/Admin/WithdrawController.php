<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Withdraw;
use App\Models\Saldo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $withdrawals = Withdraw::latest()->get();
        return view('dashboard.admin.penarikan_saldo.index', compact('withdrawals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $saldo = Saldo::where('user_id', Auth::user()->id)
                        ->latest()
                        ->first();
        $user = User::where('id', $saldo->user_id)->first();
        $banks = Bank::all();
        return view('dashboard.user.penarikan_saldo.create', compact('saldo', 'user', 'banks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required',
            'notes' => 'required',
            'bank_number' => 'required',
            'bank_id' => 'required',
        ]);

        $user = Auth::user();

        $user->update([
            'bank_id' => $data['bank_id'],
            'bank_number' => $data['bank_number'],
        ]);

        $withdraw = Withdraw::create([
            'user_id' => $user->id,
            'amount' => $data['amount'],
            'notes' => $data['notes'],
            'status' => 'Tertunda'
        ]);

        // $lastBalance = Saldo::where('user_id', $user_id)
        //                 ->latest()
        //                 ->first();

        // $newAmount = $lastBalance->amount - $data['amount'];

        // Saldo::create([
        //     'user_id' => $user_id,
        //     'transaction_id' => null,
        //     'debit' => 0,
        //     'credit' => $data['amount'],
        //     'amount' => $newAmount,
        // ]);

        return redirect()->route('dashboard.penarikan-saldo.success', $withdraw->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Withdraw $withdraw)
    {
        $saldo = Saldo::where('user_id', $withdraw->user_id)->latest()->first();
        $user = User::where('id', $withdraw->user_id)->first();
        $bankName = Bank::where('id', $user->bank_id)->first();
        return view('dashboard.admin.penarikan_saldo.show', compact('withdraw', 'saldo', 'user', 'bankName'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Withdraw $withdraw)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Withdraw $withdraw)
    {
        $message = [
            'image.image' => 'File yang diupload harus berupa gambar',
            'image.mimes' => 'Format gambar tidak sesuai!'
        ];
        $data = $request->validate([
            'status' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp,avif',
        ], $message);

        if($request->file('image')) {
            $image_name = 'IMAGE-'.$withdraw->id.'-'.rand(000,999);
            $ext = strtolower($request->file('image')->getClientOriginalExtension());
            $image_full_name = $image_name.'.'.$ext;
            $upload_path ='storage/withdraw/';
            $image_url = $upload_path.$image_full_name;
            $request->file('image')->move($upload_path, $image_full_name);
            $data['image']= $image_url;
        } else {
            $data['image'] = $withdraw->image;
        }

        if($request->has('status')) {
            $data['status'] = $request->status;
        } else {
            $data['status'] = $withdraw->status;
        }

        $lastBalance = Saldo::where('user_id', $withdraw->user_id)
                        ->latest()
                        ->first();

        if($data['status'] == 'Disetujui') {
            $newAmount = $lastBalance->amount - $withdraw->amount;
            Saldo::create([
                'user_id' => $withdraw->user_id,
                'transaction_id' => null,
                'debit' => 0,
                'credit' => $withdraw->amount,
                'amount' => $newAmount,
                'description' => 'Penarikan Saldo'
            ]);
        }

        $withdraw->update([
            'status' => $data['status'],
            'image' => $data['image']
        ]);

        return redirect()->route('dashboard.withdraw.index')->with('success', 'Data berhasi diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Withdraw $withdraw)
    {
        //
    }

    public function success(String $id){
        $withdraw = Withdraw::findOrFail($id);
        $saldo = Saldo::where('user_id', $withdraw->user_id)->latest()->first();
        return view('dashboard.user.penarikan_saldo.success', compact('withdraw','saldo'));
    }
}
