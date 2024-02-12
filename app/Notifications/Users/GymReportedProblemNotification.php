<?php

namespace App\Notifications\Users;

use App\Models\Gym;
use App\Models\Report;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GymReportedProblemNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Report $problem, public Gym $gym)
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
        $employee = User::query()->find($this->problem->reporter_id);

        $token = $this->gym->owner->createToken('MyGym')->plainTextToken;
        $url = config('urls.frontend_member_main_page_url') . "&token={$token}";

        return (new MailMessage)
            ->subject("Problem reported at {$this->gym->name}")
            ->line("{$employee->full_name} reported a problem: {$this->problem->heading}")
            ->action('Click here to view all problems', $url);
    }
}
