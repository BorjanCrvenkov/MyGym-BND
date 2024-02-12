<?php

namespace App\Console;

use App\Console\Commands\CreateRecurringExpensesCommand;
use App\Console\Commands\NotifyMembershipExpiringInADayCommand;
use App\Console\Commands\NotifyMembershipExpiringInAWeekCommand;
use App\Console\Commands\NotifySubscriptionExpiringInADayCommand;
use App\Console\Commands\NotifySubscriptionExpiringInAWeekCommand;
use App\Console\Commands\ShutDownGymCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * @var string[]
     */
    protected $commands = [
        NotifySubscriptionExpiringInAWeekCommand::class,
        NotifySubscriptionExpiringInADayCommand::class,
        NotifyMembershipExpiringInAWeekCommand::class,
        NotifyMembershipExpiringInADayCommand::class,
        CreateRecurringExpensesCommand::class,
        ShutDownGymCommand::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('notify:subscription-expiring-in-a-week-command')->weekly();
        $schedule->command('notify:subscription-expiring-in-a-day-command')->daily();
        $schedule->command('notify:membership-expiring-in-a-week-command')->weekly();
        $schedule->command('notify:membership-expiring-in-a-day-command')->daily();
        $schedule->command('create:recurring-expense-command')->daily();
        $schedule->command('gym:shut-down-command')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
