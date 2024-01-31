<div class="p-4">
    <h1 class="text-xl font-bold mb-4 pb-2 border-b">{{ __('Edit Token') }}</h1>
    <form wire:submit.prevent="save">
        <div class="flex items-center mb-4">
            <div class="w-1/3 font-semibold">{{ __('Name') }}</div>
            <div class="w-2/3 ">{{ $form->name }}</div>
        </div>
        <div class="flex items-center mb-4">
            <div class="w-1/3 font-semibold">{{ __('Marketplace') }}</div>
            <div class="w-2/3 ">{{ $form->marketplace }}</div>
        </div>
        @if ($form->marketplace == 'carousell.sg')
        <div @class(['flex items-center mb-4', 'p-2 pt-1 bg-red-100 border border-red-400 rounded' => ! $form->valid['jwt-token']])>
            <div class="w-1/3 font-semibold">{{ __('Jwt Token') }} <span class="text-red-400 align-sub">*</span></div>
            <div class="w-2/3 ">
                <input type="text" wire:model="form.token.jwt-token" class="block mt-1 p-2 w-full text-sm border border-gray-300 focus-visible:outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm" />
                @error('form.token.jwt-token') <span class="text-red-400">{{ $message }}</span> @enderror
            </div>
        </div>
        <div @class(['flex items-center mb-4', 'p-2 pt-1 bg-red-100 border border-red-400 rounded' => ! $form->valid['session-key']])>
            <div class="w-1/3 font-semibold">{{ __('Session Key') }} <span class="text-red-400 align-sub">*</span></div>
            <div class="w-2/3 ">
                <input type="text" wire:model="form.token.session-key" class="block mt-1 p-2 w-full text-sm border border-gray-300 focus-visible:outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm" />
                @error('form.token.session-key') <span class="text-red-400">{{ $message }}</span> @enderror
            </div>
        </div>
        <div @class(['flex items-center mb-4', 'p-2 pt-1 bg-red-100 border border-red-400 rounded' => ! $form->valid['csrf-token']])>
            <div class="w-1/3 font-semibold">{{ __('Csrf Token') }} <span class="text-red-400 align-sub">*</span></div>
            <div class="w-2/3 ">
                <input type="text" wire:model="form.token.csrf-token" class="block mt-1 p-2 w-full text-sm border border-gray-300 focus-visible:outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm" />
                @error('form.token.csrf-token') <span class="text-red-400">{{ $message }}</span> @enderror
            </div>
        </div>
        <div @class(['flex items-center mb-4', 'p-2 pt-1 bg-red-100 border border-red-400 rounded' => ! $form->valid['_csrf']])>
            <div class="w-1/3 font-semibold">{{ __('_csrf') }} <span class="text-red-400 align-sub">*</span></div>
            <div class="w-2/3 ">
                <input type="text" wire:model="form.token._csrf" class="block mt-1 p-2 w-full text-sm border border-gray-300 focus-visible:outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm" />
                @error('form.token._csrf') <span class="text-red-400">{{ $message }}</span> @enderror
            </div>
        </div>
        @endif
        <div class="flex justify-end mt-8 pt-4 border-t">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus-visible:outline-none hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Save') }}
            </button>
            <button type="button" wire:click="$dispatch('closeModal')" class="inline-flex items-center ml-4 px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus-visible:outline-none hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Cancel') }}
            </button>
        </div>
    </form>
</div>