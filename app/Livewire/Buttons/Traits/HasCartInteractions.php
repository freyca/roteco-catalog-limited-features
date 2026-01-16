<?php

declare(strict_types=1);

namespace App\Livewire\Buttons\Traits;

use App\Services\Cart;

trait HasCartInteractions
{
    public int $productQuantity;

    public function add(Cart $cart): void
    {
        $cart->add($this->product, 1);

        $this->productQuantity = $cart->getTotalQuantityForProduct($this->product);

        $this->dispatch('refresh-cart');
    }

    public function increment(Cart $cart): void
    {
        $cart->add(product: $this->product, quantity: 1);

        $this->productQuantity = $cart->getTotalQuantityForProduct($this->product);

        $this->dispatch('refresh-cart');
    }

    public function decrement(Cart $cart): void
    {
        $cart->add(product: $this->product, quantity: -1);

        $this->productQuantity = $cart->getTotalQuantityForProduct($this->product);

        $this->dispatch('refresh-cart');
    }

    public function remove(Cart $cart): void
    {
        $cart->remove(
            product: $this->product,
        );

        $this->productQuantity = $cart->getTotalQuantityForProduct($this->product);

        $this->dispatch('refresh-cart');
    }
}
