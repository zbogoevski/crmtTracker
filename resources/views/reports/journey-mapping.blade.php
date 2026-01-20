@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <header class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center gap-3 mb-4">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                Module 1.2
            </button>
            <button class="px-4 py-2 bg-amber-500 text-white rounded-lg text-sm font-medium hover:bg-amber-600 transition-colors">
                Beta
            </button>
            <button class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-300 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-flask"></i>
                Stub Data
            </button>
        </div>
        <p class="text-sm text-slate-600">Email touchpoints across competitor journeys (Days 0-7/0-30 Premium)</p>
    </header>

    <!-- Filters Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4">
        <div class="flex items-center justify-between gap-6">
            <!-- Channel Filters (Left) -->
            <div class="flex items-center gap-4">
                <span class="text-xs font-semibold text-slate-500 uppercase">CHANNELS</span>
                <div class="flex items-center gap-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="channel-filter" value="email" checked data-channel="email">
                        <i class="fa-solid fa-envelope text-slate-600"></i>
                        <span class="text-sm text-slate-700">Email</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="channel-filter" value="sms" checked data-channel="sms">
                        <i class="fa-solid fa-comment-sms text-slate-600"></i>
                        <span class="text-sm text-slate-700">SMS</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="channel-filter" value="call" checked data-channel="call">
                        <i class="fa-solid fa-phone text-slate-600"></i>
                        <span class="text-sm text-slate-700">Calls</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="channel-filter" value="push" checked data-channel="push">
                        <i class="fa-solid fa-bell text-slate-600"></i>
                        <span class="text-sm text-slate-700">Push</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="channel-filter" value="paid" checked data-channel="paid">
                        <i class="fa-solid fa-bullhorn text-slate-600"></i>
                        <span class="text-sm text-slate-700">Paid</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="channel-filter" value="dm" checked data-channel="dm">
                        <i class="fa-solid fa-briefcase text-slate-600"></i>
                        <span class="text-sm text-slate-700">DM</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="channel-filter" value="in-app" checked data-channel="in-app">
                        <i class="fa-solid fa-chart-bar text-slate-600"></i>
                        <span class="text-sm text-slate-700">In-App</span>
                    </label>
                </div>
            </div>

            <!-- Date Filter (Right) -->
            <div class="flex items-center gap-3">
                <span class="text-sm font-medium text-slate-700">Date:</span>
                <select id="date-filter" class="px-3 py-2 border border-slate-300 rounded-lg text-sm text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="365">Last 365 days</option>
                    <option value="90">Last 90 days</option>
                    <option value="30">Last 30 days</option>
                    <option value="7">Last 7 days</option>
                    <option value="custom">Custom Range</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Customer Journey - Side by Side -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 mb-6 flex flex-col shrink-0">
        <div class="p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-1">Customer Journey - Side by Side</h3>
            <p class="text-sm text-slate-500 mb-4">Tracking competitor communications across 31 days (Day 0-30) in 3 time slots per day</p>

            <div class="flex">
                <!-- Competitor Column -->
                <div class="w-36 shrink-0 border-r border-slate-100 z-10 bg-white">
                    <div class="h-12 flex items-center pb-2 border-b border-slate-200">
                        <span class="text-xs font-bold text-slate-500 uppercase">Competitor</span>
                    </div>
                    @foreach($data as $competitor)
                        <div class="h-24 flex items-center border-b border-slate-50 relative group">
                            <div class="pr-4 py-2 w-full">
                                <div class="font-bold text-slate-700 text-sm truncate" title="{{ $competitor['name'] }}">{{ $competitor['shortName'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Scrollable Timeline -->
                <div class="flex-1 overflow-x-auto timeline-scroll relative" id="timeline-container">
                    <div style="min-width: {{ 31 * 80 }}px;">
                        <!-- Header Row -->
                        <div class="flex border-b border-slate-200 pb-2 h-12 items-end" id="timeline-header">
                            @for($i = 0; $i <= 30; $i++)
                                <div class="text-center shrink-0 border-r border-slate-100 day-column {{ $i > 7 ? 'bg-slate-50/50 premium-day' : '' }}" 
                                     data-day="{{ $i }}" 
                                     style="width: 80px; {{ $i > 7 ? 'display: none;' : '' }}">
                                    <div class="text-[11px] font-bold text-slate-500 mb-1">DAY {{ $i }}</div>
                                    <div class="grid grid-cols-3 text-[8px] text-slate-400 gap-px">
                                        <span>M</span><span>N</span><span>E</span>
                                    </div>
                                </div>
                            @endfor
                        </div>

                        <!-- Rows -->
                        @foreach($data as $competitor)
                            <div class="flex h-24 border-b border-slate-50 items-center relative hover:bg-slate-50/30 transition-colors">
                                @for($i = 0; $i <= 30; $i++)
                                    <div class="h-full shrink-0 border-r border-slate-100 grid grid-cols-3 relative day-column premium-day-row {{ $i > 7 ? 'bg-slate-50/30' : '' }}" 
                                         data-day="{{ $i }}" 
                                         style="width: 80px; {{ $i > 7 ? 'display: none;' : '' }}">
                                        @php
                                            $events = collect($competitor['journeyEvents'])->where('day', $i);
                                            $slots = ['21-9' => 0, '9-15' => 1, '15-21' => 2];
                                        @endphp

                                        @foreach(['21-9', '9-15', '15-21'] as $slotKey)
                                            <div class="h-full flex justify-center items-center relative group/cell">
                                                @foreach($events->where('slot', $slotKey) as $event)
                                                    @php
                                                        $icon = match($event['type'] ?? 'email') {
                                                            'email' => 'fa-envelope',
                                                            'sms' => 'fa-comment-sms',
                                                            'call' => 'fa-phone',
                                                            'push' => 'fa-bell',
                                                            'paid' => 'fa-ad',
                                                            'dm' => 'fa-envelope-open',
                                                            'in-app' => 'fa-mobile-screen',
                                                            default => 'fa-circle'
                                                        };
                                                        $colorClass = match($event['type'] ?? 'email') {
                                                            'email' => 'bg-amber-500 border-amber-500',
                                                            'sms' => 'bg-purple-500 border-purple-500',
                                                            'call' => 'bg-cyan-500 border-cyan-500',
                                                            'push' => 'bg-blue-500 border-blue-500',
                                                            'paid' => 'bg-green-500 border-green-500',
                                                            'dm' => 'bg-pink-500 border-pink-500',
                                                            'in-app' => 'bg-indigo-500 border-indigo-500',
                                                            default => 'bg-slate-400'
                                                        };
                                                    @endphp
                                                    <div class="absolute z-10 cursor-pointer hover:z-50 transition-transform hover:scale-125 event-marker" data-channel="{{ $event['type'] ?? 'email' }}">
                                                        <div class="w-5 h-5 rounded-full {{ $colorClass }} text-white flex items-center justify-center shadow-sm border border-white text-[9px]">
                                                            <i class="fa-solid {{ $icon }}"></i>
                                                        </div>

                                                        <!-- Tooltip -->
                                                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 bg-slate-800 text-white text-xs rounded p-2 opacity-0 group-hover/cell:opacity-100 pointer-events-none transition-opacity z-50">
                                                            <div class="font-bold text-amber-300 mb-0.5">{{ $event['time'] ?? 'N/A' }} • {{ ucfirst($event['type'] ?? 'Email') }}</div>
                                                            <div class="mb-1">{{ $event['subject'] ?? 'No subject' }}</div>
                                                            <div class="text-slate-400 text-[10px]">Tone: {{ $event['tone'] ?? 'N/A' }}</div>
                                                            @if(isset($event['type']) && $event['type'] !== 'email')
                                                                <div class="text-slate-400 text-[10px]">Type: {{ $event['type'] ?? 'N/A' }}</div>
                                                            @endif
                                                            <!-- Arrow -->
                                                            <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-slate-800"></div>
                                                        </div>
                                                    </div>
                                                @endforeach

                                                <!-- Empty Slot Marker -->
                                                @if($events->where('slot', $slotKey)->isEmpty())
                                                    <div class="w-1 h-1 rounded-full bg-slate-200"></div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endfor
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- First 48h Diagnostic -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 mt-6 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">First 48h Diagnostic</h3>
                <p class="text-sm text-slate-500">Communication intensity in the first 48 hours post-registration</p>
            </div>
            <span class="text-xs bg-amber-100 text-amber-700 px-2 py-1 rounded font-medium">
                <i class="fa-solid fa-stopwatch mr-1"></i>Critical Window
            </span>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <!-- Email Count Chart -->
            <div class="bg-slate-50 rounded-lg p-4">
                <h4 class="font-semibold text-slate-700 mb-1">Emails in First 48h</h4>
                <p class="text-xs text-slate-500 mb-3">Total emails per competitor</p>
                <div class="h-[400px]"><canvas id="chart-first48h-count"></canvas></div>
            </div>
            <!-- Email Types Chart -->
            <div class="bg-slate-50 rounded-lg p-4">
                <h4 class="font-semibold text-slate-700 mb-1">Email Types in First 48h</h4>
                <p class="text-xs text-slate-500 mb-3">Breakdown by content type</p>
                <div class="h-[400px]"><canvas id="chart-first48h-types"></canvas></div>
            </div>
        </div>

        <!-- Summary Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-slate-200">
                        <th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs">Competitor</th>
                        <th class="text-center py-3 px-4 font-bold text-blue-600 uppercase text-xs">Total Emails</th>
                        <th class="text-center py-3 px-4 font-bold text-green-600 uppercase text-xs">Welcome</th>
                        <th class="text-center py-3 px-4 font-bold text-purple-600 uppercase text-xs">Promo</th>
                        <th class="text-center py-3 px-4 font-bold text-amber-600 uppercase text-xs">Trans.</th>
                        <th class="text-center py-3 px-4 font-bold text-slate-600 uppercase text-xs">Avg/Day</th>
                        <th class="text-center py-3 px-4 font-bold text-slate-600 uppercase text-xs">Intensity</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($data as $competitor)
                        @php $stats = $competitor['analytics']['first48h']; @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 font-medium">{{ $competitor['shortName'] }}</td>
                            <td class="py-3 px-4 text-center font-bold text-blue-600">{{ $stats['total'] }}</td>
                            <td class="py-3 px-4 text-center font-bold text-green-600">{{ $stats['welcome'] }}</td>
                            <td class="py-3 px-4 text-center font-bold text-purple-600">{{ $stats['promo'] }}</td>
                            <td class="py-3 px-4 text-center font-bold text-amber-600">{{ $stats['trans'] }}</td>
                            <td class="py-3 px-4 text-center">{{ number_format($stats['avgPerDay'], 1) }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="text-xs px-2 py-1 rounded {{ $stats['intensity'] === 'High' ? 'bg-red-100 text-red-700' : ($stats['intensity'] === 'Medium' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700') }}">
                                    {{ $stats['intensity'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Timing Analysis -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 mt-6 p-6">
        <h3 class="text-lg font-bold text-slate-800 mb-2">Timing Analysis</h3>
        <p class="text-sm text-slate-500 mb-6">Most popular sending times, weekly frequency, day-parts and days.</p>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="text-left py-3 text-xs font-semibold text-slate-500 uppercase">Competitor</th>
                        <th class="text-left py-3 text-xs font-semibold text-slate-500 uppercase">Weekly Frequency (Avg)</th>
                        <th class="text-left py-3 text-xs font-semibold text-slate-500 uppercase">Most Congested Hour</th>
                        <th class="text-left py-3 text-xs font-semibold text-slate-500 uppercase">Most Popular Day</th>
                        <th class="text-left py-3 text-xs font-semibold text-slate-500 uppercase">Most Popular Day-Part</th>
                        <th class="text-left py-3 text-xs font-semibold text-slate-500 uppercase">Most Popular Hour</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $competitor)
                        @php $timing = $competitor['analytics']['timing']; @endphp
                        <tr class="border-b border-slate-100">
                            <td class="py-4 font-bold text-slate-700">{{ $competitor['shortName'] }}</td>
                            <td class="py-4 text-slate-700">{{ $timing['weeklyFreq'] }}</td>
                            <td class="py-4 text-slate-700">{{ $timing['popularHour'] }}</td>
                            <td class="py-4 text-blue-600 font-medium">{{ $timing['popularDay'] }}</td>
                            <td class="py-4 text-slate-700">{{ $timing['popularDayPart'] }}</td>
                            <td class="py-4 text-slate-700">{{ $timing['popularHour'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const data = @json($data);
        const labels = data.map(c => c.shortName);
        const colors = ['#F59E0B', '#3B82F6', '#8B5CF6', '#10B981', '#F43F5E', '#06B6D4', '#6366F1', '#F97316'];

        // Count Chart
        new Chart(document.getElementById('chart-first48h-count'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Emails in 48h',
                    data: data.map(c => c.analytics.first48h.total),
                    backgroundColor: data.map((_, i) => colors[i % colors.length])
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { legend: { display: false } } 
            }
        });

        // Types Chart
        new Chart(document.getElementById('chart-first48h-types'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    { label: 'Welcome', data: data.map(c => c.analytics.first48h.welcome), backgroundColor: '#10B981', stack: 'types' },
                    { label: 'Promo', data: data.map(c => c.analytics.first48h.promo), backgroundColor: '#8B5CF6', stack: 'types' },
                    { label: 'Transactional', data: data.map(c => c.analytics.first48h.trans), backgroundColor: '#F59E0B', stack: 'types' }
                ]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { legend: { position: 'bottom' } }, 
                scales: { x: { stacked: true }, y: { stacked: true } } 
            }
        });

        // Date Filter Functionality
        const dateFilter = document.getElementById('date-filter');
        dateFilter.addEventListener('change', function() {
            const selectedDays = this.value;
            console.log('Date filter changed to:', selectedDays);
            // TODO: Implement date filtering logic
            // This would filter the data based on the selected date range
        });

        // Days Range Filter Functionality (for timeline display)
        const dayColumns = document.querySelectorAll('.day-column');
        let premiumUnlocked = false;

        // Initially show only days 0-7
        dayColumns.forEach(column => {
            const day = parseInt(column.getAttribute('data-day'));
            if (day > 7) {
                column.style.display = 'none';
            }
        });

        // Channel Filter Functionality
        const channelFilters = document.querySelectorAll('.channel-filter');
        const eventMarkers = document.querySelectorAll('.event-marker');

        channelFilters.forEach(filter => {
            filter.addEventListener('change', function() {
                const selectedChannels = Array.from(channelFilters)
                    .filter(f => f.checked)
                    .map(f => f.value);

                eventMarkers.forEach(marker => {
                    const channel = marker.getAttribute('data-channel');
                    if (selectedChannels.includes(channel)) {
                        marker.style.display = 'block';
                    } else {
                        marker.style.display = 'none';
                    }
                });
            });
        });
    });
</script>

<style>
    .timeline-scroll::-webkit-scrollbar {
        height: 10px;
    }
    .timeline-scroll::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 5px;
    }
    .timeline-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 5px;
    }
    .timeline-scroll::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endpush
@endsection
