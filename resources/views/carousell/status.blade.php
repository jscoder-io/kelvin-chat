@if (! empty($message->data['order']['description']))
    @if ($message->data['state'] == 'A')
    <span class="text-cyan-400">{{ strtoupper($message->data['order']['description']) }}</span> 
    @else
    <span class="text-red-400">{{ strtoupper($message->data['order']['description']) }}</span> 
    @endif
@elseif (! empty($message->order_data) && $message->order_data['state'] == 'FULFILLMENT_ORDER_STATE_PROCESSING')
    <span class="text-cyan-400">{{ strtoupper('You Accept Order') }}</span> 
@elseif ($message->data['is_product_sold'])
    <span class="text-cyan-400">{{ strtoupper('Sold') }}</span> 
@elseif ($message->is_cancelled)
    <span class="text-red-400">{{ strtoupper('You Cancel Order') }}</span> 
@elseif ($message->data['state'] == 'A')
    <span class="text-cyan-400">{{ strtoupper('Accepted') }}</span> <span class="text-slate-700">{{ sprintf('Offered you %s%s', $message->data['currency_symbol'], $message->data['latest_price_formatted']) }}</span> 
@elseif ($message->data['state'] == 'D')
    <span class="text-cyan-400">{{ strtoupper('Declined') }}</span> <span class="text-slate-700">{{ sprintf('Offered you %s%s', $message->data['currency_symbol'], $message->data['latest_price_formatted']) }}</span> 
@elseif ($message->data['state'] == 'O' && $message->data['latest_price_formatted'])
    <span class="font-bold">{{ sprintf('Offered you %s%s', $message->data['currency_symbol'], $message->data['latest_price_formatted']) }}</span> 
@endif