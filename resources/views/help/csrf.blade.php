<div class="p-4">
    <div class="flex justify-between mb-4 pb-2 border-b">
        <h1 class="text-xl font-bold">{{ __('Help - How to get _csrf') }}</h1>
        <button type="button" wire:click="$dispatch('closeModal')" class="inline-flex items-center ml-4 px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus-visible:outline-none hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            {{ __('Back') }}
        </button>
    </div>
    <img src="{{ url('images/help/jwt-token-csrf.jpg') }}" />
</div>