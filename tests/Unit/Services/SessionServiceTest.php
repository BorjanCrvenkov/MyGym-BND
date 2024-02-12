<?php

namespace Tests\Unit\Services;

use App\Models\Session;
use App\Services\SessionService;
use Closure;
use Illuminate\Support\Facades\App;
use Tests\Unit\BaseTests\BaseServiceTest;

class SessionServiceTest extends BaseServiceTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->service = App::make(SessionService::class);
        $this->model = App::make(Session::class);
        $this->shouldAssert = true;
    }

    /**
     * @param Closure $data
     * @return void
     * @dataProvider setOrUnsetMembershipActiveSessionDataProvider
     */
    public function testSetOrUnsetMembershipActiveSession(Closure $data): void
    {
        [$session, $setActiveSession] = $data();

        $this->service->setOrUnsetMembershipActiveSession($session, $setActiveSession);

        $expectedValue = $setActiveSession ? $session->getKey() : null;
        $actualValue = $session->membership->active_session_id;

        $this->assertSame($expectedValue, $actualValue);
    }

    /**
     * @return array[]
     */
    public static function setOrUnsetMembershipActiveSessionDataProvider(): array
    {
        return [
            'Create scenario' => [
                'data' => function () {
                    return [
                        Session::factory()->create(),
                        true,
                    ];
                }
            ],
            'Update scenario' => [
                'data' => function () {
                    return [
                        Session::factory()->create(),
                        false,
                    ];
                }
            ],
        ];
    }
}
