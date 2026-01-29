<div class="bg-primary-200 mx-4 my-2 rounded">
    <a href="/{{ $urlPrefix }}/{{ $product->slug }}">
        <li class="hover:bg-primary-400 flex items-center rounded p-4">
            <img
                src="{{ asset('/storage/' . $product->main_image) }}"
                style="height: 3rem; width: 3rem"
                class="mr-4 max-w-none rounded-full object-cover object-center ring-white"
            />
            <span class="text-primary-800 overflow-hidden text-nowrap text-ellipsis">
                {{ $product->name }}
            </span>
        </li>
    </a>
</div>
