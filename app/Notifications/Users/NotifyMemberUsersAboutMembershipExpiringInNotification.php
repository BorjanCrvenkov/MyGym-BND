<?php

namespace App\Notifications\Users;

use App\Models\Membership;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyMemberUsersAboutMembershipExpiringInNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Membership $membership, public bool $expiresInAWeek)
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
        $membership = $this->membership;
        $membershipTypeName = $membership->membership_type->name;
        $membershipUser = $membership->user;
        $membershipGym = $membership->gym;

        $expiresIn = $this->expiresInAWeek ? 'week' : 'day';

        $token = $membershipUser->createToken('MyGym')->plainTextToken;
        $url = config('urls.frontend_business_problems_url') . "&token={$token}";

        $finalUrl = str_replace('{gym_id}', $membershipGym->getKey(), $url);

        return (new MailMessage)
            ->subject('Membership Expiring')
            ->line("Your membership {$membershipTypeName} expires in a {$expiresIn}")
            ->action('Click here to view your gyms memberships', $finalUrl);
    }
}
