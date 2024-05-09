<?php

namespace App\Mail;

use App\Models\Restaurant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RestaurantRegistrationRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $restaurant;

    public function __construct(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
    }

    public function build()
    {
        return $this->view('emails.restaurant_register_rejected')
                    ->subject('Registration Rejected');
    }
}

