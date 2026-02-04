<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Models\Product;
use App\Models\ProductSparePart;
use App\Models\User;

beforeEach(function (): void {
    test()->user = User::factory()->create(['role' => Role::Customer]);
    test()->actingAs(test()->user);
});

describe('ProductController', function (): void {
    it('user cannot access product page if it is not published', function (): void {
        $product = Product::factory()->create(['published' => false]);

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

        $response = test()->get(route('product-list'));

        expect($response->status())->toBe(200);
        foreach ($products as $product) {
            $response->assertSee($product->name);
        }
    });

    test('spare parts urls cannot be accesed directly', function (): void {
        $products = ProductSparePart::factory(3)->create();

        foreach ($products as $product) {
            $response = test()->get('/pieza-de-repuesto/'.$product->slug);
            $response->assertStatus(404);
        }
    });
});
