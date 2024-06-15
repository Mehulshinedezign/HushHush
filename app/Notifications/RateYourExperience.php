<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RateYourExperience extends Notification implements ShouldQueue
{
    use Queueable;
    protected $data;
    /**
     * Create a new notification instance.
     */
    public function __construct($emaildata)
    {
        $this->data = $emaildata;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $data = $this->data;
        $user = auth()->user();

        return (new MailMessage)->subject("How'd it go?")->markdown('mail.customer.rate_your_experience', compact('data', 'user'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
