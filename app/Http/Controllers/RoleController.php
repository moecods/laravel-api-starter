<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Repositories\RoleRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    use ResponseTrait;

    protected RoleRepository $repository;

    public function __construct(RoleRepository $repository)
    {
        $this->authorizeResource(Role::class, 'role');
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse<Role, 200>
     */
    public function index(): JsonResponse
    {
        $roles = $this->repository->orderBy('id', 'desc')->paginate();

        return $this->successResponse($roles->load('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique(config('permission.table_names')['roles'], 'name'),
            ],
            'permissions' => 'nullable|array',
            'permissions.*' => [
                'int',
                'min:1',
                Rule::exists(config('permission.table_names')['permissions'], 'id'),
            ],
        ]);

        $role = $this->repository->create($validated);
        $role->givePermissionTo($validated['permissions']);

        return $this->successResponse($role->load('permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => Rule::unique(config('permission.table_names')['roles'], 'name')
                ->where('id', '!=', $id),
            'permissions' => 'nullable|array',
            'permissions.*' => [
                'int',
                'min:1',
                Rule::exists(config('permission.table_names')['permissions'], 'id'),
            ],
        ]);

        $role = $this->repository->update($validated, $id);
        $role->syncPermissions($validated['permissions']);

        return $this->successResponse($role->load('permissions'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = $this->repository->find($id);

        if (! $role) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        // Check if the role has associated permissions
        if ($role->permissions()->count() > 0) {
            return response()->json(['error' => 'Cannot remove role. Role has associated permissions.'], 422);
        }

        // Check if the role is assigned to any user
        if ($role->users()->count() > 0) {
            return response()->json(['error' => 'Cannot remove role. Role is assigned to one or more users.'], 422);
        }

        try {
            $this->repository->delete($id);

            return $this->successResponse($role);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
