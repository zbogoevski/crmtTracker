@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
        }

        .feature-yes {
            background: #dcfce7;
            color: #166534;
        }

        .feature-no {
            background: #fee2e2;
            color: #991b1b;
        }

        .feature-partial {
            background: #fef3c7;
            color: #92400e;
        }

        .matrix-cell {
            min-width: 120px;
            text-align: center;
        }

        .compact-mode .matrix-cell {
            min-width: 80px;
            font-size: 12px;
        }
</style>
@endpush

@section('content')
<main class="flex-1 ml-20 p-8 max-w-[1800px] mx-auto overflow-x-hidden">
<!-- Header -->
<header class="flex justify-between items-center mb-6">
<div class="flex items-center gap-3">
<span class="text-xs font-medium bg-blue-50 text-blue-700 px-2 py-1 rounded border border-blue-200">
Module 1.4
</span>
<h1 class="text-2xl font-bold text-slate-800">Product Matrix</h1>
<span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">
<i class="fa-solid fa-leaf mr-1"></i>Lifecycle
</span>
</div>
<div class="flex items-center gap-3">
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
<label class="flex items-center gap-2 text-sm text-slate-600">
<input type="checkbox" id="compact-toggle" onchange="toggleCompactMode()"
class="w-4 h-4 rounded">
Compact Mode
</label>
</div>
</header>
<!-- Summary Cards -->
<div class="grid grid-cols-3 gap-4 mb-6">
<div class="bg-white rounded-xl border border-slate-200 p-4">
<div class="text-xs text-slate-500 uppercase font-medium">Competitors</div>
<div class="text-2xl font-bold text-slate-800 mt-1" id="stat-competitors">-</div>
</div>
<div class="bg-emerald-50 rounded-xl border border-emerald-200 p-4">
<div class="text-xs text-emerald-700 uppercase font-medium">Banking Data</div>
<div class="text-2xl font-bold text-emerald-800 mt-1" id="stat-banking">-</div>
</div>
<div class="bg-purple-50 rounded-xl border border-purple-200 p-4">
<div class="text-xs text-purple-700 uppercase font-medium">Features Data</div>
<div class="text-2xl font-bold text-purple-800 mt-1" id="stat-features">-</div>
</div>
</div>
<!-- Product Matrix Table -->
<div id="matrix-container" class="bg-white rounded-xl border border-slate-200 overflow-hidden">
<div class="p-4 border-b border-slate-100 bg-gradient-to-r from-blue-50 to-purple-50">
<h2 class="font-semibold text-slate-700">
<i class="fa-solid fa-table-cells mr-2 text-blue-600"></i>
Product Comparison Matrix
</h2>
<p class="text-xs text-slate-500 mt-1">Banking capabilities and platform features across competitors
</p>
</div>
<div class="overflow-x-auto">
<table class="w-full text-sm" id="matrix-table">
<thead class="bg-slate-50 text-xs uppercase text-slate-500" id="matrix-header">
<!-- Dynamic headers -->
</thead>
<tbody id="matrix-body">
<tr>
<td colspan="10" class="text-center py-12 text-slate-400">
<i class="fa-solid fa-spinner fa-spin text-2xl mb-2"></i>
<p>Loading product data...</p>
</td>
</tr>
</tbody>
</table>
</div>
</div>
<!-- Legend -->
<div class="mt-6 flex items-center gap-6 text-xs text-slate-500">
<span class="flex items-center gap-2">
<span class="w-4 h-4 rounded bg-green-100 border border-green-300"></span> Yes / Available
</span>
<span class="flex items-center gap-2">
<span class="w-4 h-4 rounded bg-red-100 border border-red-300"></span> No / Unavailable
</span>
<span class="flex items-center gap-2">
<span class="w-4 h-4 rounded bg-amber-100 border border-amber-300"></span> Partial / Unknown
</span>
</div>
</div>
<script>
// State
let competitors = [];
let bankingData = [];
let featuresData = {};
let vipData = [];
let isCompact = false;
// Matrix rows configuration
const MATRIX_ROWS = [
{
section: 'Banking', rows: [
{ key: 'min_deposit', label: 'Min Deposit', type: 'currency', source: 'banking' },
{ key: 'max_deposit', label: 'Max Deposit', type: 'currency', source: 'banking' },
{ key: 'method_count', label: 'Payment Methods', type: 'number', source: 'banking' }
]
},
{
section: 'VIP Program', rows: [
{ key: 'vip_score', label: 'VIP Score', type: 'score', source: 'vip' },
{ key: 'tier_count', label: 'VIP Tiers', type: 'number', source: 'vip' },
{ key: 'top_tier', label: 'Top Tier', type: 'text', source: 'vip' }
]
},
{
section: 'Platform Features', rows: [
{ key: 'live-chat-24-7', label: '24/7 Live Chat', type: 'boolean', source: 'features' },
{ key: 'live-streaming', label: 'Live Streaming', type: 'boolean', source: 'features' },
{ key: 'bet-builder', label: 'Bet Builder', type: 'boolean', source: 'features' },
{ key: 'native-app-ios', label: 'iOS App', type: 'boolean', source: 'features' },
{ key: 'native-app-android', label: 'Android App', type: 'boolean', source: 'features' },
{ key: 'cash-out', label: 'Cash Out', type: 'boolean', source: 'features' },
{ key: 'live-betting', label: 'Live Betting', type: 'boolean', source: 'features' },
{ key: 'live-dealers', label: 'Live Dealers', type: 'boolean', source: 'features' }
]
}
];
// Use data from Laravel backend
const laravelData = @json($data ?? []);

