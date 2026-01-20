@extends('layouts.dashboard')


@section('title', 'Regulations')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Regulations</h1>
        <a href="{{ route('regulation.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
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
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Market Id</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Description</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Effective Date</th>

                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($regulations->items() as $regulation)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900">{{ $regulation->id }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900">{{ $regulation->market_id }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900">{{ $regulation->name }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900">{{ $regulation->description }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900">{{ $regulation->effective_date }}</td>

                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('regulation.show', $regulation->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                            <a href="{{ route('regulation.edit', $regulation->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                            <form action="{{ route('regulation.destroy', $regulation->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count(Array) + 2 }}" class="px-4 py-8 text-center text-slate-500">
                            No regulations found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $regulations->links() }}
    </div>
</div>
@endsection