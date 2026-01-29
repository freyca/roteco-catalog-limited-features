<div class="bg-primary-200 w-full justify-center rounded-lg p-4 md:w-2/3">
    <div class="justify-text mb-2 px-2">
        <p class="font-semibold">{{ __('Important') . ':' }}</p>
        <p>
            {{ __('If this complement or spare part is for a product you purchased us before you have an special price') }}
        </p>

        @if (! auth()->user())
            <p>
                <span>{{ __('To enjoy this especial price login to your account') }}</span>
                <span>
                    <a class="font-md font-semibold underline" href="/user">
                        {{ __('here') }}
                    </a>
                </span>
            </p>
        @endif
    </div>

    <div class="px-2">
        <p class="text-md text-primary-100 bg-primary-800 mr-4 inline-block rounded p-3 px-4 font-semibold">
            {{ $product->getFormattedPriceWhenUserOwnsProduct() }}
        </p>
    </div>
</div>
