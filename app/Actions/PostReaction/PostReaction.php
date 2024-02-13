<?php

namespace App\Actions\PostReaction;

use App\Models\Post;
use App\Models\User;

abstract class PostReaction
{
    protected User $user;

    protected string $reaction;

    public function user(?User $user): PostReaction
    {
        $this->user = $user;

        return $this;
    }

    public function reaction(string $reaction): PostReaction
    {
        $this->reaction = $reaction;

        return $this;
    }

    public function execute(Post $post): object
    {
        $this->user ?? $this->user(auth()->user());

        $this->user->toggleReactionOn($post, $this->reaction);

        return (object) [
            'success' => true,
            'message' => 'Reaction added',
            'reaction' => $this->reaction,
            'post' => $post,
            'user' => $this->user,
        ];
    }
}
