<?php

declare(strict_types=1);

namespace App\Repositories\Database\Traits;

trait CacheKeys
{
    private function generateCacheKey(string $functionName): string
    {
        return bcrypt(__CLASS__ . '::' . $functionName);
    }
}
