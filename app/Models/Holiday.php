<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Holiday extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'restaurant_id',
        'holiday_name',
        'start_date',
        'end_date',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
