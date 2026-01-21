@extends('layouts.dashboard')

@section('title', 'Profile - CRMTracker')

@section('content')
    <div class="p-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-slate-800 mb-6">Profile Settings</h1>

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

            <!-- Profile Information -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 mb-6">
                <h2 class="text-xl font-semibold text-slate-800 mb-4">Profile Information</h2>
                
                <form method="POST" action="{{ route('web.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Update Profile
                    </button>
                </form>
            </div>

            <!-- Change Password -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 mb-6">
                <h2 class="text-xl font-semibold text-slate-800 mb-4">Change Password</h2>
                
                <form method="POST" action="{{ route('web.profile.password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="current_password" class="block text-sm font-medium text-slate-700 mb-2">Current Password</label>
                        <input type="password" id="current_password" name="current_password" 
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('current_password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-2">New Password</label>
                        <input type="password" id="password" name="password" 
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-2">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" 
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Update Password
                    </button>
                </form>
            </div>

            <!-- Two-Factor Authentication -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <h2 class="text-xl font-semibold text-slate-800 mb-4">Two-Factor Authentication</h2>
                
                <div class="mb-4">
                    <p class="text-sm text-slate-600 mb-2">
                        Status: 
                        <span class="font-semibold {{ $isTwoFactorEnabled ? 'text-green-600' : 'text-red-600' }}">
                            {{ $isTwoFactorEnabled ? 'Enabled' : 'Disabled' }}
                        </span>
                    </p>
                </div>

                @if(session('twoFactorSetup'))
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <p class="text-sm font-medium text-blue-800 mb-2">Scan this QR code with your authenticator app:</p>
                        <div class="mb-4">
                            <img src="{{ $setup->qrCodeUrl ?? '' }}" alt="2FA QR Code" class="mx-auto">
                        </div>
                        @php
                            $setup = session('twoFactorSetup');
                        @endphp
                        <p class="text-xs text-blue-600 mb-2">Secret Key: <code class="bg-blue-100 px-2 py-1 rounded">{{ $setup->secretKey ?? '' }}</code></p>
                        <p class="text-xs text-blue-600 mb-4">Recovery Codes (save these):</p>
                        <div class="bg-blue-100 p-3 rounded mb-4">
                            <code class="text-xs">{{ $setup->recoveryCodes ?? '' }}</code>
                        </div>
                        <p class="text-sm text-blue-700">After scanning, verify the code to enable 2FA.</p>
                    </div>
                @endif

                @if($isTwoFactorEnabled)
                    <form method="POST" action="{{ route('web.profile.2fa.disable') }}" onsubmit="return confirm('Are you sure you want to disable 2FA?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                            Disable 2FA
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('web.profile.2fa.setup') }}">
                        @csrf
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            Enable 2FA
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
