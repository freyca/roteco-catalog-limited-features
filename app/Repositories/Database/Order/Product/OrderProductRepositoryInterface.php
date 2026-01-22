<?php

declare(strict_types=1);

namespace App\Repositories\Database\Order\Product;

use App\DTO\OrderProductDTO;
use App\Models\Order;
use Illuminate\Support\Collection;

interface OrderProductRepositoryInterface
{
    /**
     * @param  Collection<int, OrderProductDTO>  $order_products
     */
    public function save(Order $order, Collection $order_products): void;
}
