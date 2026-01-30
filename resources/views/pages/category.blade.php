<x-layouts.app>
    <div class="container mx-auto rounded-md">
        <x-bread-crumbs :breadcrumbs="$breadcrumbs" />

        <div
            class="{{-- lg:grid-cols-3 --}} mt-4 mb-4 grid grid-cols-1 lg:gap-4"
        >
            <div class="col-span-2 m-4 grid place-content-center">
                <div class="align-middle">
                    <h1 class="my-4 text-3xl font-bold">
                        {{ $category->name }}
                    </h1>

                    <hr />

                    {{--
                        <p class="text-justify mt-4">
                        {!! $category->description !!}
                        </p>
                    --}}
                </div>
            </div>
        </div>

        <div class="main-content w-auto px-4 transition-all duration-500 ease-in-out">
            <x-product-grid :products="$products" />
        </div>
    </div>

    <x-buttons.whats-app-button />
</x-layouts.app>
