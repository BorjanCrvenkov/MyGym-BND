<?php

namespace Tests\Unit\Services;

use App\Models\MembershipType;
use App\Services\MembershipTypeService;
use Illuminate\Support\Facades\App;
use Tests\Unit\BaseTests\BaseServiceTest;

class MembershipTypeServiceTest extends BaseServiceTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->service = App::make(MembershipTypeService::class);
        $this->model = App::make(MembershipType::class);
        $this->shouldAssert = true;
    }
}
