@php
    $isEdit = isset($license) && $license->exists;
@endphp

<div class="space-y-4">
        <div class="mb-4">
            <label for="competitor_id" class="block text-sm font-medium text-slate-700 mb-1">Competitor Id</label>
            <input type="text" name="competitor_id" id="competitor_id" value="{{ old('competitor_id', $license->competitor_id ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('competitor_id') border-red-500 @enderror">
            @error('competitor_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="market_id" class="block text-sm font-medium text-slate-700 mb-1">Market Id</label>
            <input type="text" name="market_id" id="market_id" value="{{ old('market_id', $license->market_id ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('market_id') border-red-500 @enderror">
            @error('market_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="license_number" class="block text-sm font-medium text-slate-700 mb-1">License Number</label>
            <input type="text" name="license_number" id="license_number" value="{{ old('license_number', $license->license_number ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('license_number') border-red-500 @enderror">
            @error('license_number')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="license_status" class="block text-sm font-medium text-slate-700 mb-1">License Status</label>
            <input type="text" name="license_status" id="license_status" value="{{ old('license_status', $license->license_status ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('license_status') border-red-500 @enderror">
            @error('license_status')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="license_owner" class="block text-sm font-medium text-slate-700 mb-1">License Owner</label>
            <input type="text" name="license_owner" id="license_owner" value="{{ old('license_owner', $license->license_owner ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('license_owner') border-red-500 @enderror">
            @error('license_owner')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>


    <div class="flex items-center justify-end gap-3 pt-4">
        <a href="{{ route('license.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
            Cancel
        </a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            {{ $isEdit ? 'Update' : 'Create' }} License
        </button>
    </div>
</div>