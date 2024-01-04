<div class="absolute top-6 left-1/4 right-1/4" wire:poll.5s>
    <div class="flex justify-center">
        @foreach ($tokens as $token)
        <div class="animate-bounce mx-1 px-2 border border-solid border-transparent rounded-md text-[#78261f] bg-[#fadbd8] border-[#f8ccc8]">
            Invalid {{ $token->label }}: <span class="text-blue-700">{{ $token->shop->name }}</span>
        </div>
        @endforeach
    </div>
</div>