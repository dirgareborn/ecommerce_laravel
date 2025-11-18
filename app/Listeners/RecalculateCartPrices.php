<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Session;
use App\Services\ProductPriceService;
use App\Models\Cart;
use Illuminate\Support\Facades\Log;

class RecalculateCartPrices
{
    /**
     * Handle event Login dan Logout.
     */
    public function handle($event): void
    {
    $customerType = match (true) {
        $event instanceof Login  => $event->user->customer_type ?? 'umum',
        $event instanceof Logout => 'umum',
        default => 'umum',
    };

    Log::info("🧮 RecalculateCartPrices triggered for {$customerType}");

        /** 
         * Update cart di database (jika kamu simpan cart di DB)
         */
        if ($event instanceof Login) {
            $userId = $event->user->id;
            $carts = Cart::where('user_id', $userId)->get();

            foreach ($carts as $cart) {
                $priceData = ProductPriceService::getPrice($cart->product_id, $customerType);

                $cart->update([
                    'customer_type' => $customerType,
                    'price' => $priceData['final_price'],
                ]);
            }

            Log::info("🧾 Updated cart prices in DB for user {$userId}");
        }

        /** 
         * Update cart di session (jika kamu simpan juga di session)
         */
        $cartSession = Session::get('cart', []);

        if (!empty($cartSession)) {
            $items = $cartSession['items'] ?? $cartSession;

            foreach ($items as &$item) {
                $priceData = ProductPriceService::getPrice($item['product_id'], $customerType);
                $item['price'] = $priceData['final_price'];
                $item['customer_type'] = $customerType;
            }

            if (isset($cartSession['items'])) {
                $cartSession['items'] = $items;
            } else {
                $cartSession = $items;
            }

            Session::put('cart', $cartSession);
            Log::info("💾 Updated cart prices in session for {$customerType}");
        }
    }

}