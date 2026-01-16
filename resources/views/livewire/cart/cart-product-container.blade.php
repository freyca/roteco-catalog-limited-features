<div class="max-w-4xl mx-auto px-4 py-8">
    @if($cart->isEmpty())
        @php redirect(route('checkout.cart')); @endphp
    @endif

    <h2 class="mb-8 text-3xl font-extrabold text-center text-gray-900">
        {{ __('Products') }}
    </h2>

    <div class="flex flex-col gap-4">
        @foreach ($cart->getCart() as $cart_item)
            @livewire(
                'cart.product-card',
                ['order_product' => $cart_item],
                key('product-' . Str::random(5) . '-' . $cart_item->orderableId() . '-' . Str::random(5))
            )
        @endforeach
    </div>
</div>