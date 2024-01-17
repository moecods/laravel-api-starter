<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    protected string $endpoint = '/api/posts';

    protected string $tableName = 'posts';

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testCreatePost(): void
    {
        $this->markTestIncomplete('This test case needs review.');

        $this->actingAs(User::factory()->create());

        $payload = Post::factory()->make([])->toArray();

        $this->json('POST', $this->endpoint, $payload)
            ->assertStatus(201)
            ->assertSee($payload['name']);

        $this->assertDatabaseHas($this->tableName, ['id' => 1]);
    }

    public function testViewAllPostsSuccessfully(): void
    {
        $this->markTestIncomplete('This test case needs review.');

        $this->actingAs(User::factory()->create());

        Post::factory(5)->create();

        $this->json('GET', $this->endpoint)
            ->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertSee(Post::first(rand(1, 5))->name);
    }

    public function testViewAllPostsByFooFilter(): void
    {
        $this->markTestIncomplete('This test case needs review.');

        $this->actingAs(User::factory()->create());

        Post::factory(5)->create();

        $this->json('GET', $this->endpoint.'?foo=1')
            ->assertStatus(200)
            ->assertSee('foo')
            ->assertDontSee('foo');
    }

    public function testsCreatePostValidation(): void
    {
        $this->markTestIncomplete('This test case needs review.');

        $this->actingAs(User::factory()->create());

        $data = [
        ];

        $this->json('post', $this->endpoint, $data)
            ->assertStatus(422);
    }

    public function testViewPostData(): void
    {
        $this->markTestIncomplete('This test case needs review.');

        $this->actingAs(User::factory()->create());

        Post::factory()->create();

        $this->json('GET', $this->endpoint.'/1')
            ->assertSee(Post::first()->name)
            ->assertStatus(200);
    }

    public function testUpdatePost(): void
    {
        $this->markTestIncomplete('This test case needs review.');

        $this->actingAs(User::factory()->create());

        Post::factory()->create();

        $payload = [
            'name' => 'Random',
        ];

        $this->json('PUT', $this->endpoint.'/1', $payload)
            ->assertStatus(200)
            ->assertSee($payload['name']);
    }

    public function testDeletePost(): void
    {
        $this->markTestIncomplete('This test case needs review.');

        $this->actingAs(User::factory()->create());

        Post::factory()->create();

        $this->json('DELETE', $this->endpoint.'/1')
            ->assertStatus(204);

        $this->assertEquals(0, Post::count());
    }
}
