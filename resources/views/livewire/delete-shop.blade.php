<div class="p-4">
    <h1 class="text-xl font-bold mb-4 pb-2 border-b">{{ __('Delete Shop') }}</h1>
    <div class="mb-4">
        <p class="text-base">Do you want to delete shop "<span class="font-bold">{{ $shop->name }}</span>"?</p>
    </div>
    <div class="flex justify-end mt-8 pt-4 border-t">
        <button type="button" wire:click="delete" class="inline-flex items-center px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus-visible:outline-none hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            {{ __('Yes, I do') }}
        </button>
        <button type="button" wire:click="$dispatch('closeModal')" class="inline-flex items-center ml-4 px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus-visible:outline-none hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            {{ __('Cancel') }}
        </button>
    </div>
</div>
