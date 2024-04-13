<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id',
        'restaurant_id',
        'picture'
    ];

    public function restaurants()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
