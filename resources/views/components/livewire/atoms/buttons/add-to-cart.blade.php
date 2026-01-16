<form wire:submit.prevent="add" class="m-0">
    <button
        type="submit"
        class="group relative inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-slate-900 text-white rounded-xl shadow-lg shadow-slate-200 hover:bg-slate-800 transition-all active:scale-95 focus:outline-none focus:ring-4 focus:ring-slate-900/10 disabled:opacity-50"
        wire:loading.attr="disabled"
    >
        <!-- Static Content -->
        <span wire:loading.remove class="flex items-center gap-2">
            @svg('heroicon-o-shopping-bag', 'w-4 h-4 transition-transform group-hover:scale-110')
            <span class="text-xs font-black uppercase tracking-widest">
                {{ __('Add') }}
            </span>
        </span>

        <!-- Loading Content -->
        <span wire:loading class="flex items-center gap-2">
            <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </span>
    </button>
</form>