<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Factories\BreadCrumbs\ProductBreadCrumbs;
use App\Factories\BreadCrumbs\StandardPageBreadCrumbs;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Products
     */
    public function all(): View
    {
        return view('pages.products', [
            'breadcrumbs' => new StandardPageBreadCrumbs([
                __('Products') => route('product-list'),
            ]),
        ]);
    }

    public function product(Product $product): View
    {
        if (! $product->published && ! $this->canAccessPrivateProducts()) {
            abort(403);
        }

        $relatedDisassemblies = $product->disassemblies;
        $relatedDisassemblies->load('productSpareParts');

        return view(
            'pages.product',
            [
                'product' => $product,
                'relatedDisassemblies' => $relatedDisassemblies,
                'breadcrumbs' => new ProductBreadCrumbs($product),
            ]
        );
    }

    private function canAccessPrivateProducts(): bool
    {
        /** @var ?User */
        $user = Auth::user();

        return match (true) {
            $user === null => false,
            $user->role !== Role::Admin => false,
            default => true
        };
    }
}
