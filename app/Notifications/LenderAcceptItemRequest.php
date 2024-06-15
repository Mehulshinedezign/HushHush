<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LenderAcceptItemRequest extends Notification implements ShouldQueue
{
    use Queueable;
    protected $data, $user;
    /**
     * Create a new notification instance.
     */
    public function __construct($emailData, $user)
    {
        $this->data = $emailData;
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
        $data  = $this->data;
        $user  = $this->user;
        return (new MailMessage)->subject('Chere Confirmation')->markdown('mail.customer.lender_accept_item_request', compact('data', 'user'));
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
