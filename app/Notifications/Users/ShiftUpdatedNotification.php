<?php

namespace App\Notifications\Users;

use App\Models\WorkingTime;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShiftUpdatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public WorkingTime $workingTime)
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
        $employee = $this->workingTime->working_schedule->user;

        $token = $employee->createToken('MyGym')->plainTextToken;
        $url = config('urls.frontend_profile_page_url') . "?token={$token}";

        $finalUrl = str_replace('{user_id}', $employee->getKey(), $url);

        $workingTimeDate = Carbon::parse($this->workingTime->start_time)->format('Y-m-d H:i:s');

        return (new MailMessage)
            ->subject('Shift Updated')
            ->line("Your boss updated your shift, here is the starting time{$workingTimeDate}!")
            ->action('Click here to view all your shifts', $finalUrl);
    }
}
