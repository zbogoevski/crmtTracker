@extends('layouts.dashboard')


@section('title', 'CRMTracker - Country Recap')

@push('styles')
<style>
body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .country-row {
            cursor: pointer;
        }

        .country-row:hover {
            background-color: #f8fafc;
        }

        .jurisdiction-row {
            display: none;
        }

        .jurisdiction-row.show {
            display: table-row;
        }

        .channel-cell {
            position: relative;
        }

        .channel-cell:hover .timestamp-tooltip {
            display: block;
        }

        .timestamp-tooltip {
            display: none;
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: #1e293b;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            white-space: nowrap;
            z-index: 10;
        }

        .timestamp-tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 4px solid transparent;
            border-top-color: #1e293b;
        }

        .health-bar {
            height: 8px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
        }

        .health-fill {
            height: 100%;
            transition: width 0.3s ease;
        }
</style>
@endpush

@section('content')
<main class="flex-1 ml-20 p-8 max-w-[1600px] mx-auto">
<!-- Header -->
<header class="flex justify-between items-center mb-6">
<div class="flex items-center gap-3">
<span
class="text-xs font-medium bg-red-50 text-red-700 px-2 py-1 rounded border border-red-200">Admin</span>
<span
class="text-xs font-medium bg-emerald-50 text-emerald-700 px-2 py-1 rounded border border-emerald-200">A.3</span>
<h1 class="text-xl font-bold text-slate-800 ml-2">Country Recap</h1>
</div>
<div class="flex items-center gap-2">
<button onclick="refreshData()"
class="px-4 py-2 rounded-lg font-medium shadow-sm flex items-center gap-2 transition-colors bg-blue-600 hover:bg-blue-700 text-white">
<i class="fa-solid fa-rotate"></i> Refresh Data
</button>
</div>
</header>
<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
<div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-globe text-blue-500"></i>
<span class="text-xs font-bold text-blue-800 uppercase">Countries</span>
</div>
<p class="text-3xl font-bold text-blue-700" id="stat-countries">—</p>
</div>
<div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-map-location-dot text-purple-500"></i>
<span class="text-xs font-bold text-purple-800 uppercase">Jurisdictions</span>
</div>
<p class="text-3xl font-bold text-purple-700" id="stat-jurisdictions">—</p>
</div>
<div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-users text-emerald-500"></i>
<span class="text-xs font-bold text-emerald-800 uppercase">Total Competitors</span>
</div>
<p class="text-3xl font-bold text-emerald-700" id="stat-competitors">—</p>
</div>
<div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-chart-pie text-amber-500"></i>
<span class="text-xs font-bold text-amber-800 uppercase">Avg Health</span>
</div>
<p class="text-3xl font-bold text-amber-700" id="stat-health">—</p>
</div>
</div>
<!-- Country Recap Table -->
<!-- Data Freshness Panel -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mb-6">
<div class="p-4 border-b border-slate-200 bg-gradient-to-r from-emerald-50 to-teal-50">
<h3 class="font-bold text-slate-700">
<i class="fa-solid fa-clock-rotate-left text-emerald-500 mr-2"></i>
Data Freshness Indicators
</h3>
<p class="text-sm text-slate-500 mt-1">Last update timestamps for each data type</p>
</div>
<div class="p-4">
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4" id="freshness-grid">
<div class="bg-slate-50 rounded-lg p-3 border border-slate-200">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-users text-blue-500"></i>
<span class="text-xs font-semibold text-slate-700">Competitors</span>
</div>
<div id="fresh-competitors" class="text-sm font-bold text-slate-600">Loading...</div>
<div id="fresh-competitors-status" class="text-xs text-slate-400 mt-1"></div>
</div>
<div class="bg-slate-50 rounded-lg p-3 border border-slate-200">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-envelope text-purple-500"></i>
<span class="text-xs font-semibold text-slate-700">Email Hits</span>
</div>
<div id="fresh-hits" class="text-sm font-bold text-slate-600">Loading...</div>
<div id="fresh-hits-status" class="text-xs text-slate-400 mt-1"></div>
</div>
<div class="bg-slate-50 rounded-lg p-3 border border-slate-200">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-leaf text-green-500"></i>
<span class="text-xs font-semibold text-slate-700">Trackings</span>
</div>
<div id="fresh-trackings" class="text-sm font-bold text-slate-600">Loading...</div>
<div id="fresh-trackings-status" class="text-xs text-slate-400 mt-1"></div>
</div>
<div class="bg-slate-50 rounded-lg p-3 border border-slate-200">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-credit-card text-amber-500"></i>
<span class="text-xs font-semibold text-slate-700">Banking</span>
</div>
<div id="fresh-banking" class="text-sm font-bold text-slate-600">Loading...</div>
<div id="fresh-banking-status" class="text-xs text-slate-400 mt-1"></div>
</div>
<div class="bg-slate-50 rounded-lg p-3 border border-slate-200">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-crown text-yellow-500"></i>
<span class="text-xs font-semibold text-slate-700">VIP</span>
</div>
<div id="fresh-vip" class="text-sm font-bold text-slate-600">Loading...</div>
<div id="fresh-vip-status" class="text-xs text-slate-400 mt-1"></div>
</div>
<div class="bg-slate-50 rounded-lg p-3 border border-slate-200">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-puzzle-piece text-pink-500"></i>
<span class="text-xs font-semibold text-slate-700">Features</span>
</div>
<div id="fresh-features" class="text-sm font-bold text-slate-600">Loading...</div>
<div id="fresh-features-status" class="text-xs text-slate-400 mt-1"></div>
</div>
</div>
</div>
</div>
<!-- Country Recap Table -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
<div class="p-4 border-b border-slate-200 bg-slate-50">
<h3 class="font-bold text-slate-700">
<i class="fa-solid fa-table-cells text-blue-500 mr-2"></i>
Data Coverage by Country & Jurisdiction
</h3>
<p class="text-sm text-slate-500 mt-1">
Click a country row to expand its jurisdictions. Hover on cells to see last updated timestamps.
</p>
</div>
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-50 border-b border-slate-200">
<tr>
<th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs w-8"></th>
<th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs">Country /
Jurisdiction</th>
<th class="text-center py-3 px-3 font-bold text-slate-600 uppercase text-xs">Competitors
</th>
<th class="text-center py-3 px-2 font-bold text-slate-600 uppercase text-xs"
title="Email Hits">
<i class="fa-solid fa-envelope text-blue-500"></i>
</th>
<th class="text-center py-3 px-2 font-bold text-slate-600 uppercase text-xs"
title="SMS Hits">
<i class="fa-solid fa-comment-sms text-green-500"></i>
</th>
<th class="text-center py-3 px-2 font-bold text-slate-600 uppercase text-xs"
title="Call Hits">
<i class="fa-solid fa-phone text-amber-500"></i>
</th>
<th class="text-center py-3 px-2 font-bold text-slate-600 uppercase text-xs"
title="Push Hits">
<i class="fa-solid fa-bell text-purple-500"></i>
</th>
<th class="text-center py-3 px-3 font-bold text-slate-600 uppercase text-xs">Licenses
</th>
<th class="text-center py-3 px-3 font-bold text-slate-600 uppercase text-xs">Companies
</th>
<th class="text-center py-3 px-3 font-bold text-slate-600 uppercase text-xs">Regulations
</th>
<th class="text-center py-3 px-3 font-bold text-slate-600 uppercase text-xs">Offers</th>
<th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs w-32">Health
</th>
</tr>
</thead>
<tbody id="recap-tbody" class="divide-y divide-slate-100">
<tr>
<td colspan="12" class="py-8 text-center text-slate-400">
<i class="fa-solid fa-spinner fa-spin mr-2"></i>Loading country data...
</td>
</tr>
</tbody>
</table>
</div>
</div>
<!-- Legend -->
<div class="mt-4 flex items-center gap-6 text-xs text-slate-500">
<div class="flex items-center gap-2">
<i class="fa-solid fa-envelope text-blue-500"></i> Emails
</div>
<div class="flex items-center gap-2">
<i class="fa-solid fa-comment-sms text-green-500"></i> SMS
</div>
<div class="flex items-center gap-2">
<i class="fa-solid fa-phone text-amber-500"></i> Calls
</div>
<div class="flex items-center gap-2">
<i class="fa-solid fa-bell text-purple-500"></i> Push
</div>
<div class="flex items-center gap-2 border-l border-slate-200 pl-6">
<span class="w-3 h-3 bg-emerald-500 rounded-full"></span> ≥80% Health
</div>
<div class="flex items-center gap-2">
<span class="w-3 h-3 bg-amber-500 rounded-full"></span> 40-79% Health
</div>
<div class="flex items-center gap-2">
<span class="w-3 h-3 bg-rose-500 rounded-full"></span>
<40% Health </div>
</div>
</main>
</div>
<script>
// Country/Jurisdiction data structure for recap
let recapData = null;
// Use centralized marketsConfig as source of truth
function getMarketsConfig() {
return window.CRMT?.marketsConfig?.countries || {};
}
// Load all recap data
async function loadRecapData() {
const config = getMarketsConfig();
const data = {};
// Initialize structure for each market from centralized config
for (const [countryCode, country] of Object.entries(config)) {
data[countryCode] = {
name: country.name,
flag: country.flag,
code: countryCode,
jurisdictions: {}
};
for (const [jurCode, jurData] of Object.entries(country.jurisdictions)) {
const marketId = `${countryCode}-${jurCode}`;
data[countryCode].jurisdictions[jurCode] = {
code: jurCode,
marketId,
name: jurData.name,
regulator: jurData.regulator,
competitors: 0,
channels: { email: 0, sms: 0, call: 0, push: 0 },
channelTimestamps: { email: null, sms: null, call: null, push: null },
licenses: { count: 0, total: 0 },
companies: { count: 0, total: 0 },
regulations: jurData.regulationsDocumented || false,
offers: 0,
lastUpdated: null
};
}
}
// Try to load competitor data from DAL
try {
const competitors = await CRMT.dal.getCompetitors();
console.log('[A.3] Loaded', competitors.length, 'competitors from database');
for (const c of competitors) {
if (!c.market_id) continue;
const [countryCode, jurCode] = c.market_id.split('-');
if (data[countryCode]?.jurisdictions[jurCode]) {
const jur = data[countryCode].jurisdictions[jurCode];
jur.competitors++;
jur.licenses.total++;
jur.companies.total++;
if (c.license_status === 'licensed') jur.licenses.count++;
if (c.company_name || c.entity_name) jur.companies.count++;
}
}
} catch (e) {
console.warn('[A.3] Failed to load competitors:', e.message);
// Fall back to local CRMT.competitors
if (window.CRMT?.competitors) {
for (const c of Object.values(CRMT.competitors)) {
const [countryCode, jurCode] = (c.market || '').split('-');
if (data[countryCode]?.jurisdictions[jurCode]) {
const jur = data[countryCode].jurisdictions[jurCode];
jur.competitors++;
jur.licenses.total++;
jur.companies.total++;
if (c.license?.status === 'licensed') jur.licenses.count++;
if (c.entity?.known) jur.companies.count++;
}
}
}
}
// Try to load hits data
try {
// Get channel breakdown per market
const hits = await CRMT.dal.getHits({ limit: 10000 });
if (hits.data) {
for (const hit of hits.data) {
const marketId = hit.market_id || hit.competitor?.market_id;
if (!marketId) continue;
const [countryCode, jurCode] = marketId.split('-');
if (data[countryCode]?.jurisdictions[jurCode]) {
const jur = data[countryCode].jurisdictions[jurCode];
const channel = (hit.channel || 'email').toLowerCase();
if (jur.channels[channel] !== undefined) {
jur.channels[channel]++;
// Track latest timestamp per channel
const hitDate = hit.hit_date || hit.created_at;
if (hitDate && (!jur.channelTimestamps[channel] || hitDate > jur.channelTimestamps[channel])) {
jur.channelTimestamps[channel] = hitDate;
}
}
// Track overall last updated
const updated = hit.created_at || hit.hit_date;
if (updated && (!jur.lastUpdated || updated > jur.lastUpdated)) {
jur.lastUpdated = updated;
}
}
}
}
} catch (e) {
console.warn('[A.3] Failed to load hits:', e.message);
}
// Try to load offers data
try {
const offers = await CRMT.dal.getOffers();
if (offers.data) {
for (const offer of offers.data) {
const marketId = offer.market_id;
if (!marketId) continue;
const [countryCode, jurCode] = marketId.split('-');
if (data[countryCode]?.jurisdictions[jurCode]) {
data[countryCode].jurisdictions[jurCode].offers++;
}
}
}
} catch (e) {
console.warn('[A.3] Failed to load offers:', e.message);
}
return data;
}
// Calculate health score for a jurisdiction
function calculateHealth(jur) {
let score = 0;
let total = 0;
// Competitors (20%)
total += 20;
if (jur.competitors > 0) score += 20;
// Channels - at least one hit type (30%)
total += 30;
const hitCount = jur.channels.email + jur.channels.sms + jur.channels.call + jur.channels.push;
if (hitCount > 0) score += 30;
// Licenses (15%)
total += 15;
if (jur.licenses.total > 0 && jur.licenses.count > 0) {
score += Math.round(15 * (jur.licenses.count / jur.licenses.total));
}
// Companies (15%)
total += 15;
if (jur.companies.total > 0 && jur.companies.count > 0) {
score += Math.round(15 * (jur.companies.count / jur.companies.total));
}
// Regulations (10%)
total += 10;
if (jur.regulations) score += 10;
// Offers (10%)
total += 10;
if (jur.offers > 0) score += 10;
return Math.round((score / total) * 100);
}
// Format date for display
function formatDate(dateStr) {
if (!dateStr) return 'Never';
const date = new Date(dateStr);
return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: '2-digit' });
}
// Get health bar color class
function getHealthColor(pct) {
if (pct >= 80) return 'bg-emerald-500';
if (pct >= 40) return 'bg-amber-500';
return 'bg-rose-500';
}
// Render the recap table
function renderTable() {
if (!recapData) return;
const tbody = document.getElementById('recap-tbody');
let html = '';
let totalCountries = 0;
let totalJurisdictions = 0;
let totalCompetitors = 0;
let totalHealth = 0;
let healthCount = 0;
for (const [countryCode, country] of Object.entries(recapData)) {
const jurList = Object.values(country.jurisdictions);
if (jurList.length === 0) continue;
totalCountries++;
// Aggregate country-level stats
const countryStats = {
competitors: 0,
channels: { email: 0, sms: 0, call: 0, push: 0 },
licenses: { count: 0, total: 0 },
companies: { count: 0, total: 0 },
regulations: 0,
offers: 0,
health: 0
};
for (const jur of jurList) {
totalJurisdictions++;
countryStats.competitors += jur.competitors;
countryStats.channels.email += jur.channels.email;
countryStats.channels.sms += jur.channels.sms;
countryStats.channels.call += jur.channels.call;
countryStats.channels.push += jur.channels.push;
countryStats.licenses.count += jur.licenses.count;
countryStats.licenses.total += jur.licenses.total;
countryStats.companies.count += jur.companies.count;
countryStats.companies.total += jur.companies.total;
if (jur.regulations) countryStats.regulations++;
countryStats.offers += jur.offers;
const jurHealth = calculateHealth(jur);
jur.healthScore = jurHealth;
countryStats.health += jurHealth;
totalHealth += jurHealth;
healthCount++;
}
totalCompetitors += countryStats.competitors;
const avgHealth = Math.round(countryStats.health / jurList.length);
const regStatus = countryStats.regulations === jurList.length ? '✓' :
countryStats.regulations > 0 ? `${countryStats.regulations}/${jurList.length}` : '✗';
// Country row (expandable)
html += `
<tr class="country-row bg-slate-50 hover:bg-slate-100 font-medium" onclick="toggleCountry('${countryCode}')">
<td class="py-3 px-4 text-center">
<i class="fa-solid fa-chevron-right text-slate-400 transition-transform country-chevron-${countryCode}"></i>
</td>
<td class="py-3 px-4">
<span class="text-lg mr-2">${country.flag}</span>
<span class="font-semibold">${country.name}</span>
<span class="text-xs text-slate-500 ml-2">(${jurList.length} jurisdictions)</span>
</td>
<td class="py-3 px-3 text-center font-bold">${countryStats.competitors || '—'}</td>
<td class="py-3 px-2 text-center">${countryStats.channels.email || '—'}</td>
<td class="py-3 px-2 text-center">${countryStats.channels.sms || '—'}</td>
<td class="py-3 px-2 text-center">${countryStats.channels.call || '—'}</td>
<td class="py-3 px-2 text-center">${countryStats.channels.push || '—'}</td>
<td class="py-3 px-3 text-center">
${countryStats.licenses.total > 0 ? `${countryStats.licenses.count}/${countryStats.licenses.total}` : '—'}
</td>
<td class="py-3 px-3 text-center">
${countryStats.companies.total > 0 ? `${countryStats.companies.count}/${countryStats.companies.total}` : '—'}
</td>
<td class="py-3 px-3 text-center">
<span class="${regStatus === '✓' ? 'text-emerald-600' : regStatus === '✗' ? 'text-rose-500' : 'text-amber-600'}">${regStatus}</span>
</td>
<td class="py-3 px-3 text-center">${countryStats.offers || '—'}</td>
<td class="py-3 px-4">
<div class="flex items-center gap-2">
<div class="health-bar w-16">
<div class="health-fill ${getHealthColor(avgHealth)}" style="width: ${avgHealth}%"></div>
</div>
<span class="text-sm font-medium">${avgHealth}%</span>
</div>
</td>
</tr>
`;
// Jurisdiction rows (hidden by default)
for (const jur of jurList) {
const licensesPct = jur.licenses.total > 0 ? Math.round((jur.licenses.count / jur.licenses.total) * 100) : 0;
const companiesPct = jur.companies.total > 0 ? Math.round((jur.companies.count / jur.companies.total) * 100) : 0;
html += `
<tr class="jurisdiction-row jurisdiction-${countryCode} hover:bg-slate-50">
<td class="py-3 px-4"></td>
<td class="py-3 px-4 pl-12">
<span class="text-slate-400">↳</span>
<span class="font-medium ml-2">${jur.name}</span>
<span class="text-xs text-slate-500 ml-1">(${jur.regulator})</span>
</td>
<td class="py-3 px-3 text-center">${jur.competitors || '—'}</td>
<td class="py-3 px-2 text-center channel-cell">
${jur.channels.email || '—'}
${jur.channelTimestamps.email ? `<div class="timestamp-tooltip">Updated: ${formatDate(jur.channelTimestamps.email)}</div>` : ''}
</td>
<td class="py-3 px-2 text-center channel-cell">
${jur.channels.sms || '—'}
${jur.channelTimestamps.sms ? `<div class="timestamp-tooltip">Updated: ${formatDate(jur.channelTimestamps.sms)}</div>` : ''}
</td>
<td class="py-3 px-2 text-center channel-cell">
${jur.channels.call || '—'}
${jur.channelTimestamps.call ? `<div class="timestamp-tooltip">Updated: ${formatDate(jur.channelTimestamps.call)}</div>` : ''}
</td>
<td class="py-3 px-2 text-center channel-cell">
${jur.channels.push || '—'}
${jur.channelTimestamps.push ? `<div class="timestamp-tooltip">Updated: ${formatDate(jur.channelTimestamps.push)}</div>` : ''}
</td>
<td class="py-3 px-3 text-center">
${jur.licenses.total > 0 ? `
<span class="${licensesPct >= 80 ? 'text-emerald-600' : licensesPct >= 40 ? 'text-amber-600' : 'text-rose-500'}">
${jur.licenses.count}/${jur.licenses.total}
</span>
<span class="text-xs text-slate-400 ml-1">(${licensesPct}%)</span>
` : '—'}
</td>
<td class="py-3 px-3 text-center">
${jur.companies.total > 0 ? `
<span class="${companiesPct >= 80 ? 'text-emerald-600' : companiesPct >= 40 ? 'text-amber-600' : 'text-rose-500'}">
${jur.companies.count}/${jur.companies.total}
</span>
<span class="text-xs text-slate-400 ml-1">(${companiesPct}%)</span>
` : '—'}
</td>
<td class="py-3 px-3 text-center">
${jur.regulations
? '<span class="text-emerald-600"><i class="fa-solid fa-check-circle"></i></span>'
: '<span class="text-rose-500"><i class="fa-solid fa-times-circle"></i></span>'}
</td>
<td class="py-3 px-3 text-center">${jur.offers || '—'}</td>
<td class="py-3 px-4">
<div class="flex items-center gap-2">
<div class="health-bar w-16">
<div class="health-fill ${getHealthColor(jur.healthScore)}" style="width: ${jur.healthScore}%"></div>
</div>
<span class="text-sm">${jur.healthScore}%</span>
</div>
</td>
</tr>
`;
}
}
tbody.innerHTML = html;
// Update summary cards
document.getElementById('stat-countries').textContent = totalCountries;
document.getElementById('stat-jurisdictions').textContent = totalJurisdictions;
document.getElementById('stat-competitors').textContent = totalCompetitors;
document.getElementById('stat-health').textContent = healthCount > 0 ? Math.round(totalHealth / healthCount) + '%' : '—';
}
// Toggle country expansion
window.toggleCountry = function (countryCode) {
const rows = document.querySelectorAll(`.jurisdiction-${countryCode}`);
const chevron = document.querySelector(`.country-chevron-${countryCode}`);
rows.forEach(row => row.classList.toggle('show'));
chevron?.classList.toggle('rotate-90');
};
// Refresh data
window.refreshData = async function () {
const tbody = document.getElementById('recap-tbody');
tbody.innerHTML = '<tr><td colspan="12" class="py-8 text-center text-slate-400"><i class="fa-solid fa-spinner fa-spin mr-2"></i>Loading country data...</td></tr>';
recapData = await loadRecapData();
renderTable();
console.log('[A.3] Country recap data loaded:', recapData);
};
// Load data freshness timestamps
async function loadFreshness() {
const endpoints = [
{ id: 'competitors', url: '/.netlify/functions/competitors', field: 'updated_at' },
{ id: 'hits', url: '/.netlify/functions/hits?limit=1&order=desc', field: 'created_at' },
{ id: 'trackings', url: '/.netlify/functions/trackings', field: 'updated_at' },
{ id: 'banking', url: '/.netlify/functions/product-data?type=banking', field: 'updated_at' },
{ id: 'vip', url: '/.netlify/functions/product-data?type=vip', field: 'updated_at' },
{ id: 'features', url: '/.netlify/functions/product-data?type=feature-categories', field: 'created_at' }
];
for (const ep of endpoints) {
const el = document.getElementById(`fresh-${ep.id}`);
const statusEl = document.getElementById(`fresh-${ep.id}-status`);
try {
const res = await fetch(ep.url);
const json = await res.json();
const data = json.data || json;
// Find latest timestamp
let latest = null;
let count = Array.isArray(data) ? data.length : 0;
if (Array.isArray(data) && data.length > 0) {
for (const item of data) {
const ts = item[ep.field] || item.created_at || item.updated_at;
if (ts && (!latest || ts > latest)) latest = ts;
}
}
if (latest) {
const ago = getTimeAgo(new Date(latest));
el.textContent = ago;
statusEl.textContent = `${count} records`;
el.className = getFreshnessClass(latest);
} else {
el.textContent = 'No data';
statusEl.textContent = count > 0 ? `${count} records` : '';
el.className = 'text-sm font-bold text-slate-400';
}
} catch (e) {
el.textContent = 'Error';
statusEl.textContent = '';
el.className = 'text-sm font-bold text-rose-500';
}
}
}
function getTimeAgo(date) {
const now = new Date();
const diffMs = now - date;
const diffMins = Math.floor(diffMs / 60000);
const diffHours = Math.floor(diffMins / 60);
const diffDays = Math.floor(diffHours / 24);
if (diffDays > 30) return Math.floor(diffDays / 30) + 'mo ago';
if (diffDays > 0) return diffDays + 'd ago';
if (diffHours > 0) return diffHours + 'h ago';
if (diffMins > 0) return diffMins + 'm ago';
return 'Just now';
}
function getFreshnessClass(timestamp) {
const now = new Date();
const date = new Date(timestamp);
const diffDays = (now - date) / (1000 * 60 * 60 * 24);
if (diffDays <= 1) return 'text-sm font-bold text-emerald-600';
if (diffDays <= 7) return 'text-sm font-bold text-amber-600';
return 'text-sm font-bold text-rose-500';
}
// Initialize on load
document.addEventListener('DOMContentLoaded', async () => {
// Wait for CRMT to be ready
const waitForCRMT = () => {
if (window.CRMT?.dal) {
refreshData();
loadFreshness();
} else {
setTimeout(waitForCRMT, 100);
}
};
waitForCRMT();
});
</script>
@endsection

