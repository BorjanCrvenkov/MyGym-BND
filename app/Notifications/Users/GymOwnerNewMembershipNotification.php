<?php

namespace App\Notifications\Users;

use App\Models\MembershipType;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GymOwnerNewMembershipNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $member, public MembershipType $membershipType)
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
        return (new MailMessage)
            ->subject("New membership")
            ->line("{$this->member->full_name} has bought the {$this->membershipType->name} membership")
            ->action('Click here to view all members', '');
    }
}
