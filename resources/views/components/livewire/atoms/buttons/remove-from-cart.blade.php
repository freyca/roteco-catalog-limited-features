<form wire:submit.prevent="remove" class="m-0 inline-flex">
    <button
        type="submit"
        class="group flex items-center justify-center w-9 h-9 rounded-xl text-slate-500 hover:text-red-600 hover:bg-red-50 transition-all focus:outline-none focus:ring-2 focus:ring-red-500/10"
        aria-label="{{ __('Remove') }}"
        wire:loading.attr="disabled"
    >
        <span wire:loading.remove>
            @svg('heroicon-s-trash', 'h-5 w-5')
        </span>

        <span wire:loading>
            <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </span>
    </button>
</form>