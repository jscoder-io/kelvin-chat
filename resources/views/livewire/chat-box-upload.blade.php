<div class="p-4">
    <h1 class="text-xl font-bold mb-4 pb-2 border-b">{{ __('Upload Image') }}</h1>
    <div class="mb-4">
        <input type="file" wire:model="file" class="block focus-visible:outline-none">
        <div class="mt-4" wire:loading wire:target="file">Uploading...</div>
        @error('file') <span class="text-red-400">{{ $message }}</span> @enderror
    </div>
    <div class="flex justify-end mt-8 pt-4 border-t">
        <button type="button" wire:click="send" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus-visible:outline-none hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            {{ __('Send') }}
        </button>
        <button type="button" wire:click="$dispatch('closeModal')" class="inline-flex items-center ml-4 px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus-visible:outline-none hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            {{ __('Cancel') }}
        </button>
    </div>
</div>
