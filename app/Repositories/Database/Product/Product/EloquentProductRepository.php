<?php

declare(strict_types=1);

namespace App\Repositories\Database\Product\Product;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function getAll(): LengthAwarePaginator
    {
        return Product::query()->paginate(16);
    }

    public function featured(): LengthAwarePaginator
    {
        $featured_products = config()->array('custom.featured-products');

        return Product::query()->whereIn('id', $featured_products)->paginate(15);
    }
}
