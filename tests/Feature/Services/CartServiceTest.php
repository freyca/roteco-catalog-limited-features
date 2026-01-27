<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Models\ProductSparePart;
use App\Models\User;
use App\Services\Cart;

beforeEach(function (): void {
    test()->user = User::factory()->create(['role' => Role::Customer]);
    test()->product1 = ProductSparePart::factory()->create();
    test()->product2 = ProductSparePart::factory()->create();
});

describe('Cart Service', function (): void {
    it('can add product to cart', function (): void {
        test()->actingAs(test()->user);
        $cart = resolve(Cart::class);

        $result = $cart->add(test()->product1, 1);

        expect($result)->toBeTrue();
        expect($cart->hasProduct(test()->product1))->toBeTrue();
    });

    it('can remove product from cart', function (): void {
        test()->actingAs(test()->user);
        $cart = resolve(Cart::class);

        $cart->add(test()->product1, 1);
        expect($cart->hasProduct(test()->product1))->toBeTrue();

        $cart->remove(test()->product1);

        expect($cart->hasProduct(test()->product1))->toBeFalse();
    });

    it('returns total quantity for product', function (): void {
        test()->actingAs(test()->user);
        $cart = resolve(Cart::class);

        $cart->add(test()->product1, 3);

        expect($cart->getTotalQuantityForProduct(test()->product1))->toBe(3);
    });

    it('returns total cost for product', function (): void {
        test()->actingAs(test()->user);
        $cart = resolve(Cart::class);

        $cart->add(test()->product1, 2);

        $totalCost = $cart->getTotalCostforProduct(test()->product1);
        expect($totalCost)->toBeGreaterThan(0);
    });

    it('can format product cost', function (): void {
        test()->actingAs(test()->user);
        $cart = resolve(Cart::class);

        $cart->add(test()->product1, 1);

        $formattedCost = $cart->getTotalCostforProduct(test()->product1, true);
        expect($formattedCost)->toBeString();
        expect($formattedCost)->toContain('€');
    });

    it('can get total quantity in cart', function (): void {
        test()->actingAs(test()->user);
        $cart = resolve(Cart::class);

        $cart->add(test()->product1, 2);
        $cart->add(test()->product2, 3);

        expect($cart->getTotalQuantity())->toBe(5);
    });

    it('can get total cost in cart', function (): void {
        test()->actingAs(test()->user);
        $cart = resolve(Cart::class);

        $cart->add(test()->product1, 1);
        $cart->add(test()->product2, 1);

        $totalCost = $cart->getTotalCost();
        expect($totalCost)->toBeGreaterThan(0);
    });

    it('can check if cart is empty', function (): void {
        test()->actingAs(test()->user);
        $cart = resolve(Cart::class);

        expect($cart->isEmpty())->toBeTrue();

        $cart->add(test()->product1, 1);

        expect($cart->isEmpty())->toBeFalse();
    });

    it('can get all cart items', function (): void {
        test()->actingAs(test()->user);
        $cart = resolve(Cart::class);

        $cart->add(test()->product1, 1);
        $cart->add(test()->product2, 2);

        $items = $cart->getCart();

        expect($items)->toHaveCount(2);
    });
});
