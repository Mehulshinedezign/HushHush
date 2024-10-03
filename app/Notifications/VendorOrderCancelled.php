<?php

namespace App\Notifications;

use App\Events\NewNotificationEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorOrderCancelled extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     *
     * @param $order
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Order Cancelled by Lender')
            ->line('Your order for ' . $this->order->product->name . ' has been cancelled by the lender.')
            ->line('Order ID: ' . $this->order->id)
            ->line('Cancelled Date: ' . $this->order->cancelled_date)
            ->line('Cancellation Note: ' . $this->order->cancellation_note)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification for database.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $message = 'You have a new notification!';

        // Trigger the event
        event(new NewNotificationEvent($message));
        return [
            'message' => 'Your order for product ' . $this->order->product->name . ' has been cancelled by the lender.',
            'order_id' => $this->order->id,
            'url' => route('retailercustomer', ['tab' => 'cancelled']),
        ];
    }
}
