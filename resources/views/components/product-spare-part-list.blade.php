<div class="w-full max-w-full overflow-hidden">
    <ul class="divide-y divide-slate-400">
        @foreach ($relatedSpareparts as $spare_part)
            <li class="group py-5 px-4 transition-all duration-200 hover:bg-slate-50 md:flex md:items-start md:gap-6 rounded-2xl overflow-hidden min-w-0">

                <div class="flex-1 min-w-0 mb-4 md:mb-0">
                    <!-- Tightened the first column to 40px to give more room to the name -->
                    <div class="grid grid-cols-[40px_1fr] items-start gap-4 min-w-0">
                        <!-- Number in Image Indicator -->
                        <div class="flex flex-col items-center shrink-0 pt-0.5">
                            <span class="text-[6px] font-black uppercase tracking-tighter text-slate-400 mb-1 leading-none">{{ __('Pos.') }}</span>
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-slate-900 text-white text-[10px] font-black shadow-lg shadow-slate-200 ring-4 ring-white transition-transform group-hover:scale-110">
                                {{ $spare_part->number_in_image }}
                            </span>
                        </div>

                        <div class="min-w-0">
                            <!-- Allow up to 2 lines, but with more horizontal width due to tighter columns and gaps -->
                            <h4 class="text-sm font-bold text-slate-900 leading-snug group-hover:text-slate-600 transition-colors line-clamp-2" title="{{ $spare_part->name }}">
                                {{ $spare_part->name }}
                            </h4>
                            <div class="flex items-center gap-2 mt-2 min-w-0">
                                <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 shrink-0">{{ __('Ref.') }}</span>
                                <code class="text-[10px] text-slate-600 font-mono bg-slate-100/50 border border-slate-200 px-2 py-0.5 rounded shadow-sm truncate max-w-[140px] sm:max-w-none">
                                    {{ $spare_part->reference }}
                                </code>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action & Price Wrapper -->
                <div class="flex items-center justify-between md:justify-end gap-4 md:gap-6 border-t md:border-t-0 pt-4 md:pt-0 border-slate-100 min-w-0 md:shrink-0">
                    <!-- Prices -->
                    <div class="flex items-center gap-5 shrink-0">
                        @if($spare_part->price_with_discount)
                            <div class="flex flex-col text-right">
                                <span class="text-[8px] text-slate-900 uppercase font-black tracking-tighter bg-amber-100 px-1.5 py-0.5 rounded-[3px] mb-1 self-end">{{ __('Retailer') }}</span>
                                <span class="text-base font-black text-slate-900 leading-none tabular-nums">
                                    {{ $spare_part->getFormattedPriceWithDiscount() }}
                                </span>
                            </div>

                            <div class="flex flex-col text-right">
                                <span class="text-[8px] text-slate-600 uppercase font-bold tracking-tighter mb-1">{{ __('PVP') }}</span>
                                <span class="text-[11px] font-bold text-slate-600 leading-none tabular-nums">
                                    {{ $spare_part->getFormattedPrice() }}
                                </span>
                            </div>
                        @else
                            <div class="flex flex-col text-right">
                                <span class="text-[8px] text-slate-400 uppercase font-bold tracking-tighter mb-1">{{ __('Price') }}</span>
                                <span class="text-base font-black text-slate-900 leading-none tabular-nums">
                                    {{ $spare_part->getFormattedPrice() }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Cart Buttons -->
                    <div class="shrink-0">
                        <div class="scale-90 sm:scale-100 origin-right transition-all">
                            @livewire('buttons.product-cart-buttons', ['product' => $spare_part], key('sp-'.$spare_part->id))
                        </div>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>