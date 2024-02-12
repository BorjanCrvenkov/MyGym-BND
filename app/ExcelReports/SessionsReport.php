<?php

namespace App\ExcelReports;

use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SessionsReport implements FromCollection, ShouldAutoSize, WithStyles
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

        $headings = $this->getSheetHeadings();
        $data->push($headings);

        for ($hour = 0; $hour < 24; $hour++) {
            $hourlyData = $this->getHourlyData($hour);
            $data->push($hourlyData);
        }

        return $data;
    }

    /**
     * @return string[]
     */
    public function getSheetHeadings(): array
    {
        $headings = ['Time/Date'];

        $currentDate = $this->startDate->copy();
        while ($currentDate <= $this->endDate) {
            $headings[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        return $headings;
    }

    /**
     * @param int $hour
     * @return array
     */
    public function getHourlyData(int $hour): array
    {
        $hourlyData = [$this->startDate->copy()->addHours($hour)->format('H:i')];

        $currentDate = $this->startDate->copy()->addHours($hour);
        while ($currentDate <= $this->endDate) {
            $endOfHour = $currentDate->copy()->addHour();
            $sessionsCount = $this->getSessionsCount($currentDate, $endOfHour);
            $hourlyData[] = $sessionsCount == 0 ? '/' : (string)$sessionsCount;
            $currentDate->addDay();
        }

        return $hourlyData;
    }

    /**
     * @param $start
     * @param $end
     * @return int
     */
    public function getSessionsCount($start, $end): int
    {
        return Session::query()
            ->select('sessions.id')
            ->join('memberships', 'memberships.id', '=', 'sessions.membership_id')
            ->where('sessions.created_at', '>=', $start)
            ->where('sessions.created_at', '<=', $end)
            ->where('memberships.gym_id', '=', $this->gymId)
            ->count();
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
