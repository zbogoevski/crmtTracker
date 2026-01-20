@extends('layouts.dashboard')

@section('content')

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@push('styles')
<style>
body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
        }

        .tab-btn {
            transition: all 0.2s;
        }

        .tab-btn.active {
            border-bottom: 3px solid #10B981;
            color: #10B981;
            font-weight: 600;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .competitor-card {
            transition: all 0.2s;
        }

        .competitor-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
</style>
@endpush

@section('content')
<div class="space-y-6">
<header class="flex justify-between items-center mb-6">
<div class="flex items-center gap-3">
<span
class="text-xs font-medium bg-green-50 text-green-700 px-2 py-1 rounded border border-green-200">Module
1.3</span>
<span
class="text-xs font-medium bg-purple-50 text-purple-700 px-2 py-1 rounded border border-purple-200">Lifecycle</span>
</div>
<div id="date-range-container" class="flex items-center gap-3">
    <span class="text-sm font-medium text-slate-700">Date:</span>
    <select id="date-filter" class="px-3 py-2 border border-slate-300 rounded-lg text-sm text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
        <option value="365">Last 365 days</option>
        <option value="90">Last 90 days</option>
        <option value="30">Last 30 days</option>
        <option value="7">Last 7 days</option>
        <option value="custom">Custom Range</option>
    </select>
</div>
<button
class="px-6 py-2.5 rounded-lg font-medium shadow-sm flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white">
<i class="fa-solid fa-download"></i> Export
</button>
</header>
<!-- Tab Navigation -->
<div class="bg-white rounded-t-xl border-b border-slate-200">
<div class="flex">
<button class="tab-btn active px-6 py-4 text-sm" onclick="switchTab('acquisition')">
<i class="fa-solid fa-user-plus mr-2 text-green-500"></i>Acquisition
</button>
<button class="tab-btn px-6 py-4 text-sm text-slate-500" onclick="switchTab('retention')">
<i class="fa-solid fa-heart mr-2 text-blue-500"></i>Retention
</button>
<button class="tab-btn px-6 py-4 text-sm text-slate-500" onclick="switchTab('reactivation')">
<i class="fa-solid fa-rotate mr-2 text-amber-500"></i>Reactivation
</button>
</div>
</div>
<!-- Tab Content -->
<div class="bg-white rounded-b-xl shadow-sm border border-t-0 border-slate-100 p-6 relative">
<!-- Loading Overlay -->
<div id="loading-overlay"
class="absolute inset-0 bg-white/90 flex items-center justify-center z-10 rounded-b-xl">
<div class="text-center">
<i class="fa-solid fa-spinner fa-spin text-4xl text-green-500 mb-3"></i>
<p class="text-slate-600 font-medium">Loading lifecycle data...</p>
</div>
</div>
<!-- Acquisition Tab -->
<div id="tab-acquisition" class="tab-content active">
<div class="flex justify-between items-center mb-6">
<div>
<h3 class="font-bold text-lg text-slate-800">Acquisition Campaign Analysis</h3>
<p class="text-sm text-slate-500">First-touch communications - <span class="font-semibold"
id="acq-total">0</span> total hits</p>
</div>
<span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full font-medium">
<i class="fa-solid fa-chart-line mr-1"></i>ACQ Stage
</span>
</div>
<!-- Competitor Cards Side by Side -->
<div id="acq-cards" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6"></div>
<div class="bg-slate-50 rounded-lg p-4">
<h4 class="font-semibold text-slate-700 mb-3">Acquisition Volume Comparison</h4>
<div style="height: 300px;"><canvas id="chart-acquisition"></canvas></div>
</div>
</div>
<!-- Retention Tab -->
<div id="tab-retention" class="tab-content">
<div class="flex justify-between items-center mb-6">
<div>
<h3 class="font-bold text-lg text-slate-800">Retention Communication Patterns</h3>
<p class="text-sm text-slate-500">Ongoing engagement - <span class="font-semibold"
id="ret-total">0</span> total hits</p>
</div>
<span class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-medium">
<i class="fa-solid fa-heart mr-1"></i>RET Stage
</span>
</div>
<!-- Competitor Cards Side by Side -->
<div id="ret-cards" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6"></div>
<div class="bg-slate-50 rounded-lg p-4">
<h4 class="font-semibold text-slate-700 mb-3">Retention Volume Comparison</h4>
<div style="height: 300px;"><canvas id="chart-retention"></canvas></div>
</div>
</div>
<!-- Reactivation Tab -->
<div id="tab-reactivation" class="tab-content">
<div class="flex justify-between items-center mb-6">
<div>
<h3 class="font-bold text-lg text-slate-800">Reactivation Campaigns</h3>
<p class="text-sm text-slate-500">Win-back communications - <span class="font-semibold"
id="rea-total">0</span> total hits</p>
</div>
<span class="text-xs bg-amber-100 text-amber-700 px-3 py-1 rounded-full font-medium">
<i class="fa-solid fa-rotate mr-1"></i>REA Stage
</span>
</div>
<!-- Competitor Cards Side by Side -->
<div id="rea-cards" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6"></div>
<div class="bg-slate-50 rounded-lg p-4">
<h4 class="font-semibold text-slate-700 mb-3">Reactivation Volume Comparison</h4>
<div style="height: 300px;"><canvas id="chart-reactivation"></canvas></div>
</div>
</div>
</div>
@push('scripts')
<script>
        let charts = {};
        let stageData = { ACQ: [], RET: [], REA: [] };
        let competitors = [];

        // Color palette for competitors
        const COLORS = [
            { bg: 'bg-amber-100', border: 'border-amber-300', text: 'text-amber-700', accent: '#F59E0B' },
            { bg: 'bg-blue-100', border: 'border-blue-300', text: 'text-blue-700', accent: '#3B82F6' },
            { bg: 'bg-purple-100', border: 'border-purple-300', text: 'text-purple-700', accent: '#8B5CF6' },
            { bg: 'bg-emerald-100', border: 'border-emerald-300', text: 'text-emerald-700', accent: '#10B981' },
            { bg: 'bg-rose-100', border: 'border-rose-300', text: 'text-rose-700', accent: '#F43F5E' },
            { bg: 'bg-cyan-100', border: 'border-cyan-300', text: 'text-cyan-700', accent: '#06B6D4' },
            { bg: 'bg-indigo-100', border: 'border-indigo-300', text: 'text-indigo-700', accent: '#6366F1' },
            { bg: 'bg-orange-100', border: 'border-orange-300', text: 'text-orange-700', accent: '#F97316' }
        ];

        function switchTab(tabId) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            event.currentTarget.classList.add('active');
            document.getElementById('tab-' + tabId).classList.add('active');
            window.location.hash = tabId;
        }

        function checkHash() {
            const hash = window.location.hash.slice(1);
            if (hash && ['acquisition', 'retention', 'reactivation'].includes(hash)) {
                const btn = document.querySelector(`.tab-btn[onclick*="${hash}"]`);
                if (btn) btn.click();
            }
        }

        function getDayName(dayNum) {
            return ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'][dayNum] || 'N/A';
        }

        function mode(arr) {
            if (!arr || arr.length === 0) return null;
            const freq = {};
            let maxFreq = 0, modeVal = arr[0];
            arr.forEach(v => { freq[v] = (freq[v] || 0) + 1; if (freq[v] > maxFreq) { maxFreq = freq[v]; modeVal = v; } });
            return modeVal;
        }

        // Use data from Laravel backend
        const laravelData = @json($data ?? []);
        
        async function fetchLifecycleData() {
            try {
                // Convert Laravel data to expected format
                stageData.ACQ = (laravelData.ACQ || []).flatMap(comp => 
                    (comp.hits || []).map(hit => ({
                        id: hit.id,
                        competitor_id: comp.competitor_id,
                        received_at: hit.received_at,
                        channel: hit.channel,
                        subject: hit.subject,
                        tone: hit.tone
                    }))
                );
                stageData.RET = (laravelData.RET || []).flatMap(comp => 
                    (comp.hits || []).map(hit => ({
                        id: hit.id,
                        competitor_id: comp.competitor_id,
                        received_at: hit.received_at,
                        channel: hit.channel,
                        subject: hit.subject,
                        tone: hit.tone
                    }))
                );
                stageData.REA = (laravelData.REA || []).flatMap(comp => 
                    (comp.hits || []).map(hit => ({
                        id: hit.id,
                        competitor_id: comp.competitor_id,
                        received_at: hit.received_at,
                        channel: hit.channel,
                        subject: hit.subject,
                        tone: hit.tone
                    }))
                );

                console.log('[1.3] Loaded lifecycle hits:', {
                    ACQ: stageData.ACQ.length,
                    RET: stageData.RET.length,
                    REA: stageData.REA.length
                });
            } catch (e) {
                console.error('[1.3] Failed to load lifecycle data:', e);
            }
        }

        function renderStageTab(stage, colorScheme, chartId, cardsId, totalId) {

            const hits = stageData[stage] || [];
            const cardsContainer = document.getElementById(cardsId);
            document.getElementById(totalId).textContent = hits.length;

            // Group by competitor
            const byCompetitor = {};
            hits.forEach(h => {
                const cid = h.competitor_id;
                if (!byCompetitor[cid]) byCompetitor[cid] = [];
                byCompetitor[cid].push(h);
            });

            // Render competitor cards side by side
            cardsContainer.innerHTML = '';
            competitors.forEach((c, idx) => {
                const color = COLORS[idx % COLORS.length];
                const compHits = byCompetitor[c.id] || [];
                const count = compHits.length;
                const days = compHits.map(h => new Date(h.received_at).getDay());
                const peakDay = mode(days);
                const peakHour = compHits.length > 0 ? new Date(compHits[0].received_at).getHours() : null;

                cardsContainer.innerHTML += `
                    <div class="competitor-card ${color.bg} ${color.border} border-2 rounded-xl p-4 text-center">
                        <div class="font-bold ${color.text} text-sm mb-2 truncate" title="${c.name}">${c.shortName || c.name}</div>
                        <div class="text-3xl font-black ${color.text}">${count}</div>
                        <div class="text-xs text-slate-500 mt-1">${stage} Hits</div>
                        <div class="mt-3 pt-2 border-t border-slate-200">
                            <div class="text-xs text-slate-500">Peak Day</div>
                            <div class="font-semibold ${color.text}">${count > 0 ? getDayName(peakDay) : 'N/A'}</div>
                        </div>
                    </div>
                `;
            });

            // Chart
            const chartColors = competitors.map((_, idx) => COLORS[idx % COLORS.length].accent);
            if (charts[stage]) charts[stage].destroy();
            const ctx = document.getElementById(chartId).getContext('2d');
            charts[stage] = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: competitors.map(c => c.shortName || c.name),
                    datasets: [{
                        label: stage + ' Hits',
                        data: competitors.map(c => (byCompetitor[c.id] || []).length),
                        backgroundColor: chartColors
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });
        }

        // Get competitors from Laravel data
        const laravelCompetitors = @json($data['ACQ'] ?? []);
        competitors = laravelCompetitors.map(comp => ({
            id: comp.competitor_id,
            name: comp.competitor_name,
            shortName: comp.short_name
        }));

        async function initDashboard() {
            console.log('[1.3] Initializing dashboard with', competitors.length, 'competitors');

            await fetchLifecycleData();

            renderStageTab('ACQ', 'green', 'chart-acquisition', 'acq-cards', 'acq-total');
            renderStageTab('RET', 'blue', 'chart-retention', 'ret-cards', 'ret-total');
            renderStageTab('REA', 'amber', 'chart-reactivation', 'rea-cards', 'rea-total');

            // Hide loading overlay
            document.getElementById('loading-overlay')?.classList.add('hidden');

            checkHash();
        }

        // Date Filter Functionality
        const dateFilter = document.getElementById('date-filter');
        if (dateFilter) {
            dateFilter.addEventListener('change', function() {
                const selectedDays = this.value;
                console.log('Date filter changed to:', selectedDays);
                // TODO: Implement date filtering logic
                // This would filter the data based on the selected date range
                // For now, just reload the data
                initDashboard();
            });
        }

        // Initialize immediately
        initDashboard();
    </script>
@endpush
</div>
@endsection