@extends('layouts.dashboard')

@section('title', 'Permission Details - CRMTracker')

@section('content')
    <div class="p-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold text-slate-800">Permission Details</h1>
                <div class="flex gap-2">
                    <a href="{{ route('web.permissions.edit', $permission->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                        <i class="fa-solid fa-edit mr-2"></i>Edit
                    </a>
                    <a href="{{ route('web.permissions.index') }}" class="bg-slate-600 text-white px-4 py-2 rounded-lg hover:bg-slate-700 transition-colors">
                        <i class="fa-solid fa-arrow-left mr-2"></i>Back
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 mb-6">
                <h2 class="text-xl font-semibold text-slate-800 mb-4">Permission Information</h2>
                
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-slate-500">ID</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $permission->id }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Name</dt>
                        <dd class="mt-1 text-sm text-slate-900 font-medium">{{ $permission->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Guard Name</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $permission->guard_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Roles Count</dt>
                        <dd class="mt-1 text-sm text-slate-900">
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $permission->roles()->count() }} roles
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Users Count</dt>
                        <dd class="mt-1 text-sm text-slate-900">
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $permission->users()->count() }} users
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Created At</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $permission->created_at->format('Y-m-d H:i:s') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Updated At</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $permission->updated_at->format('Y-m-d H:i:s') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Roles Section -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 mb-6">
                <h2 class="text-xl font-semibold text-slate-800 mb-4">Roles with this Permission</h2>
                
                @if($permission->roles()->count() > 0)
                    <div class="grid grid-cols-1 gap-2">
                        @foreach($permission->roles as $role)
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                <span class="text-sm text-slate-900">{{ $role->name }}</span>
                                <span class="text-xs text-slate-500">{{ $role->guard_name }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-slate-500 text-sm">No roles have this permission assigned.</p>
                @endif
            </div>

            <!-- Users Section -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <h2 class="text-xl font-semibold text-slate-800 mb-4">Users with this Permission</h2>
                
                @if($permission->users()->count() > 0)
                    <div class="grid grid-cols-1 gap-2">
                        @foreach($permission->users as $user)
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                <div>
                                    <span class="text-sm font-medium text-slate-900">{{ $user->name }}</span>
                                    <span class="text-xs text-slate-500 ml-2">{{ $user->email }}</span>
                                </div>
                                <a href="{{ route('web.users.show', $user->id) }}" class="text-blue-600 hover:text-blue-900 text-sm">
                                    <i class="fa-solid fa-eye"></i> View
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-slate-500 text-sm">No users have this permission assigned.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
