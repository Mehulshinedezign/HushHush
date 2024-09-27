<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerificationEmail extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $data, $otp;
    public function __construct($user, $otp)
    {
        $this->data = $user;
        $this->otp = $otp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $user = $this->data;
        $otp = $this->otp;

        // return (new MailMessage)->subject('Verify your email')->markdown('mail.email_verification', compact('user', 'otp'));
        return (new MailMessage)
        ->subject('Verify your email')
        ->greeting('Hello,' . $user->name . ',')
        ->line('Welcome to Nudora.')
        ->line('Please verify your email below to join the party.')
        ->line('This otp will expire in 15 minutes'. '!')
        ->line('Your OTP: ' . $otp);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
   
}
