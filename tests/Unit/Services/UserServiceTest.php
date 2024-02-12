<?php

namespace Tests\Unit\Services;

use App\Enums\UserRolesEnum;
use App\Enums\UserTypesEnum;
use App\Models\Role;
use App\Models\User;
use App\Notifications\Auth\EmailConfirmationNotification;
use App\Services\UserService;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Notification;
use Tests\Unit\BaseTests\BaseServiceTest;

class UserServiceTest extends BaseServiceTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->service = App::make(UserService::class);
        $this->model = App::make(User::class);
        $this->shouldAssert = true;
    }

    /**
     * @param Closure|null $data
     * @return void
     * @dataProvider storeDataProvider
     */
    public function testStore(Closure $data = null): void
    {
        Notification::fake();

        $createData = $data();

        $id = $createData['id'];

        unset(
            $createData['id'],
            $createData['created_at'],
            $createData['updated_at'],
            $createData['role'],
            $createData['working_schedule_id'],
        );
        $createData['password'] = 'test';

        $this->model->query()->find($id)->forceDelete();

        $createdModel = $this->service->store($createData);
        $createData['id'] = $createdModel->getKey();
        unset($createData['password'], $createData['user_type'], $createData['working_schedule_id']);

        $this->assertDatabaseHas($this->model->getTable(), $createData);
        Notification::assertSentTo($createdModel, EmailConfirmationNotification::class);
    }

    /**
     * @return array[]
     */
    public static function storeDataProvider(): array
    {
        return [
            'Administrator is being created' => [
                'data' => function () {
                    $createData = User::factory()->administrator()->create()->toArray();
                    $createData['user_type'] = UserTypesEnum::ADMINISTRATOR->value;

                    return $createData;
                },
            ],
            'Business is being created'      => [
                'data' => function () {
                    $createData = User::factory()->business()->create()->toArray();
                    $createData['user_type'] = UserTypesEnum::BUSINESS->value;

                    return $createData;
                },
            ],
            'Employee is being created'      => [
                'data' => function () {
                    $createData = User::factory()->employee()->create()->toArray();
                    $createData['user_type'] = UserTypesEnum::EMPLOYEE->value;

                    return $createData;
                },
            ],
            'Member is being created'        => [
                'data' => function () {
                    $createData = User::factory()->create()->toArray();
                    $createData['user_type'] = UserTypesEnum::MEMBER->value;

                    return $createData;
                },
            ],
        ];
    }

    /**
     * @return void
     */
    public function testUpdate(): void
    {
        $updateData = $this->createModel()->first()->toArray();

        $id = $updateData['id'];

        unset(
            $updateData['id'],
            $updateData['created_at'],
            $updateData['updated_at'],
            $updateData['identification_file'],
            $updateData['image'],
            $updateData['role'],
            $updateData['plan'],
            $updateData['working_schedule_id'],
        );

        $this->model->query()->find($id)->forceDelete();

        $model = $this->createModel()->first();

        $updatedModel = $this->service->update($model, $updateData);
        $updateData['id'] = $updatedModel->getKey();

        $this->assertDatabaseHas($this->model->getTable(), $updateData);
    }

    /**
     * @param string $userType
     * @param string $expectedRoleName
     * @return void
     * @dataProvider resolveUserRoleDataProvider
     */
    public function testResolveUserRole(string $userType, string $expectedRoleName)
    {
        $data = $this->service->resolveUserRole(['user_type' => $userType]);

        $actualRoleName = Role::query()->find($data['role_id'])->name;

        $this->assertSame($expectedRoleName, $actualRoleName);
    }

    /**
     * @return array[]
     */
    public static function resolveUserRoleDataProvider(): array
    {
        return [
            "Scenario 1: User type is administrator" => [
                'user_type'          => UserTypesEnum::ADMINISTRATOR->value,
                'expected_role_name' => UserRolesEnum::ADMINISTRATOR->value,
            ],
            "Scenario 2: User type is business"      => [
                'user_type'          => UserTypesEnum::BUSINESS->value,
                'expected_role_name' => UserRolesEnum::BUSINESS->value,
            ],
            "Scenario 3: User type is employee"      => [
                'user_type'          => UserTypesEnum::EMPLOYEE->value,
                'expected_role_name' => UserRolesEnum::EMPLOYEE->value,
            ],
            "Scenario 4: User type is member"        => [
                'user_type'          => UserTypesEnum::MEMBER->value,
                'expected_role_name' => UserRolesEnum::MEMBER->value,
            ],
        ];
    }

    /**
     * @param Closure $data
     * @return void
     * @dataProvider createWorkingScheduleDataProvider
     */
    public function testCreateWorkingSchedule(Closure $data): void
    {
        [$user, $shouldWorkingScheduleBeCreated] = $data();

        if($shouldWorkingScheduleBeCreated){
            $this->assertDatabaseHas('working_schedules', [
                'user_id' => $user->getKey(),
            ]);

            return;
        }

        $this->assertDatabaseMissing('working_schedules', [
            'user_id' => $user->getKey(),
        ]);
    }

    /**
     * @return array[]
     */
    public static function createWorkingScheduleDataProvider(): array
    {
        return [
            'Scenario 1: User is administrator' => [
                'data' => function () {
                    return [
                        User::factory()->administrator()->create(),
                        false,
                    ];
                },
            ],
            'Scenario 2: User is business' => [
                'data' => function () {
                    return [
                        User::factory()->business()->create(),
                        true,
                    ];
                },
            ],
            'Scenario 3: User is employee' => [
                'data' => function () {
                    return [
                        User::factory()->employee()->create(),
                        true,
                    ];
                },
            ],
            'Scenario 4: User is member' => [
                'data' => function () {
                    return [
                        User::factory()->create(),
                        false,
                    ];
                },
            ],
        ];
    }
}
