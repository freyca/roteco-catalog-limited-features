<x-layouts.app>
    @inject('cart', 'App\Services\Cart')

    <x-bread-crumbs :breadcrumbs="$breadcrumbs" />

    <div class="mx-auto px-0 xl:px-4 my-2 sm:my-4">
        @inject('cart', 'App\Services\Cart')

        <div>
            <h2 class="pt-6 mx-auto text-2xl sm:text-3xl text-center font-black text-slate-900 tracking-tight">
                @if ($cart->isEmpty())
                    {{ __('Shopping Cart') }}
                @else
                    {{ __('Order summary') }}
                @endif
            </h2>

            <div class="mt-4 sm:mt-8">
                @if ($cart->isEmpty())
                    <x-cart.empty-cart />
                @else
                    <div class="my-2 space-y-4 rounded-xl border-slate-100 sm:border sm:bg-white px-2 py-4 sm:p-4 sm:shadow-sm">
                        @livewire('cart.cart-product-container')
                    </div>

                    <div class="my-6 sm:my-10 space-y-4 rounded-xl border border-slate-100 bg-white p-4 shadow-sm">
                        @livewire('forms.checkout-form')
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-layouts.app>