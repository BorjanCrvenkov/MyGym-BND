<?php

namespace App\Services;

use App\ExcelReports\FinanceReport;
use App\ExcelReports\GeneralReport;
use App\ExcelReports\MembershipTypesBoughtReport;
use App\ExcelReports\SessionsReport;
use App\Models\Gym;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExcelReportsService
{
    /**
     * @param array $data
     * @return array
     */
    public function resolveStartEndDatesAndGymId(array $data): array
    {
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);
        $gymId = $data['gym_id'];

        return [
            $startDate,
            $endDate,
            $gymId
        ];
    }
    /**
     * @param array $data
     * @return BinaryFileResponse
     */
    public function sessionsReport(array $data): BinaryFileResponse
    {
        [$startDate, $endDate, $gymId] = $this->resolveStartEndDatesAndGymId($data);

        $gym = Gym::query()->find($gymId);

        $fileName = "{$gym->name} ({$startDate->toDateString()} - {$endDate->toDateString()}) Sessions Report." . \Maatwebsite\Excel\Excel::XLS;

        return Excel::download(new SessionsReport($startDate, $endDate, $gymId), $fileName);
    }

    /**
     * @param array $data
     * @return BinaryFileResponse
     */
    public function membershipTypesBoughtReport(array $data): BinaryFileResponse
    {
        [$startDate, $endDate, $gymId] = $this->resolveStartEndDatesAndGymId($data);

        $gym = Gym::query()->find($gymId);

        $fileName = "{$gym->name} ({$startDate->toDateString()} - {$endDate->toDateString()}) Memberships Bought Report." . \Maatwebsite\Excel\Excel::XLS;

        return Excel::download(new MembershipTypesBoughtReport($startDate, $endDate, $gymId), $fileName);
    }

    /**
     * @param array $data
     * @return BinaryFileResponse
     */
    public function financeReport(array $data): BinaryFileResponse
    {
        [$startDate, $endDate, $gymId] = $this->resolveStartEndDatesAndGymId($data);

        $gym = Gym::query()->find($gymId);

        $fileName = "{$gym->name} ({$startDate->toDateString()} - {$endDate->toDateString()}) Finance Report." . \Maatwebsite\Excel\Excel::XLS;

        return Excel::download(new FinanceReport($startDate, $endDate, $gymId), $fileName);
    }

    /**
     * @param array $data
     * @return BinaryFileResponse
     */
    public function generalReport(array $data): BinaryFileResponse
    {
        [$startDate, $endDate, $gymId] = $this->resolveStartEndDatesAndGymId($data);

        $gym = Gym::query()->find($gymId);

        $fileName = "{$gym->name} ({$startDate->toDateString()} - {$endDate->toDateString()}) General Report." . \Maatwebsite\Excel\Excel::XLS;

        return Excel::download(new GeneralReport($startDate, $endDate, $gymId), $fileName);
    }
}
