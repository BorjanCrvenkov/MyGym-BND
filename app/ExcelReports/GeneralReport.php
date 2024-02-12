<?php

namespace App\ExcelReports;

use App\Enums\ExpenseStatusEnum;
use App\Enums\ReportTypeEnum;
use App\Models\Expense;
use App\Models\Membership;
use App\Models\Report;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GeneralReport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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

        $reportedProblemsCount = $this->getReportedProblemsCount();
        $resolvedProblemsCount = $this->getResolvedReportedProblemsCount();
        $remainingProblemsCount = $reportedProblemsCount - $resolvedProblemsCount;

        $expensesCount = $this->getExpensesCount();
        $paidExpensesCount = $this->getExpensesCount(true);
        $remainingExpensesCount = $expensesCount - $paidExpensesCount;

        $totalPaidExpensesCost =$this->getTotalExpensesCosts(true);
        $totalNotPaidExpensesCost = $this->getTotalExpensesCosts(false);
        $totalExpensesCost = $totalPaidExpensesCost + $totalNotPaidExpensesCost;

        $data->push([
            '' . $this->getTotalMembershipCount(),
            number_format($this->getAverageDailySessions(), 2),
            '' . $reportedProblemsCount,
            '' . $resolvedProblemsCount,
            '' . $remainingProblemsCount,
            '' . $expensesCount,
            '' . $paidExpensesCount,
            '' . $remainingExpensesCount,
            '$' . $totalPaidExpensesCost,
            '$' . $totalNotPaidExpensesCost,
            '$' . $totalExpensesCost,
            '$' . $this->getTotalMembershipRevenue(),
        ]);

        return $data;
    }

    /**
     * @return int
     */
    public function getTotalMembershipCount(): int
    {
        return Membership::query()
            ->join('membership_types', 'membership_types.id', '=', 'memberships.membership_type_id')
            ->whereBetween('memberships.created_at', [$this->startDate, $this->endDate])
            ->where('memberships.gym_id', '=', $this->gymId)
            ->count();
    }

    /**
     * @return int|float
     */
    public function getAverageDailySessions(): int|float
    {
        $daysBetweenStartAndEndDateCount = $this->startDate->diffInDays($this->endDate);

        return Session::query()
            ->join('memberships', 'memberships.id', '=', 'sessions.membership_id')
            ->whereBetween('sessions.created_at', [$this->startDate, $this->endDate])
            ->whereBetween('sessions.time_end', [$this->startDate, $this->endDate])
            ->where('memberships.gym_id', '=', $this->gymId)
            ->count() / $daysBetweenStartAndEndDateCount;
    }

    /**
     * @return int
     */
    public function getReportedProblemsCount(): int
    {
        return Report::query()
            ->where('model_type', '=', ReportTypeEnum::GYM_PROBLEM->value)
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('model_id', '=', $this->gymId)
            ->count();
    }

    /**
     * @return int
     */
    public function getResolvedReportedProblemsCount(): int
    {
        return Report::query()
            ->where('model_type', '=', ReportTypeEnum::GYM_PROBLEM->value)
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('model_id', '=', $this->gymId)
            ->whereNotNull('deleted_at')
            ->count();
    }

    /**
     * @param bool $paid
     * @return int
     */
    public function getExpensesCount(?bool $paid = null): int
    {
        return Expense::query()
            ->join('expense_types', 'expense_types.id', '=', 'expenses.expense_type_id')
            ->whereBetween('expenses.created_at', [$this->startDate, $this->endDate])
            ->where('expense_types.gym_id', '=', $this->gymId)
            ->when($paid, function ($query) use ($paid) {
                $status = $paid ? ExpenseStatusEnum::PAID->value : ExpenseStatusEnum::NOT_PAID->value;

                $query->where('expenses.status', '=', $status);
            })
            ->count();
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

    public function headings(): array
    {
        return [
            'New Memberships Count',
            'Average Daily Sessions',
            'Reported Problems Count',
            'Resolved Problems Count',
            'Remaining Problems Count',
            'Expenses Count',
            'Paid Expenses Count',
            'Not Paid Expenses Count',
            'Paid Expenses Total Cost',
            'Not Paid Expenses Total Cost',
            'Expenses Total Cost',
            'Total Income'
        ];
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
