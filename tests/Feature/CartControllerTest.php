<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_cart()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        Cart::factory()->create(['user_id' => $user->id, 'product_id' => $product->id]);

        $response = $this->actingAs($user)->get('/api/cart');

        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    /** @test */
    public function user_can_add_product_to_cart()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->post('/api/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['quantity' => 2]);
    }

    /** @test */
    public function user_can_remove_product_from_cart()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $cartItem = Cart::factory()->create(['user_id' => $user->id, 'product_id' => $product->id]);

        $response = $this->actingAs($user)->delete('/api/cart/remove/' . $cartItem->id);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Cart item removed']);
    }
}
