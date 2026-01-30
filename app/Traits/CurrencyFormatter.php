<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Number;

trait CurrencyFormatter
{
    public static function formatCurrency(float $value): string
    {
        return (string) (
            Number::currency(
                $value,
                in: 'EUR',
                locale: config()->string('app.locale'),
            )
        );
    }
}
