<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PickupTimeLocation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $data, $number;
    /**
     * Create a new message instance.
     */
    public function __construct($data, $number)
    {
        $this->data = $data;
        $this->number = $number;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        if ($this->number == 1) {
            return new Envelope(
                subject: 'Hotness pending',
            );
        } else {
            return new Envelope(
                subject: 'Chere Reminder',
            );
        }
    }

    public function build()
    {
        $data = $this->data;
        $number = $this->number;

        if ($number == 1)
            $name = $data['name'];
        else
            $name = $data['retailer'];

        return $this->view('mail.pickup_time_location', compact('data', 'number', 'name'));
    }
    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         markdown: 'mail.pickup_time_location',
    //     );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
