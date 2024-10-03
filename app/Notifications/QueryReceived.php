<?php
namespace App\Notifications;

use App\Events\NewNotificationEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class QueryReceived extends Notification
{
    use Queueable;

    protected $product_date;

    /**
     * Create a new notification instance.
     *
     * @param array $product_date
     * @return void
     */
    public function __construct($product_date)
    {
        $this->product_date = $product_date;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database']; // or 'database', 'nexmo', etc.
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New Product Query Received')
                    ->line('You have received a new query for a product.')
                    ->line('Customer Name: ' . $this->product_date['customer_name'])
                    ->line('Selected Date: ' . $this->product_date['date'])
                    ->line('Message for product: '.$this->product_date['query_message'])
                    ->action('View Query', route('receive_query'))
                    ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {

        $message = 'You have a new notification!';

        // Trigger the event
        event(new NewNotificationEvent($message));

        return [
            'id' => $this->product_date['id'],
            'message' => 'You have received a new query for a product.'.'Customer Name: ' . $this->product_date['customer_name'] .'Selected Date: ' . $this->product_date['date']. 'Message for product: '.$this->product_date['query_message'] ,
            'user_type' => 'lender',
            'notification_type' => 'query',
            'url' => route('receive_query') . ('?status=PENDING'),
        ];
    }


}
