<footer class="bg-primary-800 mt-auto rounded-t-lg">
    <div class="mx-auto w-full max-w-7xl py-2 sm:py-4">
        <div class="mx-4 flex items-center justify-between sm:mx-10">
            <a href="/" class="text-primary-300 mb-2 items-center text-center text-sm font-medium sm:mb-0 sm:flex">
                <img
                    src="{{ config('custom.web_logo') }}"
                    class="mx-6 h-8"
                    alt="{{ config('custom.web_logo_alt') }}"
                />
                <span class="text-primary-50 self-center whitespace-nowrap">
                    {{ config('custom.title') }}
                </span>
            </a>

            <ul
                class="text-primary-300 mx-4 my-1 flex flex-col justify-items-center text-sm font-medium sm:mb-0 md:flex-row md:items-center"
            >
                @foreach (config('custom.footer-sections') as $section => $url)
                    <li class="my-1 md:mx-2 md:text-center">
                        <a href="{{ $url }}" class="hover:underline">{{ ucfirst($section) }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</footer>
