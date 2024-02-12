<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

$endpoint = '/api/posts';

it('can create a post', function () use ($endpoint) {
    $user = User::factory()->create();
    $this->actingAs($user);

    $payload = Post::factory()->make(['title' => 'The fake title'])->toArray();

    $this->postJson($endpoint, $payload)
        ->assertStatus(201)
        ->assertSee($payload['title']);

    $this->assertDatabaseHas('posts', ['id' => 1, 'user_id' => $user->id]);
});

it('can view all posts successfully', function () use ($endpoint) {
    $user = User::factory()->create();
    $this->actingAs($user);

    Post::factory(5)->create(function ($post) {
        $post['user_id'] = User::factory()->create()->id;

        return $post;
    });

    $this->getJson($endpoint)
        ->assertStatus(200)
        ->assertJsonCount(5, 'data')
        ->assertSee(Post::query()->inRandomOrder()->first()->title);
});

it('can view all posts by title filter', function () use ($endpoint) {
    $user = User::factory()->create();
    $this->actingAs($user);

    Post::factory(5)->create(function ($post) {
        $post['user_id'] = User::factory()->create()->id;

        return $post;
    });

    Post::factory()->for(User::factory())->create(['title' => 'test']);

    $this->getJson($endpoint, ['title' => 'test'])
        ->assertStatus(200)
        ->assertSee('test')
        ->assertDontSee('foo');
});

it('validates post creation', function () use ($endpoint) {
    $user = User::factory()->create();
    $this->actingAs($user);

    $data = Post::factory()->for(User::factory())->raw(['title' => '']);

    $this->postJson($endpoint, $data)
        ->assertStatus(422);
});

it('can view post data', function () use ($endpoint) {
    $user = User::factory()->create();
    $this->actingAs($user);

    $post = Post::factory()->for(User::factory())->create();

    $this->getJson($endpoint."/{$post->id}")
        ->assertSee(Post::first()->title)
        ->assertStatus(200);
});

it('can update a post', function () use ($endpoint) {
    $user = User::factory()->create();
    $this->actingAs($user);

    $post = Post::factory()->for(User::factory())->create();

    $payload = [
        'title' => 'Random',
        'content' => 'Random Content',
    ];

    $this->putJson($endpoint."/{$post->id}", $payload)
        ->assertStatus(200)
        ->assertSee($payload['title'])
        ->assertSee($payload['content']);
});

it('can delete a post', function () use ($endpoint) {
    $user = User::factory()->create();
    $this->actingAs($user);

    $post = Post::factory()->for(User::factory())->create();

    $this->deleteJson($endpoint."/{$post->id}")
        ->assertStatus(204);

    expect(Post::count())->toBe(0);
});
