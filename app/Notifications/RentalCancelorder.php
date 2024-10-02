<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RentalCancelorder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order_info;

    /**
     * Create a new notification instance.
     */
    public function __construct($order_info)
    {
        $this->order_info = $order_info;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Order Canceled by Customer')
            ->line('Your order has been canceled by the customer.')
            ->line('Product Name: ' . $this->order_info['product_name'])
            ->line('Cancellation Reason: ' . $this->order_info['cancellation_note'])
            ->action('View Order', route('retailercustomer'))
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
            'id' => $this->order_info['id'],
            'message' => 'Your order for the product ' . $this->order_info['product_name'] . ' has been canceled by the customer.',
            'user_type' => 'retailer',
            'notification_type' => 'order',
            'url' => route('retailercustomer'),
        ];
    }
}
