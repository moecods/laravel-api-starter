<?php

namespace App\Http\Controllers\API;

use App\Events\UserRegisteredEvent;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends APIController
{
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (! Auth::guard('web')->attempt($credentials)) {
            return $this->responseUnAuthenticated('you are not authorized to perform login');
        }

        $user = auth()->user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->responseSuccess('login successfully', ['access_token' => $token, 'token_type' => 'Bearer']);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->responseSuccess('Logged out');
    }

    public function me(Request $request): JsonResponse
    {
        return $this->responseSuccess(null, $request->user());
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());
        $token = $user->createToken('auth_token')->plainTextToken;

        event(new UserRegisteredEvent($user));

        return $this->responseSuccess('register successfully', ['access_token' => $token, 'token_type' => 'Bearer']);
    }

    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->responseSuccess('refresh successfully', ['access_token' => $token, 'token_type' => 'Bearer']);
    }
}
