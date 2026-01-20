@extends('layouts.dashboard')


@section('title', 'Markets')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Markets</h1>
        <a href="{{ route('market.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
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
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Country</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Country Code</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Regulator</th>

                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($markets->items() as $market)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900">{{ $market->id }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900">{{ $market->name }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900">{{ $market->country }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900">{{ $market->country_code }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900">{{ $market->regulator }}</td>

                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('market.show', $market->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                            <a href="{{ route('market.edit', $market->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                            <form action="{{ route('market.destroy', $market->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count(Array) + 2 }}" class="px-4 py-8 text-center text-slate-500">
                            No markets found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $markets->links() }}
    </div>
</div>
@endsection