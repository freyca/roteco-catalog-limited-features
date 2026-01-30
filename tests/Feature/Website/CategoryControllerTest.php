<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Models\Category;
use App\Models\User;

beforeEach(function (): void {
    // Create an authenticated user for the tests
    test()->user = User::factory()->create(['role' => Role::Customer]);
});

describe('CategoryController', function (): void {
    it('returns categories index view', function (): void {
        Category::factory(3)->create();

        $response = test()->actingAs(test()->user)->get(route('category-list'));

        expect($response->status())->toBe(200);
        $response->assertViewIs('pages.categories');
    });

    it('passes categories to view', function (): void {
        $categories = Category::factory(3)->create();

        $response = test()->actingAs(test()->user)->get(route('category-list'));

        $viewCategories = $response->viewData('categories');
        expect($viewCategories)->toHaveCount(3);
        foreach ($categories as $category) {
            expect($viewCategories->pluck('id')->contains((string) $category->id))->toBeTrue();
        }
    });

    it('passes breadcrumbs to view', function (): void {
        Category::factory(3)->create();

        $response = test()->actingAs(test()->user)->get(route('category-list'));

        $breadcrumbs = $response->viewData('breadcrumbs');
        expect($breadcrumbs)->not()->toBeNull();
        $response->assertViewHas('breadcrumbs');
    });

    it('returns category detail view', function (): void {
        $category = Category::factory()->create();

        $response = test()->actingAs(test()->user)->get(route('category', $category));

        expect($response->status())->toBe(200);
        $response->assertViewIs('pages.category');
    });

    it('passes category to view', function (): void {
        $category = Category::factory()->create();

        $response = test()->actingAs(test()->user)->get(route('category', $category));

        $viewCategory = $response->viewData('category');
        expect((string) $viewCategory->id)->toBe((string) $category->id);
        expect($viewCategory->name)->toBe($category->name);
    });

    it('passes products to view', function (): void {
        $category = Category::factory()->create();

        $response = test()->actingAs(test()->user)->get(route('category', $category));

        $products = $response->viewData('products');
        expect($products)->not()->toBeNull();
        $response->assertViewHas('products');
    });

    it('passes breadcrumbs with category to detail view', function (): void {
        $category = Category::factory()->create();

        $response = test()->actingAs(test()->user)->get(route('category', $category));

        $breadcrumbs = $response->viewData('breadcrumbs');
        expect($breadcrumbs)->not()->toBeNull();
        $response->assertViewHas('breadcrumbs');
    });
});
