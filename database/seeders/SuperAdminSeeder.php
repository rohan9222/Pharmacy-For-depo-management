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

        // $zse = User::create([
        //     'name' => 'Zonal Sales Executive',
        //     'email' => 'zse@gmail.com',
        //     'password' => Hash::make('zse@gmail.com'),
        //     'manager_id' => $manager->id,
        //     'role' => 'Zonal Sales Executive',
        //     'user_id' => 010503
        // ]);

        // $tse = User::create([
        //     'name' => 'Territory Sales Executive',
        //     'email' => 'tse@gmail.com',
        //     'password' => Hash::make('tse@gmail.com'),
        //     'zse_id' => $zse->id,
        //     'manager_id' => $manager->id,
        //     'role' => 'Territory Sales Executive',
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
        // $zse->assignRole('Zonal Sales Executive');
        // $tse->assignRole('Territory Sales Executive');
        // $depo_incharge->assignRole('Depo Incharge');

    }
}
