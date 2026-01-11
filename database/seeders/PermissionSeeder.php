<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Permission;
use App\Models\PermissionSubject;
use App\Models\Role;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Subjects
        $subjects = [
            'Customers',
            'Users',
            'Roles',
            'Products',
        ];

        $subjectModels = [];
        foreach ($subjects as $name) {
            $subjectModels[$name] = PermissionSubject::firstOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name)]
            );
        }

        // 2. Define Permissions per Subject
        $permissions = [
            'Customers' => ['view', 'create', 'update', 'delete', 'manage'],
            'Users' => ['view', 'create', 'update', 'delete', 'manage'],
            'Roles' => ['view', 'create', 'update', 'delete', 'manage'],
            'Products' => ['view', 'create', 'update', 'delete', 'manage'],
        ];

        foreach ($permissions as $subjectName => $actions) {
            $subject = $subjectModels[$subjectName];
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => Str::slug($subjectName) . '_' . $action,
                    'guard_name' => 'web',
                    'subject_id' => $subject->id
                ]);
            }
        }

        // 3. Assign to Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $user = Role::firstOrCreate(['name' => 'user']);

        // Admin gets everything
        $admin->syncPermissions(Permission::all());

        // User gets only customer view
        $userViewPerms = Permission::where('name', 'like', 'customers_%')->get();
        $user->syncPermissions($userViewPerms);
    }
}
