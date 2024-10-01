<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerImageUploadForReturn extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $lender_name ,$order;
    public function __construct($lender_name , $order)
    {
        $this->lender_name = $lender_name;
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
        $lender_name = $this->lender_name;
        $order = $this->order;
        return (new MailMessage)
        ->line('Dear Customer')
        ->line('Product Name: '. $order->product->name)
        ->line('Order Id: ' . $order->id)
        ->line('Retailer '.$lender_name .'is upload the image of product for Return please verify the product.')
        ->action('Receive order', route('orders'))
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
