<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Contracts\Auth\Authenticatable;

class Restaurant extends Model implements Authenticatable
{
    use HasFactory, AuthenticableTrait;

    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'address',
        'operation_time',
        'logo_pic',
        'license_pdf',
        'availability',
        'description',
        'status',
        'table_arrange_pic'
    ];

    protected $hidden = ['password', 'remember_token'];

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function holidays()
    {
        return $this->hasMany(Holiday::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, Like::class, 'restaurant_id', 'id', 'id', 'user_id');
    }
}
