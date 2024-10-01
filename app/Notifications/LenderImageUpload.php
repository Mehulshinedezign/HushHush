<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LenderImageUpload extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

    protected $customer_name , $data;
    public function __construct($customer_name , $data)
    {
        $this->customer_name = $customer_name;
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
                    ->line('Dear Retailer')
                    ->line('Product Name: '. $data->product->name)
                    ->line('Order Id: '. $data->id)
                    ->line($this->customer_name .'is upload the image for pick up a product Please verify.')
                    ->action('Receive order', route('retailercustomer'))
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
