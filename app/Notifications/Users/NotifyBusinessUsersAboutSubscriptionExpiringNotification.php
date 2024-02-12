<?php

namespace App\Notifications\Users;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyBusinessUsersAboutSubscriptionExpiringNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $user, public bool $expiresInAWeek)
    {
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
        $planName = $this->user->plan->name;
        $expiresIn = $this->expiresInAWeek ? 'week' : 'day';

        $token = $this->user->createToken('MyGym')->plainTextToken;
        $url = config('urls.frontend_business_plans_url') . "&token={$token}";

        return (new MailMessage)
            ->subject('Subscription Expiring')
            ->line("Your subscription to {$planName} expires in a {$expiresIn}")
            ->action('Click here to view available plans', $url);
    }
}
