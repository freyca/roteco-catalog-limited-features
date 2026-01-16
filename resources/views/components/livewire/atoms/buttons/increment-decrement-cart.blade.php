{{-- resources/views/components/livewire/atoms/buttons/increment-decrement-cart.blade.php --}}
<div class="flex items-center">
    <button wire:click="decrement" type="button"
        class="p-1 text-primary-800 hover:text-primary-600 disabled:opacity-30"
        @if ($productQuantity <= 1) disabled @endif>
        @svg($productQuantity <= 1 ? 'heroicon-o-minus-circle' : 'heroicon-s-minus-circle', 'w-6 h-6')
    </button>

    <span class="w-8 text-center text-sm font-bold text-gray-900">
        {{ $productQuantity }}
    </span>

    @php $cart = app(\App\Services\Cart::class); @endphp

    <button wire:click="increment" type="button"
        class="p-1 text-primary-800 hover:text-primary-600 disabled:opacity-30"
        @if (!$cart->canBeIncremented($product)) disabled @endif>
        @svg(!$cart->canBeIncremented($product) ? 'heroicon-o-plus-circle' : 'heroicon-s-plus-circle', 'w-6 h-6')
    </button>
</div>