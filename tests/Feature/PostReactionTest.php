<?php

use App\Actions\PostReaction\PostLikeReaction;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('a user can like a post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for(User::factory())->create();

    $this->actingAs($user)->post(route('posts.like', ['post' => $post->id]))
        ->assertStatus(200);

    expect($post->isReactBy($user, 'like'))->toBeTrue()
        ->and($post->isReactBy($user, 'dislike'))->toBeFalse();
});

it('a user can dislike a post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for(User::factory())->create();

    $this->actingAs($user)
        ->post(route('posts.dislike', $post))
        ->assertStatus(200);

    expect($post->isReactBy($user, 'dislike'))->toBeTrue()
        ->and($post->isReactBy($user, 'like'))->toBeFalse();
});

it('a user can remove a reaction', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for(User::factory())->create();

    (new PostLikeReaction())
        ->user($user)
        ->execute($post);

    expect($post->isReactBy($user))->toBeTrue();

    (new PostLikeReaction())
        ->user($user)
        ->execute($post);

    expect($post->isReactBy($user))->toBeFalse();
});
