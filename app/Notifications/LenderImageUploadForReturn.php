<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LenderImageUploadForReturn extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $customer_name , $order;
    public function __construct($customer_name , $order)
    {
        $this->customer_name = $customer_name;
        $this->order = $order;
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
        $order = $this->order;

        return (new MailMessage)
                    ->line('Dear Retailer')
                    ->line('Product Name: '. $order->product->name)
                    ->line('Order Id: '. $order->id)
                    ->line($this->customer_name .' is upload the image for Return a product please verify.')
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
