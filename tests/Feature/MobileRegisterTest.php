<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows a guest to request an otp', function () {
    $mobile = '09111232030';
    $this->assertFalse(\Ichtrojan\Otp\Models\Otp::query()->where('identifier', $mobile)->exists());
    $response = $this->postJson(route('mobile.send-otp', ['mobile' => $mobile]));
    $response->assertOk();
    $this->assertTrue(\Ichtrojan\Otp\Models\Otp::query()->where('identifier', $mobile)->exists());
});

it('allows a user to mobile register with valid credentials', function () {
    // Arrange
    $userCredentials = generateValidUserCredentialsForMobileRegistration();
    $code = (new \Ichtrojan\Otp\Otp())->generate($userCredentials['mobile'], 'numeric', 6)->token;

    // Act
    $response = $this->postJson(route('mobile.register'), $userCredentials + ['code' => $code]);

    // Assert
    $response
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'access_token',
                'token_type',
            ],
        ]);

    // Ensure the user is stored in the database
    $this->assertDatabaseHas('users', [
        'mobile' => $userCredentials['mobile'],
    ]);

    $this->assertNotNull(User::query()->latest()->first()->mobile_verified_at);
});
