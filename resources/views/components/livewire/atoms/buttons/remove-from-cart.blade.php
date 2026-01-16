{{-- resources/views/components/livewire/atoms/buttons/remove-from-cart.blade.php --}}
<form wire:submit="remove" class="m-0 flex items-center">
    <button type="submit"
        class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors">
        <span wire:loading.remove>
            @svg('heroicon-s-trash', 'h-5 w-5')
        </span>
        <span wire:loading class="text-xs">...</span>
    </button>
</form>