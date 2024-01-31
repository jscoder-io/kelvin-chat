<div class="absolute top-6 left-1/4 right-1/4" wire:poll.5s>
    <div class="flex justify-center">
        @foreach ($tokens as $token)
        <div class="animate-bounce mx-1 px-2 border border-solid border-transparent rounded-md text-[#78261f] bg-[#fadbd8] border-[#f8ccc8]">
            Invalid {{ $token->label }}: 
            <a wire:click="$dispatch('openModal', { component: 'edit-token', arguments: { shop: {{ $token->shop->id }} }})" class="text-blue-700 cursor-pointer" title="Edit Token">{{ $token->shop->name }}</a>
        </div>
        @endforeach
    </div>
</div>