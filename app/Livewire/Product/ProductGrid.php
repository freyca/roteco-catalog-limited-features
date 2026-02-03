<?php

declare(strict_types=1);

namespace App\Livewire\Product;

use App\Models\Product;
use App\Repositories\Database\Product\Product\EloquentProductRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ProductGrid extends Component
{
    use WithoutUrlPagination, WithPagination;

    /**
     * @var LengthAwarePaginator<int, Product>
     */
    private LengthAwarePaginator $products;

    public function render(): View
    {
        $repository = resolve(EloquentProductRepository::class);

        $this->products = $repository->getAll();

        return view(
            'livewire.product.product-grid',
            [
                'products' => $this->products,
            ]
        );
    }
}
