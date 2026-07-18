<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Plant;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_can_be_placed_and_viewed_from_history(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $category = Category::create(['name' => 'Indoor']);
        $supplier = Supplier::create(['name' => 'Green Garden Co.']);

        $plant = Plant::create([
            'name' => 'Snake Plant',
            'category_id' => $category->category_id,
            'supplier_id' => $supplier->supplier_id,
            'price' => 120,
            'stock_qty' => 10,
            'description' => 'Hardy indoor plant.',
            'care_instructions' => 'Water weekly.',
            'image' => 'snake-plant.jpg',
        ]);

        Cart::create([
            'user_id' => $user->user_id,
            'plant_id' => $plant->plant_id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user)->post(route('order.place'));

        $response->assertRedirect();
        $redirectUrl = $response->headers->get('Location');
        $this->assertStringContainsString('/order-confirmation', $redirectUrl);

        $order = Order::where('user_id', $user->user_id)->first();
        $this->assertNotNull($order, 'No order was created.');
        $this->assertSame('Pending', $order->status);
        $this->assertSame(240.0, (float) $order->total_amount);

        $item = OrderItem::where('order_id', $order->order_id)->first();
        $this->assertNotNull($item, 'No order item was created.');
        $this->assertSame(2, (int) $item->quantity);
        $this->assertSame(120.0, (float) $item->unit_price);

        $plant->refresh();
        $this->assertSame(8, (int) $plant->stock_qty);

        $this->assertSame(0, Cart::where('user_id', $user->user_id)->count());

        $this->actingAs($user)
            ->get(route('order.confirmation', ['orderId' => $order->order_id]))
            ->assertStatus(200)
            ->assertSee('Snake Plant');

        $this->actingAs($user)
            ->get(route('order.history'))
            ->assertStatus(200)
            ->assertSee('Order History')
            ->assertSee((string) $order->order_id);
    }
}
