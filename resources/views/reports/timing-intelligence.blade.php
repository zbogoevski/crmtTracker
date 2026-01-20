@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">

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
            border-bottom: 3px solid #4F46E5;
            color: #4F46E5;
            font-weight: 600;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .heatmap-cell {
            min-width: 32px;
            min-height: 28px;
        }

        .competitor-card {
            transition: all 0.2s;
        }

        .competitor-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .competitor-card.focus-selected {
            outline: 3px solid #3B82F6;
            outline-offset: 2px;
            box-shadow: 0 0 0 3px #3B82F6;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
        }

        .calendar-cell {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            border-radius: 4px;
        }

        .calendar-cell.has-activity-1 {
            background: #dcfce7;
        }

        .calendar-cell.has-activity-2 {
            background: #86efac;
        }

        .calendar-cell.has-activity-3 {
            background: #22c55e;
            color: white;
        }

        /* Compact calendar for 3-column layout */
        .calendar-compact .calendar-cell {
            font-size: 8px;
            min-width: 12px;
            min-height: 12px;
        }

        .calendar-compact {
            gap: 1px;
        }
</style>
@endpush

<main class="flex-1 ml-20 p-8 max-w-[1800px] mx-auto">
<header class="flex justify-between items-center mb-6">
<div class="flex items-center gap-3">
<span
class="text-xs font-medium bg-blue-50 text-blue-700 px-2 py-1 rounded border border-blue-200">Module
1.5</span>
<span
class="text-xs font-medium bg-amber-50 text-amber-700 px-2 py-1 rounded border border-amber-200">Timing</span>
</div>
<div id="date-range-container" class="flex items-center"></div>
<!-- Focus Competitor Selector -->
<div class="flex items-center gap-2">
<span class="text-xs text-slate-500">Focus:</span>
<select id="focus-competitor-select" onchange="setFocusCompetitor(this.value)"
class="bg-blue-50 border border-blue-200 text-blue-700 text-sm rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-blue-500">
<option value="">Select competitor...</option>
</select>
</div>
<button
class="px-6 py-2.5 rounded-lg font-medium shadow-sm flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white">
<i class="fa-solid fa-download"></i> Export
</button>
</header>
<!-- Tab Navigation -->
<div class="bg-white rounded-t-xl border-b border-slate-200">
<div class="flex">
<button class="tab-btn active px-6 py-4 text-sm" onclick="switchTab('calendar')">
<i class="fa-solid fa-calendar mr-2 text-blue-500"></i>Calendar Intelligence
</button>
<button class="tab-btn px-6 py-4 text-sm text-slate-500" onclick="switchTab('seasonal')">
<i class="fa-solid fa-snowflake mr-2 text-cyan-500"></i>Seasonal & Holiday
</button>
</div>
</div>
<!-- Tab Content -->
<div class="bg-white rounded-b-xl shadow-sm border border-t-0 border-slate-100 p-6 relative">
<!-- Loading Overlay -->
<div id="loading-overlay"
class="absolute inset-0 bg-white/90 flex items-center justify-center z-10 rounded-b-xl">
<div class="text-center">
<i class="fa-solid fa-spinner fa-spin text-4xl text-blue-500 mb-3"></i>
<p class="text-slate-600 font-medium">Loading timing data...</p>
</div>
</div>
<!-- Calendar Tab -->
<div id="tab-calendar" class="tab-content active">
<div class="flex justify-between items-center mb-6">
<div>
<h3 class="font-bold text-lg text-slate-800">Send Time Optimization</h3>
<p class="text-sm text-slate-500">Compare timing patterns across competitors - <span
class="font-semibold" id="cal-total">0</span> total hits</p>
</div>
</div>
<!-- Competitor Timing Cards Side by Side (Clickable for Focus) -->
<div id="cal-cards" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6"></div>
<!-- Three-Column Comparison Layout: Heatmaps -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
<!-- Market (All in Jurisdiction) -->
<div class="rounded-lg p-4" style="background: #F1F5F9; border: 1px solid #CBD5E1">
<h4 class="font-semibold text-sm uppercase tracking-wide mb-3" style="color: #64748B">
<i class="fa-solid fa-globe mr-2"></i>Market Average
</h4>
<div id="heatmap-market" class="overflow-x-auto"></div>
</div>
<!-- Selected Group -->
<div class="rounded-lg p-4" style="background: #E2E8F0; border: 1px solid #94A3B8">
<h4 class="font-semibold text-sm uppercase tracking-wide mb-3" style="color: #475569">
<i class="fa-solid fa-users mr-2"></i>Selected Group
</h4>
<div id="heatmap-group" class="overflow-x-auto"></div>
</div>
<!-- Focus Competitor -->
<div class="rounded-lg p-4" style="background: #DBEAFE; border: 1px solid #93C5FD">
<h4 class="font-semibold text-sm uppercase tracking-wide mb-3" style="color: #3B82F6"
id="focus-heatmap-title">
<i class="fa-solid fa-crosshairs mr-2"></i>Focus: Select a competitor
</h4>
<div id="heatmap-focus" class="overflow-x-auto"></div>
</div>
</div>
<!-- Three-Column: Day of Week Bar Charts -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
<div class="bg-white rounded-lg p-4" style="border: 1px solid #CBD5E1">
<h4 class="font-semibold text-sm mb-3" style="color: #64748B">Day of Week - Market</h4>
<div style="height: 180px;"><canvas id="chart-dow-market"></canvas></div>
</div>
<div class="bg-white rounded-lg p-4" style="border: 1px solid #94A3B8">
<h4 class="font-semibold text-sm mb-3" style="color: #475569">Day of Week - Group</h4>
<div style="height: 180px;"><canvas id="chart-dow-group"></canvas></div>
</div>
<div class="bg-white rounded-lg p-4" style="border: 1px solid #93C5FD; overflow: hidden;">
<h4 class="font-semibold text-sm mb-3" style="color: #3B82F6" id="dow-focus-title">Day of
Week - Focus</h4>
<div style="height: 180px; overflow: hidden;"><canvas id="chart-dow-focus"></canvas></div>
</div>
</div>
<!-- Three-Column: Peak Time Pie Charts -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
<div class="bg-white rounded-lg p-4" style="border: 1px solid #CBD5E1">
<h4 class="font-semibold text-sm mb-3" style="color: #64748B">Peak Time - Market</h4>
<div style="height: 180px;"><canvas id="chart-peak-market"></canvas></div>
</div>
<div class="bg-white rounded-lg p-4" style="border: 1px solid #94A3B8">
<h4 class="font-semibold text-sm mb-3" style="color: #475569">Peak Time - Group</h4>
<div style="height: 180px;"><canvas id="chart-peak-group"></canvas></div>
</div>
<div class="bg-white rounded-lg p-4" style="border: 1px solid #93C5FD; overflow: hidden;">
<h4 class="font-semibold text-sm mb-3" style="color: #3B82F6" id="peak-focus-title">Peak
Time - Focus</h4>
<div style="height: 180px; overflow: hidden;"><canvas id="chart-peak-focus"></canvas></div>
</div>
</div>
<!-- Weekly Trend (Full Width - Both on same chart) -->
<div class="bg-white border border-slate-200 rounded-lg p-4 mb-6">
<h4 class="font-semibold text-slate-700 mb-3 text-sm">Weekly Activity Trend</h4>
<div style="height: 250px;"><canvas id="chart-weekly-trend"></canvas></div>
</div>
<!-- Opportunity Windows Table -->
<div class="mb-6 bg-white border border-emerald-200 rounded-lg p-4">
<div class="flex items-center justify-between mb-4">
<div>
<h4 class="font-semibold text-emerald-700 text-sm uppercase tracking-wide">
<i class="fa-solid fa-bullseye mr-2"></i>Opportunity Windows
</h4>
<p class="text-xs text-slate-500">Time slots with lowest competitor activity</p>
</div>
<span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded font-medium">
<i class="fa-solid fa-lightbulb mr-1"></i>Strategic Intel
</span>
</div>
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead>
<tr class="border-b-2 border-slate-200">
<th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs">
Window</th>
<th class="text-center py-3 px-4 font-bold text-slate-600 uppercase text-xs">
Day(s)</th>
<th class="text-center py-3 px-4 font-bold text-slate-600 uppercase text-xs">
Hours</th>
<th class="text-center py-3 px-4 font-bold text-emerald-600 uppercase text-xs">
Competitor Hits</th>
<th class="text-center py-3 px-4 font-bold text-blue-600 uppercase text-xs">
Opportunity Score</th>
<th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs">
Recommendation</th>
</tr>
</thead>
<tbody id="opportunity-tbody" class="divide-y divide-slate-100"></tbody>
</table>
</div>
</div>
<!-- Three-Column: Activity Calendars (Market / Group / Focus) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
<!-- Market Calendar -->
<div class="rounded-lg p-3" style="background: #F1F5F9; border: 1px solid #CBD5E1">
<h4 class="font-semibold text-xs uppercase tracking-wide mb-2" style="color: #64748B">
<i class="fa-solid fa-globe mr-1"></i>Market
</h4>
<div id="calendar-market" class="calendar-grid calendar-compact"></div>
<div class="flex items-center gap-2 mt-2 text-xs text-slate-500">
<span class="flex items-center gap-1"><span
class="w-2 h-2 rounded bg-slate-100"></span>None</span>
<span class="flex items-center gap-1"><span
class="w-2 h-2 rounded has-activity-1"></span>Low</span>
<span class="flex items-center gap-1"><span
class="w-2 h-2 rounded has-activity-2"></span>Med</span>
<span class="flex items-center gap-1"><span
class="w-2 h-2 rounded has-activity-3"></span>High</span>
</div>
</div>
<!-- Group Calendar -->
<div class="rounded-lg p-3" style="background: #E2E8F0; border: 1px solid #94A3B8">
<h4 class="font-semibold text-xs uppercase tracking-wide mb-2" style="color: #475569">
<i class="fa-solid fa-users mr-1"></i>Group
</h4>
<div id="calendar-group" class="calendar-grid calendar-compact"></div>
<div class="flex items-center gap-2 mt-2 text-xs text-slate-500">
<span class="flex items-center gap-1"><span
class="w-2 h-2 rounded bg-slate-100"></span>None</span>
<span class="flex items-center gap-1"><span
class="w-2 h-2 rounded has-activity-1"></span>Low</span>
<span class="flex items-center gap-1"><span
class="w-2 h-2 rounded has-activity-2"></span>Med</span>
<span class="flex items-center gap-1"><span
class="w-2 h-2 rounded has-activity-3"></span>High</span>
</div>
</div>
<!-- Focus Calendar -->
<div class="rounded-lg p-3" style="background: #DBEAFE; border: 1px solid #93C5FD">
<h4 class="font-semibold text-xs uppercase tracking-wide mb-2" style="color: #3B82F6"
id="calendar-focus-title">
<i class="fa-solid fa-crosshairs mr-1"></i>Focus
</h4>
<div id="calendar-focus" class="calendar-grid calendar-compact"></div>
<div class="flex items-center gap-2 mt-2 text-xs text-slate-500">
<span class="flex items-center gap-1"><span
class="w-2 h-2 rounded bg-slate-100"></span>None</span>
<span class="flex items-center gap-1"><span
class="w-2 h-2 rounded has-activity-1"></span>Low</span>
<span class="flex items-center gap-1"><span
class="w-2 h-2 rounded has-activity-2"></span>Med</span>
<span class="flex items-center gap-1"><span
class="w-2 h-2 rounded has-activity-3"></span>High</span>
</div>
</div>
</div>
</div>
<!-- Seasonal Tab -->
<div id="tab-seasonal" class="tab-content">
<div class="flex justify-between items-center mb-6">
<div>
<h3 class="font-bold text-lg text-slate-800">Seasonal & Holiday Campaigns</h3>
<p class="text-sm text-slate-500">Day-of-week patterns by competitor</p>
</div>
</div>
<!-- Competitor Day Patterns Side by Side -->
<div class="overflow-x-auto mb-6">
<table class="w-full text-sm">
<thead>
<tr class="border-b-2 border-slate-200">
<th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs">
Competitor</th>
<th class="text-center py-3 px-2 font-bold text-slate-500 uppercase text-xs w-14">
Mon</th>
<th class="text-center py-3 px-2 font-bold text-slate-500 uppercase text-xs w-14">
Tue</th>
<th class="text-center py-3 px-2 font-bold text-slate-500 uppercase text-xs w-14">
Wed</th>
<th class="text-center py-3 px-2 font-bold text-slate-500 uppercase text-xs w-14">
Thu</th>
<th class="text-center py-3 px-2 font-bold text-slate-500 uppercase text-xs w-14">
Fri</th>
<th class="text-center py-3 px-2 font-bold text-slate-500 uppercase text-xs w-14">
Sat</th>
<th class="text-center py-3 px-2 font-bold text-slate-500 uppercase text-xs w-14">
Sun</th>
<th class="text-center py-3 px-4 font-bold text-purple-600 uppercase text-xs">Total
</th>
<th class="text-center py-3 px-4 font-bold text-blue-600 uppercase text-xs">Peak
</th>
</tr>
</thead>
<tbody id="seasonal-tbody" class="divide-y divide-slate-100"></tbody>
</table>
</div>
<div class="bg-slate-50 rounded-lg p-4 mb-6">
<h4 class="font-semibold text-slate-700 mb-3">Day of Week Activity by Competitor</h4>
<div style="height: 300px;"><canvas id="chart-dow"></canvas></div>
</div>
<!-- Holiday Communication Timeline -->
<div class="bg-gradient-to-br from-cyan-50 to-blue-50 rounded-lg p-4 border border-cyan-200">
<div class="flex items-center gap-2 mb-4">
<i class="fa-solid fa-calendar-days text-cyan-500"></i>
<h4 class="font-bold text-cyan-800">Holiday Communication Timeline</h4>
</div>
<p class="text-xs text-slate-500 mb-4">Activity around major holidays and events</p>
<div style="height: 260px;"><canvas id="chart-holiday"></canvas></div>
<div id="holiday-legend" class="flex flex-wrap gap-2 mt-4"></div>
</div>
</div>
</div>
</div>


