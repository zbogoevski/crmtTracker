@extends('layouts.app')

@section('title', 'User Details - CRMTracker')

@section('content')
    <div class="p-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold text-slate-800">User Details</h1>
                <div class="flex gap-2">
                    <a href="{{ route('users.edit', $user->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                        <i class="fa-solid fa-edit mr-2"></i>Edit
                    </a>
                    <a href="{{ route('users.index') }}" class="bg-slate-600 text-white px-4 py-2 rounded-lg hover:bg-slate-700 transition-colors">
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
                <h2 class="text-xl font-semibold text-slate-800 mb-4">User Information</h2>
                
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-slate-500">ID</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $user->id }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Name</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Email</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Role</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $user->roles()->first()?->name ?? 'viewer' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Email Verified</dt>
                        <dd class="mt-1 text-sm text-slate-900">
                            {{ $user->email_verified_at ? $user->email_verified_at->format('Y-m-d H:i:s') : 'Not verified' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">2FA Status</dt>
                        <dd class="mt-1 text-sm">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $isTwoFactorEnabled ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $isTwoFactorEnabled ? 'Enabled' : 'Disabled' }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Created At</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $user->created_at->format('Y-m-d H:i:s') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Updated At</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $user->updated_at->format('Y-m-d H:i:s') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- 2FA Management -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <h2 class="text-xl font-semibold text-slate-800 mb-4">Two-Factor Authentication</h2>
                
                <form method="POST" action="{{ route('users.2fa.toggle', $user->id) }}" onsubmit="return confirm('Are you sure you want to {{ $isTwoFactorEnabled ? 'disable' : 'enable' }} 2FA for this user?');">
                    @csrf
                    <input type="hidden" name="action" value="{{ $isTwoFactorEnabled ? 'disable' : 'enable' }}">
                    <button type="submit" class="bg-{{ $isTwoFactorEnabled ? 'red' : 'green' }}-600 text-white px-4 py-2 rounded-lg hover:bg-{{ $isTwoFactorEnabled ? 'red' : 'green' }}-700 transition-colors">
                        {{ $isTwoFactorEnabled ? 'Disable' : 'Enable' }} 2FA
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
