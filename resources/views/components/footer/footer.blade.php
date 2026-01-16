<footer class="bg-primary-800 mt-auto rounded-t-lg">
    <div class="w-full max-w-7xl mx-auto py-2 sm:py-4">
        <div class="mx-4 sm:mx-10 flex items-center justify-between">
            <a href="/" class="text-sm font-medium text-primary-300 mb-2 sm:mb-0 sm:flex items-center text-center">
                <img src="{{ config('custom.web_logo') }}" class="h-8 mx-6" alt="{{ config('custom.web_logo_alt') }}" />
                <span class="self-center text-primary-50 whitespace-nowrap">
                    {{  config('custom.title') }}
                </span>
            </a>

            <ul
                class="text-sm mx-4 my-1 font-medium text-primary-300 sm:mb-0 flex flex-col md:flex-row md:items-center justify-items-center">
                @foreach (config('custom.footer-sections') as $section => $url)
                    <li class="my-1 md:text-center md:mx-2">
                        <a href="{{ $url }}" class="hover:underline">
                            {{ ucfirst($section) }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</footer>
