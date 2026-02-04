<?php

declare(strict_types=1);

use App\Events\OrderCreated;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Event;

beforeEach(function (): void {
    test()->admin = User::factory()->admin_notifiable()->create();
    test()->user = User::factory()->customer()->create();
});

describe('OrderCreated Event', function (): void {
    it('is dispatched when order is created', function (): void {
        Event::fake();

        Order::factory()->create();

        Event::assertDispatched(OrderCreated::class);
    });
});
