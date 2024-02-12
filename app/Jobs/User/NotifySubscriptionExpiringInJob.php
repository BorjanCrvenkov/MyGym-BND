<?php

namespace App\Jobs\User;

use App\Notifications\Users\NotifyBusinessUsersAboutSubscriptionExpiringNotification;
use App\Queries\User\UsersWithExpiringSubscriptionsInADayQuery;
use App\Queries\User\UsersWithExpiringSubscriptionsInAWeekQuery;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifySubscriptionExpiringInJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public bool $expiresInAWeek)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = $this->expiresInAWeek ? (new UsersWithExpiringSubscriptionsInAWeekQuery())->get() : (new UsersWithExpiringSubscriptionsInADayQuery())->get();

        foreach ($users as $user){
            $user->notify(new NotifyBusinessUsersAboutSubscriptionExpiringNotification($user, $this->expiresInAWeek));
        }
    }
}
