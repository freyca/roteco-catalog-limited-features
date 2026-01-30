@inject('cart', 'App\Services\Cart')

<div class="mx-auto max-w-4xl flex-1 space-y-6 pt-10 lg:w-full xl:mb-2">
    <p class="text-primary-900 py-2 text-center text-2xl font-semibold">
        {{ __('User details') }}
    </p>

    <form wire:submit="create">
        {{ $this->form }}

        <div class="mt-10 space-x-6 rounded-lg border bg-white p-2 md:p-6">
            <div class="space-y-3">
                @if ($cart->getTotalDiscount() > 0)
                    <dl class="flex items-center justify-between gap-4">
                        <dt class="text-primary-500 text-base font-normal">
                            {{ __('Price') }}
                        </dt>
                        <dd class="text-primary-900 text-base font-medium line-through">
                            {{ $cart->getTotalCostWithoutDiscount(true) }}
                        </dd>
                    </dl>

                    <dl class="flex items-center justify-between gap-4">
                        <dt class="text-primary-500 text-base font-normal">
                            {{ __('Savings') }}
                        </dt>
                        <dd class="text-success-600 text-base font-bold">- {{ $cart->getTotalDiscount(true) }}</dd>
                    </dl>
                @endif

                <dl class="flex items-center justify-between gap-4">
                    <dt class="text-primary-900 text-base font-bold">
                        {{ __('Without taxes') }}
                    </dt>
                    <dd class="text-primary-900 text-base font-bold">
                        {{ $cart->getTotalCostWithoutTaxes(true) }}
                    </dd>
                </dl>

                <dl
                    class="@if ($cart->getTotalDiscount() > 0) {{ 'border-primary-200 border-t pt-2' }} @endif flex items-center justify-between gap-4"
                >
                    <dt class="text-primary-700 text-base font-medium">
                        {{ __('Total') }}
                    </dt>
                    <dd class="text-primary-700 text-base font-medium">
                        {{ $cart->getTotalCost(true) }}
                    </dd>
                </dl>

                <div class="space-y-3">
                    <button
                        type="submit"
                        class="bg-primary-600 hover:bg-primary-500 flex w-full items-center justify-center rounded-lg px-5 py-2.5 text-sm font-medium text-white"
                    >
                        {{ __('Place order') }}
                    </button>

                    <div class="flex items-center justify-center gap-2">
                        <span class="text-primary-500 text-sm font-normal">{{ __('or') }}</span>
                        <a
                            href="/"
                            class="text-primary-700 inline-flex items-center gap-2 text-sm font-medium underline hover:no-underline"
                        >
                            {{ __('Continue shopping') }}
                            @svg('heroicon-s-arrow-right-circle', 'h-5 w-5')
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="space-y-4 rounded-lg border border-primary-200 bg-white p-4 shadow-sm sm:p-6"> --}}
        {{-- <form class="space-y-4"> --}}
        {{-- <div> --}}
        {{-- <label for="voucher" class="mb-2 block text-sm font-medium text-primary-900"> --}}
        {{-- Do you have a voucher or gift card? --}}
        {{-- </label> --}}
        {{-- <input type="text" id="voucher" --}}
        {{-- class="block w-full rounded-lg border border-primary-300 bg-primary-50 p-2.5 text-sm text-primary-900 focus:border-primary-500 focus:ring-primary-500" --}}
        {{-- placeholder="" required /> --}}
        {{-- </div> --}}
        {{--  --}}
        {{-- <button type="submit" --}}
        {{-- class="flex w-full items-center justify-center rounded-lg bg-primary-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300"> --}}
        {{-- Apply Code --}}
        {{-- </button> --}}
        {{-- </form> --}}
        {{-- </div> --}}
    </form>

    <x-filament-actions::modals />
</div>
