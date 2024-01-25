<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends APIController
{
    public function index(Request $request): AnonymousResourceCollection
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

        $users = User::useFilters()->dynamicPaginate();

        return UserResource::collection($users);
    }

    public function store(CreateUserRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        return $this->responseCreated('User created successfully', new UserResource($user));
    }

    public function show(User $user): JsonResponse
    {
        return $this->responseSuccess(null, new UserResource($user));
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $user->update($request->validated());

        return $this->responseSuccess('User updated Successfully', new UserResource($user));
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return $this->responseDeleted();
    }
}
