<?php

namespace App\Http\Controllers\API;

use App\Actions\SendUserVerificationCodeAction;
use App\Authentication\MobileAuth;
use App\Events\UserRegisteredEvent;
use App\Http\Requests\Auth\MobileLoginRequest;
use App\Http\Requests\Auth\MobileRegisterRequest;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthMobileController extends APIController
{
    public function login(MobileLoginRequest $request): JsonResponse
    {
        $strategy = new MobileAuth();

        $strategy
            ->authenticate($request->mobile, $request->password)
            ->ifUnauthenticated(fn () => $this->responseUnAuthenticated('you are not authorized to perform login'))
            ->createToken();

        return $this->responseSuccess('login successfully', [
            'access_token' => $strategy->getToken(),
            'token_type' => 'Bearer',
        ]);
    }

    public function register(MobileRegisterRequest $request): JsonResponse
    {
        $strategy = new MobileAuth();
        $strategy->checkOtp($request->mobile, $request->code)
            ->ifOtpUnVerified(fn () => $this->responseConflictError('otp is not verified'));

        $strategy->register($request->mobile, $request->password, $request->validated())
            ->createToken();

        // Todo: handle send verification code by mobile
        // event(new UserRegisteredEvent($strategy->getUser()));

        return $this->responseSuccess('register successfully', [
            'access_token' => $strategy->getToken(),
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * @throws \Exception
     */
    public function sendOTP(Request $request)
    {
        $request->validate([
            'mobile' => 'required|numeric|digits:11',
        ]);

        (new Otp())->generate($request->mobile, 'numeric');

        return $this->responseSuccess('OTP sent successfully');
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
