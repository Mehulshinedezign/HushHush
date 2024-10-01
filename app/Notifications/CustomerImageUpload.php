<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerImageUpload extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $lender_name , $data;
    public function __construct($lender_name , $data)
    {
        $this->lender_name = $lender_name;
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
        $lender_name = $this->lender_name;
        $data = $this->data;
        
        return (new MailMessage)
                    ->line('Dear customer.')
                    ->line('Product Name: '. $data->product->name)
                    ->line('Order Id: '. $data->id)
                    ->line('Retailer '.$lender_name .'is upload the image of product for pickup please verify the product.')
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
