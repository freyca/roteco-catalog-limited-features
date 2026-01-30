<form wire:submit.prevent="remove" class="m-0 inline-flex">
    <button
        type="submit"
        class="group flex h-9 w-9 items-center justify-center rounded-xl text-slate-500 transition-all hover:bg-red-50 hover:text-red-600 focus:ring-2 focus:ring-red-500/10 focus:outline-none"
        aria-label="{{ __('Remove') }}"
        wire:loading.attr="disabled"
    >
        <span wire:loading.remove wire:target="remove,increment,decrement">
            @svg('heroicon-s-trash', 'h-5 w-5')
        </span>

        <span wire:loading wire:target="remove,increment,decrement">
            <x-filament::loading-indicator class="h-5 w-5" />
        </span>
    </button>
</form>
