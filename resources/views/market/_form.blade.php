@php
    $isEdit = isset($market) && $market->exists;
@endphp

<div class="space-y-4">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $market->name ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="country" class="block text-sm font-medium text-slate-700 mb-1">Country</label>
            <input type="text" name="country" id="country" value="{{ old('country', $market->country ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('country') border-red-500 @enderror">
            @error('country')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="country_code" class="block text-sm font-medium text-slate-700 mb-1">Country Code</label>
            <input type="text" name="country_code" id="country_code" value="{{ old('country_code', $market->country_code ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('country_code') border-red-500 @enderror">
            @error('country_code')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="regulator" class="block text-sm font-medium text-slate-700 mb-1">Regulator</label>
            <input type="text" name="regulator" id="regulator" value="{{ old('regulator', $market->regulator ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('regulator') border-red-500 @enderror">
            @error('regulator')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="flag" class="block text-sm font-medium text-slate-700 mb-1">Flag</label>
            <input type="text" name="flag" id="flag" value="{{ old('flag', $market->flag ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('flag') border-red-500 @enderror">
            @error('flag')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="timezone" class="block text-sm font-medium text-slate-700 mb-1">Timezone</label>
            <input type="text" name="timezone" id="timezone" value="{{ old('timezone', $market->timezone ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('timezone') border-red-500 @enderror">
            @error('timezone')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="currency" class="block text-sm font-medium text-slate-700 mb-1">Currency</label>
            <input type="text" name="currency" id="currency" value="{{ old('currency', $market->currency ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('currency') border-red-500 @enderror">
            @error('currency')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" @if(old('is_active', $market->is_active ?? false)) checked @endif>
                <span class="ml-2 text-sm text-slate-700">Is Active</span>
            </label>
            @error('is_active')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="regulator_id" class="block text-sm font-medium text-slate-700 mb-1">Regulator Id</label>
            <input type="text" name="regulator_id" id="regulator_id" value="{{ old('regulator_id', $market->regulator_id ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('regulator_id') border-red-500 @enderror">
            @error('regulator_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>


    <div class="flex items-center justify-end gap-3 pt-4">
        <a href="{{ route('market.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
            Cancel
        </a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            {{ $isEdit ? 'Update' : 'Create' }} Market
        </button>
    </div>
</div>