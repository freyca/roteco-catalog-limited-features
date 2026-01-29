<div>
    <p>
        <span class="text-md text-primary-100 bg-primary-800 mr-4 rounded p-3 px-4 font-semibold">
            @if (isset($variant) && ! is_null($variant))
                @if ($product->price_with_discount)
                    {{ $variant->getFormattedPriceWithDiscount() }}
                @else
                    {{ $variant->getFormattedPrice() }}
                @endif
            @else
                @if ($product->price_with_discount)
                    {{ $product->getFormattedPriceWithDiscount() }}
                @else
                    {{ $product->getFormattedPrice() }}
                @endif
            @endif
        </span>

        @if ($product->price_with_discount)
            @php
                $price = $product->getFormattedPrice();
            @endphp
        @endif

        @if (isset($variant) && ! is_null($variant) && $variant->price_with_discount)
            @php
                $price = $variant->getFormattedPrice();
            @endphp
        @endif

        @if (isset($price))
            <span class="text-primary-800 pr-2">
                {{ __('Before') . ': ' }}
                <span class="font-semibold line-through">
                    {{ $price }}
                </span>
            </span>
        @endif
    </p>
</div>
