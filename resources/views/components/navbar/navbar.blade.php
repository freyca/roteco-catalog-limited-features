<nav class="text-primary-800 z-50 px-2">
    <div class="container mx-auto mt-2 flex items-center justify-between md:mt-6">
        <div class="flex-start flex">
            <a href="/" class="text-primary-50 mr-4 text-2xl font-bold">
                <img src="{{ config('custom.web_logo') }}" class="h-13" alt="{{ config('custom.web_logo_alt') }}" />
            </a>

            <div class="hidden content-start space-x-4 md:flex">
                @if (Auth::user())
                    @foreach (config('custom.nav-sections') as $section => $url)
                        <a
                            class="hover:bg-primary-300 relative block space-y-3 rounded p-3 hover:text-white"
                            href="{{ $url }}"
                        >
                            <p class="text-primary-700 font-semibold">{{ ucfirst($section) }}</p>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="flex-end flex">
            @if (Auth::user())
                <div class="hidden content-start space-x-4 md:flex">
                    @livewire('search-bar')
                </div>
            @endif

            <div class="flex space-x-4">
                @if (Auth::user())
                    <button id="search-button" class="text-primary-900 md:hidden">
                        @svg('heroicon-o-magnifying-glass', 'h-8 w-8')
                    </button>

                    <a href="/user">
                        <button
                            type="button"
                            class="flex rounded-full text-sm md:me-0"
                            id="user-menu-button"
                            aria-expanded="false"
                        >
                            <span class="sr-only">Login</span>
                            @svg('heroicon-s-user', 'h-8 w-8')
                        </button>
                    </a>
                    @livewire('buttons.cart-icon')

                    <button id="menu-button" class="text-primary-900 mx-3 md:hidden">
                        @svg('heroicon-o-bars-3-bottom-right', 'h-8 w-8')
                    </button>
                @endif
            </div>
        </div>
    </div>

    @if (Auth::user())
        <div id="mobile-menu" class="mx-5 hidden space-x-4 md:hidden">
            <ul class="mt-5 space-y-2">
                @foreach (config('custom.nav-sections') as $section => $url)
                    <li class="@if(!$loop->first) border-primary-800 border-t-2 @endif">
                        <a class="block py-1" href="{{ $url }}">
                            <p>
                                {{ ucfirst($section) }}
                            </p>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div id="mobile-search-bar" class="mx-2 hidden md:hidden">
            @livewire('search-bar')
        </div>
    @endif
</nav>

<script>
    document.getElementById('menu-button').addEventListener('click', function () {
        var mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });

    document.getElementById('search-button').addEventListener('click', function () {
        var searchBar = document.getElementById('mobile-search-bar');
        searchBar.classList.toggle('hidden');
        searchBar.querySelector('input').focus();
    });
</script>
