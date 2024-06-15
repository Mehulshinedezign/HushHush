<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerExperience extends Notification implements ShouldQueue
{
    use Queueable;
    protected $user, $data;
    /**
     * Create a new notification instance.
     */
    public function __construct($user, $product)
    {
        $this->user = $user;
        $this->data = $product;
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
        $product = $this->data;

        return (new MailMessage)->subject("How'd it go?")->markdown('mail.customer.customer_experience', compact('user', 'product'));
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
