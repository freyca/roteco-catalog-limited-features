<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Database\Traits\WithProductDiscounts;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    use WithProductDiscounts;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        fake()->randomFloat(2, 10, 3000);

        $name = fake()->unique()->words(3, true);

        return [
            'name' => $name,
            'slug' => str()->slug($name),
            'reference' => fake()->unique()->bothify('REF-########'),
            // 'price' => $price,
            // 'price_with_discount' => $this->isProductDiscounted($price),
            'published' => fake()->boolean(75),
            // 'stock' => fake()->numberBetween(10, 100),
            // 'can_be_assembled' => $can_be_assembled,
            // 'mandatory_assembly' => $mandatory_assembly,
            // 'assembly_price' => $assembly_price,
            // 'dimension_length' => fake()->randomFloat(2, 5, 100),
            // 'dimension_width' => fake()->randomFloat(2, 5, 100),
            // 'dimension_height' => fake()->randomFloat(2, 5, 100),
            // 'dimension_weight' => fake()->randomFloat(2, 5, 100),
            // 'slogan' => fake()->realText(50),
            // 'meta_description' => fake()->realText(20),
            // 'short_description' => fake()->realText(200),
            // 'description' => fake()->realText(1000),
            'category_id' => Category::query()->inRandomOrder()->first()->id ?? Category::factory()->create()->id,
            'main_image' => 'product-images/sample-image.png',
        ];
    }
}
