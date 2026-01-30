<div
    id="search-bar-container"
    class="container mx-auto mt-4 mr-4 block w-full items-center justify-between md:mt-0 md:w-96"
>
    <form id="search-form" role="search" class="float-end w-full" x-on:focusout="$wire.set('searchTerm', '')">
        <div class="relative float-end w-full text-center md:block">
            <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3">
                @svg('heroicon-o-magnifying-glass', 'h-4 w-4 text-black')
                <span class="sr-only">{{ __('Search icon') }}</span>
            </div>

            <input
                wire:model.live="searchTerm"
                type="search"
                id="search-navbar"
                aria-label="Search"
                class="text-primary-800 border-primary-300 bg-primary-50 focus:ring-primary-800 focus:border-primary-800 block w-full rounded-3xl border p-2 ps-10 text-sm"
                placeholder="{{ __('Search') }}..."
            />
        </div>

        @if (count($results) > 0)
            <div
                id="dropdownHover"
                class="bg-primary-800 absolute z-50 mt-12 -ml-4 max-w-full min-w-full rounded sm:ml-0 sm:min-w-96"
            >
                <ul class="text-primary-100 min-w-full py-2 text-sm">
                    @if (isset($results['products']) && $results['products']->count() > 0)
                        @foreach ($results['products'] as $product)
                            <x-searchbar.search-result :product="$product" urlPrefix="producto" />
                        @endforeach
                    @endif

                    @if (isset($results['complements']) && $results['complements']->count() > 0)
                        @foreach ($results['complements'] as $product)
                            <x-searchbar.search-result :product="$product" urlPrefix="complemento" />
                        @endforeach
                    @endif

                    @if (isset($results['spare-parts']) && $results['spare-parts']->count() > 0)
                        @foreach ($results['spare-parts'] as $product)
                            <x-searchbar.search-result :product="$product" urlPrefix="pieza-de-repuesto" />
                        @endforeach
                    @endif
                </ul>
            </div>
        @endif
    </form>
</div>
