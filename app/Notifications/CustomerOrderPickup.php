<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerOrderPickup extends Notification
{
    use Queueable;

    protected $customer_info;

    /**
     * Create a new notification instance.
     */
    public function __construct($customer_info)
    {
        $this->customer_info = $customer_info;
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
            ->subject('Your Order is Ready for Pickup!')
            ->greeting('Dear ' . $this->customer_info['user_name'] . ',')
            ->line('Product Name ' . $this->customer_info['product_name'])
            ->line('order_id ' . $this->customer_info['order_id'])
            ->line('From date ' . $this->customer_info['from_date'])
            ->line('To date ' . $this->customer_info['to_date'])
            ->line('We are excited to inform you that your order is now ready for pickup at ' . $this->customer_info['pickup_location'] . '!')
            ->line('Thank you for shopping with us! If you have any questions or need further assistance, please do not hesitate to contact us.')
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
            'id' => $this->customer_info['order_id'],
            'message' => 'Dear ' . $this->customer_info['user_name'] . ', your order for product ' . $this->customer_info['product_name'] . ' is ready for pickup from ' . $this->customer_info['from_date'] . ' to ' . $this->customer_info['to_date'] . ' at ' . $this->customer_info['pickup_location'] . '.',
            'user_type' => 'Customer',
            'notification_type' => 'order_pickup',
            'url' => route('orders') // Include the route to the order
        ];
    }
}
