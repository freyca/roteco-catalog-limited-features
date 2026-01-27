<?php

declare(strict_types=1);

namespace App\Repositories\Payment\Traits;

trait PaymentActions
{
    protected function convertPriceToCents(float $price): int
    {
        return (int) (bcmul((string) $price, '100'));
    }
}
