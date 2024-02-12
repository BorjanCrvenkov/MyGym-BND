<?php

namespace App\Http\Controllers;

use App\Http\CustomResponse\CustomResponse;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class EmailConfirmationController extends BaseController
{
    /**
     * @param CustomResponse $response
     */
    public function __construct(public CustomResponse $response)
    {
    }

    /**
     * @return JsonResponse
     */
    public function sendEmailConfirmationNotification(): JsonResponse
    {
        $user = Auth::user();

        if($user->email_verified_at){
            return $this->response->success('Email is already verified');
        }

        $user->sendEmailVerificationNotification();

        return $this->response->success('Email notification sent');
    }

    /**
     * @param Request $request
     * @param User $user
     * @return Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
     */
    public function verify(Request $request, User $user)
    {
        if (!$request->hasValidSignature()) {
            throw new AuthorizationException;
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        $frontendUrl = config('urls.frontend_url');

        return redirect($frontendUrl);
    }
}
