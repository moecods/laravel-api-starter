<?php

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutMiddleware(Authorize::class);
    $this->withoutMiddleware(UserPolicy::class);
});

$endpoint = '/api/users';

it('can create a user', function () use ($endpoint) {
    $user = User::factory()->create();
    $this->actingAs($user, 'sanctum');

    $payload = User::factory()->raw(['name' => 'John Doe', 'password' => '12345678']);

    $response = $this->postJson($endpoint, $payload)
        ->assertStatus(201)
        ->assertSee($payload['name'])
        ->getOriginalContent();

    $this->assertDatabaseHas('users', ['id' => $response['data']->id]);
});

it('can view all users', function () use ($endpoint) {
    $user = User::factory()->create();
    $this->actingAs($user);

    User::factory(5)->create();

    $this->getJson($endpoint)
        ->assertStatus(200)
        ->assertJsonCount(6, 'data');
});

it('validates user creation', function () use ($endpoint) {
    $user = User::factory()->create();
    $this->actingAs($user);

    $data = User::factory()->raw(['name' => '']);

    $this->postJson($endpoint, $data)
        ->assertStatus(422);
});

it('can view user data', function () use ($endpoint) {
    $user = User::factory()->create();
    $this->actingAs($user);

    $user = User::factory()->create();

    $this->getJson($endpoint."/{$user->id}")
        ->assertSee($user->name)
        ->assertStatus(200);
});

it('can update a user', function () use ($endpoint) {
    $user = User::factory()->create();
    $this->actingAs($user);

    $user = User::factory()->create();

    $payload = [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ];

    $this->putJson($endpoint."/{$user->id}", $payload)
        ->assertStatus(200)
        ->assertSee($payload['name'])
        ->assertSee($payload['email']);
});

it('can delete a user', function () use ($endpoint) {
    $user = User::factory()->create();
    $this->actingAs($user);

    $user = User::factory()->create();

    $this->deleteJson($endpoint."/{$user->id}")
        ->assertStatus(204);

    expect(User::count())->toBe(1);
});
