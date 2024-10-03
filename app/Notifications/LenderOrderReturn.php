<?php

namespace App\Notifications;

use App\Events\NewNotificationEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LenderOrderReturn extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $lender_info;
    public function __construct($lender_info)
    {
        $this->lender_info = $lender_info;
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
        return (new MailMessage)
        ->subject('Your Order is Ready for Return!')
        ->greeting('Dear Retailer ' . $this->lender_info['lender_name'] . ',')
        ->line('Product Name: '.$this->lender_info['product_name'])
        ->line('Order Id: '.$this->lender_info['order_id'])
        ->line('From Date: '.$this->lender_info['from_date'])
        ->line('To Date: '.$this->lender_info['to_date'])
        ->line('We are excited to inform you that your order is now ready for return at ' . $this->lender_info['pickup_location'] . '!')
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
        $message = 'You have a new notification!';

        // Trigger the event
        event(new NewNotificationEvent($message));

    return [
        'lender_name' => $this->lender_info['lender_name'],
        'product_name' => $this->lender_info['product_name'],
        'order_id' => $this->lender_info['order_id'],
        'from_date' => $this->lender_info['from_date'],
        'to_date' => $this->lender_info['to_date'],
        'pickup_location' => $this->lender_info['pickup_location'],
        'message' => 'Dear Retailer ' . $this->lender_info['lender_name'] . ', your order with ID ' . $this->lender_info['order_id'] . ' is ready for return from ' . $this->lender_info['from_date'] . ' to ' . $this->lender_info['to_date'] . ' at ' . $this->lender_info['pickup_location'] . '.',
        'notification_type' => 'order',
        'url' => route('orders') // Link to the order return page
    ];
    }
}
