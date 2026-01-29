@inject('cart', 'App\Services\Cart')

<div
    class="group w-full rounded-2xl border border-slate-100 bg-white p-3 shadow-sm transition-all hover:border-slate-200 sm:p-4"
>
    {{-- Added items-center to vertically align the image with the content block --}}
    <div class="flex items-center gap-3 sm:gap-6">
        <!-- LEFT: Image -->
        <div class="shrink-0">
            <a href="{{ $path . '/' . $related_product->slug }}" class="block">
                <div
                    class="flex items-center justify-center rounded-full bg-slate-50 p-1 transition-colors group-hover:bg-white"
                >
                    <img
                        class="h-16 w-16 rounded-full object-contain sm:h-24 sm:w-24"
                        src="{{ asset('/storage/' . $related_product->main_image) }}"
                        alt="{{ $product->name }}"
                    />
                </div>
            </a>
        </div>

        <!-- RIGHT: Tiered Content -->
        <div class="flex min-w-0 flex-1 flex-col">
            <!-- TIER 1: Identity (Name & Reference) -->
            <div class="mb-2">
                <a
                    href="{{ $path . '/' . $related_product->slug }}"
                    class="mb-1 line-clamp-2 block text-sm leading-tight font-bold text-slate-900 uppercase hover:text-slate-600 sm:text-base"
                    title="{{ $product->name }}"
                >
                    {{ $product->name }}
                </a>
                <div class="flex items-center gap-1.5">
                    <span class="text-[9px] font-black tracking-widest text-slate-400 uppercase">
                        {{ __('Ref.') }}
                    </span>
                    <span class="font-mono text-[10px] tracking-tight text-slate-500 sm:text-xs">
                        {{ $product->reference }}
                    </span>
                </div>
            </div>

            <!-- TIER 2: Actions & Economics -->
            <div class="flex items-center justify-between border-t border-slate-50 pt-2">
                <!-- Controls: Quantity & Trash -->
                <div class="flex items-center gap-2 sm:gap-4">
                    <div class="origin-left scale-90 sm:scale-100">
                        <x-livewire.atoms.buttons.increment-decrement-cart
                            :product="$product"
                            :product-quantity="$quantity"
                        />
                    </div>

                    <div class="hidden h-5 w-px bg-slate-100 sm:block"></div>

                    <div class="flex scale-90 items-center sm:scale-100">
                        <x-livewire.atoms.buttons.remove-from-cart :product="$product" />
                    </div>
                </div>

                <!-- Price Stack -->
                <div class="flex shrink-0 flex-col justify-end text-right">
                    @php
                        $has_discount = ! is_null($product->price_with_discount);
                    @endphp

                    @if ($has_discount && $cart->hasProduct($product))
                        <span class="mb-0.5 block text-[9px] leading-none text-slate-500 sm:text-[11px]">
                            {{ $cart->getTotalCostforProductWithoutDiscount($product, true) }}
                        </span>
                    @endif

                    <span
                        @class([
                            'block text-sm leading-none font-black tabular-nums sm:text-lg',
                            'text-slate-900' => ! $has_discount,
                            'text-emerald-600' => $has_discount,
                        ])
                    >
                        @if ($cart->hasProduct($product))
                            {{ $cart->getTotalCostforProduct($product, true) }}
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
