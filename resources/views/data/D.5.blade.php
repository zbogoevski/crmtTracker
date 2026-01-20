@extends('layouts.dashboard')


@section('title', 'D.5 Offer Directory | CRMTracker')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
            color: #334155;
        }
</style>
@endpush

@section('content')
<main class="flex-1 ml-20 p-8 max-w-[1800px] mx-auto overflow-x-hidden">
<header class="flex justify-between items-center mb-6">
<div class="flex items-center gap-3">
<span
class="text-xs font-medium bg-purple-50 text-purple-700 px-2 py-1 rounded border border-purple-200">Data
Module D.5</span>
<span
class="text-xs font-medium bg-green-50 text-green-700 px-2 py-1 rounded border border-green-200">
<i class="fa-solid fa-check text-xs mr-1"></i><span id="total-count">0</span> Offers
</span>
<span
class="text-xs font-medium bg-slate-100 text-slate-600 px-2 py-1 rounded border border-slate-200">
<i class="fa-solid fa-clock text-xs mr-1"></i>Updated Dec 16, 2024
</span>
<div id="date-range-container" class="flex items-center"></div>
<button
class="px-6 py-2.5 rounded-lg font-medium shadow-sm flex items-center gap-2 transition-colors bg-purple-600 hover:bg-purple-700 text-white">
<i class="fa-solid fa-download"></i>
Export CSV
</button>
</header>
<!-- Valuation Settings Panel -->
<div class="bg-white rounded-xl border border-slate-200 mb-4 overflow-hidden">
<div class="flex items-center justify-between px-4 py-3 bg-slate-50 cursor-pointer border-b border-slate-200"
onclick="toggleSettings()">
<div class="flex items-center gap-2">
<i class="fa-solid fa-sliders text-purple-600"></i>
<span class="font-semibold text-sm text-slate-700">Valuation Settings</span>
</div>
<i id="settings-chevron"
class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform"></i>
</div>
<div id="settings-panel" class="px-4 py-4 flex flex-wrap gap-6 items-center">
<div class="flex items-center gap-2">
<label class="text-sm text-slate-600">Spin Value:</label>
<div class="relative">
<span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">$</span>
<input type="number" id="spin-value" step="0.01" min="0.01" max="10" value="0.10"
onchange="updateValuation()"
class="w-20 pl-6 pr-2 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500">
</div>
</div>
<div class="flex items-center gap-2">
<label class="text-sm text-slate-600">Default Max Deposit:</label>
<div class="relative">
<span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">$</span>
<input type="number" id="max-deposit" step="100" min="100" max="10000" value="500"
onchange="updateValuation()"
class="w-24 pl-6 pr-2 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500">
</div>
</div>
<div class="flex items-center gap-2">
<label class="text-sm text-slate-600">Country:</label>
<select id="country-select"
class="px-3 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500">
<option value="">Loading...</option>
</select>
</div>
<div class="flex items-center gap-2">
<label class="text-sm text-slate-600">Jurisdiction:</label>
<select id="jurisdiction-select"
class="px-3 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500">
<option value="">Loading...</option>
</select>
<div id="date-range-container" class="flex items-center"></div>
<button onclick="saveSettings()"
class="px-4 py-1.5 bg-purple-100 text-purple-700 rounded-lg text-sm font-medium hover:bg-purple-200">
<i class="fa-solid fa-save mr-1"></i> Save
</button>
</div>
</div>
<!-- Search & Filters Row -->
<div class="bg-white rounded-xl border border-slate-200 p-4 mb-4 flex gap-4 items-center">
<div class="flex-1 relative">
<i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
<input type="text" id="search-input" placeholder="Search competitor, bonus code, or offer..."
class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
onkeyup="applyFilters()">
</div>
<select id="competitor-filter" onchange="applyFilters()"
class="px-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500">
<option value="">All Competitors</option>
</select>
<select id="type-filter" onchange="applyFilters()"
class="px-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500">
<option value="">All Offer Types</option>
<option value="Match">Match %</option>
<option value="Spins">Free Spins</option>
<option value="Fixed">Fixed $</option>
<option value="Cashback">Cashback</option>
<option value="Mixed">Mixed</option>
</select>
<select id="lifecycle-filter" onchange="applyFilters()"
class="px-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500">
<option value="">All Lifecycle</option>
<option value="WEL">Welcome</option>
<option value="REA">Reactivation</option>
<option value="RET">Retention</option>
<option value="VIP">VIP</option>
</select>
</div>
<!-- Offers Table -->
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm mb-4">
<table class="w-full text-sm">
<thead class="bg-slate-50 border-b border-slate-200">
<tr>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase w-36">
Competitor</th>
<th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase w-20">
Lifecycle</th>
<th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase w-24">Offer
Type</th>
<th class="text-right py-3 px-4 text-xs font-semibold text-slate-600 uppercase w-24">
Calculated $</th>
<th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase w-20">
Wagering</th>
<th class="text-right py-3 px-4 text-xs font-semibold text-slate-600 uppercase w-28">Max
Cashout</th>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Offer Value
</th>
</tr>
</thead>
<tbody id="offers-body"></tbody>
</table>
</div>
<!-- Pagination -->
<div class="flex items-center justify-between mb-6">
<div class="text-sm text-slate-500">
Showing <span id="showing-start">1</span>-<span id="showing-end">25</span> of
<span id="filtered-count">0</span> offers
</div>
<div class="flex gap-2">
<button onclick="changePage(-1)" id="prev-btn"
class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-200 disabled:opacity-50">
<i class="fa-solid fa-chevron-left mr-1"></i> Previous
</button>
<span class="px-4 py-2 text-sm text-slate-600">Page <span id="current-page">1</span> of <span
id="total-pages">1</span></span>
<button onclick="changePage(1)" id="next-btn"
class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-200 disabled:opacity-50">
Next <i class="fa-solid fa-chevron-right ml-1"></i>
</button>
</div>
</div>
<!-- Market Comparison Summary -->
<div class="bg-white rounded-xl border border-slate-200 p-6">
<h3 class="font-bold text-lg text-slate-800 mb-4 flex items-center gap-2">
<i class="fa-solid fa-chart-bar text-purple-600"></i>
Market Comparison
</h3>
<div id="market-comparison" class="grid grid-cols-6 gap-4">
<!-- Populated by JS -->
</div>
<div class="mt-4 pt-4 border-t border-slate-200">
<div class="flex items-center gap-4">
<span class="text-sm font-medium text-slate-600">Market Average:</span>
<span id="market-avg" class="text-2xl font-bold text-purple-600">$0</span>
<div class="flex-1 bg-slate-100 rounded-full h-3 overflow-hidden">
<div id="market-avg-bar"
class="bg-gradient-to-r from-purple-500 to-blue-500 h-full rounded-full"
style="width: 0%"></div>
</div>
</div>
</div>
</div>
</main>
</div>
<script>
// Offer data - loaded from JSON
let offerData = [];
let currentPage = 1;
const perPage = 25;
let filteredData = [];
let processedData = [];
// Settings
let spinValue = parseFloat(localStorage.getItem('d5_spin_value') || '0.10');
let maxDeposit = parseInt(localStorage.getItem('d5_max_deposit') || '500');
// Load data from JSON
async function loadOfferData() {
// Try database API first (Stage 3 migration)
try {
if (window.CRMT?.dal?.getOffers) {
console.log('[D.5] Loading offers from database API...');
const result = await CRMT.dal.getOffers({ limit: 1000 });
if (result.data && result.data.length > 0) {
offerData = result.data.map(o => ({
competitor: o.competitor_name || o.competitor_id,
lifecycle: o.lifecycle,
offerRaw: o.offer_raw,
offerType: o.offer_type,
calculatedValue: o.calculated_value,
wagering: o.wagering,
maxCashout: o.max_cashout,
matchPct: o.match_pct,
spins: o.spins
}));
console.log(`[D.5] ✅ Loaded ${offerData.length} offers from DATABASE`);
document.getElementById('total-count').textContent = offerData.length;
processOffers();
populateFilters();
applyFilters();
return;
}
}
} catch (e) {
console.warn('[D.5] Database API unavailable:', e.message);
}
// No fallback - database only
console.log('[D.5] No offer data in database - import via D.10');
offerData = [];
document.getElementById('total-count').textContent = '0'
processOffers();
populateFilters();
applyFilters();
}
// Initialize
document.addEventListener('DOMContentLoaded', () => {
document.getElementById('spin-value').value = spinValue;
document.getElementById('max-deposit').value = maxDeposit;
loadOfferData();
// Listen for group changes to update market comparison
if (window.setupGroupChangeListener) {
window.setupGroupChangeListener(() => {
console.log('[D.5] Group changed, re-rendering market comparison');
renderMarketComparison();
});
}
window.addEventListener('competitorGroupActivated', () => {
console.log('[D.5] competitorGroupActivated event received');
renderMarketComparison();
});
// Listen for market changes from navBar
window.addEventListener('navBarChange', () => {
console.log('[D.5] Market changed, reloading offer data');
loadOfferData();
});
});
function toggleSettings() {
const panel = document.getElementById('settings-panel');
const chevron = document.getElementById('settings-chevron');
panel.classList.toggle('hidden');
chevron.classList.toggle('rotate-180');
}
function saveSettings() {
spinValue = parseFloat(document.getElementById('spin-value').value) || 0.10;
maxDeposit = parseInt(document.getElementById('max-deposit').value) || 500;
localStorage.setItem('d5_spin_value', spinValue);
localStorage.setItem('d5_max_deposit', maxDeposit);
processOffers();
applyFilters();
}
function updateValuation() {
saveSettings();
}
// Parse offer and calculate value
function parseOffer(offerRaw) {
if (!offerRaw) return { type: 'Unknown', value: 0 };
const str = offerRaw.toString();
let type = 'Unknown';
let value = 0;
// Mixed: Contains both % and spins
if (/\d+%/.test(str) && /\d+\s*(Free\s*)?Spins/i.test(str)) {
type = 'Mixed';
const matchPct = str.match(/(\d+)%/);
const matchSpins = str.match(/(\d+)\s*(Free\s*)?Spins/i);
if (matchPct) value += (parseInt(matchPct[1]) / 100) * maxDeposit;
if (matchSpins) value += parseInt(matchSpins[1]) * spinValue;
}
// Match %: standalone percentage
else if (/^(\d+)%/.test(str) || /(\d+)%\s*(up to|match)?/i.test(str)) {
type = 'Match';
const match = str.match(/(\d+)%/);
if (match) {
const pct = parseInt(match[1]);
// Check for "up to $X" limit
const upTo = str.match(/up to \$(\d+)/i);
if (upTo) {
value = Math.min((pct / 100) * maxDeposit, parseInt(upTo[1]));
} else {
value = (pct / 100) * maxDeposit;
}
}
}
// Free Spins
else if (/(\d+)\s*(Free\s*)?Spins/i.test(str)) {
type = 'Spins';
const match = str.match(/(\d+)\s*(Free\s*)?Spins/i);
if (match) value = parseInt(match[1]) * spinValue;
}
// Cashback
else if (/(\d+)%\s*Cashback/i.test(str) || /(\d+)%\s*Rakeback/i.test(str)) {
type = 'Cashback';
const match = str.match(/(\d+)%/);
if (match) value = (parseInt(match[1]) / 100) * maxDeposit * 0.5; // Assume 50% avg loss
}
// Fixed $ amount
else if (/\$(\d+)/i.test(str)) {
type = 'Fixed';
const match = str.match(/\$(\d+)/i);
if (match) value = parseInt(match[1]);
}
// Reload (treated as Match at 50%)
else if (/reload/i.test(str)) {
type = 'Match';
const match = str.match(/(\d+)%/);
if (match) value = (parseInt(match[1]) / 100) * maxDeposit;
else value = 0.5 * maxDeposit; // Default 50% reload
}
return { type, value: Math.round(value) };
}
function processOffers() {
processedData = offerData.map(offer => {
const parsed = parseOffer(offer.offerRaw);
return {
...offer,
offerType: parsed.type,
calculatedValue: parsed.value
};
});
document.getElementById('total-count').textContent = processedData.length;
}
function populateFilters() {
const competitors = [...new Set(offerData.map(o => o.competitor))].sort();
const select = document.getElementById('competitor-filter');
select.innerHTML = '<option value="">All Competitors</option>' +
competitors.map(c => `<option value="${c}">${c}</option>`).join('');
}
function applyFilters() {
const search = document.getElementById('search-input').value.toLowerCase();
const competitor = document.getElementById('competitor-filter').value;
const type = document.getElementById('type-filter').value;
const lifecycle = document.getElementById('lifecycle-filter').value;
filteredData = processedData.filter(offer => {
const matchSearch = !search ||
offer.competitor.toLowerCase().includes(search) ||
offer.offerRaw.toLowerCase().includes(search);
const matchCompetitor = !competitor || offer.competitor === competitor;
const matchType = !type || offer.offerType === type;
const matchLifecycle = !lifecycle || offer.lifecycle === lifecycle;
return matchSearch && matchCompetitor && matchType && matchLifecycle;
});
currentPage = 1;
renderTable();
renderMarketComparison();
}
function getCompetitorColor(name) {
const colors = {
"CasinoMax": "bg-purple-500",
"VegasCasino": "bg-amber-500",
"Stake": "bg-green-500",
"JackpotCity": "bg-blue-500",
"SlotsOfVegas": "bg-red-500",
"CaptainJack": "bg-teal-500",
"Betnow": "bg-orange-500"
};
return colors[name] || "bg-slate-500";
}
function getTypeColor(type) {
const colors = {
"Match": "bg-blue-100 text-blue-700",
"Spins": "bg-purple-100 text-purple-700",
"Fixed": "bg-green-100 text-green-700",
"Cashback": "bg-amber-100 text-amber-700",
"Mixed": "bg-rose-100 text-rose-700",
"Unknown": "bg-slate-100 text-slate-500"
};
return colors[type] || "bg-slate-100 text-slate-500";
}
function getLifecycleColor(lc) {
const colors = {
"WEL": "bg-green-100 text-green-700",
"REA": "bg-blue-100 text-blue-700",
"RET": "bg-amber-100 text-amber-700",
"VIP": "bg-purple-100 text-purple-700"
};
return colors[lc] || "bg-slate-100 text-slate-600";
}
function renderTable() {
const tbody = document.getElementById('offers-body');
const start = (currentPage - 1) * perPage;
const end = Math.min(start + perPage, filteredData.length);
const pageData = filteredData.slice(start, end);
tbody.innerHTML = pageData.map(offer => {
const offerText = offer.offerRaw || '—';
const truncated = offerText.length > 30 ? offerText.substring(0, 30) + '…' : offerText;
return `
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4">
<div class="flex items-center gap-2">
<div class="w-6 h-6 ${getCompetitorColor(offer.competitor)} rounded text-white text-xs flex items-center justify-center font-bold">${offer.competitor[0]}</div>
<span class="font-medium text-sm">${offer.competitor}</span>
</div>
</td>
<td class="py-3 px-4 text-center"><span class="px-2 py-0.5 rounded text-xs font-medium ${getLifecycleColor(offer.lifecycle)}">${offer.lifecycle}</span></td>
<td class="py-3 px-4 text-center"><span class="px-2 py-0.5 rounded text-xs font-medium ${getTypeColor(offer.offerType)}">${offer.offerType}</span></td>
<td class="py-3 px-4 text-right font-bold text-emerald-600">$${offer.calculatedValue.toLocaleString()}</td>
<td class="py-3 px-4 text-center text-sm text-slate-600">${offer.wagering || '—'}</td>
<td class="py-3 px-4 text-right text-sm text-slate-600">${offer.maxCashout ? '$' + offer.maxCashout.toLocaleString() : '—'}</td>
<td class="py-3 px-4 text-sm text-slate-500" title="${offerText}">${truncated}</td>
</tr>
`}).join('');
// Update pagination
document.getElementById('showing-start').textContent = filteredData.length ? start + 1 : 0;
document.getElementById('showing-end').textContent = end;
document.getElementById('filtered-count').textContent = filteredData.length;
document.getElementById('current-page').textContent = currentPage;
document.getElementById('total-pages').textContent = Math.ceil(filteredData.length / perPage) || 1;
document.getElementById('prev-btn').disabled = currentPage === 1;
document.getElementById('next-btn').disabled = end >= filteredData.length;
}
function changePage(delta) {
const maxPage = Math.ceil(filteredData.length / perPage);
currentPage = Math.max(1, Math.min(currentPage + delta, maxPage));
renderTable();
}
function renderMarketComparison() {
const container = document.getElementById('market-comparison');
// Get selected competitors from navigation if available
let selectedCompetitors = [];
if (window.getActiveCompetitorsForReport) {
selectedCompetitors = window.getActiveCompetitorsForReport().map(c => c.shortName || c.displayName || c.id);
console.log('[D.5] Selected competitors from group:', selectedCompetitors);
}
// Filter to selected competitors if available, otherwise use all from data
const dataCompetitors = [...new Set(processedData.map(o => o.competitor))];
console.log('[D.5] Competitors with offer data:', dataCompetitors);
const competitors = selectedCompetitors.length > 0
? dataCompetitors.filter(c => selectedCompetitors.some(sc =>
c.toLowerCase().includes(sc.toLowerCase()) || sc.toLowerCase().includes(c.toLowerCase())
))
: dataCompetitors;
console.log('[D.5] Showing intersection:', competitors, `(${competitors.length} of ${selectedCompetitors.length} selected have offer data)`);
// Build stats for ALL selected competitors (show "No data" if missing)
const stats = [];
if (selectedCompetitors.length > 0) {
// Show all selected competitors
selectedCompetitors.forEach(sc => {
// Find matching competitor in data
const matchedData = dataCompetitors.find(dc =>
dc.toLowerCase().includes(sc.toLowerCase()) || sc.toLowerCase().includes(dc.toLowerCase())
);
if (matchedData) {
const offers = processedData.filter(o => o.competitor === matchedData);
const totalValue = offers.reduce((sum, o) => sum + o.calculatedValue, 0);
const avgValue = offers.length ? Math.round(totalValue / offers.length) : 0;
stats.push({ name: matchedData, count: offers.length, avg: avgValue, total: totalValue, hasData: true });
} else {
// No data for this selected competitor
stats.push({ name: sc, count: 0, avg: 0, total: 0, hasData: false });
}
});
} else {
// No group selected - show all from data
dataCompetitors.forEach(comp => {
const offers = processedData.filter(o => o.competitor === comp);
const totalValue = offers.reduce((sum, o) => sum + o.calculatedValue, 0);
const avgValue = offers.length ? Math.round(totalValue / offers.length) : 0;
stats.push({ name: comp, count: offers.length, avg: avgValue, total: totalValue, hasData: true });
});
}
// Sort: those with data first by avg, then "No data" at end
stats.sort((a, b) => {
if (a.hasData && !b.hasData) return -1;
if (!a.hasData && b.hasData) return 1;
return b.avg - a.avg;
});
const maxAvg = Math.max(...stats.filter(s => s.hasData).map(s => s.avg), 1);
const statsWithData = stats.filter(s => s.hasData);
const marketAvg = statsWithData.length ? Math.round(statsWithData.reduce((sum, s) => sum + s.avg, 0) / statsWithData.length) : 0;
container.innerHTML = stats.map(s => s.hasData ? `
<div class="bg-slate-50 rounded-lg p-4 text-center">
<div class="flex items-center justify-center gap-2 mb-2">
<div class="w-6 h-6 ${getCompetitorColor(s.name)} rounded text-white text-xs flex items-center justify-center font-bold">${s.name[0]}</div>
<span class="font-medium text-sm text-slate-700">${s.name}</span>
</div>
<div class="text-2xl font-bold text-slate-800">$${s.avg.toLocaleString()}</div>
<div class="text-xs text-slate-500">${s.count} offers</div>
<div class="mt-2 bg-slate-200 rounded-full h-2 overflow-hidden">
<div class="bg-purple-500 h-full rounded-full" style="width: ${(s.avg / maxAvg * 100)}%"></div>
</div>
</div>
` : `
<div class="bg-slate-100 rounded-lg p-4 text-center border border-dashed border-slate-300">
<div class="flex items-center justify-center gap-2 mb-2">
<div class="w-6 h-6 bg-slate-300 rounded text-slate-500 text-xs flex items-center justify-center font-bold">${s.name[0]}</div>
<span class="font-medium text-sm text-slate-400">${s.name}</span>
</div>
<div class="text-lg font-medium text-slate-400">No data</div>
<div class="text-xs text-slate-400">0 offers</div>
</div>
`).join('');
document.getElementById('market-avg').textContent = '$' + marketAvg.toLocaleString();
document.getElementById('market-avg-bar').style.width = (marketAvg / maxAvg * 100) + '%';
}
</script>
@endsection

