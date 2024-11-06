<div class="bg-white p-4 border border-gray-300 rounded-md shadow-md" style="height:calc(100vh - 8rem);" wire:poll.10s>
    <div class="flex mb-4">
        <div class="w-1/4">
            <select wire:model="filters.shop" wire:change="$refresh" class="block mt-1 p-2 w-full text-sm bg-white border border-gray-300 focus-visible:outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm">
                <option value="0">{{ __('All Shops') }}</option>
                @foreach ($shops as $shop)
                <option value="{{ $shop->id }}">{{ __($shop->name) }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-1/4 pl-4">
            <select wire:model="filters.type" wire:change="$refresh" class="block mt-1 p-2 w-full text-sm bg-white border border-gray-300 focus-visible:outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm">
                <option value="0">{{ __('All Messages') }}</option>
                <option value="1">{{ __('Unread') }}</option>
                <option value="2">{{ __('Archived') }}</option>
            </select>
        </div>
        <div class="w-2/4 pl-4">
            <input wire:model.live="filters.search" type="text" placeholder="Search" class="block mt-1 py-2 px-4 w-full text-sm border border-gray-300 focus-visible:outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm" />
        </div>
    </div>
    <div class="flex flex-row mb-2">
        <div class="basis-1/4 text-left">
            <button wire:click="archive" type="button" class="inline-flex items-center px-2 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus-visible:outline-none hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Archive') }}
            </button>
        </div>
        <div class="basis-1/4 text-right mr-2">
            @if ($offset >= 30)
            <a href="#" wire:click.prevent="prev" class="text-xl text-teal-400" title="Previous">
                <i class="bi bi-arrow-left-square"></i>
            </a>
            @else
            <div class="text-xl" title="Previous">
                <i class="bi bi-arrow-left-square"></i>
            </div>
            @endif
        </div>
        <div class="basis-1/4 text-left ml-2">
            @if ($messages->count() == 30)
            <a href="#" wire:click.prevent="next" class="text-xl text-teal-400" title="Next">
                <i class="bi bi-arrow-right-square"></i>
            </a>
            @else
            <div class="text-xl" title="Next">
                <i class="bi bi-arrow-right-square"></i>
            </div>
            @endif
        </div>
        <div class="basis-1/4">&nbsp;</div>
    </div>
    <div class="overflow-y-scroll" style="height:calc(100vh - 16rem);">
        @forelse ($messages as $message)
        <div wire:click="chat({{ $message->id }})" class="flex justify-between mb-4 p-4 pl-0 border-b border-gray-300 hover:bg-gray-100 hover:cursor-pointer">
            <div class="w-3/4 pr-4 text-left">
                <div class="flex">
                    <input type="checkbox" wire:model="selected" @click.stop="let x = false;" value="{{ $message->id }}" class="mr-2">
                    <img class="w-14 h-14 object-cover" src="{{ $message->product_image_copy ?? $message->product_image }}" />
                    <div class="w-3/4 ml-6">
                        <div class="mb-2">
                            <span class="inline-block text-green-700">{{ $message->username }}</span> 
                            <div onclick="copyToClipboard('{{ $message->username }}', 'copied-{{ $message->id }}', event)" title="Copy username" style="position:relative;display:inline-block;margin:0px 8px;">
                                <div class="copied-{{ $message->id }}" style="display:none;position:absolute;top:-20px;left:0px;padding:0 4px;background-color:blue;font-size:12px;font-weight:700;color:#FFFFFF;border-radius:4px;">Copied</div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-copy" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1z"/>
                                </svg>
                            </div>
                            @if ($message->unread_count > 0)
                            <span class="inline-block ml-2 text-white bg-blue-700 text-xs px-1.5 py-0.5 leading-none rounded">{{ $message->unread_count }}</span>
                            @endif
                        </div>
                        <div class="truncate">
                            @if ($message->shop->marketplace == 'carousell.sg')
                                @include('carousell.status', ['message' => $message])
                            @endif
                            <span @class(['font-bold' => $message->unread_count > 0])>{{ $message->latest_message }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-1/4 pl-4 text-right">
                <div class="text-sm text-sky-700 font-bold mb-4">
                    {{ $message->shop->name }}
                </div>
                <div class="text-sm text-slate-400">
                    {{ $this->diffForHumansLatestCreatedAt($message->latest_created) }}
                </div>
            </div>
        </div>
        @empty
        <div class="w-full px-0 py-4 text-center">{{ __('No message found') }}</div>
        @endforelse
    </div>
</div>
