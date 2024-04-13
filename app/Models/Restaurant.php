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
}
