<?php

declare(strict_types=1);

namespace App\Repositories\Database;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class SearchByName
{
    private static int $limit_results = 5;

    /**
     * @return array{products: Collection<int, Product>}
     */
    public static function search(string $search_term): array
    {
        $products = self::query($search_term);

        return [
            'products' => $products,
        ];
    }

    /**
     * @return Collection<int, Product>
     */
    private static function query(string $search_term): Collection
    {
        return Product::query()
            ->where('name', 'like', "%{$search_term}%")
            ->limit(self::$limit_results)
            ->get();
    }
}
