@extends('layouts.dashboard')


@section('title', 'FieldAnnotation Details')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">FieldAnnotation Details</h1>
        <div class="flex gap-3">
            <a href="{{ route('fieldannotation.edit', $fieldannotation->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Edit
            </a>
            <form action="{{ route('fieldannotation.destroy', $fieldannotation->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Delete
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <dl class="divide-y divide-slate-200">
            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-slate-500">Field Key</dt>
                <dd class="mt-1 text-sm text-slate-900 sm:mt-0 sm:col-span-2">{{ $fieldannotation->field_key ?? 'N/A' }}</dd>
            </div>
            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-slate-500">Label</dt>
                <dd class="mt-1 text-sm text-slate-900 sm:mt-0 sm:col-span-2">{{ $fieldannotation->label ?? 'N/A' }}</dd>
            </div>
            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-slate-500">Description</dt>
                <dd class="mt-1 text-sm text-slate-900 sm:mt-0 sm:col-span-2">{{ $fieldannotation->description ?? 'N/A' }}</dd>
            </div>
            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-slate-500">Data Type</dt>
                <dd class="mt-1 text-sm text-slate-900 sm:mt-0 sm:col-span-2">{{ $fieldannotation->data_type ?? 'N/A' }}</dd>
            </div>
            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-slate-500">Is Required</dt>
                <dd class="mt-1 text-sm text-slate-900 sm:mt-0 sm:col-span-2">{{ $fieldannotation->is_required ?? 'N/A' }}</dd>
            </div>

        </dl>
    </div>

    <div class="mt-6">
        <a href="{{ route('fieldannotation.index') }}" class="text-indigo-600 hover:text-indigo-900">
            ← Back to list
        </a>
    </div>
</div>
@endsection