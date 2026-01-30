<aside
    id="filter-side-menu"
    @class([
        'open' => $is_hidden,
        'top-0',
        'md:top-28',
        'z-50',
        'md:z-0',
        'fixed',
        'left-0',
        'p-1',
        'h-full',
        'w-full',
        'md:w-2/5',
        'xl:w-1/5',
        'transition-transform',
        'duration-500',
        'ease-in-out',
        'overflow-y-auto',
        'bg-gray-50',
        'rounded-r',
        'mb-10',
    ])
>
    <div class="filters relative h-full rounded-lg bg-white p-6 shadow-md">
        <button
            id="open-filter-side-menu"
            class="absolute right-10 rounded-full p-3 text-black"
            wire:click="toggleFilterBar"
            aria-label="Abrir filtros"
        >
            @svg('heroicon-o-x-mark', 'h-6 w-6')
        </button>

        <h3 class="text-primary-900 text-2xl font-semibold">
            {{ __('Search filters') }}
        </h3>

        <button
            type="button"
            class="hover:bg-primary-700 my-4 rounded bg-slate-500 px-4 py-2 font-bold text-white"
            wire:click="clearFilters"
        >
            {{ __('Clear all filters') }}
        </button>

        <form wire:change.debounce.500ms="filterProducts">
            @if ($enabled_filters['category'] === true)
                <!-- Filtro de Categoría -->
                <div class="filter-category mb-4">
                    <label for="category" class="text-primary-700 block">{{ __('Category') }}:</label>
                    <select
                        wire:model="filtered_category"
                        id="category-filter"
                        class="form-select border-primary-300 filter-item mt-1 block w-full rounded-lg border p-2"
                    >
                        <option value="0">{{ __('Select category') }}</option>
                        @foreach (\App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}">{{ __($category->name) }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if ($enabled_filters['price'] === true)
                <!-- Filtro de Precio -->
                <div class="filter-price mb-4">
                    <label for="price" class="text-primary-700 block">
                        {{ __('Price range') }}
                    </label>
                    <div class="mt-1">
                        <label for="min_price" class="text-primary-600 text-sm">
                            {{ __('Min Price') . ': ' . $min_price . ' €' }}
                        </label>
                        <div class="flex items-center">
                            <input
                                type="range"
                                wire:model.debounce.500ms="min_price"
                                id="min_price"
                                min="0"
                                max="10000"
                                step="100"
                                class="filter-item mr-2 w-full accent-red-500"
                            />
                        </div>
                    </div>
                    <div class="mt-1">
                        <label for="max_price" class="text-primary-600 text-sm">
                            {{ __('Max Price') . ': ' . $max_price . ' €' }}
                        </label>
                        <div class="flex items-center">
                            <input
                                type="range"
                                wire:model.debounce.500ms="max_price"
                                id="max_price"
                                min="0"
                                max="10000"
                                step="100"
                                class="filter-item mr-2 w-full"
                            />
                        </div>
                    </div>
                </div>
            @endif
        </form>
    </div>
</aside>
