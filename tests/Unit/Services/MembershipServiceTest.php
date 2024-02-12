<?php

namespace Tests\Unit\Services;

use App\Models\Membership;
use App\Models\MembershipType;
use App\Models\User;
use App\Services\MembershipService;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\App;
use Tests\Unit\BaseTests\BaseServiceTest;

class MembershipServiceTest extends BaseServiceTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->service = App::make(MembershipService::class);
        $this->model = App::make(Membership::class);
        $this->shouldAssert = true;
        $user = User::factory()->administrator()->create();
        $this->be($user);
    }

    /**
     * @param Closure|null $data
     * @return void
     */
    public function testStore(Closure $data = null): void
    {
        $createData = $this->createModel()->first()->toArray();

        $id = $createData['id'];

        unset($createData['id'], $createData['created_at'], $createData['updated_at']);

        $this->model->query()->find($id)->forceDelete();

        $createdModel = $this->service->store($createData);
        $createData['id'] = $createdModel->getKey();
        unset($createData['original_membership_type_model']);

        $this->assertDatabaseHas($this->model->getTable(), $createData);
    }

    /**
     * @return void
     */
    public function testUpdate(): void
    {
        $this->expectNotToPerformAssertions();
    }

    /**
     * @return void
     */
    public function testResolveFields(): void
    {
        Carbon::setTestNow(now());

        $membershipType = MembershipType::factory()->create();
        $now = now();

        $data = [
            'membership_type_id' => $membershipType->getKey(),
            'start_date'         => $now->toDateString(),
        ];

        $actualData = $this->service->resolveFields($data, $membershipType);

        $membershipType->unsetRelation('gym');

        $expectedData = [
            'membership_type_id'             => $membershipType->getKey(),
            'start_date'                     => $now->toDateString(),
            'end_date'                       => $now->addWeeks($membershipType->duration_weeks)->toDateString(),
            'original_membership_type_model' => json_encode($membershipType),
            'gym_id'                         => $membershipType->gym_id,
            'name'                           => $membershipType->gym->name . ' - ' . $membershipType->name,
        ];

        $this->assertSame($expectedData, $actualData);
    }
}
