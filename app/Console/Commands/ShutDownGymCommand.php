<?php

namespace App\Console\Commands;

use App\Jobs\Gym\GymShutsDownJob;
use App\Models\Gym;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class ShutDownGymCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gym:shut-down-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shut down gym command';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $gyms = $this->getGymToBeShutDown();

        foreach ($gyms as $gym){
            GymShutsDownJob::dispatch($gym, $gym->shutdown_date);

            $gym->update([
                'shutdown' => true,
            ]);
        }
    }

    /**
     * @return Collection|array
     */
    public function getGymToBeShutDown(): Collection|array
    {
        return Gym::query()
            ->where('shutdown', '=', false)
            ->where('shutdown_date', '=', now()->toDateString())
            ->get();
    }
}
