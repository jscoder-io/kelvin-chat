<div class="p-4">
    <h1 class="text-xl font-bold mb-4 pb-2 border-b">{{ __('Check Token') }}</h1>
    <div class="mb-4">
        <h3 class="text-base font-bold mb-2">{{ $shop->name }}</h3>
        @foreach ($shop->tokens as $token)
        <div class="flex">
            <div class="w-1/3 self-center">{{ $token->label }}</div>
            <div class="w-2/3 self-center" wire:stream="status">
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
    <div class="flex justify-end mt-8 pt-4 border-t">
        <button type="button" wire:click="scan" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus-visible:outline-none hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            {{ __('Scan') }}
        </button>
        <button type="button" wire:click="completed" class="inline-flex items-center ml-4 px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus-visible:outline-none hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            {{ __('Close') }}
        </button>
    </div>
</div>
