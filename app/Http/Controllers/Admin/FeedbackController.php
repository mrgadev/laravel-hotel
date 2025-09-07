<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $feedbacks = Feedback::all();
        return view('dashboard.admin.feedbacks.index', compact('feedbacks'));
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
        $feedback = [
            'title.required' => 'Judul wajib diisi',
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'phone.required' => 'Nomor telepon wajib diisi',
            'feedback.required' => 'Pesan wajib diisi',
        ];

        $data = $request->validate([
            'title' => 'required|string',
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'feedback' => 'required'
        ], $feedback);

        $data['slug'] = Str::slug($data['title']);

        Feedback::create($data);
        return redirect()->route('dashboard.admin.feedbacks.index')->with('success', 'Pesan berhasil dikirim!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Feedback $feedback)
    {
        return view('dashboard.admin.feedbacks.show', compact('feedback'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Feedback $feedback)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Feedback $feedback)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feedback $feedback)
    {
        $feedback->delete();
        return redirect()->route('dashboard.feedback')->with('success', 'Pesan berhaisl dihapus!');
    }
}
