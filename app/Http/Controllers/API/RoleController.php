<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Requests\Role\CreateRoleRequest;
use App\Http\Resources\Role\RoleResource;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoleController extends Controller
{
    public function __construct()
    {

    }

    public function index(): AnonymousResourceCollection
    {
        $roles = Role::useFilters()->dynamicPaginate();

        return RoleResource::collection($roles);
    }

    public function store(CreateRoleRequest $request): JsonResponse
    {
        $role = Role::create($request->validated());

        return $this->responseCreated('Role created successfully', new RoleResource($role));
    }

    public function show(Role $role): JsonResponse
    {
        return $this->responseSuccess(null, new RoleResource($role));
    }

    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        $role->update($request->validated());

        return $this->responseSuccess('Role updated Successfully', new RoleResource($role));
    }

    public function destroy(Role $role): JsonResponse
    {
        $role->delete();

        return $this->responseDeleted();
    }

   
}
