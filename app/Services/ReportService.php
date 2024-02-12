<?php

namespace App\Services;

use App\Enums\ReportTypeEnum;
use App\Models\BaseModel;
use App\Models\Gym;
use App\Models\Report;
use App\Models\User;
use App\Notifications\Users\GymReportedProblemNotification;
use Illuminate\Support\Facades\Auth;

class ReportService extends BaseService
{
    /**
     * @param Report $model
     */
    public function __construct(Report $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $data
     * @return BaseModel|User
     */
    public function store(array $data): BaseModel|User
    {
        $data = $this->resolveReporterId($data);

        $model = $this->model->create($data);

        $this->sendNotifications($model);

        return $this->show($model);
    }

    /**
     * @param array $data
     * @return array
     */
    public function resolveReporterId(array $data): array
    {
        $data['reporter_id'] = Auth::id();

        return $data;
    }

    /**
     * @param Report $report
     * @return void
     */
    public function sendNotifications(Report $report): void
    {
        if ($report->model_type != ReportTypeEnum::GYM_PROBLEM->value) {
            return;
        }

        $gym = Gym::query()
            ->with('owner')
            ->find($report->model_id);

        $gym->owner->notify(new GymReportedProblemNotification($report, $gym));
    }
}
