<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
    public function __construct()
    {

    }

    public function index(): AnonymousResourceCollection
    {
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
