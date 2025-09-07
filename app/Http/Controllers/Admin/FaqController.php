<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faqs = Faq::all();
        return view('dashboard.admin.faq.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.admin.faq.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $message = [
            'question.required' => 'Pertanyaan wajib diisi',
            'answer.required' => 'Jawaban wajib diisi'
        ];

        $data = $request->validate([
            'question' => 'required',
            'answer' => 'required'
        ], $message);

        Faq::create($data);
        return redirect()->route('dashboard.faq.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Faq $faq)
    {
        // return view('')
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faq $faq)
    {
        return view('dashboard.admin.faq.edit', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faq $faq)
    {
        $message = [
            'question.required' => 'Pertanyaan wajib diisi',
            'answer.required' => 'Jawaban wajib diisi'
        ];

        $data = $request->validate([
            'quesiton' => 'required',
            'answer' => 'required'
        ]);

        $faq->update($data);
        return redirect()->route('dashboard.faq.index')->with('success', 'Data berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('dashboard.faq.index')->with('success', 'Data berhasil dihapus!');
    }
}
