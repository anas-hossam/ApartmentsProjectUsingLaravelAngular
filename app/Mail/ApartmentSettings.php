<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApartmentSettings extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $apartment;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Apartment $apartment)
    {
        $this->user = $user;
        $this->apartment = $apartment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.apartmentSettings')
            ->with('user', $this->user)
            ->with('apartment', $this->apartment);
    }
}
