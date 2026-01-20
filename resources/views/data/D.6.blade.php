@extends('layouts.dashboard')


@section('title', 'D.6 Competitor Data | CRMTracker')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
            color: #334155;
        }

        .upload-zone {
            transition: all 0.2s;
        }

        .upload-zone.dragover {
            border-color: #7c3aed;
            background-color: #f5f3ff;
        }

        .module-status {
            cursor: help;
        }

        .coverage-bar {
            height: 6px;
            background: #e2e8f0;
            border-radius: 3px;
            overflow: hidden;
        }

        .coverage-fill {
            height: 100%;
            background: linear-gradient(90deg, #22c55e, #16a34a);
        }

        .coverage-gap {
            height: 100%;
            background: #fbbf24;
        }
</style>
@endpush

@section('content')
<main class="flex-1 ml-20 p-8 max-w-[1800px] mx-auto overflow-x-hidden">
<header class="flex justify-between items-center mb-6">
<div class="flex items-center gap-3">
<span
class="text-xs font-medium bg-purple-50 text-purple-700 px-2 py-1 rounded border border-purple-200">Data
Module D.6</span>
<span id="competitor-count-badge"
class="text-xs font-medium bg-green-50 text-green-700 px-2 py-1 rounded border border-green-200">
<i class="fa-solid fa-spinner fa-spin text-xs mr-1"></i>Loading...
</span>
<div id="date-range-container" class="flex items-center"></div>
<button onclick="window.location.href='upload.html'"
class="px-6 py-2.5 rounded-lg font-medium shadow-sm flex items-center gap-2 transition-colors bg-purple-600 hover:bg-purple-700 text-white">
<i class="fa-solid fa-upload"></i>
Upload New Data
</button>
</header>
<!-- Filters - Market selection via top nav bar only -->
<div class="bg-white rounded-xl border border-slate-200 p-4 mb-6 flex items-center gap-4 flex-wrap">
<!-- Local Market Filter -->
<div class="flex items-center gap-2">
<label class="text-sm text-slate-600">Market:</label>
<select id="filter-market" onchange="updateJurisdictionDropdown(); renderCompetitorTable()"
class="bg-blue-50 border border-blue-200 text-blue-700 text-sm rounded-lg px-3 py-2 font-medium">
<option value="">Loading...</option>
</select>
</div>
<!-- Jurisdiction Filter -->
<div class="flex items-center gap-2">
<label class="text-sm text-slate-600">Jurisdiction:</label>
<select id="filter-jurisdiction" onchange="renderCompetitorTable()"
class="bg-blue-50 border border-blue-200 text-blue-700 text-sm rounded-lg px-3 py-2 font-medium">
<option value="ALL">All</option>
</select>
</div>
<div class="flex items-center gap-2">
<label class="text-sm text-slate-600">Sort By:</label>
<select id="filter-sort" onchange="renderCompetitorTable()"
class="bg-slate-50 border border-slate-200 text-sm rounded-lg px-3 py-2">
<option value="crmt">CRMT Score</option>
<option value="visits" disabled title="Coming Soon">Visits (Coming Soon)</option>
<option value="revenue" disabled title="Coming Soon">Revenue (Coming Soon)</option>
</select>
</div>
<div class="flex items-center gap-2">
<label class="text-sm text-slate-600">Completeness:</label>
<select id="filter-completeness" onchange="renderCompetitorTable()"
class="bg-slate-50 border border-slate-200 text-sm rounded-lg px-3 py-2">
<option value="all">All</option>
<option value="complete">Complete (100%)</option>
<option value="partial">Partial (40-80%)</option>
<option value="minimal">Minimal (&lt;40%)</option>
</select>
</div>
<div class="flex-1">
<input type="text" id="filter-search" placeholder="Search competitors..."
oninput="renderCompetitorTable()"
class="w-full bg-slate-50 border border-slate-200 text-sm rounded-lg px-3 py-2">
</div>
</div>
<!-- Competitor Table -->
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
<table class="w-full">
<thead class="bg-slate-50 border-b border-slate-200">
<tr>
<th class="w-10 py-3 px-2 text-center">
<input type="checkbox" id="select-all" onchange="toggleSelectAll(this)"
class="w-4 h-4 rounded border-slate-300 text-purple-600 focus:ring-purple-500">
</th>
<th
class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">
Competitor</th>
<th
class="text-left py-3 px-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">
Market</th>
<th
class="text-left py-3 px-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">
Jurisdiction</th>
<th class="text-center py-3 px-2 text-xs font-semibold text-slate-600 uppercase tracking-wider"
title="License Status">
<i class="fa-solid fa-certificate"></i>
</th>
<th
class="text-center py-3 px-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">
Hits</th>
<th
class="text-left py-3 px-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">
Coverage</th>
<th
class="text-center py-3 px-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">
Score</th>
<th class="text-center py-3 px-3 text-xs font-semibold text-slate-600 uppercase tracking-wider"
title="CRMT Ranking">
<i class="fa-solid fa-ranking-star"></i>
</th>
<th class="text-center py-3 px-2 text-xs font-semibold text-slate-600 uppercase tracking-wider"
title="D.2 Corporate Intelligence">
<i class="fa-solid fa-building"></i>
</th>
<th class="text-center py-3 px-2 text-xs font-semibold text-slate-600 uppercase tracking-wider"
title="D.3 Compliance">
<i class="fa-solid fa-id-card"></i>
</th>
<th class="text-center py-3 px-2 text-xs font-semibold text-slate-600 uppercase tracking-wider"
title="D.4 CRM Tech Stack">
<i class="fa-solid fa-envelope"></i>
</th>
<th class="text-center py-3 px-2 text-xs font-semibold text-slate-600 uppercase tracking-wider"
title="D.5 Offers">
<i class="fa-solid fa-tags"></i>
</th>
<th class="text-center py-3 px-2 text-xs font-semibold text-slate-600 uppercase tracking-wider"
title="D.7 Product Intelligence">
<i class="fa-solid fa-box"></i>
</th>
</tr>
</thead>
<tbody id="competitor-table-body">
<!-- Loading indicator - replaced by JS -->
<tr>
<td colspan="12" class="py-8 text-center text-slate-400">
<i class="fa-solid fa-spinner fa-spin mr-2"></i>Loading competitors...
</td>
</tr>
</tbody>
</table>
</div>
<!-- Saved Groups Panel -->
<div class="bg-white rounded-xl border border-slate-200 mt-6 overflow-hidden">
<button onclick="toggleGroupsPanel()"
class="w-full px-4 py-3 flex items-center justify-between bg-slate-50 hover:bg-slate-100 transition-colors">
<div class="flex items-center gap-2">
<i class="fa-solid fa-folder text-purple-500"></i>
<span class="font-semibold text-slate-700">Saved Groups</span>
<span id="groups-count"
class="text-xs bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full">0</span>
</div>
<i id="groups-chevron" class="fa-solid fa-chevron-down text-slate-400 transition-transform"></i>
</button>
<div id="groups-panel" class="hidden border-t border-slate-200">
<table class="w-full text-sm" id="groups-table">
<thead class="bg-slate-50">
<tr>
<th class="text-left py-2 px-4 font-semibold text-slate-600">Group Name</th>
<th class="text-left py-2 px-3 font-semibold text-slate-600">Market</th>
<th class="text-left py-2 px-3 font-semibold text-slate-600">Jurisdiction</th>
<th class="text-center py-2 px-3 font-semibold text-slate-600">Size</th>
<th class="text-center py-2 px-3 font-semibold text-slate-600">Status</th>
<th class="text-center py-2 px-3 font-semibold text-slate-600">Actions</th>
</tr>
</thead>
<tbody id="groups-list">
<!-- Populated by JS -->
</tbody>
</table>
<div id="groups-empty" class="py-8 text-center text-slate-400">
<i class="fa-solid fa-folder-open text-3xl mb-2"></i>
<p class="text-sm">No groups saved for this market yet</p>
<p class="text-xs">Select 3, 5, or 10 competitors below to create a group</p>
</div>
</div>
</div>
<!-- Summary Stats -->
<div class="grid grid-cols-4 gap-4 mt-6">
<div class="bg-white rounded-xl border border-slate-200 p-4">
<div class="text-2xl font-bold text-slate-800">0</div>
<div class="text-sm text-slate-500">Total Competitors</div>
</div>
<div class="bg-white rounded-xl border border-slate-200 p-4">
<div class="text-2xl font-bold text-green-600">0</div>
<div class="text-sm text-slate-500">Total Data Rows</div>
</div>
<div class="bg-white rounded-xl border border-slate-200 p-4">
<div class="text-2xl font-bold text-amber-600">0%</div>
<div class="text-sm text-slate-500">Avg Completeness</div>
</div>
<div class="bg-white rounded-xl border border-slate-200 p-4">
<div class="text-2xl font-bold text-purple-600">0</div>
<div class="text-sm text-slate-500">Coverage Gaps</div>
</div>
</div>
</main>
</div>
<!-- Floating Action Bar -->
<div id="selection-bar"
class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-slate-800 text-white px-6 py-3 rounded-xl shadow-2xl hidden flex items-center gap-4 transition-all z-50">
<div class="flex items-center gap-2">
<span id="selection-count" class="text-xl font-bold">0</span>
<span class="text-slate-300">selected</span>
</div>
<span id="selection-hint" class="text-sm text-slate-400">Select 3, 5, or 7</span>
<button id="create-group-btn" onclick="showCreateGroupModal()" disabled
class="px-4 py-2 bg-purple-600 hover:bg-purple-700 disabled:bg-slate-600 disabled:cursor-not-allowed text-white rounded-lg font-medium transition-colors">
<i class="fa-solid fa-folder-plus mr-2"></i>Create Group
</button>
<button onclick="clearSelection()" class="px-3 py-2 hover:bg-slate-700 rounded-lg transition-colors"
title="Clear selection">
<i class="fa-solid fa-xmark"></i>
</button>
</div>
<!-- Create Group Modal -->
<div id="create-group-modal" class="hidden fixed inset-0 bg-slate-900/50 z-50 flex items-center justify-center">
<div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 shadow-2xl">
<h3 class="text-lg font-bold text-slate-800 mb-4">Create Competitor Group</h3>
<!-- Market Info -->
<div class="mb-4 bg-purple-50 rounded-lg p-3 flex items-center gap-3">
<i class="fa-solid fa-map-marker-alt text-purple-500"></i>
<div>
<p class="text-xs text-purple-600 font-medium">Market / Jurisdiction</p>
<p id="modal-market-info" class="text-sm font-semibold text-slate-800">Loading...</p>
</div>
</div>
<div class="mb-4">
<label class="block text-sm font-medium text-slate-700 mb-2">Group Name</label>
<input type="text" id="group-name-input" placeholder="e.g., Top Challengers"
class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
<p class="text-xs text-slate-500 mt-1">ID will be: <span id="group-id-preview"
class="font-mono text-purple-600">...</span></p>
</div>
<div class="mb-4">
<label class="block text-sm font-medium text-slate-700 mb-2">Selected Competitors</label>
<div id="selected-competitors-list"
class="bg-slate-50 rounded-lg p-3 text-sm text-slate-600 max-h-32 overflow-y-auto">
<!-- Populated by JS -->
</div>
</div>
<div class="flex gap-3">
<button onclick="closeCreateGroupModal()"
class="flex-1 px-4 py-2 border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-50">Cancel</button>
<button onclick="createGroup()"
class="flex-1 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium">
<i class="fa-solid fa-check mr-2"></i>Create Group
</button>
</div>
</div>
</div>
<!-- Upload Modal -->
<div id="upload-modal" class="hidden fixed inset-0 bg-slate-900/50 z-50 flex items-center justify-center">
<div class="bg-white rounded-2xl p-8 max-w-2xl w-full mx-4 shadow-2xl max-h-[90vh] overflow-y-auto">
<div class="flex justify-between items-center mb-6">
<h3 class="text-xl font-bold text-slate-800">Upload New Data</h3>
<button onclick="closeUploadModal()" class="text-slate-400 hover:text-slate-600">
<i class="fa-solid fa-times text-xl"></i>
</button>
</div>
<!-- Step 1: Select Market -->
<div id="upload-step-1">
<label class="block text-sm font-medium text-slate-700 mb-3">Select Market & Jurisdiction</label>
<div class="grid grid-cols-2 gap-3 mb-6">
<button onclick="selectMarket('CA', 'ON')"
class="market-btn p-4 border-2 border-slate-200 rounded-lg hover:border-purple-500 transition-colors text-left">
<span class="text-2xl">🇨🇦</span>
<p class="font-medium text-slate-800 mt-1">Canada - Ontario</p>
<p class="text-xs text-slate-500">AGCO Regulated</p>
</button>
<button onclick="selectMarket('CA', 'ROC')"
class="market-btn p-4 border-2 border-slate-200 rounded-lg hover:border-purple-500 transition-colors text-left">
<span class="text-2xl">🇨🇦</span>
<p class="font-medium text-slate-800 mt-1">Canada - ROC</p>
<p class="text-xs text-slate-500">CASL Regulated</p>
</button>
<button onclick="selectMarket('UK', 'GB')"
class="market-btn p-4 border-2 border-slate-200 rounded-lg hover:border-purple-500 transition-colors text-left">
<span class="text-2xl">🇬🇧</span>
<p class="font-medium text-slate-800 mt-1">United Kingdom</p>
<p class="text-xs text-slate-500">UKGC Regulated</p>
</button>
<button onclick="selectMarket('CH', 'ALL')"
class="market-btn p-4 border-2 border-slate-200 rounded-lg hover:border-purple-500 transition-colors text-left">
<span class="text-2xl">🇨🇭</span>
<p class="font-medium text-slate-800 mt-1">Switzerland</p>
<p class="text-xs text-slate-500">ESBK/CFMJ Regulated</p>
</button>
<button onclick="selectMarket('TR', 'ALL')"
class="market-btn p-4 border-2 border-slate-200 rounded-lg hover:border-purple-500 transition-colors text-left">
<span class="text-2xl">🇹🇷</span>
<p class="font-medium text-slate-800 mt-1">Turkey</p>
<p class="text-xs text-slate-500">Grey Market</p>
</button>
<button onclick="selectMarket('AR', 'CABA')"
class="market-btn p-4 border-2 border-slate-200 rounded-lg hover:border-purple-500 transition-colors text-left">
<span class="text-2xl">🇦🇷</span>
<p class="font-medium text-slate-800 mt-1">Argentina - CABA</p>
<p class="text-xs text-slate-500">LOTBA Regulated</p>
</button>
</div>
</div>
<!-- Step 2: Upload File -->
<div id="upload-step-2" class="hidden">
<div class="mb-4">
<span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded font-medium"
id="selected-market-badge"></span>
</div>
<div id="upload-zone"
class="upload-zone border-2 border-dashed border-slate-300 rounded-xl p-8 text-center cursor-pointer hover:border-purple-500 transition-colors">
<div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
<i class="fa-solid fa-cloud-arrow-up text-purple-600 text-2xl"></i>
</div>
<p class="text-slate-600 mb-2">Drop your file here or <span
class="text-purple-600 font-medium">browse</span></p>
<p class="text-xs text-slate-400">Supports .xlsx, .xls, .csv</p>
<input type="file" id="file-input" class="hidden" accept=".xlsx,.xls,.csv"
onchange="handleFileSelect(event)">
<div id="date-range-container" class="flex items-center"></div>
<button onclick="goBackToStep1()" class="mt-4 text-sm text-slate-500 hover:text-slate-700">
<i class="fa-solid fa-arrow-left mr-1"></i>Change Market
</button>
</div>
</div>
<!-- Step 3: Confirm with duplicate detection -->
<div id="upload-step-3" class="hidden">
<div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
<div class="flex items-center gap-2 text-green-700 mb-1">
<i class="fa-solid fa-check-circle"></i>
<span class="font-medium">File Parsed Successfully</span>
</div>
<p class="text-sm text-green-600" id="confirm-filename"></p>
</div>
<div class="mb-6">
<div class="flex justify-between text-sm mb-2">
<span class="text-slate-600">Market:</span>
<span class="font-medium text-slate-800" id="confirm-market"></span>
</div>
<div class="flex justify-between text-sm mb-2">
<span class="text-slate-600">Date Range:</span>
<span class="font-medium text-slate-800" id="confirm-daterange"></span>
</div>
</div>
<div class="mb-6">
<h4 class="text-sm font-medium text-slate-700 mb-3">Competitors Found</h4>
<div class="bg-slate-50 rounded-lg border border-slate-200 overflow-hidden">
<table class="w-full text-sm">
<thead class="bg-slate-100">
<tr>
<th class="text-left py-2 px-3 text-xs font-medium text-slate-600">
Competitor</th>
<th class="text-center py-2 px-3 text-xs font-medium text-slate-600">New
Rows</th>
<th class="text-center py-2 px-3 text-xs font-medium text-slate-600">
Duplicates</th>
<th class="text-center py-2 px-3 text-xs font-medium text-slate-600">Status
</th>
</tr>
</thead>
<tbody id="confirm-competitors-table">
<!-- Populated by JS -->
</tbody>
</table>
</div>
</div>
<div class="bg-slate-100 rounded-lg p-4 mb-6">
<div class="flex justify-between text-sm">
<span class="text-slate-600">Total new rows to import:</span>
<span class="font-bold text-green-600" id="confirm-new-rows">0</span>
</div>
<div class="flex justify-between text-sm">
<span class="text-slate-600">Duplicates skipped:</span>
<span class="font-medium text-slate-500" id="confirm-duplicates">0</span>
</div>
</div>
<div class="flex gap-3">
<button onclick="goBackToStep2()"
class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-700 rounded-lg font-medium hover:bg-slate-50 transition-colors">
<i class="fa-solid fa-arrow-left mr-2"></i>Back
</button>
<button onclick="confirmUpload()"
class="flex-1 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition-colors">
<i class="fa-solid fa-check mr-2"></i>Confirm & Import
</button>
</div>
</div>
</div>
</div>
<script>
// ============ SELECTION STATE ============
let selectedCompetitorIds = new Set();
let editingGroupId = null; // Track if we're editing an existing group
// Valid group sizes
const VALID_SIZES = [3, 5, 7];
// Get current filters - uses local market dropdown
function getFilters() {
// Get country and jurisdiction from separate dropdowns
const country = document.getElementById('filter-market')?.value || 'TR';
const jurisdiction = document.getElementById('filter-jurisdiction')?.value || 'ALL';
// Combine into full marketId format (e.g., 'TR-ALL', 'CA-ON')
const marketId = `${country}-${jurisdiction}`;
console.log('[D.6] getFilters:', { country, jurisdiction, marketId });
return {
market: country,
jurisdiction: jurisdiction,
marketId: marketId,  // Full market ID like 'TR-ALL'
sort: document.getElementById('filter-sort')?.value || 'crmt',
completeness: document.getElementById('filter-completeness')?.value || 'all',
search: document.getElementById('filter-search')?.value?.toLowerCase() || ''
};
}
// ============ TABLE RENDERING ============
async function renderCompetitorTable() {
if (typeof CRMT === 'undefined') {
console.warn('CRMT data not loaded');
return;
}
const filters = getFilters();
const tableBody = document.getElementById('competitor-table-body');
if (!tableBody) return;
// Show loading
tableBody.innerHTML = '<tr><td colspan="12" class="py-8 text-center text-slate-400"><i class="fa-solid fa-spinner fa-spin mr-2"></i>Loading competitors...</td></tr>';
let competitors = [];
// Always load from database using the selected market
try {
let dbCompetitors;
// If 'All Jurisdictions' selected, query by country prefix
if (filters.jurisdiction === 'ALL') {
// Get all competitors for this country (any jurisdiction)
dbCompetitors = await CRMT.dal.getCompetitorsByCountry(filters.market);
console.log('[D.6] Loaded', dbCompetitors.length, 'competitors for country', filters.market);
} else {
// Load from DAL (database) using full marketId
dbCompetitors = await CRMT.dal.getCompetitors(filters.marketId);
console.log('[D.6] Loaded', dbCompetitors.length, 'competitors for market', filters.marketId);
}
competitors = dbCompetitors.map(c => ({
id: c.id,
shortName: c.short_name || c.name,
fullName: c.name,
market: c.market_id || 'unknown',
tier: c.tier || 'unknown',
license: { status: c.license_status || 'unknown' },
crm: { emailCount: c.email_count || 0 },
completeness: {
percentage: c.completeness_pct || 0,
modules: {
d2: c.has_d2 || false,  // Corporate Intelligence
d3: c.has_d3 || false,  // Compliance
d4: c.has_d4 || false,  // CRM Tech Stack
d5: c.has_d5 || false,  // Promotional Offers
d7: c.has_d7 || false   // Product Intelligence
}
},
rankings: { crmt: c.rank_crmt || 999 }
}));
console.log('[D.6] Loaded', competitors.length, 'competitors from database');
} catch (e) {
console.error('[D.6] Failed to load from DB:', e);
tableBody.innerHTML = '<tr><td colspan="13" class="py-8 text-center text-red-400"><i class="fa-solid fa-exclamation-circle mr-2"></i>Failed to load competitors</td></tr>';
return;
}
// Apply filters
if (filters.completeness !== 'all') {
competitors = competitors.filter(c => {
const pct = c.completeness.percentage;
if (filters.completeness === 'complete') return pct === 100;
if (filters.completeness === 'partial') return pct >= 40 && pct < 100;
if (filters.completeness === 'minimal') return pct < 40;
return true;
});
}
if (filters.search) {
competitors = competitors.filter(c =>
c.shortName.toLowerCase().includes(filters.search) ||
c.fullName.toLowerCase().includes(filters.search)
);
}
// Sort
competitors = [...competitors].sort((a, b) => {
if (filters.sort === 'crmt') {
return (a.rankings?.crmt || 999) - (b.rankings?.crmt || 999);
}
return 0; // visits/revenue not yet available
});
// Tier badge helper
const getTierBadge = (tier) => {
const badges = {
'leader': '<span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded font-medium">Leader</span>',
'challenger': '<span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded font-medium">Challenger</span>',
'niche': '<span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-medium">Niche</span>'
};
return badges[tier] || '';
};
// Market display helper - handles both local config and DB market IDs
const getMarketDisplay = (marketId) => {
if (!marketId || marketId === 'unknown') return '🌐 Unknown';
// Check local CRMT.markets config first
if (CRMT.markets[marketId]) {
return `${CRMT.markets[marketId].flag} ${CRMT.markets[marketId].jurisdiction}`;
}
// Parse market ID format like "US-NJ" or "CA-ON"
const marketFlags = {
'US': '🇺🇸', 'CA': '🇨🇦', 'UK': '🇬🇧', 'AR': '🇦🇷'
};
const jurisdictionNames = {
'NJ': 'New Jersey', 'CO': 'Colorado', 'ON': 'Ontario',
'ROC': 'Rest of Canada', 'GB': 'Great Britain'
};
const [country, jurisdiction] = marketId.split('-');
const flag = marketFlags[country] || '🌐';
const name = jurisdictionNames[jurisdiction] || jurisdiction || marketId;
return `${flag} ${name}`;
};
// Parse market ID into separate components
const parseMarketId = (marketId) => {
if (!marketId || marketId === 'unknown') {
return { flag: '🌐', country: 'Unknown', jurisdiction: '-' };
}
const marketFlags = {
'US': '🇺🇸', 'CA': '🇨🇦', 'UK': '🇬🇧', 'AR': '🇦🇷', 'CH': '🇨🇭', 'TR': '🇹🇷', 'MX': '🇲🇽', 'BR': '🇧🇷'
};
const countryNames = {
'US': 'USA', 'CA': 'Canada', 'UK': 'UK', 'AR': 'Argentina', 'CH': 'Switzerland', 'TR': 'Turkey', 'MX': 'Mexico', 'BR': 'Brazil'
};
const jurisdictionNames = {
'NJ': 'New Jersey', 'CO': 'Colorado', 'ON': 'Ontario', 'ALL': 'All',
'ROC': 'Rest of Canada', 'GB': 'Great Britain', 'CABA': 'CABA', 'LOTBA': 'LOTBA'
};
const [countryCode, jurisdictionCode] = marketId.split('-');
return {
flag: marketFlags[countryCode] || '🌐',
country: countryNames[countryCode] || countryCode || 'Unknown',
jurisdiction: jurisdictionNames[jurisdictionCode] || jurisdictionCode || '-'
};
};
// Module status icons
const checkIcon = '<i class="fa-solid fa-check text-green-500"></i>';
const xIcon = '<i class="fa-solid fa-xmark text-red-400"></i>';
// Generate table rows
tableBody.innerHTML = competitors.map(c => {
const isSelected = selectedCompetitorIds.has(c.id);
const completeness = c.completeness.percentage;
const colorClass = completeness === 100 ? 'bg-green-100 text-green-700' :
completeness >= 60 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700';
const crmtRank = c.rankings?.crmt || '-';
const licenseIcon = c.license.status === 'licensed'
? '<i class="fa-solid fa-circle-check text-green-500"></i>'
: '<i class="fa-solid fa-triangle-exclamation text-amber-500"></i>';
// Parse market info
const marketParts = parseMarketId(c.market);
return `
<tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors ${isSelected ? 'bg-purple-50' : ''}">
<td class="py-3 px-2 text-center">
<input type="checkbox" data-id="${c.id}" onchange="toggleCompetitorSelection('${c.id}')"
${isSelected ? 'checked' : ''}
class="w-4 h-4 rounded border-slate-300 text-purple-600 focus:ring-purple-500">
</td>
<td class="py-3 px-4">
<div class="flex items-center gap-2">
<span class="font-medium text-slate-800">${c.shortName}</span>
${getTierBadge(c.tier)}
</div>
</td>
<td class="py-3 px-3">
<span class="text-sm">${marketParts.flag} ${marketParts.country}</span>
</td>
<td class="py-3 px-3">
<span class="text-sm text-slate-600">${marketParts.jurisdiction}</span>
</td>
<td class="py-3 px-2 text-center" title="${c.license.status}">
${licenseIcon}
</td>
<td class="py-3 px-3 text-center"><span class="text-sm font-medium">${c.crm.emailCount || '-'}</span></td>
<td class="py-3 px-3">
<div class="w-20 h-2 bg-slate-100 rounded-full overflow-hidden">
<div class="h-full ${completeness === 100 ? 'bg-green-500' : completeness >= 60 ? 'bg-amber-500' : 'bg-red-500'}" style="width: ${completeness}%"></div>
</div>
</td>
<td class="py-3 px-3 text-center">
<span class="inline-flex items-center justify-center w-10 h-6 ${colorClass} rounded-full text-xs font-bold">${completeness}%</span>
</td>
<td class="py-3 px-3 text-center">
<span class="inline-flex items-center justify-center w-6 h-6 bg-purple-100 text-purple-700 rounded text-xs font-bold">#${crmtRank}</span>
</td>
<td class="py-3 px-2 text-center">${c.completeness.modules.d2 ? checkIcon : xIcon}</td>
<td class="py-3 px-2 text-center">${c.completeness.modules.d3 ? checkIcon : xIcon}</td>
<td class="py-3 px-2 text-center">${c.completeness.modules.d4 ? checkIcon : xIcon}</td>
<td class="py-3 px-2 text-center">${c.completeness.modules.d5 ? checkIcon : xIcon}</td>
<td class="py-3 px-2 text-center">${c.completeness.modules.d7 ? checkIcon : xIcon}</td>
</tr>
`;
}).join('');
// Update summary stats
updateSummaryStats(competitors);
updateSelectionBar();
renderSavedGroups();
}
function updateSummaryStats(competitors) {
// Ensure numeric addition by parsing values
const totalEmails = competitors.reduce((sum, c) => sum + (parseInt(c.crm.emailCount, 10) || 0), 0);
const avgCompleteness = competitors.length > 0
? Math.round(competitors.reduce((sum, c) => sum + (parseInt(c.completeness.percentage, 10) || 0), 0) / competitors.length)
: 0;
// Update badge
const badge = document.getElementById('competitor-count-badge');
if (badge) {
badge.innerHTML = `<i class="fa-solid fa-check text-xs mr-1"></i>${competitors.length} Competitors`;
}
const statCards = document.querySelectorAll('.grid.grid-cols-4 .bg-white');
if (statCards.length >= 4) {
statCards[0].querySelector('.text-2xl').textContent = competitors.length.toLocaleString();
statCards[1].querySelector('.text-2xl').textContent = totalEmails.toLocaleString();
statCards[2].querySelector('.text-2xl').textContent = avgCompleteness + '%';
}
}
// ============ SELECTION MANAGEMENT ============
function toggleCompetitorSelection(competitorId) {
if (selectedCompetitorIds.has(competitorId)) {
selectedCompetitorIds.delete(competitorId);
} else {
selectedCompetitorIds.add(competitorId);
}
updateSelectionBar();
// Update row highlight
document.querySelectorAll(`[data-id="${competitorId}"]`).forEach(cb => {
cb.closest('tr').classList.toggle('bg-purple-50', cb.checked);
});
}
function toggleSelectAll(checkbox) {
const checkboxes = document.querySelectorAll('#competitor-table-body input[type="checkbox"]');
checkboxes.forEach(cb => {
const id = cb.dataset.id;
if (checkbox.checked) {
selectedCompetitorIds.add(id);
} else {
selectedCompetitorIds.delete(id);
}
cb.checked = checkbox.checked;
cb.closest('tr').classList.toggle('bg-purple-50', checkbox.checked);
});
updateSelectionBar();
}
function clearSelection() {
selectedCompetitorIds.clear();
document.querySelectorAll('#competitor-table-body input[type="checkbox"]').forEach(cb => {
cb.checked = false;
cb.closest('tr').classList.remove('bg-purple-50');
});
document.getElementById('select-all').checked = false;
updateSelectionBar();
}
function updateSelectionBar() {
const bar = document.getElementById('selection-bar');
const countEl = document.getElementById('selection-count');
const hintEl = document.getElementById('selection-hint');
const btn = document.getElementById('create-group-btn');
const count = selectedCompetitorIds.size;
if (count === 0) {
bar.classList.add('hidden');
bar.classList.remove('flex');
return;
}
bar.classList.remove('hidden');
bar.classList.add('flex');
countEl.textContent = count;
// Determine hint and button state
const isValidSize = VALID_SIZES.includes(count);
btn.disabled = !isValidSize;
if (isValidSize) {
hintEl.textContent = '✓ Valid group size';
hintEl.classList.remove('text-slate-400');
hintEl.classList.add('text-green-400');
} else {
const nextValid = VALID_SIZES.find(s => s > count) || 10;
const diff = nextValid - count;
hintEl.textContent = `Select ${diff} more for ${nextValid}`;
hintEl.classList.add('text-slate-400');
hintEl.classList.remove('text-green-400');
}
}
// ============ GROUP MANAGEMENT ============
function toggleGroupsPanel() {
const panel = document.getElementById('groups-panel');
const chevron = document.getElementById('groups-chevron');
panel.classList.toggle('hidden');
chevron.classList.toggle('rotate-180');
}
async function renderSavedGroups() {
const list = document.getElementById('groups-list');
const empty = document.getElementById('groups-empty');
const countEl = document.getElementById('groups-count');
try {
// Fetch groups from database API
const res = await fetch('/.netlify/functions/groups');
if (!res.ok) throw new Error('Failed to fetch groups');
const groups = await res.json();
// Filter by current market (optional - show all if "ALL" selected)
const filters = getFilters();
const marketId = filters.marketId;
const filteredGroups = marketId && marketId !== 'ALL'
? groups.filter(g => g.market_id === marketId)
: groups;
countEl.textContent = filteredGroups.length;
if (filteredGroups.length === 0) {
list.innerHTML = '';
empty.classList.remove('hidden');
return;
}
empty.classList.add('hidden');
// Get active group from navBar or localStorage
const activeId = CRMT.navBar?.getSelectedGroup()?.id || localStorage.getItem('navBar_groupId');
list.innerHTML = filteredGroups.map(g => {
const flag = g.flag || '📁';
const marketParts = (g.market_id || '').split('-');
const country = marketParts[0] || '';
const jurisdiction = marketParts[1] || 'ALL';
return `
<tr class="border-b border-slate-100 hover:bg-slate-50 ${g.id === activeId ? 'bg-purple-50' : ''}">
<td class="py-3 px-4">
<div class="flex items-center gap-2 cursor-pointer" onclick="setActiveGroup('${g.id}')">
<i class="fa-solid fa-users text-purple-500"></i>
<span class="font-medium text-slate-800">${g.name}</span>
</div>
</td>
<td class="py-3 px-3 text-slate-600">${flag} ${country}</td>
<td class="py-3 px-3 text-slate-600">${jurisdiction}</td>
<td class="py-3 px-3 text-center">
<span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-xs font-medium">${g.competitor_count || g.competitor_ids?.length || 0}</span>
</td>
<td class="py-3 px-3 text-center">
${g.id === activeId ? '<span class="text-xs bg-purple-100 text-purple-700 px-2 py-0.5 rounded">Active</span>' : '<span class="text-xs text-slate-400">-</span>'}
</td>
<td class="py-3 px-3 text-center">
<button onclick="editGroup('${g.id}')" class="text-slate-400 hover:text-blue-600 p-1" title="Edit">
<i class="fa-solid fa-pen-to-square"></i>
</button>
<button onclick="deleteGroupFromAPI('${g.id}')" class="text-slate-400 hover:text-red-600 p-1" title="Delete">
<i class="fa-solid fa-trash"></i>
</button>
</td>
</tr>
`;
}).join('');
} catch (e) {
console.error('[D.6] Failed to load groups:', e);
countEl.textContent = 0;
list.innerHTML = '';
empty.classList.remove('hidden');
}
}
async function setActiveGroup(groupId) {
// Set via navBar if available
if (CRMT.navBar?.setActiveGroup) {
await CRMT.navBar.setActiveGroup(groupId);
} else {
// Fallback to API directly
await fetch(`/.netlify/functions/groups?action=set-active&id=${groupId}`, {
method: 'POST'
});
}
renderSavedGroups();
}
function editGroup(groupId) {
// For now, editing needs to load group from API
// TODO: Implement proper group editing with API
alert('Edit functionality coming soon. Please delete and recreate the group.');
}
async function deleteGroupFromAPI(groupId) {
if (!confirm('Delete this group?')) return;
try {
await fetch(`/.netlify/functions/groups?id=${groupId}`, {
method: 'DELETE'
});
// Also delete from localStorage for backward compatibility
if (CRMT.groups?.delete) {
CRMT.groups.delete(groupId);
}
// Refresh navBar
if (CRMT.navBar?.refreshGroups) {
await CRMT.navBar.refreshGroups();
}
renderSavedGroups();
} catch (e) {
console.error('[D.6] Failed to delete group:', e);
alert('Failed to delete group');
}
}
// Keep old deleteGroup for backward compatibility
function deleteGroup(groupId) {
deleteGroupFromAPI(groupId);
}
// ============ CREATE GROUP MODAL ============
function showCreateGroupModal() {
const modal = document.getElementById('create-group-modal');
modal.classList.remove('hidden');
const filters = getFilters();
const marketKey = filters.marketId; // Use full market ID like 'TR-ALL'
// Flag and name lookup for countries not in CRMT.markets
const countryFlags = { 'CA': '🇨🇦', 'US': '🇺🇸', 'UK': '🇬🇧', 'CH': '🇨🇭', 'TR': '🇹🇷', 'AR': '🇦🇷' };
const countryNames = { 'CA': 'Canada', 'US': 'USA', 'UK': 'United Kingdom', 'CH': 'Switzerland', 'TR': 'Turkey', 'AR': 'Argentina' };
// Populate market info - use filters with flag lookup if CRMT.markets doesn't have it
const marketInfo = document.getElementById('modal-market-info');
if (marketInfo) {
const marketData = CRMT.markets?.[marketKey];
if (marketData) {
marketInfo.textContent = `${marketData.flag} ${marketData.market} · ${marketData.jurisdiction}`;
} else {
// Fallback: build display with flag lookup
const flag = countryFlags[filters.market] || '🌐';
const countryName = countryNames[filters.market] || filters.market;
const jurName = filters.jurisdiction === 'ALL' ? 'All' : filters.jurisdiction;
marketInfo.textContent = `${flag} ${countryName} · ${jurName}`;
}
}
document.getElementById('group-id-preview').textContent = `${filters.market}-${filters.jurisdiction}-...`;
document.getElementById('group-name-input').value = '';
document.getElementById('group-name-input').focus();
// Show selected competitors - get names from table DOM instead of static CRMT data
const selectedNames = [];
document.querySelectorAll('#competitor-table-body input[type="checkbox"]:checked').forEach(cb => {
const row = cb.closest('tr');
if (row) {
const nameCell = row.querySelector('td:nth-child(2) .font-medium');
if (nameCell) {
selectedNames.push(nameCell.textContent.trim());
}
}
});
document.getElementById('selected-competitors-list').innerHTML = selectedNames.length > 0
? selectedNames.map(name => `<div class="flex items-center gap-2 py-1"><span class="font-medium">${name}</span></div>`).join('')
: '<div class="text-slate-400 text-sm">No competitors selected</div>';
// Update preview on input
document.getElementById('group-name-input').oninput = (e) => {
const sanitized = e.target.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '').substring(0, 30);
document.getElementById('group-id-preview').textContent = `${filters.market}-${filters.jurisdiction}-${sanitized || '...'}`;
};
}
function closeCreateGroupModal() {
document.getElementById('create-group-modal').classList.add('hidden');
editingGroupId = null;
}
async function createGroup() {
const label = document.getElementById('group-name-input').value.trim();
if (!label) {
alert('Please enter a group name');
return;
}
const filters = getFilters();
const competitorIds = Array.from(selectedCompetitorIds);
const marketId = filters.marketId || `${filters.market}-${filters.jurisdiction}`;
// Save to localStorage (for backward compatibility)
if (editingGroupId) {
CRMT.groups.update(editingGroupId, competitorIds);
} else {
const group = CRMT.groups.create(label, competitorIds, filters.market, filters.jurisdiction);
if (!group) {
alert('Failed to create group. Name may already exist.');
return;
}
}
// Also save to database API so navBar can see it
try {
const groupId = editingGroupId || `${marketId}-${label.toLowerCase().replace(/[^a-z0-9]+/g, '-')}`;
await fetch('/.netlify/functions/groups', {
method: 'POST',
headers: { 'Content-Type': 'application/json' },
body: JSON.stringify({
id: groupId,
name: label,
market_id: marketId,
competitor_ids: competitorIds
})
});
// Refresh navBar groups dropdown
if (CRMT.navBar?.refreshGroups) {
await CRMT.navBar.refreshGroups();
}
} catch (e) {
console.warn('[D.6] Failed to save group to API:', e);
}
closeCreateGroupModal();
clearSelection();
renderSavedGroups();
}
// ============ INITIALIZATION ============
// Jurisdiction options by market
const JURISDICTION_OPTIONS = {
'ALL': [
{ value: 'ALL', label: 'All Jurisdictions' }
],
'CA': [
{ value: 'ON', label: 'Ontario (AGCO)' },
{ value: 'ROC', label: 'Rest of Canada (CASL)' }
],
'UK': [
{ value: 'GB', label: 'Great Britain (UKGC)' }
],
'US': [
{ value: 'ALL', label: 'All US States' },
{ value: 'OH', label: 'Ohio (OCCC)' },
{ value: 'NJ', label: 'New Jersey (DGE)' },
{ value: 'CO', label: 'Colorado (DOR)' },
{ value: 'MI', label: 'Michigan (MGCB)' },
{ value: 'MA', label: 'Massachusetts (MGC)' }
],
'AR': [
{ value: 'LOTBA', label: 'Buenos Aires (LOTBA)' }
]
};
function updateJurisdictionOptions(saveToStorage = true) {
const marketSelect = document.getElementById('filter-market');
const jurisdictionSelect = document.getElementById('filter-jurisdiction');
if (!marketSelect || !jurisdictionSelect) return;
const market = marketSelect.value;
const options = JURISDICTION_OPTIONS[market] || [];
// Get saved jurisdiction or default to first
const savedJurisdiction = localStorage.getItem('crmt_market_jurisdiction');
const validJurisdiction = options.find(o => o.value === savedJurisdiction);
const selectedValue = validJurisdiction ? savedJurisdiction : (options[0]?.value || 'ALL');
jurisdictionSelect.innerHTML = options.map(opt =>
`<option value="${opt.value}" ${opt.value === selectedValue ? 'selected' : ''}>${opt.label}</option>`
).join('');
// Only save to localStorage when explicitly changing (not on init)
if (saveToStorage) {
localStorage.setItem('crmt_market_country', market);
localStorage.setItem('crmt_market_jurisdiction', selectedValue);
}
}
// Store all markets for jurisdiction lookup
let allMarkets = [];
// Load markets from API and populate country dropdown
async function loadMarketsDropdown() {
const select = document.getElementById('filter-market');
if (!select) return;
try {
const res = await fetch('/.netlify/functions/markets');
if (!res.ok) throw new Error('Failed to fetch markets');
const data = await res.json();
// API returns { countries: [...], total: N }
allMarkets = data.countries || [];
select.innerHTML = allMarkets.map(c =>
`<option value="${c.code}">${c.flag} ${c.name}</option>`
).join('');
// Restore saved selection
const savedCountry = localStorage.getItem('crmt_market_country');
if (savedCountry && Array.from(select.options).some(o => o.value === savedCountry)) {
select.value = savedCountry;
}
// Populate jurisdictions for selected country
updateJurisdictionDropdown();
} catch (e) {
console.warn('[D.6] Failed to load markets:', e);
select.innerHTML = '<option value="TR">🇹🇷 Turkey</option>';
}
}
// Update jurisdiction dropdown based on selected country
function updateJurisdictionDropdown() {
const countrySelect = document.getElementById('filter-market');
const jurSelect = document.getElementById('filter-jurisdiction');
if (!countrySelect || !jurSelect) return;
const countryCode = countrySelect.value;
// Find the country in allMarkets (which is now an array of country objects)
const country = allMarkets.find(c => c.code === countryCode);
const jurisdictions = country?.jurisdictions || [];
if (jurisdictions.length <= 1) {
// Only one jurisdiction (or ALL), hide or simplify
jurSelect.innerHTML = '<option value="ALL">All</option>';
} else {
jurSelect.innerHTML = '<option value="ALL">All Jurisdictions</option>' +
jurisdictions.map(j =>
`<option value="${j.code}">${j.name}</option>`
).join('');
}
// Save selection
localStorage.setItem('crmt_market_country', countryCode);
}
// Initialize on page load
document.addEventListener('DOMContentLoaded', async () => {
// Load markets from API first
await loadMarketsDropdown();
// Wait for CRMT to be available
function init() {
if (typeof CRMT !== 'undefined') {
// Initialize jurisdiction dropdown without overwriting localStorage
updateJurisdictionOptions(false);
renderCompetitorTable();
} else {
setTimeout(init, 100);
}
}
init();
});
// Re-render when groups change
window.addEventListener('competitorGroupsChanged', renderSavedGroups);
// ============ END SELECTION/GROUPS ============
let selectedMarket = { country: null, jurisdiction: null };
let parsedData = { filename: '', rows: 0, competitors: [], dateRange: { start: null, end: null } };
// Simulated existing data (would come from backend/storage)
const existingTrackingIds = new Set(['hit_001', 'hit_002', 'hit_003']); // Example
const existingCompetitors = ['CasinoMax', 'VegasCasinoOnline', 'Stake', 'JackpotCityCasino', 'SlotsOfVegas'];
function openUploadModal() {
document.getElementById('upload-modal').classList.remove('hidden');
showStep(1);
}
function closeUploadModal() {
document.getElementById('upload-modal').classList.add('hidden');
showStep(1);
selectedMarket = { country: null, jurisdiction: null };
parsedData = { filename: '', rows: 0, competitors: [], dateRange: { start: null, end: null } };
}
function showStep(step) {
document.getElementById('upload-step-1').classList.toggle('hidden', step !== 1);
document.getElementById('upload-step-2').classList.toggle('hidden', step !== 2);
document.getElementById('upload-step-3').classList.toggle('hidden', step !== 3);
}
function selectMarket(country, jurisdiction) {
selectedMarket = { country, jurisdiction };
const labels = {
'CA-ON': '🇨🇦 Canada - Ontario (AGCO)',
'CA-ROC': '🇨🇦 Canada - Rest of Canada (CASL)',
'UK-GB': '🇬🇧 United Kingdom (UKGC)',
'CH-ALL': '🇨🇭 Switzerland (ESBK/CFMJ)',
'TR-ALL': '🇹🇷 Turkey (Grey Market)',
'AR-CABA': '🇦🇷 Argentina - CABA (LOTBA)'
};
document.getElementById('selected-market-badge').textContent = labels[`${country}-${jurisdiction}`] || `${country}-${jurisdiction}`;
showStep(2);
}
function goBackToStep1() { showStep(1); }
function goBackToStep2() { showStep(2); }
// File handling
const uploadZone = document.getElementById('upload-zone');
uploadZone?.addEventListener('click', () => document.getElementById('file-input').click());
uploadZone?.addEventListener('dragover', (e) => { e.preventDefault(); uploadZone.classList.add('dragover'); });
uploadZone?.addEventListener('dragleave', () => uploadZone.classList.remove('dragover'));
uploadZone?.addEventListener('drop', (e) => {
e.preventDefault();
uploadZone.classList.remove('dragover');
const file = e.dataTransfer.files[0];
if (file) parseFile(file);
});
function handleFileSelect(event) {
const file = event.target.files[0];
if (file) parseFile(file);
}
function parseFile(file) {
parsedData.filename = file.name;
const reader = new FileReader();
reader.onload = function (e) {
try {
const data = new Uint8Array(e.target.result);
const workbook = XLSX.read(data, { type: 'array' });
const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
const jsonData = XLSX.utils.sheet_to_json(firstSheet);
// Store raw data for import
parsedData.rawRows = jsonData;
// Group by competitor and check duplicates
const competitorMap = {};
let minDate = null, maxDate = null;
jsonData.forEach(row => {
const competitor = row.competitor_name || row.competitor_id || 'Unknown';
const trackingId = row.tracking_hit_id;
const rowDate = row.created_at ? new Date(row.created_at) : (row.date ? new Date(row.date) : null);
if (!competitorMap[competitor]) {
competitorMap[competitor] = {
newRows: 0,
duplicates: 0,
isNew: true, // Will be determined by API
competitorId: row.competitor_id || null
};
}
// Always count as new - API handles deduplication
competitorMap[competitor].newRows++;
if (rowDate) {
if (!minDate || rowDate < minDate) minDate = rowDate;
if (!maxDate || rowDate > maxDate) maxDate = rowDate;
}
});
parsedData.competitors = Object.entries(competitorMap).map(([name, data]) => ({ name, ...data }));
parsedData.dateRange = { start: minDate, end: maxDate };
parsedData.rows = jsonData.length;
showConfirmation();
} catch (err) {
alert('Error parsing file: ' + err.message);
}
};
reader.readAsArrayBuffer(file);
}
function showConfirmation() {
document.getElementById('confirm-filename').textContent = parsedData.filename;
document.getElementById('confirm-market').textContent = document.getElementById('selected-market-badge').textContent;
const formatDate = (d) => d ? d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '-';
document.getElementById('confirm-daterange').textContent =
`${formatDate(parsedData.dateRange.start)} → ${formatDate(parsedData.dateRange.end)}`;
const tableBody = document.getElementById('confirm-competitors-table');
let totalNew = 0, totalDupes = 0;
tableBody.innerHTML = parsedData.competitors.map(c => {
totalNew += c.newRows;
totalDupes += c.duplicates;
return `
<tr class="border-t border-slate-200">
<td class="py-2 px-3 font-medium">${c.name}</td>
<td class="py-2 px-3 text-center text-green-600">${c.newRows}</td>
<td class="py-2 px-3 text-center text-slate-400">${c.duplicates}</td>
<td class="py-2 px-3 text-center">
${c.isNew ? '<span class="text-xs bg-purple-100 text-purple-700 px-2 py-0.5 rounded font-medium">NEW ✨</span>' : '<span class="text-xs text-slate-500">Existing</span>'}
</td>
</tr>
`;
}).join('');
document.getElementById('confirm-new-rows').textContent = totalNew.toLocaleString();
document.getElementById('confirm-duplicates').textContent = totalDupes.toLocaleString();
showStep(3);
}
async function confirmUpload() {
const confirmBtn = document.querySelector('#upload-step-3 button:last-child');
const originalText = confirmBtn.innerHTML;
confirmBtn.disabled = true;
confirmBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Importing...';
try {
const marketId = `${selectedMarket.country}-${selectedMarket.jurisdiction}`;
const result = await CRMT.dal.importData({
rows: parsedData.rawRows,
market_id: marketId,
source_file: parsedData.filename
});
if (result.success) {
const stats = result.stats;
alert(`Import Complete!\n\nCompetitors created: ${stats.competitors_created}\nHits inserted: ${stats.hits_inserted}\nHits updated: ${stats.hits_updated}\nHits skipped: ${stats.hits_skipped}`);
closeUploadModal();
location.reload();
} else {
throw new Error(result.error || 'Import failed');
}
} catch (err) {
alert('Import failed: ' + err.message);
confirmBtn.disabled = false;
confirmBtn.innerHTML = originalText;
}
}
// Listen for market changes from navBar
window.addEventListener('navBarChange', () => {
console.log('[D.6] Market changed, reloading table');
renderCompetitorTable();
});
// Initial load after navBar initializes
setTimeout(() => {
if (CRMT.navBar?._markets) {
renderCompetitorTable();
}
}, 500);
</script>
@endsection

