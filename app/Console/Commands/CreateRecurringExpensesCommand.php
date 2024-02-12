<?php

namespace App\Console\Commands;

use App\Jobs\Expense\CreateRecurringExpenseJob;
use Illuminate\Console\Command;

class CreateRecurringExpensesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:recurring-expense-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch a job for creating recurring expenses';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        CreateRecurringExpenseJob::dispatch();
    }
}
