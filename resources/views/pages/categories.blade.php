<x-layouts.app>
    <x-bread-crumbs :breadcrumbs="$breadcrumbs" />

    <h1 class="mt-5 mb-4 text-center text-3xl font-bold">
        {{ __('Categories') }}
    </h1>

    <x-category-grid :categories="$categories" />

    <x-buttons.whats-app-button />
</x-layouts.app>
