<?php

namespace Tests\Unit\LogicValidators\User;

use App\Enums\UserTypesEnum;
use App\Exceptions\User\GymHasReachedMaximumNumberOfEmployeesException;
use App\LogicValidators\User\GymRegisterEmployeeNumberLogicValidator;
use App\Models\Gym;
use App\Models\Plan;
use App\Models\User;
use Closure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GymRegisterEmployeeNumberLogicValidatorTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param Closure $data
     * @return void
     * @throws GymHasReachedMaximumNumberOfEmployeesException
     * @dataProvider handleDataProvider
     */
    public function testHandle(Closure $data)
    {
        [$createData, $shouldThrowException] = $data();

        if ($shouldThrowException) {
            $this->expectException(GymHasReachedMaximumNumberOfEmployeesException::class);
        } else {
            $this->withoutExceptionHandling();
            $this->expectNotToPerformAssertions();
        }

        (new GymRegisterEmployeeNumberLogicValidator($createData))->validate();
    }

    /**
     * @return array[]
     */
    public static function handleDataProvider(): array
    {
        return [
            'Scenario 1: Created user is not employee'                       => [
                'data' => function () {
                    return [
                        [
                            'gym_id'    => Gym::factory()->create(),
                            'user_type' => UserTypesEnum::MEMBER->value
                        ],
                        false,
                    ];
                },
            ],
            'Scenario 2: gym_id is not present in the data'                  => [
                'data' => function () {
                    return [
                        [
                            'user_type' => UserTypesEnum::EMPLOYEE->value
                        ],
                        false,
                    ];
                },
            ],
            'Scenario 3: The gym has less than maximum allowed employees'    => [
                'data' => function () {
                    $planId = Plan::factory()->create([
                        'number_of_allowed_employees' => 2,
                    ])->getKey();

                    $gymOwnerId = User::factory()->create([
                        'plan_id' => $planId
                    ]);

                    $gymId = Gym::factory()->create([
                        'owner_id' => $gymOwnerId,
                    ])->getKey();

                    return [
                        [
                            'gym_id'    => $gymId,
                            'user_type' => UserTypesEnum::EMPLOYEE->value
                        ],
                        false,
                    ];
                },
            ],
            'Scenario 4: The gym has equal to the maximum allowed employees' => [
                'data' => function () {
                    $planId = Plan::factory()->create([
                        'number_of_allowed_employees' => 2,
                    ])->getKey();

                    $gymOwnerId = User::factory()->create([
                        'plan_id' => $planId
                    ]);

                    $gymId = Gym::factory()->create([
                        'owner_id' => $gymOwnerId,
                    ])->getKey();

                    User::factory(2)->employee()->create([
                        'gym_id' => $gymId,
                    ]);

                    return [
                        [
                            'gym_id'    => $gymId,
                            'user_type' => UserTypesEnum::EMPLOYEE->value
                        ],
                        true,
                    ];
                },
            ],
        ];
    }
}
