<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected string $endpoint = '/api/users';

    protected string $tableName = 'users';

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testCreateUser(): void
    {
        $this->markTestIncomplete('This test case needs review.');

        $this->actingAs(User::factory()->create());

        $payload = User::factory()->make([])->toArray();

        $this->json('POST', $this->endpoint, $payload)
            ->assertStatus(201)
            ->assertSee($payload['name']);

        $this->assertDatabaseHas($this->tableName, ['id' => 1]);
    }

    public function testViewAllUsersSuccessfully(): void
    {
        $this->markTestIncomplete('This test case needs review.');

        $this->actingAs(User::factory()->create());

        User::factory(5)->create();

        $this->json('GET', $this->endpoint)
            ->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertSee(User::first(rand(1, 5))->name);
    }

    public function testViewAllUsersByFooFilter(): void
    {
        $this->markTestIncomplete('This test case needs review.');

        $this->actingAs(User::factory()->create());

        User::factory(5)->create();

        $this->json('GET', $this->endpoint.'?foo=1')
            ->assertStatus(200)
            ->assertSee('foo')
            ->assertDontSee('foo');
    }

    public function testsCreateUserValidation(): void
    {
        $this->markTestIncomplete('This test case needs review.');

        $this->actingAs(User::factory()->create());

        $data = [
        ];

        $this->json('post', $this->endpoint, $data)
            ->assertStatus(422);
    }

    public function testViewUserData(): void
    {
        $this->markTestIncomplete('This test case needs review.');

        $this->actingAs(User::factory()->create());

        User::factory()->create();

        $this->json('GET', $this->endpoint.'/1')
            ->assertSee(User::first()->name)
            ->assertStatus(200);
    }

    public function testUpdateUser(): void
    {
        $this->markTestIncomplete('This test case needs review.');

        $this->actingAs(User::factory()->create());

        User::factory()->create();

        $payload = [
            'name' => 'Random',
        ];

        $this->json('PUT', $this->endpoint.'/1', $payload)
            ->assertStatus(200)
            ->assertSee($payload['name']);
    }

    public function testDeleteUser(): void
    {
        $this->markTestIncomplete('This test case needs review.');

        $this->actingAs(User::factory()->create());

        User::factory()->create();

        $this->json('DELETE', $this->endpoint.'/1')
            ->assertStatus(204);

        $this->assertEquals(0, User::count());
    }
}
