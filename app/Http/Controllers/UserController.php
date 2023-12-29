<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepo;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ResponseTrait;

    private UserRepo $userRepo;

    public function __construct(UserRepo $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * Display a listing of the user.
     * @return JsonResponse<User, 200>
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'per_page' => 'nullable',
        ]);

        $perPage = request('per_page') ?? 10;

        $users = $this->userRepo->paginate($perPage);

        return $this->successResponse($users);
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

        return $this->successResponse($user);
    }

    /**
     * Display the specified user.
     */
    public function show($id): JsonResponse
    {
        $user = $this->userRepo->findOrNull($id);

        if (is_null($user)) {
            return $this->errorResponse('User not found', 404);
        }

        return $this->successResponse($user);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $isUpdated = $this->userRepo->update($id, $validated);

        if (! $isUpdated) {
            return $this->errorResponse('User not Updated', 404);
        }

        return $this->show($id);
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $deletedUser = $this->userRepo->delete($id);

        if (! $deletedUser) {
            return $this->errorResponse('User not Deleted', 404);
        }

        return $this->successResponse('User Deleted Successfully', 200);
    }
}
