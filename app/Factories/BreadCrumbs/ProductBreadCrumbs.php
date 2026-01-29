<?php

declare(strict_types=1);

namespace App\Factories\BreadCrumbs;

use App\Models\BaseProduct;
use App\Models\Product;
use App\Models\ProductSparePart;
use Exception;

class ProductBreadCrumbs extends StandardPageBreadCrumbs
{
    public function __construct(BaseProduct $product)
    {
        parent::setDefaultBreadCrumb();

        /** @codeCoverageIgnore exception is for sanity  */
        throw_if(! $product instanceof BaseProduct, new Exception('Invalid class type'));

        /** @var array<string, string> */
        $bread_crumbs = match (true) {
            $product instanceof ProductSparePart => $this->productSparePartBreadCrumb(),
            $product instanceof Product => $this->productBreadCrumb($product),
        };

        $bread_crumbs = array_merge($bread_crumbs, [$product->name => $product->slug]);

        $this->bread_crumbs = array_merge($this->default_bread_crumb, $bread_crumbs);
    }

    /**
     * @return array<string, string>
     */
    private function productBreadCrumb(Product $product): array
    {
        return [
            $product->category?->name => '/' . $product->category?->slug,
        ];
    }

    /**
     * @codeCoverageIgnore It is not used by now
     *
     * @return array<string, string>
     */
    private function productSparePartBreadCrumb(): array
    {
        return [
            __('Spare parts') => route('spare-part-list'),
        ];
    }
}
