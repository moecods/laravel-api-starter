<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        if (! auth()->attempt($credentials)) {
            return $this->responseUnAuthorized('you are not authorized to perform login');
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

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $user = User::query()->create($validated);
        $token = $user->createToken('auth_token')->plainTextToken;

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
