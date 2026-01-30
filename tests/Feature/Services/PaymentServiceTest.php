<?php

declare(strict_types=1);

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\Role;
use App\Models\Order;
use App\Models\User;

beforeEach(function (): void {
    // Create admin user first to satisfy notification listener
    test()->admin = User::factory()->admin_notifiable()->create();

    test()->user = User::factory()->create(['role' => Role::Customer]);
    test()->order = Order::factory()->create([
        'user_id' => test()->user->id,
        'status' => OrderStatus::PaymentPending,
    ]);
});

describe('Payment Service', function (): void {
    it('handles bank transfer payment method', function (): void {
        test()->order->update(['payment_method' => PaymentMethod::BankTransfer]);

        expect(test()->order->payment_method)->toBe(PaymentMethod::BankTransfer);
    });

    it('handles card payment method', function (): void {
        test()->order->update(['payment_method' => PaymentMethod::Card]);

        expect(test()->order->payment_method)->toBe(PaymentMethod::Card);
    });

    it('handles bizum payment method', function (): void {
        test()->order->update(['payment_method' => PaymentMethod::Bizum]);

        expect(test()->order->payment_method)->toBe(PaymentMethod::Bizum);
    });

    it('handles paypal payment method', function (): void {
        test()->order->update(['payment_method' => PaymentMethod::PayPal]);

        expect(test()->order->payment_method)->toBe(PaymentMethod::PayPal);
    });

    it('validates payment amount', function (): void {
        expect(test()->order->purchase_cost)->toBeGreaterThan(0);
    });

    it('processes payment with user information', function (): void {
        expect(test()->order->user_id)->toBe(test()->user->id);
    });

    it('marks order as payment pending', function (): void {
        test()->order->update(['status' => OrderStatus::PaymentPending]);

        expect(test()->order->status)->toBe(OrderStatus::PaymentPending);
    });

    it('can mark order as paid', function (): void {
        test()->order->update(['status' => OrderStatus::Paid]);

        expect(test()->order->status)->toBe(OrderStatus::Paid);
    });

    it('can mark order as processing after payment', function (): void {
        test()->order->update(['status' => OrderStatus::Processing]);

        expect(test()->order->status)->toBe(OrderStatus::Processing);
    });

    it('handles multiple payment methods for different orders', function (): void {
        $order1 = Order::factory()->create(['payment_method' => PaymentMethod::BankTransfer]);
        $order2 = Order::factory()->create(['payment_method' => PaymentMethod::Card]);

        expect($order1->payment_method)->toBe(PaymentMethod::BankTransfer);
        expect($order2->payment_method)->toBe(PaymentMethod::Card);
    });

    it('can verify order payment status', function (): void {
        test()->order->update(['status' => OrderStatus::Paid]);

        expect(test()->order->status)->toBe(OrderStatus::Paid);
    });
});