@push('scripts')
<script>
        // Use Laravel data if available
        const laravelData = {!! json_encode($data ?? []) !!};
        console.log('[1.5] Laravel data received:', laravelData);
        const laravelHits = laravelData.hits || laravelData.all_hits || [];
        const laravelCompetitors = laravelData.competitors || [];
        console.log('[1.5] Parsed Laravel data - Hits:', laravelHits.length, 'Competitors:', laravelCompetitors.length);

        let charts = {};
        let currentHits = [];
        let competitors = [];       // Selected group competitors
        let marketCompetitors = []; // All competitors in market (for Market tier)
        let marketHits = [];        // All hits in market (for Market tier)
        let focusCompetitorId = null;

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

        // Time slot definitions
        const TIME_SLOTS = [
            { name: 'Business Hours', start: 9, end: 17, color: '#3B82F6' },
            { name: 'After Hours', start: 17, end: 22, color: '#8B5CF6' },
            { name: 'Off Hours', start: 22, end: 9, color: '#64748B' }
        ];

        function setFocusCompetitor(id) {
            focusCompetitorId = id || null;
            // Update dropdown
            const select = document.getElementById('focus-competitor-select');
            if (select) select.value = id || '';
            // Update card highlights
            document.querySelectorAll('.competitor-card').forEach(card => {
                card.classList.toggle('focus-selected', card.dataset.id === id);
            });
            // Update focus titles
            const focusComp = competitors.find(c => String(c.id) === String(id));
            const focusName = focusComp ? (focusComp.shortName || focusComp.name) : 'Select a competitor';
            document.getElementById('focus-heatmap-title').innerHTML = `<i class="fa-solid fa-crosshairs mr-2"></i>Focus: ${focusName}`;
            document.getElementById('dow-focus-title').textContent = `Day of Week - ${focusName}`;
            document.getElementById('peak-focus-title').textContent = `Peak Time - ${focusName}`;
            document.getElementById('calendar-focus-title').textContent = `Activity Calendar - ${focusName}`;
            // Re-render charts
            renderCalendar();
        }

        function populateFocusDropdown() {
            const select = document.getElementById('focus-competitor-select');
            if (!select) return;
            select.innerHTML = '<option value="">Select competitor...</option>' +
                competitors.map(c => `<option value="${c.id}">${c.shortName || c.name}</option>`).join('');
            if (focusCompetitorId) select.value = focusCompetitorId;
        }

        function switchTab(tabId) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            event.currentTarget.classList.add('active');
            document.getElementById('tab-' + tabId).classList.add('active');
            window.location.hash = tabId;
        }

        function checkHash() {
            const hash = window.location.hash.slice(1);
            if (hash && ['calendar', 'seasonal'].includes(hash)) {
                const btn = document.querySelector(`.tab-btn[onclick*="${hash}"]`);
                if (btn) btn.click();
            }
        }

        function getIntensityColor(value, max) {
            if (max === 0) return 'bg-slate-50 text-slate-400';
            const ratio = value / max;
            if (ratio < 0.2) return 'bg-blue-50 text-blue-600';
            if (ratio < 0.4) return 'bg-blue-100 text-blue-700';
            if (ratio < 0.6) return 'bg-blue-200 text-blue-800';
            if (ratio < 0.8) return 'bg-blue-400 text-white';
            return 'bg-blue-600 text-white font-bold';
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

        async function fetchHitsData() {
            // Use Laravel data if available
            if (laravelHits && laravelHits.length > 0) {
                console.log('[1.5] Using Laravel hits data:', laravelHits.length);
                return laravelHits;
            }

            const competitorIds = competitors.map(c => c.id);
            if (competitorIds.length === 0) {
                console.warn('[1.5] No competitor IDs available');
                return [];
            }

            try {
                if (window.CRMT?.dal?.getTimelineHits) {
                    const result = await window.CRMT.dal.getTimelineHits(competitorIds);
                    return result.data || [];
                }
            } catch (e) {
                console.error('[1.5] Error fetching hits:', e);
            }
            return [];
        }

        async function renderCalendar() {
            const hits = currentHits;
            const cardsContainer = document.getElementById('cal-cards');
            const heatmapContainer = document.getElementById('heatmap-container');
            document.getElementById('cal-total').textContent = hits.length;

            // Group by competitor
            const byCompetitor = {};
            hits.forEach(h => {
                const cid = h.competitor_id;
                if (!byCompetitor[cid]) byCompetitor[cid] = [];
                byCompetitor[cid].push(h);
            });

            // Render competitor timing cards
            cardsContainer.innerHTML = '';
            competitors.forEach((c, idx) => {
                const color = COLORS[idx % COLORS.length];
                const compHits = byCompetitor[c.id] || [];
                const count = compHits.length;

                const hours = compHits.map(h => new Date(h.received_at).getHours());
                const days = compHits.map(h => new Date(h.received_at).getDay());
                const peakHour = mode(hours);
                const peakDay = mode(days);

                const isSelected = String(c.id) === String(focusCompetitorId);
                cardsContainer.innerHTML += `
                    <div class="competitor-card ${color.bg} ${color.border} border-2 rounded-xl p-4 text-center ${isSelected ? 'focus-selected' : ''}" 
                         data-id="${c.id}" 
                         onclick="setFocusCompetitor('${c.id}')"
                         style="cursor:pointer;">
                        <div class="font-bold ${color.text} text-sm mb-2 truncate" title="${c.name}">${c.shortName || c.name}</div>
                        <div class="text-3xl font-black ${color.text}">${count}</div>
                        <div class="text-xs text-slate-500 mt-1">Hits</div>
                        <div class="grid grid-cols-2 gap-2 mt-3 pt-2 border-t border-slate-200">
                            <div>
                                <div class="text-xs text-slate-500">Peak Hour</div>
                                <div class="font-bold ${color.text}">${count > 0 ? peakHour + ':00' : 'N/A'}</div>
                            </div>
                            <div>
                                <div class="text-xs text-slate-500">Peak Day</div>
                                <div class="font-bold ${color.text}">${count > 0 ? getDayName(peakDay) : 'N/A'}</div>
                            </div>
                        </div>
                    </div>
                `;
            });

            // Build 3-tier hit data
            const groupIds = competitors.map(c => String(c.id));
            const marketIds = marketCompetitors.map(c => String(c.id));
            const tierHits = {
                market: marketHits,
                group: hits,
                focus: focusCompetitorId ? hits.filter(h => String(h.competitor_id) === String(focusCompetitorId)) : []
            };

            // Render 3 heatmaps (Market / Group / Focus)
            renderHeatmap('heatmap-market', tierHits.market);
            renderHeatmap('heatmap-group', tierHits.group);
            if (focusCompetitorId) {
                renderHeatmap('heatmap-focus', tierHits.focus);
            } else {
                document.getElementById('heatmap-focus').innerHTML =
                    '<div class="text-slate-400 text-center py-8 text-sm">Select a competitor to compare</div>';
            }

            // Render 3-tier Day of Week bar charts
            renderDayOfWeekCharts(tierHits);

            // Render 3-tier Peak Time pie charts
            renderPeakTimeCharts(tierHits);

            // Render Weekly Trend dual-axis chart (all 3 tiers)
            renderWeeklyTrendChart(tierHits);

            // Render GitHub-style calendar views
            renderCalendarViews(tierHits);

            // Render Opportunity Windows table
            renderOpportunityTable(hits);
        }

        function renderOpportunityTable(hits) {
            const tbody = document.getElementById('opportunity-tbody');
            if (!tbody) return;

            // Build day-hour matrix
            const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            const matrix = {};
            days.forEach(d => {
                matrix[d] = {};
                for (let h = 0; h < 24; h++) matrix[d][h] = 0;
            });

            hits.forEach(hit => {
                const date = new Date(hit.received_at);
                const hour = date.getHours();
                const dayNum = date.getDay();
                const dayIdx = dayNum === 0 ? 6 : dayNum - 1;
                const dayName = days[dayIdx];
                if (matrix[dayName]) matrix[dayName][hour]++;
            });

            // Define strategic windows
            const windows = [
                { name: 'Early Morning Rush', days: ['Tue', 'Wed', 'Thu'], hours: [6, 7, 8] },
                { name: 'Lunch Break (Weekdays)', days: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'], hours: [12, 13] },
                { name: 'Evening Peak', days: ['Mon', 'Tue', 'Wed', 'Thu'], hours: [19, 20, 21] },
                { name: 'Weekend Morning', days: ['Sat', 'Sun'], hours: [9, 10, 11] },
                { name: 'Weekend Afternoon', days: ['Sat', 'Sun'], hours: [14, 15, 16] },
                { name: 'Sunday Evening', days: ['Sun'], hours: [18, 19, 20] },
                { name: 'Late Night', days: ['Fri', 'Sat'], hours: [22, 23, 0] },
                { name: 'Midweek Afternoon', days: ['Wed', 'Thu'], hours: [14, 15, 16] }
            ];

            // Calculate hit count for each window
            const windowStats = windows.map(w => {
                let totalHits = 0;
                w.days.forEach(d => {
                    w.hours.forEach(h => {
                        totalHits += matrix[d]?.[h] || 0;
                    });
                });
                const slots = w.days.length * w.hours.length;
                const avgPerSlot = totalHits / slots;
                // Lower hits = higher opportunity (inverse score)
                const maxPossible = hits.length / (7 * 24);
                const opportunityScore = Math.max(0, Math.round((1 - avgPerSlot / (maxPossible * 3)) * 100));
                return { ...w, totalHits, avgPerSlot, opportunityScore, slots };
            });

            // Sort by opportunity score (highest first)
            windowStats.sort((a, b) => b.opportunityScore - a.opportunityScore);

            // Generate recommendation based on score
            const getRecommendation = (score) => {
                if (score >= 80) return '<span class="text-xs px-2 py-1 rounded bg-emerald-100 text-emerald-700">Highly Recommended</span>';
                if (score >= 60) return '<span class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-700">Consider Testing</span>';
                if (score >= 40) return '<span class="text-xs px-2 py-1 rounded bg-amber-100 text-amber-700">Moderate Opportunity</span>';
                return '<span class="text-xs px-2 py-1 rounded bg-slate-100 text-slate-600">Low Priority</span>';
            };

            tbody.innerHTML = windowStats.slice(0, 6).map(w => `
                <tr class="hover:bg-slate-50">
                    <td class="py-3 px-4 font-medium">${w.name}</td>
                    <td class="py-3 px-4 text-center text-sm">${w.days.join(', ')}</td>
                    <td class="py-3 px-4 text-center text-sm">${w.hours.map(h => h + ':00').join(' - ')}</td>
                    <td class="py-3 px-4 text-center font-bold text-emerald-600">${w.totalHits}</td>
                    <td class="py-3 px-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <div class="w-16 h-2 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full ${w.opportunityScore >= 60 ? 'bg-emerald-500' : w.opportunityScore >= 40 ? 'bg-blue-500' : 'bg-amber-500'}" style="width:${w.opportunityScore}%"></div>
                            </div>
                            <span class="font-bold text-blue-600">${w.opportunityScore}%</span>
                        </div>
                    </td>
                    <td class="py-3 px-4">${getRecommendation(w.opportunityScore)}</td>
                </tr>
            `).join('');
        }

        function renderHeatmap(containerId, hits) {
            const container = document.getElementById(containerId);
            if (!container) return;

            const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            const hoursArr = Array.from({ length: 24 }, (_, i) => i); // 0-23
            const data = {};
            let maxVal = 0;

            days.forEach(day => {
                data[day] = {};
                hoursArr.forEach(hour => data[day][hour] = 0);
            });

            hits.forEach(hit => {
                const date = new Date(hit.received_at);
                const hour = date.getHours();
                const dayNum = date.getDay();
                const dayIdx = dayNum === 0 ? 6 : dayNum - 1;
                const dayName = days[dayIdx];

                if (data[dayName]) {
                    data[dayName][hour]++;
                    if (data[dayName][hour] > maxVal) maxVal = data[dayName][hour];
                }
            });

            let html = '<table class="text-xs w-full"><thead><tr><th class="px-2 py-1"></th>';
            hoursArr.forEach(h => html += `<th class="px-1 py-1 text-center">${h}</th>`);
            html += '</tr></thead><tbody>';

            days.forEach(day => {
                html += `<tr><td class="px-2 py-1 font-semibold">${day}</td>`;
                hoursArr.forEach(hour => {
                    const val = data[day][hour];
                    const color = getIntensityColor(val, maxVal);
                    html += `<td class="heatmap-cell ${color} text-center rounded text-xs">${val || ''}</td>`;
                });
                html += '</tr>';
            });
            html += '</tbody></table>';
            container.innerHTML = html;
        }

        function renderDayOfWeekCharts(tierHits) {
            const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

            // Market tier
            const marketCounts = aggregateByDay(tierHits.market);
            renderBarChart('chart-dow-market', days, marketCounts, '#64748B');

            // Group tier
            const groupCounts = aggregateByDay(tierHits.group);
            renderBarChart('chart-dow-group', days, groupCounts, '#475569');

            // Focus tier
            if (focusCompetitorId && tierHits.focus.length > 0) {
                const focusCounts = aggregateByDay(tierHits.focus);
                renderBarChart('chart-dow-focus', days, focusCounts, '#3B82F6');
            } else {
                // Clear focus chart
                if (charts['chart-dow-focus']) {
                    charts['chart-dow-focus'].destroy();
                    charts['chart-dow-focus'] = null;
                }
                // Draw placeholder on canvas
                const canvas = document.getElementById('chart-dow-focus');
                if (canvas) {
                    const ctx = canvas.getContext('2d');
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    ctx.font = '14px sans-serif';
                    ctx.fillStyle = '#94A3B8';
                    ctx.textAlign = 'center';
                    ctx.fillText('Select a competitor', canvas.width / 2, canvas.height / 2);
                }
            }
        }

        function aggregateByDay(hits) {
            const counts = Array(7).fill(0);
            hits.forEach(h => counts[new Date(h.received_at).getDay()]++);
            // Reorder Sun=0...Sat=6 → Mon...Sun
            return [counts[1], counts[2], counts[3], counts[4], counts[5], counts[6], counts[0]];
        }

        function renderBarChart(canvasId, labels, data, color) {
            if (charts[canvasId]) charts[canvasId].destroy();
            const ctx = document.getElementById(canvasId)?.getContext('2d');
            if (!ctx) return;

            charts[canvasId] = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: color,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }

        function renderPeakTimeCharts(tierHits) {
            // Market tier
            const marketSlots = aggregateByTimeSlot(tierHits.market);
            renderPieChart('chart-peak-market', marketSlots);

            // Group tier
            const groupSlots = aggregateByTimeSlot(tierHits.group);
            renderPieChart('chart-peak-group', groupSlots);

            // Focus tier
            if (focusCompetitorId && tierHits.focus.length > 0) {
                const focusSlots = aggregateByTimeSlot(tierHits.focus);
                renderPieChart('chart-peak-focus', focusSlots);
            } else {
                // Clear focus chart
                if (charts['chart-peak-focus']) {
                    charts['chart-peak-focus'].destroy();
                    charts['chart-peak-focus'] = null;
                }
                // Draw placeholder on canvas
                const canvas = document.getElementById('chart-peak-focus');
                if (canvas) {
                    const ctx = canvas.getContext('2d');
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    ctx.font = '14px sans-serif';
                    ctx.fillStyle = '#94A3B8';
                    ctx.textAlign = 'center';
                    ctx.fillText('Select a competitor', canvas.width / 2, canvas.height / 2);
                }
            }
        }

        function aggregateByTimeSlot(hits) {
            const slots = { business: 0, after: 0, off: 0 };
            hits.forEach(h => {
                const hour = new Date(h.received_at).getHours();
                if (hour >= 9 && hour < 17) slots.business++;
                else if (hour >= 17 && hour < 22) slots.after++;
                else slots.off++;
            });
            return slots;
        }

        function renderPieChart(canvasId, slots) {
            if (charts[canvasId]) charts[canvasId].destroy();
            const ctx = document.getElementById(canvasId)?.getContext('2d');
            if (!ctx) return;

            charts[canvasId] = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Business (9-17)', 'After Hours (17-22)', 'Off Hours (22-9)'],
                    datasets: [{
                        data: [slots.business, slots.after, slots.off],
                        backgroundColor: ['#3B82F6', '#8B5CF6', '#64748B']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'right', labels: { boxWidth: 12 } } }
                }
            });
        }

        function renderWeeklyTrendChart(tierHits) {
            // Aggregate by week for all 3 tiers
            const marketData = aggregateByWeek(tierHits.market);
            const groupData = aggregateByWeek(tierHits.group);
            const focusData = focusCompetitorId ? aggregateByWeek(tierHits.focus) : { weeks: [], volumes: [] };

            // Get all unique weeks from all tiers
            const allWeeks = [...new Set([
                ...marketData.weeks,
                ...groupData.weeks,
                ...focusData.weeks
            ])].sort();

            if (charts['chart-weekly-trend']) charts['chart-weekly-trend'].destroy();
            const ctx = document.getElementById('chart-weekly-trend')?.getContext('2d');
            if (!ctx) return;

            // Format week labels to show week number and year (e.g., "W38 '24")
            const formattedWeeks = allWeeks.map(w => {
                const d = new Date(w);
                const jan1 = new Date(d.getFullYear(), 0, 1);
                const dayOfYear = Math.ceil((d - jan1) / (24 * 60 * 60 * 1000));
                const weekNum = Math.ceil((dayOfYear + jan1.getDay()) / 7);
                const year = d.getFullYear().toString().slice(-2);
                return `W${weekNum} '${year}`;
            });

            // Map volumes to aligned weeks
            const marketVolumes = allWeeks.map(w => { const i = marketData.weeks.indexOf(w); return i >= 0 ? marketData.volumes[i] : 0; });
            const groupVolumes = allWeeks.map(w => { const i = groupData.weeks.indexOf(w); return i >= 0 ? groupData.volumes[i] : 0; });
            const focusVolumes = allWeeks.map(w => { const i = focusData.weeks.indexOf(w); return i >= 0 ? focusData.volumes[i] : 0; });

            const focusComp = competitors.find(c => String(c.id) === String(focusCompetitorId));
            const focusName = focusComp ? (focusComp.shortName || focusComp.name) : 'Focus';

            const datasets = [
                {
                    label: 'Market',
                    data: marketVolumes,
                    borderColor: '#64748B',
                    backgroundColor: 'rgba(100, 116, 139, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    borderWidth: 2
                },
                {
                    label: 'Group',
                    data: groupVolumes,
                    borderColor: '#8B5CF6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    borderWidth: 2
                }
            ];

            // Add focus line if selected
            if (focusCompetitorId) {
                datasets.push({
                    label: focusName,
                    data: focusVolumes,
                    borderColor: '#3B82F6',
                    backgroundColor: 'transparent',
                    fill: false,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#3B82F6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    borderWidth: 3
                });
            }

            charts['chart-weekly-trend'] = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: formattedWeeks,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => `${ctx.dataset.label}: ${ctx.parsed.y} hits`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Hits per Week' },
                            grid: { color: 'rgba(0,0,0,0.05)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        function aggregateByWeek(hits) {
            const weekMap = {};
            hits.forEach(h => {
                const d = new Date(h.received_at);
                const weekStart = new Date(d);
                weekStart.setDate(d.getDate() - d.getDay() + 1); // Monday
                const key = weekStart.toISOString().slice(0, 10);
                weekMap[key] = (weekMap[key] || 0) + 1;
            });

            const sorted = Object.entries(weekMap).sort((a, b) => a[0].localeCompare(b[0]));
            const weeks = sorted.map(([k]) => k);
            const volumes = sorted.map(([, v]) => v);
            const changes = volumes.map((v, i) => i === 0 ? 0 : Math.round((v - volumes[i - 1]) / (volumes[i - 1] || 1) * 100));

            return { weeks, volumes, changes };
        }

        function renderCalendarViews(tierHits) {
            // Get date range - default to last 90 days if not set
            const dateRange = window.CRMT?.dateRange?.getRange?.() || { from: null, to: null };
            const endDate = dateRange.to ? new Date(dateRange.to) : new Date();
            const startDate = dateRange.from ? new Date(dateRange.from) : new Date(Date.now() - 90 * 24 * 60 * 60 * 1000);

            // Render all 3 calendars
            renderGitHubCalendar('calendar-market', tierHits.market, startDate, endDate);
            renderGitHubCalendar('calendar-group', tierHits.group, startDate, endDate);

            if (focusCompetitorId && tierHits.focus.length > 0) {
                renderGitHubCalendar('calendar-focus', tierHits.focus, startDate, endDate);
            } else {
                document.getElementById('calendar-focus').innerHTML =
                    '<div class="text-slate-400 text-center py-4 text-xs col-span-7">Select a competitor</div>';
            }
        }

        function renderGitHubCalendar(containerId, hits, startDate, endDate) {
            const container = document.getElementById(containerId);
            if (!container) return;

            // Build day → count map
            const dayCount = {};
            hits.forEach(h => {
                const day = new Date(h.received_at).toISOString().slice(0, 10);
                dayCount[day] = (dayCount[day] || 0) + 1;
            });

            // Calculate dynamic thresholds based on max count in this dataset
            const counts = Object.values(dayCount);
            const maxCount = counts.length > 0 ? Math.max(...counts) : 0;
            // Use quartiles: 0 = empty, 1-25% = low, 26-50% = medium, 51%+ = high
            const threshold1 = Math.max(1, Math.ceil(maxCount * 0.25));
            const threshold2 = Math.max(2, Math.ceil(maxCount * 0.50));
            const threshold3 = Math.max(3, Math.ceil(maxCount * 0.75));

            // Generate grid cells for date range
            let html = '';
            const current = new Date(startDate);
            const end = new Date(endDate);

            // Add day headers
            const dayLabels = ['M', 'T', 'W', 'T', 'F', 'S', 'S'];
            dayLabels.forEach(d => html += `<div class="text-xs text-slate-400 text-center font-medium">${d}</div>`);

            // Pad to start on Monday
            const dayOfWeek = current.getDay();
            const padDays = dayOfWeek === 0 ? 6 : dayOfWeek - 1;
            for (let i = 0; i < padDays; i++) {
                html += '<div class="calendar-cell bg-transparent"></div>';
            }

            while (current <= end) {
                const key = current.toISOString().slice(0, 10);
                const count = dayCount[key] || 0;
                const level = count === 0 ? 'bg-slate-100' :
                    count <= threshold1 ? 'has-activity-1' :
                        count <= threshold2 ? 'has-activity-2' : 'has-activity-3';
                html += `<div class="calendar-cell ${level}" title="${key}: ${count} hits"></div>`;
                current.setDate(current.getDate() + 1);
            }

            container.innerHTML = html;
        }

        async function renderSeasonal() {
            const hits = currentHits;
            const tbody = document.getElementById('seasonal-tbody');
            tbody.innerHTML = '';

            // Group by competitor
            const byCompetitor = {};
            hits.forEach(h => {
                const cid = h.competitor_id;
                if (!byCompetitor[cid]) byCompetitor[cid] = [];
                byCompetitor[cid].push(h);
            });

            // Find max for color intensity
            let maxDayCount = 0;
            competitors.forEach(c => {
                const dayCounts = Array(7).fill(0);
                (byCompetitor[c.id] || []).forEach(h => dayCounts[new Date(h.received_at).getDay()]++);
                maxDayCount = Math.max(maxDayCount, ...dayCounts);
            });

            competitors.forEach((c, idx) => {
                const color = COLORS[idx % COLORS.length];
                const compHits = byCompetitor[c.id] || [];
                const dayCounts = Array(7).fill(0);
                compHits.forEach(h => dayCounts[new Date(h.received_at).getDay()]++);

                const peakDay = dayCounts.indexOf(Math.max(...dayCounts));
                const total = compHits.length;

                // Reorder: Mon=1 to Sun=0 -> Mon, Tue, Wed, Thu, Fri, Sat, Sun
                const ordered = [dayCounts[1], dayCounts[2], dayCounts[3], dayCounts[4], dayCounts[5], dayCounts[6], dayCounts[0]];

                let cells = ordered.map(val => {
                    const intensity = getIntensityColor(val, maxDayCount);
                    return `<td class="py-2 px-2 text-center ${intensity} rounded">${val || '-'}</td>`;
                }).join('');

                tbody.innerHTML += `
                    <tr class="hover:bg-slate-50">
                        <td class="py-2 px-4 font-medium ${color.text}">${c.shortName || c.name}</td>
                        ${cells}
                        <td class="py-2 px-4 text-center font-bold text-purple-600">${total}</td>
                        <td class="py-2 px-4 text-center font-bold text-blue-600">${total > 0 ? getDayName(peakDay) : 'N/A'}</td>
                    </tr>
                `;
            });

            // Day of week chart by competitor
            if (charts['dow']) charts['dow'].destroy();
            const ctx = document.getElementById('chart-dow').getContext('2d');

            const datasets = competitors.map((c, idx) => {
                const compHits = byCompetitor[c.id] || [];
                const dayCounts = Array(7).fill(0);
                compHits.forEach(h => dayCounts[new Date(h.received_at).getDay()]++);
                // Reorder for Mon-Sun
                const ordered = [dayCounts[1], dayCounts[2], dayCounts[3], dayCounts[4], dayCounts[5], dayCounts[6], dayCounts[0]];
                return {
                    label: c.shortName || c.name,
                    data: ordered,
                    backgroundColor: COLORS[idx % COLORS.length].accent
                };
            });

            charts['dow'] = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });

            // Holiday Communication Timeline
            renderHolidayTimeline(byCompetitor);
        }

        function renderHolidayTimeline(byCompetitor) {
            // Define major holidays (month-day format for matching)
            const HOLIDAYS = [
                { name: 'New Year', date: '01-01', color: '#8B5CF6' },
                { name: "Valentine's", date: '02-14', color: '#EC4899' },
                { name: 'Easter', date: '04-09', color: '#22C55E' },     // Approximate
                { name: 'Black Friday', date: '11-24', color: '#F59E0B' }, // Approximate
                { name: 'Cyber Monday', date: '11-27', color: '#3B82F6' }, // Approximate
                { name: 'Christmas', date: '12-25', color: '#EF4444' },
                { name: 'Boxing Day', date: '12-26', color: '#10B981' }
            ];

            const hits = currentHits;
            const holidayData = {};

            // Initialize holiday counters
            HOLIDAYS.forEach(h => { holidayData[h.name] = { before: 0, during: 0, after: 0, color: h.color }; });

            // Analyze hits around holidays (±3 days)
            hits.forEach(hit => {
                const hitDate = new Date(hit.received_at);
                const mmdd = `${String(hitDate.getMonth() + 1).padStart(2, '0')}-${String(hitDate.getDate()).padStart(2, '0')}`;

                HOLIDAYS.forEach(hol => {
                    const [hMonth, hDay] = hol.date.split('-').map(Number);
                    const holidayDate = new Date(hitDate.getFullYear(), hMonth - 1, hDay);
                    const diffDays = Math.round((hitDate - holidayDate) / (1000 * 60 * 60 * 24));

                    if (diffDays >= -3 && diffDays <= 3) {
                        if (diffDays < 0) holidayData[hol.name].before++;
                        else if (diffDays === 0) holidayData[hol.name].during++;
                        else holidayData[hol.name].after++;
                    }
                });
            });

            // Render chart
            if (charts['holiday']) charts['holiday'].destroy();
            const ctx = document.getElementById('chart-holiday')?.getContext('2d');
            if (ctx) {
                const labels = HOLIDAYS.map(h => h.name);
                const hasData = Object.values(holidayData).some(d => d.before + d.during + d.after > 0);

                charts['holiday'] = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: hasData ? labels : ['No holiday data available'],
                        datasets: hasData ? [
                            { label: 'Before (-3d)', data: HOLIDAYS.map(h => holidayData[h.name].before), backgroundColor: '#94A3B8' },
                            { label: 'On Day', data: HOLIDAYS.map(h => holidayData[h.name].during), backgroundColor: HOLIDAYS.map(h => h.color) },
                            { label: 'After (+3d)', data: HOLIDAYS.map(h => holidayData[h.name].after), backgroundColor: '#64748B' }
                        ] : [{ data: [0], backgroundColor: '#E2E8F0' }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom', display: hasData } },
                        scales: { x: { stacked: true }, y: { stacked: true, title: { display: true, text: 'Emails' } } }
                    }
                });

                // Render legend
                const legendEl = document.getElementById('holiday-legend');
                if (legendEl && hasData) {
                    legendEl.innerHTML = HOLIDAYS.map(h => {
                        const total = holidayData[h.name].before + holidayData[h.name].during + holidayData[h.name].after;
                        return total > 0 ? `<span class="px-2 py-1 rounded text-xs font-medium bg-white border" style="border-color: ${h.color}; color: ${h.color}"><i class="fa-solid fa-circle mr-1"></i>${h.name}: ${total}</span>` : '';
                    }).filter(Boolean).join('');
                }
            }
        }

        async function loadAndRenderAll() {
            console.log('[1.5] loadAndRenderAll called');
            
            try {
                // Use Laravel competitors if available
                if (laravelCompetitors.length > 0) {
                    competitors = laravelCompetitors.map(c => ({
                        id: c.competitor_id,
                        name: c.competitor_name,
                        shortName: c.short_name,
                    }));
                    console.log('[1.5] Using Laravel competitors:', competitors.length);
                } else {
                    // Get market competitors (all in current jurisdiction)
                    const marketId = window.CRMT?.navBar?.jurisdiction || localStorage.getItem('crmt_market') || 'TR-ALL';
                    try {
                        const result = await window.CRMT.dal.getCompetitors(marketId);
                        marketCompetitors = result.data || result || [];
                        console.log('[1.5] Market competitors:', marketCompetitors.length);
                    } catch (e) {
                        console.warn('[1.5] Could not fetch market competitors:', e);
                        marketCompetitors = competitors; // Fallback to group
                    }
                }

                // Fetch hits for selected group
                currentHits = await fetchHitsData();
                console.log('[1.5] Loaded', currentHits.length, 'hits for selected group');

                // If no hits and no competitors, show error
                if (currentHits.length === 0 && competitors.length === 0) {
                    console.warn('[1.5] No data available');
                    document.getElementById('loading-overlay')?.classList.add('hidden');
                    return;
                }

                // Use same hits for market (can be improved later)
                marketHits = currentHits;
                marketCompetitors = competitors;

                populateFocusDropdown();
                await renderCalendar();
                await renderSeasonal();
                checkHash();

                // Hide loading overlay
                const overlay = document.getElementById('loading-overlay');
                if (overlay) {
                    overlay.classList.add('hidden');
                    console.log('[1.5] Loading overlay hidden');
                }
            } catch (error) {
                console.error('[1.5] Error in loadAndRenderAll:', error);
                document.getElementById('loading-overlay')?.classList.add('hidden');
            }
        }

        async function handleDataLoaderChange() {
            competitors = window.getActiveCompetitorsForReport?.() || [];
            console.log('[1.5] DataLoader changed, competitors:', competitors.length);
            if (competitors.length > 0) {
                await loadAndRenderAll();
            }
        }

        async function initDashboard() {
            console.log('[1.5] initDashboard called');
            console.log('[1.5] laravelCompetitors.length:', laravelCompetitors.length);
            console.log('[1.5] laravelHits.length:', laravelHits.length);
            console.log('[1.5] laravelData keys:', Object.keys(laravelData || {}));
            
            // If Laravel data is available, use it immediately
            if (laravelCompetitors && laravelCompetitors.length > 0) {
                console.log('[1.5] Initializing with Laravel competitors data...');
                try {
                    await loadAndRenderAll();
                } catch (error) {
                    console.error('[1.5] Error in loadAndRenderAll:', error);
                    // Hide loading overlay even on error
                    document.getElementById('loading-overlay')?.classList.add('hidden');
                }
                return;
            }
            
            // Also check if we have hits data
            if (laravelHits && laravelHits.length > 0) {
                console.log('[1.5] Initializing with Laravel hits data...');
                try {
                    await loadAndRenderAll();
                } catch (error) {
                    console.error('[1.5] Error in loadAndRenderAll:', error);
                    document.getElementById('loading-overlay')?.classList.add('hidden');
                }
                return;
            }

            // Otherwise, wait for CRMT dataLoader
            console.log('[1.5] No Laravel data, waiting for CRMT...');
            if (!window.CRMT?.dataLoader || !window.getActiveCompetitorsForReport) {
                console.log('[1.5] CRMT not ready, retrying...');
                setTimeout(initDashboard, 200);
                return;
            }

            // Set up event listener for future updates
            window.addEventListener('dataLoaderChange', handleDataLoaderChange);

            // Get selected group competitors - may be empty on initial load
            competitors = window.getActiveCompetitorsForReport?.() || [];
            console.log('[1.5] Initial competitors:', competitors.length);

            // If no competitors yet, wait for dataLoaderChange event (already set up)
            if (competitors.length === 0) {
                console.log('[1.5] Waiting for dataLoader to provide competitors...');
                // Hide loading overlay after timeout
                setTimeout(() => {
                    document.getElementById('loading-overlay')?.classList.add('hidden');
                }, 5000);
                return;
            }

            try {
                await loadAndRenderAll();
            } catch (error) {
                console.error('[1.5] Error in loadAndRenderAll:', error);
                document.getElementById('loading-overlay')?.classList.add('hidden');
            }
        }

        // Initialize immediately
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                console.log('[1.5] DOMContentLoaded - Initializing...');
                console.log('[1.5] Laravel competitors:', laravelCompetitors.length);
                console.log('[1.5] Laravel hits:', laravelHits.length);
                initDashboard();
            });
        } else {
            // DOM already loaded, initialize immediately
            console.log('[1.5] DOM already loaded - Initializing immediately...');
            console.log('[1.5] Laravel competitors:', laravelCompetitors.length);
            console.log('[1.5] Laravel hits:', laravelHits.length);
            initDashboard();
        }
    </script>
@endpush
</div>
@endsection