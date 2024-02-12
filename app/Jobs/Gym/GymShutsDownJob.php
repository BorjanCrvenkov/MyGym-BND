<?php

namespace App\Jobs\Gym;

use App\Jobs\Membership\RefundMembershipsJob;
use App\Jobs\User\NotifyGymClosingDownJob;
use App\Models\Gym;
use App\Models\Membership;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GymShutsDownJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public const DAYS_IN_A_MONTH = 30;

    /**
     * Create a new job instance.
     */
    public function __construct(public Gym $gym, public Carbon $shutdownDate)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if($this->gym->shutdown){
            return;
        }

        if($this->shutdownDate->diffInDays(now()) <= self::DAYS_IN_A_MONTH && $this->gymHasActiveMembershipsPastShutdownDate()){
            RefundMembershipsJob::dispatch($this->gym, $this->shutdownDate);
        }

        NotifyGymClosingDownJob::dispatch($this->gym);
    }

    /**
     * @return bool
     */
    public function gymHasActiveMembershipsPastShutdownDate(): bool
    {
        return Membership::query()
            ->where('gym_id', '=', $this->gym->getKey())
            ->where('end_date', '>=', $this->shutdownDate)
            ->exists();
    }
}
