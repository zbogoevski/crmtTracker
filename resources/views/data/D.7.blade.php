@extends('layouts.dashboard')


@section('title', 'D.7 Product Intelligence | CRMTracker')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
            color: #334155;
        }

        .tab-btn {
            transition: all 0.2s;
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #7c3aed 0%, #6366f1 100%);
            color: white;
        }

        .editable-cell {
            min-width: 80px;
        }

        .editable-cell:focus {
            outline: 2px solid #7c3aed;
            outline-offset: -2px;
            background: #faf5ff;
        }

        .completeness-badge {
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 9999px;
        }
</style>
@endpush

@section('content')
<main class="flex-1 ml-20 p-8 max-w-[1800px] mx-auto overflow-x-hidden">
<header class="flex justify-between items-center mb-6">
<div class="flex items-center gap-3">
<span
class="text-xs font-medium bg-purple-50 text-purple-700 px-2 py-1 rounded border border-purple-200">
Data Module D.7
</span>
<h1 class="text-2xl font-bold text-slate-800">Product Intelligence</h1>
</div>
<div class="flex items-center gap-3">
<!-- Group Info -->
<div class="flex items-center gap-2 text-sm">
<span class="text-slate-500">Group:</span>
<span id="current-group-label"
class="font-medium text-purple-700 bg-purple-50 px-2 py-1 rounded">
Loading...
</span>
</div>
<!-- Show All Toggle -->
<label class="flex items-center gap-2 cursor-pointer">
<input type="checkbox" id="show-all-toggle" onchange="toggleShowAll()"
class="w-4 h-4 rounded border-slate-300 text-blue-600">
<span class="text-sm text-slate-600">Show All Market</span>
</label>
<button onclick="saveAllChanges()"
class="px-4 py-2 rounded-lg font-medium shadow-sm flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white">
<i class="fa-solid fa-save"></i> Save Changes
</button>
</div>
</header>
<!-- Tabs -->
<div class="flex gap-2 mb-6">
<button class="tab-btn active px-6 py-2.5 rounded-lg font-medium" onclick="switchTab('banking')"
id="tab-banking">
<i class="fa-solid fa-credit-card mr-2"></i>Banking
</button>
<button class="tab-btn bg-white border border-slate-200 px-6 py-2.5 rounded-lg font-medium"
onclick="switchTab('features')" id="tab-features">
<i class="fa-solid fa-puzzle-piece mr-2"></i>Features
</button>
<button class="tab-btn bg-white border border-slate-200 px-6 py-2.5 rounded-lg font-medium"
onclick="switchTab('vip')" id="tab-vip">
<i class="fa-solid fa-crown mr-2"></i>VIP Tiers
</button>
</div>
<!-- Banking Tab -->
<div id="panel-banking" class="tab-panel">
<!-- Payment Methods Matrix -->
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden mb-6">
<div
class="p-4 border-b border-slate-100 bg-gradient-to-r from-green-50 to-emerald-50 flex justify-between items-center">
<div>
<h2 class="font-semibold text-slate-700">
<i class="fa-solid fa-credit-card mr-2 text-green-600"></i>
Payment Methods Matrix
</h2>
<p class="text-xs text-slate-500 mt-1">Payment availability, limits and fees per competitor
</p>
<div id="date-range-container" class="flex items-center"></div>
<button onclick="openAddMethodModal()"
class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
<i class="fa-solid fa-plus mr-2"></i>Add Method
</button>
</div>
<div class="overflow-x-auto">
<table class="w-full text-sm" id="payment-matrix-table">
<thead class="bg-slate-50 text-xs uppercase text-slate-500" id="payment-matrix-header">
<tr>
<th
class="px-4 py-3 text-left font-semibold sticky left-0 bg-slate-50 z-10 min-w-[150px]">
Competitor</th>
<!-- Dynamic payment method columns inserted by JS -->
</tr>
</thead>
<tbody id="payment-matrix-body">
<tr>
<td colspan="20" class="text-center py-8 text-slate-400">Loading payment
methods...
</td>
</tr>
</tbody>
</table>
</div>
</div>
<!-- Banking Profiles -->
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
<div class="p-4 border-b border-slate-100 bg-gradient-to-r from-purple-50 to-indigo-50">
<h2 class="font-semibold text-slate-700">
<i class="fa-solid fa-building-columns mr-2 text-purple-600"></i>
Banking Profiles
</h2>
<p class="text-xs text-slate-500 mt-1">Payment methods, limits, and speed benchmarks per
competitor</p>
</div>
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-50 text-xs uppercase text-slate-500">
<tr>
<th class="px-4 py-3 text-left font-semibold">Competitor</th>
<th class="px-4 py-3 text-left font-semibold">Market</th>
<th class="px-4 py-3 text-center font-semibold">Min Deposit</th>
<th class="px-4 py-3 text-center font-semibold">Max Withdrawal</th>
<th class="px-4 py-3 text-center font-semibold">Speed (Adv)</th>
<th class="px-4 py-3 text-center font-semibold">Speed (Avg hrs)</th>
<th class="px-4 py-3 text-center font-semibold">Fees</th>
<th class="px-4 py-3 text-center font-semibold">Updated</th>
</tr>
</thead>
<tbody id="banking-table-body">
<tr>
<td colspan="8" class="text-center py-8 text-slate-400">Loading...</td>
</tr>
</tbody>
</table>
</div>
</div>
<!-- Banking Summary (Aggregated) -->
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden mt-6">
<div class="p-4 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-purple-50">
<h2 class="font-semibold text-slate-700">
<i class="fa-solid fa-chart-bar mr-2 text-indigo-600"></i>
Banking Summary
</h2>
<p class="text-xs text-slate-500 mt-1">Aggregated min/max deposits from payment methods</p>
</div>
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-50 text-xs uppercase text-slate-500">
<tr>
<th class="px-4 py-3 text-left font-semibold">Competitor</th>
<th class="px-4 py-3 text-center font-semibold">Min Deposit</th>
<th class="px-4 py-3 text-center font-semibold">Max Deposit</th>
<th class="px-4 py-3 text-center font-semibold">Methods Available</th>
</tr>
</thead>
<tbody id="banking-summary-body">
<tr>
<td colspan="4" class="text-center py-8 text-slate-400">Loading...</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
<!-- Features Tab -->
<div id="panel-features" class="tab-panel hidden">
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
<div
class="p-4 border-b border-slate-100 bg-gradient-to-r from-blue-50 to-cyan-50 flex justify-between items-center">
<div>
<h2 class="font-semibold text-slate-700">
<i class="fa-solid fa-puzzle-piece mr-2 text-blue-600"></i>
Platform Features by Category
</h2>
<p class="text-xs text-slate-500 mt-1">Click categories to expand/collapse</p>
</div>
<div class="flex gap-2">
<button onclick="openAddCategoryModal()"
class="px-3 py-1.5 text-xs font-medium bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg">
<i class="fa-solid fa-folder-plus mr-1"></i>Add Category
</button>
<button onclick="openAddFeatureModal()"
class="px-3 py-1.5 text-xs font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
<i class="fa-solid fa-plus mr-1"></i>Add Feature
</button>
</div>
</div>
<!-- Accordion container - dynamically populated -->
<div id="features-accordion" class="divide-y divide-slate-100">
<div class="p-8 text-center text-slate-400">Loading feature categories...</div>
</div>
</div>
</div>
<!-- VIP Tab -->
<div id="panel-vip" class="tab-panel hidden">
<!-- VIP Comparison Header -->
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
<div
class="p-4 border-b border-slate-100 bg-gradient-to-r from-amber-50 to-yellow-50 flex justify-between items-center">
<div>
<h2 class="font-semibold text-slate-700">
<i class="fa-solid fa-crown mr-2 text-amber-600"></i>
VIP Program Comparison
</h2>
<p class="text-xs text-slate-500 mt-1">Cross-market comparison with scoring</p>
</div>
<div class="flex items-center gap-2">
<span class="text-xs text-slate-500">Markets:</span>
<div id="vip-market-selector" class="flex gap-1 flex-wrap">
<!-- Dynamic market checkboxes -->
<div id="date-range-container" class="flex items-center"></div>
<button onclick="loadVipComparison()"
class="ml-2 px-3 py-1 bg-amber-600 text-white rounded text-xs font-medium hover:bg-amber-700">
Compare
</button>
</div>
</div>
<!-- VIP Comparison Table -->
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-50 text-xs uppercase text-slate-500">
<tr>
<th class="px-4 py-3 text-left font-semibold">Competitor</th>
<th class="px-4 py-3 text-left font-semibold">Market</th>
<th class="px-4 py-3 text-center font-semibold">VIP Score</th>
<th class="px-4 py-3 text-left font-semibold">Tiers</th>
<th class="px-4 py-3 text-left font-semibold">Top Perks</th>
</tr>
</thead>
<tbody id="vip-comparison-body">
<tr>
<td colspan="5" class="text-center py-8 text-slate-400">Select markets and
click
Compare</td>
</tr>
</tbody>
</table>
</div>
</div>
<!-- VIP Tier Editor (existing, below comparison) -->
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden mt-6">
<div class="p-4 border-b border-slate-100 bg-gradient-to-r from-amber-50/50 to-yellow-50/50">
<h2 class="font-semibold text-slate-700">
<i class="fa-solid fa-edit mr-2 text-amber-500"></i>
VIP Tier Editor
</h2>
<p class="text-xs text-slate-500 mt-1">Manage tiers for current market</p>
</div>
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-50 text-xs uppercase text-slate-500">
<tr>
<th class="px-4 py-3 text-left font-semibold">Competitor</th>
<th class="px-4 py-3 text-left font-semibold">Tier Name</th>
<th class="px-4 py-3 text-center font-semibold">Order</th>
<th class="px-4 py-3 text-left font-semibold">Entry Requirements</th>
<th class="px-4 py-3 text-center font-semibold">Actions</th>
</tr>
</thead>
<tbody id="vip-table-body">
<tr>
<td colspan="5" class="text-center py-8 text-slate-400">Loading...</td>
</tr>
</tbody>
</table>
</div>
<div class="p-4 border-t border-slate-100">
<button onclick="addVipTier()"
class="px-4 py-2 rounded-lg font-medium bg-amber-100 hover:bg-amber-200 text-amber-700">
<i class="fa-solid fa-plus mr-2"></i>Add VIP Tier
</button>
</div>
</div>
</div>
</main>
</div>
<script>
// State
let currentTab = 'banking';
let bankingData = [];
let featuresData = [];
let vipData = [];
let competitors = [];
let pendingChanges = { banking: {}, features: {}, vip: {} };
let showAllMarket = false; // Toggle state
// Initialize
document.addEventListener('DOMContentLoaded', async () => {
updateGroupLabel();
await loadCompetitors();
await loadData();
// Set up reportBase event handling for future changes
CRMT.reportBase?.init({
reportId: 'D.7-product-intelligence',
onLoad: async () => {
await loadCompetitors();
await loadData();
}
});
});
// Listen for group changes from navigation
window.addEventListener('navBarChange', async (e) => {
updateGroupLabel();
await loadCompetitors();
await loadData();
loadPaymentMatrix();
loadBankingSummary();
});
function updateGroupLabel() {
const label = document.getElementById('current-group-label');
if (!label) return;
const group = CRMT.navBar?.getSelectedGroup();
if (group) {
label.textContent = `${group.flag || '📁'} ${group.name}`;
} else {
label.textContent = 'No group selected';
}
}
function toggleShowAll() {
showAllMarket = document.getElementById('show-all-toggle')?.checked || false;
loadCompetitors();
loadData();
loadPaymentMatrix();
loadBankingSummary();
}
// Load competitors - from group or all market
async function loadCompetitors() {
const group = CRMT.navBar?.getSelectedGroup();
const marketId = group?.market_id || 'TR-ALL';
try {
if (showAllMarket) {
// Load ALL competitors in the market
competitors = await CRMT.dal.getCompetitors(marketId);
console.log('[D.7] Loaded', competitors.length, 'competitors (ALL market)');
} else if (group?.competitor_ids?.length) {
// Load only competitors in the active group
const allCompetitors = await CRMT.dal.getCompetitors(marketId);
competitors = allCompetitors.filter(c => group.competitor_ids.includes(c.id));
console.log('[D.7] Loaded', competitors.length, 'competitors (from group)');
} else {
// Fallback to all
competitors = await CRMT.dal.getCompetitors(marketId);
console.log('[D.7] Loaded', competitors.length, 'competitors (fallback)');
}
} catch (e) {
console.warn('[D.7] Failed to load competitors:', e);
competitors = [];
}
}
async function loadData() {
const group = CRMT.navBar?.getSelectedGroup();
const marketId = group?.market_id || null;
try {
// Load all data types
bankingData = await CRMT.dal.getBankingProfiles(null, marketId);
featuresData = await CRMT.dal.getProductFeatures(null, marketId);
vipData = await CRMT.dal.getVipStructures(null, marketId);
} catch (e) {
console.warn('Failed to load product data:', e);
bankingData = [];
featuresData = [];
vipData = [];
}
renderCurrentTab();
}
function switchTab(tab) {
currentTab = tab;
// Update tab buttons
document.querySelectorAll('.tab-btn').forEach(btn => {
btn.classList.remove('active');
btn.classList.add('bg-white', 'border', 'border-slate-200');
});
document.getElementById(`tab-${tab}`).classList.add('active');
document.getElementById(`tab-${tab}`).classList.remove('bg-white', 'border', 'border-slate-200');
// Update panels
document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.add('hidden'));
document.getElementById(`panel-${tab}`).classList.remove('hidden');
renderCurrentTab();
}
function renderCurrentTab() {
switch (currentTab) {
case 'banking':
renderBankingTable();
break;
case 'features':
renderFeaturesTable();
break;
case 'vip':
renderVipTable();
break;
}
}
function renderBankingTable() {
const tbody = document.getElementById('banking-table-body');
if (bankingData.length === 0) {
// Show competitors without data
tbody.innerHTML = competitors.slice(0, 10).map(c => `
<tr class="border-b border-slate-100 hover:bg-slate-50" data-competitor="${c.id}">
<td class="px-4 py-3 font-medium">${c.short_name || c.name}</td>
<td class="px-4 py-3 text-slate-500">${c.market_id || '-'}</td>
<td class="px-4 py-3 text-center">
<input type="number" class="editable-cell w-20 px-2 py-1 border rounded text-center" 
placeholder="€" data-field="min_deposit">
</td>
<td class="px-4 py-3 text-center">
<input type="number" class="editable-cell w-20 px-2 py-1 border rounded text-center" 
placeholder="€" data-field="max_withdrawal">
</td>
<td class="px-4 py-3 text-center">
<input type="text" class="editable-cell w-24 px-2 py-1 border rounded text-center" 
placeholder="24h" data-field="speed_advertised">
</td>
<td class="px-4 py-3 text-center">
<input type="number" class="editable-cell w-16 px-2 py-1 border rounded text-center" 
placeholder="hrs" data-field="speed_measured_avg_hours">
</td>
<td class="px-4 py-3 text-center">
<input type="text" class="editable-cell w-24 px-2 py-1 border rounded text-center" 
placeholder="Free" data-field="fee_policy">
</td>
<td class="px-4 py-3 text-center text-slate-400">-</td>
</tr>
`).join('');
} else {
tbody.innerHTML = bankingData.map(row => `
<tr class="border-b border-slate-100 hover:bg-slate-50" data-id="${row.id}" data-competitor="${row.competitor_id}">
<td class="px-4 py-3 font-medium">${row.competitor_name || row.competitor_id}</td>
<td class="px-4 py-3 text-slate-500">${row.market_name || row.market_id || 'Global'}</td>
<td class="px-4 py-3 text-center">
<input type="number" class="editable-cell w-20 px-2 py-1 border rounded text-center" 
value="${row.min_deposit || ''}" data-field="min_deposit">
</td>
<td class="px-4 py-3 text-center">
<input type="number" class="editable-cell w-20 px-2 py-1 border rounded text-center" 
value="${row.max_withdrawal || ''}" data-field="max_withdrawal">
</td>
<td class="px-4 py-3 text-center">
<input type="text" class="editable-cell w-24 px-2 py-1 border rounded text-center" 
value="${row.speed_advertised || ''}" data-field="speed_advertised">
</td>
<td class="px-4 py-3 text-center">
<input type="number" class="editable-cell w-16 px-2 py-1 border rounded text-center" 
value="${row.speed_measured_avg_hours || ''}" data-field="speed_measured_avg_hours">
</td>
<td class="px-4 py-3 text-center">
<input type="text" class="editable-cell w-24 px-2 py-1 border rounded text-center" 
value="${row.fee_policy || ''}" data-field="fee_policy">
</td>
<td class="px-4 py-3 text-center text-xs text-slate-400">
${row.updated_at ? new Date(row.updated_at).toLocaleDateString() : '-'}
</td>
</tr>
`).join('');
}
}
function renderFeaturesTable() {
const container = document.getElementById('features-accordion');
if (!container) { console.warn('[D.7] features-accordion not found'); return; }
const hdr = `<table class="w-full text-sm"><thead class="bg-slate-50 text-xs uppercase text-slate-500"><tr>
<th class="px-4 py-3 text-left">Competitor</th><th class="px-4 py-3 text-center">Stream</th>
<th class="px-4 py-3 text-center">Builder</th><th class="px-4 py-3 text-center">VIP</th>
<th class="px-4 py-3 text-center">App</th><th class="px-4 py-3 text-center">Crypto</th>
<th class="px-4 py-3 text-center">Slots</th><th class="px-4 py-3 text-center">KYC</th>
</tr></thead><tbody>`;
const ftr = `</tbody></table>`;
const data = featuresData.length > 0 ? featuresData : competitors.slice(0, 10).map(c => ({ ...c, competitor_name: c.short_name || c.name }));
const rows = data.map(r => `<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="px-4 py-3 font-medium">${r.competitor_name || r.short_name || r.name || '-'}</td>
<td class="px-4 py-3 text-center"><input type="checkbox" class="w-4 h-4" ${r.has_live_streaming ? 'checked' : ''} data-field="has_live_streaming"></td>
<td class="px-4 py-3 text-center"><input type="checkbox" class="w-4 h-4" ${r.has_bet_builder ? 'checked' : ''} data-field="has_bet_builder"></td>
<td class="px-4 py-3 text-center"><input type="checkbox" class="w-4 h-4" ${r.has_vip_program ? 'checked' : ''} data-field="has_vip_program"></td>
<td class="px-4 py-3 text-center"><input type="checkbox" class="w-4 h-4" ${r.has_native_app ? 'checked' : ''} data-field="has_native_app"></td>
<td class="px-4 py-3 text-center"><input type="checkbox" class="w-4 h-4" ${r.has_crypto ? 'checked' : ''} data-field="has_crypto"></td>
<td class="px-4 py-3 text-center"><input type="number" class="w-16 px-1 border rounded text-center" value="${r.game_count_slots || ''}"></td>
<td class="px-4 py-3 text-center"><select class="px-1 border rounded text-xs"><option>-</option>
<option ${r.kyc_trigger === 'on_registration' ? 'selected' : ''}>Reg</option>
<option ${r.kyc_trigger === 'on_deposit' ? 'selected' : ''}>Dep</option></select></td>
</tr>`).join('');
container.innerHTML = hdr + rows + ftr;
}
function renderVipTable() {
const tbody = document.getElementById('vip-table-body');
if (vipData.length === 0) {
tbody.innerHTML = `<tr><td colspan="5" class="text-center py-8 text-slate-400">
No VIP tiers defined. Click "Add VIP Tier" to create one.
</td></tr>`;
} else {
tbody.innerHTML = vipData.map(row => `
<tr class="border-b border-slate-100 hover:bg-slate-50" data-id="${row.id}">
<td class="px-4 py-3 font-medium">${row.competitor_name || row.competitor_id}</td>
<td class="px-4 py-3">
<input type="text" class="editable-cell w-24 px-2 py-1 border rounded" 
value="${row.tier_name}" data-field="tier_name">
</td>
<td class="px-4 py-3 text-center">
<input type="number" class="editable-cell w-12 px-2 py-1 border rounded text-center" 
value="${row.tier_order || 0}" data-field="tier_order">
</td>
<td class="px-4 py-3">
<input type="text" class="editable-cell w-48 px-2 py-1 border rounded" 
value="${row.entry_requirement || ''}" data-field="entry_requirement">
</td>
<td class="px-4 py-3 text-center">
<button onclick="deleteVipTier(${row.id})" class="text-red-500 hover:text-red-700">
<i class="fa-solid fa-trash"></i>
</button>
</td>
</tr>
`).join('');
}
}
async function saveAllChanges() {
const marketId = document.getElementById('filter-market').value || null;
let saved = 0;
let errors = 0;
// Collect and save banking changes
document.querySelectorAll('#banking-table-body tr[data-competitor]').forEach(async row => {
const competitorId = row.dataset.competitor;
const data = {
competitor_id: competitorId,
market_id: marketId
};
row.querySelectorAll('[data-field]').forEach(input => {
const field = input.dataset.field;
data[field] = input.type === 'number' ? parseFloat(input.value) || null : input.value || null;
});
if (Object.values(data).some(v => v !== null && v !== competitorId && v !== marketId)) {
try {
await CRMT.dal.saveBankingProfile(data);
saved++;
} catch (e) {
errors++;
}
}
});
// Collect and save features changes
document.querySelectorAll('#features-table-body tr[data-competitor]').forEach(async row => {
const competitorId = row.dataset.competitor;
const data = {
competitor_id: competitorId,
market_id: marketId
};
row.querySelectorAll('[data-field]').forEach(input => {
const field = input.dataset.field;
if (input.type === 'checkbox') {
data[field] = input.checked;
} else if (input.type === 'number') {
data[field] = parseFloat(input.value) || null;
} else {
data[field] = input.value || null;
}
});
try {
await CRMT.dal.saveProductFeatures(data);
saved++;
} catch (e) {
errors++;
}
});
// Show result
if (errors === 0) {
alert(`Saved ${saved} records successfully!`);
} else {
alert(`Saved ${saved} records with ${errors} errors.`);
}
// Reload data
await loadData();
}
function addVipTier() {
const competitorId = prompt('Enter competitor ID (e.g., stake-ca-on):');
if (!competitorId) return;
const tierName = prompt('Enter tier name (e.g., Gold):');
if (!tierName) return;
CRMT.dal.saveVipStructure({
competitor_id: competitorId,
tier_name: tierName,
tier_order: vipData.filter(v => v.competitor_id === competitorId).length + 1
}).then(() => loadData());
}
async function deleteVipTier(id) {
if (!confirm('Delete this VIP tier?')) return;
await CRMT.dal.deleteProductData('vip', id);
await loadData();
}
// ========== PAYMENT METHODS MATRIX ==========
let paymentMatrixData = null;
async function loadPaymentMatrix() {
const group = CRMT.navBar?.getSelectedGroup();
const market = group?.market_id || 'TR-ALL';
try {
const res = await fetch(`/.netlify/functions/product-data?type=competitor-payments&market=${market}`);
paymentMatrixData = await res.json();
renderPaymentMatrix();
} catch (e) {
console.error('[D.7] Failed to load payment matrix:', e);
document.getElementById('payment-matrix-body').innerHTML =
'<tr><td colspan="20" class="text-center py-8 text-red-400">Failed to load payment methods</td></tr>';
}
}
function renderPaymentMatrix() {
if (!paymentMatrixData) return;
const { paymentMethods, competitors } = paymentMatrixData;
const headerRow = document.querySelector('#payment-matrix-header tr');
const tbody = document.getElementById('payment-matrix-body');
// Build header: Competitor + each payment method
headerRow.innerHTML = `
<th class="px-4 py-3 text-left font-semibold sticky left-0 bg-slate-50 z-10 min-w-[150px]">Competitor</th>
${paymentMethods.map(m => `
<th class="px-3 py-3 text-center font-semibold min-w-[90px]">
<i class="fa-solid ${m.icon || 'fa-credit-card'} mr-1"></i>
<span class="text-[10px] block">${m.name}</span>
</th>
`).join('')}
`;
// Build rows: each competitor
tbody.innerHTML = competitors.map(c => `
<tr class="border-b border-slate-100 hover:bg-slate-50" data-competitor="${c.id}">
<td class="px-4 py-3 font-medium sticky left-0 bg-white">${c.name}</td>
${paymentMethods.map(m => {
const p = c.payments[m.name] || { enabled: false };
const checked = p.enabled ? 'checked' : '';
const details = p.enabled && (p.min || p.max)
? `<div class="text-[9px] text-slate-400">$${p.min || 0}-$${p.max || '∞'}</div>`
: '';
return `
<td class="px-3 py-2 text-center cursor-pointer hover:bg-slate-100" 
onclick="openPaymentModal('${c.id}', '${c.name}', '${m.name}', ${JSON.stringify(p).replace(/"/g, '&quot;')})">
<input type="checkbox" class="w-4 h-4 text-green-600 rounded pointer-events-none" ${checked}>
${details}
</td>
`;
}).join('')}
</tr>
`).join('');
if (competitors.length === 0) {
tbody.innerHTML = '<tr><td colspan="20" class="text-center py-8 text-slate-400">No competitors in this market</td></tr>';
}
}
function openPaymentModal(competitorId, competitorName, method, data) {
document.getElementById('modal-competitor-id').value = competitorId;
document.getElementById('modal-payment-method').value = method;
document.getElementById('modal-title').textContent = `${competitorName} - ${method}`;
document.getElementById('modal-method-name').textContent = method;
document.getElementById('modal-enabled').checked = data.enabled || false;
document.getElementById('modal-min').value = data.min || '';
document.getElementById('modal-max').value = data.max || '';
document.getElementById('modal-fee').value = data.fee || '';
document.getElementById('payment-method-modal').classList.remove('hidden');
}
function closePaymentModal() {
document.getElementById('payment-method-modal').classList.add('hidden');
}
async function savePaymentMethod() {
const market = CRMT.navBar?.getSelectedMarket() || 'CA-ON';
const data = {
type: 'competitor-payment',
competitor_id: document.getElementById('modal-competitor-id').value,
market_id: market,
payment_method: document.getElementById('modal-payment-method').value,
enabled: document.getElementById('modal-enabled').checked,
min: parseFloat(document.getElementById('modal-min').value) || null,
max: parseFloat(document.getElementById('modal-max').value) || null,
fee: parseFloat(document.getElementById('modal-fee').value) || 0
};
try {
await fetch('/.netlify/functions/product-data', {
method: 'POST',
headers: { 'Content-Type': 'application/json' },
body: JSON.stringify(data)
});
closePaymentModal();
await loadPaymentMatrix();
} catch (e) {
console.error('[D.7] Failed to save payment:', e);
alert('Failed to save payment method');
}
}
function openAddMethodModal() {
document.getElementById('new-method-name').value = '';
document.getElementById('new-method-category').value = 'card';
document.getElementById('add-method-modal').classList.remove('hidden');
}
function closeAddMethodModal() {
document.getElementById('add-method-modal').classList.add('hidden');
}
async function saveNewPaymentMethod() {
const name = document.getElementById('new-method-name').value.trim();
const category = document.getElementById('new-method-category').value;
if (!name) {
alert('Please enter a method name');
return;
}
try {
await fetch('/.netlify/functions/product-data', {
method: 'POST',
headers: { 'Content-Type': 'application/json' },
body: JSON.stringify({ type: 'payment-method', name, category })
});
closeAddMethodModal();
await loadPaymentMatrix();
} catch (e) {
console.error('[D.7] Failed to add payment method:', e);
alert('Failed to add payment method');
}
}
// Load payment matrix on page load and market change
document.addEventListener('DOMContentLoaded', () => {
setTimeout(() => {
loadPaymentMatrix();
loadBankingSummary();
}, 500);
});
window.addEventListener('navBarChange', () => {
loadPaymentMatrix();
loadBankingSummary();
});
// ========== BANKING SUMMARY ==========
async function loadBankingSummary() {
const group = CRMT.navBar?.getSelectedGroup();
const market = group?.market_id || 'TR-ALL';
try {
const res = await fetch(`/.netlify/functions/product-data?type=banking-summary&market=${market}`);
const json = await res.json();
renderBankingSummary(json.data || []);
} catch (e) {
console.error('[D.7] Failed to load banking summary:', e);
}
}
function renderBankingSummary(data) {
const tbody = document.getElementById('banking-summary-body');
if (!tbody) return;
if (data.length === 0) {
tbody.innerHTML = '<tr><td colspan="4" class="text-center py-8 text-slate-400">No data available</td></tr>';
return;
}
tbody.innerHTML = data.map(row => `
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="px-4 py-3 font-medium">${row.competitor_name}</td>
<td class="px-4 py-3 text-center">
${row.min_deposit != null ? '$' + parseFloat(row.min_deposit).toLocaleString() : '-'}
</td>
<td class="px-4 py-3 text-center">
${row.max_deposit != null ? '$' + parseFloat(row.max_deposit).toLocaleString() : '-'}
</td>
<td class="px-4 py-3 text-center">
<span class="px-2 py-1 rounded-full text-xs ${row.methods_count > 5 ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600'}">
${row.methods_count || 0} / ${row.total_methods || 18}
</span>
</td>
</tr>
`).join('');
}
// ========== FEATURES ACCORDION ==========
async function loadFeaturesAccordion() {
const market = CRMT.navBar?.getSelectedMarket() || 'CA-ON';
try {
const res = await fetch(`/.netlify/functions/product-data?type=competitor-features&market=${market}`);
const json = await res.json();
featuresData = json.categories || [];
renderFeaturesAccordion();
} catch (e) {
console.error('[D.7] Failed to load features:', e);
document.getElementById('features-accordion').innerHTML =
'<div class="p-8 text-center text-red-400">Failed to load features</div>';
}
}
function renderFeaturesAccordion() {
const container = document.getElementById('features-accordion');
if (!container || !featuresData) return;
if (featuresData.length === 0) {
container.innerHTML = '<div class="p-8 text-center text-slate-400">No feature categories available</div>';
return;
}
container.innerHTML = featuresData.map((cat, idx) => `
<div class="accordion-item">
<button class="w-full px-4 py-3 flex justify-between items-center text-left hover:bg-slate-50 transition-colors"
onclick="toggleAccordion('cat-${cat.id}')">
<div class="flex items-center gap-3">
<i class="fa-solid ${cat.icon || 'fa-folder'} text-blue-600"></i>
<span class="font-medium text-slate-700">${cat.name}</span>
<span class="text-xs text-slate-400">(${cat.features?.length || 0} features)</span>
</div>
<i class="fa-solid fa-chevron-down text-slate-400 transition-transform" id="icon-cat-${cat.id}"></i>
</button>
<div class="hidden overflow-x-auto bg-slate-50" id="cat-${cat.id}">
<table class="w-full text-sm">
<thead class="bg-slate-100 text-xs uppercase text-slate-500">
<tr>
<th class="px-4 py-2 text-left font-semibold">Competitor</th>
${(cat.features || []).map(f => `
<th class="px-3 py-2 text-center font-semibold min-w-[80px]">
<i class="fa-solid ${f.icon || 'fa-check'} mr-1"></i>
<span class="text-[10px] block">${f.name}</span>
</th>
`).join('')}
</tr>
</thead>
<tbody>
${(cat.competitors || []).map(c => `
<tr class="border-b border-slate-200 hover:bg-white">
<td class="px-4 py-2 font-medium">${c.name}</td>
${(cat.features || []).map(f => `
<td class="px-3 py-2 text-center">
<input type="checkbox" ${c.features[f.id] ? 'checked' : ''} 
class="w-4 h-4 text-blue-600 rounded"
onchange="toggleFeature('${c.id}', '${f.id}', this.checked)">
</td>
`).join('')}
</tr>
`).join('')}
</tbody>
</table>
</div>
</div>
`).join('');
}
function toggleAccordion(id) {
const panel = document.getElementById(id);
const icon = document.getElementById('icon-' + id);
if (panel) {
panel.classList.toggle('hidden');
if (icon) icon.classList.toggle('rotate-180');
}
}
async function toggleFeature(competitorId, featureId, enabled) {
const market = CRMT.navBar?.getSelectedMarket() || 'CA-ON';
try {
await fetch('/.netlify/functions/product-data', {
method: 'POST',
headers: { 'Content-Type': 'application/json' },
body: JSON.stringify({
type: 'attributes',
competitor_id: competitorId,
market_id: market,
attribute_type: 'feature',
attribute_value: featureId,
enabled
})
});
} catch (e) {
console.error('[D.7] Failed to update feature:', e);
}
}
// Add features loading to page load
document.addEventListener('DOMContentLoaded', () => {
setTimeout(loadFeaturesAccordion, 600);
setTimeout(initVipMarketSelector, 700);
});
window.addEventListener('navBarChange', loadFeaturesAccordion);
// ========== ADD FEATURE/CATEGORY ==========
let featureCategories = [];
function openAddCategoryModal() {
document.getElementById('new-category-name').value = '';
document.getElementById('new-category-icon').value = 'fa-folder';
document.getElementById('new-category-applies').value = 'all';
document.getElementById('add-category-modal').classList.remove('hidden');
}
function closeAddCategoryModal() {
document.getElementById('add-category-modal').classList.add('hidden');
}
async function saveNewCategory() {
const name = document.getElementById('new-category-name').value.trim();
const icon = document.getElementById('new-category-icon').value.trim();
const appliesTo = document.getElementById('new-category-applies').value;
if (!name) {
alert('Please enter a category name');
return;
}
try {
const id = name.toLowerCase().replace(/\s+/g, '-');
await fetch('/.netlify/functions/product-data', {
method: 'POST',
headers: { 'Content-Type': 'application/json' },
body: JSON.stringify({
type: 'feature-category',
id, name, icon, applies_to: appliesTo
})
});
closeAddCategoryModal();
await loadFeaturesAccordion();
} catch (e) {
console.error('[D.7] Failed to add category:', e);
alert('Failed to add category');
}
}
async function openAddFeatureModal() {
// Load categories for dropdown
try {
const res = await fetch('/.netlify/functions/product-data?type=feature-categories');
const json = await res.json();
featureCategories = json.data || [];
const select = document.getElementById('new-feature-category');
select.innerHTML = '<option value="">Select category...</option>' +
featureCategories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
} catch (e) {
console.error('[D.7] Failed to load categories:', e);
}
document.getElementById('new-feature-name').value = '';
document.getElementById('new-feature-icon').value = 'fa-check';
document.getElementById('add-feature-modal').classList.remove('hidden');
}
function closeAddFeatureModal() {
document.getElementById('add-feature-modal').classList.add('hidden');
}
async function saveNewFeature() {
const categoryId = document.getElementById('new-feature-category').value;
const name = document.getElementById('new-feature-name').value.trim();
const icon = document.getElementById('new-feature-icon').value.trim();
if (!categoryId) {
alert('Please select a category');
return;
}
if (!name) {
alert('Please enter a feature name');
return;
}
try {
const id = name.toLowerCase().replace(/\s+/g, '-');
await fetch('/.netlify/functions/product-data', {
method: 'POST',
headers: { 'Content-Type': 'application/json' },
body: JSON.stringify({
type: 'feature',
id, name, icon, category_id: categoryId
})
});
closeAddFeatureModal();
await loadFeaturesAccordion();
} catch (e) {
console.error('[D.7] Failed to add feature:', e);
alert('Failed to add feature');
}
}
// ========== VIP COMPARISON ==========
let allMarkets = [];
async function initVipMarketSelector() {
// Get markets from API - returns {countries: [{jurisdictions: [...]}]}
try {
const res = await fetch('/.netlify/functions/markets');
const json = await res.json();
// Flatten countries → jurisdictions into markets array
allMarkets = [];
(json.countries || []).forEach(country => {
(country.jurisdictions || []).forEach(j => {
allMarkets.push({
id: j.marketId,
name: `${country.flag || ''} ${j.name}`.trim(),
countryCode: country.code
});
});
});
console.log('[D.7] Loaded', allMarkets.length, 'markets for VIP selector');
renderVipMarketSelector();
} catch (e) {
console.error('[D.7] Failed to load markets for VIP:', e);
}
}
function renderVipMarketSelector() {
const container = document.getElementById('vip-market-selector');
if (!container) return;
const currentMarket = CRMT.navBar?.getSelectedMarket() || 'CA-ON';
container.innerHTML = allMarkets.slice(0, 6).map(m => `
<label class="flex items-center gap-1 px-2 py-1 bg-white border rounded text-xs cursor-pointer hover:bg-amber-50">
<input type="checkbox" class="w-3 h-3" value="${m.id}" ${m.id === currentMarket ? 'checked' : ''}>
${m.name}
</label>
`).join('');
}
async function loadVipComparison() {
const container = document.getElementById('vip-market-selector');
const checkboxes = container?.querySelectorAll('input[type="checkbox"]:checked') || [];
const selectedMarkets = Array.from(checkboxes).map(cb => cb.value);
if (selectedMarkets.length === 0) {
alert('Please select at least one market');
return;
}
try {
const res = await fetch(`/.netlify/functions/product-data?type=vip-comparison&markets=${selectedMarkets.join(',')}`);
const json = await res.json();
renderVipComparison(json.competitors || []);
} catch (e) {
console.error('[D.7] Failed to load VIP comparison:', e);
}
}
function renderVipComparison(competitors) {
const tbody = document.getElementById('vip-comparison-body');
if (!tbody) return;
if (competitors.length === 0) {
tbody.innerHTML = '<tr><td colspan="5" class="text-center py-8 text-slate-400">No VIP data found for selected markets</td></tr>';
return;
}
tbody.innerHTML = competitors.map(c => {
const scoreColor = c.score >= 70 ? 'bg-green-100 text-green-700' :
c.score >= 40 ? 'bg-yellow-100 text-yellow-700' :
'bg-slate-100 text-slate-600';
const tierNames = c.tiers.map(t => t.name).join(' → ');
const topPerks = c.total_perks.slice(0, 3).map(p => p.name).join(', ');
return `
<tr class="border-b border-slate-100 hover:bg-amber-50 vip-row"
data-competitor="${c.competitor_id}">
<td class="px-4 py-3 font-medium">
<span class="text-amber-700 hover:underline cursor-pointer" 
onclick="selectCompetitorVip('${c.competitor_id}', '${c.competitor_name}')">${c.competitor_name}</span>
<button class="ml-2 text-slate-400 hover:text-amber-600" onclick="toggleVipDetails('vip-${c.competitor_id}')">
<i class="fa-solid fa-chevron-down text-xs"></i>
</button>
</td>
<td class="px-4 py-3 text-slate-500">${c.market_id}</td>
<td class="px-4 py-3 text-center">
<span class="px-3 py-1 rounded-full text-sm font-bold ${scoreColor}">
${c.score}/100
</span>
</td>
<td class="px-4 py-3 text-xs">${tierNames || 'No tiers'}</td>
<td class="px-4 py-3 text-xs text-slate-500">${topPerks || 'No perks'}</td>
</tr>
<tr id="vip-${c.competitor_id}" class="hidden bg-amber-50/50">
<td colspan="5" class="p-4">
<div class="grid grid-cols-3 gap-4">
${c.tiers.map(t => `
<div class="bg-white rounded-lg p-3 border border-slate-200">
<div class="font-medium text-sm mb-2">${t.name} (Tier ${t.order})</div>
<div class="text-xs text-slate-500 mb-2">
${t.entry_requirements?.min_deposit ? `Min: $${t.entry_requirements.min_deposit}` : ''}
${t.entry_requirements?.wagering ? ` | Wager: $${t.entry_requirements.wagering}` : ''}
${t.entry_requirements?.invite_only ? ' | Invite only' : ''}
</div>
<div class="text-xs">
${(t.perks || []).map(p => `<span class="inline-block px-2 py-0.5 bg-amber-100 text-amber-700 rounded mr-1 mb-1">${p.name} (${p.points}pts)</span>`).join('')}
</div>
</div>
`).join('')}
</div>
</td>
</tr>
`;
}).join('');
}
function toggleVipDetails(id) {
const row = document.getElementById(id);
if (row) row.classList.toggle('hidden');
}
let selectedVipCompetitor = null;
function selectCompetitorVip(competitorId, competitorName) {
selectedVipCompetitor = competitorId;
// Highlight selected row
document.querySelectorAll('.vip-row').forEach(r => {
r.classList.remove('bg-amber-100');
if (r.dataset.competitor === competitorId) {
r.classList.add('bg-amber-100');
}
});
// Update editor header
const editorHeader = document.querySelector('#panel-vip .mt-6 h2');
if (editorHeader) {
editorHeader.innerHTML = `< i class="fa-solid fa-edit mr-2 text-amber-500" ></i > VIP Tiers: ${competitorName} `;
}
// Filter the tier editor table
filterVipTierEditor(competitorId);
}
function filterVipTierEditor(competitorId) {
const tbody = document.getElementById('vip-table-body');
if (!tbody) return;
// Filter vipData to this competitor and re-render
const filtered = vipData.filter(v => v.competitor_id === competitorId);
if (filtered.length === 0) {
tbody.innerHTML = `< tr > <td colspan="5" class="text-center py-8 text-slate-400">No tiers for this competitor. Click "Add VIP Tier" to create one.</td></tr > `;
return;
}
tbody.innerHTML = filtered.map(tier => `
< tr class="border-b border-slate-100 hover:bg-slate-50" >
<td class="px-4 py-3 font-medium">${tier.competitor_name || competitorId}</td>
<td class="px-4 py-3">${tier.tier_name}</td>
<td class="px-4 py-3 text-center">${tier.tier_order}</td>
<td class="px-4 py-3 text-xs text-slate-500">
${tier.entry_requirements ? formatEntryReqs(tier.entry_requirements) : '-'}
</td>
<td class="px-4 py-3 text-center">
<button onclick="deleteVipTier(${tier.id})" class="text-red-500 hover:text-red-700">
<i class="fa-solid fa-trash"></i>
</button>
</td>
</tr >
`).join('');
}
function formatEntryReqs(reqs) {
if (!reqs || typeof reqs !== 'object') return '-';
const parts = [];
if (reqs.min_deposit) parts.push(`Min: $${reqs.min_deposit} `);
if (reqs.wagering) parts.push(`Wager: $${reqs.wagering}`);
if (reqs.invite_only) parts.push('Invite only');
return parts.join(' | ') || '-';
}
</script>
<!-- Payment Method Modal -->
<div id="payment-method-modal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
<div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4">
<div class="p-4 border-b border-slate-100">
<h3 class="font-semibold text-slate-700" id="modal-title">Edit Payment Method</h3>
</div>
<div class="p-6 space-y-4">
<input type="hidden" id="modal-competitor-id">
<input type="hidden" id="modal-payment-method">
<div class="flex items-center gap-3">
<input type="checkbox" id="modal-enabled" class="w-5 h-5 rounded text-green-600">
<label for="modal-enabled" class="text-sm font-medium text-slate-700">
This competitor offers <span id="modal-method-name" class="font-bold"></span>
</label>
</div>
<div class="grid grid-cols-3 gap-4">
<div>
<label class="block text-xs text-slate-500 mb-1">Min Deposit</label>
<div class="flex">
<span
class="px-3 py-2 bg-slate-100 border border-r-0 border-slate-300 rounded-l-lg text-sm">$</span>
<input type="number" id="modal-min"
class="w-full px-3 py-2 border border-slate-300 rounded-r-lg text-sm" placeholder="0">
</div>
</div>
<div>
<label class="block text-xs text-slate-500 mb-1">Max Deposit</label>
<div class="flex">
<span
class="px-3 py-2 bg-slate-100 border border-r-0 border-slate-300 rounded-l-lg text-sm">$</span>
<input type="number" id="modal-max"
class="w-full px-3 py-2 border border-slate-300 rounded-r-lg text-sm" placeholder="∞">
</div>
</div>
<div>
<label class="block text-xs text-slate-500 mb-1">Fee</label>
<div class="flex">
<input type="number" id="modal-fee"
class="w-full px-3 py-2 border border-slate-300 rounded-l-lg text-sm" placeholder="0"
step="0.1">
<span
class="px-3 py-2 bg-slate-100 border border-l-0 border-slate-300 rounded-r-lg text-sm">%</span>
</div>
</div>
</div>
</div>
<div class="p-4 border-t border-slate-100 flex justify-end gap-3">
<button onclick="closePaymentModal()"
class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg text-sm font-medium transition-colors">
Cancel
</button>
<button onclick="savePaymentMethod()"
class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
Save
</button>
</div>
</div>
</div>
<!-- Add Method Modal -->
<div id="add-method-modal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
<div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4">
<div class="p-4 border-b border-slate-100">
<h3 class="font-semibold text-slate-700">Add Payment Method</h3>
</div>
<div class="p-6 space-y-4">
<div>
<label class="block text-xs text-slate-500 mb-1">Method Name</label>
<input type="text" id="new-method-name"
class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm"
placeholder="e.g., Apple Pay">
</div>
<div>
<label class="block text-xs text-slate-500 mb-1">Category</label>
<select id="new-method-category"
class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
<option value="card">Card</option>
<option value="crypto">Crypto</option>
<option value="ewallet">E-Wallet</option>
<option value="bank">Bank</option>
<option value="other">Other</option>
</select>
</div>
</div>
<div class="p-4 border-t border-slate-100 flex justify-end gap-3">
<button onclick="closeAddMethodModal()"
class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg text-sm font-medium transition-colors">
Cancel
</button>
<button onclick="saveNewPaymentMethod()"
class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
Add Method
</button>
</div>
</div>
</div>
<!-- Add Category Modal -->
<div id="add-category-modal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
<div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4">
<div class="p-4 border-b border-slate-100">
<h3 class="font-semibold text-slate-700">Add Feature Category</h3>
</div>
<div class="p-6 space-y-4">
<div>
<label class="block text-xs text-slate-500 mb-1">Category Name</label>
<input type="text" id="new-category-name"
class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm"
placeholder="e.g., Live Betting">
</div>
<div>
<label class="block text-xs text-slate-500 mb-1">Icon (FontAwesome)</label>
<input type="text" id="new-category-icon"
class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="fa-bolt"
value="fa-folder">
</div>
<div>
<label class="block text-xs text-slate-500 mb-1">Applies To</label>
<select id="new-category-applies"
class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
<option value="all">All</option>
<option value="sports">Sportsbook</option>
<option value="casino">Casino</option>
<option value="poker">Poker</option>
</select>
</div>
</div>
<div class="p-4 border-t border-slate-100 flex justify-end gap-3">
<button onclick="closeAddCategoryModal()"
class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-200">Cancel</button>
<button onclick="saveNewCategory()"
class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">Add
Category</button>
</div>
</div>
</div>
<!-- Add Feature Modal -->
<div id="add-feature-modal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
<div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4">
<div class="p-4 border-b border-slate-100">
<h3 class="font-semibold text-slate-700">Add Feature</h3>
</div>
<div class="p-6 space-y-4">
<div>
<label class="block text-xs text-slate-500 mb-1">Category</label>
<select id="new-feature-category"
class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
<option value="">Select category...</option>
</select>
</div>
<div>
<label class="block text-xs text-slate-500 mb-1">Feature Name</label>
<input type="text" id="new-feature-name"
class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm"
placeholder="e.g., Early Cashout">
</div>
<div>
<label class="block text-xs text-slate-500 mb-1">Icon (FontAwesome)</label>
<input type="text" id="new-feature-icon"
class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="fa-check"
value="fa-check">
</div>
</div>
<div class="p-4 border-t border-slate-100 flex justify-end gap-3">
<button onclick="closeAddFeatureModal()"
class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-200">Cancel</button>
<button onclick="saveNewFeature()"
class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">Add
Feature</button>
</div>
</div>
@endsection

