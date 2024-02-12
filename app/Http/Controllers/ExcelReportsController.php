<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExcelReports\FinanceReportRequest;
use App\Http\Requests\ExcelReports\GeneralReportRequest;
use App\Http\Requests\ExcelReports\MembershipTypesBoughtReportRequest;
use App\Http\Requests\ExcelReports\SessionsReportRequest;
use App\Services\ExcelReportsService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExcelReportsController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * @param ExcelReportsService $service
     */
    public function __construct(public ExcelReportsService $service)
    {

    }

    /**
     * @param SessionsReportRequest $request
     * @return BinaryFileResponse
     */
    public function sessionsReport(SessionsReportRequest $request){
        $validatedData = $request->validated();

        return $this->service->sessionsReport($validatedData);
    }

    /**
     * @param MembershipTypesBoughtReportRequest $request
     * @return BinaryFileResponse
     */
    public function membershipTypesBoughtReport(MembershipTypesBoughtReportRequest $request){
        $validatedData = $request->validated();

        return $this->service->membershipTypesBoughtReport($validatedData);
    }

    /**
     * @param FinanceReportRequest $request
     * @return BinaryFileResponse
     */
    public function financeReport(FinanceReportRequest $request){
        $validatedData = $request->validated();

        return $this->service->financeReport($validatedData);
    }

    /**
     * @param GeneralReportRequest $request
     * @return BinaryFileResponse
     */
    public function generalReport(GeneralReportRequest $request){
        $validatedData = $request->validated();

        return $this->service->generalReport($validatedData);
    }
}
