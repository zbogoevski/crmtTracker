@extends('layouts.dashboard')

@section('title', '1.1 Frequency Analyzer')

@section('content')
    <div class="p-6">
        <header class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-3">
                <span class="text-xs font-medium bg-blue-50 text-blue-700 px-2 py-1 rounded border border-blue-200">Module 1.1</span>
                <span class="text-xs font-medium bg-purple-50 text-purple-700 px-2 py-1 rounded border border-purple-200">Performance</span>
            </div>
            <button class="px-6 py-2.5 rounded-lg font-medium shadow-sm flex items-center gap-2 transition-colors bg-blue-600 hover:bg-blue-700 text-white">
                <i class="fa-solid fa-download"></i> Export Report
            </button>
        </header>

        <!-- Date Range Filter -->
        <div class="absolute top-6 left-1/2 -translate-x-1/2 bg-white px-4 py-2 rounded-lg shadow-sm border border-slate-200 flex items-center gap-3">
            <div class="flex items-center gap-2 text-sm text-slate-600">
                <i class="fa-regular fa-calendar text-slate-400"></i>
                <span class="font-medium">Period:</span>
            </div>
            <select class="text-sm bg-transparent border-none focus:ring-0 text-slate-700 font-medium cursor-pointer py-0 pl-2 pr-8">
                <option value="last_30">Last 30 Days</option>
                <option value="last_90">Last 90 Days</option>
                <option value="ytd">Year to Date</option>
                <option value="custom">Custom Range</option>
            </select>
            <div class="h-4 w-px bg-slate-200"></div>
            <button class="text-xs text-blue-600 font-medium hover:text-blue-700">Apply</button>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white rounded-t-xl border-b border-slate-200">
            <div class="flex">
                <button class="tab-btn active px-6 py-4 text-sm font-semibold text-indigo-600 border-b-2 border-indigo-600" onclick="switchTab('scorecard', this)">
                    <i class="fa-solid fa-star mr-2 text-amber-500"></i>Scorecard
                </button>
                <button class="tab-btn px-6 py-4 text-sm text-slate-500 hover:text-slate-700" onclick="switchTab('channels', this)">
                    <i class="fa-solid fa-chart-bar mr-2 text-blue-500"></i>Channels
                </button>
                <button class="tab-btn px-6 py-4 text-sm text-slate-500 hover:text-slate-700" onclick="switchTab('activity', this)">
                    <i class="fa-solid fa-chart-line mr-2 text-green-500"></i>Activity
                </button>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="bg-white rounded-b-xl shadow-sm border border-t-0 border-slate-100 p-6 relative">
            
            <!-- Scorecard Tab -->
            <div id="tab-scorecard" class="tab-content active">
                <h3 class="font-bold text-lg text-slate-800 mb-4">CRM Scorecard</h3>
                
                <!-- Focus Competitor Selector for Scorecard -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h4 class="text-sm font-semibold text-slate-700">Select Focus Competitor</h4>
                            <p class="text-xs text-slate-400">Click a card to highlight in the table below</p>
                        </div>
                         <div class="text-xs text-slate-500">
                            Selected: <span id="scorecard-focus-name" class="font-bold text-blue-600">None</span>
                        </div>
                    </div>
                    <div id="scorecard-competitor-cards" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                        <!-- Populated by JS -->
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm" id="scorecard-table">
                        <thead>
                            <tr class="border-b-2 border-slate-200">
                                <th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs">Competitor</th>
                                <th class="text-center py-3 px-4 font-bold text-purple-600 uppercase text-xs">CRM Score</th>
                                <th class="text-center py-3 px-4 font-bold text-blue-600 uppercase text-xs">Diversity</th>
                                <th class="text-center py-3 px-4 font-bold text-green-600 uppercase text-xs">Balance</th>
                                <th class="text-center py-3 px-4 font-bold text-amber-600 uppercase text-xs">Activity</th>
                                <th class="text-center py-3 px-4 font-bold text-slate-600 uppercase text-xs">Total Hits</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($data ?? [] as $row)
                            <tr class="hover:bg-slate-50 transition-colors cursor-pointer" id="row-{{ $row['id'] }}" onclick="highlightCompetitor('{{ $row['id'] }}', '{{ $row['name'] }}')">
                                <td class="py-3 px-4 font-medium text-slate-700">{{ $row['name'] }}</td>
                                <td class="py-3 px-4 text-center"><span class="font-bold text-purple-600">{{ number_format($row['generatedScores']['crm'] ?? 0, 2) }}</span></td>
                                <td class="py-3 px-4 text-center"><span class="font-bold text-blue-600">{{ number_format($row['generatedScores']['content'] ?? 0, 2) }}</span></td>
                                <td class="py-3 px-4 text-center"><span class="font-bold text-green-600">{{ number_format($row['generatedScores']['compliance'] ?? 0, 2) }}</span></td>
                                <td class="py-3 px-4 text-center"><span class="font-bold text-amber-600">{{ number_format($row['generatedScores']['engagement'] ?? 0, 2) }}</span></td>
                                <td class="py-3 px-4 text-center"><span class="font-bold text-slate-700">{{ $row['generatedScores']['hits'] ?? 0 }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-500">No data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Score Formula -->
                <div class="mt-4 p-4 bg-slate-50 rounded-lg border border-slate-200 text-xs text-slate-600">
                    <p class="font-bold text-slate-700 mb-2">Score Formulas:</p>
                    <ul class="space-y-1">
                        <li><span class="font-semibold text-purple-600">CRM Score</span> = Weighted Average of factors</li>
                        <li><span class="font-semibold text-blue-600">Diversity</span> = Channel utilization breadth</li>
                        <li><span class="font-semibold text-green-600">Balance</span> = Distribution across channels</li>
                        <li><span class="font-semibold text-amber-600">Activity</span> = Volume and frequency metric</li>
                    </ul>
                </div>
            </div>

            <!-- Channels Tab -->
            <div id="tab-channels" class="tab-content">
                <h3 class="font-bold text-lg text-slate-800 mb-4">Channel Mechanics</h3>

                <div class="flex items-center gap-4 text-xs mb-4">
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-blue-500"></span>Email</div>
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-purple-500"></span>SMS</div>
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-cyan-500"></span>Calls</div>
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-amber-500"></span>Push</div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b-2 border-slate-200">
                                <th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs">Competitor</th>
                                <th class="text-center py-3 px-4 font-bold text-blue-600 uppercase text-xs">Email</th>
                                <th class="text-center py-3 px-4 font-bold text-purple-600 uppercase text-xs">SMS</th>
                                <th class="text-center py-3 px-4 font-bold text-cyan-600 uppercase text-xs">Calls</th>
                                <th class="text-center py-3 px-4 font-bold text-amber-600 uppercase text-xs">Push</th>
                                <th class="text-center py-3 px-4 font-bold text-slate-600 uppercase text-xs">Total</th>
                                <th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs">Distribution</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                             @forelse($data ?? [] as $row)
                                @php
                                    $stats = $row['channelStats'] ?? ['email' => 0, 'sms' => 0, 'calls' => 0, 'push' => 0, 'total' => 0];
                                    $max = max(1, $stats['total']);
                                    $emailPct = $max > 0 ? ($stats['email'] / $max) * 100 : 0;
                                    $smsPct = $max > 0 ? ($stats['sms'] / $max) * 100 : 0;
                                    $callsPct = $max > 0 ? ($stats['calls'] / $max) * 100 : 0;
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-3 px-4 font-medium text-slate-700">{{ $row['name'] ?? 'N/A' }}</td>
                                    <td class="py-3 px-4 text-center font-bold text-blue-600">{{ $stats['email'] ?? 0 }}</td>
                                    <td class="py-3 px-4 text-center font-bold text-purple-600">{{ $stats['sms'] ?? 0 }}</td>
                                    <td class="py-3 px-4 text-center font-bold text-cyan-600">{{ $stats['calls'] ?? 0 }}</td>
                                    <td class="py-3 px-4 text-center font-bold text-amber-600">{{ $stats['push'] ?? 0 }}</td>
                                    <td class="py-3 px-4 text-center font-bold text-slate-700">{{ $stats['total'] ?? 0 }}</td>
                                    <td class="py-3 px-4">
                                        <div class="h-4 bg-slate-100 rounded-full overflow-hidden flex w-full max-w-[140px]">
                                            <div class="h-full bg-blue-500" style="width: {{ $emailPct }}%"></div>
                                            <div class="h-full bg-purple-500" style="width: {{ $smsPct }}%"></div>
                                            <div class="h-full bg-cyan-500" style="width: {{ $callsPct }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-slate-500">No data available</td>
                                </tr>
                                @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Activity Tab -->
            <div id="tab-activity" class="tab-content">
                 <h3 class="font-bold text-lg text-slate-800 mb-4">Analytics Dashboard</h3>
                 
                 <!-- Competitor Selector (Activity Context) -->
                 <div class="mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h4 class="text-sm font-semibold text-slate-700">Focus Selection</h4>
                            <p class="text-xs text-slate-400">Select a competitor to view detailed analysis below</p>
                        </div>
                         <div class="text-xs text-slate-500">
                            Selected: <span id="activity-focus-name" class="font-bold text-blue-600">None</span>
                        </div>
                    </div>
                    <div id="activity-competitor-cards" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                        <!-- Populated by JS -->
                    </div>
                </div>

                 <!-- Key Metrics Summary -->
                 <div class="grid grid-cols-4 gap-4 mb-6">
                     <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                         <p class="text-xs text-blue-600 font-medium">Total Hits</p>
                         <p class="text-2xl font-bold text-blue-700" id="stat-total-hits">0</p>
                     </div>
                     <div class="bg-purple-50 rounded-lg p-4 border border-purple-100">
                         <p class="text-xs text-purple-600 font-medium">Avg Hits/Week</p>
                         <p class="text-2xl font-bold text-purple-700" id="stat-avg-weekly">0</p>
                     </div>
                     <div class="bg-emerald-50 rounded-lg p-4 border border-emerald-100">
                         <p class="text-xs text-emerald-600 font-medium">Most Active</p>
                         <p class="text-lg font-bold text-emerald-700 truncate" id="stat-most-active">-</p>
                     </div>
                     <div class="bg-amber-50 rounded-lg p-4 border border-amber-100">
                         <p class="text-xs text-amber-600 font-medium">Tracking Period</p>
                         <p class="text-sm font-bold text-amber-700">Last 30 Days</p>
                     </div>
                 </div>

                 <!-- Charts Grid -->
                 <div class="grid grid-cols-2 gap-6 mb-6">
                     <div class="bg-slate-50 rounded-lg p-4">
                         <h4 class="font-semibold text-slate-700 mb-1">Hit Volume by Competitor</h4>
                         <p class="text-xs text-slate-500 mb-3">Total communications recorded</p>
                         <!-- Fixed height container for chart -->
                         <div class="relative h-64 w-full">
                            <canvas id="chart-volume-combined"></canvas>
                         </div>
                     </div>
                     <div class="bg-slate-50 rounded-lg p-4">
                         <h4 class="font-semibold text-slate-700 mb-1">Weekly Velocity Comparison</h4>
                         <p class="text-xs text-slate-500 mb-3">Average hits per week</p>
                         <div class="relative h-64 w-full">
                            <canvas id="chart-velocity-combined"></canvas>
                         </div>
                     </div>
                 </div>

                 <!-- Timeline (Full Width) -->
                 <div class="bg-slate-50 rounded-lg p-4 mb-6">
                     <h4 class="font-semibold text-slate-700 mb-1">Weekly Activity Trend</h4>
                     <p class="text-xs text-slate-500 mb-3">Communication frequency over time (all competitors)</p>
                     <div class="relative h-72 w-full">
                        <canvas id="chart-timeline-combined"></canvas>
                     </div>
                 </div>

                 <!-- Focus Competitor Analysis -->
                 <div id="focus-analysis-section" class="bg-orange-50 rounded-lg p-4 border-2 border-orange-200 mb-6 hidden">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="font-semibold text-orange-700">Focus Competitor Analysis</h4>
                            <p class="text-xs text-orange-600" id="focus-name-label">Detailed breakdown</p>
                        </div>
                        <span class="text-xs bg-orange-200 text-orange-800 px-2 py-1 rounded font-medium">Deep Dive</span>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-white/50 rounded p-2">
                            <p class="text-xs text-orange-800 mb-2 font-medium text-center">Channel Breakdown</p>
                            <div class="h-40 relative"><canvas id="chart-focus-volume"></canvas></div>
                        </div>
                        <div class="bg-white/50 rounded p-2">
                            <p class="text-xs text-orange-800 mb-2 font-medium text-center">Sub-Scores</p>
                            <div class="h-40 relative"><canvas id="chart-focus-scores"></canvas></div>
                        </div>
                        <div class="bg-white/50 rounded p-2">
                            <p class="text-xs text-orange-800 mb-2 font-medium text-center">Daily Pattern</p>
                            <div class="h-40 relative"><canvas id="chart-focus-timeline"></canvas></div>
                        </div>
                    </div>
                 </div>
                 
                 <!-- Placeholder when no competitor is selected -->
                 <div id="focus-placeholder" class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-lg p-8 text-center mb-6">
                    <p class="text-slate-400 text-sm">Select a competitor from the cards above or in the Scorecard tab to view detailed analysis.</p>
                 </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Server-side data
        const reportData = @json($data ?? []);
        console.log('[1.1] Report data loaded:', reportData.length, 'competitors');
        console.log('[1.1] Full report data:', reportData);
        
        // Check if data is empty
        if (!reportData || reportData.length === 0) {
            console.warn('[1.1] WARNING: No report data available!');
        }
        const CARD_COLORS = ['#3B82F6', '#8B5CF6', '#10B981', '#F59E0B', '#EF4444', '#06B6D4', '#6366F1', '#F97316'];

        function switchTab(tabId, btn) {
            // Hide all tabs by removing active class
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            
            // Deactivate all buttons
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('active', 'text-indigo-600', 'border-b-2', 'border-indigo-600', 'font-semibold');
                el.classList.add('text-slate-500');
            });

            // Activate target
            const tab = document.getElementById('tab-' + tabId);
            tab.classList.add('active');
            
            btn.classList.add('active', 'text-indigo-600', 'border-b-2', 'border-indigo-600', 'font-semibold');
            btn.classList.remove('text-slate-500');

            if (tabId === 'activity') {
                renderAnalytics();
            }
        }

        // Initialize Scorecard Cards
        document.addEventListener('DOMContentLoaded', () => {
            renderScorecardCards();
            renderActivityCards(); // Render in Activity tab too
        });

        function renderScorecardCards() {
            renderCardsToContainer('scorecard-competitor-cards');
        }

        function renderActivityCards() {
            renderCardsToContainer('activity-competitor-cards');
        }

        function renderCardsToContainer(containerId) {
            const container = document.getElementById(containerId);
            if (!container) return;
            container.innerHTML = '';
            
            reportData.forEach((competitor, index) => {
                const color = CARD_COLORS[index % CARD_COLORS.length];
                const card = document.createElement('div');
                card.className = `bg-white p-3 rounded-lg border-l-4 shadow-sm hover:shadow-md transition-all cursor-pointer group`;
                card.style.borderLeftColor = color;
                
                card.innerHTML = `
                    <div class="flex justify-between items-start">
                        <h5 class="font-bold text-slate-700 text-sm truncate group-hover:text-blue-600 transition-colors">${competitor.shortName || competitor.name}</h5>
                        <span class="text-xs font-bold px-1.5 py-0.5 rounded bg-slate-100 text-slate-600">${competitor.generatedScores.crm}</span>
                    </div>
                    <div class="mt-2 text-xs text-slate-400">
                        Hits: <span class="font-medium text-slate-600">${competitor.channelStats.total}</span>
                    </div>
                `;
                
                card.onclick = () => highlightCompetitor(competitor.id, competitor.name);
                container.appendChild(card);
            });
        }

        let selectedCompetitorId = null;

        function highlightCompetitor(id, name) {
            selectedCompetitorId = id;

            // Update text in Scorecard & Activity
            const focusNameEl = document.getElementById('scorecard-focus-name');
            if (focusNameEl) focusNameEl.textContent = name;
            
            const activityFocusNameEl = document.getElementById('activity-focus-name');
            if (activityFocusNameEl) activityFocusNameEl.textContent = name;
            
            // Highlight Table Row
            document.querySelectorAll('tbody tr').forEach(tr => {
                tr.classList.remove('bg-blue-50', 'ring-1', 'ring-blue-200');
            });
            const row = document.getElementById('row-' + id);
            if (row) {
                row.classList.add('bg-blue-50', 'ring-1', 'ring-blue-200');
            }

            // Update Focus Chart Section visibility
            document.getElementById('focus-analysis-section').classList.remove('hidden');
            const placeholder = document.getElementById('focus-placeholder');
            if (placeholder) placeholder.classList.add('hidden');
            
            // Update Focus Label
            document.getElementById('focus-name-label').textContent = 'Analysis for ' + name;

            // Render/Update Focus Charts
            // If the analytics dashboard volume chart exists, it means we are in a context where chart libs are ready.
            if (charts['volume']) {
                if (!charts['focus_volume']) {
                    initFocusCharts();
                }
                updateFocusCharts(id);
            }
        }

        function updateFocusCharts(id) {
            const competitor = reportData.find(c => c.id == id);
            if (!competitor) return;

            // 1. Focus Volume (Pie)
            charts['focus_volume'].data.datasets[0].data = [
                competitor.channelStats.email,
                competitor.channelStats.sms,
                competitor.channelStats.calls,
                competitor.channelStats.push
            ];
            charts['focus_volume'].update();

            // 2. Focus Sub-Scores (Radar)
            charts['focus_scores'].data.datasets[0].data = [
                competitor.generatedScores.content,
                competitor.generatedScores.compliance,
                competitor.generatedScores.engagement
            ];
            charts['focus_scores'].update();

            // 3. Focus Timeline (Line)
            const days = competitor.dailyHits.map(d => new Date(d.date).toLocaleDateString('en-US', { day: 'numeric', month: 'short' }));
            const counts = competitor.dailyHits.map(d => d.count);
            
            charts['focus_timeline'].data.labels = days;
            charts['focus_timeline'].data.datasets[0].data = counts;
            charts['focus_timeline'].update();
        }

        let charts = {};

        function renderAnalytics() {
            if (charts['volume']) {
                // If existing charts rendered, checking if we need to render focus charts for the first time
                if (selectedCompetitorId && !charts['focus_volume']) {
                    initFocusCharts();
                    updateFocusCharts(selectedCompetitorId);
                }
                return; 
            }

            // ... (Existing render logic for summary charts) ...
            
            // 1. Calculate Summary Stats
            let totalHits = 0;
            let maxHits = 0;
            let mostActive = '-';

            reportData.forEach(c => {
                const hits = c.channelStats.total;
                totalHits += hits;
                if (hits > maxHits) {
                    maxHits = hits;
                    mostActive = c.shortName || c.name;
                }
            });

            document.getElementById('stat-total-hits').textContent = totalHits.toLocaleString();
            document.getElementById('stat-avg-weekly').textContent = (totalHits / 4).toFixed(1); 
            document.getElementById('stat-most-active').textContent = mostActive;

            // 2. Volume Chart
            const sortedByHits = [...reportData].sort((a, b) => b.channelStats.total - a.channelStats.total).slice(0, 10);
            const ctxVolume = document.getElementById('chart-volume-combined').getContext('2d');
            charts['volume'] = new Chart(ctxVolume, {
                type: 'bar',
                data: {
                    labels: sortedByHits.map(c => c.shortName || c.name),
                    datasets: [{
                        label: 'Total Hits',
                        data: sortedByHits.map(c => c.channelStats.total),
                        backgroundColor: sortedByHits.map((_, i) => CARD_COLORS[i % CARD_COLORS.length]),
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, grid: { display: false } },
                        y: { grid: { display: false } }
                    }
                }
            });

            // 3. Velocity Chart
            const ctxVelocity = document.getElementById('chart-velocity-combined').getContext('2d');
            charts['velocity'] = new Chart(ctxVelocity, {
                type: 'bar',
                data: {
                    labels: sortedByHits.map(c => c.shortName || c.name),
                    datasets: [{
                        label: 'Weekly Avg',
                        data: sortedByHits.map(c => (c.channelStats.total / 4).toFixed(1)),
                        backgroundColor: sortedByHits.map((_, i) => CARD_COLORS[i % CARD_COLORS.length]),
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, grid: { display: false } },
                        y: { grid: { display: false } }
                    }
                }
            });

            // 4. Timeline Chart
            const timelineMap = {};
            if (reportData.length > 0 && reportData[0].dailyHits) {
                 reportData[0].dailyHits.forEach(d => timelineMap[d.date] = 0);
            }

            reportData.forEach(c => {
                if (c.dailyHits) {
                    c.dailyHits.forEach(d => {
                        if (!timelineMap[d.date]) timelineMap[d.date] = 0;
                        timelineMap[d.date] += d.count;
                    });
                }
            });

            const timelineData = Object.keys(timelineMap).map(date => ({
                date: date,
                count: timelineMap[date]
            })).sort((a, b) => new Date(a.date) - new Date(b.date));

            const ctxTimeline = document.getElementById('chart-timeline-combined').getContext('2d');
            charts['timeline'] = new Chart(ctxTimeline, {
                type: 'line',
                data: {
                    labels: timelineData.map(d => {
                        const date = new Date(d.date);
                        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                    }),
                    datasets: [{
                        label: 'Market Activity',
                        data: timelineData.map(d => d.count),
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                        pointHoverRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [2, 2] } },
                        x: { grid: { display: false } }
                    }
                }
            });

            // Initialize Focus Charts empty structures if selected
            if (selectedCompetitorId) {
                initFocusCharts();
                updateFocusCharts(selectedCompetitorId);
            }
        }

        function initFocusCharts() {
            // Focus Volume
            const ctxFocusVol = document.getElementById('chart-focus-volume').getContext('2d');
            charts['focus_volume'] = new Chart(ctxFocusVol, {
                type: 'doughnut',
                data: {
                    labels: ['Email', 'SMS', 'Calls', 'Push'],
                    datasets: [{
                        data: [0, 0, 0, 0],
                        backgroundColor: ['#3B82F6', '#8B5CF6', '#06B6D4', '#F59E0B'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    cutout: '70%'
                }
            });

            // Focus Scores
            const ctxFocusScores = document.getElementById('chart-focus-scores').getContext('2d');
            charts['focus_scores'] = new Chart(ctxFocusScores, {
                type: 'radar',
                data: {
                    labels: ['Diversity', 'Balance', 'Activity'],
                    datasets: [{
                        label: 'Score',
                        data: [0, 0, 0],
                        backgroundColor: 'rgba(249, 115, 22, 0.2)',
                        borderColor: '#F97316',
                        pointBackgroundColor: '#F97316'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        r: { beginAtZero: true, max: 100, ticks: { display: false } }
                    }
                }
            });

            // Focus Timeline
            const ctxFocusTime = document.getElementById('chart-focus-timeline').getContext('2d');
            charts['focus_timeline'] = new Chart(ctxFocusTime, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Hits',
                        data: [],
                        backgroundColor: '#F97316',
                        borderRadius: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { display: false },
                        y: { display: false }
                    }
                }
            });
        }
    </script>
@endsection
