<?php

namespace App\ExcelReports;

use App\Models\Membership;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MembershipTypesBoughtReport implements FromCollection, ShouldAutoSize, WithStyles
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

        $reportData = $this->getReportData();

        $headings = ['Membership Name'];
        $quantities = ['Memberships Bought'];
        $prices = ['Membership Prices'];
        $total = ['Total Revenue'];

        foreach ($reportData as $rd) {
            $headings[] = $rd->name;
            $quantities[] = $rd->count;
            $prices[] = '$' . $rd->price;
            $total[] = '$' . $rd->count * $rd->price;
        }

        $data->push($headings);
        $data->push($quantities);
        $data->push($prices);
        $data->push($total);

        return $data;
    }

    /**
     * @return Collection|array
     */
    public function getReportData(): Collection|array
    {
        return Membership::query()
            ->join('membership_types', 'membership_types.id', '=', 'memberships.membership_type_id')
            ->whereBetween('memberships.created_at', [$this->startDate, $this->endDate])
            ->where('memberships.gym_id', '=', $this->gymId)
            ->groupBy('membership_type_id', 'membership_types.name', 'membership_types.price')
            ->select([
                'membership_types.name',
                DB::raw('count(memberships.id)'),
                'membership_types.price',
            ])
            ->get();
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
