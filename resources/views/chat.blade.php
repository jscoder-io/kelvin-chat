<x-admin-layout>
    <x-slot:title>
        {{ __('Chat') }}
    </x-slot>

    <x-slot:button>
        {{-- Button --}}
    </x-slot>

    <livewire:chat-box :message="$id" />
</x-admin-layout>