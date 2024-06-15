<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ItemYouLike extends Notification implements ShouldQueue
{
    use Queueable;
    protected $data, $user;
    /**
     * Create a new notification instance.
     */
    public function __construct($user, $product)
    {
        $this->data = $product;
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
        $user = $this->user;
        $product = $this->data;
        // dd("check", $product['0']['product']);
        return (new MailMessage)->subject('Warning: hotness enclosed')->markdown('mail.customer.item_you_like', compact('user', 'product'));
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
