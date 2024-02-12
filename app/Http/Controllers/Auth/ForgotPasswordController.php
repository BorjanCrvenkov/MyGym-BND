<?php

namespace App\Http\Controllers\Auth;

use App\Http\CustomResponse\CustomResponse;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Models\User;
use App\Notifications\Auth\ResetPasswordNotification;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class ForgotPasswordController extends BaseController
{
    /**
     * @param CustomResponse $response
     */
    public function __construct(public CustomResponse $response)
    {
    }

    /**
     * @param ForgotPasswordRequest $request
     * @return JsonResponse
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $email = $request->validated()['email'];

        $user = User::query()->where('email', '=', $email)->first();

        $token = app(PasswordBroker::class)->createToken($user);

        $user->notify(new ResetPasswordNotification($token));

        return $this->response->success("A reset link has been sent to the email address");
    }
}
