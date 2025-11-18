<?php

use App\Services\ProductPriceService;

if (!function_exists('priceHtml')) {
    function priceHtml($product): string
    {
        $userType = auth()->user()?->customer_type ?? 'umum';
        $price = ProductPricingService::getPrice($product, $userType);

        $formatted = '<del>' . number_format($price['product_price'], 0, ',', '.') . '</del> ';
        $formatted .= '<strong>' . number_format($price['final_price'], 0, ',', '.') . '</strong>';

        if ($price['discount_percent'] > 0) {
            $formatted .= " <span class='badge bg-success'>{$price['discount_percent']}% OFF</span>";
        }

        return $formatted;
    }
}
