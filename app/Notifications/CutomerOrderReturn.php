<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CutomerOrderReturn extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $customer_info;
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Your Order is Ready for Return!')
                    ->greeting('Dear ' . $this->customer_info['user_name'] . ',')
                    ->line('from date '.$this->customer_info['from_date'])
                    ->line('to date'.$this->customer_info['to_date'])
                    ->line('We are excited to inform you that your order is now ready for return at ' . $this->customer_info['pickup_location'] . '!')
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
            //
        ];
    }
}
