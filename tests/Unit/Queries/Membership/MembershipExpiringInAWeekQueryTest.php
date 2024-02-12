<?php

namespace Tests\Unit\Queries\Membership;

use App\Models\Membership;

use App\Queries\Membership\MembershipExpiringInAWeekQuery;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembershipExpiringInAWeekQueryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testQueryResults(): void
    {
        Carbon::setTestNow(now());
        $expectedIds = $this->generateQueryData();

        $actualIds = (new MembershipExpiringInAWeekQuery())->get()->modelKeys();

        $this->assertEqualsCanonicalizing($expectedIds, $actualIds);
    }

    /**
     * @return array
     */
    public function generateQueryData(): array
    {
        $correctEndDate = now()->addWeek()->toDateString();
        $wrongEndDate = now()->addMonth()->toDateString();

        Membership::factory()->create([
            'end_date' => $wrongEndDate
        ]);

        $membership1 = Membership::factory()->create();
        $membership1->update([
            'end_date' => $correctEndDate,
        ]);

        $membership2 = Membership::factory()->create();
        $membership2->update([
            'end_date' => $correctEndDate,
        ]);

        return [
            $membership1->getKey(),
            $membership2->getKey(),
        ];
    }
}
