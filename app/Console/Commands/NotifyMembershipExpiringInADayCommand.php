<?php

namespace App\Console\Commands;

use App\Jobs\User\NotifyMembershipExpiringInJob;
use Illuminate\Console\Command;

class NotifyMembershipExpiringInADayCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:membership-expiring-in-a-day-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification for membership expiring in a day';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        NotifyMembershipExpiringInJob::dispatch(false);
    }
}
