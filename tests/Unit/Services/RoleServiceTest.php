<?php

namespace Tests\Unit\Services;

use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Support\Facades\App;
use Tests\Unit\BaseTests\BaseServiceTest;

class RoleServiceTest extends BaseServiceTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->service = App::make(RoleService::class);
        $this->model = App::make(Role::class);
        $this->shouldAssert = true;
    }

    /**
     * @return void
     */
    public function testIndex(): void
    {
        $expectedIds = [
            1,
            2,
            3,
            4
        ];

        $actualIds = $this->service->index()->modelKeys();

        $this->assertEqualsCanonicalizing($expectedIds, $actualIds);
    }
}
