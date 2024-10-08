<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class IdentityVerificationStatus extends Notification implements ShouldQueue
{
    use Queueable;

    protected $status;

    /**
     * Create a new notification instance.
     *
     * @param string $status
     */
    public function __construct($status)
    {
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $statusMessage = '';

        switch ($this->status) {
            case 'verified':
                $statusMessage = 'Your identity has been successfully verified. Proceed with adding bank account';
                break;
            case 'failed':
                $statusMessage = 'Your identity verification has failed. Please try again.';
                break;
            case 'canceled':
                $statusMessage = 'Your identity verification session has been canceled.';
                break;
            case 'pending':
                $statusMessage = 'Your identity verification is still processing.';
                break;
        }

        return (new MailMessage)
                    ->subject('Identity Verification Status Update')
                    ->line($statusMessage)
                    // ->action('Check your account', url('/'))
                    ->line('Thank you for using our service!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {


        return [
            'status' => $this->status,
        ];
    }
}
