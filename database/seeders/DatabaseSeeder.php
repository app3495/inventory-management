<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Type;
use App\Models\Unit;
use App\Models\User;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Support\Str;
use App\Models\InventoryLine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // Product::factory()->count(5)->create();


        Product::factory()->create([
            'code' => "001",
            'name' => "Biogesic",
            'description' => 'for illness good.',
        ]);

        Product::factory()->create([
            'code' => "002",
            'name' => "Oramin-G",
            'description' => 'for men',
        ]);

        Product::factory()->create([
            'code' => "003",
            'name' => "Oramin-F",
            'description' => 'for women',
        ]);

        Product::factory()->create([
            'code' => "004",
            'name' => "Air-x",
            'description' => 'for stomach',
        ]);

        Product::factory()->create([
            'code' => "005",
            'name' => "Liv-up",
            'description' => 'good for alcoholist',
        ]);



        Unit::factory()->create([
            "code" => "pc" ,
            "name" => "piece"
        ]);

        Unit::factory()->create([
            "code" => "box" ,
            "name" => "box"
        ]);

        Unit::factory()->create([
            "code" => "pkg" ,
            "name" => "package"
        ]);

        Type::factory()->create([
            "name" => "In"
        ]);

        Type::factory()->create([
            "name" => "out"
        ]);

        for ($i = 1; $i <= 50; $i++) {
            Inventory::factory()->create([
                'date' => Carbon::now(),
                'type_id' => $i < 30 ? 1 : rand(1,2),
                'doc_no' => Str::random(10),
                'description' => Str::random(20),
            ]);
        }

        for ($i = 1; $i <= 200; $i++) {
            InventoryLine::factory()->create([
                'inventory_id' => rand(1, 50),
                'product_id' => rand(1,5),
                'qty' => rand(1,10),
                'unit_id' => rand(1,2),
                'price' => rand(100,20000),
            ]);
        }

        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('Admin123')
        ]);


    }
}
