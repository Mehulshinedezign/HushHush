<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Payment extends Notification implements ShouldQueue
{
    use Queueable;
    protected $data, $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($emaildata, $retailer)
    {

        $this->data = $emaildata;
        $this->user = $retailer;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $data = $this->data;
        $user = $this->user;
        return (new MailMessage)->subject('Side Hustle Loading')->markdown('mail.customer.payment', compact('data', 'user'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
