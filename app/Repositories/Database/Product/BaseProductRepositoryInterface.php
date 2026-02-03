<?php

declare(strict_types=1);

namespace App\Repositories\Database\Product;

use App\Models\BaseProduct;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @template T of BaseProduct
 */
interface BaseProductRepositoryInterface
{
    /**
     * @return LengthAwarePaginator<int, T>
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * @return LengthAwarePaginator<int, T>
     */
    public function featured(): LengthAwarePaginator;
}
