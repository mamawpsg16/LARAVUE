<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Module;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $admin = User::create([
            'username' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
        ]);

        $adminRole = Role::create([
            'name' => 'Admin',
        ]);

        $userRole = Role::create([
            'name' => 'User',
        ]);

        $roleUser = DB::table('role_user')->insert([
            'role_id' => $adminRole->id,
            'user_id' => $admin->id
        ]);

        $modules = [
            [
                'name' => 'Tasks',
                'route' => '/tasks',
                'icon' => 'fa-solid fa-list',
                'description' => 'Manage Tasks',
            ],
            [
                'name' => 'Modules',
                'route' => '/modules',
                'icon' => 'fa-solid fa-book-open-reader',
                'description' => 'Modules',
            ],
            [
                'name' => 'Permissions',
                'route' => '/permissions',
                'icon' => 'fa-solid fa-pen-to-square',
                'description' => 'Permissions',
            ],
            [
                'name' => 'Role Access',
                'route' => '/role-access',
                'icon' => 'fa-solid fa-feather-pointed',
                'description' => 'Role Access',
            ],
            [
                'name' => 'User Roles',
                'route' => '/user-roles',
                'icon' => 'fa-solid fa-address-card',
                'description' => 'User Roles',
            ],
        ];

        $permissions = [
            ['name' => 'Create'],
            ['name' => 'Read'],
            ['name' => 'Update'],
            ['name' => 'Delete'],
        ];

        foreach ($modules as $moduleData) {
            $module = Module::create($moduleData);

            foreach ($permissions as $permissionData) {
                $permission = Permission::firstOrCreate($permissionData);

                DB::table('role_module_permission')->insert([
                    'role_id' => $adminRole->id,
                    'module_id' => $module->id,
                    'permission_id' => $permission->id
                ]);
            }
        }
    }
}
