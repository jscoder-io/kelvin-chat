<x-admin-layout>
    <x-slot:title>
        <!-- <h1 class="text-3xl mb-0 text-[#5a5c69]">{{ __('Chat') }}</h1> -->
    </x-slot>

    <x-slot:button>
        {{-- Button --}}
    </x-slot>

    <livewire:chat-box :message="$id" />

    <livewire:token-alert />
</x-admin-layout>