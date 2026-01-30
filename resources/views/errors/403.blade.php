<x-layouts.app>
    <section>
        <div class="container mx-auto p-4">
            <h1 class="mb-4 text-center text-3xl font-bold">
                OOOOOPS, parece que estás intentando acceder a una página prohibida
            </h1>

            <h2 class="mb-2 text-center text-xl">Quizá te interesen estos productos</h2>

            @php
                $productRepository = app(App\Repositories\Database\Product\Product\ProductRepositoryInterface::class);
                $featured_products = $productRepository->featured();
            @endphp

            <div class="container mx-auto py-8">
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-5">
                    @foreach ($featured_products as $product)
                        <x-product.product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
