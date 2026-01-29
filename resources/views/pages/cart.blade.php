<x-layouts.app>
    @inject('cart', 'App\Services\Cart')

    <x-bread-crumbs :breadcrumbs="$breadcrumbs" />

    <div class="mx-auto my-2 px-0 sm:my-4 xl:px-4">
        @inject('cart', 'App\Services\Cart')

        <div>
            <h2 class="mx-auto pt-6 text-center text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">
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
                    <div
                        class="my-2 space-y-4 rounded-xl border-slate-100 px-2 py-4 sm:border sm:bg-white sm:p-4 sm:shadow-sm"
                    >
                        @livewire('cart.cart-product-container')
                    </div>

                    <div class="my-6 space-y-4 rounded-xl border border-slate-100 bg-white p-4 shadow-sm sm:my-10">
                        @livewire('forms.checkout-form')
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
