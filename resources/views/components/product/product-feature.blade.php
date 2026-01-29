@php
    $id = md5($featureValue->name);
@endphp

<h2 id="accordion-collapse-heading-{{ $id }}">
    <button
        type="button"
        class="bg-primary-100 border-primary-200 hover:bg-primary-200 flex w-full items-center justify-between gap-3 rounded border p-5 font-medium"
        data-accordion-target="#accordion-collapse-body-{{ $id }}"
        aria-expanded="true"
        aria-controls="accordion-collapse-body-{{ $id }}"
    >
        <span class="text-primary-800 text-sm">
            {{ __($feature->name) . ': ' . $featureValue->name }}
        </span>
        @svg('heroicon-o-chevron-down', 'h-6 w-6')
    </button>
</h2>

<div id="accordion-collapse-body-{{ $id }}" class="hidden" aria-labelledby="accordion-collapse-heading-{{ $id }}">
    <div class="border-primary-200 border border-b-0 bg-white px-5 py-2">
        <p class="text-primary-800 tx mb-2">
            {!! $featureValue->description !!}
        </p>
    </div>
</div>
