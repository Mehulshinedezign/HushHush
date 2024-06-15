<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LenderReceivebookingReq extends Notification implements ShouldQueue
{
    use Queueable;
    protected $data, $user;
    /**
     * Create a new notification instance.
     */
    public function __construct($emaildata, $user)
    {
        $this->data = $emaildata;
        $this->user = $user;
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
        $data = $this->data;
        $user = $this->user;
        $lender = $data['product']['retailer'];

        // dd('dgnneerkberbrebrebnerb', $user, $data);
        return (new MailMessage)->subject('Chere Request')->markdown('mail.customer.lender_receive_booking_req', compact('user', 'data', 'lender'));
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
