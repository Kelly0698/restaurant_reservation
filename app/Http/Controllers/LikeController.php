<?php

namespace App\Http\Controllers;
use App\Models\Restaurant;
use App\Models\User;
use App\Models\Role;
use App\Models\Like;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{

    public function saveRestaurant($id)
    {
        $user = Auth::user();
        $like = Like::where('user_id', $user->id)->where('restaurant_id', $id)->first();

        if ($like) {
            // If already liked, remove the like
            $like->delete();
            return response()->json(['success' => true, 'liked' => false]);
        } else {
            // If not liked, add the like
            Like::create([
                'user_id' => $user->id,
                'restaurant_id' => $id,
            ]);
            return response()->json(['success' => true, 'liked' => true]);
        }
    }
   
    public function savedRestaurants()
    {
        $user = Auth::user();
    
        // Fetch all restaurants liked by the user
        $likedRestaurants = $user->savedRestaurants()
            ->with('ratings') // Eager load the ratings relationship
            ->where('status', 'Approved')
            ->get()
            ->map(function ($restaurant) {
                // Calculate the average rating for each restaurant
                $restaurant->averageRating = $restaurant->ratings->avg('mark') ?? 0;
                return $restaurant;
            })
            ->sortByDesc('averageRating');
    
        return view('user.saved_restaurant', compact('user', 'likedRestaurants'));
    }
    
    
}
