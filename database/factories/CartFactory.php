<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition(): array
    {
        return [
            'session_id'    => Str::uuid()->toString(),
            'user_id'       => User::factory(),
            'customer_type' => 'umum',
            'product_id'    => Product::factory(),
            'start_date'    => Carbon::today()->toDateString(),
            'end_date'      => Carbon::today()->addDays(1)->toDateString(),
            'qty'           => $this->faker->numberBetween(1, 5),
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ];
    }
}