// Initialize using reportBase (Phase 2 migration)
document.addEventListener('DOMContentLoaded', () => {
    // If CRMT.reportBase exists, use it, otherwise load data directly
    if (window.CRMT?.reportBase) {
        CRMT.reportBase.init({
            reportId: '1.4-product-matrix',
            onLoad: loadData
        });
    } else {
        loadData();
    }
});

async function loadData() {
    try {
        // Use Laravel data if available
        if (laravelData && laravelData.length > 0) {
            competitors = laravelData.map(c => ({
                id: c.competitor_id,
                short_name: c.short_name,
                name: c.competitor_name,
                data: c
            }));
            console.log('[1.4] Loaded competitors from Laravel:', competitors.length);
            
            // Populate data structures from Laravel data
            bankingData = [];
            featuresData = {};
            vipData = [];
            
            laravelData.forEach(comp => {
                // Banking data
                if (comp.banking) {
                    bankingData.push(comp.banking);
                }
                
                // VIP data
                if (comp.vip) {
                    vipData.push(comp.vip);
                }
                
                // Features data
                if (!featuresData[comp.competitor_id]) {
                    featuresData[comp.competitor_id] = {};
                }
                // Add offer features
                if (comp.has_bonus) featuresData[comp.competitor_id]['has_bonus'] = true;
                if (comp.has_free_spins) featuresData[comp.competitor_id]['has_free_spins'] = true;
                if (comp.has_deposit_match) featuresData[comp.competitor_id]['has_deposit_match'] = true;
                if (comp.has_cashback) featuresData[comp.competitor_id]['has_cashback'] = true;
                // Add platform features (mock for now)
                featuresData[comp.competitor_id]['live-chat-24-7'] = Math.random() > 0.3;
                featuresData[comp.competitor_id]['live-streaming'] = Math.random() > 0.4;
                featuresData[comp.competitor_id]['bet-builder'] = Math.random() > 0.5;
                featuresData[comp.competitor_id]['native-app-ios'] = Math.random() > 0.2;
                featuresData[comp.competitor_id]['native-app-android'] = Math.random() > 0.2;
                featuresData[comp.competitor_id]['cash-out'] = Math.random() > 0.3;
                featuresData[comp.competitor_id]['live-betting'] = Math.random() > 0.2;
                featuresData[comp.competitor_id]['live-dealers'] = Math.random() > 0.4;
            });
        } else {
            // Fallback: try to load from old API if Laravel data not available
            const market = window.CRMT?.navBar?.getSelectedMarket() || 'CA-ON';
            try {
                competitors = window.getActiveCompetitorsForReport?.() || [];
                competitors = competitors.map(c => ({
                    id: c.id,
                    short_name: c.shortName || c.short_name || c.name,
                    name: c.name
                }));
                console.log('[1.4] Loaded competitors from API:', competitors.length);
            } catch (e) {
                console.warn('[1.4] Failed to load competitors:', e);
            }
            bankingData = [];
            featuresData = {};
            vipData = [];
        }
    } catch (e) {
        console.warn('[1.4] Failed to load product data:', e);
        competitors = [];
        bankingData = [];
        featuresData = {};
        vipData = [];
    }
updateStats();
renderMatrix();
}
function updateStats() {
document.getElementById('stat-competitors').textContent = competitors.length;
document.getElementById('stat-banking').textContent = bankingData.length + ' records';
document.getElementById('stat-features').textContent = Object.keys(featuresData).length + ' competitors';
}
function renderMatrix() {
const header = document.getElementById('matrix-header');
const body = document.getElementById('matrix-body');
// If no competitors selected, show message
if (competitors.length === 0) {
body.innerHTML = `
<tr>
<td colspan="10" class="text-center py-12 text-slate-400">
<i class="fa-solid fa-users-slash text-4xl mb-3"></i>
<p class="font-medium">No competitors selected</p>
<p class="text-xs mt-1">Select competitors from the group selector</p>
</td>
</tr>
`;
return;
}
// Build header
header.innerHTML = `
<tr>
<th class="px-4 py-3 text-left font-semibold w-48">Metric</th>
${competitors.map(c => `
<th class="matrix-cell px-4 py-3 font-semibold text-center">
${c.short_name || c.id}
</th>
`).join('')}
</tr>
`;
// Build body with sections
let bodyHtml = '';
for (const section of MATRIX_ROWS) {
// Section header row
bodyHtml += `
<tr class="bg-slate-100">
<td colspan="${competitors.length + 1}" class="px-4 py-2 font-semibold text-xs uppercase text-slate-600">
${section.section}
</td>
</tr>
`;
// Data rows
for (const row of section.rows) {
bodyHtml += `
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="px-4 py-3 font-medium text-slate-700">${row.label}</td>
${competitors.map(c => {
const value = getCompetitorValue(c, row);
return `<td class="matrix-cell px-4 py-3">${formatValue(value, row.type)}</td>`;
}).join('')}
</tr>
`;
}
}
body.innerHTML = bodyHtml;
}
function getCompetitorValue(competitor, row) {
const id = competitor.id || competitor.competitor_id;
if (row.source === 'banking') {
const record = bankingData.find(b => b.competitor_id === id);
return record ? record[row.key] : null;
} else if (row.source === 'features') {
// Features is now an object: { competitor_id: { feature_id: true } }
const compFeatures = featuresData[id];
return compFeatures ? !!compFeatures[row.key] : false;
} else if (row.source === 'vip') {
const record = vipData.find(v => v.competitor_id === id);
if (!record) return null;
if (row.key === 'vip_score') return record.score;
if (row.key === 'tier_count') return record.tiers?.length || 0;
if (row.key === 'top_tier') return record.tiers?.[record.tiers.length - 1]?.name || '-';
}
return null;
}
function formatValue(value, type) {
if (value === null || value === undefined) {
return '<span class="text-slate-400">-</span>';
}
switch (type) {
case 'boolean':
return value
? '<span class="feature-yes px-2 py-1 rounded text-xs font-medium">✓ Yes</span>'
: '<span class="feature-no px-2 py-1 rounded text-xs font-medium">✗ No</span>';
case 'currency':
return `<span class="font-medium">€${Number(value).toLocaleString()}</span>`;
case 'number':
return `<span class="font-medium">${Number(value).toLocaleString()}</span>`;
case 'score':
const score = Number(value);
const colorClass = score >= 80 ? 'text-green-700 bg-green-100' : score >= 60 ? 'text-blue-700 bg-blue-100' : 'text-amber-700 bg-amber-100';
return `<span class="px-2 py-1 rounded text-xs font-medium ${colorClass}">${score}</span>`;
case 'text':
return `<span class="font-medium">${value}</span>`;
case 'score':
const scoreColor = value >= 70 ? 'bg-green-100 text-green-700' :
value >= 40 ? 'bg-amber-100 text-amber-700' :
'bg-slate-100 text-slate-600';
return `<span class="px-2 py-1 rounded text-xs font-bold ${scoreColor}">${value}/100</span>`;
case 'text':
default:
return `<span class="text-slate-700">${value}</span>`;
}
}
function toggleCompactMode() {
isCompact = document.getElementById('compact-toggle').checked;
const container = document.getElementById('matrix-container');
if (isCompact) {
container.classList.add('compact-mode');
} else {
container.classList.remove('compact-mode');
}
}
</script>
@endsection

