<?php

declare(strict_types=1);

namespace App\Repositories\Database;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class SearchByName
{
    private static int $limit_results = 5;

    /**
     * @return array{products: Collection<int, Product>}|array{}
     */
    public static function search(string $search_term): array
    {
        $results['products'] = self::queryProducts($search_term, self::$limit_results);

        // Return empty array if no results found
        if ($results['products']->count() === 0) {
            return [];
        }

        return $results;
    }

    private static function queryProducts(string $search_term, int $limit_results): Collection
    {
        return self::query(Product::class, $search_term, $limit_results);
    }

    /**
     * @template TModel of Model
     *
     * @param  class-string<TModel>  $class_name
     * @return Collection<int, TModel>
     */
    private static function query(string $class_name, string $search_term, int $limit_results): Collection
    {
        if ($limit_results === 0) {
            return new Collection();
        }

        /** @var Collection<int, TModel> */
        $results = $class_name::query()
            ->where('name', 'like', "%{$search_term}%")
            ->limit($limit_results)
            ->get();

        return $results;
    }
}
