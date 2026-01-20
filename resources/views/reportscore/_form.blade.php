@php
    $isEdit = isset($reportscore) && $reportscore->exists;
@endphp

<div class="space-y-4">
        <div class="mb-4">
            <label for="competitor_id" class="block text-sm font-medium text-slate-700 mb-1">Competitor Id</label>
            <input type="text" name="competitor_id" id="competitor_id" value="{{ old('competitor_id', $reportscore->competitor_id ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('competitor_id') border-red-500 @enderror">
            @error('competitor_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="report_type" class="block text-sm font-medium text-slate-700 mb-1">Report Type</label>
            <input type="text" name="report_type" id="report_type" value="{{ old('report_type', $reportscore->report_type ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('report_type') border-red-500 @enderror">
            @error('report_type')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="score" class="block text-sm font-medium text-slate-700 mb-1">Score</label>
            <input type="text" name="score" id="score" value="{{ old('score', $reportscore->score ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('score') border-red-500 @enderror">
            @error('score')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="calculated_at" class="block text-sm font-medium text-slate-700 mb-1">Calculated At</label>
            <input type="text" name="calculated_at" id="calculated_at" value="{{ old('calculated_at', $reportscore->calculated_at ?? '') }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('calculated_at') border-red-500 @enderror">
            @error('calculated_at')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>


    <div class="flex items-center justify-end gap-3 pt-4">
        <a href="{{ route('reportscore.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
            Cancel
        </a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            {{ $isEdit ? 'Update' : 'Create' }} ReportScore
        </button>
    </div>
</div>