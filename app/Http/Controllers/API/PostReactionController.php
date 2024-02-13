<?php

namespace App\Http\Controllers\API;

use App\Actions\PostReaction\PostDislikeReaction;
use App\Actions\PostReaction\PostLikeReaction;
use App\Models\Post;

class PostReactionController extends APIController
{
    public function like(Post $post)
    {
        (new PostLikeReaction)->execute($post);

        return $this->responseSuccess('Post liked successfully');
    }

    public function dislike(Post $post)
    {
        (new PostDislikeReaction())->execute($post);

        return $this->responseSuccess('Post disliked successfully');
    }
}
