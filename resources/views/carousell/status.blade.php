@if (! empty($message->data['order']['description']))
    @if ($message->data['state'] == 'A')
    <span class="text-cyan-400">{{ strtoupper($message->data['order']['description']) }}</span> 
    @else
    <span class="text-red-400">{{ strtoupper($message->data['order']['description']) }}</span> 
    @endif
@elseif ($message->data['state'] == 'A')
    <span class="text-cyan-400">{{ strtoupper('Accepted') }}</span> <span class="text-slate-700">{{ sprintf('Offered you %s%s', $message->data['currency_symbol'], $message->data['latest_price_formatted']) }}</span> 
@elseif ($message->data['state'] == 'D')
    <span class="text-cyan-400">{{ strtoupper('Declined') }}</span> <span class="text-slate-700">{{ sprintf('Offered you %s%s', $message->data['currency_symbol'], $message->data['latest_price_formatted']) }}</span> 
@elseif ($message->data['state'] == 'O' && $message->data['latest_price_formatted'])
    <span class="font-bold">{{ sprintf('Offered you %s%s', $message->data['currency_symbol'], $message->data['latest_price_formatted']) }}</span> 
@endif