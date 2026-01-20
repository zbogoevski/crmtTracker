<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CRMTracker - Report Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
            color: #334155;
        }

        .nav-item.active {
            background-color: #1E293B;
            border-left: 4px solid #3B82F6;
        }

        .nav-submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .nav-submenu.open {
            max-height: 500px;
        }

        .gradient-hero {
            background: linear-gradient(135deg, #0F172A 0%, #1E3A5F 50%, #0F172A 100%);
        }

        /* Custom scrollbar for nav sidebar */
        #crmt-nav-sidebar nav::-webkit-scrollbar {
            width: 6px;
        }

        #crmt-nav-sidebar nav::-webkit-scrollbar-track {
            background: transparent;
        }

        #crmt-nav-sidebar nav::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 3px;
        }

        #crmt-nav-sidebar nav::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }

        #crmt-nav-sidebar nav {
            scrollbar-width: thin;
            scrollbar-color: #334155 transparent;
        }
    </style>

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

    <script>
        // Toggle sidebar submenu
        function toggleCRMTSubmenu(element) {
            event.preventDefault();
            const submenu = element.nextElementSibling;
            const chevron = element.querySelector('.fa-chevron-down');

            // Close all other submenus
            document.querySelectorAll('.nav-submenu').forEach(s => {
                if (s !== submenu) {
                    s.style.maxHeight = '0';
                    const prevChevron = s.previousElementSibling.querySelector('.fa-chevron-down');
                    if (prevChevron) prevChevron.style.transform = 'rotate(0deg)';
                }
            });

            // Toggle current submenu
            if (submenu.style.maxHeight === '0px' || submenu.style.maxHeight === '') {
                submenu.style.maxHeight = '500px';
                chevron.style.transform = 'rotate(180deg)';
            } else {
                submenu.style.maxHeight = '0';
                chevron.style.transform = 'rotate(0deg)';
            }
        }

        // Toggle navbar collapsed state
        function toggleCRMTNavbar() {
            const sidebar = document.getElementById('crmt-nav-sidebar');
            const main = document.getElementById('main-content');
            const isCollapsed = sidebar.classList.contains('w-20');

            if (isCollapsed) {
                sidebar.classList.remove('w-20');
                sidebar.classList.add('w-96');
                main.classList.remove('ml-20');
                main.classList.add('ml-96');
                document.querySelectorAll('.nav-text').forEach(el => el.classList.remove('hidden'));
                localStorage.setItem('crmt_nav_collapsed', 'false');
            } else {
                sidebar.classList.remove('w-96');
                sidebar.classList.add('w-20');
                main.classList.remove('ml-96');
                main.classList.add('ml-20');
                document.querySelectorAll('.nav-text').forEach(el => el.classList.add('hidden'));
                document.querySelectorAll('.nav-submenu').forEach(s => s.style.maxHeight = '0');
                localStorage.setItem('crmt_nav_collapsed', 'true');
            }
        }

        // Initialize collapsed state
        document.addEventListener('DOMContentLoaded', function () {
            const isCollapsed = localStorage.getItem('crmt_nav_collapsed') === 'true';
            if (isCollapsed) {
                const sidebar = document.getElementById('crmt-nav-sidebar');
                const main = document.getElementById('main-content');
                if (sidebar && main) {
                    sidebar.classList.remove('w-96');
                    sidebar.classList.add('w-20');
                    main.classList.remove('ml-96');
                    main.classList.add('ml-20');
                    document.querySelectorAll('.nav-text').forEach(el => el.classList.add('hidden'));
                }
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
