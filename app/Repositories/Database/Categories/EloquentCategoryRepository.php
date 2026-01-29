<?php

declare(strict_types=1);

namespace App\Repositories\Database\Categories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    public function getAll(): Collection
    {
        /**
         * @var Collection<int, Category>
         */
        return Category::query()->where('published', true)->get();
    }

    public function getProducts(Category $category): LengthAwarePaginator
    {
        /**
         * @var LengthAwarePaginator<Product>
         */
        return $category->products()->paginate(8);
    }

    /**
     * @codeCoverageIgnore It is not used by now
     */
    public function featured(): Collection
    {
        $featured_categories = config()->array('custom.featured-categories');

        return Category::query()->whereIn('id', $featured_categories)->get();
    }
}
