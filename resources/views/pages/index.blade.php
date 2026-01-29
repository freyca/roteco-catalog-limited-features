<x-layouts.app>
    <!-- <x-sliders.main-slider /> -->

    <div class="rounded-top-md container">
        <div class="container mx-auto">
            <h2 class="justify-left text-primary-800 mx-auto mt-10 ml-4 flex text-3xl font-bold">
                {{ __('Categories') }}
            </h2>

            <x-category-grid :categories="$categories" />
        </div>

        <div class="container mx-auto">
            <h2 class="justify-left text-primary-800 mx-auto mt-10 ml-4 flex text-3xl font-bold">
                {{ __('Featured products') }}
            </h2>

            <div class="main-content w-auto px-4 transition-all duration-500 ease-in-out">
                <x-product-grid :products="$products" />
            </div>
        </div>

        <x-buttons.whats-app-button />
    </div>
</x-layouts.app>
