<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\OrderProductDTO;
use Illuminate\Support\Collection;
use RuntimeException;

class PriceCalculator
{
    /**
     * Product calculations
     */
    public function getTotalCostForProduct(OrderProductDTO $product, int $quantity, bool $apply_discount = true): float
    {
        if ($apply_discount) {
            $price = is_null($product->priceWithDiscount()) ? $product->priceWithoutDiscount() : $product->priceWithDiscount();
        } else {
            $price = $product->priceWithoutDiscount();
        }

        return $quantity * $price;
    }

    public function getTotalCostForProductWithoutDiscount(OrderProductDTO $product, int $quantity): float
    {
        return $this->getTotalCostForProduct(product: $product, quantity: $quantity, apply_discount: false);
    }

    public function getTotalDiscountForProduct(OrderProductDTO $product, int $quantity): float
    {
        return $this->getTotalCostForProductWithoutDiscount($product, $quantity) - $this->getTotalCostForProduct($product, $quantity);
    }

    /**
     * Order calculations
     */
    public function getTotaCostForOrderWithoutDiscount(Collection $order_products): float
    {
        return $this->getTotalCostForOrder($order_products, apply_discount: false);
    }

    public function getTotalDiscountForOrder(Collection $order): float
    {
        return $this->getTotaCostForOrderWithoutDiscount($order) - $this->getTotalCostForOrder($order);
    }

    public function getTotalCostForOrderWithoutTaxes(Collection $order_products): float
    {
        return $this->getTotalCostForOrder($order_products);
    }

    public function getTotalCostForOrderWithTaxes(Collection $order_products, bool $apply_discount = true): float
    {
        $taxes = config('custom.tax_iva');
        throw_unless(is_numeric($taxes), RuntimeException::class, 'Invalid tax IVA config');
        $taxes = (float) $taxes;

        return $this->getTotalCostForOrder($order_products, $apply_discount) * (1 + $taxes);
    }

    public function getTotalCostForOrderWithTaxesAndManualDiscount(Collection $order_products, bool $apply_discount = true, float $percentage_discount = 0): float
    {
        $total_with_taxes = $this->getTotalCostForOrderWithTaxes($order_products, $apply_discount);

        return round($total_with_taxes - ($total_with_taxes * $percentage_discount / 100), 2);
    }

    /**
     * @paran Collection<int, OrderProductDTO> $order_products
     */
    private function getTotalCostForOrder(Collection $order_products, bool $apply_discount = true): float
    {
        $total = 0;

        /** @var Collection<int, OrderProductDTO> $order_products */
        foreach ($order_products as $order_product) {
            $total += $this->getTotalCostForProduct(
                product: $order_product,
                quantity: $order_product->quantity(),
                apply_discount: $apply_discount,
            );
        }

        return $total;
    }
}
