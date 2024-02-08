<?php

namespace App\Http\Controllers\API;

use App\Actions\SendUserVerificationCodeAction;
use App\Authentication\EmailAuth;
use App\Events\UserRegisteredEvent;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends APIController
{
    public function login(LoginRequest $request): JsonResponse
    {
        $strategy = new EmailAuth();
        // authenticate
        // verify
        $strategy
            ->authenticate($request->email, $request->password)
            ->ifUnauthenticated(fn () => $this->responseUnAuthenticated('you are not authorized to perform login'))
            ->createToken();

        return $this->responseSuccess('login successfully', [
            'access_token' => $strategy->getToken(),
            'token_type' => 'Bearer',
        ]);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $strategy = new EmailAuth();

        $strategy
            ->register($request->name, $request->email, $request->password)
            ->createToken();

        event(new UserRegisteredEvent($strategy->getUser()));

        return $this->responseSuccess('register successfully', [
            'access_token' => $strategy->getToken(),
            'token_type' => 'Bearer',
        ]);
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

    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->responseSuccess('refresh successfully', ['access_token' => $token, 'token_type' => 'Bearer']);
    }

    public function verifyEmail(Request $request): JsonResponse
    {
        $request->validate(['code' => 'required']);

        $user = $request->user();
        $response = (new Otp)->validate($user->email, $request->get('code'));

        if ($user->email_verified_at) {
            return $this->responseConflictError('', 'email already verified');
        }

        if ($response->status) {
            $user->email_verified_at = now();
            $user->save();

            return $this->responseSuccess('email verified successfully');
        }

        return $this->responseConflictError('', $response->message);
    }

    public function requestVerificationCode(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->email_verified_at) {
            return $this->responseConflictError('', 'email already verified');
        }

        $action = app(SendUserVerificationCodeAction::class);
        $action->execute($user);
        $otpResponse = $action->getOtpResponse();

        if ($otpResponse->status) {
            return $this->responseSuccess('email sent successfully');
        }

        return $this->responseConflictError('', $otpResponse->message);
    }
}
