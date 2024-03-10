<div class="p-4">
    <h1 class="text-xl font-bold mb-4 pb-2 border-b">{{ __('View Order') }}</h1>
    <div class="mb-4">
        @foreach ($message->order_total as $index => $row)
        <div @class(["flex flex-row justify-between py-2", "font-bold border-t border-slate-300" => ($index + 1) == count($message->order_total)])>
            <div class="text-left">{{ $row['label'] }}</div>
            <div class="text-right">{{ $row['amount'] }}</div>
        </div>
        @endforeach
    </div>
    <div class="flex justify-end mt-8 pt-4 border-t">
        <button type="button" wire:click="$dispatch('closeModal')" class="inline-flex items-center ml-4 px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus-visible:outline-none hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            {{ __('Close') }}
        </button>
    </div>
</div>