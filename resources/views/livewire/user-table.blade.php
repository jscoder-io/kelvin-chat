<div class="bg-white p-4 border border-gray-300 rounded-md shadow-md">
    <table class="w-full">
        <tr class="font-bold border-b-2">
            <td class="w-1/12 pb-2">{{ __('#') }}</td>
            <td class="pb-2">{{ __('Name') }}</td>
            <td class="pb-2">{{ __('Email') }}</td>
            <td class="pb-2">{{ __('Role') }}</td>
            <td class="pb-2">{{ __('Preset') }}</td>
            <td class="w-1/12 pb-2">{{ __('Action') }}</td>
        </tr>
        @php $n = 1 @endphp
        @forelse ($users as $user)
        <tr class="border-t align-top">
            <td class="py-2">{{ $n }}</td>
            <td class="py-2">{{ $user->name }}</td>
            <td class="py-2">{{ $user->email }}</td>
            <td class="py-2">
                @if ($user->role == 1)
                    {{ __('Admin') }}
                @else
                    {{ __('Staff') }}
                @endif
            </td>
            <td class="py-2">{{ $user->preset }}</td>
            <td class="py-2">
                <div class="flex">
                    <a wire:click="$dispatch('openModal', { component: 'edit-user', arguments: { user: {{ $user->id }} }})" class="cursor-pointer" title="Edit">
                        <i class="bi bi-pencil-square mr-4"></i>
                    </a>
                    <a wire:click="$dispatch('openModal', { component: 'delete-user', arguments: { user: {{ $user->id }} }})" class="cursor-pointer" title="Delete">
                        <i class="bi bi-trash"></i>
                    </a>
                </div>
            </td>
        </tr>
        @php $n++ @endphp
        @empty
        <tr class="border-t">
            <td class="py-2" colspan="6">{{ __('No records found') }}</td>
        </tr>
        @endforelse
    </table>
</div>
