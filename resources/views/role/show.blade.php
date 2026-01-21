@extends('layouts.dashboard')

@section('title', 'Role Details - CRMTracker')

@section('content')
    <div class="p-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold text-slate-800">Role Details</h1>
                <div class="flex gap-2">
                    @if(auth()->user()->hasRole('admin'))
                        <a href="{{ route('web.roles.edit', $role->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                            <i class="fa-solid fa-edit mr-2"></i>Edit
                        </a>
                    @endif
                    <a href="{{ route('web.roles.index') }}" class="bg-slate-600 text-white px-4 py-2 rounded-lg hover:bg-slate-700 transition-colors">
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
                <h2 class="text-xl font-semibold text-slate-800 mb-4">Role Information</h2>
                
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-slate-500">ID</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $role->id }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Name</dt>
                        <dd class="mt-1 text-sm text-slate-900 font-medium">{{ $role->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Guard Name</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $role->guard_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Permissions Count</dt>
                        <dd class="mt-1 text-sm text-slate-900">
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $role->permissions()->count() }} permissions
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Users Count</dt>
                        <dd class="mt-1 text-sm text-slate-900">
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $role->users()->count() }} users
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Created At</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $role->created_at->format('Y-m-d H:i:s') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Updated At</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $role->updated_at->format('Y-m-d H:i:s') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Permissions Section -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 mb-6">
                <h2 class="text-xl font-semibold text-slate-800 mb-4">Permissions</h2>
                
                @if($role->permissions()->count() > 0)
                    <div class="grid grid-cols-1 gap-2">
                        @foreach($role->permissions as $permission)
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                <span class="text-sm text-slate-900">{{ $permission->name }}</span>
                                <span class="text-xs text-slate-500">{{ $permission->guard_name }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-slate-500 text-sm">No permissions assigned to this role.</p>
                @endif
            </div>

            <!-- Users Section -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <h2 class="text-xl font-semibold text-slate-800 mb-4">Users with this Role</h2>
                
                @if($role->users()->count() > 0)
                    <div class="grid grid-cols-1 gap-2">
                        @foreach($role->users as $user)
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
                    <p class="text-slate-500 text-sm">No users have this role assigned.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
