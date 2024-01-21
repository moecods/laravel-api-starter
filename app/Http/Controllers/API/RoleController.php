<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Role\CreateRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Resources\Role\RoleResource;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoleController extends Controller
{
    public function __construct()
    {

    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'pagination' => 'in:none',
            'per_page' => 'integer|min:1',
            'search' => 'string',
        ]);

        $roles = Role::with('permissions')->useFilters()->dynamicPaginate();

        return RoleResource::collection($roles);
    }

    public function store(CreateRoleRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $role = Role::create($validated);
        $role->givePermissionTo($validated['permissions']);

        return $this->responseCreated('Role created successfully', new RoleResource($role->load('permissions')));
    }

    public function show(Role $role): JsonResponse
    {
        return $this->responseSuccess(null, new RoleResource($role));
    }

    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        $validated = $request->validated();
        $role->update($validated);
        $role->syncPermissions($validated['permissions']);

        return $this->responseSuccess('Role updated Successfully', new RoleResource($role->load('permissions')));
    }

    public function destroy(Role $role): JsonResponse
    {
        $role->load('permissions', 'users');

        if ($role->permissions()->count() > 0) {
            return $this->responseConflictError(
                'Cannot remove role. Role has associated permissions.',
                'Please first remove role\'s permissions',
            );
        }

        if ($role->users()->count() > 0) {
            return $this->responseConflictError(
                'Cannot remove role. Role is assigned to one or more users.',
                'Cannot remove role. Role is assigned to one or more users.',
            );
        }

        $role->delete();

        return $this->responseDeleted();
    }
}
