<?php

namespace App\Services;

use App\Models\Product;

class ProductPriceService
{
    /**
     * Get price details for a single product.
     *
     * @param  \App\Models\Product|int  $product
     * @param  string|null  $customerType
     * @return array<string, mixed>
     */
    public static function getPrice(Product|int $product, ?string $customerType = null): array
    {
        if (is_numeric($product)) {
            $product = Product::with('attributes', 'category')->find($product);
        }

        $customerType ??= auth()->user()?->customer_type ?? 'umum';

        if (!$product) {
            return self::emptyPrice();
        }

        return self::calculate($product, $customerType);
    }

    /**
     * Get prices for multiple products.
     *
     * @param  iterable<Product|int>  $products
     * @param  string|null  $customerType
     * @return array<int, array<string, mixed>>
     */
    public static function getPrices(iterable $products, ?string $customerType = null): array
    {
        $results = [];

        foreach ($products as $product) {
            $id = $product instanceof Product ? $product->id : $product;
            $results[$id] = self::getPrice($product, $customerType);
        }

        return $results;
    }

    /**
     * Calculate price details for a single product based on type of customer.
     */
    public static function calculate(Product $product, string $customerType): array
    {
        $attribute = $product->attributes()->where('customer_type', $customerType)->first();

        if (!$attribute) {
            return self::emptyPrice($product);
        }

        $productDiscount  = $product->product_discount ?? 0;
        $categoryDiscount = $product->category?->category_discount ?? 0;

        $discountType     = $product->discount_type ?? ($productDiscount > 0 ? 'percent' : '');
        $discountPercent  = $productDiscount > 0 ? $productDiscount : ($categoryDiscount > 0 ? $categoryDiscount : 0);

        $discountAmount = $discountType === 'nominal'
            ? $productDiscount
            : $attribute->price * ($discountPercent / 100);

        $finalPrice = max(0, $attribute->price - $discountAmount);

        return [
            'product_price'    => $attribute->price,
            'final_price'      => $finalPrice,
            'discount'         => $discountAmount,
            'discount_percent' => $discountPercent,
            'discount_type'    => $discountType,
        ];
    }

    /**
     * Get only the final price for a single product.
     */
    public static function finalPrice(Product|int $product, ?string $customerType = null): float
    {
        return (float) self::getPrice($product, $customerType)['final_price'];
    }

    /**
     * Get only the final price for multiple products.
     * Returns associative array [product_id => final_price].
     */
    public static function finalPrices(iterable $products, ?string $customerType = null): array
    {
        $prices = self::getPrices($products, $customerType);

        return collect($prices)->mapWithKeys(fn ($p, $id) => [$id => $p['final_price']])->toArray();
    }

    /**
     * Empty price structure (default).
     */
    protected static function emptyPrice($product): array
    {
        return [
            'product_price'    => $product->product_price,
            'final_price'      => $product->product_price,
            'discount'         => 0,
            'discount_percent' => 0,
            'discount_type'    => '',
        ];
    }
}
