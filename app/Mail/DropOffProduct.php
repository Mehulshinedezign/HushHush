<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DropOffProduct extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $data, $number;
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
                subject: 'Parting is such sweet sorrow',
            );
        } else {
            return new Envelope(
                subject: 'Chere Reminder',
            );
        }
    }

    /**
     * Get the message content definition.
     */
    public function build()
    {
        $data = $this->data;
        $number = $this->number;


        if ($number == 1)
            $name = $data['name'];
        else
            $name = $data['retailer'];

        return $this->view('mail.dropoffproduct', compact('data', 'number', 'name'));
    }

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
