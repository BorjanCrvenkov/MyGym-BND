<?php

namespace App\Http\Controllers\Auth;

use App\Http\CustomResponse\CustomResponse;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends BaseController
{
    /**
     * @param CustomResponse $response
     */
    public function __construct(public CustomResponse $response)
    {
    }

    /**
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $request->validated();

        $response = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                ])->save();
            }
        );

        if ($response === Password::PASSWORD_RESET) {
            return $this->response->success('Password has been successfully reset.');
        }

        return $this->response->badRequest('Unable to reset password.');
    }
}
