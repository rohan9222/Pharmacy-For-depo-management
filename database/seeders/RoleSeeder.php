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
        $depo = Role::create(['name' => 'Depo Incharge']);
        $manager = Role::create(['name' => 'Manager']);
        $sales_manager = Role::create(['name' => 'Sales Manager']);
        $field_officer = Role::create(['name' => 'Field Officer']);
        $delivery_man = Role::create(['name' => 'Delivery Man']);

        $delivery_man->givePermissionTo([
            'view'
        ]);

        $depo->givePermissionTo([
            'create-delivery-man',
            'edit-delivery-man',
            'delete-delivery-man',
            'create',
            'edit',
            'delete',
            'view'
        ]);

        $manager->givePermissionTo([
            'admin-role',
            'create-user',
            'edit-user',
            'delete-user',
            'create-manager',
            'create-sales-manager',
            'edit-sales-manager',
            'delete-sales-manager',
            'create-field-officer',
            'edit-field-officer',
            'delete-field-officer',
            'create-customer',
            'edit-customer',
            'delete-customer',
            'create-supplier',
            'edit-supplier',
            'create',
            'edit',
            'delete',
            'view'
        ]);

        $sales_manager->givePermissionTo([
            'create-user',
            'edit-user',
            'delete-user',
            'create-sales-manager',
            'edit-sales-manager',
            'create-field-officer',
            'edit-field-officer',
            'delete-field-officer',
            'create-customer',
            'edit-customer',
            'delete-customer',
            'create',
            'edit',
            'delete',
            'view'
        ]);

        $field_officer->givePermissionTo([
            'create-field-officer',
            'edit-field-officer',
            'create-customer',
            'edit-customer',
            'delete-customer',
            'create',
            'edit',
            'delete',
            'view'
        ]);
    }
}
