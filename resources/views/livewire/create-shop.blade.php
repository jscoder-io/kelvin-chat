<div class="p-4">
    <h1 class="text-xl font-bold mb-4 pb-2 border-b">{{ __('New Shop') }}</h1>
    <form wire:submit.prevent="save">
        <div class="flex items-center mb-4">
            <div class="w-1/3 font-semibold">{{ __('Name') }} <span class="text-red-400 align-sub">*</span></div>
            <div class="w-2/3 ">
                <input type="text" wire:model="form.name" class="block mt-1 p-2 w-full text-sm border border-gray-300 focus-visible:outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm" />
                @error('form.name') <span class="text-red-400">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="flex items-center mb-4">
            <div class="w-1/3 font-semibold">{{ __('Marketplace') }} <span class="text-red-400 align-sub">*</span></div>
            <div class="w-2/3 ">
                <select wire:model="form.marketplace" wire:change="$refresh" class="block mt-1 p-2 w-full text-sm bg-white border border-gray-300 focus-visible:outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm">
                    <option value="">{{ __('-- Select Marketplace --') }}</option>
                    <option value="carousell.sg">{{ __('carousell.sg') }}</option>
                </select>
                @error('form.marketplace') <span class="text-red-400">{{ $message }}</span> @enderror
            </div>
        </div>
        @if ($form->marketplace == 'carousell.sg')
        <div class="flex items-center mb-4">
            <div class="w-1/3 font-semibold">
                <div class="float-left pt-1">{{ __('Jwt Token') }} <span class="text-red-400 align-sub">*</span></div>
                <div class="float-right pt-1">
                    <a wire:click="$dispatch('openModal', { component: 'help', arguments: { section: 'jwt-token' }})" class="cursor-pointer focus-visible:outline-none" title="How to get Jwt Token">
                        <i class="bi bi-question-circle mr-2"></i>
                    </a>
                </div>
            </div>
            <div class="w-2/3 ">
                <input type="text" wire:model="form.token.jwt-token" class="block mt-1 p-2 w-full text-sm border border-gray-300 focus-visible:outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm" />
                @error('form.token.jwt-token') <span class="text-red-400">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="flex items-center mb-4">
            <div class="w-1/3 font-semibold">
                <div class="float-left pt-1">{{ __('Session Key') }} <span class="text-red-400 align-sub">*</span></div>
                <div class="float-right pt-1">
                    <a wire:click="$dispatch('openModal', { component: 'help', arguments: { section: 'session-key' }})" class="cursor-pointer focus-visible:outline-none" title="How to get Session Key">
                        <i class="bi bi-question-circle mr-2"></i>
                    </a>
                </div>
            </div>
            <div class="w-2/3 ">
                <input type="text" wire:model="form.token.session-key" class="block mt-1 p-2 w-full text-sm border border-gray-300 focus-visible:outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm" />
                @error('form.token.session-key') <span class="text-red-400">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="flex items-center mb-4">
            <div class="w-1/3 font-semibold">
                <div class="float-left pt-1">{{ __('Csrf Token') }} <span class="text-red-400 align-sub">*</span></div>
                <div class="float-right pt-1">
                    <a wire:click="$dispatch('openModal', { component: 'help', arguments: { section: 'csrf-token' }})" class="cursor-pointer focus-visible:outline-none" title="How to get Csrf Token">
                        <i class="bi bi-question-circle mr-2"></i>
                    </a>
                </div>
            </div>
            <div class="w-2/3 ">
                <input type="text" wire:model="form.token.csrf-token" class="block mt-1 p-2 w-full text-sm border border-gray-300 focus-visible:outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm" />
                @error('form.token.csrf-token') <span class="text-red-400">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="flex items-center mb-4">
            <div class="w-1/3 font-semibold">
                <div class="float-left pt-1">{{ __('_csrf') }} <span class="text-red-400 align-sub">*</span></div>
                <div class="float-right pt-1">
                    <a wire:click="$dispatch('openModal', { component: 'help', arguments: { section: '_csrf' }})" class="cursor-pointer focus-visible:outline-none" title="How to get _csrf">
                        <i class="bi bi-question-circle mr-2"></i>
                    </a>
                </div>
            </div>
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
