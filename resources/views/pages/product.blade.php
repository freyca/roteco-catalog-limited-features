<x-layouts.app> {{-- :seotags="$seotags"> --}}
    @inject(cart, '\App\Services\Cart')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-bread-crumbs :breadcrumbs="$breadcrumbs" class="py-6" />

        <!-- Product Hero Section -->
        <header class="mt-6 mb-12">
            <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tighter mb-4">
                {{ $product->name }}
            </h1>
        </header>

        <div class="grid gap-12 lg:grid-cols-12 items-start">
            <!-- Left: Gallery (Sticky) -->
            <div class="lg:col-span-5 xl:col-span-5 lg:sticky lg:top-24">
                <div class="bg-white rounded-3xl p-4 shadow-xl shadow-slate-200/50 ring-1 ring-slate-100">
                    <x-product.product-image-gallery :image="$product->main_image" />
                </div>

                @if($product->short_description)
                <div id="product-short-description" class="mt-8 prose prose-slate prose-sm text-slate-600 max-w-none px-2">
                    {!! $product->short_description !!}
                </div>
                @endif
            </div>

            <!-- Right: Content & Disassembly -->
            <div class="lg:col-span-7 xl:col-span-7 space-y-10">
                <section>
                    <div class="flex items-center justify-end mb-6 px-2">
                        <span class="text-[10px] font-bold px-2 py-1 bg-slate-100 text-slate-500 rounded-md">
                            {{ count($relatedDisassemblies) }} {{ __('Disassemblies') }}
                        </span>
                    </div>

                    <x-disassembly-list :relatedDisassemblies="$relatedDisassemblies" />
                </section>
            </div>
        </div>

        <!-- Long Description Section -->
        @if($product->description)
        <div class="mt-24 mb-16 border-t border-slate-100 pt-16">
            <div class="max-w-3xl mx-auto">
                <h3 class="text-2xl font-black text-slate-900 mb-8 text-center uppercase tracking-tight">
                    {{ __('Full Specifications') }}
                </h3>
                <div id="product-long-description" class="prose prose-slate prose-lg text-slate-600 text-justify max-w-none">
                    {!! $product->description !!}
                </div>
            </div>
        </div>
        @endif

        <!-- Featured Products -->
        @if(isset($featuredProducts) && $featuredProducts->count() > 0)
            <div class="mt-24 mb-12">
                <h3 class="text-xl font-black text-slate-900 mb-10 flex items-center gap-4 uppercase tracking-tighter">
                    {{ __('You might also need') }}
                    <span class="flex-1 h-px bg-slate-100"></span>
                </h3>
                <x-product-grid :products="$featuredProducts" />
            </div>
        @endif
    </div>

    <x-buttons.whats-app-button />
</x-layouts.app>