<?php

declare(strict_types=1);

namespace App\Repositories\Database\Product\Product;

use App\Models\Product;
use App\Repositories\Database\Product\BaseProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

/** @extends BaseProductRepositoryInterface<Product> */
interface ProductRepositoryInterface extends BaseProductRepositoryInterface
{
    /**
     * @return LengthAwarePaginator<int, Product>
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * @return LengthAwarePaginator<int, Product>
     */
    public function featured(): LengthAwarePaginator;
}
