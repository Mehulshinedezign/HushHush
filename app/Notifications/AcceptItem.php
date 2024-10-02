<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Events\NewNotificationEvent;

class AcceptItem extends Notification
{
    use Queueable;

    protected $userId, $data;

    public function __construct($userId, $data)
    {
        $this->userId = $userId;
        $this->data = $data;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $data = $this->data;
        return (new MailMessage)
            ->subject('Product Inquiry Accepted')
            ->line('Your Inquiry is accepted.')
            ->line('Product Name: ' . $data->product->name)
            ->line('Date: ' . $data->date_range)
            ->action('View Inquiry', route('my_query') . ('?status=ACCEPTED'))
            ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        $data = $this->data;
        $message = 'You have a new notification!';

        // Trigger the event
        event(new NewNotificationEvent($message));

        return [
            'id' => $data->id,
            'message' => 'Your query was accepted by the retailer',
            'user_type' => 'borrower',
            'notification_type' => 'query',
            'url' => route('my_query') . ('?status=ACCEPTED'),
        ];
    }
}
