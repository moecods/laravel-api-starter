<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows a user to log in successfully with valid mobile credentials', function () {
    // Arrange
    $user = User::factory()->create([
        'mobile' => '09306666357',
        'password' => bcrypt('password123'),
    ]);

    $loginData = [
        'mobile' => '09306666357',
        'password' => 'password123',
    ];

    // Act & Assert
    $this->postJson(route('mobile.login'), $loginData)
        ->assertStatus(200);
});

it('fails login if required fields (mobile, password) are not provided', function () {
    // Arrange
    $invalidLoginData = [];

    // Act & Assert
    $this->postJson(route('mobile.login'), $invalidLoginData)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['mobile', 'password']);
});

it('fails mobile login if the user enters an incorrect password', function () {
    // Arrange
    $user = User::factory()->create([
        'mobile' => '09306666357',
        'password' => bcrypt('correctpassword'),
    ]);

    // Attempt
    $invalidLoginData = [
        'mobile' => '09306666357',
        'password' => 'incorrectpassword',
    ];

    // Assert
    $this->postJson(route('mobile.login'), $invalidLoginData)
        ->assertStatus(401);
});
