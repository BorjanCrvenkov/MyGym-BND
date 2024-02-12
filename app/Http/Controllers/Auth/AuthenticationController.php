<?php

namespace App\Http\Controllers\Auth;

use App\Http\CustomResponse\CustomResponse;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthenticationController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param UserService $userService
     * @param CustomResponse $response
     * @param int $tokenLifespanMinutes
     */
    public function __construct(public UserService $userService, public CustomResponse $response, public int $tokenLifespanMinutes = 0)
    {
        $this->tokenLifespanMinutes = config('sanctum.expiration');
    }

    /**
     * Register
     *
     * @param StoreUserRequest $request
     * @return JsonResponse
     * @throws Exception
     * @unauthenticated
     */
    public function register(StoreUserRequest $request)
    {
        $validatedData = $request->validated();

        $user = $this->userService->store($validatedData);

        $userData = new UserResource($user);

        return $this->response->success(data: $userData);
    }

    /**
     * Login
     *
     * @param LoginRequest $request
     * @return JsonResponse
     * @unauthenticated
     */
    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();

        if (!Auth::attempt($validatedData)) {
            return $this->response->invalidLoginCredentials();
        }

        $user = Auth::user();
        $token = $user->createToken('MyGym');

        $auth = [
            'token'             => $token->plainTextToken,
            'token_expiry_time' => $token->accessToken->created_at->addMinutes($this->tokenLifespanMinutes),
        ];

        $userData = new UserResource($user);

        return $this->response->success('Successfully logged in user.', data: $userData, auth: $auth);
    }

    /**
     * Logout
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $user = Auth::user();

        $user->currentAccessToken()->delete();

        return $this->response->noContent();
    }

    /**
     * Return authenticated user
     *
     * @return JsonResponse
     */
    public function getAuthenticatedUser(): JsonResponse
    {
        $userData = new UserResource(Auth::user());

        return $this->response->success(data: $userData);
    }

    /**
     * Refresh token
     *
     * @return JsonResponse
     */
    public function refreshToken(): JsonResponse
    {
        $user = Auth::user();

        $user->currentAccessToken()->delete();

        $token = $user->createToken('MyGym');

        $auth = [
            'token'             => $token->plainTextToken,
            'token_expiry_time' => $token->accessToken->created_at->addMinutes($this->tokenLifespanMinutes),
        ];

        return $this->response->success(message: "Successfully refreshed the authenticated users token", auth: $auth);
    }
}
