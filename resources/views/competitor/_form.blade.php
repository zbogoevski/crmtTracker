@php
    $isEdit = isset($competitor) && $competitor->exists;
@endphp

<div class="space-y-4">
        <div class="mb-4">
            <label for="brand_id" class="block text-sm font-medium text-slate-700 mb-1">Brand Id</label>
            <input type="text" name="brand_id" id="brand_id" value="{{ old('brand_id', $competitor->brand_id ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('brand_id') border-red-500 @enderror">
            @error('brand_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="market_id" class="block text-sm font-medium text-slate-700 mb-1">Market Id</label>
            <input type="text" name="market_id" id="market_id" value="{{ old('market_id', $competitor->market_id ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('market_id') border-red-500 @enderror">
            @error('market_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $competitor->name ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="short_name" class="block text-sm font-medium text-slate-700 mb-1">Short Name</label>
            <input type="text" name="short_name" id="short_name" value="{{ old('short_name', $competitor->short_name ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('short_name') border-red-500 @enderror">
            @error('short_name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="tier" class="block text-sm font-medium text-slate-700 mb-1">Tier</label>
            <input type="text" name="tier" id="tier" value="{{ old('tier', $competitor->tier ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('tier') border-red-500 @enderror">
            @error('tier')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="color" class="block text-sm font-medium text-slate-700 mb-1">Color</label>
            <input type="text" name="color" id="color" value="{{ old('color', $competitor->color ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('color') border-red-500 @enderror">
            @error('color')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="color_class" class="block text-sm font-medium text-slate-700 mb-1">Color Class</label>
            <input type="text" name="color_class" id="color_class" value="{{ old('color_class', $competitor->color_class ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('color_class') border-red-500 @enderror">
            @error('color_class')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="flex items-center">
                <input type="checkbox" name="is_leader" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" @if(old('is_leader', $competitor->is_leader ?? false)) checked @endif>
                <span class="ml-2 text-sm text-slate-700">Is Leader</span>
            </label>
            @error('is_leader')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" @if(old('is_active', $competitor->is_active ?? false)) checked @endif>
                <span class="ml-2 text-sm text-slate-700">Is Active</span>
            </label>
            @error('is_active')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>


    <div class="flex items-center justify-end gap-3 pt-4">
        <a href="{{ route('competitor.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
            Cancel
        </a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            {{ $isEdit ? 'Update' : 'Create' }} Competitor
        </button>
    </div>
</div>