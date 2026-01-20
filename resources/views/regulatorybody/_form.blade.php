@php
    $isEdit = isset($regulatorybody) && $regulatorybody->exists;
@endphp

<div class="space-y-4">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $regulatorybody->name ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="country" class="block text-sm font-medium text-slate-700 mb-1">Country</label>
            <input type="text" name="country" id="country" value="{{ old('country', $regulatorybody->country ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('country') border-red-500 @enderror">
            @error('country')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="jurisdiction" class="block text-sm font-medium text-slate-700 mb-1">Jurisdiction</label>
            <input type="text" name="jurisdiction" id="jurisdiction" value="{{ old('jurisdiction', $regulatorybody->jurisdiction ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('jurisdiction') border-red-500 @enderror">
            @error('jurisdiction')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="website" class="block text-sm font-medium text-slate-700 mb-1">Website</label>
            <input type="url" name="website" id="website" value="{{ old('website', $regulatorybody->website ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('website') border-red-500 @enderror">
            @error('website')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>


    <div class="flex items-center justify-end gap-3 pt-4">
        <a href="{{ route('regulatorybody.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
            Cancel
        </a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            {{ $isEdit ? 'Update' : 'Create' }} RegulatoryBody
        </button>
    </div>
</div>