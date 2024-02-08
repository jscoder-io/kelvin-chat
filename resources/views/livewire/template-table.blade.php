<div class="bg-white p-4 border border-gray-300 rounded-md shadow-md">
    <table class="w-full">
        <tr class="font-bold border-b-2">
            <td class="w-1/12 pb-2">{{ __('#') }}</td>
            <td class="pb-2">{{ __('Message') }}</td>
            <td class="w-1/12 pb-2">{{ __('Shop') }}</td>
            <td class="w-1/12 pb-2">{{ __('Action') }}</td>
        </tr>
        @php $n = 1 @endphp
        @forelse ($templates as $template)
        <tr class="border-t align-top">
            <td class="py-2">{{ $n }}</td>
            <td class="py-2">{{ $template->message }}</td>
            <td class="py-2">{!! $this->getShopList($template->shop) !!}</td>
            <td class="py-2">
                <div class="flex">
                    <a wire:click="$dispatch('openModal', { component: 'edit-template', arguments: { template: {{ $template->id }} }})" class="cursor-pointer" title="Edit">
                        <i class="bi bi-pencil-square mr-4"></i>
                    </a>
                    <a wire:click="$dispatch('openModal', { component: 'delete-template', arguments: { template: {{ $template->id }} }})" class="cursor-pointer" title="Delete">
                        <i class="bi bi-trash"></i>
                    </a>
                </div>
            </td>
        </tr>
        @php $n++ @endphp
        @empty
        <tr class="border-t">
            <td class="py-2" colspan="4">{{ __('No records found') }}</td>
        </tr>
        @endforelse
    </table>
</div>