<?php

namespace App\ExcelReports;

use App\Enums\ExpenseStatusEnum;
use App\Models\Expense;
use App\Models\Membership;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FinanceReport implements FromCollection, ShouldAutoSize, WithStyles
{
    /**
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param int $gymId
     */
    public function __construct(public Carbon $startDate, public Carbon $endDate, public int $gymId)
    {
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        $data = new Collection();

        $totalMembershipRevenue = $this->getTotalMembershipRevenue();
        $totalPaidExpenses = $this->getTotalExpensesCosts(true);
        $totalNotPaidExpenses = $this->getTotalExpensesCosts(false);

        $netIncomeBeforePayingAllExpenses = $totalMembershipRevenue - $totalPaidExpenses;
        $netIncomeAfterPayingAllExpenses = $totalMembershipRevenue - ($totalPaidExpenses + $totalNotPaidExpenses);

        $data->push(['Memberships Income', '$' . $totalMembershipRevenue]);
        $data->push(['Paid Expenses', '$' . $totalPaidExpenses]);
        $data->push(['Not Paid Expenses', '$' . $totalNotPaidExpenses]);
        $data->push(['Net Income After Currently Paid Expenses', '$' . $netIncomeBeforePayingAllExpenses]);
        $data->push(['Net Income After All Paid Expenses', '$' . $netIncomeAfterPayingAllExpenses]);

        return $data;
    }

    /**
     * @return mixed
     */
    public function getTotalMembershipRevenue(): mixed
    {
        return Membership::query()
            ->join('membership_types', 'membership_types.id', '=', 'memberships.membership_type_id')
            ->whereBetween('memberships.created_at', [$this->startDate, $this->endDate])
            ->where('memberships.gym_id', '=', $this->gymId)
            ->sum('price');
    }

    /**
     * @param bool $paid
     * @return int|mixed
     */
    public function getTotalExpensesCosts(bool $paid): mixed
    {
        $status = $paid ? ExpenseStatusEnum::PAID->value : ExpenseStatusEnum::NOT_PAID->value;

        return Expense::query()
            ->join('expense_types', 'expense_types.id', '=', 'expenses.expense_type_id')
            ->whereBetween('expenses.created_at', [$this->startDate, $this->endDate])
            ->where('expense_types.gym_id', '=', $this->gymId)
            ->where('expenses.status', '=', $status)
            ->sum('cost');
    }

    /**
     * @param Worksheet $sheet
     * @return void
     */
    public function styles(Worksheet $sheet): void
    {
        $sheet->getDefaultRowDimension()->setRowHeight(15);
    }
}
