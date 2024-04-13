<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Restaurant;

class RestaurantCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $restaurant;
    public $password;

    public function __construct(Restaurant $restaurant, $password)
    {
        $this->restaurant = $restaurant;
        $this->password = $password;
    }

    public function build()
    {
        return $this->view('emails.restaurant_created')
                    ->subject('Your restaurant account has been created')
                    ->with([
                        'restaurant' => $this->restaurant,
                        'password' => $this->password
                    ]);
    }
}

