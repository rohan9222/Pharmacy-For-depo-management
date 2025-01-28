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
            'create-medicine',
            'edit-medicine',
            'view-medicine',
            'create-medicine-stock',
            'edit-medicine-stock',
            'view-medicine-stock',
            'create-delivery-man',
            'edit-delivery-man',
            'admin-role',
            'create-user',
            'edit-user',
            'create-manager',
            'create-sales-manager',
            'edit-sales-manager',
            'create-field-officer',
            'edit-field-officer',
            'create-customer',
            'edit-customer',
            'create-supplier',
            'edit-supplier',
            'view-report',
            'create-category',
            'edit-category',
            'invoice',
            'view-invoice',
            'return-medicine',
            'create',
            'edit',
            'view'
        ]);

        $manager->givePermissionTo([
            'admin-role',
            'create-user',
            'edit-user',
            'view-medicine',
            'create-manager',
            'view-medicine-stock',
            'create-sales-manager',
            'edit-sales-manager',
            'create-field-officer',
            'edit-field-officer',
            'create-customer',
            'edit-customer',
            'create-supplier',
            'edit-supplier',
            'view-report',
            'invoice',
            'view-invoice',
            'return-medicine',
            'create',
            'edit',
            'view'
        ]);

        $sales_manager->givePermissionTo([
            'create-user',
            'edit-user',
            'view-medicine',
            'create-sales-manager',
            'edit-sales-manager',
            'create-field-officer',
            'edit-field-officer',
            'create-customer',
            'edit-customer',
            'view-report',
            'invoice',
            'view-invoice',
            'return-medicine',
            'create',
            'edit',
            'view'
        ]);

        $field_officer->givePermissionTo([
            'create-user',
            'view-medicine',
            'create-field-officer',
            'edit-field-officer',
            'create-customer',
            'edit-customer',
            'view-report',
            'invoice',
            'view-invoice',
            'return-medicine',
            'create',
            'edit',
            'view'
        ]);
    }
}
