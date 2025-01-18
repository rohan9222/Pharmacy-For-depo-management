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
        $superAdmin = User::create([
            'name' => 'Md. Jahangir Alam Rohan',
            'email' => 'rohan9222@gmail.com',
            'password' => Hash::make('rohan9222@gmail.com')
        ]);
        $manager = User::create([
            'name' => 'Manager',
            'email' => 'manager@gmail.com',
            'password' => Hash::make('manager@gmail.com')
        ]);

        $sales_manager = User::create([
            'name' => 'Sales Manager',
            'email' => 'sales_manager@gmail.com',
            'password' => Hash::make('sales_manager@gmail.com')
        ]);

        $field_officer = User::create([
            'name' => 'Field Officer',
            'email' => 'field_officer@gmail.com',
            'password' => Hash::make('field_officer@gmail.com')
        ]);

        $depo_incharge = User::create([
            'name' => 'Depo Incharge',
            'email' => 'depo_incharge@gmail.com',
            'password' => Hash::make('depo_incharge@gmail.com')
        ]);

        $superAdmin->assignRole('Super Admin');
        $manager->assignRole('Manager');
        $sales_manager->assignRole('Sales Manager');
        $field_officer->assignRole('Field Officer');
        $depo_incharge->assignRole('Depo Incharge');

    }
}
