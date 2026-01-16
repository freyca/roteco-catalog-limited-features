<div class="overflow-hidden">
    <ul class="divide-y divide-slate-100">
        @foreach ($relatedSpareparts as $spare_part)
            <li class="group py-5 px-4 transition-all duration-200 hover:bg-slate-50 md:flex md:items-center md:gap-6 rounded-2xl">

                <!-- Part Info -->
                <div class="flex-1 min-w-0 mb-4 md:mb-0">
                    <div class="flex items-center gap-5">
                        <!-- Number in Image Indicator -->
                        <div class="flex flex-col items-center shrink-0">
                            <span class="text-[7px] font-black uppercase tracking-tighter text-slate-400 mb-1 leading-none">{{ __('Img num') }}</span>
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-900 text-white text-xs font-black shadow-lg shadow-slate-200 ring-4 ring-white">
                                {{ $spare_part->number_in_image }}
                            </span>
                        </div>

                        <div class="min-w-0">
                            <h4 class="text-sm font-bold text-slate-900 leading-tight group-hover:text-slate-600 transition-colors truncate">
                                {{ $spare_part->name }}
                            </h4>
                            <div class="flex items-center gap-2 mt-1.5">
                                <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">{{ __('Reference') }}</span>
                                <code class="text-[10px] text-slate-600 font-mono bg-slate-100/50 border border-slate-200 px-2 py-0.5 rounded shadow-sm">
                                    {{ $spare_part->reference }}
                                </code>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action & Price Wrapper -->
                <div class="flex items-center justify-between md:justify-end gap-6 md:gap-10 border-t md:border-t-0 pt-4 md:pt-0 border-slate-100 ml-11 md:ml-0">

                    <!-- Prices -->
                    <div class="flex items-center gap-4">
                        @if($spare_part->price_with_discount)
                            <div class="flex flex-col text-right">
                                <span class="text-[9px] text-slate-900 uppercase font-black tracking-tighter mb-0.5">{{ __('Retailer') }}</span>
                                <span class="text-base font-black text-slate-900 leading-none tabular-nums">
                                    {{ $spare_part->getFormattedPriceWithDiscount() }}
                                </span>
                            </div>

                            <div class="h-8 w-px bg-slate-200 hidden sm:block"></div>

                            <div class="flex flex-col text-right">
                                <span class="text-[9px] text-slate-500 uppercase font-bold tracking-tighter mb-0.5">{{ __('PVP') }}</span>
                                <span class="text-xs font-bold text-slate-500 leading-none tabular-nums">
                                    {{ $spare_part->getFormattedPrice() }}
                                </span>
                            </div>
                        @else
                            <div class="flex flex-col text-right">
                                <span class="text-[9px] text-slate-400 uppercase font-bold tracking-tighter mb-0.5">{{ __('Price') }}</span>
                                <span class="text-base font-black text-slate-900 leading-none tabular-nums">
                                    {{ $spare_part->getFormattedPrice() }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="shrink-0">
                        @livewire('buttons.product-cart-buttons', ['product' => $spare_part], key('sp-'.$spare_part->id))
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>