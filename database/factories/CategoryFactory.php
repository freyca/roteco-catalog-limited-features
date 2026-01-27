<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->sentence(3);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            // 'meta_description' => fake()->realText(30),
            // 'description' => fake()->realText(1000),
            'big_image' => 'category-images/sample-image.png',
            // 'small_image' => 'category-images/sample-image.png',
            'published' => true,
        ];
    }
}
