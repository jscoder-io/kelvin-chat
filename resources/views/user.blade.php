<x-admin-layout>
    <x-slot:title>
        {{ __('Staff') }}
    </x-slot>

    <x-slot:button>
        {{-- Button --}}
        <div id="btn-top">
            <button onclick="Livewire.dispatch('openModal', { component: 'create-user' })" type="button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus-visible:outline-none hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('New User') }}
            </button>
        </div>
    </x-slot>

    <livewire:user-table />
</x-admin-layout>