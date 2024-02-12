<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Notifications\Auth\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testSuccess(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $requestData = [
            'email' => $user->email,
        ];

        $this->post(route('password.forgot'), $requestData)
            ->assertStatus(Response::HTTP_OK);

        Notification::assertSentTo($user, ResetPasswordNotification::class);
    }
}
