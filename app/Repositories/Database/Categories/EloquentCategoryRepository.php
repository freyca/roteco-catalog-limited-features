<?php

declare(strict_types=1);

namespace App\Repositories\Database\Categories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    /**
     * @return Collection<int, Category>
     */
    public function getAll(): Collection
    {
        return Category::query()->where('published', true)->get();
    }

    /**
     * @return LengthAwarePaginator<int, Product>
     */
    public function getProducts(Category $category): LengthAwarePaginator
    {
        return $category->products()->paginate(8);
    }

    /**
     * @return Collection<int, Category>
     */
    public function featured(): Collection
    {
        $featured_categories = config()->array('custom.featured-categories');

        return Category::query()->whereIn('id', $featured_categories)->get();
    }
}
