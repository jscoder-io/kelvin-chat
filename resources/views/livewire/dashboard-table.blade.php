<div class="grid grid-cols-4 gap-4 mb-4" wire:poll>
    @foreach ($shops as $shop)
    <div class="border border-gray-300 rounded-md">
        <div class="px-4 py-1 bg-gray-200 border-b border-b-gray-300 rounded-t-md" style="position:relative;">
            <h1 class="text-base font-bold">{{ $shop->name }}</h1>
            <h3 class="text-sm">{{ $shop->marketplace }}</h3>
            <a wire:click="$dispatch('openModal', { component: 'edit-shop', arguments: { shop: {{ $shop->id }} }})" class="cursor-pointer" title="Edit" style="position:absolute;top:5px;right:10px;width:16px;height:22px;">
                <i class="bi bi-pencil-square"></i>
            </a>
        </div>
        <div class="p-4 pb-2 bg-white rounded-b-md carousell-sg">
            @foreach ($shop->sortedToken() as $token)
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