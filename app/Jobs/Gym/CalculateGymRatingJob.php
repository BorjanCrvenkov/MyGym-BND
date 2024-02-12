<?php

namespace App\Jobs\Gym;

use App\Enums\ReviewTypeEnum;
use App\Models\Gym;
use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalculateGymRatingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $gymId)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Gym::query()
            ->find($this->gymId)
            ->update([
                'rating' => round(Review::query()
                    ->where('model_id', '=', $this->gymId)
                    ->where('model_type', '=', ReviewTypeEnum::GYM_REVIEW->value)
                    ->avg('rating'), 2),
            ]);
    }
}
