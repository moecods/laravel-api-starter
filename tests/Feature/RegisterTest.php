<?php

use App\Mail\UserWelcomeMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

it('allows a user to register with valid credentials', function () {
    // Arrange
    $userCredentials = generateValidUserCredentialsForRegistration();

    // Act
    $response = $this->postJson(route('register'), $userCredentials);

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
        'name' => $userCredentials['name'],
        'email' => $userCredentials['email'],
    ]);
});

it('fails registration if required fields are not provided', function () {
    $this->postJson(route('register'), [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'email', 'password']);
});

it('fails registration if password and password confirmation do not match', function () {
    $userData = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'differentpassword',
    ];

    $this->postJson(route('register'), $userData)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});

it('fails registration if the email is already in use', function () {
    // Create a user with a specific email
    $existingUser = User::factory()->create([
        'email' => 'john@example.com',
    ]);

    // Attempt to register with the same email
    $userData = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $this->postJson(route('register'), $userData)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email'])
        ->assertJsonFragment(['detail' => 'The email has already been taken.']);
});

it('fails registration if the password does not meet strength requirements', function () {
    // Attempt to register with a weak password
    $userData = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'weakpas', // This password does not meet strength requirements
        'password_confirmation' => 'weakpas',
    ];

    $this->postJson(route('register'), $userData)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});

it('fails registration if the email format is invalid', function () {
    // Attempt to register with an invalid email format
    $userData = [
        'name' => 'John Doe',
        'email' => 'invalid-email', // This email has an invalid format
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $this->postJson(route('register'), $userData)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email'])
        ->assertJsonFragment(['detail' => 'The email field must be a valid email address.']);
});

it('sends a welcome email after user registration', function () {
    // Disable mail sending temporarily for a clean test
    Mail::fake();

    $userCredentials = generateValidUserCredentialsForRegistration();

    // Register a user
    $response = $this->post(route('register'), $userCredentials);

    // Assert that the user was created successfully
    $response->assertStatus(200);

    // Retrieve the registered user from the database
    $user = User::where('email', $userCredentials['email'])->first();

    // Assert that a welcome email was sent to the user
    Mail::assertSent(UserWelcomeMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

function generateValidUserCredentialsForRegistration(): array
{
    return [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];
}