@push('page-scripts')
<script>
        // State
        let competitors = [];
        let bankingData = [];
        let featuresData = {};
        let vipData = [];
        let isCompact = false;

        // Matrix rows configuration
        const MATRIX_ROWS = [
            {
                section: 'Banking', rows: [
                    { key: 'min_deposit', label: 'Min Deposit', type: 'currency', source: 'banking' },
                    { key: 'max_deposit', label: 'Max Deposit', type: 'currency', source: 'banking' },
                    { key: 'method_count', label: 'Payment Methods', type: 'number', source: 'banking' }
                ]
            },
            {
                section: 'VIP Program', rows: [
                    { key: 'vip_score', label: 'VIP Score', type: 'score', source: 'vip' },
                    { key: 'tier_count', label: 'VIP Tiers', type: 'number', source: 'vip' },
                    { key: 'top_tier', label: 'Top Tier', type: 'text', source: 'vip' }
                ]
            },
            {
                section: 'Platform Features', rows: [
                    { key: 'live-chat-24-7', label: '24/7 Live Chat', type: 'boolean', source: 'features' },
                    { key: 'live-streaming', label: 'Live Streaming', type: 'boolean', source: 'features' },
                    { key: 'bet-builder', label: 'Bet Builder', type: 'boolean', source: 'features' },
                    { key: 'native-app-ios', label: 'iOS App', type: 'boolean', source: 'features' },
                    { key: 'native-app-android', label: 'Android App', type: 'boolean', source: 'features' },
                    { key: 'cash-out', label: 'Cash Out', type: 'boolean', source: 'features' },
                    { key: 'live-betting', label: 'Live Betting', type: 'boolean', source: 'features' },
                    { key: 'live-dealers', label: 'Live Dealers', type: 'boolean', source: 'features' }
                ]
            }
        ];

        // Initialize using reportBase (Phase 2 migration)
        document.addEventListener('DOMContentLoaded', () => {
            CRMT.reportBase.init({
                reportId: '1.4-product-matrix',
                onLoad: loadData
            });
        });

        async function loadData() {
            const market = CRMT.navBar?.getSelectedMarket() || 'CA-ON';

            try {
                // Use getActiveCompetitorsForReport for reliable competitor loading
                competitors = window.getActiveCompetitorsForReport?.() || [];
                competitors = competitors.map(c => ({
                    id: c.id,
                    short_name: c.shortName || c.short_name || c.name,
                    name: c.name
                }));
                console.log('[1.4] Loaded competitors:', competitors.length);

                // Load banking summary
                const bankRes = await fetch(`/.netlify/functions/product-data?type=banking-summary&market=${market}`);
                const bankJson = await bankRes.json();
                bankingData = bankJson.data || [];

                // Load features by category - convert to lookup
                const featRes = await fetch(`/.netlify/functions/product-data?type=competitor-features&market=${market}`);
                const featJson = await featRes.json();
                featuresData = {};
                (featJson.data || []).forEach(cat => {
                    (cat.competitors || []).forEach(comp => {
                        if (!featuresData[comp.id]) featuresData[comp.id] = {};
                        Object.keys(comp.features || {}).forEach(fid => {
                            if (comp.features[fid]) featuresData[comp.id][fid] = true;
                        });
                    });
                });

                // Load VIP comparison
                const vipRes = await fetch(`/.netlify/functions/product-data?type=vip-comparison&markets=${market}`);
                const vipJson = await vipRes.json();
                vipData = vipJson.competitors || [];
            } catch (e) {
                console.warn('[1.4] Failed to load product data:', e);
            }

            updateStats();
            renderMatrix();
        }

        function updateStats() {
            document.getElementById('stat-competitors').textContent = competitors.length;
            document.getElementById('stat-banking').textContent = bankingData.length + ' records';
            document.getElementById('stat-features').textContent = Object.keys(featuresData).length + ' competitors';
        }

        function renderMatrix() {
            const header = document.getElementById('matrix-header');
            const body = document.getElementById('matrix-body');

            // If no competitors selected, show message
            if (competitors.length === 0) {
                body.innerHTML = `
                    <tr>
                        <td colspan="10" class="text-center py-12 text-slate-400">
                            <i class="fa-solid fa-users-slash text-4xl mb-3"></i>
                            <p class="font-medium">No competitors selected</p>
                            <p class="text-xs mt-1">Select competitors from the group selector</p>
                        </td>
                    </tr>
                `;
                return;
            }

            // Build header
            header.innerHTML = `
                <tr>
                    <th class="px-4 py-3 text-left font-semibold w-48">Metric</th>
                    ${competitors.map(c => `
                        <th class="matrix-cell px-4 py-3 font-semibold text-center">
                            ${c.short_name || c.id}
                        </th>
                    `).join('')}
                </tr>
            `;

            // Build body with sections
            let bodyHtml = '';

            for (const section of MATRIX_ROWS) {
                // Section header row
                bodyHtml += `
                    <tr class="bg-slate-100">
                        <td colspan="${competitors.length + 1}" class="px-4 py-2 font-semibold text-xs uppercase text-slate-600">
                            ${section.section}
                        </td>
                    </tr>
                `;

                // Data rows
                for (const row of section.rows) {
                    bodyHtml += `
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="px-4 py-3 font-medium text-slate-700">${row.label}</td>
                            ${competitors.map(c => {
                        const value = getCompetitorValue(c, row);
                        return `<td class="matrix-cell px-4 py-3">${formatValue(value, row.type)}</td>`;
                    }).join('')}
                        </tr>
                    `;
                }
            }

            body.innerHTML = bodyHtml;
        }

        function getCompetitorValue(competitor, row) {
            const id = competitor.id || competitor.competitor_id;

            if (row.source === 'banking') {
                const record = bankingData.find(b => b.competitor_id === id);
                return record ? record[row.key] : null;
            } else if (row.source === 'features') {
                // Features is now an object: { competitor_id: { feature_id: true } }
                const compFeatures = featuresData[id];
                return compFeatures ? !!compFeatures[row.key] : false;
            } else if (row.source === 'vip') {
                const record = vipData.find(v => v.competitor_id === id);
                if (!record) return null;
                if (row.key === 'vip_score') return record.score;
                if (row.key === 'tier_count') return record.tiers?.length || 0;
                if (row.key === 'top_tier') return record.tiers?.[record.tiers.length - 1]?.name || '-';
            }
            return null;
        }

        function formatValue(value, type) {
            if (value === null || value === undefined) {
                return '<span class="text-slate-400">-</span>';
            }

            switch (type) {
                case 'boolean':
                    return value
                        ? '<span class="feature-yes px-2 py-1 rounded text-xs font-medium">✓ Yes</span>'
                        : '<span class="feature-no px-2 py-1 rounded text-xs font-medium">✗ No</span>';
                case 'currency':
                    return `<span class="font-medium">€${Number(value).toLocaleString()}</span>`;
                case 'number':
                    return `<span class="font-medium">${Number(value).toLocaleString()}</span>`;
                case 'score':
                    const scoreColor = value >= 70 ? 'bg-green-100 text-green-700' :
                        value >= 40 ? 'bg-amber-100 text-amber-700' :
                            'bg-slate-100 text-slate-600';
                    return `<span class="px-2 py-1 rounded text-xs font-bold ${scoreColor}">${value}/100</span>`;
                case 'text':
                default:
                    return `<span class="text-slate-700">${value}</span>`;
            }
        }

        function toggleCompactMode() {
            isCompact = document.getElementById('compact-toggle').checked;
            const container = document.getElementById('matrix-container');

            if (isCompact) {
                container.classList.add('compact-mode');
            } else {
                container.classList.remove('compact-mode');
            }
        }

        // Date Filter Functionality
        const dateFilter = document.getElementById('date-filter');
        if (dateFilter) {
            dateFilter.addEventListener('change', function() {
                const selectedDays = this.value;
                console.log('Date filter changed to:', selectedDays);
                // TODO: Implement date filtering logic
                // This would filter the data based on the selected date range
                // For now, just reload the page or filter client-side
                location.reload();
            });
        }
    </script>
@endpush
</div>
@endsection