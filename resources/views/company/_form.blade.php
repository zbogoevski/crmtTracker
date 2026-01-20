@php
    $isEdit = isset($company) && $company->exists;
@endphp

<div class="space-y-4">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $company->name ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="website" class="block text-sm font-medium text-slate-700 mb-1">Website</label>
            <input type="url" name="website" id="website" value="{{ old('website', $company->website ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('website') border-red-500 @enderror">
            @error('website')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="headquarters" class="block text-sm font-medium text-slate-700 mb-1">Headquarters</label>
            <input type="text" name="headquarters" id="headquarters" value="{{ old('headquarters', $company->headquarters ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('headquarters') border-red-500 @enderror">
            @error('headquarters')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="founded_year" class="block text-sm font-medium text-slate-700 mb-1">Founded Year</label>
            <input type="text" name="founded_year" id="founded_year" value="{{ old('founded_year', $company->founded_year ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('founded_year') border-red-500 @enderror">
            @error('founded_year')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="parent_company_id" class="block text-sm font-medium text-slate-700 mb-1">Parent Company Id</label>
            <input type="text" name="parent_company_id" id="parent_company_id" value="{{ old('parent_company_id', $company->parent_company_id ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('parent_company_id') border-red-500 @enderror">
            @error('parent_company_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>


    <div class="flex items-center justify-end gap-3 pt-4">
        <a href="{{ route('company.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
            Cancel
        </a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            {{ $isEdit ? 'Update' : 'Create' }} Company
        </button>
    </div>
</div>