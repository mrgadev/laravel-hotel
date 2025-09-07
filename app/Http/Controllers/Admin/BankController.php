<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\SiteSettings;
use Illuminate\Http\Request;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'name' => ['required','string','max:255'],
        ]);

        Bank::create($data);

        return redirect()->route('dashboard.site.settings.edit')->with('success', 'Bank created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bank $bank)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bank $bank)
    {
        $site_setting = SiteSettings::where('id',1)->firstOrFail();
        return view('dashboard.admin.site-settings.bank-edit', compact('bank', 'site_setting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bank $bank)
    {
        $data = $request->validate([
            'name' => 'required'
        ]);
        $site_setting = SiteSettings::where('id',1)->firstOrFail();
        $bank->update($data);
        return redirect()->route('dashboard.site.settings.edit', compact('site_setting'))->with('success', 'Berhasil mengubah data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bank $bank)
    {
        $bank->delete();

        return redirect()->route('dashboard.site.settings.edit')->with('success', 'Bank deleted successfully.');
    }
}
