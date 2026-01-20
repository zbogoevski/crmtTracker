@extends('layouts.dashboard')


@section('title', 'Brand Details')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Brand Details</h1>
        <div class="flex gap-3">
            <a href="{{ route('brand.edit', $brand->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Edit
            </a>
            <form action="{{ route('brand.destroy', $brand->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
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
                <dt class="text-sm font-medium text-slate-500">Id</dt>
                <dd class="mt-1 text-sm text-slate-900 sm:mt-0 sm:col-span-2">{{ $brand->id ?? 'N/A' }}</dd>
            </div>
            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-slate-500">Name</dt>
                <dd class="mt-1 text-sm text-slate-900 sm:mt-0 sm:col-span-2">{{ $brand->name ?? 'N/A' }}</dd>
            </div>
            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-slate-500">Website</dt>
                <dd class="mt-1 text-sm text-slate-900 sm:mt-0 sm:col-span-2">{{ $brand->website ?? 'N/A' }}</dd>
            </div>
            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-slate-500">Headquarters</dt>
                <dd class="mt-1 text-sm text-slate-900 sm:mt-0 sm:col-span-2">{{ $brand->headquarters ?? 'N/A' }}</dd>
            </div>
            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-slate-500">Parent Company</dt>
                <dd class="mt-1 text-sm text-slate-900 sm:mt-0 sm:col-span-2">{{ $brand->parent_company ?? 'N/A' }}</dd>
            </div>
            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-slate-500">Founded Year</dt>
                <dd class="mt-1 text-sm text-slate-900 sm:mt-0 sm:col-span-2">{{ $brand->founded_year ?? 'N/A' }}</dd>
            </div>
            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-slate-500">Company Id</dt>
                <dd class="mt-1 text-sm text-slate-900 sm:mt-0 sm:col-span-2">{{ $brand->company_id ?? 'N/A' }}</dd>
            </div>

        </dl>
    </div>

    <div class="mt-6">
        <a href="{{ route('brand.index') }}" class="text-indigo-600 hover:text-indigo-900">
            ← Back to list
        </a>
    </div>
</div>
@endsection