<?php

namespace App\Services;


use App\Enums\UserRolesEnum;
use App\Enums\UserTypesEnum;
use App\Exceptions\User\GymHasReachedMaximumNumberOfEmployeesException;
use App\LogicValidators\User\GymRegisterEmployeeNumberLogicValidator;
use App\Models\BaseModel;
use App\Models\Gym;
use App\Models\Role;
use App\Models\User;
use App\Models\WorkingSchedule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserService extends BaseService
{
    /**
     * @param User $model
     * @param FileService $fileService
     */
    public function __construct(User $model, public FileService $fileService)
    {
        parent::__construct($model);
    }

    /**
     * @param array $data
     * @return BaseModel|User
     * @throws GymHasReachedMaximumNumberOfEmployeesException
     */
    public function store(array $data): BaseModel|User
    {
        /** Resolve data $data */
        $data = $this->resolveUserRole($data);

        $this->validateCreate($data);

        unset($data['user_type']);

        /** Create the user */
        $user = User::query()->create($data);
        $userId = $user->getKey();

        /** Store files and images */
        $this->fileService->storeIdentificationFile($data, $userId);
        $this->fileService->storeUserImage($data, $userId);

        /** Send email verification notification */
        $user->sendEmailVerificationNotification();

        /** Create working schedule if the user is business or employee */
        $this->createWorkingSchedule($user);

        return $this->show($user);
    }

    /**
     * @param array $data
     * @return void
     * @throws GymHasReachedMaximumNumberOfEmployeesException
     */
    public function validateCreate(array $data): void
    {
        (new GymRegisterEmployeeNumberLogicValidator($data))->validate();
    }


    /**
     * @param BaseModel|User $user
     * @param array $data
     * @return Builder|Model|Collection|Builder[]
     */
    public function update(BaseModel|User $user, array $data): Builder|array|Collection|Model
    {
        $user->update($data);
        $userId = $user->getKey();

        /** Store files and images */
        $this->fileService->storeIdentificationFile($data, $userId, $user);
        $this->fileService->storeUserImage($data, $userId, $user);

        /** Create working schedule if the user is business or employee */
        $this->createWorkingSchedule($user);

        return $user->refresh();
    }

    /**
     * @param array $data
     * @return array
     */
    public function resolveUserRole(array $data): array
    {
        $roleName = match ($data['user_type']) {
            UserTypesEnum::ADMINISTRATOR->value => UserRolesEnum::ADMINISTRATOR->value,
            UserTypesEnum::BUSINESS->value => UserRolesEnum::BUSINESS->value,
            UserTypesEnum::EMPLOYEE->value => UserRolesEnum::EMPLOYEE->value,
            UserTypesEnum::MEMBER->value => UserRolesEnum::MEMBER->value,
            default => '',
        };

        $roleId = Role::query()->where('name', '=', $roleName)->first()->getKey();

        $data['role_id'] = $roleId;

        return $data;
    }

    /**
     * @param User $user
     * @return void
     */
    public function createWorkingSchedule(User $user): void
    {
        if (!$user->is_business && !$user->is_employee) {
            return;
        }

        WorkingSchedule::query()->create([
            'name'    => $user->full_name . ' Shift',
            'user_id' => $user->getKey(),
            'gym_id'  => $user->gym_id,
        ]);
    }
}
