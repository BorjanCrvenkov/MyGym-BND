<?php

namespace Tests\Unit\LogicValidators\Gym;

use App\Exceptions\Gym\UserHasReachedMaximumNumberOfGymsException;
use App\LogicValidators\Gym\UserRegisterGymNumberLogicValidator;
use App\Models\Gym;
use App\Models\Plan;
use App\Models\User;
use Closure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRegisterGymNumberLogicValidatorTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param Closure $data
     * @return void
     * @throws UserHasReachedMaximumNumberOfGymsException
     * @dataProvider handleDataProvider
     */
    public function testHandle(Closure $data)
    {
        [$user, $shouldThrowException] = $data();

        $this->be($user);

        if ($shouldThrowException) {
            $this->expectException(UserHasReachedMaximumNumberOfGymsException::class);
        } else {
            $this->withoutExceptionHandling();
            $this->expectNotToPerformAssertions();
        }

        (new UserRegisterGymNumberLogicValidator())->validate();
    }

    /**
     * @return array[]
     */
    public static function handleDataProvider(): array
    {
        return [
            'Scenario 1: User has no registered gyms'                 => [
                'data' => function () {
                    $planId = Plan::factory()->create([
                        'number_of_allowed_gyms' => 2,
                    ])->getKey();

                    $gymOwner = User::factory()->business()->create([
                        'plan_id' => $planId
                    ]);
                    return [
                        $gymOwner,
                        false,
                    ];
                },
            ],
            'Scenario 2: User is not business'                        => [
                'data' => function () {
                    return [
                        User::factory()->administrator()->create(),
                        false,
                    ];
                },
            ],
            'Scenario 3: User has has less than maximum allowed gyms' => [
                'data' => function () {
                    $planId = Plan::factory()->create([
                        'number_of_allowed_gyms' => 2,
                    ])->getKey();

                    $gymOwner = User::factory()->business()->create([
                        'plan_id' => $planId
                    ]);

                    Gym::factory()->create([
                        'owner_id' => $gymOwner->getKey(),
                    ]);

                    return [
                        $gymOwner,
                        false,
                    ];
                },
            ],
            'Scenario 3: User has equal to the maximum allowed gyms'  => [
                'data' => function () {
                    $planId = Plan::factory()->create([
                        'number_of_allowed_gyms' => 2,
                    ])->getKey();

                    $gymOwner = User::factory()->business()->create([
                        'plan_id' => $planId,
                    ]);

                    Gym::factory(2)->create([
                        'owner_id' => $gymOwner->getKey(),
                    ]);

                    return [
                        $gymOwner,
                        true,
                    ];
                },
            ],
        ];
    }
}
