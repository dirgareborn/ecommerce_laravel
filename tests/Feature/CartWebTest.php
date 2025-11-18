<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CartWebTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $sessionId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        // buat user dengan customer_type (bisa dihapus jika kolom itu tidak ada)
        $this->user = User::factory()->create([
            'customer_type' => 'regular',
        ]);

        $this->sessionId = Str::uuid()->toString();
    }

    /** @test */
    public function user_can_add_product_to_cart()
    {
        $product = Product::factory()->create([
            'product_name'        => 'Sewa Aula Kampus',
            'url'                 => 'sewa-aula-kampus',
            'product_price'       => 500000,
            'final_price'         => 450000,
            'discount_type'       => 'percent',
            'product_discount'    => 10,
            'product_facility'    => json_encode([['name' => 'kursi'], ['name' => 'proyektor']]),
            'product_description' => 'Aula besar dengan kapasitas 200 orang',
            'product_image'       => 'images/aula.jpg',
            'meta_title'          => 'Sewa Aula Kampus Murah',
            'meta_description'    => 'Aula nyaman untuk seminar dan acara besar',
            'meta_keywords'       => 'aula, kampus, sewa, acara',
            'status'              => 1,
            'category_id'         => 1,
            'location_id'         => 1,
        ]);

        $response = $this->actingAs($this->user)
            ->post('/addCart', [
                '_token' => csrf_token(),
                'product_id' => $product->id,
                'qty'        => 2,
                'start_date' => Carbon::today()->toDateString(),
                'end_date'   => Carbon::today()->addDays(3)->toDateString(),
            ]);

        $response->assertRedirect('/cart');

        $this->assertDatabaseHas('carts', [
            'user_id'       => $this->user->id,
            'product_id'    => $product->id,
            'qty'           => 2,
        ]);
    }

    /** @test */
    public function user_can_view_cart_page()
    {
        $product = Product::factory()->create([
            'product_name'        => 'Sewa Ruang Rapat',
            'url'                 => 'sewa-ruang-rapat',
            'product_price'       => 300000,
            'final_price'         => 270000,
            'discount_type'       => 'percent',
            'product_discount'    => 10,
            'product_facility'    => json_encode([['name' => 'ac'], ['name' => 'wifi']]),
            'product_description' => 'Ruang rapat modern dengan fasilitas lengkap',
            'product_image'       => 'images/meeting.jpg',
            'meta_title'          => 'Sewa Ruang Rapat Kampus',
            'meta_description'    => 'Ruang rapat ber-AC dan nyaman untuk presentasi',
            'meta_keywords'       => 'rapat, sewa, kampus',
            'status'              => 1,
            'category_id'         => 1,
            'location_id'         => 1,
        ]);

        Cart::factory()->create([
            'session_id'    => $this->sessionId,
            'user_id'       => $this->user->id,
            'customer_type' => $this->user->customer_type,
            'product_id'    => $product->id,
            'qty'           => 1,
            'start_date'    => Carbon::today()->toDateString(),
            'end_date'      => Carbon::today()->addDays(1)->toDateString(),
        ]);

        $response = $this->actingAs($this->user)->get('/cart');

        $response->assertOk()
                 ->assertViewIs('front.carts.cart')
                 ->assertSee($product->product_name)
                 ->assertSee(number_format($product->final_price, 0, ',', '.'));
    }

    /** @test */
    public function user_can_remove_cart_item()
    {
        $product = Product::factory()->create([
            'product_name'        => 'Sewa Aula Utama',
            'url'                 => 'sewa-aula-utama',
            'product_price'       => 800000,
            'final_price'         => 700000,
            'discount_type'       => 'fixed',
            'product_discount'    => 100000,
            'product_facility'    => json_encode([['name' => 'sound system'], ['name' => 'panggung']]),
            'product_description' => 'Aula utama dengan fasilitas lengkap',
            'product_image'       => 'images/aula-utama.jpg',
            'meta_title'          => 'Sewa Aula Utama',
            'meta_description'    => 'Aula utama besar dan nyaman untuk acara besar',
            'meta_keywords'       => 'aula utama, sewa, acara kampus',
            'status'              => 1,
            'category_id'         => 1,
            'location_id'         => 1,
        ]);

        $cart = Cart::factory()->create([
            'session_id'    => $this->sessionId,
            'user_id'       => $this->user->id,
            'customer_type' => $this->user->customer_type,
            'product_id'    => $product->id,
            'qty'           => 1,
            'start_date'    => Carbon::today()->toDateString(),
            'end_date'      => Carbon::today()->addDays(1)->toDateString(),
        ]);

        $response = $this->actingAs($this->user)
            ->get("/cart/delete-cart-item/{$cart->id}");

        $response->assertRedirect('/cart');
        $this->assertDatabaseMissing('carts', ['id' => $cart->id]);
    }

    /** @test */
    public function user_can_apply_coupon()
    {
        $response = $this->actingAs($this->user)
            ->post('/apply-coupon', [
                'coupon_code' => 'DISKON10',
            ]);

        $response->assertRedirect();
    }

    /** @test */
    // public function user_can_checkout_cart()
    // {
    //     $product = Product::factory()->create([
    //         'product_name'        => 'Sewa Studio Musik',
    //         'url'                 => 'sewa-studio-musik',
    //         'product_price'       => 150000,
    //         'final_price'         => 120000,
    //         'discount_type'       => 'percent',
    //         'product_discount'    => 20,
    //         'product_facility'    => json_encode([['name' => 'drum'], ['name' => 'amplifier']]),
    //         'product_description' => 'Studio musik dengan alat lengkap',
    //         'product_image'       => 'images/studio.jpg',
    //         'meta_title'          => 'Sewa Studio Musik Kampus',
    //         'meta_description'    => 'Studio musik dengan fasilitas lengkap',
    //         'meta_keywords'       => 'musik, studio, sewa',
    //         'status'              => 1,
    //         'category_id'         => 1,
    //         'location_id'         => 1,
    //     ]);

    //     Cart::factory()->create([
    //         'session_id'    => $this->sessionId,
    //         'user_id'       => $this->user->id,
    //         'customer_type' => $this->user->customer_type,
    //         'product_id'    => $product->id,
    //         'qty'           => 1,
    //         'start_date'    => Carbon::today()->toDateString(),
    //         'end_date'      => Carbon::today()->addDays(1)->toDateString(),
    //     ]);

    //     $get = $this->actingAs($this->user)->get('/checkout');
    //     $get->assertOk()->assertViewIs('front.carts.checkout');

    //     $post = $this->actingAs($this->user)
    //         ->post('/checkout', [
    //             '_token' => csrf_token(),
    //             'payment_method' => 'transfer',
    //             'address' => 'Jl. Kampus Raya No. 12',
    //         ]);

    //     $post->assertRedirect();
    // }
}
