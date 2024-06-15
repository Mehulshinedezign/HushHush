<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LenderFirstMsg extends Notification implements ShouldQueue
{
    use Queueable;
    protected $user, $emaildata;
    /**
     * Create a new notification instance.
     */
    public function __construct($user, $emaildata)
    {
        $this->user = $user;
        $this->emaildata = $emaildata;
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
        $user = $this->user;
        $data = $this->emaildata;
        return (new MailMessage)->subject('Knock Knock')->markdown('mail.customer.lender_sends_first_msg', compact('user', 'data'));
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
