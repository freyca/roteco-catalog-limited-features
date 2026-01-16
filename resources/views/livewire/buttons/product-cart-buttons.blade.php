<div class="flex items-center justify-end min-w-[100px] sm:min-w-[140px]">
    @inject(cart, '\App\Services\Cart')

    @if(!$cart->hasProduct($product))
        <x-livewire.atoms.buttons.add-to-cart :product="$product" />
    @else
        <div class="inline-flex items-center bg-white border border-slate-200 rounded-2xl p-0.5 sm:p-1 shadow-sm ring-1 ring-slate-900/5 transition-all hover:border-slate-300">
            <x-livewire.atoms.buttons.remove-from-cart :product="$product" />

            <div class="h-4 sm:h-5 w-px bg-slate-100 mx-1 sm:mx-1.5"></div>

            <x-livewire.atoms.buttons.increment-decrement-cart
                :product="$product"
                :product-quantity="$productQuantity"
            />
        </div>
    @endif
</div>