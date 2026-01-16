<div class="max-w-4xl mx-auto px-1 sm:px-4 py-2 sm:py-8">
    @if($cart->isEmpty())
        @php redirect(route('checkout.cart')); @endphp
    @endif

    <h2 class="mb-4 sm:mb-8 text-lg sm:text-2xl font-black text-center text-slate-900 uppercase tracking-tighter">
        {{ __('Products') }}
    </h2>

    <div class="flex flex-col gap-3 sm:gap-4">
        @foreach ($cart->getCart() as $cart_item)
            @livewire(
                'cart.product-card',
                ['order_product' => $cart_item],
                key('product-' . Str::random(5) . '-' . $cart_item->orderableId() . '-' . Str::random(5))
            )
        @endforeach
    </div>
</div>