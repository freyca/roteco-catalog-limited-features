<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Models\Category;
use App\Models\User;

beforeEach(function (): void {
    test()->user = User::factory()->create(['role' => Role::Customer]);
});

test('categories page does not show not published categories', function (): void {
    $published = Category::factory()->create(['published' => true, 'name' => 'Published Category']);
    $notPublished = Category::factory()->create(['published' => false, 'name' => 'Not Published Category']);

    $response = test()->actingAs(test()->user)->get(route('category-list'));
    $response->assertOk();
    $response->assertSee($published->name);
    $response->assertDontSee($notPublished->name);
});
