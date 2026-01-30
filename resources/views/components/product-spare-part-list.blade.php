<div class="w-full max-w-full overflow-hidden">
    <ul class="divide-y divide-slate-400">
        @foreach ($relatedSpareparts as $spare_part)
            <li
                class="group min-w-0 overflow-hidden rounded-2xl px-4 py-5 transition-all duration-200 hover:bg-slate-50 md:flex md:items-start md:gap-6"
            >
                <div class="mb-4 min-w-0 flex-1 md:mb-0">
                    <!-- Tightened the first column to 40px to give more room to the name -->
                    <div class="grid min-w-0 grid-cols-[40px_1fr] items-start gap-4">
                        <!-- Number in Image Indicator -->
                        <div class="flex shrink-0 flex-col items-center pt-0.5">
                            <span
                                class="mb-1 text-[6px] leading-none font-black tracking-tighter text-slate-400 uppercase"
                            >
                                {{ __('Pos.') }}
                            </span>
                            <span
                                class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-slate-900 text-[10px] font-black text-white shadow-lg ring-4 shadow-slate-200 ring-white transition-transform"
                            >
                                {{ $spare_part->number_in_image }}
                            </span>
                        </div>

                        <div class="min-w-0">
                            <!-- Allow up to 2 lines, but with more horizontal width due to tighter columns and gaps -->
                            <h4
                                class="line-clamp-2 text-sm leading-snug font-bold text-slate-900 transition-colors group-hover:text-slate-600"
                                title="{{ $spare_part->name }}"
                            >
                                {{ $spare_part->name }}
                            </h4>
                            <div class="mt-2 flex min-w-0 items-center gap-2">
                                <span class="shrink-0 text-[9px] font-black tracking-widest text-slate-400 uppercase">
                                    {{ __('Ref.') }}
                                </span>
                                <code
                                    class="max-w-[140px] truncate rounded border border-slate-200 bg-slate-100/50 px-2 py-0.5 font-mono text-[10px] text-slate-600 shadow-sm sm:max-w-none"
                                >
                                    {{ $spare_part->reference }}
                                </code>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action & Price Wrapper -->
                <div
                    class="flex min-w-0 items-center justify-between gap-4 border-t border-slate-100 pt-4 md:shrink-0 md:justify-end md:gap-6 md:border-t-0 md:pt-0"
                >
                    <!-- Prices -->
                    <div class="flex shrink-0 items-center gap-5">
                        @if ($spare_part->price_with_discount)
                            <div class="flex flex-col text-right">
                                <span
                                    class="mb-1 self-end rounded-[3px] bg-amber-100 px-1.5 py-0.5 text-[8px] font-black tracking-tighter text-slate-900 uppercase"
                                >
                                    {{ __('Retailer') }}
                                </span>
                                <span class="text-base leading-none font-black text-slate-900 tabular-nums">
                                    {{ $spare_part->getFormattedPriceWithDiscount() }}
                                </span>
                            </div>

                            <div class="flex flex-col text-right">
                                <span class="mb-1 text-[8px] font-bold tracking-tighter text-slate-600 uppercase">
                                    {{ __('PVP') }}
                                </span>
                                <span class="text-[11px] leading-none font-bold text-slate-600 tabular-nums">
                                    {{ $spare_part->getFormattedPrice() }}
                                </span>
                            </div>
                        @else
                            <div class="flex flex-col text-right">
                                <span class="mb-1 text-[8px] font-bold tracking-tighter text-slate-400 uppercase">
                                    {{ __('Price') }}
                                </span>
                                <span class="text-base leading-none font-black text-slate-900 tabular-nums">
                                    {{ $spare_part->getFormattedPrice() }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Cart Buttons -->
                    <div class="shrink-0">
                        <div class="origin-right scale-90 transition-all sm:scale-100">
                            @livewire('buttons.product-cart-buttons', ['product' => $spare_part], key('sp-' . $spare_part->id))
                        </div>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>