@push('page-scripts')
<script>
        // Country/Jurisdiction data structure for recap
        let recapData = null;

        // Use centralized marketsConfig as source of truth
        function getMarketsConfig() {
            return window.CRMT?.marketsConfig?.countries || {};
        }

        // Load all recap data
        async function loadRecapData() {
            const config = getMarketsConfig();
            const data = {};

            // Initialize structure for each market from centralized config
            for (const [countryCode, country] of Object.entries(config)) {
                data[countryCode] = {
                    name: country.name,
                    flag: country.flag,
                    code: countryCode,
                    jurisdictions: {}
                };

                for (const [jurCode, jurData] of Object.entries(country.jurisdictions)) {
                    const marketId = `${countryCode}-${jurCode}`;
                    data[countryCode].jurisdictions[jurCode] = {
                        code: jurCode,
                        marketId,
                        name: jurData.name,
                        regulator: jurData.regulator,
                        competitors: 0,
                        channels: { email: 0, sms: 0, call: 0, push: 0 },
                        channelTimestamps: { email: null, sms: null, call: null, push: null },
                        licenses: { count: 0, total: 0 },
                        companies: { count: 0, total: 0 },
                        regulations: jurData.regulationsDocumented || false,
                        offers: 0,
                        lastUpdated: null
                    };
                }
            }

            // Try to load competitor data from DAL
            try {
                const competitors = await CRMT.dal.getCompetitors();
                console.log('[A.3] Loaded', competitors.length, 'competitors from database');

                for (const c of competitors) {
                    if (!c.market_id) continue;
                    const [countryCode, jurCode] = c.market_id.split('-');

                    if (data[countryCode]?.jurisdictions[jurCode]) {
                        const jur = data[countryCode].jurisdictions[jurCode];
                        jur.competitors++;
                        jur.licenses.total++;
                        jur.companies.total++;

                        if (c.license_status === 'licensed') jur.licenses.count++;
                        if (c.company_name || c.entity_name) jur.companies.count++;
                    }
                }
            } catch (e) {
                console.warn('[A.3] Failed to load competitors:', e.message);
                // Fall back to local CRMT.competitors
                if (window.CRMT?.competitors) {
                    for (const c of Object.values(CRMT.competitors)) {
                        const [countryCode, jurCode] = (c.market || '').split('-');
                        if (data[countryCode]?.jurisdictions[jurCode]) {
                            const jur = data[countryCode].jurisdictions[jurCode];
                            jur.competitors++;
                            jur.licenses.total++;
                            jur.companies.total++;
                            if (c.license?.status === 'licensed') jur.licenses.count++;
                            if (c.entity?.known) jur.companies.count++;
                        }
                    }
                }
            }

            // Try to load hits data
            try {
                // Get channel breakdown per market
                const hits = await CRMT.dal.getHits({ limit: 10000 });
                if (hits.data) {
                    for (const hit of hits.data) {
                        const marketId = hit.market_id || hit.competitor?.market_id;
                        if (!marketId) continue;

                        const [countryCode, jurCode] = marketId.split('-');
                        if (data[countryCode]?.jurisdictions[jurCode]) {
                            const jur = data[countryCode].jurisdictions[jurCode];
                            const channel = (hit.channel || 'email').toLowerCase();

                            if (jur.channels[channel] !== undefined) {
                                jur.channels[channel]++;

                                // Track latest timestamp per channel
                                const hitDate = hit.hit_date || hit.created_at;
                                if (hitDate && (!jur.channelTimestamps[channel] || hitDate > jur.channelTimestamps[channel])) {
                                    jur.channelTimestamps[channel] = hitDate;
                                }
                            }

                            // Track overall last updated
                            const updated = hit.created_at || hit.hit_date;
                            if (updated && (!jur.lastUpdated || updated > jur.lastUpdated)) {
                                jur.lastUpdated = updated;
                            }
                        }
                    }
                }
            } catch (e) {
                console.warn('[A.3] Failed to load hits:', e.message);
            }

            // Try to load offers data
            try {
                const offers = await CRMT.dal.getOffers();
                if (offers.data) {
                    for (const offer of offers.data) {
                        const marketId = offer.market_id;
                        if (!marketId) continue;

                        const [countryCode, jurCode] = marketId.split('-');
                        if (data[countryCode]?.jurisdictions[jurCode]) {
                            data[countryCode].jurisdictions[jurCode].offers++;
                        }
                    }
                }
            } catch (e) {
                console.warn('[A.3] Failed to load offers:', e.message);
            }

            return data;
        }

        // Calculate health score for a jurisdiction
        function calculateHealth(jur) {
            let score = 0;
            let total = 0;

            // Competitors (20%)
            total += 20;
            if (jur.competitors > 0) score += 20;

            // Channels - at least one hit type (30%)
            total += 30;
            const hitCount = jur.channels.email + jur.channels.sms + jur.channels.call + jur.channels.push;
            if (hitCount > 0) score += 30;

            // Licenses (15%)
            total += 15;
            if (jur.licenses.total > 0 && jur.licenses.count > 0) {
                score += Math.round(15 * (jur.licenses.count / jur.licenses.total));
            }

            // Companies (15%)
            total += 15;
            if (jur.companies.total > 0 && jur.companies.count > 0) {
                score += Math.round(15 * (jur.companies.count / jur.companies.total));
            }

            // Regulations (10%)
            total += 10;
            if (jur.regulations) score += 10;

            // Offers (10%)
            total += 10;
            if (jur.offers > 0) score += 10;

            return Math.round((score / total) * 100);
        }

        // Format date for display
        function formatDate(dateStr) {
            if (!dateStr) return 'Never';
            const date = new Date(dateStr);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: '2-digit' });
        }

        // Get health bar color class
        function getHealthColor(pct) {
            if (pct >= 80) return 'bg-emerald-500';
            if (pct >= 40) return 'bg-amber-500';
            return 'bg-rose-500';
        }

        // Render the recap table
        function renderTable() {
            if (!recapData) return;

            const tbody = document.getElementById('recap-tbody');
            let html = '';

            let totalCountries = 0;
            let totalJurisdictions = 0;
            let totalCompetitors = 0;
            let totalHealth = 0;
            let healthCount = 0;

            for (const [countryCode, country] of Object.entries(recapData)) {
                const jurList = Object.values(country.jurisdictions);
                if (jurList.length === 0) continue;

                totalCountries++;

                // Aggregate country-level stats
                const countryStats = {
                    competitors: 0,
                    channels: { email: 0, sms: 0, call: 0, push: 0 },
                    licenses: { count: 0, total: 0 },
                    companies: { count: 0, total: 0 },
                    regulations: 0,
                    offers: 0,
                    health: 0
                };

                for (const jur of jurList) {
                    totalJurisdictions++;
                    countryStats.competitors += jur.competitors;
                    countryStats.channels.email += jur.channels.email;
                    countryStats.channels.sms += jur.channels.sms;
                    countryStats.channels.call += jur.channels.call;
                    countryStats.channels.push += jur.channels.push;
                    countryStats.licenses.count += jur.licenses.count;
                    countryStats.licenses.total += jur.licenses.total;
                    countryStats.companies.count += jur.companies.count;
                    countryStats.companies.total += jur.companies.total;
                    if (jur.regulations) countryStats.regulations++;
                    countryStats.offers += jur.offers;

                    const jurHealth = calculateHealth(jur);
                    jur.healthScore = jurHealth;
                    countryStats.health += jurHealth;
                    totalHealth += jurHealth;
                    healthCount++;
                }

                totalCompetitors += countryStats.competitors;
                const avgHealth = Math.round(countryStats.health / jurList.length);
                const regStatus = countryStats.regulations === jurList.length ? '✓' :
                    countryStats.regulations > 0 ? `${countryStats.regulations}/${jurList.length}` : '✗';

                // Country row (expandable)
                html += `
                    <tr class="country-row bg-slate-50 hover:bg-slate-100 font-medium" onclick="toggleCountry('${countryCode}')">
                        <td class="py-3 px-4 text-center">
                            <i class="fa-solid fa-chevron-right text-slate-400 transition-transform country-chevron-${countryCode}"></i>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-lg mr-2">${country.flag}</span>
                            <span class="font-semibold">${country.name}</span>
                            <span class="text-xs text-slate-500 ml-2">(${jurList.length} jurisdictions)</span>
                        </td>
                        <td class="py-3 px-3 text-center font-bold">${countryStats.competitors || '—'}</td>
                        <td class="py-3 px-2 text-center">${countryStats.channels.email || '—'}</td>
                        <td class="py-3 px-2 text-center">${countryStats.channels.sms || '—'}</td>
                        <td class="py-3 px-2 text-center">${countryStats.channels.call || '—'}</td>
                        <td class="py-3 px-2 text-center">${countryStats.channels.push || '—'}</td>
                        <td class="py-3 px-3 text-center">
                            ${countryStats.licenses.total > 0 ? `${countryStats.licenses.count}/${countryStats.licenses.total}` : '—'}
                        </td>
                        <td class="py-3 px-3 text-center">
                            ${countryStats.companies.total > 0 ? `${countryStats.companies.count}/${countryStats.companies.total}` : '—'}
                        </td>
                        <td class="py-3 px-3 text-center">
                            <span class="${regStatus === '✓' ? 'text-emerald-600' : regStatus === '✗' ? 'text-rose-500' : 'text-amber-600'}">${regStatus}</span>
                        </td>
                        <td class="py-3 px-3 text-center">${countryStats.offers || '—'}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <div class="health-bar w-16">
                                    <div class="health-fill ${getHealthColor(avgHealth)}" style="width: ${avgHealth}%"></div>
                                </div>
                                <span class="text-sm font-medium">${avgHealth}%</span>
                            </div>
                        </td>
                    </tr>
                `;

                // Jurisdiction rows (hidden by default)
                for (const jur of jurList) {
                    const licensesPct = jur.licenses.total > 0 ? Math.round((jur.licenses.count / jur.licenses.total) * 100) : 0;
                    const companiesPct = jur.companies.total > 0 ? Math.round((jur.companies.count / jur.companies.total) * 100) : 0;

                    html += `
                        <tr class="jurisdiction-row jurisdiction-${countryCode} hover:bg-slate-50">
                            <td class="py-3 px-4"></td>
                            <td class="py-3 px-4 pl-12">
                                <span class="text-slate-400">↳</span>
                                <span class="font-medium ml-2">${jur.name}</span>
                                <span class="text-xs text-slate-500 ml-1">(${jur.regulator})</span>
                            </td>
                            <td class="py-3 px-3 text-center">${jur.competitors || '—'}</td>
                            <td class="py-3 px-2 text-center channel-cell">
                                ${jur.channels.email || '—'}
                                ${jur.channelTimestamps.email ? `<div class="timestamp-tooltip">Updated: ${formatDate(jur.channelTimestamps.email)}</div>` : ''}
                            </td>
                            <td class="py-3 px-2 text-center channel-cell">
                                ${jur.channels.sms || '—'}
                                ${jur.channelTimestamps.sms ? `<div class="timestamp-tooltip">Updated: ${formatDate(jur.channelTimestamps.sms)}</div>` : ''}
                            </td>
                            <td class="py-3 px-2 text-center channel-cell">
                                ${jur.channels.call || '—'}
                                ${jur.channelTimestamps.call ? `<div class="timestamp-tooltip">Updated: ${formatDate(jur.channelTimestamps.call)}</div>` : ''}
                            </td>
                            <td class="py-3 px-2 text-center channel-cell">
                                ${jur.channels.push || '—'}
                                ${jur.channelTimestamps.push ? `<div class="timestamp-tooltip">Updated: ${formatDate(jur.channelTimestamps.push)}</div>` : ''}
                            </td>
                            <td class="py-3 px-3 text-center">
                                ${jur.licenses.total > 0 ? `
                                    <span class="${licensesPct >= 80 ? 'text-emerald-600' : licensesPct >= 40 ? 'text-amber-600' : 'text-rose-500'}">
                                        ${jur.licenses.count}/${jur.licenses.total}
                                    </span>
                                    <span class="text-xs text-slate-400 ml-1">(${licensesPct}%)</span>
                                ` : '—'}
                            </td>
                            <td class="py-3 px-3 text-center">
                                ${jur.companies.total > 0 ? `
                                    <span class="${companiesPct >= 80 ? 'text-emerald-600' : companiesPct >= 40 ? 'text-amber-600' : 'text-rose-500'}">
                                        ${jur.companies.count}/${jur.companies.total}
                                    </span>
                                    <span class="text-xs text-slate-400 ml-1">(${companiesPct}%)</span>
                                ` : '—'}
                            </td>
                            <td class="py-3 px-3 text-center">
                                ${jur.regulations
                            ? '<span class="text-emerald-600"><i class="fa-solid fa-check-circle"></i></span>'
                            : '<span class="text-rose-500"><i class="fa-solid fa-times-circle"></i></span>'}
                            </td>
                            <td class="py-3 px-3 text-center">${jur.offers || '—'}</td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <div class="health-bar w-16">
                                        <div class="health-fill ${getHealthColor(jur.healthScore)}" style="width: ${jur.healthScore}%"></div>
                                    </div>
                                    <span class="text-sm">${jur.healthScore}%</span>
                                </div>
                            </td>
                        </tr>
                    `;
                }
            }

            tbody.innerHTML = html;

            // Update summary cards
            document.getElementById('stat-countries').textContent = totalCountries;
            document.getElementById('stat-jurisdictions').textContent = totalJurisdictions;
            document.getElementById('stat-competitors').textContent = totalCompetitors;
            document.getElementById('stat-health').textContent = healthCount > 0 ? Math.round(totalHealth / healthCount) + '%' : '—';
        }

        // Toggle country expansion
        window.toggleCountry = function (countryCode) {
            const rows = document.querySelectorAll(`.jurisdiction-${countryCode}`);
            const chevron = document.querySelector(`.country-chevron-${countryCode}`);

            rows.forEach(row => row.classList.toggle('show'));
            chevron?.classList.toggle('rotate-90');
        };

        // Refresh data
        window.refreshData = async function () {
            const tbody = document.getElementById('recap-tbody');
            tbody.innerHTML = '<tr><td colspan="12" class="py-8 text-center text-slate-400"><i class="fa-solid fa-spinner fa-spin mr-2"></i>Loading country data...</td></tr>';

            recapData = await loadRecapData();
            renderTable();

            console.log('[A.3] Country recap data loaded:', recapData);
        };

        // Load data freshness timestamps
        async function loadFreshness() {
            const endpoints = [
                { id: 'competitors', url: '/.netlify/functions/competitors', field: 'updated_at' },
                { id: 'hits', url: '/.netlify/functions/hits?limit=1&order=desc', field: 'created_at' },
                { id: 'trackings', url: '/.netlify/functions/trackings', field: 'updated_at' },
                { id: 'banking', url: '/.netlify/functions/product-data?type=banking', field: 'updated_at' },
                { id: 'vip', url: '/.netlify/functions/product-data?type=vip', field: 'updated_at' },
                { id: 'features', url: '/.netlify/functions/product-data?type=feature-categories', field: 'created_at' }
            ];

            for (const ep of endpoints) {
                const el = document.getElementById(`fresh-${ep.id}`);
                const statusEl = document.getElementById(`fresh-${ep.id}-status`);

                try {
                    const res = await fetch(ep.url);
                    const json = await res.json();
                    const data = json.data || json;

                    // Find latest timestamp
                    let latest = null;
                    let count = Array.isArray(data) ? data.length : 0;

                    if (Array.isArray(data) && data.length > 0) {
                        for (const item of data) {
                            const ts = item[ep.field] || item.created_at || item.updated_at;
                            if (ts && (!latest || ts > latest)) latest = ts;
                        }
                    }

                    if (latest) {
                        const ago = getTimeAgo(new Date(latest));
                        el.textContent = ago;
                        statusEl.textContent = `${count} records`;
                        el.className = getFreshnessClass(latest);
                    } else {
                        el.textContent = 'No data';
                        statusEl.textContent = count > 0 ? `${count} records` : '';
                        el.className = 'text-sm font-bold text-slate-400';
                    }
                } catch (e) {
                    el.textContent = 'Error';
                    statusEl.textContent = '';
                    el.className = 'text-sm font-bold text-rose-500';
                }
            }
        }

        function getTimeAgo(date) {
            const now = new Date();
            const diffMs = now - date;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMins / 60);
            const diffDays = Math.floor(diffHours / 24);

            if (diffDays > 30) return Math.floor(diffDays / 30) + 'mo ago';
            if (diffDays > 0) return diffDays + 'd ago';
            if (diffHours > 0) return diffHours + 'h ago';
            if (diffMins > 0) return diffMins + 'm ago';
            return 'Just now';
        }

        function getFreshnessClass(timestamp) {
            const now = new Date();
            const date = new Date(timestamp);
            const diffDays = (now - date) / (1000 * 60 * 60 * 24);

            if (diffDays <= 1) return 'text-sm font-bold text-emerald-600';
            if (diffDays <= 7) return 'text-sm font-bold text-amber-600';
            return 'text-sm font-bold text-rose-500';
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', async () => {
            // Wait for CRMT to be ready
            const waitForCRMT = () => {
                if (window.CRMT?.dal) {
                    refreshData();
                    loadFreshness();
                } else {
                    setTimeout(waitForCRMT, 100);
                }
            };
            waitForCRMT();
        });
    </script>
@endpush
