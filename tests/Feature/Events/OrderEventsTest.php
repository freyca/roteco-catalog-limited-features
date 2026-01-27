<?php

declare(strict_types=1);

use App\Events\OrderCreated;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Event;

beforeEach(function (): void {
    test()->admin = User::factory()->admin_notifiable()->create();
    test()->user = User::factory()->customer()->create();
    test()->order = Order::factory()->create(['user_id' => test()->user->id]);
});

describe('OrderCreated Event', function (): void {
    it('can be constructed with an order', function (): void {
        $order = Order::factory()->create();
        $event = new OrderCreated($order);
        expect($event->order)->toBe($order);
    });

    it('is dispatched when order is created', function (): void {
        Event::fake();
        $order = Order::factory()->create();
        event(new OrderCreated($order));
        Event::assertDispatched(OrderCreated::class, fn ($e): bool => $e->order->id === $order->id);
    });

    it('order instance is retrievable from event', function (): void {
        $order = Order::factory()->create();
        $event = new OrderCreated($order);
        expect($event->order->id)->toBe($order->id);
    });
});
