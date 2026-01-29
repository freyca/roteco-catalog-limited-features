<div class="flex items-center gap-1">
    @php
        $cart = app(\App\Services\Cart::class);
    @endphp

    <!-- Decrement -->
    <button
        wire:click="decrement"
        type="button"
        class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-500 transition-all hover:bg-slate-50 hover:text-slate-900 disabled:opacity-20"
        @disabled($productQuantity <= 1)
        aria-label="{{ __('Decrease') }}"
    >
        @svg($productQuantity <= 1 ? 'heroicon-o-minus-circle' : 'heroicon-s-minus-circle', 'h-6 w-6')
    </button>

    <!-- Quantity Display -->
    <div class="min-w-[2rem] px-1 text-center">
        <span class="text-sm font-black text-slate-900 tabular-nums">
            {{ $productQuantity }}
        </span>
    </div>

    <!-- Increment -->
    <button
        wire:click="increment"
        type="button"
        class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-500 transition-all hover:bg-slate-50 hover:text-slate-900 disabled:opacity-20"
        @disabled(! $cart->canBeIncremented($product))
        aria-label="{{ __('Increase') }}"
    >
        @svg(! $cart->canBeIncremented($product) ? 'heroicon-o-plus-circle' : 'heroicon-s-plus-circle', 'h-6 w-6')
    </button>
</div>
