@php
    $isEdit = isset($alert) && $alert->exists;
@endphp

<div class="space-y-4">
        <div class="mb-4">
            <label for="type" class="block text-sm font-medium text-slate-700 mb-1">Type</label>
            <input type="text" name="type" id="type" value="{{ old('type', $alert->type ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('type') border-red-500 @enderror">
            @error('type')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $alert->title ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('title') border-red-500 @enderror">
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="message" class="block text-sm font-medium text-slate-700 mb-1">Message</label>
            <textarea name="message" id="message" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('message') border-red-500 @enderror">{{ old('message', $alert->message ?? '') }}</textarea>
            @error('message')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="severity" class="block text-sm font-medium text-slate-700 mb-1">Severity</label>
            <input type="text" name="severity" id="severity" value="{{ old('severity', $alert->severity ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('severity') border-red-500 @enderror">
            @error('severity')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="flex items-center">
                <input type="checkbox" name="is_read" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" @if(old('is_read', $alert->is_read ?? false)) checked @endif>
                <span class="ml-2 text-sm text-slate-700">Is Read</span>
            </label>
            @error('is_read')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>


    <div class="flex items-center justify-end gap-3 pt-4">
        <a href="{{ route('alert.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
            Cancel
        </a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            {{ $isEdit ? 'Update' : 'Create' }} Alert
        </button>
    </div>
</div>