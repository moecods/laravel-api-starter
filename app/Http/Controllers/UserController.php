<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepo;
use Essa\APIToolKit\Api\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse;

    private UserRepo $userRepo;

    public function __construct(UserRepo $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * Display a listing of the user.
     *
     * @return JsonResponse<User, 200>
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'pagination' => 'in:none',
            'per_page' => 'integer|min:1',
        ]);

        $users = User::dynamicPaginate();

        return $this->responseSuccess('Show users successfully', $users);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = $this->userRepo->create($validated);

        return $this->responseSuccess('User created successfully', $user);
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): JsonResponse
    {
        return $this->responseSuccess('Show user successfully', $user);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|unique:users',
            'password' => 'string|min:8',
        ]);

        $isUpdated = $this->userRepo->update($user->id, $validated);

        if (! $isUpdated) {
            return $this->responseNotFound('User not found');
        }

        return $this->responseSuccess('User updated successfully', $user);
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        $deletedUser = $this->userRepo->delete($user->id);

        if (! $deletedUser) {
            return $this->responseNotFound('User not found');
        }

        return $this->responseSuccess('User updated successfully', $user);
    }
}
