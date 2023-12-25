<div class="bg-white p-4 border border-gray-300 rounded-md shadow-md h-[475px]" wire:poll.10s>
    <div class="flex mb-4">
        <div class="w-1/3">
            <select wire:model="filters.shop" wire:change="$refresh" class="block mt-1 p-2 w-full text-sm bg-white border border-gray-300 focus-visible:outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm">
                <option value="0">{{ __('All Shops') }}</option>
                @foreach ($shops as $shop)
                <option value="{{ $shop->id }}">{{ __($shop->name) }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-2/3 pl-4">
            <input type="text" placeholder="Search" class="block mt-1 py-2 px-4 w-full text-sm border border-gray-300 focus-visible:outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm" />
        </div>
    </div>
    <div class="h-[385px] overflow-y-scroll">
        @forelse ($messages as $message)
        <div wire:click="chat({{ $message->id }})" class="flex mb-4 p-4 border-b border-gray-300 hover:bg-gray-100 hover:cursor-pointer">
            <div class="w-1/6">
                <img class="w-24 h-24 object-cover" src="{{ $message->profile_image }}" />
            </div>
            <div class="w-4/6">
                <div class="flex mb-4">
                    <div class="w-1/2 text-left">
                        <span class="inline-block text-green-700">{{ $message->username }}</span> 
                        @if ($message->unread_count > 0)
                        <span class="inline-block ml-2 text-white bg-blue-700 text-xs px-1.5 py-0.5 leading-none rounded">{{ $message->unread_count }}</span>
                        @endif
                    </div>
                    <div class="w-1/2 text-right">
                        <span class="font-bold">{{ $message->product_title }}</span>
                    </div>
                </div>
                <div class="flex mb-4">
                    <div class="w-1/2 text-left">
                        <div @class(['truncate', 'font-bold' => $message->unread_count > 0])>{{ $message->latest_message }}</div>
                    </div>
                    <div class="w-1/2 text-right">
                        <span class="text-sm text-sky-700 font-bold">{{ $message->shop->name }}</span>
                    </div>
                </div>
                <div class="flex">
                    <div class="w-1/2 text-left text-sm text-slate-400">
                        {{ $this->diffForHumansLatestCreatedAt($message->latest_created) }}
                    </div>
                    <div class="w-1/2 text-right text-sm font-bold">
                        {{ $message->shop->marketplace }}
                    </div>
                </div>
            </div>
            <div class="w-1/6">
                <img class="w-24 h-24 object-cover float-right" src="{{ $message->product_image }}" />
            </div>
        </div>
        @empty
        <div class="w-full px-0 py-4 text-center">{{ __('No message found') }}</div>
        @endforelse
    </div>
</div>
