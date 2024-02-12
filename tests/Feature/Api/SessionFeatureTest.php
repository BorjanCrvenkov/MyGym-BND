<?php

namespace Tests\Feature\Api;

use App\Models\Session;
use Illuminate\Support\Facades\App;
use Tests\Feature\BaseTests\BaseFeatureTest;
use Tests\TestCase;

class SessionFeatureTest extends BaseFeatureTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = App::make(Session::class);
        $this->endpoint = $this->resolveModelEndpoint();
        $this->shouldAssert = true;
    }
}
