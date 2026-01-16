@inject('cart', 'App\Services\Cart')

<div class="w-full bg-white border border-gray-100 rounded-xl shadow-sm px-4 py-3 md:p-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 md:gap-4">

        <!-- SECTION 1: Image & Title Info -->
        <div class="flex items-center flex-1 gap-3 min-w-0">
            <a href="{{ $path . '/' . $related_product->slug }}" class="shrink-0">
                <div class="bg-gray-50 rounded-lg p-1.5 flex items-center justify-center border border-gray-100">
                    <img class="h-16 w-16 md:h-24 md:w-24 object-contain mx-auto rounded-md"
                        src="{{ asset('/storage/' . $related_product->main_image) }}"
                        alt="{{ $product->name }}"
                    />
                </div>
            </a>

            <div class="min-w-0 flex-1">
                <a href="{{ $path . '/' . $related_product->slug }}"
                    class="text-sm md:text-lg font-semibold text-gray-900 hover:text-primary-700 leading-tight uppercase">
                    {{ $product->name }}
                </a>
            </div>
        </div>

        <hr class="md:hidden border-gray-100 my-1">

        <!-- SECTION 2: Controls & Pricing -->
        <div class="flex items-center justify-between md:justify-end gap-4 md:gap-12">

            <!-- Quantity Controls and Trash Bin -->
            <div class="flex items-center md:gap-2">
                <div class="flex items-center scale-90 md:scale-100 origin-left">
                    <x-livewire.atoms.buttons.increment-decrement-cart
                        :product="$product"
                        :product-quantity="$quantity"
                    />
                </div>

                <div class="flex items-center text-gray-400 hover:text-red-600 transition-colors">
                    <x-livewire.atoms.buttons.remove-from-cart
                        :product="$product"
                    />
                </div>
            </div>

            <!-- Price Display -->
            <div class="text-right flex flex-col justify-center min-w-[100px]">
                @php
                    $has_discount = !is_null($product->price_with_discount);
                @endphp

                @if ($has_discount && $cart->hasProduct($product))
                    <span class="text-[10px] md:text-sm line-through text-gray-500 block leading-none">
                        {{ $cart->getTotalCostforProductWithoutDiscount($product, true) }}
                    </span>
                @endif

                <span @class([
                    'text-base md:text-lg font-semibold block leading-tight',
                    'text-gray-600' => !$has_discount,
                    'text-green-600' => $has_discount,
                ])>
                    @if($cart->hasProduct($product))
                        {{ $cart->getTotalCostforProduct($product, true) }}
                    @endif
                </span>
            </div>
        </div>

    </div>
</div>