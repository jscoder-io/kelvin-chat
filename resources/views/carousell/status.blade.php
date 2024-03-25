@if (! empty($message->data['order']['description']))
    @if ($message->data['state'] == 'A')
    <span class="text-cyan-400">{{ strtoupper($message->data['order']['description']) }}</span> 
    @else
    <span class="text-red-400">{{ strtoupper($message->data['order']['description']) }}</span> 
    @endif

@elseif (! empty($message->order_status) && in_array($message->order_status, ['to_start', 'in_progress', 'completed', 'returns', 'cancelled']))
    @php $label = ['to_start'=>'Order Made','in_progress'=>'Order In Progress','completed'=>'Order Completed','returns'=>'Order Returned','cancelled'=>'Order Cancelled'] @endphp
    <span @class(['text-cyan-400', 'text-red-400' => $message->order_status == 'cancelled'])>{{ strtoupper($label[$message->order_status]) }}</span> 
@elseif ($message->data['state'] == 'A')
    <span class="text-cyan-400">{{ strtoupper('Accepted') }}</span> <span class="text-slate-700">{{ sprintf('Offered you %s%s', $message->data['currency_symbol'], $message->data['latest_price_formatted']) }}</span> 
@elseif ($message->data['state'] == 'D')
    <span class="text-cyan-400">{{ strtoupper('Declined') }}</span> <span class="text-slate-700">{{ sprintf('Offered you %s%s', $message->data['currency_symbol'], $message->data['latest_price_formatted']) }}</span> 
@elseif ($message->data['state'] == 'O' && $message->data['latest_price_formatted'])
    <span class="font-bold">{{ sprintf('Offered you %s%s', $message->data['currency_symbol'], $message->data['latest_price_formatted']) }}</span> 
@endif