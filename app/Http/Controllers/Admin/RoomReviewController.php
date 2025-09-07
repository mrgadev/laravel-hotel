<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RoomReview;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
// use Illuminate\Support\Facades\Gate;
class RoomReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', User::class);
        $room_reviews = RoomReview::all();
        return view('dashboard.admin.reviews.index', compact('room_reviews'));
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
        Gate::authorize('store', User::class);
        $data = $request->validate([
            'title' => 'string|required',
            'description' => 'required',
            // 'images.*' => 'image|mimes:jpeg,png,jpg,svg,webp,avif|max:2048|nullable',
            'room_id' => 'required|exists:rooms,id',
            'transaction_id' => 'required|exists:transactions,id',
            'rating' => 'required|integer'
        ]);



        $rating = $data['rating'];
        $rating_text = "";
        switch ($rating) {
            case 1:
                $rating_text = 'Buruk';
                break;
            case 2:
                $rating_text = 'Lumayan';
                break;
            case 3:
                $rating_text = 'Bagus';
                break;
            case 4:
                $rating_text = 'Sangat Bagus';
                break;
            case 5:
                $rating_text = 'Sempurna';
                break;

            default:
                $rating_text = "";
                break;
        }

        // $images = [];
        // if ($request->hasFile('images')) {
        //     foreach ($request->file('images') as $image) {
        //         $path = $image->store('review-images', 'public');
        //         $images[] = $path;
        //     }
        // }
        $roomReview = new RoomReview();
        $roomReview->title = $data['title'];
        $roomReview->description = $data['description'];
        $roomReview->rating = $rating;
        $roomReview->rating_text = $rating_text;
        $roomReview->user_id = Auth::user()->id;
        $roomReview->room_id = $data['room_id'];
        $roomReview->transaction_id = $data['transaction_id'];

        // dd($roomReview);
        $roomReview->save();
        $transaction = Transaction::where('id', $roomReview->transaction->id)->firstOrFail();
        return redirect()->route('dashboard.user.bookings.detail', $transaction->invoice)->with('success', 'Berhasil menambahkan ulasan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(RoomReview $room_review)
    {
        Gate::authorize('view', User::class);
        return view('dashboard.admin.reviews.detail', compact('room_review'));
        // return response()->json($room_review);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RoomReview $room_review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RoomReview $room_review)
    {
        Gate::authorize('update', User::class);
        $data = $request->validate([
            'title' => 'string|required',
            'description' => 'required',
            // 'images.*' => 'image|mimes:jpeg,png,jpg,svg,webp,avif|max:2048|nullable',
            'room_id' => 'required|exists:rooms,id',
            'transaction_id' => 'required|exists:transactions,id',
            'rating' => 'required'
        ]);



        $rating = $data['rating'];
        $rating_text = "";
        switch ($rating) {
            case 1:
                $rating_text = 'Buruk';
                break;
            case 2:
                $rating_text = 'Lumayan';
                break;
            case 3:
                $rating_text = 'Bagus';
                break;
            case 4:
                $rating_text = 'Sangat Bagus';
                break;
            case 5:
                $rating_text = 'Sempurna';
                break;

            default:
                $rating_text = "";
                break;
        }

        // $images = [];
        // if ($request->hasFile('images')) {
        //     foreach ($request->file('images') as $image) {
        //         $path = $image->store('review-images', 'public');
        //         $images[] = $path;
        //     }
        // }
        $room_review->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'rating' => $rating,
            'rating_text' => $rating_text,
            'user_id' => Auth::user()->id,
            'room_id' => $data['room_id'],
            'transaction_id' => $data['transaction_id']
        ]);
        
        $transaction = Transaction::where('id', $data['transaction_id'])->firstOrFail();
    return redirect()->route('dashboard.user.bookings.detail', $transaction->invoice)->with('success', 'Berhasil mengubah ulasan!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomReview $roomReview)
    {
        Gate::authorize('destroy', User::class);
    }
}
