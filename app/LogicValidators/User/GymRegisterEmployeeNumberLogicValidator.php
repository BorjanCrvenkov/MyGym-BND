<?php

namespace App\LogicValidators\User;

use App\Enums\UserRolesEnum;
use App\Enums\UserTypesEnum;
use App\Exceptions\User\GymHasReachedMaximumNumberOfEmployeesException;
use App\LogicValidators\BaseLogicValidator;
use App\Models\Gym;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Arr;

class GymRegisterEmployeeNumberLogicValidator extends BaseLogicValidator
{
    /**
     * @param array $data
     */
    public function __construct(public array $data)
    {
    }

    /**
     * @return void
     * @throws GymHasReachedMaximumNumberOfEmployeesException
     */
    public function validate(): void
    {
        $data = $this->data;

        $userType = $data['user_type'];

        if($userType != UserTypesEnum::EMPLOYEE->value){
            return;
        }

        if (!Arr::has($data, 'gym_id')){
            return;
        }

        $gym = Gym::query()->find($data['gym_id']);
        $gymOwner = $gym->owner;
        $ownerPlan = $gymOwner->plan;

        $currentGymEmployeeNumber = $this->calculateCurrentGymEmployeeNumber($gym);

        if($currentGymEmployeeNumber < $ownerPlan->number_of_allowed_employees){
            return;
        }

        throw new GymHasReachedMaximumNumberOfEmployeesException();
    }

    /**
     * @param Gym $gym
     * @return int
     */
    public function calculateCurrentGymEmployeeNumber(Gym $gym): int
    {
        $employeeRole = Role::query()
            ->where('name', '=', UserRolesEnum::EMPLOYEE->value)->first();

        return User::query()
            ->where('gym_id', '=', $gym->getKey())
            ->where('role_id', '=', $employeeRole->getKey())
            ->count();
    }
}
