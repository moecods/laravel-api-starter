<?php

namespace Database\Seeders;

use App\Models\User;
use App\Repositories\UserRepo;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    private UserRepo $userRepo;

    public function __construct(UserRepo $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function run(): void
    {
        $role = Role::findOrCreate('user-manger');
        $role->givePermissionTo(Permission::findOrCreate('manage-users'));
        $role->givePermissionTo(Permission::findOrCreate('view-users'));

        $role = Role::findOrCreate('role-manger');
        $role->givePermissionTo(Permission::findOrCreate('manage-roles'));
        $role->givePermissionTo(Permission::findOrCreate('view-roles'));

        $admin = $this->userRepo->findOrCreate(User::factory(['name' => 'admin', 'email' => 'admin@example.com'])->raw());
        $admin->assignRole('role-manger');

        $userManager = $this->userRepo->findOrCreate(User::factory(['name' => 'user manager', 'email' => 'usermanager@example.com'])->raw());
        $userManager->assignRole('user-manger');

        $roleManager = $this->userRepo->findOrCreate(User::factory(['name' => 'role manager', 'email' => 'rolemanager@example.com'])->raw());
        $roleManager->assignRole('role-manger');
    }
}
