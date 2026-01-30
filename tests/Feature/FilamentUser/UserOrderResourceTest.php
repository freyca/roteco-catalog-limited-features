<?php

declare(strict_types=1);

use App\Filament\User\Resources\Orders\Pages\ListOrders;
use App\Filament\User\Resources\Orders\Pages\ViewOrder;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Livewire;

beforeEach(function (): void {
    // Create an admin user for notifications
    test()->admin = User::factory()->admin_notifiable()->create();
    test()->user = User::factory()->create();
    test()->otherUser = User::factory()->create();
});

it('does not show edit order button', function (): void {
    $user = test()->user;
    test()->actingAs($user);
    $order = Order::factory()->for($user)->create();

    // Check in the order list page
    $component = Livewire::test(ListOrders::class)
        ->assertSuccessful();
    $component->assertDontSee('Edit');

    // Check in the order view page
    $component = Livewire::test(ViewOrder::class, ['record' => $order->id])
        ->assertSuccessful();
    $component->assertDontSee('Edit');
});

it('shows only own orders', function (): void {
    $user = test()->user;
    $otherUser = test()->otherUser;
    test()->actingAs($user);
    $myOrders = Order::factory()->count(2)->for($user)->create();
    $otherOrders = Order::factory()->count(2)->for($otherUser)->create();

    $component = Livewire::test(ListOrders::class)
        ->assertSuccessful();

    foreach ($myOrders as $order) {
        $component->assertSee((string) $order->id);
    }
    foreach ($otherOrders as $order) {
        $component->assertDontSee((string) $order->id);
    }
});

it('cannot view other users order', function (): void {
    $user = test()->user;
    $otherUser = test()->otherUser;
    test()->actingAs($user);
    $otherOrder = Order::factory()->for($otherUser)->create();

    expect(fn () => Livewire::test(ViewOrder::class, ['record' => $otherOrder->id]))
        ->toThrow(ModelNotFoundException::class);

    expect(fn () => test()->get(route('filament.user.resources.orders.view', ['record' => $otherOrder->id])))
        ->toThrow(ModelNotFoundException::class);
});
