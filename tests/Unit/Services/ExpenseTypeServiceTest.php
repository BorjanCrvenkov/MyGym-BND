<?php

namespace Tests\Unit\Services;

use App\Models\ExpenseType;
use App\Services\ExpenseTypeService;
use Illuminate\Support\Facades\App;
use Tests\Unit\BaseTests\BaseServiceTest;

class ExpenseTypeServiceTest extends BaseServiceTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->service = App::make(ExpenseTypeService::class);
        $this->model = App::make(ExpenseType::class);
        $this->shouldAssert = true;
    }

    /**
     * @param array $createData
     * @param array $expectedData
     * @return void
     * @dataProvider resolveNextRecurringDateFieldDataProvider
     */
    public function testResolveNextRecurringDateField(array $createData, array $expectedData): void
    {
        $actualData = $this->service->resolveNextRecurringDateField($createData);

        $this->assertSame($expectedData, $actualData);
    }

    /**
     * @return \array[][]
     */
    public static function resolveNextRecurringDateFieldDataProvider(): array
    {
        return [
            'Scenario 1: Expense type is not recurring' => [
                'createData'   => [
                    'recurring' => false,
                ],
                'expectedData' => [
                    'recurring' => false,
                ],
            ],
            'Scenario 2: Expense type is recurring' => [
                'createData'   => [
                    'recurring'                      => true,
                    'recurring_every_number_of_days' => 2,
                ],
                'expectedData' => [
                    'recurring'                      => true,
                    'recurring_every_number_of_days' => 2,
                    'next_recurring_date'            => now()->addDays(2)->toDateString(),
                ],
            ],
        ];
    }
}
