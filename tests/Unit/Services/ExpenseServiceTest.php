<?php

namespace Tests\Unit\Services;

use App\Enums\ExpenseStatusEnum;
use App\Models\Expense;
use App\Models\ExpenseType;
use App\Services\ExpenseService;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\App;
use Tests\Unit\BaseTests\BaseServiceTest;

class ExpenseServiceTest extends BaseServiceTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->service = App::make(ExpenseService::class);
        $this->model = App::make(Expense::class);
        $this->shouldAssert = true;
    }

    /**
     * @return void
     */
    public function testUpdate(): void
    {
        $updateData = $this->createModel()->first()->toArray();

        $id = $updateData['id'];

        unset($updateData['id'], $updateData['created_at'], $updateData['updated_at']);

        $this->model->query()->find($id)->forceDelete();

        $model = $this->createModel()->first();

        $updatedModel = $this->service->update($model, $updateData);
        $updateData['id'] = $updatedModel->getKey();
        unset($updateData['name']);

        $this->assertDatabaseHas($this->model->getTable(), $updateData);
    }

    /**
     * @return void
     */
    public function testResolveNameField(): void
    {
        $expenseType = ExpenseType::factory()->create();

        $expectedData = [
            'name' => $expenseType->name,
        ];

        $actualData = $this->service->resolveNameField([], $expenseType);

        $this->assertSame($expectedData, $actualData);
    }

    /**
     * @param Closure $data
     * @return void
     * @dataProvider resolvePaidAtFieldDataProvider
     */
    public function testResolvePaidAtField(Closure $data)
    {
        Carbon::setTestNow(now());

        [$validatedRequestData, $expectedData, $expense] = $data();

        $actualData = $this->service->resolvePaidAtField($validatedRequestData, $expense);

        $this->assertSame($expectedData, $actualData);
    }

    /**
     * @return array[]
     */
    public static function resolvePaidAtFieldDataProvider(): array
    {
        return [
            'Scenario 1: status is not present in the data'             => [
                'data' => function () {
                    return [
                        [], // data parameter,
                        [], // expected data
                        null, // expense
                    ];
                },
            ],
            'Scenario 2: status is not paid'                            => [
                'data' => function () {
                    return [
                        [
                            'status' => ExpenseStatusEnum::NOT_PAID->value,
                        ], // data parameter,
                        [
                            'status' => ExpenseStatusEnum::NOT_PAID->value
                        ], // expected data
                        null, // expense
                    ];
                },
            ],
            'Scenario 3: Test create method, status is not paid'        => [
                'data' => function () {
                    return [
                        [
                            'status' => ExpenseStatusEnum::NOT_PAID->value,
                        ], // data parameter,
                        [
                            'status' => ExpenseStatusEnum::NOT_PAID->value,

                        ], // expected data
                        null, // expense
                    ];
                },
            ],
            'Scenario 4: Test create method, status is paid'            => [
                'data' => function () {
                    return [
                        [
                            'status' => ExpenseStatusEnum::PAID->value,
                        ], // data parameter,
                        [
                            'status'  => ExpenseStatusEnum::PAID->value,
                            'paid_at' => now()->toDateTimeString(),
                        ], // expected data
                        null, // expense
                    ];
                },
            ],
            'Scenario 5: status is being changed from not paid to paid' => [
                'data' => function () {
                    return [
                        [
                            'status' => ExpenseStatusEnum::PAID->value,
                        ], // data parameter,
                        [
                            'status'  => ExpenseStatusEnum::PAID->value,
                            'paid_at' => now()->toDateTimeString(),
                        ], // expected data
                        Expense::factory()->create(),
                    ];
                },
            ],
        ];
    }
}
