@inject('cart', 'App\Services\Cart')

<div class="w-full bg-white border border-slate-100 rounded-2xl shadow-sm p-3 sm:p-4 transition-all hover:border-slate-200 group">
    {{-- Added items-center to vertically align the image with the content block --}}
    <div class="flex items-center gap-3 sm:gap-6">

        <!-- LEFT: Image -->
        <div class="shrink-0">
            <a href="{{ $path . '/' . $related_product->slug }}" class="block">
                <div class="bg-slate-50 rounded-full p-1 flex items-center justify-center transition-colors group-hover:bg-white">
                    <img class="h-16 w-16 sm:h-24 sm:w-24 object-contain rounded-full"
                        src="{{ asset('/storage/' . $related_product->main_image) }}"
                        alt="{{ $product->name }}"
                    />
                </div>
            </a>
        </div>

        <!-- RIGHT: Tiered Content -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- TIER 1: Identity (Name & Reference) -->
            <div class="mb-2">
                <a href="{{ $path . '/' . $related_product->slug }}"
                    class="text-sm sm:text-base font-bold text-slate-900 hover:text-slate-600 leading-tight uppercase line-clamp-2 block mb-1"
                    title="{{ $product->name }}">
                    {{ $product->name }}
                </a>
                <div class="flex items-center gap-1.5">
                    <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Ref.</span>
                    <span class="text-[10px] sm:text-xs text-slate-500 font-mono tracking-tight">{{ $related_product->reference }}</span>
                </div>
            </div>

            <!-- TIER 2: Actions & Economics -->
            <div class="flex items-center justify-between pt-2 border-t border-slate-50">

                <!-- Controls: Quantity & Trash -->
                <div class="flex items-center gap-2 sm:gap-4">
                    <div class="scale-90 sm:scale-100 origin-left">
                        <x-livewire.atoms.buttons.increment-decrement-cart
                            :product="$product"
                            :product-quantity="$quantity"
                        />
                    </div>

                    <div class="h-5 w-px bg-slate-100 hidden sm:block"></div>

                    <div class="flex items-center scale-90 sm:scale-100">
                        <x-livewire.atoms.buttons.remove-from-cart
                            :product="$product"
                        />
                    </div>
                </div>

                <!-- Price Stack -->
                <div class="text-right flex flex-col justify-end shrink-0">
                    @php
                        $has_discount = !is_null($product->price_with_discount);
                    @endphp

                    @if ($has_discount && $cart->hasProduct($product))
                        <span class="text-[9px] sm:text-[11px] text-slate-500 block leading-none mb-0.5">
                            {{ $cart->getTotalCostforProductWithoutDiscount($product, true) }}
                        </span>
                    @endif

                    <span @class([
                        'text-sm sm:text-lg font-black block leading-none tabular-nums',
                        'text-slate-900' => !$has_discount,
                        'text-emerald-600' => $has_discount,
                    ])>
                        @if($cart->hasProduct($product))
                            {{ $cart->getTotalCostforProduct($product, true) }}
                        @endif
                    </span>
                </div>
            </div>

        </div>
    </div>
</div>