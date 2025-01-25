<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'admin-role',
            'create-role',
            'edit-role',
            'delete-role',
            'create-user',
            'edit-user',
            'delete-user',
            'create-medicine',
            'edit-medicine',
            'delete-medicine',
            'create-medicine-stock',
            'edit-medicine-stock',
            'delete-medicine-stock',
            'create-depo-manager',
            'edit-depo-manager',
            'delete-depo-manager',
            'create-manager',
            'edit-manager',
            'delete-manager',
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
            'delete-supplier',
            'create-delivery-man',
            'edit-delivery-man',
            'delete-delivery-man',
            'create-category',
            'edit-category',
            'delete-category',
            'create',
            'edit',
            'delete',
            'view'
         ];

          // Looping and Inserting Array's Permissions into Permission Table
         foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
          }
    }
}
