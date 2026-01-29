<nav
    class="text-primary-700 border-primary-200 mx-4 flex rounded-full border px-3 py-3 md:px-5 md:py-4"
    aria-label="breadcrumb"
>
    <ol class="inline-flex items-center truncate md:space-x-2 rtl:space-x-reverse">
        @foreach ($breadcrumbs->getBreadCrumbs() as $breadcrumb => $url)
            @if ($loop->first)
                <li class="inline-flex items-center">
                    <a
                        href="{{ $url }}"
                        class="text-primary-700 hover:text-primary-600 inline-flex items-center text-sm font-medium"
                    >
                        @svg($breadcrumb, 'h-4 w-4')
                    </a>
                </li>
            @else
                <li @if($loop->last) {{ 'aria-current=page class=truncate' }} @endif>
                    <div class="flex items-center truncate">
                        @svg('heroicon-c-chevron-right', 'text-primary-800 text-semibold h-4 w-4')

                        @if (! $loop->last)
                            <a
                                href="{{ $url }}"
                                class="text-primary-700 hover:text-primary-600 ms-1 text-sm font-medium md:ms-2"
                            >
                                {{ $breadcrumb }}
                            </a>
                        @else
                            <span class="text-primary-500 ms-1 truncate text-sm font-medium md:ms-2">
                                {{ $breadcrumb }}
                            </span>
                        @endif
                    </div>
                </li>
            @endif
        @endforeach
    </ol>
</nav>
