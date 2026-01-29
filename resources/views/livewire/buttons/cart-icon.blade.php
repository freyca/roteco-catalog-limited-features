<a href="{{ route('checkout.cart') }}">
    <button type="button" class="flex rounded-full text-sm md:me-0" id="user-menu-button" aria-expanded="false">
        <span class="sr-only">
            {{ __('Open cart') }}
        </span>
        @svg('heroicon-o-shopping-bag', 'text-primary-800 h-8 w-8 rounded')
        @if ($cartItems > 0)
            <span class="relative right-4 bottom-2 flex animate-pulse">
                <span
                    id="cart-count"
                    class="bg-primary-600 absolute flex h-6 w-6 items-center justify-center rounded-full text-white"
                >
                    {{ $cartItems }}
                </span>
            </span>
        @endif
    </button>
</a>
