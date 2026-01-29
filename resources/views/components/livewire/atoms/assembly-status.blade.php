<div>
    <label class="inline-flex cursor-pointer items-center">
        <input
            type="checkbox"
            value=""
            class="peer sr-only"
            checked
            @if ($mandatoryassembly)
                disabled
            @endif
            wire:click="toggleAssemble"
        />

        <div
            class="bg-primary-400 peer after:border-primary-300 @if($mandatoryassembly) {{ 'peer-checked:bg-primary-300' }} @else {{ 'peer-checked:bg-primary-800' }} @endif relative h-6 w-11 rounded-full after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white rtl:peer-checked:after:-translate-x-full"
        ></div>

        <span class="text-primary-800 ms-3 font-semibold">
            {{ __("Assembly") . ":" }}
            @if ($mandatoryassembly)
                {{ "(" . __("mandatory") . ")" }}
            @endif

            {{ $assemblyPrice }}
        </span>
    </label>
</div>
