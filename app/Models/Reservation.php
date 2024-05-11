<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'restaurant_id',
        'date',
        'time',
        'party_size',
        'remark',
        'status',
    ];

    //relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //relationship with Restaurant model
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }
}
