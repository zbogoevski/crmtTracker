@php
    $isEdit = isset($offer) && $offer->exists;
@endphp

<div class="space-y-4">
        <div class="mb-4">
            <label for="competitor_id" class="block text-sm font-medium text-slate-700 mb-1">Competitor Id</label>
            <input type="text" name="competitor_id" id="competitor_id" value="{{ old('competitor_id', $offer->competitor_id ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('competitor_id') border-red-500 @enderror">
            @error('competitor_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="offer_type" class="block text-sm font-medium text-slate-700 mb-1">Offer Type</label>
            <input type="text" name="offer_type" id="offer_type" value="{{ old('offer_type', $offer->offer_type ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('offer_type') border-red-500 @enderror">
            @error('offer_type')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="offer_value" class="block text-sm font-medium text-slate-700 mb-1">Offer Value</label>
            <input type="text" name="offer_value" id="offer_value" value="{{ old('offer_value', $offer->offer_value ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('offer_value') border-red-500 @enderror">
            @error('offer_value')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="expiry_date" class="block text-sm font-medium text-slate-700 mb-1">Expiry Date</label>
            <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date', $offer->expiry_date ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('expiry_date') border-red-500 @enderror">
            @error('expiry_date')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>


    <div class="flex items-center justify-end gap-3 pt-4">
        <a href="{{ route('offer.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
            Cancel
        </a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            {{ $isEdit ? 'Update' : 'Create' }} Offer
        </button>
    </div>
</div>