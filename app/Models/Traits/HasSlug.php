<?php

declare(strict_types=1);

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::saving(function (self $model): void {
            $model->slug = Str::slug($model->name);
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
