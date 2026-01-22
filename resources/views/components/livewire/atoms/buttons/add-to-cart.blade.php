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
        <span wire:loading wire:target="add" class="flex items-center gap-2">
            <x-filament::loading-indicator class="h-5 w-5" />
        </span>
    </button>
</form>