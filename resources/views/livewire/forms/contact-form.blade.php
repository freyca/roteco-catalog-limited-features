<div>
    <form wire:submit="submit" class="col-span-5 mt-10 mb-8">
        @if (session()->has('contactFormSuccess'))
            <div class="mx-auto mb-10 text-center">
                <p class="text-primary-500 mb-1 font-semibold">{{ __('We have received your message') }}</p>
                <p class="text-primary-800 mb-1 font-semibold">{{ __('We will answer back as soon as possible') }}</p>
            </div>
        @endif

        {{ $this->form }}

        <div class="mt-10">
            <button
                type="submit"
                class="bg-primary-600 hover:bg-primary-500 flex w-full items-center justify-center rounded-lg px-5 py-2.5 text-sm font-medium text-white"
            >
                {{ __('Send') }}
            </button>
        </div>
    </form>

    <x-filament-actions::modals />
</div>
