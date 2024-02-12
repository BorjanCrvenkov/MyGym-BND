<?php

namespace Tests\Feature\Api;

use App\Enums\UserTypesEnum;
use App\Models\Role;
use App\Models\User;
use Closure;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response as HTTPResponse;
use Tests\Feature\BaseTests\BaseFeatureTest;

class UserFeatureTest extends BaseFeatureTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = App::make(User::class);
        $this->endpoint = $this->resolveModelEndpoint();
        $this->shouldAssert = true;
    }

    /**
     * @param array $expectedData
     * @param bool $singleResult
     * @return array
     */
    protected function getExpectedJsonStructure(array $expectedData, bool $singleResult = false): array
    {
        if(!$singleResult){
            $updatedData = [];

            foreach ($expectedData as $userData){
                unset($userData['stripe_id'], $userData['pm_type'], $userData['pm_last_four'], $userData['trial_ends_at']);
                $updatedData[] = $userData;
            }

            $expectedData = $updatedData;
        }else{
            unset($expectedData['stripe_id'], $expectedData['pm_type'], $expectedData['pm_last_four'], $expectedData['trial_ends_at']);

        }

        return parent::getExpectedJsonStructure($expectedData, $singleResult);
    }

    /**
     * @param Closure|null $data
     * @return void
     * @dataProvider storeRouteDataProvider
     */
    public function testStoreRoute(Closure $data = null): void
    {
        Storage::fake();
        Notification::fake();

        [$createData, $userType] = $data();

        $expectedStructure = $this->getExpectedJsonStructure($createData, true);

        $createData['user_type'] = $userType;
        $createData['password'] = 'test';

        $identificationFile = UploadedFile::fake()->create('test_file.pdf', 100);
        $image = UploadedFile::fake()->create('test_file.jpg', 100);

        $createData['identification_file'] = $identificationFile;
        $createData['image'] = $image;

        $id = $createData['id'];

        unset($createData['id'], $createData['created_at'], $createData['updated_at']);

        $this->model->query()->find($id)->forceDelete();

        $this->be($this->user)
            ->post("{$this->endpoint}", $createData)
            ->assertStatus(HTTPResponse::HTTP_CREATED)
            ->assertJsonStructure($expectedStructure);
    }

    /**
     * @return array[]
     */
    public static function storeRouteDataProvider(): array
    {
        return [
            'Administrator is being created' => [
                'data' => function () {
                    return [
                        User::factory()->create()->toArray(),
                        UserTypesEnum::ADMINISTRATOR->value,
                    ];
                },
            ],
            'Business is being created'      => [
                'data' => function () {
                    return [
                        User::factory()->business()->create()->toArray(),
                        UserTypesEnum::BUSINESS->value,
                    ];
                },
            ],
            'Employee is being created'      => [
                'data' => function () {
                    return [
                        User::factory()->employee()->create()->toArray(),
                        UserTypesEnum::EMPLOYEE->value,
                    ];
                },
            ],
            'Member is being created'        => [
                'data' => function () {
                    return [
                        User::factory()->create()->toArray(),
                        UserTypesEnum::MEMBER->value,
                    ];
                },
            ],
        ];
    }

    /**
     * @param Closure|null $data
     * @return void
     * @dataProvider updateRouteDataProvider
     */
    public function testUpdateRoute(Closure $data = null): void
    {
        [$updateData, $userType] = $data();

        $expectedStructure = $this->getExpectedJsonStructure($updateData, true);

        $updateData['user_type'] = $userType;

        $id = $updateData['id'];

        $this->model->query()->find($id)->forceDelete();

        unset($updateData['id'], $updateData['created_at'], $updateData['updated_at']);

        $model = $this->createModel()->first();

        $this->be($this->user)
        ->post("{$this->endpoint}/{$model->getKey()}", $updateData)
            ->assertStatus(HTTPResponse::HTTP_OK)
            ->assertJsonStructure($expectedStructure);
    }

    /**
     * @return array[]
     */
    public static function updateRouteDataProvider(): array
    {
        return [
            'Administrator is being updated' => [
                'data' => function () {
                    return [
                        User::factory()->create()->toArray(),
                        UserTypesEnum::ADMINISTRATOR->value,
                    ];
                },
            ],
            'Business is being updated'      => [
                'data' => function () {
                    return [
                        User::factory()->business()->create()->toArray(),
                        UserTypesEnum::BUSINESS->value,
                    ];
                },
            ],
            'Employee is being updated'      => [
                'data' => function () {
                    return [
                        User::factory()->employee()->create()->toArray(),
                        UserTypesEnum::EMPLOYEE->value,
                    ];
                },
            ],
            'Member is being updated'        => [
                'data' => function () {
                    return [
                        User::factory()->create()->toArray(),
                        UserTypesEnum::MEMBER->value,
                    ];
                },
            ],
        ];
    }
}
