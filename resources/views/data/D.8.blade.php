@extends('layouts.dashboard')


@section('title', 'D.8 Tracking Manager | CRMTracker')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
        }

        .stage-badge {
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 9999px;
            font-weight: 600;
        }

        .stage-acq {
            background: #dcfce7;
            color: #166534;
        }

        .stage-ret {
            background: #dbeafe;
            color: #1e40af;
        }

        .stage-rea {
            background: #fef3c7;
            color: #92400e;
        }

        .stage-none {
            background: #f1f5f9;
            color: #64748b;
        }
</style>
@endpush

@section('content')
<main class="flex-1 ml-20 p-8 max-w-[1800px] mx-auto overflow-x-hidden">
<header class="flex justify-between items-center mb-6">
<div class="flex items-center gap-3">
<span
class="text-xs font-medium bg-purple-50 text-purple-700 px-2 py-1 rounded border border-purple-200">
Data Module D.8
</span>
<h1 class="text-2xl font-bold text-slate-800">Tracking Manager</h1>
<span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">
<i class="fa-solid fa-leaf mr-1"></i>Lifecycle
</span>
</div>
<div class="flex items-center gap-3">
<select id="filter-market" onchange="updateJurisdictionFilter(); loadTrackings()"
class="bg-slate-50 border border-slate-200 text-sm rounded-lg px-3 py-2">
<option value="">All Markets</option>
</select>
<select id="filter-jurisdiction" onchange="loadTrackings()"
class="bg-slate-50 border border-slate-200 text-sm rounded-lg px-3 py-2">
<option value="">All Jurisdictions</option>
</select>
<select id="filter-stage" onchange="loadTrackings()"
class="bg-slate-50 border border-slate-200 text-sm rounded-lg px-3 py-2">
<option value="">All Stages</option>
<option value="ACQ">?? Acquisition (ACQ)</option>
<option value="RET">?? Retention (RET)</option>
<option value="REA">?? Reactivation (REA)</option>
</select>
<button onclick="applyBulkStage()" id="bulk-apply-btn" disabled
class="px-4 py-2 rounded-lg font-medium shadow-sm flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white disabled:bg-slate-300 disabled:cursor-not-allowed">
<i class="fa-solid fa-tags"></i> Apply Stage
</button>
</div>
</header>
<!-- Stats Cards -->
<div class="grid grid-cols-4 gap-4 mb-6">
<div class="bg-white rounded-xl border border-slate-200 p-4">
<div class="text-xs text-slate-500 uppercase font-medium">Total Trackings</div>
<div class="text-2xl font-bold text-slate-800 mt-1" id="stat-total">-</div>
</div>
<div class="bg-green-50 rounded-xl border border-green-200 p-4">
<div class="text-xs text-green-700 uppercase font-medium">Acquisition</div>
<div class="text-2xl font-bold text-green-800 mt-1" id="stat-acq">-</div>
</div>
<div class="bg-blue-50 rounded-xl border border-blue-200 p-4">
<div class="text-xs text-blue-700 uppercase font-medium">Retention</div>
<div class="text-2xl font-bold text-blue-800 mt-1" id="stat-ret">-</div>
</div>
<div class="bg-amber-50 rounded-xl border border-amber-200 p-4">
<div class="text-xs text-amber-700 uppercase font-medium">Reactivation</div>
<div class="text-2xl font-bold text-amber-800 mt-1" id="stat-rea">-</div>
</div>
</div>
<!-- Bulk Stage Selector -->
<div id="bulk-bar"
class="hidden bg-purple-50 border border-purple-200 rounded-xl p-4 mb-4 flex items-center justify-between">
<div class="flex items-center gap-3">
<span class="font-medium text-purple-800"><span id="selected-count">0</span> trackings
selected</span>
<select id="bulk-stage-select"
class="bg-white border border-purple-300 rounded-lg px-3 py-1.5 text-sm">
<option value="">Select Stage...</option>
<option value="ACQ">?? ACQ - Acquisition</option>
<option value="RET">?? RET - Retention</option>
<option value="REA">?? REA - Reactivation</option>
</select>
</div>
<button onclick="applyBulkStage()"
class="px-4 py-2 rounded-lg font-medium bg-purple-600 hover:bg-purple-700 text-white">
<i class="fa-solid fa-check mr-2"></i>Apply to Selected
</button>
</div>
<!-- Trackings Table -->
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
<div
class="p-4 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-purple-50 flex items-center justify-between">
<div>
<h2 class="font-semibold text-slate-700">
<i class="fa-solid fa-calendar-days mr-2 text-purple-600"></i>
Tracking Periods
</h2>
<p class="text-xs text-slate-500 mt-1">Assign lifecycle stages to competitor tracking periods
</p>
</div>
<label class="flex items-center gap-2 text-sm text-slate-600">
<input type="checkbox" id="select-all" onchange="toggleSelectAll()" class="w-4 h-4 rounded">
Select All
</label>
</div>
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-50 text-xs uppercase text-slate-500">
<tr>
<th class="px-4 py-3 w-10"></th>
<th class="px-4 py-3 text-left font-semibold">Tracking ID</th>
<th class="px-4 py-3 text-left font-semibold">Competitor</th>
<th class="px-4 py-3 text-center font-semibold">Market</th>
<th class="px-4 py-3 text-center font-semibold">Hits</th>
<th class="px-4 py-3 text-center font-semibold">Date Range</th>
<th class="px-4 py-3 text-center font-semibold">Lifecycle</th>
<th class="px-4 py-3 text-center font-semibold">VIP Tier</th>
</tr>
</thead>
<tbody id="trackings-table-body">
<tr>
<td colspan="8" class="text-center py-12 text-slate-400">
<i class="fa-solid fa-spinner fa-spin text-2xl mb-2"></i>
<p>Loading trackings...</p>
</td>
</tr>
</tbody>
</table>
</div>
</div>
<!-- Legend -->
<div class="mt-6 flex items-center gap-6 text-xs text-slate-500">
<span class="flex items-center gap-2">
<span class="stage-badge stage-acq">ACQ</span> Acquisition (New Reg, Days 0-3)
</span>
<span class="flex items-center gap-2">
<span class="stage-badge stage-ret">RET</span> Retention (Active Player)
</span>
<span class="flex items-center gap-2">
<span class="stage-badge stage-rea">REA</span> Reactivation (Dormant 14+ days)
</span>
</div>
</main>
</div>
<script>
// State
let trackings = [];
let selectedIds = new Set();
// Initialize using reportBase (Phase 2 migration)
document.addEventListener('DOMContentLoaded', async () => {
await populateMarketFilter();
await loadTrackings();
// Set up reportBase event handling for future changes
CRMT.reportBase.init({
reportId: 'D.8-tracking-manager',
onLoad: loadTrackings
});
});
async function loadTrackings() {
const stageFilter = document.getElementById('filter-stage').value;
try {
trackings = await CRMT.dal.getTrackingsByLifecycle(stageFilter || null);
} catch (e) {
console.warn('Failed to load trackings:', e);
trackings = [];
}
renderTable();
updateStats();
}
function renderTable() {
const tbody = document.getElementById('trackings-table-body');
if (trackings.length === 0) {
tbody.innerHTML = `
<tr>
<td colspan="8" class="text-center py-12 text-slate-400">
<i class="fa-solid fa-calendar-xmark text-4xl mb-3"></i>
<p class="font-medium">No trackings found</p>
<p class="text-xs mt-1">Import trackings via the hits upload or create them manually</p>
</td>
</tr>
`;
return;
}
tbody.innerHTML = trackings.map((t, index) => {
const isSelected = selectedIds.has(t.id);
const startDate = t.start_date ? new Date(t.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) : '-';
const endDate = t.end_date ? new Date(t.end_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) : '-';
const dateRange = t.start_date && t.end_date ? `${startDate} ? ${endDate}` : '-';
const hitCount = t.hit_count || 0;
const marketDisplay = t.country_flag ? `${t.country_flag} ${t.jurisdiction_name || t.country_name || ''}` : '-';
// Display row number starting from 1, or use numeric id if available
const displayId = /^\d+$/.test(t.id) ? t.id : (index + 1);
return `
<tr class="border-b border-slate-100 hover:bg-slate-50 ${isSelected ? 'bg-purple-50' : ''}" data-id="${t.id}">
<td class="px-4 py-3">
<input type="checkbox" class="tracking-checkbox w-4 h-4 rounded"
${isSelected ? 'checked' : ''} onchange="toggleSelect('${t.id}')">
</td>
<td class="px-4 py-3 font-mono text-xs text-slate-600">${displayId}</td>
<td class="px-4 py-3 font-medium">${t.competitor_name || t.competitor_id}</td>
<td class="px-4 py-3 text-center text-sm">${marketDisplay}</td>
<td class="px-4 py-3 text-center">
<span class="inline-flex items-center justify-center w-10 h-6 bg-slate-100 text-slate-700 rounded text-xs font-medium">${hitCount}</span>
</td>
<td class="px-4 py-3 text-center text-xs text-slate-500">${dateRange}</td>
<td class="px-4 py-3 text-center">
<select class="stage-select bg-transparent border border-slate-200 rounded-lg px-2 py-1 text-xs font-medium"
data-id="${t.id}" onchange="updateStage('${t.id}', this.value)">
<option value="" ${!t.lifecycle_stage ? 'selected' : ''}>Not Set</option>
<option value="ACQ" ${t.lifecycle_stage === 'ACQ' ? 'selected' : ''}>?? ACQ</option>
<option value="RET" ${t.lifecycle_stage === 'RET' ? 'selected' : ''}>?? RET</option>
<option value="REA" ${t.lifecycle_stage === 'REA' ? 'selected' : ''}>?? REA</option>
</select>
</td>
<td class="px-4 py-3 text-center">
<input type="text" class="w-20 px-2 py-1 border border-slate-200 rounded text-xs text-center"
value="${t.vip_tier || ''}" placeholder="VIP" 
onchange="updateVipTier('${t.id}', this.value)">
</td>
</tr>
`;
}).join('');
}
// Populate market filter dropdown
async function populateMarketFilter() {
try {
const data = await CRMT.dal.getMarketsGrouped();
const marketSelect = document.getElementById('filter-market');
if (!marketSelect || !data.countries) return;
marketSelect.innerHTML = '<option value="">All Markets</option>' +
data.countries.map(c => `<option value="${c.code}">${c.flag} ${c.name}</option>`).join('');
} catch (e) {
console.warn('Failed to load markets:', e);
}
}
async function updateJurisdictionFilter() {
const marketSelect = document.getElementById('filter-market');
const jurisdictionSelect = document.getElementById('filter-jurisdiction');
const selectedCountry = marketSelect?.value;
if (!selectedCountry) {
jurisdictionSelect.innerHTML = '<option value="">All Jurisdictions</option>';
return;
}
// Fetch jurisdictions for selected country
try {
const data = await CRMT.dal.getMarketsGrouped();
const country = data.countries?.find(c => c.code === selectedCountry);
if (country && country.jurisdictions) {
jurisdictionSelect.innerHTML = '<option value="">All Jurisdictions</option>' +
country.jurisdictions.map(j =>
`<option value="${j.marketId}">${j.name}</option>`
).join('');
} else {
jurisdictionSelect.innerHTML = '<option value="">All Jurisdictions</option>';
}
} catch (e) {
console.warn('Failed to load jurisdictions:', e);
jurisdictionSelect.innerHTML = '<option value="">All Jurisdictions</option>';
}
}
function updateStats() {
const total = trackings.length;
const acq = trackings.filter(t => t.lifecycle_stage === 'ACQ').length;
const ret = trackings.filter(t => t.lifecycle_stage === 'RET').length;
const rea = trackings.filter(t => t.lifecycle_stage === 'REA').length;
document.getElementById('stat-total').textContent = total;
document.getElementById('stat-acq').textContent = acq;
document.getElementById('stat-ret').textContent = ret;
document.getElementById('stat-rea').textContent = rea;
}
function toggleSelect(id) {
if (selectedIds.has(id)) {
selectedIds.delete(id);
} else {
selectedIds.add(id);
}
updateBulkBar();
renderTable();
}
function toggleSelectAll() {
const selectAll = document.getElementById('select-all').checked;
if (selectAll) {
trackings.forEach(t => selectedIds.add(t.id));
} else {
selectedIds.clear();
}
updateBulkBar();
renderTable();
}
function updateBulkBar() {
const bar = document.getElementById('bulk-bar');
const btn = document.getElementById('bulk-apply-btn');
const count = selectedIds.size;
document.getElementById('selected-count').textContent = count;
if (count > 0) {
bar.classList.remove('hidden');
btn.disabled = false;
} else {
bar.classList.add('hidden');
btn.disabled = true;
}
}
async function updateStage(id, stage) {
try {
await CRMT.dal.updateTrackingLifecycle(id, stage || null, null);
// Update local state
const t = trackings.find(t => t.id === id);
if (t) t.lifecycle_stage = stage || null;
updateStats();
} catch (e) {
console.error('Failed to update stage:', e);
alert('Failed to update stage: ' + e.message);
}
}
async function updateVipTier(id, tier) {
try {
const t = trackings.find(t => t.id === id);
await CRMT.dal.updateTrackingLifecycle(id, t?.lifecycle_stage || null, tier || null);
if (t) t.vip_tier = tier || null;
} catch (e) {
console.error('Failed to update VIP tier:', e);
}
}
async function applyBulkStage() {
const stage = document.getElementById('bulk-stage-select').value;
if (!stage) {
alert('Please select a stage first');
return;
}
if (selectedIds.size === 0) {
alert('No trackings selected');
return;
}
let success = 0;
let errors = 0;
for (const id of selectedIds) {
try {
await CRMT.dal.updateTrackingLifecycle(id, stage, null);
const t = trackings.find(t => t.id === id);
if (t) t.lifecycle_stage = stage;
success++;
} catch (e) {
errors++;
}
}
selectedIds.clear();
updateBulkBar();
renderTable();
updateStats();
alert(`Applied ${stage} to ${success} trackings${errors > 0 ? ` (${errors} failed)` : ''}`);
}
</script>
@endsection

