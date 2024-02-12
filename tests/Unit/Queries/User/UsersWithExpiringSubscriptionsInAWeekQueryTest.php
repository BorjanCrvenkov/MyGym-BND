<?php

namespace Tests\Unit\Queries\User;

use App\Models\Plan;
use App\Models\User;
use App\Queries\User\UsersWithExpiringSubscriptionsInAWeekQuery;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UsersWithExpiringSubscriptionsInAWeekQueryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testQueryResults(): void
    {
        Carbon::setTestNow(now());
        $expectedIds = $this->generateQueryData();

        $actualIds = (new UsersWithExpiringSubscriptionsInAWeekQuery())->get()->modelKeys();

        $this->assertEqualsCanonicalizing($expectedIds, $actualIds);
    }

    /**
     * @return array
     */
    public function generateQueryData(): array
    {
        $correctEndDate = now()->addWeek()->toDateString();
        $wrongEndDate = now()->addMonth()->toDateString();

        $planId = Plan::factory()->create()->getKey();

        $user1Id = User::factory()->create([
            'plan_id' => $planId
        ])->getKey();
        DB::statement("INSERT INTO subscriptions (user_id, name, ends_at, stripe_id, stripe_status)
                             VALUES({$user1Id}, 'test', '{$correctEndDate}', 'test_1', '0')");

        $user2Id = User::factory()->create([
            'plan_id' => $planId
        ])->getKey();
        DB::statement("INSERT INTO subscriptions (user_id, name, ends_at, stripe_id, stripe_status)
                             VALUES({$user2Id}, 'test', '{$correctEndDate}', 'test_2', '0')");

        $user3Id = User::factory()->create([
            'plan_id' => $planId
        ])->getKey();
        DB::statement("INSERT INTO subscriptions (user_id, name, ends_at, stripe_id, stripe_status)
                             VALUES({$user3Id}, 'test', '{$wrongEndDate}', 'test_3', '0')");

        return [
            $user1Id,
            $user2Id,
        ];
    }
}
