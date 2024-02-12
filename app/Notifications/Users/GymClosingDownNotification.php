<?php

namespace App\Notifications\Users;

use App\Models\Gym;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GymClosingDownNotification  extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Gym $gym, public User $user)
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
        $token = $this->user->createToken('MyGym')->plainTextToken;
        $url = config('urls.frontend_member_main_page_url') . "?token={$token}";

        $finalUrl = str_replace('{gym_id}', $this->gym->getKey(), $url);

        $gym = $this->gym;
        $shutdownDate = Carbon::parse($gym->shutdown_date)->format('Y-m-d');

        return (new MailMessage)
            ->subject("Gym Shutting Down")
            ->line("{$gym->name} is shutting down on {$shutdownDate}, if you have any memberships that are active after this date, they will be refunded")
            ->action('Click here to browse other gyms', $finalUrl);
    }
}
