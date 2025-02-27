<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SupplierControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function supplier_can_view_their_products()
    {
        $supplier = User::factory()->create(['role' => 'supplier']);
        $product = Product::factory()->create(['supplier_id' => $supplier->id]);

        $response = $this->actingAs($supplier)->get('/api/supplier/products');

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $product->id]);
    }

    /** @test */
    public function supplier_can_view_orders()
    {
        $supplier = User::factory()->create(['role' => 'supplier']);
        // Create an order with items related to the supplier's products here

        $response = $this->actingAs($supplier)->get('/api/supplier/orders');

        $response->assertStatus(200);
    }
}
