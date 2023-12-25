<div class="flex bg-white p-4 border border-gray-300 rounded-md shadow-md h-[475px]" wire:poll.5s>
    <div class="w-2/3 flex flex-col">
        <div id="chat-box" class="h-full flex flex-col mb-0.5 p-2 border border-gray-300 rounded-md overflow-y-scroll">
        @foreach ($rows as $row)
            <div @class(['flex items-end mt-6', 'justify-start' => $row->user == 'buyer', 'justify-end' => $row->user == 'admin'])>
                @if ($row->user == 'buyer')
                <img class="w-10 h-10 object-cover" src="{{ $message->profile_image }}" />
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
                    {{ $row->message }}
                    @endif
                </div>
            </div>
            <div @class(['flex items-end mt-2', 'justify-start' => $row->user == 'buyer', 'justify-end' => $row->user == 'admin'])>
                @if ($row->user == 'buyer')
                <div class="w-10"></div>
                <div class="ml-4 text-[11px] text-start text-slate-400">
                    {{ $this->diffForHumansLatestCreatedAt($row->created_at) }}
                </div>
                @else
                <div class="ml-4 text-[11px] text-end text-slate-400">
                    {{ $this->diffForHumansLatestCreatedAt($row->created_at) }}
                </div>
                @endif
            </div>
        @endforeach
        </div>
        <div class="flex justify-between">
            <div class="grow">
                <input wire:model="text" type="text" class="block mt-1 p-2 w-full text-sm border border-gray-300 focus-visible:outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm" />
                @error('text') <span class="text-red-400">{{ $message }}</span> @enderror
            </div>
            <div class="pl-2">
                <div wire:click="$dispatch('openModal', { component: 'chat-box-upload', arguments: { message: {{ $message->id }} }})" class="inline-block">
                    <i class="bi bi-upload ml-2 cursor-pointer" title="Upload"></i>
                </div>
                <button wire:click="send" type="button" class="inline-flex items-center mt-1 ml-4 px-4 py-2.5 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus-visible:outline-none hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Send') }}
                </button>
            </div>
        </div>
    </div>
    <div class="w-1/3">
        <div class="ml-4">
            <table class="mb-8">
                <tr>
                    <td class="font-bold pr-4">Shop</td>
                    <td>{{ $message->shop->name }}</td>
                </tr>
                <tr>
                    <td class="font-bold pr-4">Marketplace</td>
                    <td>{{ $message->shop->marketplace }}</td>
                </tr>
                <tr>
                    <td class="font-bold pr-4">Product</td>
                    <td>{{ $message->product_title }}</td>
                </tr>
            </table>
            <div class="pt-8 border-t border-gray-300">
                <img class="w-24 h-24 object-cover" src="{{ $message->product_image }}" />
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
