<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Factories\BreadCrumbs\StandardPageBreadCrumbs;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        return view('pages.cart', [
            'breadcrumbs' => new StandardPageBreadCrumbs([
                __('Cart') => route('checkout.cart'),
            ]),
        ]);
    }
}
