<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'user_id',
        'restaurant_id',
        'mark',
        'comment'
    ];

    // Define the relationship with the Reservation model
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with the Restaurant model
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
