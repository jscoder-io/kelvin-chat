<x-admin-layout>
    <x-slot:title>
        <h1 class="text-3xl mb-0 text-[#5a5c69]">{{ __('Shop') }}</h1>
    </x-slot>

    <x-slot:button>
        {{-- Button --}}
        <div id="btn-top">
            @if (auth()->user()->role < 2)
            <button onclick="Livewire.dispatch('openModal', { component: 'create-shop' })" type="button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus-visible:outline-none hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('New Shop') }}
            </button>
            @endif
            <button onclick="Livewire.dispatch('openModal', { component: 'check-token-all-shop' })" type="button" class="inline-flex items-center ml-2 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus-visible:outline-none hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Check Token') }}
            </button>
        </div>
    </x-slot>

    <livewire:shop-table />
</x-admin-layout>