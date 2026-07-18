<?php

namespace Tests\Feature;

use Tests\TestCase;

class OrderFlowTest extends TestCase
{
    public function test_order_can_be_placed_and_viewed_from_history(): void
    {
        $this->withSession([
            'cart' => [
                [
                    'id' => 1,
                    'name' => 'Snake Plant',
                    'price' => 120,
                    'quantity' => 2,
                    'image' => 'snake-plant.jpg',
                ],
            ],
        ])->post(route('order.place'))
            ->assertRedirect(route('order.confirmation'));

        $orders = session('orders', []);

        $this->assertCount(1, $orders);
        $this->assertSame('Pending', $orders[0]['status']);
        $this->assertSame(240.0, $orders[0]['total']);
        $this->assertSame([], session('cart', []));

        $this->get(route('order.confirmation'))
            ->assertStatus(200)
            ->assertSee('Snake Plant')
            ->assertSee('Continue Shopping');

        $this->get(route('order.history'))
            ->assertStatus(200)
            ->assertSee('Order History')
            ->assertSee($orders[0]['order_id']);
    }
}
