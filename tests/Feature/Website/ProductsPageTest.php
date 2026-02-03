<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Models\Product;
use App\Models\User;

beforeEach(function (): void {
    test()->user = User::factory()->create(['role' => Role::Customer]);
});

describe('Products Page - ProductCard Component', function (): void {
    it('user cannot access product page if it is not published', function (): void {
        $product = Product::factory()->create(['published' => false]);

        test()->actingAs(test()->user);

        $response = test()->get(route('product', $product->slug));

        expect($response->status())->toBe(404);
    });

    it('admin can access product page if it is not published', function (): void {
        $product = Product::factory()->create(['published' => false]);

        test()->actingAs(User::factory()->create(['role' => Role::Admin]));

        $response = test()->get(route('product', $product->slug));

        expect($response->status())->toBe(200);
        $response->assertSee($product->name);
    });

    it('handles multiple products on page', function (): void {
        $products = Product::factory()->count(3)->create(['published' => true]);

        $response = test()->actingAs(test()->user)->get(route('product-list'));

        expect($response->status())->toBe(200);
        foreach ($products as $product) {
            $response->assertSee($product->name);
        }
    });
});
