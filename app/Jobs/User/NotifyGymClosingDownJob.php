<?php

namespace App\Jobs\User;

use App\Models\Gym;
use App\Models\Membership;
use App\Notifications\Users\GymClosingDownNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyGymClosingDownJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Gym $gym)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $activeMemberships = $this->getAllActiveGymMemberships();

        foreach ($activeMemberships as $membership){
            $user = $membership->user;
            $user->notify(new GymClosingDownNotification($this->gym, $user));
        }
    }

    /**
     * @return Collection|array
     */
    public function getAllActiveGymMemberships(): Collection|array
    {
        return Membership::query()
            ->where('gym_id', '=', $this->gym->getKey())
            ->where('end_date', '>', now()->toDateString())
            ->with('user')
            ->get();
    }
}
