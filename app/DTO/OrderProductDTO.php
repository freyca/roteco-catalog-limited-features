<?php

declare(strict_types=1);

namespace App\DTO;

use App\Models\BaseProduct;

class OrderProductDTO
{
    /**
     * Private attributes are used as a "cache system"
     * This avoid repetitive queries to the database by querying this object
     */
    private readonly float $price_without_discount;

    private readonly ?float $price_with_discount;

    private readonly string $reference;

    public function __construct(
        private readonly int $orderable_id,
        private readonly string $orderable_type,
        private readonly float $unit_price,
        private int $quantity,
        BaseProduct $product,
    ) {
        $this->reference = (string) $product->reference;
        $this->price_with_discount = $product->price_with_discount;
        $this->price_without_discount = $product->price;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * Getters
     */
    public function getProduct(): BaseProduct
    {
        /** @var BaseProduct */
        return $this->orderable_type::find($this->orderable_id);
    }

    public function priceWithoutDiscount(): float
    {
        return $this->price_without_discount;
    }

    public function priceWithDiscount(): ?float
    {
        return $this->price_with_discount;
    }

    public function reference(): string
    {
        return $this->reference;
    }

    public function orderableId(): int
    {
        return $this->orderable_id;
    }

    public function orderableType(): string
    {
        return $this->orderable_type;
    }

    public function unitPrice(): float
    {
        return $this->unit_price;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }
}
