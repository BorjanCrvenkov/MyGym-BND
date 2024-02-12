<?php

namespace Tests\Feature\Filter;

use App\Models\Gym;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Tests\Feature\BaseTests\BaseFilterTest;

class UserFilterTest extends BaseFilterTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = App::make(User::class);
        $this->endpoint = $this->resolveModelEndpoint();
    }

    /**
     * @return void
     */
    public function testEmailFilter(): void
    {
        $this->createModel();
        $user = $this->createModel()->first();

        $filters = [
            'email' => $user->email,
        ];

        $expectedIds = [
            $user->getKey(),
        ];

        $this->testBulkFilters($filters, $expectedIds);
    }

    /**
     * @return void
     */
    public function testUsernameFilter(): void
    {
        $this->createModel();
        $user = $this->createModel()->first();

        $filters = [
            'username' => $user->username,
        ];

        $expectedIds = [
            $user->getKey(),
        ];

        $this->testBulkFilters($filters, $expectedIds);
    }

    /**
     * @return void
     */
    public function testGymFilter(): void
    {
        $gym = Gym::factory()->create();

        $expectedIds = User::factory(2)->employee()->create([
            'gym_id' => $gym->getKey(),
        ])->modelKeys();

        User::factory()->employee()->create();

        $filters = [
            'gym_id' => $gym->getKey(),
        ];

        $this->testBulkFilters($filters, $expectedIds);
    }
}
