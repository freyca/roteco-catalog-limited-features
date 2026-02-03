<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Models\ProductSparePart;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class ProductSparePartList extends Component
{
    /**
     * @param  Collection<int, ProductSparePart>  $relatedSpareparts
     */
    public function __construct(public Collection $relatedSpareparts) {}

    public function render(): View|Closure|string
    {
        return view('components.product-spare-part-list');
    }
}