@push('page-scripts')
<script>
        // ============ SELECTION STATE ============
        let selectedCompetitorIds = new Set();
        let editingGroupId = null; // Track if we're editing an existing group

        // Valid group sizes
        const VALID_SIZES = [3, 5, 7];

        // Get current filters - uses local market dropdown
        function getFilters() {
            // Get country and jurisdiction from separate dropdowns
            const country = document.getElementById('filter-market')?.value || 'TR';
            const jurisdiction = document.getElementById('filter-jurisdiction')?.value || 'ALL';

            // Combine into full marketId format (e.g., 'TR-ALL', 'CA-ON')
            const marketId = `${country}-${jurisdiction}`;

            console.log('[D.6] getFilters:', { country, jurisdiction, marketId });

            return {
                market: country,
                jurisdiction: jurisdiction,
                marketId: marketId,  // Full market ID like 'TR-ALL'
                sort: document.getElementById('filter-sort')?.value || 'crmt',
                completeness: document.getElementById('filter-completeness')?.value || 'all',
                search: document.getElementById('filter-search')?.value?.toLowerCase() || ''
            };

        }

        // ============ TABLE RENDERING ============
        async function renderCompetitorTable() {
            if (typeof CRMT === 'undefined') {
                console.warn('CRMT data not loaded');
                return;
            }

            const filters = getFilters();
            const tableBody = document.getElementById('competitor-table-body');
            if (!tableBody) return;

            // Show loading
            tableBody.innerHTML = '<tr><td colspan="12" class="py-8 text-center text-slate-400"><i class="fa-solid fa-spinner fa-spin mr-2"></i>Loading competitors...</td></tr>';

            let competitors = [];

            // Always load from database using the selected market
            try {
                let dbCompetitors;

                // If 'All Jurisdictions' selected, query by country prefix
                if (filters.jurisdiction === 'ALL') {
                    // Get all competitors for this country (any jurisdiction)
                    dbCompetitors = await CRMT.dal.getCompetitorsByCountry(filters.market);
                    console.log('[D.6] Loaded', dbCompetitors.length, 'competitors for country', filters.market);
                } else {
                    // Load from DAL (database) using full marketId
                    dbCompetitors = await CRMT.dal.getCompetitors(filters.marketId);
                    console.log('[D.6] Loaded', dbCompetitors.length, 'competitors for market', filters.marketId);
                }

                competitors = dbCompetitors.map(c => ({
                    id: c.id,
                    shortName: c.short_name || c.name,
                    fullName: c.name,
                    market: c.market_id || 'unknown',
                    tier: c.tier || 'unknown',
                    license: { status: c.license_status || 'unknown' },
                    crm: { emailCount: c.email_count || 0 },
                    completeness: {
                        percentage: c.completeness_pct || 0,
                        modules: {
                            d2: c.has_d2 || false,  // Corporate Intelligence
                            d3: c.has_d3 || false,  // Compliance
                            d4: c.has_d4 || false,  // CRM Tech Stack
                            d5: c.has_d5 || false,  // Promotional Offers
                            d7: c.has_d7 || false   // Product Intelligence
                        }
                    },
                    rankings: { crmt: c.rank_crmt || 999 }
                }));
                console.log('[D.6] Loaded', competitors.length, 'competitors from database');
            } catch (e) {
                console.error('[D.6] Failed to load from DB:', e);
                tableBody.innerHTML = '<tr><td colspan="13" class="py-8 text-center text-red-400"><i class="fa-solid fa-exclamation-circle mr-2"></i>Failed to load competitors</td></tr>';
                return;
            }

            // Apply filters
            if (filters.completeness !== 'all') {
                competitors = competitors.filter(c => {
                    const pct = c.completeness.percentage;
                    if (filters.completeness === 'complete') return pct === 100;
                    if (filters.completeness === 'partial') return pct >= 40 && pct < 100;
                    if (filters.completeness === 'minimal') return pct < 40;
                    return true;
                });
            }

            if (filters.search) {
                competitors = competitors.filter(c =>
                    c.shortName.toLowerCase().includes(filters.search) ||
                    c.fullName.toLowerCase().includes(filters.search)
                );
            }

            // Sort
            competitors = [...competitors].sort((a, b) => {
                if (filters.sort === 'crmt') {
                    return (a.rankings?.crmt || 999) - (b.rankings?.crmt || 999);
                }
                return 0; // visits/revenue not yet available
            });

            // Tier badge helper
            const getTierBadge = (tier) => {
                const badges = {
                    'leader': '<span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded font-medium">Leader</span>',
                    'challenger': '<span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded font-medium">Challenger</span>',
                    'niche': '<span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-medium">Niche</span>'
                };
                return badges[tier] || '';
            };

            // Market display helper - handles both local config and DB market IDs
            const getMarketDisplay = (marketId) => {
                if (!marketId || marketId === 'unknown') return '🌐 Unknown';

                // Check local CRMT.markets config first
                if (CRMT.markets[marketId]) {
                    return `${CRMT.markets[marketId].flag} ${CRMT.markets[marketId].jurisdiction}`;
                }

                // Parse market ID format like "US-NJ" or "CA-ON"
                const marketFlags = {
                    'US': '🇺🇸', 'CA': '🇨🇦', 'UK': '🇬🇧', 'AR': '🇦🇷'
                };
                const jurisdictionNames = {
                    'NJ': 'New Jersey', 'CO': 'Colorado', 'ON': 'Ontario',
                    'ROC': 'Rest of Canada', 'GB': 'Great Britain'
                };

                const [country, jurisdiction] = marketId.split('-');
                const flag = marketFlags[country] || '🌐';
                const name = jurisdictionNames[jurisdiction] || jurisdiction || marketId;
                return `${flag} ${name}`;
            };

            // Parse market ID into separate components
            const parseMarketId = (marketId) => {
                if (!marketId || marketId === 'unknown') {
                    return { flag: '🌐', country: 'Unknown', jurisdiction: '-' };
                }

                const marketFlags = {
                    'US': '🇺🇸', 'CA': '🇨🇦', 'UK': '🇬🇧', 'AR': '🇦🇷', 'CH': '🇨🇭', 'TR': '🇹🇷', 'MX': '🇲🇽', 'BR': '🇧🇷'
                };
                const countryNames = {
                    'US': 'USA', 'CA': 'Canada', 'UK': 'UK', 'AR': 'Argentina', 'CH': 'Switzerland', 'TR': 'Turkey', 'MX': 'Mexico', 'BR': 'Brazil'
                };
                const jurisdictionNames = {
                    'NJ': 'New Jersey', 'CO': 'Colorado', 'ON': 'Ontario', 'ALL': 'All',
                    'ROC': 'Rest of Canada', 'GB': 'Great Britain', 'CABA': 'CABA', 'LOTBA': 'LOTBA'
                };

                const [countryCode, jurisdictionCode] = marketId.split('-');
                return {
                    flag: marketFlags[countryCode] || '🌐',
                    country: countryNames[countryCode] || countryCode || 'Unknown',
                    jurisdiction: jurisdictionNames[jurisdictionCode] || jurisdictionCode || '-'
                };
            };

            // Module status icons
            const checkIcon = '<i class="fa-solid fa-check text-green-500"></i>';
            const xIcon = '<i class="fa-solid fa-xmark text-red-400"></i>';

            // Generate table rows
            tableBody.innerHTML = competitors.map(c => {
                const isSelected = selectedCompetitorIds.has(c.id);
                const completeness = c.completeness.percentage;
                const colorClass = completeness === 100 ? 'bg-green-100 text-green-700' :
                    completeness >= 60 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700';
                const crmtRank = c.rankings?.crmt || '-';
                const licenseIcon = c.license.status === 'licensed'
                    ? '<i class="fa-solid fa-circle-check text-green-500"></i>'
                    : '<i class="fa-solid fa-triangle-exclamation text-amber-500"></i>';

                // Parse market info
                const marketParts = parseMarketId(c.market);

                return `
                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors ${isSelected ? 'bg-purple-50' : ''}">
                        <td class="py-3 px-2 text-center">
                            <input type="checkbox" data-id="${c.id}" onchange="toggleCompetitorSelection('${c.id}')"
                                ${isSelected ? 'checked' : ''}
                                class="w-4 h-4 rounded border-slate-300 text-purple-600 focus:ring-purple-500">
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-slate-800">${c.shortName}</span>
                                ${getTierBadge(c.tier)}
                            </div>
                        </td>
                        <td class="py-3 px-3">
                            <span class="text-sm">${marketParts.flag} ${marketParts.country}</span>
                        </td>
                        <td class="py-3 px-3">
                            <span class="text-sm text-slate-600">${marketParts.jurisdiction}</span>
                        </td>
                        <td class="py-3 px-2 text-center" title="${c.license.status}">
                            ${licenseIcon}
                        </td>
                        <td class="py-3 px-3 text-center"><span class="text-sm font-medium">${c.crm.emailCount || '-'}</span></td>
                        <td class="py-3 px-3">
                            <div class="w-20 h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full ${completeness === 100 ? 'bg-green-500' : completeness >= 60 ? 'bg-amber-500' : 'bg-red-500'}" style="width: ${completeness}%"></div>
                            </div>
                        </td>
                        <td class="py-3 px-3 text-center">
                            <span class="inline-flex items-center justify-center w-10 h-6 ${colorClass} rounded-full text-xs font-bold">${completeness}%</span>
                        </td>
                        <td class="py-3 px-3 text-center">
                            <span class="inline-flex items-center justify-center w-6 h-6 bg-purple-100 text-purple-700 rounded text-xs font-bold">#${crmtRank}</span>
                        </td>
                        <td class="py-3 px-2 text-center">${c.completeness.modules.d2 ? checkIcon : xIcon}</td>
                        <td class="py-3 px-2 text-center">${c.completeness.modules.d3 ? checkIcon : xIcon}</td>
                        <td class="py-3 px-2 text-center">${c.completeness.modules.d4 ? checkIcon : xIcon}</td>
                        <td class="py-3 px-2 text-center">${c.completeness.modules.d5 ? checkIcon : xIcon}</td>
                        <td class="py-3 px-2 text-center">${c.completeness.modules.d7 ? checkIcon : xIcon}</td>
                    </tr>
                `;
            }).join('');

            // Update summary stats
            updateSummaryStats(competitors);
            updateSelectionBar();
            renderSavedGroups();
        }

        function updateSummaryStats(competitors) {
            // Ensure numeric addition by parsing values
            const totalEmails = competitors.reduce((sum, c) => sum + (parseInt(c.crm.emailCount, 10) || 0), 0);
            const avgCompleteness = competitors.length > 0
                ? Math.round(competitors.reduce((sum, c) => sum + (parseInt(c.completeness.percentage, 10) || 0), 0) / competitors.length)
                : 0;

            // Update badge
            const badge = document.getElementById('competitor-count-badge');
            if (badge) {
                badge.innerHTML = `<i class="fa-solid fa-check text-xs mr-1"></i>${competitors.length} Competitors`;
            }

            const statCards = document.querySelectorAll('.grid.grid-cols-4 .bg-white');
            if (statCards.length >= 4) {
                statCards[0].querySelector('.text-2xl').textContent = competitors.length.toLocaleString();
                statCards[1].querySelector('.text-2xl').textContent = totalEmails.toLocaleString();
                statCards[2].querySelector('.text-2xl').textContent = avgCompleteness + '%';
            }
        }

        // ============ SELECTION MANAGEMENT ============
        function toggleCompetitorSelection(competitorId) {
            if (selectedCompetitorIds.has(competitorId)) {
                selectedCompetitorIds.delete(competitorId);
            } else {
                selectedCompetitorIds.add(competitorId);
            }
            updateSelectionBar();
            // Update row highlight
            document.querySelectorAll(`[data-id="${competitorId}"]`).forEach(cb => {
                cb.closest('tr').classList.toggle('bg-purple-50', cb.checked);
            });
        }

        function toggleSelectAll(checkbox) {
            const checkboxes = document.querySelectorAll('#competitor-table-body input[type="checkbox"]');
            checkboxes.forEach(cb => {
                const id = cb.dataset.id;
                if (checkbox.checked) {
                    selectedCompetitorIds.add(id);
                } else {
                    selectedCompetitorIds.delete(id);
                }
                cb.checked = checkbox.checked;
                cb.closest('tr').classList.toggle('bg-purple-50', checkbox.checked);
            });
            updateSelectionBar();
        }

        function clearSelection() {
            selectedCompetitorIds.clear();
            document.querySelectorAll('#competitor-table-body input[type="checkbox"]').forEach(cb => {
                cb.checked = false;
                cb.closest('tr').classList.remove('bg-purple-50');
            });
            document.getElementById('select-all').checked = false;
            updateSelectionBar();
        }

        function updateSelectionBar() {
            const bar = document.getElementById('selection-bar');
            const countEl = document.getElementById('selection-count');
            const hintEl = document.getElementById('selection-hint');
            const btn = document.getElementById('create-group-btn');

            const count = selectedCompetitorIds.size;

            if (count === 0) {
                bar.classList.add('hidden');
                bar.classList.remove('flex');
                return;
            }

            bar.classList.remove('hidden');
            bar.classList.add('flex');
            countEl.textContent = count;

            // Determine hint and button state
            const isValidSize = VALID_SIZES.includes(count);
            btn.disabled = !isValidSize;

            if (isValidSize) {
                hintEl.textContent = '✓ Valid group size';
                hintEl.classList.remove('text-slate-400');
                hintEl.classList.add('text-green-400');
            } else {
                const nextValid = VALID_SIZES.find(s => s > count) || 10;
                const diff = nextValid - count;
                hintEl.textContent = `Select ${diff} more for ${nextValid}`;
                hintEl.classList.add('text-slate-400');
                hintEl.classList.remove('text-green-400');
            }
        }

        // ============ GROUP MANAGEMENT ============
        function toggleGroupsPanel() {
            const panel = document.getElementById('groups-panel');
            const chevron = document.getElementById('groups-chevron');
            panel.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        }

        async function renderSavedGroups() {
            const list = document.getElementById('groups-list');
            const empty = document.getElementById('groups-empty');
            const countEl = document.getElementById('groups-count');

            try {
                // Fetch groups from database API
                const res = await fetch('/.netlify/functions/groups');
                if (!res.ok) throw new Error('Failed to fetch groups');
                const groups = await res.json();

                // Filter by current market (optional - show all if "ALL" selected)
                const filters = getFilters();
                const marketId = filters.marketId;
                const filteredGroups = marketId && marketId !== 'ALL'
                    ? groups.filter(g => g.market_id === marketId)
                    : groups;

                countEl.textContent = filteredGroups.length;

                if (filteredGroups.length === 0) {
                    list.innerHTML = '';
                    empty.classList.remove('hidden');
                    return;
                }

                empty.classList.add('hidden');

                // Get active group from navBar or localStorage
                const activeId = CRMT.navBar?.getSelectedGroup()?.id || localStorage.getItem('navBar_groupId');

                list.innerHTML = filteredGroups.map(g => {
                    const flag = g.flag || '📁';
                    const marketParts = (g.market_id || '').split('-');
                    const country = marketParts[0] || '';
                    const jurisdiction = marketParts[1] || 'ALL';

                    return `
                    <tr class="border-b border-slate-100 hover:bg-slate-50 ${g.id === activeId ? 'bg-purple-50' : ''}">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2 cursor-pointer" onclick="setActiveGroup('${g.id}')">
                                <i class="fa-solid fa-users text-purple-500"></i>
                                <span class="font-medium text-slate-800">${g.name}</span>
                            </div>
                        </td>
                        <td class="py-3 px-3 text-slate-600">${flag} ${country}</td>
                        <td class="py-3 px-3 text-slate-600">${jurisdiction}</td>
                        <td class="py-3 px-3 text-center">
                            <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-xs font-medium">${g.competitor_count || g.competitor_ids?.length || 0}</span>
                        </td>
                        <td class="py-3 px-3 text-center">
                            ${g.id === activeId ? '<span class="text-xs bg-purple-100 text-purple-700 px-2 py-0.5 rounded">Active</span>' : '<span class="text-xs text-slate-400">-</span>'}
                        </td>
                        <td class="py-3 px-3 text-center">
                            <button onclick="editGroup('${g.id}')" class="text-slate-400 hover:text-blue-600 p-1" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button onclick="deleteGroupFromAPI('${g.id}')" class="text-slate-400 hover:text-red-600 p-1" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                }).join('');
            } catch (e) {
                console.error('[D.6] Failed to load groups:', e);
                countEl.textContent = 0;
                list.innerHTML = '';
                empty.classList.remove('hidden');
            }
        }

        async function setActiveGroup(groupId) {
            // Set via navBar if available
            if (CRMT.navBar?.setActiveGroup) {
                await CRMT.navBar.setActiveGroup(groupId);
            } else {
                // Fallback to API directly
                await fetch(`/.netlify/functions/groups?action=set-active&id=${groupId}`, {
                    method: 'POST'
                });
            }
            renderSavedGroups();
        }

        function editGroup(groupId) {
            // For now, editing needs to load group from API
            // TODO: Implement proper group editing with API
            alert('Edit functionality coming soon. Please delete and recreate the group.');
        }

        async function deleteGroupFromAPI(groupId) {
            if (!confirm('Delete this group?')) return;

            try {
                await fetch(`/.netlify/functions/groups?id=${groupId}`, {
                    method: 'DELETE'
                });
                // Also delete from localStorage for backward compatibility
                if (CRMT.groups?.delete) {
                    CRMT.groups.delete(groupId);
                }
                // Refresh navBar
                if (CRMT.navBar?.refreshGroups) {
                    await CRMT.navBar.refreshGroups();
                }
                renderSavedGroups();
            } catch (e) {
                console.error('[D.6] Failed to delete group:', e);
                alert('Failed to delete group');
            }
        }

        // Keep old deleteGroup for backward compatibility
        function deleteGroup(groupId) {
            deleteGroupFromAPI(groupId);
        }

        // ============ CREATE GROUP MODAL ============
        function showCreateGroupModal() {
            const modal = document.getElementById('create-group-modal');
            modal.classList.remove('hidden');

            const filters = getFilters();
            const marketKey = filters.marketId; // Use full market ID like 'TR-ALL'

            // Flag and name lookup for countries not in CRMT.markets
            const countryFlags = { 'CA': '🇨🇦', 'US': '🇺🇸', 'UK': '🇬🇧', 'CH': '🇨🇭', 'TR': '🇹🇷', 'AR': '🇦🇷' };
            const countryNames = { 'CA': 'Canada', 'US': 'USA', 'UK': 'United Kingdom', 'CH': 'Switzerland', 'TR': 'Turkey', 'AR': 'Argentina' };

            // Populate market info - use filters with flag lookup if CRMT.markets doesn't have it
            const marketInfo = document.getElementById('modal-market-info');
            if (marketInfo) {
                const marketData = CRMT.markets?.[marketKey];
                if (marketData) {
                    marketInfo.textContent = `${marketData.flag} ${marketData.market} · ${marketData.jurisdiction}`;
                } else {
                    // Fallback: build display with flag lookup
                    const flag = countryFlags[filters.market] || '🌐';
                    const countryName = countryNames[filters.market] || filters.market;
                    const jurName = filters.jurisdiction === 'ALL' ? 'All' : filters.jurisdiction;
                    marketInfo.textContent = `${flag} ${countryName} · ${jurName}`;
                }
            }

            document.getElementById('group-id-preview').textContent = `${filters.market}-${filters.jurisdiction}-...`;
            document.getElementById('group-name-input').value = '';
            document.getElementById('group-name-input').focus();

            // Show selected competitors - get names from table DOM instead of static CRMT data
            const selectedNames = [];
            document.querySelectorAll('#competitor-table-body input[type="checkbox"]:checked').forEach(cb => {
                const row = cb.closest('tr');
                if (row) {
                    const nameCell = row.querySelector('td:nth-child(2) .font-medium');
                    if (nameCell) {
                        selectedNames.push(nameCell.textContent.trim());
                    }
                }
            });

            document.getElementById('selected-competitors-list').innerHTML = selectedNames.length > 0
                ? selectedNames.map(name => `<div class="flex items-center gap-2 py-1"><span class="font-medium">${name}</span></div>`).join('')
                : '<div class="text-slate-400 text-sm">No competitors selected</div>';

            // Update preview on input
            document.getElementById('group-name-input').oninput = (e) => {
                const sanitized = e.target.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '').substring(0, 30);
                document.getElementById('group-id-preview').textContent = `${filters.market}-${filters.jurisdiction}-${sanitized || '...'}`;
            };
        }

        function closeCreateGroupModal() {
            document.getElementById('create-group-modal').classList.add('hidden');
            editingGroupId = null;
        }

        async function createGroup() {
            const label = document.getElementById('group-name-input').value.trim();
            if (!label) {
                alert('Please enter a group name');
                return;
            }

            const filters = getFilters();
            const competitorIds = Array.from(selectedCompetitorIds);
            const marketId = filters.marketId || `${filters.market}-${filters.jurisdiction}`;

            // Save to localStorage (for backward compatibility)
            if (editingGroupId) {
                CRMT.groups.update(editingGroupId, competitorIds);
            } else {
                const group = CRMT.groups.create(label, competitorIds, filters.market, filters.jurisdiction);
                if (!group) {
                    alert('Failed to create group. Name may already exist.');
                    return;
                }
            }

            // Also save to database API so navBar can see it
            try {
                const groupId = editingGroupId || `${marketId}-${label.toLowerCase().replace(/[^a-z0-9]+/g, '-')}`;
                await fetch('/.netlify/functions/groups', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        id: groupId,
                        name: label,
                        market_id: marketId,
                        competitor_ids: competitorIds
                    })
                });
                // Refresh navBar groups dropdown
                if (CRMT.navBar?.refreshGroups) {
                    await CRMT.navBar.refreshGroups();
                }
            } catch (e) {
                console.warn('[D.6] Failed to save group to API:', e);
            }

            closeCreateGroupModal();
            clearSelection();
            renderSavedGroups();
        }

        // ============ INITIALIZATION ============
        // Jurisdiction options by market
        const JURISDICTION_OPTIONS = {
            'ALL': [
                { value: 'ALL', label: 'All Jurisdictions' }
            ],
            'CA': [
                { value: 'ON', label: 'Ontario (AGCO)' },
                { value: 'ROC', label: 'Rest of Canada (CASL)' }
            ],
            'UK': [
                { value: 'GB', label: 'Great Britain (UKGC)' }
            ],
            'US': [
                { value: 'ALL', label: 'All US States' },
                { value: 'OH', label: 'Ohio (OCCC)' },
                { value: 'NJ', label: 'New Jersey (DGE)' },
                { value: 'CO', label: 'Colorado (DOR)' },
                { value: 'MI', label: 'Michigan (MGCB)' },
                { value: 'MA', label: 'Massachusetts (MGC)' }
            ],
            'AR': [
                { value: 'LOTBA', label: 'Buenos Aires (LOTBA)' }
            ]
        };

        function updateJurisdictionOptions(saveToStorage = true) {
            const marketSelect = document.getElementById('filter-market');
            const jurisdictionSelect = document.getElementById('filter-jurisdiction');
            if (!marketSelect || !jurisdictionSelect) return;

            const market = marketSelect.value;
            const options = JURISDICTION_OPTIONS[market] || [];

            // Get saved jurisdiction or default to first
            const savedJurisdiction = localStorage.getItem('crmt_market_jurisdiction');
            const validJurisdiction = options.find(o => o.value === savedJurisdiction);
            const selectedValue = validJurisdiction ? savedJurisdiction : (options[0]?.value || 'ALL');

            jurisdictionSelect.innerHTML = options.map(opt =>
                `<option value="${opt.value}" ${opt.value === selectedValue ? 'selected' : ''}>${opt.label}</option>`
            ).join('');

            // Only save to localStorage when explicitly changing (not on init)
            if (saveToStorage) {
                localStorage.setItem('crmt_market_country', market);
                localStorage.setItem('crmt_market_jurisdiction', selectedValue);
            }
        }
        // Store all markets for jurisdiction lookup
        let allMarkets = [];

        // Load markets from API and populate country dropdown
        async function loadMarketsDropdown() {
            const select = document.getElementById('filter-market');
            if (!select) return;

            try {
                const res = await fetch('/.netlify/functions/markets');
                if (!res.ok) throw new Error('Failed to fetch markets');
                const data = await res.json();

                // API returns { countries: [...], total: N }
                allMarkets = data.countries || [];

                select.innerHTML = allMarkets.map(c =>
                    `<option value="${c.code}">${c.flag} ${c.name}</option>`
                ).join('');

                // Restore saved selection
                const savedCountry = localStorage.getItem('crmt_market_country');
                if (savedCountry && Array.from(select.options).some(o => o.value === savedCountry)) {
                    select.value = savedCountry;
                }

                // Populate jurisdictions for selected country
                updateJurisdictionDropdown();
            } catch (e) {
                console.warn('[D.6] Failed to load markets:', e);
                select.innerHTML = '<option value="TR">🇹🇷 Turkey</option>';
            }
        }

        // Update jurisdiction dropdown based on selected country
        function updateJurisdictionDropdown() {
            const countrySelect = document.getElementById('filter-market');
            const jurSelect = document.getElementById('filter-jurisdiction');
            if (!countrySelect || !jurSelect) return;

            const countryCode = countrySelect.value;
            // Find the country in allMarkets (which is now an array of country objects)
            const country = allMarkets.find(c => c.code === countryCode);
            const jurisdictions = country?.jurisdictions || [];

            if (jurisdictions.length <= 1) {
                // Only one jurisdiction (or ALL), hide or simplify
                jurSelect.innerHTML = '<option value="ALL">All</option>';
            } else {
                jurSelect.innerHTML = '<option value="ALL">All Jurisdictions</option>' +
                    jurisdictions.map(j =>
                        `<option value="${j.code}">${j.name}</option>`
                    ).join('');
            }

            // Save selection
            localStorage.setItem('crmt_market_country', countryCode);
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', async () => {
            // Load markets from API first
            await loadMarketsDropdown();

            // Wait for CRMT to be available
            function init() {
                if (typeof CRMT !== 'undefined') {
                    // Initialize jurisdiction dropdown without overwriting localStorage
                    updateJurisdictionOptions(false);
                    renderCompetitorTable();
                } else {
                    setTimeout(init, 100);
                }
            }
            init();
        });

        // Re-render when groups change
        window.addEventListener('competitorGroupsChanged', renderSavedGroups);
        // ============ END SELECTION/GROUPS ============

        let selectedMarket = { country: null, jurisdiction: null };
        let parsedData = { filename: '', rows: 0, competitors: [], dateRange: { start: null, end: null } };

        // Simulated existing data (would come from backend/storage)
        const existingTrackingIds = new Set(['hit_001', 'hit_002', 'hit_003']); // Example
        const existingCompetitors = ['CasinoMax', 'VegasCasinoOnline', 'Stake', 'JackpotCityCasino', 'SlotsOfVegas'];

        function openUploadModal() {
            document.getElementById('upload-modal').classList.remove('hidden');
            showStep(1);
        }

        function closeUploadModal() {
            document.getElementById('upload-modal').classList.add('hidden');
            showStep(1);
            selectedMarket = { country: null, jurisdiction: null };
            parsedData = { filename: '', rows: 0, competitors: [], dateRange: { start: null, end: null } };
        }

        function showStep(step) {
            document.getElementById('upload-step-1').classList.toggle('hidden', step !== 1);
            document.getElementById('upload-step-2').classList.toggle('hidden', step !== 2);
            document.getElementById('upload-step-3').classList.toggle('hidden', step !== 3);
        }

        function selectMarket(country, jurisdiction) {
            selectedMarket = { country, jurisdiction };
            const labels = {
                'CA-ON': '🇨🇦 Canada - Ontario (AGCO)',
                'CA-ROC': '🇨🇦 Canada - Rest of Canada (CASL)',
                'UK-GB': '🇬🇧 United Kingdom (UKGC)',
                'CH-ALL': '🇨🇭 Switzerland (ESBK/CFMJ)',
                'TR-ALL': '🇹🇷 Turkey (Grey Market)',
                'AR-CABA': '🇦🇷 Argentina - CABA (LOTBA)'
            };
            document.getElementById('selected-market-badge').textContent = labels[`${country}-${jurisdiction}`] || `${country}-${jurisdiction}`;
            showStep(2);
        }

        function goBackToStep1() { showStep(1); }
        function goBackToStep2() { showStep(2); }

        // File handling
        const uploadZone = document.getElementById('upload-zone');
        uploadZone?.addEventListener('click', () => document.getElementById('file-input').click());
        uploadZone?.addEventListener('dragover', (e) => { e.preventDefault(); uploadZone.classList.add('dragover'); });
        uploadZone?.addEventListener('dragleave', () => uploadZone.classList.remove('dragover'));
        uploadZone?.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadZone.classList.remove('dragover');
            const file = e.dataTransfer.files[0];
            if (file) parseFile(file);
        });

        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) parseFile(file);
        }

        function parseFile(file) {
            parsedData.filename = file.name;

            const reader = new FileReader();
            reader.onload = function (e) {
                try {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, { type: 'array' });
                    const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                    const jsonData = XLSX.utils.sheet_to_json(firstSheet);

                    // Store raw data for import
                    parsedData.rawRows = jsonData;

                    // Group by competitor and check duplicates
                    const competitorMap = {};
                    let minDate = null, maxDate = null;

                    jsonData.forEach(row => {
                        const competitor = row.competitor_name || row.competitor_id || 'Unknown';
                        const trackingId = row.tracking_hit_id;
                        const rowDate = row.created_at ? new Date(row.created_at) : (row.date ? new Date(row.date) : null);

                        if (!competitorMap[competitor]) {
                            competitorMap[competitor] = {
                                newRows: 0,
                                duplicates: 0,
                                isNew: true, // Will be determined by API
                                competitorId: row.competitor_id || null
                            };
                        }

                        // Always count as new - API handles deduplication
                        competitorMap[competitor].newRows++;

                        if (rowDate) {
                            if (!minDate || rowDate < minDate) minDate = rowDate;
                            if (!maxDate || rowDate > maxDate) maxDate = rowDate;
                        }
                    });

                    parsedData.competitors = Object.entries(competitorMap).map(([name, data]) => ({ name, ...data }));
                    parsedData.dateRange = { start: minDate, end: maxDate };
                    parsedData.rows = jsonData.length;

                    showConfirmation();
                } catch (err) {
                    alert('Error parsing file: ' + err.message);
                }
            };
            reader.readAsArrayBuffer(file);
        }

        function showConfirmation() {
            document.getElementById('confirm-filename').textContent = parsedData.filename;
            document.getElementById('confirm-market').textContent = document.getElementById('selected-market-badge').textContent;

            const formatDate = (d) => d ? d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '-';
            document.getElementById('confirm-daterange').textContent =
                `${formatDate(parsedData.dateRange.start)} → ${formatDate(parsedData.dateRange.end)}`;

            const tableBody = document.getElementById('confirm-competitors-table');
            let totalNew = 0, totalDupes = 0;

            tableBody.innerHTML = parsedData.competitors.map(c => {
                totalNew += c.newRows;
                totalDupes += c.duplicates;
                return `
                    <tr class="border-t border-slate-200">
                        <td class="py-2 px-3 font-medium">${c.name}</td>
                        <td class="py-2 px-3 text-center text-green-600">${c.newRows}</td>
                        <td class="py-2 px-3 text-center text-slate-400">${c.duplicates}</td>
                        <td class="py-2 px-3 text-center">
                            ${c.isNew ? '<span class="text-xs bg-purple-100 text-purple-700 px-2 py-0.5 rounded font-medium">NEW ✨</span>' : '<span class="text-xs text-slate-500">Existing</span>'}
                        </td>
                    </tr>
                `;
            }).join('');

            document.getElementById('confirm-new-rows').textContent = totalNew.toLocaleString();
            document.getElementById('confirm-duplicates').textContent = totalDupes.toLocaleString();

            showStep(3);
        }

        async function confirmUpload() {
            const confirmBtn = document.querySelector('#upload-step-3 button:last-child');
            const originalText = confirmBtn.innerHTML;
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Importing...';

            try {
                const marketId = `${selectedMarket.country}-${selectedMarket.jurisdiction}`;

                const result = await CRMT.dal.importData({
                    rows: parsedData.rawRows,
                    market_id: marketId,
                    source_file: parsedData.filename
                });

                if (result.success) {
                    const stats = result.stats;
                    alert(`Import Complete!\n\nCompetitors created: ${stats.competitors_created}\nHits inserted: ${stats.hits_inserted}\nHits updated: ${stats.hits_updated}\nHits skipped: ${stats.hits_skipped}`);
                    closeUploadModal();
                    location.reload();
                } else {
                    throw new Error(result.error || 'Import failed');
                }
            } catch (err) {
                alert('Import failed: ' + err.message);
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = originalText;
            }
        }

        // Listen for market changes from navBar
        window.addEventListener('navBarChange', () => {
            console.log('[D.6] Market changed, reloading table');
            renderCompetitorTable();
        });

        // Initial load after navBar initializes
        setTimeout(() => {
            if (CRMT.navBar?._markets) {
                renderCompetitorTable();
            }
        }, 500);
    </script>
@endpush
