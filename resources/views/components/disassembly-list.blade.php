<div id="accordion-collapse" data-accordion="collapse">
    @php
        $counter = 1; // Initialize counter for accordion items
    @endphp

    @foreach ($relatedDisassemblies as $disassembly)
        <h2 id="accordion-collapse-heading-{{ $counter }}" onclick="changeImage({{ $disassembly }})">
            <button
                type="button"
                class="flex w-full items-center justify-between gap-3 rounded-t-xl border border-b-0 border-gray-200 p-5 font-medium text-gray-500 hover:bg-gray-100 rtl:text-right"
                data-accordion-target="#accordion-collapse-body-{{ $counter }}"
                aria-expanded="false"
                aria-controls="accordion-collapse-body-{{ $counter }}"
            >
                <span>{{ $disassembly->name }}</span>
                <svg
                    data-accordion-icon
                    class="h-3 w-3 shrink-0 rotate-180"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 10 6"
                >
                    <path
                        stroke="currentColor"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5 5 1 1 5"
                    />
                </svg>
            </button>
        </h2>

        <div
            id="accordion-collapse-body-{{ $counter }}"
            class="hidden"
            aria-labelledby="accordion-collapse-heading-{{ $counter }}"
        >
            <x-product-spare-part-list :relatedSpareparts="$disassembly->productSpareParts" />
        </div>

        @php
            $counter++;
        @endphp
    @endforeach
</div>

<script>
    function changeImage(disassembly) {
        window.dispatchEvent(
            new CustomEvent('set-product-image', {
                detail: window.location.origin + '/storage/' + disassembly.main_image,
            }),
        );
    }
</script>
