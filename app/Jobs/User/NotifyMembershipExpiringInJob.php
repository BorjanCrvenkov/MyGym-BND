<?php

namespace App\Jobs\User;

use App\Models\Membership;
use App\Notifications\Users\NotifyMemberUsersAboutMembershipExpiringInNotification;
use App\Queries\Membership\MembershipExpiringInADayQuery;
use App\Queries\Membership\MembershipExpiringInAWeekQuery;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyMembershipExpiringInJob implements ShouldQueue
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
        $memberships = $this->expiresInAWeek ? (new MembershipExpiringInAWeekQuery())->get() : (new MembershipExpiringInADayQuery())->get();

        foreach ($memberships as $membership){
            $membership->user->notify(new NotifyMemberUsersAboutMembershipExpiringInNotification($membership, $this->expiresInAWeek));
        }
    }
}
