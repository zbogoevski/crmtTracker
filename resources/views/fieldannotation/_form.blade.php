@php
    $isEdit = isset($fieldannotation) && $fieldannotation->exists;
@endphp

<div class="space-y-4">
        <div class="mb-4">
            <label for="field_key" class="block text-sm font-medium text-slate-700 mb-1">Field Key</label>
            <input type="text" name="field_key" id="field_key" value="{{ old('field_key', $fieldannotation->field_key ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('field_key') border-red-500 @enderror">
            @error('field_key')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="label" class="block text-sm font-medium text-slate-700 mb-1">Label</label>
            <input type="text" name="label" id="label" value="{{ old('label', $fieldannotation->label ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('label') border-red-500 @enderror">
            @error('label')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Description</label>
            <textarea name="description" id="description" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-500 @enderror">{{ old('description', $fieldannotation->description ?? '') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="data_type" class="block text-sm font-medium text-slate-700 mb-1">Data Type</label>
            <input type="text" name="data_type" id="data_type" value="{{ old('data_type', $fieldannotation->data_type ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('data_type') border-red-500 @enderror">
            @error('data_type')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="flex items-center">
                <input type="checkbox" name="is_required" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" @if(old('is_required', $fieldannotation->is_required ?? false)) checked @endif>
                <span class="ml-2 text-sm text-slate-700">Is Required</span>
            </label>
            @error('is_required')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>


    <div class="flex items-center justify-end gap-3 pt-4">
        <a href="{{ route('fieldannotation.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
            Cancel
        </a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            {{ $isEdit ? 'Update' : 'Create' }} FieldAnnotation
        </button>
    </div>
</div>