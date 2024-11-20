<div class="bg-white p-4 border border-gray-300 rounded-md shadow-md">
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
            <select wire:model="filters.status" wire:change="$refresh" class="block mt-1 p-2 w-full text-sm bg-white border border-gray-300 focus-visible:outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm">
                <option value="">{{ __('All Statuses') }}</option>
                <option value="to_start">{{ __('Newly Made') }}</option>
                <option value="in_progress">{{ __('In Progress') }}</option>
                <option value="completed">{{ __('Completed') }}</option>
                <option value="returns">{{ __('Returned') }}</option>
                <option value="cancelled">{{ __('Cancelled') }}</option>
            </select>
        </div>
        <div class="w-2/4 pl-4">
            <input wire:model.live="filters.search" type="text" placeholder="Search" class="block mt-1 py-2 px-4 w-full text-sm border border-gray-300 focus-visible:outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm" />
        </div>
    </div>
    <div class="flex flex-row mb-2">
        <div class="mr-4 font-bold">Column: </div>
        <div class="mr-4"><input class="mr-1" type="checkbox" wire:change="$refresh" wire:model="columns.status"> Status</div>
        <div class="mr-4"><input class="mr-1" type="checkbox" wire:change="$refresh" wire:model="columns.date"> Date</div>
        <div class="mr-4"><input class="mr-1" type="checkbox" wire:change="$refresh" wire:model="columns.shop"> Shop</div>
        <div class="mr-4"><input class="mr-1" type="checkbox" wire:change="$refresh" wire:model="columns.product"> Product</div>
        <div class="mr-4"><input class="mr-1" type="checkbox" wire:change="$refresh" wire:model="columns.customer_id"> Customer ID</div>
        <div class="mr-4"><input class="mr-1" type="checkbox" wire:change="$refresh" wire:model="columns.customer_name"> Customer Name</div>
    </div>
    <div class="flex flex-row mb-2">
        <div class="mr-4 font-bold">Column: </div>
        <div class="mr-4"><input class="mr-1" type="checkbox" wire:change="$refresh" wire:model="columns.contact"> Contact</div>
        <div class="mr-4"><input class="mr-1" type="checkbox" wire:change="$refresh" wire:model="columns.subtotal"> Subtotal</div>
        <div class="mr-4"><input class="mr-1" type="checkbox" wire:change="$refresh" wire:model="columns.delivery_fee"> Delivery Fee</div>
        <div class="mr-4"><input class="mr-1" type="checkbox" wire:change="$refresh" wire:model="columns.platform_fee"> Platform Fee</div>
        <div class="mr-4"><input class="mr-1" type="checkbox" wire:change="$refresh" wire:model="columns.total"> Total</div>        
    </div>
    <div class="flex flex-row mb-2">
        <div class="basis-1/4">&nbsp;</div>
        <div class="basis-1/4 text-right mr-2">
            @if ($offset >= 10)
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
            @if ($orders->count() == 10)
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
    <div style="overflow-x:scroll;">
        <table class="w-full" style="text-wrap:nowrap;">
            <tr class="font-bold border-b-2">
                <td class="pb-2 px-2">{{ __('#') }}</td>
                <td class="pb-2 px-2">{{ __('Order ID') }}</td>
                @if($columns['status'])
                <td class="pb-2 px-2">{{ __('Status') }}</td>
                @endif
                @if($columns['date'])
                <td class="pb-2 px-2">{{ __('Date') }}</td>
                @endif
                @if($columns['shop'])
                <td class="pb-2 px-2">{{ __('Shop') }}</td>
                @endif
                @if($columns['product'])
                <td class="pb-2 px-2">{{ __('Product') }}</td>
                @endif
                @if($columns['customer_id'])
                <td class="pb-2 px-2">{{ __('Customer ID') }}</td>
                @endif
                @if($columns['customer_name'])
                <td class="pb-2 px-2">{{ __('Customer Name') }}</td>
                @endif
                @if($columns['contact'])
                <td class="pb-2 px-2">{{ __('Contact') }}</td>
                @endif
                @if($columns['subtotal'])
                <td class="pb-2 px-2">{{ __('Subtotal') }}</td>
                @endif
                @if($columns['delivery_fee'])
                <td class="pb-2 px-2">{{ __('Delivery Fee') }}</td>
                @endif
                @if($columns['platform_fee'])
                <td class="pb-2 px-2">{{ __('Platform Fee') }}</td>
                @endif
                @if($columns['total'])
                <td class="pb-2 px-2">{{ __('Total') }}</td>
                @endif
                <!--<td class="w-1/12 pb-2">{{ __('Action') }}</td>-->
            </tr>
            @php $n = $offset + 1 @endphp
            @forelse ($orders as $order)
            <tr class="border-t align-top">
                <td class="py-2 px-2">{{ $n }}</td>
                <td class="py-2 px-2">{{ $order->identifier }}</td>
                @if($columns['status'])
                <td class="py-2 px-2">
                    @switch ($order->tab)
                        @case('to_start')
                            <span class="text-green-400">Newly Made</span>
                            @break
                        @case('in_progress')
                            <span class="text-yellow-400">In Progress</span>
                            @break
                        @case('completed')
                            <span class="text-blue-400">Completed</span>
                            @break
                        @case('returns')
                            <span class="text-red-400">Returned</span>
                            @break
                        @case('cancelled')
                            <span class="text-red-400">Cancelled</span>
                            @break
                    @endswitch
                </td>
                @endif
                @if($columns['date'])
                <td class="py-2 px-2">{{ $order->creation_date }}</td>
                @endif
                @if($columns['shop'])
                <td class="py-2 px-2">{{ $order->shop->name }}</td>
                @endif
                @if($columns['product'])
                <td class="py-2 px-2" title="{{ $order->message->product_title }}">{{ Str::limit($order->message->product_title, 50) }}</td>
                @endif
                @if($columns['customer_id'])
                <td class="py-2 px-2">{{ $order->message->username }}</td>
                @endif
                @if($columns['customer_name'])
                <td class="py-2 px-2">{{ $order->customer }}</td>
                @endif
                @if($columns['contact'])
                <td class="py-2 px-2">{{ $order->contact }}</td>
                @endif
                @if($columns['subtotal'])
                <td class="py-2 px-2">{{ $order->subtotal }}</td>
                @endif
                @if($columns['delivery_fee'])
                <td class="py-2 px-2">{{ $order->delivery }}</td>
                @endif
                @if($columns['platform_fee'])
                <td class="py-2 px-2">{{ $order->platform_fee }}</td>
                @endif
                @if($columns['total'])
                <td class="py-2 px-2">{{ $order->total }}</td>
                @endif
            </tr>
            @php $n++ @endphp
            @empty
            <tr class="border-t">
                <td class="py-2" colspan="13">{{ __('No records found') }}</td>
            </tr>
            @endforelse
        </table>
    </div>
</div>