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

        $roleUser = DB::table('role_user')->insert([
            'role_id' => $adminRole->id,
            'user_id' => $admin->id
        ]);

        $modules = [
            [
                'name' => 'Tasks',
                'route' => '/tasks',
                'description' => 'Manage Tasks',
            ],
            // [
            //     'name' => 'Roles',
            //     'route' => '/roles',
            //     'description' => 'Roles',
            // ],
            [
                'name' => 'Modules',
                'route' => '/modules',
                'description' => 'Modules',
            ],
            [
                'name' => 'Permissions',
                'route' => '/permissions',
                'description' => 'Permissions',
            ],
            [
                'name' => 'Role Access',
                'route' => '/role-access',
                'description' => 'Role Access',
            ],
            [
                'name' => 'User Roles',
                'route' => '/user-roles',
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
