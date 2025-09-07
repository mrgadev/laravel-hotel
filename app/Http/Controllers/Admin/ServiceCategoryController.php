<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $service_categories = ServiceCategory::all();
        return view('dashboard.admin.service-categories.index', compact('service_categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.admin.service-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $message = [
            'name.required' => 'Nama kategori wajib diisi'
        ];
        $request->validate([
            'name' => ['required','string','max:255'],
        ], $message);

        ServiceCategory::create($request->all());
        return redirect()->route('dashboard.service-category.index')->with('success', 'Kategori layanan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceCategory $serviceCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceCategory $service_category)
    {
        return view('dashboard.admin.service-categories.edit', compact('service_category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceCategory $service_category)
    {
        $message = [
            'name.required' => 'Nama kategori wajib diisi'
        ];
        $request->validate([
            'name' => ['required','string','max:255'],
        ], $message);

        $service_category->update($request->all());
        return redirect()->route('dashboard.service-category.index')->with('success', 'Kategori layanan berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceCategory $service_category)
    {
        $service_category->delete();
        return redirect()->route('dashboard.service-category.index')->with('success', 'Kategori layanan berhasil dihapus');
    }
}
