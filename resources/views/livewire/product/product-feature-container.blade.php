<div class="my-10">
    <div class="flex items-center justify-center">
        <p class="bg-primary-800 my-6 max-w-2xl rounded-xl p-4 text-center">
            <span class="text-primary-100 text-lg font-bold">
                {{ __('Technical details') }}
            </span>
        </p>
    </div>

    <div id="accordion-collapse" data-accordion="collapse" class="grid grid-cols-1 gap-1 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Get associated families --}}
        @foreach ($features->pluck('family')->unique() as $featureFamily)
            <div>
                {{-- Print name of family --}}
                <h3
                    class="font-md bg-primary-800 border-primary-200 text-primary-200 mt-6 rounded rounded-md border p-5 text-center font-semibold"
                >
                    {{ __($featureFamily->value) }}
                </h3>

                @foreach ($features as $feature)
                    {{-- Print the feature only if it belogns to family --}}
                    @if ($feature->family === $featureFamily)
                        @foreach ($featureValues as $featureValue)
                            {{-- Print feature value only if belongs to feature --}}
                            @if ($featureValue->product_feature_id === $feature->id)
                                <x-product.product-feature :feature="$feature" :featureValue="$featureValue" />
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>
        @endforeach
    </div>
</div>