@push('page-scripts')
<script>
        // Offer data - loaded from JSON
        let offerData = [];

        let currentPage = 1;
        const perPage = 25;
        let filteredData = [];
        let processedData = [];

        // Settings
        let spinValue = parseFloat(localStorage.getItem('d5_spin_value') || '0.10');
        let maxDeposit = parseInt(localStorage.getItem('d5_max_deposit') || '500');

        // Load data from JSON
        async function loadOfferData() {
            // Try database API first (Stage 3 migration)
            try {
                if (window.CRMT?.dal?.getOffers) {
                    console.log('[D.5] Loading offers from database API...');
                    const result = await CRMT.dal.getOffers({ limit: 1000 });

                    if (result.data && result.data.length > 0) {
                        offerData = result.data.map(o => ({
                            competitor: o.competitor_name || o.competitor_id,
                            lifecycle: o.lifecycle,
                            offerRaw: o.offer_raw,
                            offerType: o.offer_type,
                            calculatedValue: o.calculated_value,
                            wagering: o.wagering,
                            maxCashout: o.max_cashout,
                            matchPct: o.match_pct,
                            spins: o.spins
                        }));
                        console.log(`[D.5] ✅ Loaded ${offerData.length} offers from DATABASE`);
                        document.getElementById('total-count').textContent = offerData.length;
                        processOffers();
                        populateFilters();
                        applyFilters();
                        return;
                    }
                }
            } catch (e) {
                console.warn('[D.5] Database API unavailable:', e.message);
            }

            // No fallback - database only
            console.log('[D.5] No offer data in database - import via D.10');
            offerData = [];
            document.getElementById('total-count').textContent = '0'
            processOffers();
            populateFilters();
            applyFilters();
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('spin-value').value = spinValue;
            document.getElementById('max-deposit').value = maxDeposit;
            loadOfferData();

            // Listen for group changes to update market comparison
            if (window.setupGroupChangeListener) {
                window.setupGroupChangeListener(() => {
                    console.log('[D.5] Group changed, re-rendering market comparison');
                    renderMarketComparison();
                });
            }
            window.addEventListener('competitorGroupActivated', () => {
                console.log('[D.5] competitorGroupActivated event received');
                renderMarketComparison();
            });

            // Listen for market changes from navBar
            window.addEventListener('navBarChange', () => {
                console.log('[D.5] Market changed, reloading offer data');
                loadOfferData();
            });
        });

        function toggleSettings() {
            const panel = document.getElementById('settings-panel');
            const chevron = document.getElementById('settings-chevron');
            panel.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        }

        function saveSettings() {
            spinValue = parseFloat(document.getElementById('spin-value').value) || 0.10;
            maxDeposit = parseInt(document.getElementById('max-deposit').value) || 500;
            localStorage.setItem('d5_spin_value', spinValue);
            localStorage.setItem('d5_max_deposit', maxDeposit);
            processOffers();
            applyFilters();
        }

        function updateValuation() {
            saveSettings();
        }

        // Parse offer and calculate value
        function parseOffer(offerRaw) {
            if (!offerRaw) return { type: 'Unknown', value: 0 };

            const str = offerRaw.toString();
            let type = 'Unknown';
            let value = 0;

            // Mixed: Contains both % and spins
            if (/\d+%/.test(str) && /\d+\s*(Free\s*)?Spins/i.test(str)) {
                type = 'Mixed';
                const matchPct = str.match(/(\d+)%/);
                const matchSpins = str.match(/(\d+)\s*(Free\s*)?Spins/i);
                if (matchPct) value += (parseInt(matchPct[1]) / 100) * maxDeposit;
                if (matchSpins) value += parseInt(matchSpins[1]) * spinValue;
            }
            // Match %: standalone percentage
            else if (/^(\d+)%/.test(str) || /(\d+)%\s*(up to|match)?/i.test(str)) {
                type = 'Match';
                const match = str.match(/(\d+)%/);
                if (match) {
                    const pct = parseInt(match[1]);
                    // Check for "up to $X" limit
                    const upTo = str.match(/up to \$(\d+)/i);
                    if (upTo) {
                        value = Math.min((pct / 100) * maxDeposit, parseInt(upTo[1]));
                    } else {
                        value = (pct / 100) * maxDeposit;
                    }
                }
            }
            // Free Spins
            else if (/(\d+)\s*(Free\s*)?Spins/i.test(str)) {
                type = 'Spins';
                const match = str.match(/(\d+)\s*(Free\s*)?Spins/i);
                if (match) value = parseInt(match[1]) * spinValue;
            }
            // Cashback
            else if (/(\d+)%\s*Cashback/i.test(str) || /(\d+)%\s*Rakeback/i.test(str)) {
                type = 'Cashback';
                const match = str.match(/(\d+)%/);
                if (match) value = (parseInt(match[1]) / 100) * maxDeposit * 0.5; // Assume 50% avg loss
            }
            // Fixed $ amount
            else if (/\$(\d+)/i.test(str)) {
                type = 'Fixed';
                const match = str.match(/\$(\d+)/i);
                if (match) value = parseInt(match[1]);
            }
            // Reload (treated as Match at 50%)
            else if (/reload/i.test(str)) {
                type = 'Match';
                const match = str.match(/(\d+)%/);
                if (match) value = (parseInt(match[1]) / 100) * maxDeposit;
                else value = 0.5 * maxDeposit; // Default 50% reload
            }

            return { type, value: Math.round(value) };
        }

        function processOffers() {
            processedData = offerData.map(offer => {
                const parsed = parseOffer(offer.offerRaw);
                return {
                    ...offer,
                    offerType: parsed.type,
                    calculatedValue: parsed.value
                };
            });
            document.getElementById('total-count').textContent = processedData.length;
        }

        function populateFilters() {
            const competitors = [...new Set(offerData.map(o => o.competitor))].sort();
            const select = document.getElementById('competitor-filter');
            select.innerHTML = '<option value="">All Competitors</option>' +
                competitors.map(c => `<option value="${c}">${c}</option>`).join('');
        }

        function applyFilters() {
            const search = document.getElementById('search-input').value.toLowerCase();
            const competitor = document.getElementById('competitor-filter').value;
            const type = document.getElementById('type-filter').value;
            const lifecycle = document.getElementById('lifecycle-filter').value;

            filteredData = processedData.filter(offer => {
                const matchSearch = !search ||
                    offer.competitor.toLowerCase().includes(search) ||
                    offer.offerRaw.toLowerCase().includes(search);
                const matchCompetitor = !competitor || offer.competitor === competitor;
                const matchType = !type || offer.offerType === type;
                const matchLifecycle = !lifecycle || offer.lifecycle === lifecycle;
                return matchSearch && matchCompetitor && matchType && matchLifecycle;
            });

            currentPage = 1;
            renderTable();
            renderMarketComparison();
        }

        function getCompetitorColor(name) {
            const colors = {
                "CasinoMax": "bg-purple-500",
                "VegasCasino": "bg-amber-500",
                "Stake": "bg-green-500",
                "JackpotCity": "bg-blue-500",
                "SlotsOfVegas": "bg-red-500",
                "CaptainJack": "bg-teal-500",
                "Betnow": "bg-orange-500"
            };
            return colors[name] || "bg-slate-500";
        }

        function getTypeColor(type) {
            const colors = {
                "Match": "bg-blue-100 text-blue-700",
                "Spins": "bg-purple-100 text-purple-700",
                "Fixed": "bg-green-100 text-green-700",
                "Cashback": "bg-amber-100 text-amber-700",
                "Mixed": "bg-rose-100 text-rose-700",
                "Unknown": "bg-slate-100 text-slate-500"
            };
            return colors[type] || "bg-slate-100 text-slate-500";
        }

        function getLifecycleColor(lc) {
            const colors = {
                "WEL": "bg-green-100 text-green-700",
                "REA": "bg-blue-100 text-blue-700",
                "RET": "bg-amber-100 text-amber-700",
                "VIP": "bg-purple-100 text-purple-700"
            };
            return colors[lc] || "bg-slate-100 text-slate-600";
        }

        function renderTable() {
            const tbody = document.getElementById('offers-body');
            const start = (currentPage - 1) * perPage;
            const end = Math.min(start + perPage, filteredData.length);
            const pageData = filteredData.slice(start, end);

            tbody.innerHTML = pageData.map(offer => {
                const offerText = offer.offerRaw || '—';
                const truncated = offerText.length > 30 ? offerText.substring(0, 30) + '…' : offerText;
                return `
                <tr class="border-b border-slate-100 hover:bg-slate-50">
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 ${getCompetitorColor(offer.competitor)} rounded text-white text-xs flex items-center justify-center font-bold">${offer.competitor[0]}</div>
                            <span class="font-medium text-sm">${offer.competitor}</span>
                        </div>
                    </td>
                    <td class="py-3 px-4 text-center"><span class="px-2 py-0.5 rounded text-xs font-medium ${getLifecycleColor(offer.lifecycle)}">${offer.lifecycle}</span></td>
                    <td class="py-3 px-4 text-center"><span class="px-2 py-0.5 rounded text-xs font-medium ${getTypeColor(offer.offerType)}">${offer.offerType}</span></td>
                    <td class="py-3 px-4 text-right font-bold text-emerald-600">$${offer.calculatedValue.toLocaleString()}</td>
                    <td class="py-3 px-4 text-center text-sm text-slate-600">${offer.wagering || '—'}</td>
                    <td class="py-3 px-4 text-right text-sm text-slate-600">${offer.maxCashout ? '$' + offer.maxCashout.toLocaleString() : '—'}</td>
                    <td class="py-3 px-4 text-sm text-slate-500" title="${offerText}">${truncated}</td>
                </tr>
            `}).join('');

            // Update pagination
            document.getElementById('showing-start').textContent = filteredData.length ? start + 1 : 0;
            document.getElementById('showing-end').textContent = end;
            document.getElementById('filtered-count').textContent = filteredData.length;
            document.getElementById('current-page').textContent = currentPage;
            document.getElementById('total-pages').textContent = Math.ceil(filteredData.length / perPage) || 1;
            document.getElementById('prev-btn').disabled = currentPage === 1;
            document.getElementById('next-btn').disabled = end >= filteredData.length;
        }

        function changePage(delta) {
            const maxPage = Math.ceil(filteredData.length / perPage);
            currentPage = Math.max(1, Math.min(currentPage + delta, maxPage));
            renderTable();
        }

        function renderMarketComparison() {
            const container = document.getElementById('market-comparison');

            // Get selected competitors from navigation if available
            let selectedCompetitors = [];
            if (window.getActiveCompetitorsForReport) {
                selectedCompetitors = window.getActiveCompetitorsForReport().map(c => c.shortName || c.displayName || c.id);
                console.log('[D.5] Selected competitors from group:', selectedCompetitors);
            }

            // Filter to selected competitors if available, otherwise use all from data
            const dataCompetitors = [...new Set(processedData.map(o => o.competitor))];
            console.log('[D.5] Competitors with offer data:', dataCompetitors);

            const competitors = selectedCompetitors.length > 0
                ? dataCompetitors.filter(c => selectedCompetitors.some(sc =>
                    c.toLowerCase().includes(sc.toLowerCase()) || sc.toLowerCase().includes(c.toLowerCase())
                ))
                : dataCompetitors;

            console.log('[D.5] Showing intersection:', competitors, `(${competitors.length} of ${selectedCompetitors.length} selected have offer data)`);

            // Build stats for ALL selected competitors (show "No data" if missing)
            const stats = [];

            if (selectedCompetitors.length > 0) {
                // Show all selected competitors
                selectedCompetitors.forEach(sc => {
                    // Find matching competitor in data
                    const matchedData = dataCompetitors.find(dc =>
                        dc.toLowerCase().includes(sc.toLowerCase()) || sc.toLowerCase().includes(dc.toLowerCase())
                    );

                    if (matchedData) {
                        const offers = processedData.filter(o => o.competitor === matchedData);
                        const totalValue = offers.reduce((sum, o) => sum + o.calculatedValue, 0);
                        const avgValue = offers.length ? Math.round(totalValue / offers.length) : 0;
                        stats.push({ name: matchedData, count: offers.length, avg: avgValue, total: totalValue, hasData: true });
                    } else {
                        // No data for this selected competitor
                        stats.push({ name: sc, count: 0, avg: 0, total: 0, hasData: false });
                    }
                });
            } else {
                // No group selected - show all from data
                dataCompetitors.forEach(comp => {
                    const offers = processedData.filter(o => o.competitor === comp);
                    const totalValue = offers.reduce((sum, o) => sum + o.calculatedValue, 0);
                    const avgValue = offers.length ? Math.round(totalValue / offers.length) : 0;
                    stats.push({ name: comp, count: offers.length, avg: avgValue, total: totalValue, hasData: true });
                });
            }

            // Sort: those with data first by avg, then "No data" at end
            stats.sort((a, b) => {
                if (a.hasData && !b.hasData) return -1;
                if (!a.hasData && b.hasData) return 1;
                return b.avg - a.avg;
            });

            const maxAvg = Math.max(...stats.filter(s => s.hasData).map(s => s.avg), 1);
            const statsWithData = stats.filter(s => s.hasData);
            const marketAvg = statsWithData.length ? Math.round(statsWithData.reduce((sum, s) => sum + s.avg, 0) / statsWithData.length) : 0;

            container.innerHTML = stats.map(s => s.hasData ? `
                <div class="bg-slate-50 rounded-lg p-4 text-center">
                    <div class="flex items-center justify-center gap-2 mb-2">
                        <div class="w-6 h-6 ${getCompetitorColor(s.name)} rounded text-white text-xs flex items-center justify-center font-bold">${s.name[0]}</div>
                        <span class="font-medium text-sm text-slate-700">${s.name}</span>
                    </div>
                    <div class="text-2xl font-bold text-slate-800">$${s.avg.toLocaleString()}</div>
                    <div class="text-xs text-slate-500">${s.count} offers</div>
                    <div class="mt-2 bg-slate-200 rounded-full h-2 overflow-hidden">
                        <div class="bg-purple-500 h-full rounded-full" style="width: ${(s.avg / maxAvg * 100)}%"></div>
                    </div>
                </div>
            ` : `
                <div class="bg-slate-100 rounded-lg p-4 text-center border border-dashed border-slate-300">
                    <div class="flex items-center justify-center gap-2 mb-2">
                        <div class="w-6 h-6 bg-slate-300 rounded text-slate-500 text-xs flex items-center justify-center font-bold">${s.name[0]}</div>
                        <span class="font-medium text-sm text-slate-400">${s.name}</span>
                    </div>
                    <div class="text-lg font-medium text-slate-400">No data</div>
                    <div class="text-xs text-slate-400">0 offers</div>
                </div>
            `).join('');

            document.getElementById('market-avg').textContent = '$' + marketAvg.toLocaleString();
            document.getElementById('market-avg-bar').style.width = (marketAvg / maxAvg * 100) + '%';
        }
    </script>
@endpush
