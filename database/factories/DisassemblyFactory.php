<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Disassembly;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Disassembly>
 */
class DisassemblyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(3, true),
            'main_image' => 'product-images/sample-image.png',
            'product_id' => Product::factory(),
        ];
    }
}
