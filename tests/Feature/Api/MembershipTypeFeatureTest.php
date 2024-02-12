<?php

namespace Tests\Feature\Api;

use App\Models\MembershipType;
use Illuminate\Support\Facades\App;
use Tests\Feature\BaseTests\BaseFeatureTest;

class MembershipTypeFeatureTest extends BaseFeatureTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = App::make(MembershipType::class);
        $this->endpoint = $this->resolveModelEndpoint();
        $this->shouldAssert = true;
    }
}
