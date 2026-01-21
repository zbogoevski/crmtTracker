@extends('layouts.dashboard')

@section('title', 'Roles Management - CRMTracker')

@section('content')
    <div class="p-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold text-slate-800">Roles Management</h1>
                @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('web.roles.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fa-solid fa-plus mr-2"></i>Create Role
                    </a>
                @endif
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Guard Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Permissions</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Users</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Created At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse($roles as $role)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">{{ $role->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 font-medium">{{ $role->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $role->guard_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $role->permissions()->count() }} permissions
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $role->users()->count() }} users
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $role->created_at->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('web.roles.show', $role->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fa-solid fa-eye"></i> View
                                    </a>
                                    @if(auth()->user()->hasRole('admin'))
                                        <a href="{{ route('web.roles.edit', $role->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            <i class="fa-solid fa-edit"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('web.roles.destroy', $role->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this role?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fa-solid fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-slate-500">No roles found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($roles->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200">
                        {{ $roles->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
