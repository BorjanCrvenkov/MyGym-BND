<?php

namespace App\Console\Commands;

use App\Jobs\User\NotifyMembershipExpiringInJob;
use Illuminate\Console\Command;

class NotifyMembershipExpiringInAWeekCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:membership-expiring-in-a-week-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification for membership expiring in a week';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        NotifyMembershipExpiringInJob::dispatch(true);
    }
}
