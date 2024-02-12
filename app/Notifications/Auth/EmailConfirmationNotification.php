<?php

namespace App\Notifications\Auth;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class EmailConfirmationNotification extends Notification
{
    /**
     * @param $notifiable
     * @return string[]
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * @param $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        $confirmationUrl = URL::signedRoute('email.confirmation', ['user' => $notifiable]);

        return (new MailMessage)
            ->subject('Confirm Your Email')
            ->line('Please click the link below to confirm your email address.')
            ->action('Confirm Email', $confirmationUrl)
            ->line('If you did not create an account, no further action is required.');
    }
}
