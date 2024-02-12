<?php

namespace App\Console\Commands;

use App\Jobs\User\NotifySubscriptionExpiringInJob;
use Illuminate\Console\Command;

class NotifySubscriptionExpiringInADayCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:subscription-expiring-in-a-day-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification for subscription expiring in a day';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        NotifySubscriptionExpiringInJob::dispatch(false);
    }
}
