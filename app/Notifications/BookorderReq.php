<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookorderReq extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $product_info;
    public function __construct($product_info)
    {
        $this->product_info = $product_info;
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
        return (new MailMessage)
                    ->subject('Product book')
                    ->line('The booking for your product is done.')
                    ->line('Customer Name: ' . $this->product_info['customer_name'])
                    ->line('From Date: ' . $this->product_info['from_date'])
                    ->line('To Date: ' . $this->product_info['to_date'])
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
