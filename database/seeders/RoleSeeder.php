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
        $zse = Role::create(['name' => 'Zonal Sales Executive']);
        $tse = Role::create(['name' => 'Territory Sales Executive']);
        $delivery_man = Role::create(['name' => 'Delivery Man']);

        $delivery_man->givePermissionTo([
            'view'
        ]);

        $depo->givePermissionTo([
            'admin-role',
            'create-medicine',
            'edit-medicine',
            'view-medicine',
            'create-medicine-stock',
            'edit-medicine-stock',
            'view-medicine-stock',
            'create-delivery-man',
            'edit-delivery-man',
            'delivery-report',
            'admin-role',
            'create-user',
            'edit-user',
            'create-manager',
            'edit-manager',
            'create-zonal-sales-executive',
            'edit-zonal-sales-executive',
            'create-territory-sales-executive',
            'edit-territory-sales-executive',
            'create-customer',
            'view-customer',
            'edit-customer',
            'create-supplier',
            'edit-supplier',
            'view-report',
            'create-category',
            'edit-category',
            'invoice',
            'make-payment',
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
            'view-medicine-stock',
            'create-zonal-sales-executive',
            'edit-zonal-sales-executive',
            'create-territory-sales-executive',
            'edit-territory-sales-executive',
            'create-customer',
            'view-customer',
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

        $zse->givePermissionTo([
            'admin-role',
            'create-user',
            'edit-user',
            'view-medicine',
            'create-territory-sales-executive',
            'edit-territory-sales-executive',
            'create-customer',
            'view-customer',
            'edit-customer',
            'view-report',
            'invoice',
            'view-invoice',
            'return-medicine',
            'create',
            'edit',
            'view'
        ]);

        $tse->givePermissionTo([
            'admin-role',
            'view-medicine',
            'create-customer',
            'view-customer',
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
