<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CRMTracker') | CRMTracker</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Chart.js (if needed) -->
    @stack('scripts')
    
    @stack('styles')
</head>

<body class="bg-[#f3f4f6] font-sans text-slate-800">
    <div id="root-container" class="flex min-h-screen">
        <!-- Sidebar -->
        <x-sidebar />

        <!-- Main Content -->
        <div class="flex-1 ml-20">
            <!-- Header -->
            <x-header />

            <!-- Page Content -->
            <div>
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Global CRMTracker object
        window.CRMT = window.CRMT || {};
        
        // CSRF token for AJAX requests
        window.CSRF_TOKEN = '{{ csrf_token() }}';
        
        // API base URL
        window.API_BASE_URL = '{{ url('/api') }}';
    </script>
    
    @stack('page-scripts')
</body>
</html>
