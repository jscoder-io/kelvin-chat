<div class="p-4">
    <h1 class="text-xl font-bold mb-4 pb-2 border-b">{{ __('New User') }}</h1>
    <form wire:submit.prevent="save">
        <div class="flex items-center mb-4">
            <div class="w-1/3 font-semibold">{{ __('Name') }} <span class="text-red-400 align-sub">*</span></div>
            <div class="w-2/3 ">
                <input type="text" wire:model="form.name" class="block mt-1 p-2 w-full text-sm border border-gray-300 focus-visible:outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm" />
                @error('form.name') <span class="text-red-400">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="flex items-center mb-4">
            <div class="w-1/3 font-semibold">{{ __('Email') }} <span class="text-red-400 align-sub">*</span></div>
            <div class="w-2/3 ">
                <input type="text" wire:model="form.email" class="block mt-1 p-2 w-full text-sm border border-gray-300 focus-visible:outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm" />
                @error('form.email') <span class="text-red-400">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="flex items-center mb-4">
            <div class="w-1/3 font-semibold">{{ __('Role') }} <span class="text-red-400 align-sub">*</span></div>
            <div class="w-2/3 ">
                <select wire:model="form.role" wire:change="$refresh" class="block mt-1 p-2 w-full text-sm bg-white border border-gray-300 focus-visible:outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm">
                    <option value="">{{ __('-- Select Role --') }}</option>
                    <option value="1">{{ __('Admin') }}</option>
                    <option value="2">{{ __('Staff') }}</option>
                </select>
                @error('form.role') <span class="text-red-400">{{ $message }}</span> @enderror
            </div>
        </div>
        @if ($form->role == '2')
        <div class="flex items-center mb-4">
            <div class="w-1/3 font-semibold">{{ __('Shop') }} <span class="text-red-400 align-sub">*</span></div>
            <div class="w-2/3 ">
                <select wire:model="form.shop" class="block mt-1 p-2 w-full text-sm bg-white border border-gray-300 focus-visible:outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm" multiple>
                    @foreach (\App\Models\Shop::latest()->get() as $shop)
                    <option value="{{ $shop->id }}" wire:mousedown="$dispatch('multiSelectWithoutCtrl', event);$wire.selectOption({{ $shop->id }})">{{ __($shop->name) }}</option>
                    @endforeach
                </select>
                @error('form.shop') <span class="text-red-400">{{ $message }}</span> @enderror
            </div>
        </div>
        @endif
        <div class="flex items-center mb-4">
            <div class="w-1/3 font-semibold">{{ __('Preset') }} <span class="text-red-400 align-sub">*</span></div>
            <div class="w-2/3 ">
                <input type="text" wire:model="form.preset" class="block mt-1 p-2 w-full text-sm border border-gray-300 focus-visible:outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm" />
                @error('form.preset') <span class="text-red-400">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="flex items-center mb-4">
            <div class="w-1/3 font-semibold">{{ __('Password') }} <span class="text-red-400 align-sub">*</span></div>
            <div class="w-2/3 ">
                <input type="password" wire:model="form.password" class="block mt-1 p-2 w-full text-sm border border-gray-300 focus-visible:outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm" />
                @error('form.password') <span class="text-red-400">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="flex items-center mb-4">
            <div class="w-1/3 font-semibold">{{ __('Confirmation') }} <span class="text-red-400 align-sub">*</span></div>
            <div class="w-2/3 ">
                <input type="password" wire:model="form.password_confirmation" class="block mt-1 p-2 w-full text-sm border border-gray-300 focus-visible:outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm" />
                @error('form.password_confirmation') <span class="text-red-400">{{ $message }}</span> @enderror
            </div>
        </div>
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
