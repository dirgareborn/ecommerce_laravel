<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRecords = [
            [
            'id' => 1, 
            'category_id'=>1,
            'location_id'=>3,
            'product_name'=>'Hall A.P Pettarani',
            'product_facility'=>'[
            { "name": "WiFi" },
            { "name": "AC" },
            { "name": "Proyektor" },
            { "name": "Whiteboard" },
            { "name": "Kursi 400 unit" }
          ]',
            'product_price'=>17500000,
            'product_image'=>'',
            'product_description'=>'Content',
            'url' => 'hall-ap-pettarani',
            'meta_title' => 'Hall A.P Pettarani',
            'meta_description' => ' Content',
            'meta_keywords' => 'Hall Convenction, Gedung Nikah, Venue Wedding',
            'status'=>1,
            ],
    ];

        Product::insert($adminRecords);
    }
}
