<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;

beforeEach(function (): void {
    test()->user = User::factory()->create(['role' => Role::Customer]);
    test()->actingAs(test()->user);
});

describe('CategoryController', function (): void {
    it('an authenticated user can access the list of categories', function (): void {
        $categories = Category::factory(3)->create();

        $response = test()->get(route('category-list'));

        expect($response->status())->toBe(200);
        $response->assertViewIs('pages.categories');

        $viewCategories = $response->viewData('categories');
        expect($viewCategories)->toHaveCount(3);
        foreach ($categories as $category) {
            expect($viewCategories->pluck('name')->contains((string) $category->name))->toBeTrue();
        }

        $breadcrumbs = $response->viewData('breadcrumbs');
        expect($breadcrumbs)->not()->toBeNull();
        $response->assertViewHas('breadcrumbs');
    });

    it('returns category detail view', function (): void {
        $category = Category::factory()->create();

        $response = test()->actingAs(test()->user)->get(route('category', $category));

        expect($response->status())->toBe(200);
        $response->assertViewIs('pages.category');

        $viewCategory = $response->viewData('category');
        expect($viewCategory->name)->toBe($category->name);
    });

    it('passes products to category view', function (): void {
        $category = Category::factory()->create();
        Product::factory(3)->for($category)->create(['published' => true]);

        $response = test()->actingAs(test()->user)->get(route('category', $category));

        $products_in_view = $response->viewData('products');
        expect($products_in_view)->count()->toBe(3);
        $response->assertViewHas('products');

        foreach ($products_in_view as $product) {
            expect($products_in_view->pluck('name')->contains((string) $product->name))->toBeTrue();
        }

        $breadcrumbs = $response->viewData('breadcrumbs');
        expect($breadcrumbs)->not()->toBeNull();
        $response->assertViewHas('breadcrumbs');
    });
});
