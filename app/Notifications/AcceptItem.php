<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AcceptItem extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

     protected $userId ,$data;
    public function __construct($userId , $data)
    {
        $this->userId = $userId;
        $this->data = $data;
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
        return (new MailMessage)
                    ->subject('Product Inquiry  Accepted')
                    ->line('Your Inquiry is accepted .')
                    ->line('Product Name: '.$data->product->name)
                    ->line('Date: '.$data->date_range)
                    ->action('View Inquiry ', route('my_query'). ('?status=ACCEPTED'))
                    ->line('Thank you for using our application!');
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
