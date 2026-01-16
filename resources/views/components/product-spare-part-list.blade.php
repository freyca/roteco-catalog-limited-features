<div class="ml-3">
    <ul>
        @foreach ($relatedSpareparts as $spare_part)
            <div class="py-4 border-b border-gray-200 hover:bg-gray-50 transition-colors px-2 md:flex md:items-center md:gap-4">

                <!-- Part Info -->
                <div class="flex-1 mb-4 md:mb-0">
                    <div class="text-sm font-bold text-gray-900 leading-tight">
                        {{ $spare_part->number_in_image }} - {{ $spare_part->name }}
                    </div>
                    <div class="text-xs text-gray-400 font-mono mt-1">
                        Ref: {{ $spare_part->reference }}
                    </div>
                </div>

                <!-- Action & Price Wrapper -->
                <!-- On mobile, justify-between pushes Price left and Button right -->
                <div class="flex items-center justify-between md:justify-end gap-3 md:gap-8">

                    <!-- Prices -->
                    <div class="flex items-center gap-2 md:gap-3">
                        @if($spare_part->price_with_discount)
                            <div class="flex flex-col">
                                <span class="text-[9px] md:text-[10px] text-gray-400 uppercase font-bold leading-none mb-1">{{ __('Retailer') }}</span>
                                <span class="text-sm md:text-base font-bold text-green-600 leading-none">
                                    {{ $spare_part->getFormattedPriceWithDiscount() }}
                                </span>
                            </div>

                            <div class="h-6 w-px bg-gray-200"></div>

                            <div class="flex flex-col">
                                <span class="text-[9px] md:text-[10px] text-gray-400 uppercase font-bold leading-none mb-1">PVP</span>
                                <span class="text-[11px] md:text-xs font-medium text-gray-400 leading-none">
                                    {{ $spare_part->getFormattedPrice() }}
                                </span>
                            </div>
                        @else
                            <div class="flex flex-col">
                                <span class="text-[9px] md:text-[10px] text-gray-400 uppercase font-bold leading-none mb-1">PVP</span>
                                <span class="text-sm md:text-base font-bold text-gray-900 leading-none">
                                    {{ $spare_part->getFormattedPrice() }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="shrink-0">
                        @livewire('buttons.product-cart-buttons', ['product' => $spare_part], key('sp-'.$spare_part->id))
                    </div>
                </div>
            </div>
        @endforeach
    </ul>
</div>
