<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows a user to log in successfully with valid credentials', function () {
    // Arrange
    $user = User::factory()->create([
        'email' => 'john@example.com',
        'password' => bcrypt('password123'),
    ]);

    $loginData = [
        'email' => 'john@example.com',
        'password' => 'password123',
    ];

    // Act & Assert
    $this->postJson(route('login'), $loginData)
        ->assertStatus(200);
});

it('fails login if required fields (email, password) are not provided', function () {
    // Arrange
    $invalidLoginData = [];

    // Act & Assert
    $this->postJson(route('login'), $invalidLoginData)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email', 'password']);
});

it('fails login if the user enters an incorrect password', function () {
    // Arrange
    $user = User::factory()->create([
        'email' => 'john@example.com',
        'password' => bcrypt('correctpassword'),
    ]);

    // Attempt
    $invalidLoginData = [
        'email' => 'john@example.com',
        'password' => 'incorrectpassword',
    ];

    // Assert
    $this->postJson(route('login'), $invalidLoginData)
        ->assertStatus(401);
});
