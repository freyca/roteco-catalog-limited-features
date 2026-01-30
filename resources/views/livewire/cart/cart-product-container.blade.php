<div class="mx-auto max-w-4xl px-1 py-2 sm:px-4 sm:py-8">
    @if ($cart->isEmpty())
        @php
            redirect(route('checkout.cart'));
        @endphp
    @endif

    <h2 class="mb-4 text-center text-lg font-black tracking-tighter text-slate-900 uppercase sm:mb-8 sm:text-2xl">
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
