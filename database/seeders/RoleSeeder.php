<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Super Admin']);
        $admin = Role::create(['name' => 'Admin']);
        $operator = Role::create(['name' => 'Operator']);
        $user = Role::create(['name' => 'User']);

        $admin->givePermissionTo([
            'admin-role',
            'create-user',
            'edit-user',
            'delete-user',
            'create',
            'edit',
            'delete',
            'view'
        ]);
        $operator->givePermissionTo([
            'create',
            'edit',
            'delete',
            'view'
        ]);

        $user->givePermissionTo([
            'view'
        ]);

    }
}
