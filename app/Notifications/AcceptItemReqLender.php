<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AcceptItemReqLender extends Notification implements ShouldQueue
{
    use Queueable;
    protected $data, $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($emaildata, $user)
    {
        $this->data = $emaildata;
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
        $data = $this->data;
        $user = $this->user;
        $retaileruser = $data['product']['retailer'];

        return (new MailMessage)->subject('Chere Confirmation')->markdown('mail.customer.accept_item_by_lender', compact('user', 'data', 'retaileruser'));
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
