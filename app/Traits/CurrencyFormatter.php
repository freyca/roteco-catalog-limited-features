<?php

declare(strict_types=1);

namespace App\Traits;

use Exception;
use Illuminate\Support\Number;

trait CurrencyFormatter
{
    public static function formatCurrency(float $value): string
    {
        $locale = config('app.locale');

        throw_unless(is_string($locale), Exception::class, 'Invalid locale configured');

        return strval(
            Number::currency(
                $value,
                in: 'EUR',
                locale: $locale,
            )
        );
    }
}
