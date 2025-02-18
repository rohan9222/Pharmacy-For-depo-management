<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creating Super Admin User
        $superAdmin = User::create(
            // [
            //     'name' => 'Md. Jahangir Alam Rohan',
            //     'email' => 'rohan9222@gmail.com',
            //     'password' => Hash::make('rohan9222@gmail.com'),
            //     'role' => 'Super Admin',
            //     'user_id' => 010500
            // ],
            [
                'name' => 'Super Admin',
                'email' => 'super_admin@gmail.com',
                'password' => Hash::make('super_admin@gmail.com'),
                'role' => 'Super Admin',
                'user_id' => 010500
            ]
        );
        // $manager = User::create([
        //     'name' => 'Manager',
        //     'email' => 'manager@gmail.com',
        //     'password' => Hash::make('manager@gmail.com'),
        //     'role' => 'Manager',
        //     'user_id' => 010502
        // ]);

        // $sales_manager = User::create([
        //     'name' => 'Sales Manager',
        //     'email' => 'sales_manager@gmail.com',
        //     'password' => Hash::make('sales_manager@gmail.com'),
        //     'manager_id' => $manager->id,
        //     'role' => 'Sales Manager',
        //     'user_id' => 010503
        // ]);

        // $field_officer = User::create([
        //     'name' => 'Field Officer',
        //     'email' => 'field_officer@gmail.com',
        //     'password' => Hash::make('field_officer@gmail.com'),
        //     'sales_manager_id' => $sales_manager->id,
        //     'manager_id' => $manager->id,
        //     'role' => 'Field Officer',
        //     'user_id' => 010504
        // ]);

        // $depo_incharge = User::create([
        //     'name' => 'Depo Incharge',
        //     'email' => 'depo_incharge@gmail.com',
        //     'password' => Hash::make('depo_incharge@gmail.com'),
        //     'role' => 'Depo Incharge',
        //     'user_id' => 010505
        // ]);

        $superAdmin->assignRole('Super Admin');
        // $manager->assignRole('Manager');
        // $sales_manager->assignRole('Sales Manager');
        // $field_officer->assignRole('Field Officer');
        // $depo_incharge->assignRole('Depo Incharge');

    }
}
