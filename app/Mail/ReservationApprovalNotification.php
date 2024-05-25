<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Restaurant;
use App\Models\Reservation;
class ReservationApprovalNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;
    public $status;

    /**
     * Create a new message instance.
     *
     * @param Reservation $reservation
     * @param string $status
     * @return void
     */
    public function __construct(Reservation $reservation, $status)
    {
        $this->reservation = $reservation;
        $this->status = $status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->status === 'Approved' ? 'Reservation Approved' : 'Reservation Rejected';
        
        return $this->view('emails.reservation_approval_notification')
                    ->subject($subject);
    }
}
