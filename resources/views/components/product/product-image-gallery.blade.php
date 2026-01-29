<div
    x-data="{
        open: false,
        image: '{{ asset('storage/' . $image) }}',
    }"
    @keydown.escape.window="open = false"
    @set-product-image.window="image = $event.detail"
    x-cloak
>
    <!-- Thumbnail -->
    <div class="mx-auto w-full max-w-lg">
        <div class="relative w-full cursor-zoom-in overflow-hidden pb-[112.5%]">
            <div class="absolute inset-0" x-transition.opacity.duration.700ms>
                <img
                    @click="open = true"
                    :src="image"
                    class="absolute inset-0 h-full w-full rounded-md object-cover"
                    alt="Product image"
                />
            </div>
        </div>
    </div>

    <!-- Overlay -->
    <div
        x-show="open"
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/70"
        @click.self="open = false"
    >
        <img
            x-transition.scale
            @click.stop="open = false"
            :src="image"
            class="max-h-[90vh] max-w-[90vw] cursor-zoom-out rounded-lg shadow-2xl"
            alt="Enlarged product image"
        />
    </div>
</div>
