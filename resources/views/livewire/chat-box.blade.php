<div class="flex bg-white p-4 border border-gray-300 rounded-md shadow-md" style="height:calc(100vh - 8rem);" wire:poll.5s>
    <div class="w-2/3 flex flex-col">
        <div id="chat-box" class="h-full flex flex-col mb-0.5 p-2 border border-gray-300 rounded-md overflow-y-scroll">
        @foreach ($rows as $row)
            @if ($row->type == 'ADMM' && $row->custom_type == 'SYSTEM_MESSAGE')
                @if (! in_array($message->buyer_id, $row->data['visibility']['user_ids']))
                <div class="flex items-end mt-6 justify-center">
                    <div class="max-w-[15rem] ml-4 px-3 py-2 bg-slate-100 border border-gray-300 rounded-md text-sm">
                    {{ $row->message }}
                    </div>
                </div>
                <div class="flex items-end mt-2 justify-center">
                    <div class="text-[11px] text-center text-slate-400">
                    {{ $this->diffForHumansLatestCreatedAt($row->created_at) }}
                    </div>
                </div>
                @endif
            @else
            <div @class(['flex items-end mt-6', 'justify-start' => $row->user == 'buyer', 'justify-end' => $row->user == 'admin'])>
                @if ($row->user == 'buyer')
                <img class="w-10 h-10 object-cover" src="{{ $message->profile_image_copy ?? $message->profile_image }}" />
                @endif
                <div class="max-w-[15rem] ml-4 px-3 py-2 bg-slate-100 border border-gray-300 rounded-md text-sm">
                    @if ($row->type == 'FILE' && $row->custom_type == 'IMAGE')
                    <img class="w-[14rem] h-[14rem] object-cover" src="{{ $row->file['url'] }}" />
                    @elseif ($row->type == 'MESG' && $row->custom_type == 'DELETED')
                    <i>You deleted this message</i>
                    @elseif ($row->type == 'MESG' && $row->custom_type == 'MAKE_OFFER')
                    {{ $row->message }} <br /> <span class="font-bold">{{ $row->data['currency_symbol'] }}{{ round($row->data['offer_amount']) }}</span>
                    @elseif ($row->type == 'MESG' && $row->custom_type == 'ACCEPT_OFFER')
                    {{ $row->message }} <br /> <span class="font-bold">{{ $row->data['currency_symbol'] }}{{ round($row->data['offer_amount']) }}</span>
                    @elseif ($row->type == 'MESG' && $row->custom_type == 'DECLINE_OFFER')
                    {{ $row->message }} <br /> <span class="font-bold">{{ $row->data['currency_symbol'] }}{{ round($row->data['offer_amount']) }}</span>
                    @else
                        @php $arrMsg = explode("\n", $row->message) @endphp
                        {!! implode("<br />", $arrMsg) !!}
                    @endif
                </div>
            </div>
            @endif
            <div @class(['flex items-end mt-2', 'justify-start' => $row->user == 'buyer', 'justify-end' => $row->user == 'admin'])>
                @if ($row->user == 'buyer')
                <div class="w-10"></div>
                <div class="ml-4 text-[11px] text-start text-slate-400">
                    {{ $this->diffForHumansLatestCreatedAt($row->created_at) }}
                </div>
                @endif
                @if ($row->user == 'admin')
                <div class="ml-4 text-[11px] text-end text-slate-400">
                    {{ $this->diffForHumansLatestCreatedAt($row->created_at) }}
                </div>
                @endif
            </div>
        @endforeach
        @if ($chat = $this->makeOfferActions())
            <div class="flex items-end mt-6 justify-end">
                <div class="max-w-[15rem] ml-4 px-3 py-2 bg-slate-100 border border-gray-300 rounded-md text-sm">
                    <div class="text-right">{{ __('Offered to you') }} <span class="font-bold">{{ $chat->data['currency_symbol'] . round($chat->data['offer_amount']) }}</span></div>
                    <div class="mt-4 flex justify-end">
                        <button wire:click="accept" type="button" class="px-4 py-2.5 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus-visible:outline-none hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Accept') }}
                        </button>
                        <button wire:click="decline" type="button" class="ml-4 px-4 py-2.5 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus-visible:outline-none hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Decline') }}
                        </button>
                    </div>
                </div>
            </div>
        @endif
        @if ($this->orderActions())
            <div class="flex items-end mt-6 justify-end">
                <div class="max-w-[15rem] ml-4 px-3 py-2 bg-slate-100 border border-gray-300 rounded-md text-sm">
                    <div class="text-right">{{ __('Do you want to Accept or Cancel this order?') }}</div>
                    <div class="mt-4 flex justify-end">
                        <button wire:click="acceptOrder" type="button" class="px-4 py-2.5 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus-visible:outline-none hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Accept') }}
                        </button>
                        <button wire:click="cancelOrder" type="button" class="ml-4 px-4 py-2.5 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus-visible:outline-none hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        @endif
        </div>
        <div class="flex justify-between">
            <div class="grow">
                <!--<input wire:model="text" wire:keydown.enter="send" @keydown.enter="$el.value = ''" type="text" class="block mt-1 p-2 w-full text-sm border border-gray-300 focus-visible:outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm" />-->
                <textarea x-ref="text" wire:model="text" wire:keydown.enter.prevent="send" @keydown.enter.prevent="$el.value = ''" rows="3" class="block mt-1 p-2 w-full text-sm border border-gray-300 focus-visible:outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm"></textarea>
                @error('text') <span class="text-red-400">{{ $message }}</span> @enderror
            </div>
            <div class="pl-2">
                <div wire:click="$dispatch('openModal', { component: 'chat-box-upload', arguments: { message: {{ $message->id }} }})" class="inline-block">
                    <i class="bi bi-upload ml-2 cursor-pointer" title="Upload"></i>
                </div>
                <button wire:click="send" @click="$refs.text.value = ''" type="button" class="inline-flex items-center mt-1 ml-4 px-4 py-2.5 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus-visible:outline-none hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Send') }}
                </button>
            </div>
        </div>
    </div>
    <div class="w-1/3 px-4 overflow-y-scroll">
        <div class="pb-4 overflow-x-scroll">
            <div class="flex min-w-max">
                <div class="pr-4">
                    <img class="w-24 h-24 object-cover" src="{{ $message->product_image_copy ?? $message->product_image }}" />
                </div>
                <table class="">
                    <tr>
                        <td class="font-bold pr-4 align-top">Marketplace</td>
                        <td>{{ $message->shop->marketplace }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold pr-4 align-top">Shop</td>
                        <td>{{ $message->shop->name }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold pr-4 align-top">Product</td>
                        <td>{{ $message->product_title }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold pr-4 align-top">Price</td>
                        <td>{{ $message->price_formatted }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold pr-4 align-top">Url</td>
                        <td>
                            @if ($message->product_url)
                            <a href="{{ $message->product_url }}" title="{{ $message->product_url }}" target="_blank">
                                <span class="text-blue-700">Go to product page <i class="bi bi-link-45deg"></i></span>
                            </a>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="mt-2">
            <div class="flex flex-col">
                @foreach ($templates as $template)
                <div class="my-1 p-2 bg-slate-100 hover:bg-slate-200 border border-gray-300 rounded">
                    <div>{{ $template->message }}</div>
                    <button wire:click="sendTemplate({{ $template->id }})" type="button" class="mt-1 p-1.5 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus-visible:outline-none hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Send') }}
                    </button>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function scrollTop() {
            var element = document.querySelector('#chat-box');
            element.scrollTop = element.scrollHeight;
        }

        scrollTop()

        document.addEventListener("DOMContentLoaded", (event) => {
            Livewire.hook('morph.added', ({ el }) => {
                scrollTop()
            })
        });
    </script>
</div>
