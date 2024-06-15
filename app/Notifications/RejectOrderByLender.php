<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RejectOrderByLender extends Notification implements ShouldQueue
{
    use Queueable;
    // protected $data;
    protected $user;
    /**
     * Create a new notification instance.
     */
    public function __construct($user)
    {
        // $this->data = $order;
        $this->user = $user;
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
        // $data = $this->data;
        $user = $this->user;
        return (new MailMessage)->subject('No sweat')->markdown('mail.customer.rejectorderbylender', compact('user'));
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
