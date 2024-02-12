<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ResetPasswordFeatureTest extends TestCase
{
    /**
     * @return void
     */
    public function testSuccess(): void
    {
        $user = User::factory()->create();

        $token = app(PasswordBroker::class)->createToken($user);

        $requestData = [
            'email'                 => $user->email,
            'password'              => 'testTest',
            'password_confirmation' => 'testTest',
            'token'                 => $token,
        ];

        $this->post(route('password.reset'), $requestData)
            ->assertStatus(Response::HTTP_OK);

        $user = $user->refresh();

        $this->assertTrue(password_verify('testTest', $user->password));
    }

    /**
     * @return void
     */
    public function testFail(): void
    {
        $user = User::factory()->create();

        $requestData = [
            'email'                 => $user->email,
            'password'              => 'testTest',
            'password_confirmation' => 'testTest',
            'token'                 => 'test',
        ];

        $this->post(route('password.reset'), $requestData)
            ->assertStatus(Response::HTTP_BAD_REQUEST);
    }
}
