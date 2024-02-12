<?php

namespace App\Services;

use App\Models\WorkingSchedule;

class WorkingScheduleService extends BaseService
{
    /**
     * @param WorkingSchedule $model
     */
    public function __construct(WorkingSchedule $model)
    {
        parent::__construct($model);
    }
}
