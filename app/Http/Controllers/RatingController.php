<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Role;
use App\Models\Attachment;
use App\Models\Reservation;
use App\Models\Restaurant;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // Create a new rating
        Rating::create([
            'reservation_id' => $request->reservation_id,
            'user_id' => auth()->id(), // Assuming you are using authentication
            'restaurant_id' => Reservation::find($request->reservation_id)->restaurant_id,
            'mark' => $request->mark,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Rating submitted successfully.');
    }

    public function show(Rating $rating)
    {
        //
    }

    public function edit(Rating $rating)
    {
        //
    }

    public function update(Request $request, Rating $rating)
    {
        //
    }

    public function destroy(Rating $rating)
    {
        //
    }
}