@push('page-scripts')
<script>
        // State
        let trackings = [];
        let selectedIds = new Set();

        // Initialize using reportBase (Phase 2 migration)
        document.addEventListener('DOMContentLoaded', async () => {
            await populateMarketFilter();
            await loadTrackings();

            // Set up reportBase event handling for future changes
            CRMT.reportBase.init({
                reportId: 'D.8-tracking-manager',
                onLoad: loadTrackings
            });
        });

        async function loadTrackings() {
            const stageFilter = document.getElementById('filter-stage').value;

            try {
                trackings = await CRMT.dal.getTrackingsByLifecycle(stageFilter || null);
            } catch (e) {
                console.warn('Failed to load trackings:', e);
                trackings = [];
            }

            renderTable();
            updateStats();
        }

        function renderTable() {
            const tbody = document.getElementById('trackings-table-body');

            if (trackings.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center py-12 text-slate-400">
                            <i class="fa-solid fa-calendar-xmark text-4xl mb-3"></i>
                            <p class="font-medium">No trackings found</p>
                            <p class="text-xs mt-1">Import trackings via the hits upload or create them manually</p>
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = trackings.map((t, index) => {
                const isSelected = selectedIds.has(t.id);
                const startDate = t.start_date ? new Date(t.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) : '-';
                const endDate = t.end_date ? new Date(t.end_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) : '-';
                const dateRange = t.start_date && t.end_date ? `${startDate} ? ${endDate}` : '-';
                const hitCount = t.hit_count || 0;
                const marketDisplay = t.country_flag ? `${t.country_flag} ${t.jurisdiction_name || t.country_name || ''}` : '-';
                // Display row number starting from 1, or use numeric id if available
                const displayId = /^\d+$/.test(t.id) ? t.id : (index + 1);

                return `
                    <tr class="border-b border-slate-100 hover:bg-slate-50 ${isSelected ? 'bg-purple-50' : ''}" data-id="${t.id}">
                        <td class="px-4 py-3">
                            <input type="checkbox" class="tracking-checkbox w-4 h-4 rounded"
                                ${isSelected ? 'checked' : ''} onchange="toggleSelect('${t.id}')">
                        </td>
                        <td class="px-4 py-3 font-mono text-xs text-slate-600">${displayId}</td>
                        <td class="px-4 py-3 font-medium">${t.competitor_name || t.competitor_id}</td>
                        <td class="px-4 py-3 text-center text-sm">${marketDisplay}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-10 h-6 bg-slate-100 text-slate-700 rounded text-xs font-medium">${hitCount}</span>
                        </td>
                        <td class="px-4 py-3 text-center text-xs text-slate-500">${dateRange}</td>
                        <td class="px-4 py-3 text-center">
                            <select class="stage-select bg-transparent border border-slate-200 rounded-lg px-2 py-1 text-xs font-medium"
                                data-id="${t.id}" onchange="updateStage('${t.id}', this.value)">
                                <option value="" ${!t.lifecycle_stage ? 'selected' : ''}>Not Set</option>
                                <option value="ACQ" ${t.lifecycle_stage === 'ACQ' ? 'selected' : ''}>?? ACQ</option>
                                <option value="RET" ${t.lifecycle_stage === 'RET' ? 'selected' : ''}>?? RET</option>
                                <option value="REA" ${t.lifecycle_stage === 'REA' ? 'selected' : ''}>?? REA</option>
                            </select>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <input type="text" class="w-20 px-2 py-1 border border-slate-200 rounded text-xs text-center"
                                value="${t.vip_tier || ''}" placeholder="VIP" 
                                onchange="updateVipTier('${t.id}', this.value)">
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Populate market filter dropdown
        async function populateMarketFilter() {
            try {
                const data = await CRMT.dal.getMarketsGrouped();
                const marketSelect = document.getElementById('filter-market');
                if (!marketSelect || !data.countries) return;

                marketSelect.innerHTML = '<option value="">All Markets</option>' +
                    data.countries.map(c => `<option value="${c.code}">${c.flag} ${c.name}</option>`).join('');
            } catch (e) {
                console.warn('Failed to load markets:', e);
            }
        }

        async function updateJurisdictionFilter() {
            const marketSelect = document.getElementById('filter-market');
            const jurisdictionSelect = document.getElementById('filter-jurisdiction');
            const selectedCountry = marketSelect?.value;

            if (!selectedCountry) {
                jurisdictionSelect.innerHTML = '<option value="">All Jurisdictions</option>';
                return;
            }

            // Fetch jurisdictions for selected country
            try {
                const data = await CRMT.dal.getMarketsGrouped();
                const country = data.countries?.find(c => c.code === selectedCountry);

                if (country && country.jurisdictions) {
                    jurisdictionSelect.innerHTML = '<option value="">All Jurisdictions</option>' +
                        country.jurisdictions.map(j =>
                            `<option value="${j.marketId}">${j.name}</option>`
                        ).join('');
                } else {
                    jurisdictionSelect.innerHTML = '<option value="">All Jurisdictions</option>';
                }
            } catch (e) {
                console.warn('Failed to load jurisdictions:', e);
                jurisdictionSelect.innerHTML = '<option value="">All Jurisdictions</option>';
            }
        }

        function updateStats() {
            const total = trackings.length;
            const acq = trackings.filter(t => t.lifecycle_stage === 'ACQ').length;
            const ret = trackings.filter(t => t.lifecycle_stage === 'RET').length;
            const rea = trackings.filter(t => t.lifecycle_stage === 'REA').length;

            document.getElementById('stat-total').textContent = total;
            document.getElementById('stat-acq').textContent = acq;
            document.getElementById('stat-ret').textContent = ret;
            document.getElementById('stat-rea').textContent = rea;
        }

        function toggleSelect(id) {
            if (selectedIds.has(id)) {
                selectedIds.delete(id);
            } else {
                selectedIds.add(id);
            }
            updateBulkBar();
            renderTable();
        }

        function toggleSelectAll() {
            const selectAll = document.getElementById('select-all').checked;
            if (selectAll) {
                trackings.forEach(t => selectedIds.add(t.id));
            } else {
                selectedIds.clear();
            }
            updateBulkBar();
            renderTable();
        }

        function updateBulkBar() {
            const bar = document.getElementById('bulk-bar');
            const btn = document.getElementById('bulk-apply-btn');
            const count = selectedIds.size;

            document.getElementById('selected-count').textContent = count;

            if (count > 0) {
                bar.classList.remove('hidden');
                btn.disabled = false;
            } else {
                bar.classList.add('hidden');
                btn.disabled = true;
            }
        }

        async function updateStage(id, stage) {
            try {
                await CRMT.dal.updateTrackingLifecycle(id, stage || null, null);
                // Update local state
                const t = trackings.find(t => t.id === id);
                if (t) t.lifecycle_stage = stage || null;
                updateStats();
            } catch (e) {
                console.error('Failed to update stage:', e);
                alert('Failed to update stage: ' + e.message);
            }
        }

        async function updateVipTier(id, tier) {
            try {
                const t = trackings.find(t => t.id === id);
                await CRMT.dal.updateTrackingLifecycle(id, t?.lifecycle_stage || null, tier || null);
                if (t) t.vip_tier = tier || null;
            } catch (e) {
                console.error('Failed to update VIP tier:', e);
            }
        }

        async function applyBulkStage() {
            const stage = document.getElementById('bulk-stage-select').value;
            if (!stage) {
                alert('Please select a stage first');
                return;
            }

            if (selectedIds.size === 0) {
                alert('No trackings selected');
                return;
            }

            let success = 0;
            let errors = 0;

            for (const id of selectedIds) {
                try {
                    await CRMT.dal.updateTrackingLifecycle(id, stage, null);
                    const t = trackings.find(t => t.id === id);
                    if (t) t.lifecycle_stage = stage;
                    success++;
                } catch (e) {
                    errors++;
                }
            }

            selectedIds.clear();
            updateBulkBar();
            renderTable();
            updateStats();

            alert(`Applied ${stage} to ${success} trackings${errors > 0 ? ` (${errors} failed)` : ''}`);
        }
    </script>
@endpush
