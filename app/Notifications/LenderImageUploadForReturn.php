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
    protected $customer_name, $order;
    public function __construct($customer_name, $order)
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
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $order = $this->order;

        return (new MailMessage)
            ->line('Dear Retailer')
            ->line('Product Name: ' . $order->product->name)
            ->line('Order Id: ' . $order->id)
            ->line($this->customer_name . ' is upload the image for Return a product please verify.')
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
        $order = $this->order;

        return [
            'id' => $order->id,
            'message' => 'Dear Retailer, ' . $this->customer_name . ' has uploaded the image for the return of the product ' . $order->product->name . '. Please verify the image for order ID ' . $order->id . '.',
            'user_type' => 'Retailer',
            'notification_type' => 'return_image_upload',
            'url' => route('retailercustomer') // URL for the retailer to verify the return order
        ];
    }
}
