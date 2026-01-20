<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CRMTracker - Report Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @stack('styles')
</head>

<body class="bg-[#f3f4f6] font-sans text-slate-800">
    <div id="root-container" class="flex min-h-screen">
        <!-- Sidebar Navigation (Fixed) -->
        <x-sidebar />

        <!-- Main Content (with left margin to account for fixed sidebar) -->
        <main class="flex-1 ml-96 p-8 max-w-[1600px] mx-auto overflow-x-hidden" id="main-content">
            @yield('content')
        </main>
    </div>


    @stack('scripts')
</body>

</html>
