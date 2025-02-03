<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Medicine;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\SiteSetting;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(20)->withPersonalTeam()->create();

        // User::factory()->withPersonalTeam()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@gmail.com',
        // ]);

        $this->call([
            // PersonnelInfosSeeder::class,
            // Add more seeders if necessary
            PermissionSeeder::class,
            RoleSeeder::class,
            SuperAdminSeeder::class,
        ]);

        Supplier::create([
            'name' => 'ACME',
            'mobile' => '1234567890',
            'email' => 'OQ0U4@example.com',
            'supplier_type' => 'supplier',
        ]);

        Category::create([
            'name' => 'ACME',
        ]);

        SiteSetting::create([
            'site_name' => 'IMPEXPHARMABD',
            'site_title' => 'IMPEXPHARMABD',
        ]);

        Medicine::insert([
            [
                'barcode' => '1234567890',
                'name' => 'Aztrum A-Z',
                'category_name' => 'ACME',
                'generic_name' => 'Paracetamol',
                'supplier' => 'ACME',
                'shelf' => 'ACME',
                'quantity' => 100,  // Set the initial quantity to 100
                'price' => 157.42,
                'vat' => 17.4,
            ],
            [
                'barcode' => '1234567891',
                'name' => 'Coralgen',
                'category_name' => 'ACME',
                'generic_name' => 'Ibuprofen',
                'supplier' => 'ACME',
                'shelf' => 'ACME',
                'quantity' => 50,  // Set the initial quantity to 50
                'price' => 225.00,
                'vat' => 17.4,
            ],
            [
                'barcode' => '1234567892',
                'name' => 'Losarpex',
                'category_name' => 'ACME',
                'generic_name' => 'Ibuprofen',
                'supplier' => 'ACME',
                'shelf' => 'ACME',
                'quantity' => 50,  // Set the initial quantity to 50
                'price' => 180.00,
                'vat' => 17.4,
            ],
            [
                'barcode' => '1234567893',
                'name' => 'Progend',
                'category_name' => 'ACME',
                'generic_name' => 'Ibuprofen',
                'supplier' => 'ACME',
                'shelf' => 'ACME',
                'quantity' => 50,  // Set the initial quantity to 50
                'price' => 180.00,
                'vat' => 17.4,
            ],
            [
                'barcode' => '1234567894',
                'name' => 'Clonaplex',
                'category_name' => 'ACME',
                'generic_name' => 'Ibuprofen',
                'supplier' => 'ACME',
                'shelf' => 'ACME',
                'quantity' => 50,  // Set the initial quantity to 50
                'price' => 299.85,
                'vat' => 17.4,
            ],
            [
                'barcode' => '1234567895',
                'name' => 'Metle',
                'category_name' => 'ACME',
                'generic_name' => 'Ibuprofen',
                'supplier' => 'ACME',
                'shelf' => 'ACME',
                'quantity' => 50,  // Set the initial quantity to 50
                'price' => 150.00,
                'vat' => 17.4,
            ],
            [
                'barcode' => '1234567896',
                'name' => 'Xiva',
                'category_name' => 'ACME',
                'generic_name' => 'Ibuprofen',
                'supplier' => 'ACME',
                'shelf' => 'ACME',
                'quantity' => 50,  // Set the initial quantity to 50
                'price' => 134.94,
                'vat' => 17.4,
            ],
            [
                'barcode' => '1234567897',
                'name' => 'Telmisat',
                'category_name' => 'ACME',
                'generic_name' => 'Ibuprofen',
                'supplier' => 'ACME',
                'shelf' => 'ACME',
                'quantity' => 50,  // Set the initial quantity to 50
                'price' => 269.87,
                'vat' => 17.4,
            ],
            [
                'barcode' => '1234567898',
                'name' => 'Leride',
                'category_name' => 'ACME',
                'generic_name' => 'Ibuprofen',
                'supplier' => 'ACME',
                'shelf' => 'ACME',
                'quantity' => 50,  // Set the initial quantity to 50
                'price' => 134.94,
                'vat' => 17.4,
            ],
            [
                'barcode' => '1234567899',
                'name' => 'Redep',
                'category_name' => 'ACME',
                'generic_name' => 'Ibuprofen',
                'supplier' => 'ACME',
                'shelf' => 'ACME',
                'quantity' => 50,  // Set the initial quantity to 50
                'price' => 157.42,
                'vat' => 17.4,
            ],
            [
                'barcode' => '1234567999',
                'name' => 'Clonpex',
                'category_name' => 'ACME',
                'generic_name' => 'Ibuprofen',
                'supplier' => 'ACME',
                'shelf' => 'ACME',
                'quantity' => 50,  // Set the initial quantity to 50
                'price' => 243.63,
                'vat' => 17.4,
            ],
            [
                'barcode' => '1234547890',
                'name' => 'Lerupa',
                'category_name' => 'ACME',
                'generic_name' => 'Ibuprofen',
                'supplier' => 'ACME',
                'shelf' => 'ACME',
                'quantity' => 50,  // Set the initial quantity to 50
                'price' => 213.65,
                'vat' => 17.4,
            ],
            [
                'barcode' => '1235567890',
                'name' => 'Legaba 25',
                'category_name' => 'ACME',
                'generic_name' => 'Ibuprofen',
                'supplier' => 'ACME',
                'shelf' => 'ACME',
                'quantity' => 50,  // Set the initial quantity to 50
                'price' => 202.40,
                'vat' => 17.4,
            ],
            [
                'barcode' => '1237567890',
                'name' => 'Zapitor',
                'category_name' => 'ACME',
                'generic_name' => 'Ibuprofen',
                'supplier' => 'ACME',
                'shelf' => 'ACME',
                'quantity' => 50,  // Set the initial quantity to 50
                'price' => 247.38,
                'vat' => 17.4,
            ],
            [
                'barcode' => '1234467890',
                'name' => 'Rabegend',
                'category_name' => 'ACME',
                'generic_name' => 'Ibuprofen',
                'supplier' => 'ACME',
                'shelf' => 'ACME',
                'quantity' => 50,  // Set the initial quantity to 50
                'price' => 599.70,
                'vat' => 17.4,
            ],
            [
                'barcode' => '1232567890',
                'name' => 'Losarpex Plus',
                'category_name' => 'ACME',
                'generic_name' => 'Ibuprofen',
                'supplier' => 'ACME',
                'shelf' => 'ACME',
                'quantity' => 50,  // Set the initial quantity to 50
                'price' => 202.40,
                'vat' => 17.4,
            ],
            [
                'barcode' => '1239567890',
                'name' => 'legaba 50',
                'category_name' => 'ACME',
                'generic_name' => 'Ibuprofen',
                'supplier' => 'ACME',
                'shelf' => 'ACME',
                'quantity' => 50,  // Set the initial quantity to 50
                'price' => 292.35,
                'vat' => 17.4,
            ],
            [
                'barcode' => '1244567890',
                'name' => 'Redep 10',
                'category_name' => 'ACME',
                'generic_name' => 'Ibuprofen',
                'supplier' => 'ACME',
                'shelf' => 'ACME',
                'quantity' => 50,  // Set the initial quantity to 50
                'price' => 247.38,
                'vat' => 17.4,
            ]
        ]);


    }
}
