<div class="h-full flex items-center justify-between px-6">
    <div class="flex items-center gap-4">
        @yield('header-left')
    </div>
    
    <div class="flex items-center gap-4">
        <div id="date-range-container" class="flex items-center"></div>
        <button class="px-6 py-2.5 rounded-lg font-medium shadow-sm flex items-center gap-2 transition-colors bg-blue-600 hover:bg-blue-700 text-white">
            <i class="fa-solid fa-download"></i> Export Report
        </button>
    </div>
</div>
