<?php

namespace App\Jobs\Membership;

use App\Models\Gym;
use App\Models\Membership;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Stripe\Refund;
use Stripe\Stripe;

class RefundMembershipsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Gym $gym, public Carbon $shutdownDate)
    {
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        Stripe::setApiKey(config('app.stripe_secret_key'));

        $activeMemberships = $this->getAllActiveMemberships();

        foreach ($activeMemberships as $membership) {
            try {
                Refund::create([
                    'charge' => $membership->charge_id,
                    'reason' => 'Gym is shutting down',
                    'description' => 'Gym is shutting down',
                ]);

                $membership->update([
                    'refunded' => true,
                ]);
            } catch (Exception $exception) {
                Log::alert($exception->getMessage());
                continue;
            }
        }
    }

    /**
     * @return Collection|array
     */
    public function getAllActiveMemberships(): Collection|array
    {
        return Membership::query()
            ->where('end_date', '>', $this->shutdownDate)
            ->get();
    }
}
