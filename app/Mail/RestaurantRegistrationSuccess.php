<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Restaurant;

class RestaurantRegistrationSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public $restaurant;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.restaurant_register_success')
                    ->with([
                        'name' => $this->restaurant->name,
                        // You can pass more data to the email template if needed
                    ])
                    ->subject('Registration Successful');
    }
}
