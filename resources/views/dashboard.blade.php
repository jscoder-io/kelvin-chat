<x-admin-layout>
    <x-slot:title>
        <h1 class="text-3xl mb-0 text-[#5a5c69]">{{ __('Dashboard') }}</h1>
    </x-slot>

    <x-slot:button>
        {{-- Button --}}
    </x-slot>

    <div class="grid grid-cols-4 gap-4 mb-4">
        @foreach ($shops as $shop)
        <div class="border border-gray-300 rounded-md">
            <div class="px-4 py-1 bg-gray-200 border-b border-b-gray-300 rounded-t-md">
                <h1 class="text-base font-bold">{{ $shop->name }}</h1>
                <h3 class="text-sm">{{ $shop->marketplace }}</h3>
            </div>
            <div class="p-4 pb-2 bg-white rounded-b-md carousell-sg">
                @foreach ($shop->tokens as $token)
                <div class="w-full flex mb-2">
                    <div class="w-2/3">{{ $token->label }}</div>
                    <div class="w-1/3 self-center">
                        @if ($token->status == 'valid')
                        <div class="w-2 h-2 rounded-full bg-green-500"></div>
                        @elseif ($token->status == 'invalid')
                        <div class="w-2 h-2 rounded-full bg-red-500"></div>
                        @else
                        <div class="w-2 h-2 rounded-full bg-gray-500"></div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</x-admin-layout>