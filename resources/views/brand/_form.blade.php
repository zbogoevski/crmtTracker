@php
    $isEdit = isset($brand) && $brand->exists;
@endphp

<div class="space-y-4">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $brand->name ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="website" class="block text-sm font-medium text-slate-700 mb-1">Website</label>
            <input type="url" name="website" id="website" value="{{ old('website', $brand->website ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('website') border-red-500 @enderror">
            @error('website')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="headquarters" class="block text-sm font-medium text-slate-700 mb-1">Headquarters</label>
            <input type="text" name="headquarters" id="headquarters" value="{{ old('headquarters', $brand->headquarters ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('headquarters') border-red-500 @enderror">
            @error('headquarters')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="parent_company" class="block text-sm font-medium text-slate-700 mb-1">Parent Company</label>
            <input type="text" name="parent_company" id="parent_company" value="{{ old('parent_company', $brand->parent_company ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('parent_company') border-red-500 @enderror">
            @error('parent_company')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="founded_year" class="block text-sm font-medium text-slate-700 mb-1">Founded Year</label>
            <input type="text" name="founded_year" id="founded_year" value="{{ old('founded_year', $brand->founded_year ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('founded_year') border-red-500 @enderror">
            @error('founded_year')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="company_id" class="block text-sm font-medium text-slate-700 mb-1">Company Id</label>
            <input type="text" name="company_id" id="company_id" value="{{ old('company_id', $brand->company_id ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('company_id') border-red-500 @enderror">
            @error('company_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>


    <div class="flex items-center justify-end gap-3 pt-4">
        <a href="{{ route('brand.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
            Cancel
        </a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            {{ $isEdit ? 'Update' : 'Create' }} Brand
        </button>
    </div>
</div>