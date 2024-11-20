<div class="bg-white p-4 border border-gray-300 rounded-md shadow-md" wire:poll>
    <table class="w-full">
        <tr class="font-bold border-b-2">
            <td class="w-1/12 pb-2">{{ __('#') }}</td>
            <td class="pb-2">{{ __('Name') }}</td>
            <td class="pb-2">{{ __('Marketplace') }}</td>
            <td class="pb-2">{{ __('Token') }}</td>
            <td class="w-1/12 pb-2">{{ __('Action') }}</td>
        </tr>
        @php $n = 1 @endphp
        @forelse ($shops as $shop)
        <tr class="border-t align-top">
            <td class="py-2">{{ $n }}</td>
            <td class="py-2">{{ $shop->name }}</td>
            <td class="py-2">{{ $shop->marketplace }}</td>
            <td class="py-2">
                @foreach ($shop->sortedToken() as $token)
                <div class="flex">
                    <div class="w-2/3 self-center">{{ $token->label }}</div>
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
            </td>
            <td class="py-2">
                <div class="flex">
                    <a wire:click="$dispatch('openModal', { component: 'check-token-shop', arguments: { shop: {{ $shop->id }}, close: true }})" class="cursor-pointer" title="Check Token">
                        <i class="bi bi-check2-circle mr-4"></i>
                    </a>
                    <a wire:click="$dispatch('openModal', { component: 'edit-shop', arguments: { shop: {{ $shop->id }} }})" class="cursor-pointer" title="Edit">
                        <i class="bi bi-pencil-square mr-4"></i>
                    </a>
                    @if (auth()->user()->role < 2)
                    <a wire:click="$dispatch('openModal', { component: 'delete-shop', arguments: { shop: {{ $shop->id }} }})" class="cursor-pointer" title="Delete">
                        <i class="bi bi-trash"></i>
                    </a>
                    @endif
                </div>
            </td>
        </tr>
        @php $n++ @endphp
        @empty
        <tr class="border-t">
            <td class="py-2" colspan="5">{{ __('No records found') }}</td>
        </tr>
        @endforelse
    </table>
</div>
