<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        $price = $this->faker->numberBetween(10000, 100000);

        return [
            'product_name'        => $name,
            'url'                 => Str::slug($name . '-' . $this->faker->unique()->numberBetween(1, 9999)),
            'product_price'       => $price,
            'final_price'         => (int) ($price * (1 - 0.10)), // contoh diskon 10%
            'discount_type'       => 'percent',
            'product_discount'    => 10,
            'product_facility'    => json_encode([['name' => 'lapangan']]),
            'product_description' => $this->faker->sentence(10),
            'product_image'       => $this->faker->imageUrl(640, 480, 'products'),
            'meta_title'          => $this->faker->sentence(6),
            'meta_description'    => $this->faker->sentence(12),
            'meta_keywords'       => implode(', ', $this->faker->words(5)),
            'status'              => '1',
            'category_id'         => 1,
            'location_id'         => 1,
            'created_at'          => Carbon::now(),
            'updated_at'          => Carbon::now(),
        ];
    }
}
