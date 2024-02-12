<?php

namespace App\Http\Controllers;

use App\Http\CustomResponse\CustomResponse;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Http\Resources\ReportCollection;
use App\Http\Resources\ReportResource;
use App\Models\Report;
use App\Services\ReportService;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    /**
     * @param Report $model
     * @param ReportService $service
     * @param CustomResponse $response
     */
    public function __construct(Report $model, ReportService $service, CustomResponse $response)
    {
        parent::__construct($model, $service, $response, ReportResource::class, ReportCollection::class, 'report');
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        return $this->indexHelper();
    }

    /**
     * Report store
     *
     * @param StoreReportRequest $request
     * @return JsonResponse
     */
    public function store(StoreReportRequest $request)
    {
        return $this->storeHelper($request);
    }

    /**
     * Report show
     *
     * @param Report $report
     * @return JsonResponse
     */
    public function show(Report $report)
    {
        return $this->showHelper($report);
    }

    /**
     * Report update
     *
     * @param UpdateReportRequest $request
     * @param Report $report
     * @return JsonResponse
     */
    public function update(UpdateReportRequest $request, Report $report)
    {
        return $this->updateHelper($request, $report);
    }

    /**
     * Report delete
     *
     * @param Report $report
     * @return JsonResponse
     */
    public function destroy(Report $report)
    {
        return $this->destroyHelper($report);
    }
}
