<?php

namespace App\Services;

use App\Models\BaseModel;
use App\Models\User;
use App\Models\WorkingTime;
use App\Notifications\Users\ShiftAddedNotification;
use App\Notifications\Users\ShiftDeletedNotification;
use App\Notifications\Users\ShiftUpdatedNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class WorkingTimeService extends BaseService
{
    /**
     * @param WorkingTime $model
     */
    public function __construct(WorkingTime $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $data
     * @return BaseModel|User
     */
    public function store(array $data): BaseModel|User
    {
        $shift = parent::store($data);
        $shift->load('working_schedule.user');
        $shift->working_schedule->user->notify(new ShiftAddedNotification($shift));

        return $shift;
    }

    /**
     * @param User|BaseModel $model
     * @param array $data
     * @return Builder|array|Collection|Model|Builder[]
     */
    public function update(User|BaseModel $model, array $data): Builder|array|Collection|Model
    {
        $shift =  parent::update($model, $data);

        $shift->load('working_schedule.user');
        $shift->working_schedule->user->notify(new ShiftUpdatedNotification($shift));

        return $shift;
    }

    /**
     * @param User|BaseModel $model
     * @return bool|null
     */
    public function destroy(User|BaseModel $model): bool|null
    {
        $destroyed = parent::destroy($model);

        $model->load('working_schedule.user');
        $model->working_schedule->user->notify(new ShiftDeletedNotification($model));

        return  $destroyed;
    }
}