@push('page-scripts')
<script>
        // State
        let currentTab = 'banking';
        let bankingData = [];
        let featuresData = [];
        let vipData = [];
        let competitors = [];
        let pendingChanges = { banking: {}, features: {}, vip: {} };
        let showAllMarket = false; // Toggle state

        // Initialize
        document.addEventListener('DOMContentLoaded', async () => {
            updateGroupLabel();
            await loadCompetitors();
            await loadData();

            // Set up reportBase event handling for future changes
            CRMT.reportBase?.init({
                reportId: 'D.7-product-intelligence',
                onLoad: async () => {
                    await loadCompetitors();
                    await loadData();
                }
            });
        });

        // Listen for group changes from navigation
        window.addEventListener('navBarChange', async (e) => {
            updateGroupLabel();
            await loadCompetitors();
            await loadData();
            loadPaymentMatrix();
            loadBankingSummary();
        });

        function updateGroupLabel() {
            const label = document.getElementById('current-group-label');
            if (!label) return;

            const group = CRMT.navBar?.getSelectedGroup();
            if (group) {
                label.textContent = `${group.flag || '📁'} ${group.name}`;
            } else {
                label.textContent = 'No group selected';
            }
        }

        function toggleShowAll() {
            showAllMarket = document.getElementById('show-all-toggle')?.checked || false;
            loadCompetitors();
            loadData();
            loadPaymentMatrix();
            loadBankingSummary();
        }

        // Load competitors - from group or all market
        async function loadCompetitors() {
            const group = CRMT.navBar?.getSelectedGroup();
            const marketId = group?.market_id || 'TR-ALL';

            try {
                if (showAllMarket) {
                    // Load ALL competitors in the market
                    competitors = await CRMT.dal.getCompetitors(marketId);
                    console.log('[D.7] Loaded', competitors.length, 'competitors (ALL market)');
                } else if (group?.competitor_ids?.length) {
                    // Load only competitors in the active group
                    const allCompetitors = await CRMT.dal.getCompetitors(marketId);
                    competitors = allCompetitors.filter(c => group.competitor_ids.includes(c.id));
                    console.log('[D.7] Loaded', competitors.length, 'competitors (from group)');
                } else {
                    // Fallback to all
                    competitors = await CRMT.dal.getCompetitors(marketId);
                    console.log('[D.7] Loaded', competitors.length, 'competitors (fallback)');
                }
            } catch (e) {
                console.warn('[D.7] Failed to load competitors:', e);
                competitors = [];
            }
        }

        async function loadData() {
            const group = CRMT.navBar?.getSelectedGroup();
            const marketId = group?.market_id || null;

            try {
                // Load all data types
                bankingData = await CRMT.dal.getBankingProfiles(null, marketId);
                featuresData = await CRMT.dal.getProductFeatures(null, marketId);
                vipData = await CRMT.dal.getVipStructures(null, marketId);
            } catch (e) {
                console.warn('Failed to load product data:', e);
                bankingData = [];
                featuresData = [];
                vipData = [];
            }

            renderCurrentTab();
        }

        function switchTab(tab) {
            currentTab = tab;

            // Update tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
                btn.classList.add('bg-white', 'border', 'border-slate-200');
            });
            document.getElementById(`tab-${tab}`).classList.add('active');
            document.getElementById(`tab-${tab}`).classList.remove('bg-white', 'border', 'border-slate-200');

            // Update panels
            document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.add('hidden'));
            document.getElementById(`panel-${tab}`).classList.remove('hidden');

            renderCurrentTab();
        }

        function renderCurrentTab() {
            switch (currentTab) {
                case 'banking':
                    renderBankingTable();
                    break;
                case 'features':
                    renderFeaturesTable();
                    break;
                case 'vip':
                    renderVipTable();
                    break;
            }
        }

        function renderBankingTable() {
            const tbody = document.getElementById('banking-table-body');

            if (bankingData.length === 0) {
                // Show competitors without data
                tbody.innerHTML = competitors.slice(0, 10).map(c => `
                    <tr class="border-b border-slate-100 hover:bg-slate-50" data-competitor="${c.id}">
                        <td class="px-4 py-3 font-medium">${c.short_name || c.name}</td>
                        <td class="px-4 py-3 text-slate-500">${c.market_id || '-'}</td>
                        <td class="px-4 py-3 text-center">
                            <input type="number" class="editable-cell w-20 px-2 py-1 border rounded text-center" 
                                placeholder="€" data-field="min_deposit">
                        </td>
                        <td class="px-4 py-3 text-center">
                            <input type="number" class="editable-cell w-20 px-2 py-1 border rounded text-center" 
                                placeholder="€" data-field="max_withdrawal">
                        </td>
                        <td class="px-4 py-3 text-center">
                            <input type="text" class="editable-cell w-24 px-2 py-1 border rounded text-center" 
                                placeholder="24h" data-field="speed_advertised">
                        </td>
                        <td class="px-4 py-3 text-center">
                            <input type="number" class="editable-cell w-16 px-2 py-1 border rounded text-center" 
                                placeholder="hrs" data-field="speed_measured_avg_hours">
                        </td>
                        <td class="px-4 py-3 text-center">
                            <input type="text" class="editable-cell w-24 px-2 py-1 border rounded text-center" 
                                placeholder="Free" data-field="fee_policy">
                        </td>
                        <td class="px-4 py-3 text-center text-slate-400">-</td>
                    </tr>
                `).join('');
            } else {
                tbody.innerHTML = bankingData.map(row => `
                    <tr class="border-b border-slate-100 hover:bg-slate-50" data-id="${row.id}" data-competitor="${row.competitor_id}">
                        <td class="px-4 py-3 font-medium">${row.competitor_name || row.competitor_id}</td>
                        <td class="px-4 py-3 text-slate-500">${row.market_name || row.market_id || 'Global'}</td>
                        <td class="px-4 py-3 text-center">
                            <input type="number" class="editable-cell w-20 px-2 py-1 border rounded text-center" 
                                value="${row.min_deposit || ''}" data-field="min_deposit">
                        </td>
                        <td class="px-4 py-3 text-center">
                            <input type="number" class="editable-cell w-20 px-2 py-1 border rounded text-center" 
                                value="${row.max_withdrawal || ''}" data-field="max_withdrawal">
                        </td>
                        <td class="px-4 py-3 text-center">
                            <input type="text" class="editable-cell w-24 px-2 py-1 border rounded text-center" 
                                value="${row.speed_advertised || ''}" data-field="speed_advertised">
                        </td>
                        <td class="px-4 py-3 text-center">
                            <input type="number" class="editable-cell w-16 px-2 py-1 border rounded text-center" 
                                value="${row.speed_measured_avg_hours || ''}" data-field="speed_measured_avg_hours">
                        </td>
                        <td class="px-4 py-3 text-center">
                            <input type="text" class="editable-cell w-24 px-2 py-1 border rounded text-center" 
                                value="${row.fee_policy || ''}" data-field="fee_policy">
                        </td>
                        <td class="px-4 py-3 text-center text-xs text-slate-400">
                            ${row.updated_at ? new Date(row.updated_at).toLocaleDateString() : '-'}
                        </td>
                    </tr>
                `).join('');
            }
        }

        function renderFeaturesTable() {
            const container = document.getElementById('features-accordion');
            if (!container) { console.warn('[D.7] features-accordion not found'); return; }

            const hdr = `<table class="w-full text-sm"><thead class="bg-slate-50 text-xs uppercase text-slate-500"><tr>
                <th class="px-4 py-3 text-left">Competitor</th><th class="px-4 py-3 text-center">Stream</th>
                <th class="px-4 py-3 text-center">Builder</th><th class="px-4 py-3 text-center">VIP</th>
                <th class="px-4 py-3 text-center">App</th><th class="px-4 py-3 text-center">Crypto</th>
                <th class="px-4 py-3 text-center">Slots</th><th class="px-4 py-3 text-center">KYC</th>
            </tr></thead><tbody>`;
            const ftr = `</tbody></table>`;
            const data = featuresData.length > 0 ? featuresData : competitors.slice(0, 10).map(c => ({ ...c, competitor_name: c.short_name || c.name }));
            const rows = data.map(r => `<tr class="border-b border-slate-100 hover:bg-slate-50">
                <td class="px-4 py-3 font-medium">${r.competitor_name || r.short_name || r.name || '-'}</td>
                <td class="px-4 py-3 text-center"><input type="checkbox" class="w-4 h-4" ${r.has_live_streaming ? 'checked' : ''} data-field="has_live_streaming"></td>
                <td class="px-4 py-3 text-center"><input type="checkbox" class="w-4 h-4" ${r.has_bet_builder ? 'checked' : ''} data-field="has_bet_builder"></td>
                <td class="px-4 py-3 text-center"><input type="checkbox" class="w-4 h-4" ${r.has_vip_program ? 'checked' : ''} data-field="has_vip_program"></td>
                <td class="px-4 py-3 text-center"><input type="checkbox" class="w-4 h-4" ${r.has_native_app ? 'checked' : ''} data-field="has_native_app"></td>
                <td class="px-4 py-3 text-center"><input type="checkbox" class="w-4 h-4" ${r.has_crypto ? 'checked' : ''} data-field="has_crypto"></td>
                <td class="px-4 py-3 text-center"><input type="number" class="w-16 px-1 border rounded text-center" value="${r.game_count_slots || ''}"></td>
                <td class="px-4 py-3 text-center"><select class="px-1 border rounded text-xs"><option>-</option>
                    <option ${r.kyc_trigger === 'on_registration' ? 'selected' : ''}>Reg</option>
                    <option ${r.kyc_trigger === 'on_deposit' ? 'selected' : ''}>Dep</option></select></td>
            </tr>`).join('');
            container.innerHTML = hdr + rows + ftr;
        }

        function renderVipTable() {
            const tbody = document.getElementById('vip-table-body');

            if (vipData.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="text-center py-8 text-slate-400">
                    No VIP tiers defined. Click "Add VIP Tier" to create one.
                </td></tr>`;
            } else {
                tbody.innerHTML = vipData.map(row => `
                    <tr class="border-b border-slate-100 hover:bg-slate-50" data-id="${row.id}">
                        <td class="px-4 py-3 font-medium">${row.competitor_name || row.competitor_id}</td>
                        <td class="px-4 py-3">
                            <input type="text" class="editable-cell w-24 px-2 py-1 border rounded" 
                                value="${row.tier_name}" data-field="tier_name">
                        </td>
                        <td class="px-4 py-3 text-center">
                            <input type="number" class="editable-cell w-12 px-2 py-1 border rounded text-center" 
                                value="${row.tier_order || 0}" data-field="tier_order">
                        </td>
                        <td class="px-4 py-3">
                            <input type="text" class="editable-cell w-48 px-2 py-1 border rounded" 
                                value="${row.entry_requirement || ''}" data-field="entry_requirement">
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button onclick="deleteVipTier(${row.id})" class="text-red-500 hover:text-red-700">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `).join('');
            }
        }

        async function saveAllChanges() {
            const marketId = document.getElementById('filter-market').value || null;
            let saved = 0;
            let errors = 0;

            // Collect and save banking changes
            document.querySelectorAll('#banking-table-body tr[data-competitor]').forEach(async row => {
                const competitorId = row.dataset.competitor;
                const data = {
                    competitor_id: competitorId,
                    market_id: marketId
                };

                row.querySelectorAll('[data-field]').forEach(input => {
                    const field = input.dataset.field;
                    data[field] = input.type === 'number' ? parseFloat(input.value) || null : input.value || null;
                });

                if (Object.values(data).some(v => v !== null && v !== competitorId && v !== marketId)) {
                    try {
                        await CRMT.dal.saveBankingProfile(data);
                        saved++;
                    } catch (e) {
                        errors++;
                    }
                }
            });

            // Collect and save features changes
            document.querySelectorAll('#features-table-body tr[data-competitor]').forEach(async row => {
                const competitorId = row.dataset.competitor;
                const data = {
                    competitor_id: competitorId,
                    market_id: marketId
                };

                row.querySelectorAll('[data-field]').forEach(input => {
                    const field = input.dataset.field;
                    if (input.type === 'checkbox') {
                        data[field] = input.checked;
                    } else if (input.type === 'number') {
                        data[field] = parseFloat(input.value) || null;
                    } else {
                        data[field] = input.value || null;
                    }
                });

                try {
                    await CRMT.dal.saveProductFeatures(data);
                    saved++;
                } catch (e) {
                    errors++;
                }
            });

            // Show result
            if (errors === 0) {
                alert(`Saved ${saved} records successfully!`);
            } else {
                alert(`Saved ${saved} records with ${errors} errors.`);
            }

            // Reload data
            await loadData();
        }

        function addVipTier() {
            const competitorId = prompt('Enter competitor ID (e.g., stake-ca-on):');
            if (!competitorId) return;

            const tierName = prompt('Enter tier name (e.g., Gold):');
            if (!tierName) return;

            CRMT.dal.saveVipStructure({
                competitor_id: competitorId,
                tier_name: tierName,
                tier_order: vipData.filter(v => v.competitor_id === competitorId).length + 1
            }).then(() => loadData());
        }

        async function deleteVipTier(id) {
            if (!confirm('Delete this VIP tier?')) return;
            await CRMT.dal.deleteProductData('vip', id);
            await loadData();
        }

        // ========== PAYMENT METHODS MATRIX ==========
        let paymentMatrixData = null;

        async function loadPaymentMatrix() {
            const group = CRMT.navBar?.getSelectedGroup();
            const market = group?.market_id || 'TR-ALL';
            try {
                const res = await fetch(`/.netlify/functions/product-data?type=competitor-payments&market=${market}`);
                paymentMatrixData = await res.json();
                renderPaymentMatrix();
            } catch (e) {
                console.error('[D.7] Failed to load payment matrix:', e);
                document.getElementById('payment-matrix-body').innerHTML =
                    '<tr><td colspan="20" class="text-center py-8 text-red-400">Failed to load payment methods</td></tr>';
            }
        }

        function renderPaymentMatrix() {
            if (!paymentMatrixData) return;

            const { paymentMethods, competitors } = paymentMatrixData;
            const headerRow = document.querySelector('#payment-matrix-header tr');
            const tbody = document.getElementById('payment-matrix-body');

            // Build header: Competitor + each payment method
            headerRow.innerHTML = `
                <th class="px-4 py-3 text-left font-semibold sticky left-0 bg-slate-50 z-10 min-w-[150px]">Competitor</th>
                ${paymentMethods.map(m => `
                    <th class="px-3 py-3 text-center font-semibold min-w-[90px]">
                        <i class="fa-solid ${m.icon || 'fa-credit-card'} mr-1"></i>
                        <span class="text-[10px] block">${m.name}</span>
                    </th>
                `).join('')}
            `;

            // Build rows: each competitor
            tbody.innerHTML = competitors.map(c => `
                <tr class="border-b border-slate-100 hover:bg-slate-50" data-competitor="${c.id}">
                    <td class="px-4 py-3 font-medium sticky left-0 bg-white">${c.name}</td>
                    ${paymentMethods.map(m => {
                const p = c.payments[m.name] || { enabled: false };
                const checked = p.enabled ? 'checked' : '';
                const details = p.enabled && (p.min || p.max)
                    ? `<div class="text-[9px] text-slate-400">$${p.min || 0}-$${p.max || '∞'}</div>`
                    : '';
                return `
                            <td class="px-3 py-2 text-center cursor-pointer hover:bg-slate-100" 
                                onclick="openPaymentModal('${c.id}', '${c.name}', '${m.name}', ${JSON.stringify(p).replace(/"/g, '&quot;')})">
                                <input type="checkbox" class="w-4 h-4 text-green-600 rounded pointer-events-none" ${checked}>
                                ${details}
                            </td>
                        `;
            }).join('')}
                </tr>
            `).join('');

            if (competitors.length === 0) {
                tbody.innerHTML = '<tr><td colspan="20" class="text-center py-8 text-slate-400">No competitors in this market</td></tr>';
            }
        }

        function openPaymentModal(competitorId, competitorName, method, data) {
            document.getElementById('modal-competitor-id').value = competitorId;
            document.getElementById('modal-payment-method').value = method;
            document.getElementById('modal-title').textContent = `${competitorName} - ${method}`;
            document.getElementById('modal-method-name').textContent = method;
            document.getElementById('modal-enabled').checked = data.enabled || false;
            document.getElementById('modal-min').value = data.min || '';
            document.getElementById('modal-max').value = data.max || '';
            document.getElementById('modal-fee').value = data.fee || '';
            document.getElementById('payment-method-modal').classList.remove('hidden');
        }

        function closePaymentModal() {
            document.getElementById('payment-method-modal').classList.add('hidden');
        }

        async function savePaymentMethod() {
            const market = CRMT.navBar?.getSelectedMarket() || 'CA-ON';
            const data = {
                type: 'competitor-payment',
                competitor_id: document.getElementById('modal-competitor-id').value,
                market_id: market,
                payment_method: document.getElementById('modal-payment-method').value,
                enabled: document.getElementById('modal-enabled').checked,
                min: parseFloat(document.getElementById('modal-min').value) || null,
                max: parseFloat(document.getElementById('modal-max').value) || null,
                fee: parseFloat(document.getElementById('modal-fee').value) || 0
            };

            try {
                await fetch('/.netlify/functions/product-data', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                closePaymentModal();
                await loadPaymentMatrix();
            } catch (e) {
                console.error('[D.7] Failed to save payment:', e);
                alert('Failed to save payment method');
            }
        }

        function openAddMethodModal() {
            document.getElementById('new-method-name').value = '';
            document.getElementById('new-method-category').value = 'card';
            document.getElementById('add-method-modal').classList.remove('hidden');
        }

        function closeAddMethodModal() {
            document.getElementById('add-method-modal').classList.add('hidden');
        }

        async function saveNewPaymentMethod() {
            const name = document.getElementById('new-method-name').value.trim();
            const category = document.getElementById('new-method-category').value;

            if (!name) {
                alert('Please enter a method name');
                return;
            }

            try {
                await fetch('/.netlify/functions/product-data', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ type: 'payment-method', name, category })
                });
                closeAddMethodModal();
                await loadPaymentMatrix();
            } catch (e) {
                console.error('[D.7] Failed to add payment method:', e);
                alert('Failed to add payment method');
            }
        }

        // Load payment matrix on page load and market change
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                loadPaymentMatrix();
                loadBankingSummary();
            }, 500);
        });
        window.addEventListener('navBarChange', () => {
            loadPaymentMatrix();
            loadBankingSummary();
        });

        // ========== BANKING SUMMARY ==========
        async function loadBankingSummary() {
            const group = CRMT.navBar?.getSelectedGroup();
            const market = group?.market_id || 'TR-ALL';
            try {
                const res = await fetch(`/.netlify/functions/product-data?type=banking-summary&market=${market}`);
                const json = await res.json();
                renderBankingSummary(json.data || []);
            } catch (e) {
                console.error('[D.7] Failed to load banking summary:', e);
            }
        }

        function renderBankingSummary(data) {
            const tbody = document.getElementById('banking-summary-body');
            if (!tbody) return;

            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center py-8 text-slate-400">No data available</td></tr>';
                return;
            }

            tbody.innerHTML = data.map(row => `
                <tr class="border-b border-slate-100 hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium">${row.competitor_name}</td>
                    <td class="px-4 py-3 text-center">
                        ${row.min_deposit != null ? '$' + parseFloat(row.min_deposit).toLocaleString() : '-'}
                    </td>
                    <td class="px-4 py-3 text-center">
                        ${row.max_deposit != null ? '$' + parseFloat(row.max_deposit).toLocaleString() : '-'}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 rounded-full text-xs ${row.methods_count > 5 ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600'}">
                            ${row.methods_count || 0} / ${row.total_methods || 18}
                        </span>
                    </td>
                </tr>
            `).join('');
        }

        // ========== FEATURES ACCORDION ==========

        async function loadFeaturesAccordion() {
            const market = CRMT.navBar?.getSelectedMarket() || 'CA-ON';
            try {
                const res = await fetch(`/.netlify/functions/product-data?type=competitor-features&market=${market}`);
                const json = await res.json();
                featuresData = json.categories || [];
                renderFeaturesAccordion();
            } catch (e) {
                console.error('[D.7] Failed to load features:', e);
                document.getElementById('features-accordion').innerHTML =
                    '<div class="p-8 text-center text-red-400">Failed to load features</div>';
            }
        }

        function renderFeaturesAccordion() {
            const container = document.getElementById('features-accordion');
            if (!container || !featuresData) return;

            if (featuresData.length === 0) {
                container.innerHTML = '<div class="p-8 text-center text-slate-400">No feature categories available</div>';
                return;
            }

            container.innerHTML = featuresData.map((cat, idx) => `
                <div class="accordion-item">
                    <button class="w-full px-4 py-3 flex justify-between items-center text-left hover:bg-slate-50 transition-colors"
                        onclick="toggleAccordion('cat-${cat.id}')">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid ${cat.icon || 'fa-folder'} text-blue-600"></i>
                            <span class="font-medium text-slate-700">${cat.name}</span>
                            <span class="text-xs text-slate-400">(${cat.features?.length || 0} features)</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-slate-400 transition-transform" id="icon-cat-${cat.id}"></i>
                    </button>
                    <div class="hidden overflow-x-auto bg-slate-50" id="cat-${cat.id}">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-100 text-xs uppercase text-slate-500">
                                <tr>
                                    <th class="px-4 py-2 text-left font-semibold">Competitor</th>
                                    ${(cat.features || []).map(f => `
                                        <th class="px-3 py-2 text-center font-semibold min-w-[80px]">
                                            <i class="fa-solid ${f.icon || 'fa-check'} mr-1"></i>
                                            <span class="text-[10px] block">${f.name}</span>
                                        </th>
                                    `).join('')}
                                </tr>
                            </thead>
                            <tbody>
                                ${(cat.competitors || []).map(c => `
                                    <tr class="border-b border-slate-200 hover:bg-white">
                                        <td class="px-4 py-2 font-medium">${c.name}</td>
                                        ${(cat.features || []).map(f => `
                                            <td class="px-3 py-2 text-center">
                                                <input type="checkbox" ${c.features[f.id] ? 'checked' : ''} 
                                                    class="w-4 h-4 text-blue-600 rounded"
                                                    onchange="toggleFeature('${c.id}', '${f.id}', this.checked)">
                                            </td>
                                        `).join('')}
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            `).join('');
        }

        function toggleAccordion(id) {
            const panel = document.getElementById(id);
            const icon = document.getElementById('icon-' + id);
            if (panel) {
                panel.classList.toggle('hidden');
                if (icon) icon.classList.toggle('rotate-180');
            }
        }

        async function toggleFeature(competitorId, featureId, enabled) {
            const market = CRMT.navBar?.getSelectedMarket() || 'CA-ON';
            try {
                await fetch('/.netlify/functions/product-data', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        type: 'attributes',
                        competitor_id: competitorId,
                        market_id: market,
                        attribute_type: 'feature',
                        attribute_value: featureId,
                        enabled
                    })
                });
            } catch (e) {
                console.error('[D.7] Failed to update feature:', e);
            }
        }

        // Add features loading to page load
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(loadFeaturesAccordion, 600);
            setTimeout(initVipMarketSelector, 700);
        });
        window.addEventListener('navBarChange', loadFeaturesAccordion);

        // ========== ADD FEATURE/CATEGORY ==========
        let featureCategories = [];

        function openAddCategoryModal() {
            document.getElementById('new-category-name').value = '';
            document.getElementById('new-category-icon').value = 'fa-folder';
            document.getElementById('new-category-applies').value = 'all';
            document.getElementById('add-category-modal').classList.remove('hidden');
        }

        function closeAddCategoryModal() {
            document.getElementById('add-category-modal').classList.add('hidden');
        }

        async function saveNewCategory() {
            const name = document.getElementById('new-category-name').value.trim();
            const icon = document.getElementById('new-category-icon').value.trim();
            const appliesTo = document.getElementById('new-category-applies').value;

            if (!name) {
                alert('Please enter a category name');
                return;
            }

            try {
                const id = name.toLowerCase().replace(/\s+/g, '-');
                await fetch('/.netlify/functions/product-data', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        type: 'feature-category',
                        id, name, icon, applies_to: appliesTo
                    })
                });
                closeAddCategoryModal();
                await loadFeaturesAccordion();
            } catch (e) {
                console.error('[D.7] Failed to add category:', e);
                alert('Failed to add category');
            }
        }

        async function openAddFeatureModal() {
            // Load categories for dropdown
            try {
                const res = await fetch('/.netlify/functions/product-data?type=feature-categories');
                const json = await res.json();
                featureCategories = json.data || [];

                const select = document.getElementById('new-feature-category');
                select.innerHTML = '<option value="">Select category...</option>' +
                    featureCategories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
            } catch (e) {
                console.error('[D.7] Failed to load categories:', e);
            }

            document.getElementById('new-feature-name').value = '';
            document.getElementById('new-feature-icon').value = 'fa-check';
            document.getElementById('add-feature-modal').classList.remove('hidden');
        }

        function closeAddFeatureModal() {
            document.getElementById('add-feature-modal').classList.add('hidden');
        }

        async function saveNewFeature() {
            const categoryId = document.getElementById('new-feature-category').value;
            const name = document.getElementById('new-feature-name').value.trim();
            const icon = document.getElementById('new-feature-icon').value.trim();

            if (!categoryId) {
                alert('Please select a category');
                return;
            }
            if (!name) {
                alert('Please enter a feature name');
                return;
            }

            try {
                const id = name.toLowerCase().replace(/\s+/g, '-');
                await fetch('/.netlify/functions/product-data', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        type: 'feature',
                        id, name, icon, category_id: categoryId
                    })
                });
                closeAddFeatureModal();
                await loadFeaturesAccordion();
            } catch (e) {
                console.error('[D.7] Failed to add feature:', e);
                alert('Failed to add feature');
            }
        }

        // ========== VIP COMPARISON ==========
        let allMarkets = [];

        async function initVipMarketSelector() {
            // Get markets from API - returns {countries: [{jurisdictions: [...]}]}
            try {
                const res = await fetch('/.netlify/functions/markets');
                const json = await res.json();

                // Flatten countries → jurisdictions into markets array
                allMarkets = [];
                (json.countries || []).forEach(country => {
                    (country.jurisdictions || []).forEach(j => {
                        allMarkets.push({
                            id: j.marketId,
                            name: `${country.flag || ''} ${j.name}`.trim(),
                            countryCode: country.code
                        });
                    });
                });

                console.log('[D.7] Loaded', allMarkets.length, 'markets for VIP selector');
                renderVipMarketSelector();
            } catch (e) {
                console.error('[D.7] Failed to load markets for VIP:', e);
            }
        }

        function renderVipMarketSelector() {
            const container = document.getElementById('vip-market-selector');
            if (!container) return;

            const currentMarket = CRMT.navBar?.getSelectedMarket() || 'CA-ON';

            container.innerHTML = allMarkets.slice(0, 6).map(m => `
                <label class="flex items-center gap-1 px-2 py-1 bg-white border rounded text-xs cursor-pointer hover:bg-amber-50">
                    <input type="checkbox" class="w-3 h-3" value="${m.id}" ${m.id === currentMarket ? 'checked' : ''}>
                    ${m.name}
                </label>
            `).join('');
        }

        async function loadVipComparison() {
            const container = document.getElementById('vip-market-selector');
            const checkboxes = container?.querySelectorAll('input[type="checkbox"]:checked') || [];
            const selectedMarkets = Array.from(checkboxes).map(cb => cb.value);

            if (selectedMarkets.length === 0) {
                alert('Please select at least one market');
                return;
            }

            try {
                const res = await fetch(`/.netlify/functions/product-data?type=vip-comparison&markets=${selectedMarkets.join(',')}`);
                const json = await res.json();
                renderVipComparison(json.competitors || []);
            } catch (e) {
                console.error('[D.7] Failed to load VIP comparison:', e);
            }
        }

        function renderVipComparison(competitors) {
            const tbody = document.getElementById('vip-comparison-body');
            if (!tbody) return;

            if (competitors.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center py-8 text-slate-400">No VIP data found for selected markets</td></tr>';
                return;
            }

            tbody.innerHTML = competitors.map(c => {
                const scoreColor = c.score >= 70 ? 'bg-green-100 text-green-700' :
                    c.score >= 40 ? 'bg-yellow-100 text-yellow-700' :
                        'bg-slate-100 text-slate-600';
                const tierNames = c.tiers.map(t => t.name).join(' → ');
                const topPerks = c.total_perks.slice(0, 3).map(p => p.name).join(', ');

                return `
                <tr class="border-b border-slate-100 hover:bg-amber-50 vip-row"
                    data-competitor="${c.competitor_id}">
                        <td class="px-4 py-3 font-medium">
                            <span class="text-amber-700 hover:underline cursor-pointer" 
                                onclick="selectCompetitorVip('${c.competitor_id}', '${c.competitor_name}')">${c.competitor_name}</span>
                            <button class="ml-2 text-slate-400 hover:text-amber-600" onclick="toggleVipDetails('vip-${c.competitor_id}')">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </button>
                        </td>
                        <td class="px-4 py-3 text-slate-500">${c.market_id}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-3 py-1 rounded-full text-sm font-bold ${scoreColor}">
                                ${c.score}/100
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs">${tierNames || 'No tiers'}</td>
                        <td class="px-4 py-3 text-xs text-slate-500">${topPerks || 'No perks'}</td>
                    </tr>
                <tr id="vip-${c.competitor_id}" class="hidden bg-amber-50/50">
                    <td colspan="5" class="p-4">
                        <div class="grid grid-cols-3 gap-4">
                            ${c.tiers.map(t => `
                                    <div class="bg-white rounded-lg p-3 border border-slate-200">
                                        <div class="font-medium text-sm mb-2">${t.name} (Tier ${t.order})</div>
                                        <div class="text-xs text-slate-500 mb-2">
                                            ${t.entry_requirements?.min_deposit ? `Min: $${t.entry_requirements.min_deposit}` : ''}
                                            ${t.entry_requirements?.wagering ? ` | Wager: $${t.entry_requirements.wagering}` : ''}
                                            ${t.entry_requirements?.invite_only ? ' | Invite only' : ''}
                                        </div>
                                        <div class="text-xs">
                                            ${(t.perks || []).map(p => `<span class="inline-block px-2 py-0.5 bg-amber-100 text-amber-700 rounded mr-1 mb-1">${p.name} (${p.points}pts)</span>`).join('')}
                                        </div>
                                    </div>
                                `).join('')}
                        </div>
                    </td>
                </tr>
            `;
            }).join('');
        }

        function toggleVipDetails(id) {
            const row = document.getElementById(id);
            if (row) row.classList.toggle('hidden');
        }

        let selectedVipCompetitor = null;

        function selectCompetitorVip(competitorId, competitorName) {
            selectedVipCompetitor = competitorId;

            // Highlight selected row
            document.querySelectorAll('.vip-row').forEach(r => {
                r.classList.remove('bg-amber-100');
                if (r.dataset.competitor === competitorId) {
                    r.classList.add('bg-amber-100');
                }
            });

            // Update editor header
            const editorHeader = document.querySelector('#panel-vip .mt-6 h2');
            if (editorHeader) {
                editorHeader.innerHTML = `< i class="fa-solid fa-edit mr-2 text-amber-500" ></i > VIP Tiers: ${competitorName} `;
            }

            // Filter the tier editor table
            filterVipTierEditor(competitorId);
        }

        function filterVipTierEditor(competitorId) {
            const tbody = document.getElementById('vip-table-body');
            if (!tbody) return;

            // Filter vipData to this competitor and re-render
            const filtered = vipData.filter(v => v.competitor_id === competitorId);

            if (filtered.length === 0) {
                tbody.innerHTML = `< tr > <td colspan="5" class="text-center py-8 text-slate-400">No tiers for this competitor. Click "Add VIP Tier" to create one.</td></tr > `;
                return;
            }

            tbody.innerHTML = filtered.map(tier => `
                < tr class="border-b border-slate-100 hover:bg-slate-50" >
                    <td class="px-4 py-3 font-medium">${tier.competitor_name || competitorId}</td>
                    <td class="px-4 py-3">${tier.tier_name}</td>
                    <td class="px-4 py-3 text-center">${tier.tier_order}</td>
                    <td class="px-4 py-3 text-xs text-slate-500">
                        ${tier.entry_requirements ? formatEntryReqs(tier.entry_requirements) : '-'}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <button onclick="deleteVipTier(${tier.id})" class="text-red-500 hover:text-red-700">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr >
                `).join('');
        }

        function formatEntryReqs(reqs) {
            if (!reqs || typeof reqs !== 'object') return '-';
            const parts = [];
            if (reqs.min_deposit) parts.push(`Min: $${reqs.min_deposit} `);
            if (reqs.wagering) parts.push(`Wager: $${reqs.wagering}`);
            if (reqs.invite_only) parts.push('Invite only');
            return parts.join(' | ') || '-';
        }
    </script>
@endpush
