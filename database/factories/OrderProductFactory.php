<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Disassembly;
use App\Models\OrderProduct;
use App\Models\ProductSparePart;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderProduct>
 */
class OrderProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Create a ProductSparePart with its required Disassembly
        $disassembly = Disassembly::factory()->create();
        $product = ProductSparePart::factory()->for($disassembly)->create();

        $price = is_null($product->price_with_discount) ? $product->price : $product->price_with_discount;

        return [
            'orderable_id' => $product->id,
            'orderable_type' => ProductSparePart::class,
            'quantity' => fake()->numberBetween(1, 10),
            'unit_price' => $price,
        ];
    }
}
