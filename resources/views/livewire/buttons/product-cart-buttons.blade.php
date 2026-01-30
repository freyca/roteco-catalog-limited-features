<div class="flex min-w-[100px] items-center justify-end sm:min-w-[140px]">
    @inject(cart, '\App\Services\Cart')

    @if (! $cart->hasProduct($product))
        <x-livewire.atoms.buttons.add-to-cart :product="$product" />
    @else
        <div
            class="inline-flex items-center rounded-2xl border border-slate-200 bg-white p-0.5 shadow-sm ring-1 ring-slate-900/5 transition-all hover:border-slate-300 sm:p-1"
        >
            <x-livewire.atoms.buttons.remove-from-cart :product="$product" />

            <div class="mx-1 h-4 w-px bg-slate-100 sm:mx-1.5 sm:h-5"></div>

            <x-livewire.atoms.buttons.increment-decrement-cart
                :product="$product"
                :product-quantity="$productQuantity"
            />
        </div>
    @endif
</div>
