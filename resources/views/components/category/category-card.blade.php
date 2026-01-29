<div class="bg-primary-800 relative m-2 flex rounded-md">
    <a href="{{ $category->slug }}" class="mx-auto h-full">
        <figure>
            <picture>
                <img
                    class="p-6 pb-12"
                    src="{{ asset('/storage/' . $category->big_image) }}"
                    alt="{{ $category->name }}"
                />
            </picture>

            <figcaption class="absolute right-0 bottom-0 max-w-full min-w-full text-center md:max-w-32 md:min-w-64">
                <h3
                    class="text-primary-800 border-primary-800 truncate rounded-md border-2 bg-white md:border-r-4 md:border-b-4 md:text-lg md:font-semibold"
                >
                    <span class="px-2 py-2">
                        {{ $category->name }}
                    </span>
                </h3>
            </figcaption>
        </figure>
    </a>
</div>
