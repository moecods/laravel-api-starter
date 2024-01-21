<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $request->validate([
            'pagination' => 'in:none',
            'per_page' => 'integer|min:1',
            'search' => 'string',
            /**
             * @example -created_at, created_at
             */
            'sorts' => 'string',
        ]);

        $posts = Post::useFilters()->dynamicPaginate();

        return PostResource::collection($posts);
    }

    public function store(CreatePostRequest $request): JsonResponse
    {
        $post = Post::create($request->validated());

        return $this->responseCreated('Post created successfully', new PostResource($post));
    }

    public function show(Post $post): JsonResponse
    {
        return $this->responseSuccess(null, new PostResource($post));
    }

    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        $post->update($request->validated());

        return $this->responseSuccess('Post updated Successfully', new PostResource($post));
    }

    public function destroy(Post $post): JsonResponse
    {
        $post->delete();

        return $this->responseDeleted();
    }
}
