<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Find or create Super Admin user
        $user = User::updateOrCreate(
            ['email' => 'super_admin@lab.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('123456'),
                'token' => \Str::random(32),
            ]
        );

        // Find or create super_admin role
        $role = Role::updateOrCreate(
            ['name' => 'super_admin']
        );

        // Assign all permissions to the super_admin role (add missing ones)
        $permissions = Permission::all();

        foreach ($permissions as $permission) {
            RolePermission::updateOrCreate(
                [
                    'role_id' => $role->id,
                    'permission_id' => $permission->id
                ]
            );
        }

        // Assign the role to the user (if not already assigned)
        UserRole::updateOrCreate(
            [
                'user_id' => $user->id,
                'role_id' => $role->id
            ]
        );
    }
}
