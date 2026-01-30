<?php

declare(strict_types=1);

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\Role;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    test()->admin = User::factory()->admin_notifiable()->create();
    test()->user = User::factory()->create(['role' => Role::Customer]);
});

describe('Payment Service - Repositories and Logic', function (): void {
    it('can retrieve orders by payment method', function (): void {
        Order::factory()->create(['payment_method' => PaymentMethod::BankTransfer]);
        Order::factory()->create(['payment_method' => PaymentMethod::Card]);

        $bankTransferOrders = Order::query()->where('payment_method', PaymentMethod::BankTransfer)->get();

        expect($bankTransferOrders)->toHaveCount(1);
    });

    it('can retrieve pending payment orders', function (): void {
        Order::factory()->create(['status' => OrderStatus::PaymentPending]);
        Order::factory()->create(['status' => OrderStatus::Paid]);

        $pendingOrders = Order::query()->where('status', OrderStatus::PaymentPending)->get();

        expect($pendingOrders)->toHaveCount(1);
    });

    it('can retrieve paid orders', function (): void {
        Order::factory()->create(['status' => OrderStatus::Paid]);
        Order::factory()->create(['status' => OrderStatus::PaymentPending]);

        $paidOrders = Order::query()->where('status', OrderStatus::Paid)->get();

        expect($paidOrders)->toHaveCount(1);
    });

    it('can retrieve orders for specific user', function (): void {
        $user1 = User::factory()->create(['role' => Role::Customer]);
        $user2 = User::factory()->create(['role' => Role::Customer]);

        Order::factory()->create(['user_id' => $user1->id]);
        Order::factory()->create(['user_id' => $user2->id]);

        $user1Orders = Order::query()->where('user_id', $user1->id)->get();

        expect($user1Orders)->toHaveCount(1);
    });

    it('can retrieve orders by multiple statuses', function (): void {
        Order::factory()->create(['status' => OrderStatus::PaymentPending]);
        Order::factory()->create(['status' => OrderStatus::Paid]);
        Order::factory()->create(['status' => OrderStatus::Processing]);

        $statusOrders = Order::query()->whereIn('status', [
            OrderStatus::PaymentPending,
            OrderStatus::Paid,
        ])->get();

        expect($statusOrders)->toHaveCount(2);
    });

    it('can retrieve orders by payment method bank transfer', function (): void {
        Order::factory()->create(['payment_method' => PaymentMethod::BankTransfer]);
        Order::factory()->create(['payment_method' => PaymentMethod::BankTransfer]);
        Order::factory()->create(['payment_method' => PaymentMethod::Card]);

        $bankOrders = Order::query()->where('payment_method', PaymentMethod::BankTransfer)->get();

        expect($bankOrders)->toHaveCount(2);
    });

    it('can calculate total amount for orders', function (): void {
        $order1 = Order::factory()->create(['purchase_cost' => 100]);
        $order2 = Order::factory()->create(['purchase_cost' => 50]);

        $total = Order::query()->sum('purchase_cost');

        expect($total)->toBeGreaterThan(100);
    });

    it('can count orders by status', function (): void {
        Order::factory()->count(3)->create(['status' => OrderStatus::Paid]);
        Order::factory()->create(['status' => OrderStatus::PaymentPending]);

        $paidCount = Order::query()->where('status', OrderStatus::Paid)->count();

        expect($paidCount)->toBe(3);
    });

    it('can paginate orders', function (): void {
        Order::factory()->count(5)->create();

        $orders = Order::query()->paginate(2);

        expect($orders->count())->toBeLessThanOrEqual(2);
    });

    it('handles low cost orders', function (): void {
        $order = Order::factory()->create(['purchase_cost' => 1]);

        expect($order->purchase_cost)->toBeGreaterThan(0);
    });

    it('retrieves orders with correct user relationship', function (): void {
        $user = User::factory()->create(['role' => Role::Customer]);
        $order = Order::factory()->create(['user_id' => $user->id]);

        $retrievedOrder = Order::query()->find($order->id);

        expect($retrievedOrder->user_id)->toBe($user->id);
    });
});
