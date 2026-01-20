@extends('layouts.dashboard')


@section('title', 'Alerts')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Alerts</h1>
        <a href="{{ route('alert.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            Create New
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Message</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Severity</th>

                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($alerts->items() as $alert)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900">{{ $alert->id }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900">{{ $alert->type }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900">{{ $alert->title }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900">{{ $alert->message }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900">{{ $alert->severity }}</td>

                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('alert.show', $alert->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                            <a href="{{ route('alert.edit', $alert->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                            <form action="{{ route('alert.destroy', $alert->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count(Array) + 2 }}" class="px-4 py-8 text-center text-slate-500">
                            No alerts found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $alerts->links() }}
    </div>
</div>
@endsection