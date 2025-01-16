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
            'email' => 'rohan471111@gmail.com',
            'password' => Hash::make('rohan471111@gmail.com')
        ]);
        $superAdmin->assignRole('Super Admin');

    }
}
