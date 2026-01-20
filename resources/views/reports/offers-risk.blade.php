@extends('layouts.dashboard')


@section('title', 'CRMTracker - Offers & Risk')

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
            border-bottom: 3px solid #F59E0B;
            color: #F59E0B;
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

        .risk-high {
            background: #FEE2E2;
            color: #991B1B;
        }

        .risk-medium {
            background: #FEF3C7;
            color: #92400E;
        }

        .risk-low {
            background: #D1FAE5;
            color: #065F46;
        }
</style>
@endpush

@section('content')
<div class="space-y-6">
<header class="flex justify-between items-center mb-6">
<div class="flex items-center gap-3">
<span
class="text-xs font-medium bg-amber-50 text-amber-700 px-2 py-1 rounded border border-amber-200">Module
2.2</span>
<span
class="text-xs font-medium bg-red-50 text-red-700 px-2 py-1 rounded border border-red-200">Risk
& Offers</span>
</div>
<div id="date-range-container" class="flex items-center"></div>
<button
class="px-6 py-2.5 rounded-lg font-medium shadow-sm flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white">
<i class="fa-solid fa-download"></i> Export
</button>
</header>
<!-- Tab Navigation -->
<div class="bg-white rounded-t-xl border-b border-slate-200">
<div class="flex">
<button class="tab-btn active px-6 py-4 text-sm" onclick="switchTab('risk')">
<i class="fa-solid fa-shield-halved mr-2 text-red-500"></i>Compliance Risk
</button>
<button class="tab-btn px-6 py-4 text-sm text-slate-500" onclick="switchTab('offers')">
<i class="fa-solid fa-gift mr-2 text-amber-500"></i>Jackpots & Offers
</button>
<button class="tab-btn px-6 py-4 text-sm text-slate-500" onclick="switchTab('bundles')">
<i class="fa-solid fa-layer-group mr-2 text-purple-500"></i>Bundles
</button>
</div>
</div>
<!-- Tab Content -->
<div class="bg-white rounded-b-xl shadow-sm border border-t-0 border-slate-100 p-6 relative">
<!-- Loading Overlay -->
<div id="loading-overlay"
class="absolute inset-0 bg-white/90 flex items-center justify-center z-10 rounded-b-xl">
<div class="text-center">
<i class="fa-solid fa-spinner fa-spin text-4xl text-amber-500 mb-3"></i>
<p class="text-slate-600 font-medium">Loading offers data...</p>
</div>
</div>
<!-- Risk Tab -->
<div id="tab-risk" class="tab-content active">
<div class="flex justify-between items-center mb-6">
<div>
<h3 class="font-bold text-lg text-slate-800">Compliance Risk by Competitor</h3>
<p class="text-sm text-slate-500">Risk assessment based on promotional content - <span
class="font-semibold" id="risk-total">0</span> analyzed</p>
</div>
</div>
<!-- Competitor Risk Cards Side by Side -->
<div id="risk-cards" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6"></div>
<div class="bg-slate-50 rounded-lg p-4 mb-6">
<h4 class="font-semibold text-slate-700 mb-3">Risk Distribution by Competitor</h4>
<div style="height: 300px;"><canvas id="chart-risk"></canvas></div>
</div>
<!-- Risk Level Reference Table -->
<div class="border border-slate-200 rounded-lg overflow-hidden">
<div class="bg-slate-100 px-4 py-2 border-b border-slate-200">
<h4 class="font-semibold text-slate-700 text-sm">
<i class="fa-solid fa-info-circle mr-2 text-blue-500"></i>Risk Level Classification
Reference
</h4>
</div>
<table class="w-full text-sm">
<thead class="bg-slate-50">
<tr>
<th class="px-4 py-2 text-left font-semibold text-slate-600 w-28">Level</th>
<th class="px-4 py-2 text-left font-semibold text-slate-600">Trigger Keywords</th>
<th class="px-4 py-2 text-left font-semibold text-slate-600 w-64">Description</th>
</tr>
</thead>
<tbody class="divide-y divide-slate-100">
<tr>
<td class="px-4 py-3">
<span class="risk-high px-2 py-1 rounded text-xs font-bold">High</span>
</td>
<td class="px-4 py-3">
<span
class="bg-red-50 text-red-700 px-2 py-0.5 rounded text-xs mr-1">guaranteed</span>
<span class="bg-red-50 text-red-700 px-2 py-0.5 rounded text-xs mr-1">no
deposit</span>
<span
class="bg-red-50 text-red-700 px-2 py-0.5 rounded text-xs mr-1">risk-free</span>
<span
class="bg-red-50 text-red-700 px-2 py-0.5 rounded text-xs mr-1">unlimited</span>
<span
class="bg-red-50 text-red-700 px-2 py-0.5 rounded text-xs mr-1">jackpot</span>
<span class="bg-red-50 text-red-700 px-2 py-0.5 rounded text-xs">winner</span>
</td>
<td class="px-4 py-3 text-slate-600 text-xs">
Claims that may mislead players about winning probabilities or risk
</td>
</tr>
<tr>
<td class="px-4 py-3">
<span class="risk-medium px-2 py-1 rounded text-xs font-bold">Medium</span>
</td>
<td class="px-4 py-3">
<span
class="bg-amber-50 text-amber-700 px-2 py-0.5 rounded text-xs mr-1">bonus</span>
<span class="bg-amber-50 text-amber-700 px-2 py-0.5 rounded text-xs mr-1">free
spins</span>
<span
class="bg-amber-50 text-amber-700 px-2 py-0.5 rounded text-xs mr-1">cashback</span>
<span
class="bg-amber-50 text-amber-700 px-2 py-0.5 rounded text-xs mr-1">offer</span>
<span
class="bg-amber-50 text-amber-700 px-2 py-0.5 rounded text-xs">exclusive</span>
</td>
<td class="px-4 py-3 text-slate-600 text-xs">
Standard promotional content requiring T&Cs disclosure
</td>
</tr>
<tr>
<td class="px-4 py-3">
<span class="risk-low px-2 py-1 rounded text-xs font-bold">Low</span>
</td>
<td class="px-4 py-3 text-slate-500 italic text-xs">
No trigger keywords detected
</td>
<td class="px-4 py-3 text-slate-600 text-xs">
Informational or transactional content with minimal compliance risk
</td>
</tr>
</tbody>
</table>
</div>
</div>
<!-- Offers Tab -->
<div id="tab-offers" class="tab-content">
<div class="flex justify-between items-center mb-6">
<div>
<h3 class="font-bold text-lg text-slate-800">Promotional Offers by Competitor</h3>
<p class="text-sm text-slate-500">Jackpots, bonuses and promotional content</p>
</div>
</div>
<!-- Competitor Offer Cards Side by Side -->
<div id="offers-cards" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6"></div>
<div class="grid grid-cols-2 gap-6 mb-6">
<div class="bg-slate-50 rounded-lg p-4">
<h4 class="font-semibold text-slate-700 mb-3">Offers by Type</h4>
<div style="height: 280px;"><canvas id="chart-offers-type"></canvas></div>
</div>
<div class="bg-slate-50 rounded-lg p-4">
<h4 class="font-semibold text-slate-700 mb-3">Promo Volume by Competitor</h4>
<div style="height: 280px;"><canvas id="chart-offers-competitor"></canvas></div>
</div>
</div>
<!-- Promotion Value Analysis -->
<div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-lg p-4 border border-amber-200">
<h4 class="font-semibold text-amber-700 mb-3">
<i class="fa-solid fa-coins mr-2"></i>Promotion Value Analysis
</h4>
<p class="text-xs text-slate-500 mb-4">Extracted values from promotional subject lines (bonus %,
spins count, etc.)</p>
<div class="grid grid-cols-2 gap-4">
<div style="height: 240px;"><canvas id="chart-promo-values"></canvas></div>
<div id="promo-value-breakdown" class="space-y-2"></div>
</div>
</div>
</div>
<!-- Bundles Tab -->
<div id="tab-bundles" class="tab-content">
<div class="flex justify-between items-center mb-6">
<div>
<h3 class="font-bold text-lg text-slate-800">Bundle & Package Analysis</h3>
<p class="text-sm text-slate-500">Multi-day/multi-offer patterns by competitor</p>
</div>
</div>
<!-- Competitor Bundle Cards Side by Side -->
<div id="bundles-cards" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6"></div>
<div class="text-center py-4 text-slate-500 mb-6" id="bundles-content">
<i class="fa-solid fa-layer-group text-2xl mb-2 opacity-50"></i>
<p class="text-sm">Bundle days = days with 2+ promotional emails from the same competitor</p>
</div>
<!-- Bundle Pairwise Heatmap -->
<div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-lg p-4 border border-purple-200">
<h4 class="font-semibold text-purple-700 mb-3">
<i class="fa-solid fa-grip mr-2"></i>Offer Type Co-occurrence Matrix
</h4>
<p class="text-xs text-slate-500 mb-4">Which offer types are frequently bundled together</p>
<div id="bundle-heatmap-grid" class="overflow-x-auto"></div>
</div>
</div>
</div>
</div>
</div>
<script>
// Use Laravel data if available
const laravelData = @json($data ?? []);
console.log('[2.2] Laravel data loaded:', laravelData.length, 'competitors');

let charts = {};
let allOffers = [];
let allHits = [];
let competitors = [];

// Populate competitors, offers, and hits from Laravel data
if (laravelData.length > 0) {
    competitors = laravelData.map(c => ({
        id: c.competitor_id,
        name: c.competitor_name,
        shortName: c.short_name,
    }));
    
    // Flatten all offers
    laravelData.forEach(comp => {
        if (comp.offers && comp.offers.length > 0) {
            allOffers = allOffers.concat(comp.offers.map(offer => ({
                ...offer,
                competitor_id: comp.competitor_id,
                competitor_name: comp.competitor_name,
            })));
        }
    });
    
    // Flatten all hits (from high_risk_hits and total_hits)
    laravelData.forEach(comp => {
        if (comp.high_risk_hits && comp.high_risk_hits.length > 0) {
            allHits = allHits.concat(comp.high_risk_hits.map(hit => ({
                ...hit,
                competitor_id: comp.competitor_id,
                competitor_name: comp.competitor_name,
            })));
        }
    });
    
    console.log('[2.2] Using Laravel data - Competitors:', competitors.length, 'Offers:', allOffers.length, 'Hits:', allHits.length);
}
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
if (hash && ['risk', 'offers', 'bundles'].includes(hash)) {
const btn = document.querySelector(`.tab-btn[onclick*="${hash}"]`);
if (btn) btn.click();
}
}
async function fetchData() {
    // Use Laravel data if available
    if (laravelData.length > 0 && (allOffers.length > 0 || allHits.length > 0)) {
        console.log('[2.2] Using Laravel data - Offers:', allOffers.length, 'Hits:', allHits.length);
        return;
    }
    
    // Fallback to old API if no Laravel data
    try {
        const offersRes = await window.CRMT?.dal?.getOffers();
        allOffers = offersRes?.data || [];
        console.log('[2.2] Loaded', allOffers.length, 'offers');
        const competitorIds = competitors.map(c => c.id);
        if (competitorIds.length > 0) {
            const hitsRes = await window.CRMT?.dal?.getTimelineHits(competitorIds);
            allHits = hitsRes?.data || [];
            console.log('[2.2] Loaded', allHits.length, 'hits for risk analysis');
        }
    } catch (e) {
        console.error('[2.2] Failed to fetch data:', e);
    }
}
function assessRisk(hit) {
const subject = (hit.subject || '').toLowerCase();
const highRiskTerms = ['guaranteed', 'no deposit', 'risk-free', 'unlimited', 'jackpot', 'winner'];
const mediumRiskTerms = ['bonus', 'free spins', 'cashback', 'offer', 'exclusive'];
for (const term of highRiskTerms) {
if (subject.includes(term)) return 'high';
}
for (const term of mediumRiskTerms) {
if (subject.includes(term)) return 'medium';
}
return 'low';
}
function renderRisk() {
    const cardsContainer = document.getElementById('risk-cards');
    
    // Use Laravel risk data if available
    let riskByCompetitor = {};
    if (laravelData.length > 0) {
        laravelData.forEach(comp => {
            if (comp.risk) {
                riskByCompetitor[comp.competitor_id] = comp.risk;
            }
        });
        const totalRisk = Object.values(riskByCompetitor).reduce((sum, r) => sum + (r.total || 0), 0);
        document.getElementById('risk-total').textContent = totalRisk;
    } else {
        // Fallback: calculate from hits
        document.getElementById('risk-total').textContent = allHits.length;
        allHits.forEach(h => {
            const cid = h.competitor_id;
            if (!riskByCompetitor[cid]) riskByCompetitor[cid] = { high: 0, medium: 0, low: 0, total: 0 };
            const risk = assessRisk(h);
            riskByCompetitor[cid][risk]++;
            riskByCompetitor[cid].total++;
        });
    }
// Render risk cards
cardsContainer.innerHTML = '';
competitors.forEach((c, idx) => {
const color = COLORS[idx % COLORS.length];
const risk = riskByCompetitor[c.id] || { high: 0, medium: 0, low: 0, total: 0 };
const score = risk.score !== undefined ? risk.score : (risk.total > 0
    ? Math.round((risk.high * 3 + risk.medium * 2 + risk.low) / risk.total * 33.3)
    : 0);
const riskLabel = score > 66 ? 'High' : score > 33 ? 'Med' : 'Low';
const riskIcon = score > 66 ? 'fa-triangle-exclamation text-red-500' : score > 33 ? 'fa-exclamation-circle text-amber-500' : 'fa-check-circle text-green-500';
cardsContainer.innerHTML += `
<div class="competitor-card ${color.bg} ${color.border} border-2 rounded-xl p-4 text-center">
<div class="font-bold ${color.text} text-sm mb-2 truncate" title="${c.name}">${c.shortName || c.name}</div>
<div class="text-3xl font-black ${color.text}">${score}%</div>
<div class="text-xs text-slate-500 mt-1"><i class="fa-solid ${riskIcon} mr-1"></i>${riskLabel} Risk</div>
<div class="grid grid-cols-3 gap-1 mt-3 pt-2 border-t border-slate-200 text-xs">
<div>
<div class="font-bold text-red-600">${risk.high}</div>
<div class="text-slate-400">High</div>
</div>
<div>
<div class="font-bold text-amber-600">${risk.medium}</div>
<div class="text-slate-400">Med</div>
</div>
<div>
<div class="font-bold text-green-600">${risk.low}</div>
<div class="text-slate-400">Low</div>
</div>
</div>
</div>
`;
});
// Risk chart (stacked)
if (charts['risk']) charts['risk'].destroy();
const ctx = document.getElementById('chart-risk').getContext('2d');
charts['risk'] = new Chart(ctx, {
type: 'bar',
data: {
labels: competitors.map(c => c.shortName || c.name),
datasets: [
{
label: 'High Risk',
data: competitors.map(c => (riskByCompetitor[c.id] || {}).high || 0),
backgroundColor: '#EF4444'
},
{
label: 'Medium Risk',
data: competitors.map(c => (riskByCompetitor[c.id] || {}).medium || 0),
backgroundColor: '#F59E0B'
},
{
label: 'Low Risk',
data: competitors.map(c => (riskByCompetitor[c.id] || {}).low || 0),
backgroundColor: '#10B981'
}
]
},
options: {
responsive: true,
maintainAspectRatio: false,
scales: { x: { stacked: true }, y: { stacked: true } },
plugins: { legend: { position: 'bottom' } }
}
});
}
function renderOffers() {
const cardsContainer = document.getElementById('offers-cards');
// Group by competitor
const byCompetitor = {};
allHits.forEach(h => {
const cid = h.competitor_id;
if (!byCompetitor[cid]) byCompetitor[cid] = [];
byCompetitor[cid].push(h);
});
// Find promotional hits
const promoHits = allHits.filter(h => {
const subject = (h.subject || '').toLowerCase();
return subject.includes('bonus') || subject.includes('free') ||
subject.includes('offer') || subject.includes('jackpot') ||
subject.includes('spin') || subject.includes('deposit');
});
const promoByCompetitor = {};
promoHits.forEach(h => {
const cid = h.competitor_id;
if (!promoByCompetitor[cid]) promoByCompetitor[cid] = [];
promoByCompetitor[cid].push(h);
});
// Render offer cards
cardsContainer.innerHTML = '';
competitors.forEach((c, idx) => {
const color = COLORS[idx % COLORS.length];
const totalHits = (byCompetitor[c.id] || []).length;
const promos = (promoByCompetitor[c.id] || []).length;
const promoRate = totalHits > 0 ? Math.round(promos / totalHits * 100) : 0;
cardsContainer.innerHTML += `
<div class="competitor-card ${color.bg} ${color.border} border-2 rounded-xl p-4 text-center">
<div class="font-bold ${color.text} text-sm mb-2 truncate" title="${c.name}">${c.shortName || c.name}</div>
<div class="text-3xl font-black ${color.text}">${promos}</div>
<div class="text-xs text-slate-500 mt-1">Promos</div>
<div class="mt-3 pt-2 border-t border-slate-200">
<div class="text-xs text-slate-500">Promo Rate</div>
<div class="font-bold ${color.text}">${promoRate}%</div>
</div>
</div>
`;
});
// Type distribution
const typeCount = { Bonus: 0, 'Free Spins': 0, Cashback: 0, Jackpot: 0, Other: 0 };
promoHits.forEach(h => {
const s = (h.subject || '').toLowerCase();
if (s.includes('bonus')) typeCount.Bonus++;
else if (s.includes('spin')) typeCount['Free Spins']++;
else if (s.includes('cash')) typeCount.Cashback++;
else if (s.includes('jackpot')) typeCount.Jackpot++;
else typeCount.Other++;
});
if (charts['type']) charts['type'].destroy();
const typeCtx = document.getElementById('chart-offers-type').getContext('2d');
charts['type'] = new Chart(typeCtx, {
type: 'doughnut',
data: {
labels: Object.keys(typeCount),
datasets: [{
data: Object.values(typeCount),
backgroundColor: ['#F59E0B', '#8B5CF6', '#10B981', '#3B82F6', '#94A3B8']
}]
},
options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
});
if (charts['competitor']) charts['competitor'].destroy();
const compCtx = document.getElementById('chart-offers-competitor').getContext('2d');
charts['competitor'] = new Chart(compCtx, {
type: 'bar',
data: {
labels: competitors.map(c => c.shortName || c.name),
datasets: [{
label: 'Promos',
data: competitors.map(c => (promoByCompetitor[c.id] || []).length),
backgroundColor: competitors.map((_, idx) => COLORS[idx % COLORS.length].accent)
}]
},
options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
});
// Promotion Value Analysis
renderPromoValues(promoHits);
}
function renderPromoValues(promoHits) {
// Extract numerical values from subject lines
const valuePatterns = {
'Bonus %': /\b(\d+)\s*%/g,
'Free Spins': /\b(\d+)\s*(?:free\s*)?spins?/gi,
'Cash Amount': /[$€£](\d+)/g,
'Deposit Match': /\b(\d+)x/gi
};
const extractedValues = { 'Bonus %': [], 'Free Spins': [], 'Cash Amount': [], 'Deposit Match': [] };
promoHits.forEach(h => {
const text = (h.subject || '');
for (const [type, regex] of Object.entries(valuePatterns)) {
let match;
const re = new RegExp(regex.source, regex.flags);
while ((match = re.exec(text)) !== null) {
extractedValues[type].push(parseInt(match[1]));
}
}
});
// Chart - average values by type
if (charts['promoValues']) charts['promoValues'].destroy();
const ctx = document.getElementById('chart-promo-values')?.getContext('2d');
if (ctx) {
const avgValues = Object.entries(extractedValues).map(([type, values]) => ({
type,
avg: values.length > 0 ? Math.round(values.reduce((a, b) => a + b, 0) / values.length) : 0,
count: values.length,
max: values.length > 0 ? Math.max(...values) : 0
}));
charts['promoValues'] = new Chart(ctx, {
type: 'bar',
data: {
labels: avgValues.map(v => v.type),
datasets: [{
label: 'Average Value',
data: avgValues.map(v => v.avg),
backgroundColor: ['#F59E0B', '#8B5CF6', '#10B981', '#3B82F6'],
borderRadius: 4
}]
},
options: {
indexAxis: 'y',
responsive: true,
maintainAspectRatio: false,
plugins: { legend: { display: false } }
}
});
}
// Breakdown panel
const breakdown = document.getElementById('promo-value-breakdown');
if (breakdown) {
breakdown.innerHTML = Object.entries(extractedValues).map(([type, values]) => {
const count = values.length;
const avg = count > 0 ? Math.round(values.reduce((a, b) => a + b, 0) / count) : 0;
const max = count > 0 ? Math.max(...values) : 0;
const icon = type.includes('%') ? 'fa-percent' : type.includes('Spin') ? 'fa-dice' : type.includes('Cash') ? 'fa-dollar-sign' : 'fa-arrows-rotate';
return `
<div class="bg-white rounded-lg p-3 border border-slate-200 flex items-center gap-3">
<i class="fa-solid ${icon} text-amber-500"></i>
<div class="flex-1">
<div class="font-medium text-sm">${type}</div>
<div class="text-xs text-slate-500">${count} found</div>
</div>
<div class="text-right">
<div class="font-bold text-amber-600">${avg}</div>
<div class="text-xs text-slate-400">avg</div>
</div>
<div class="text-right">
<div class="font-bold text-indigo-600">${max}</div>
<div class="text-xs text-slate-400">max</div>
</div>
</div>
`;
}).join('');
}
}
function renderBundles() {
const cardsContainer = document.getElementById('bundles-cards');
const content = document.getElementById('bundles-content');
// Find multi-day promotional patterns per competitor
const bundlesByCompetitor = {};
competitors.forEach(c => {
const compHits = allHits.filter(h => h.competitor_id === c.id);
const byDate = {};
compHits.forEach(h => {
const date = new Date(h.received_at).toDateString();
if (!byDate[date]) byDate[date] = [];
byDate[date].push(h);
});
const multiDays = Object.values(byDate).filter(arr => arr.length > 1);
bundlesByCompetitor[c.id] = {
bundleCount: multiDays.length,
totalBundled: multiDays.reduce((a, b) => a + b.length, 0),
avgSize: multiDays.length > 0 ? (multiDays.reduce((a, b) => a + b.length, 0) / multiDays.length).toFixed(1) : 0
};
});
// Render bundle cards
cardsContainer.innerHTML = '';
competitors.forEach((c, idx) => {
const color = COLORS[idx % COLORS.length];
const bundle = bundlesByCompetitor[c.id] || { bundleCount: 0, totalBundled: 0, avgSize: 0 };
cardsContainer.innerHTML += `
<div class="competitor-card ${color.bg} ${color.border} border-2 rounded-xl p-4 text-center">
<div class="font-bold ${color.text} text-sm mb-2 truncate" title="${c.name}">${c.shortName || c.name}</div>
<div class="text-3xl font-black ${color.text}">${bundle.bundleCount}</div>
<div class="text-xs text-slate-500 mt-1">Bundle Days</div>
<div class="grid grid-cols-2 gap-2 mt-3 pt-2 border-t border-slate-200">
<div>
<div class="text-xs text-slate-500">Bundled</div>
<div class="font-bold ${color.text}">${bundle.totalBundled}</div>
</div>
<div>
<div class="text-xs text-slate-500">Avg Size</div>
<div class="font-bold ${color.text}">${bundle.avgSize}</div>
</div>
</div>
</div>
`;
});
content.innerHTML = `<div class="text-center py-2 text-slate-500">
<p class="text-xs">Bundle days = days with 2+ promotional emails from the same competitor</p>
</div>`;
// Bundle Pairwise Heatmap
renderBundleHeatmap();
}
function renderBundleHeatmap() {
const offerTypes = ['Bonus', 'Spins', 'Cashback', 'Jackpot', 'Deposit'];
const coOccurrence = {};
offerTypes.forEach(a => {
coOccurrence[a] = {};
offerTypes.forEach(b => coOccurrence[a][b] = 0);
});
// Find days with multiple promos and count co-occurrences
competitors.forEach(c => {
const compHits = allHits.filter(h => h.competitor_id === c.id);
const byDate = {};
compHits.forEach(h => {
const date = new Date(h.received_at).toDateString();
if (!byDate[date]) byDate[date] = [];
byDate[date].push(h);
});
// For each bundle day, detect offer types and count pairs
Object.values(byDate).filter(arr => arr.length > 1).forEach(dayHits => {
const typesFound = new Set();
dayHits.forEach(h => {
const s = (h.subject || '').toLowerCase();
if (s.includes('bonus')) typesFound.add('Bonus');
if (s.includes('spin')) typesFound.add('Spins');
if (s.includes('cash')) typesFound.add('Cashback');
if (s.includes('jackpot')) typesFound.add('Jackpot');
if (s.includes('deposit')) typesFound.add('Deposit');
});
const types = Array.from(typesFound);
types.forEach(a => {
types.forEach(b => {
if (a <= b) coOccurrence[a][b]++;
});
});
});
});
// Find max for color scaling
let maxCount = 1;
offerTypes.forEach(a => offerTypes.forEach(b => {
if (coOccurrence[a][b] > maxCount) maxCount = coOccurrence[a][b];
}));
// Render heatmap grid
const grid = document.getElementById('bundle-heatmap-grid');
if (grid) {
let html = '<table class="w-full text-sm"><thead><tr><th class="p-2"></th>';
offerTypes.forEach(t => html += `<th class="p-2 text-xs font-medium text-slate-600">${t}</th>`);
html += '</tr></thead><tbody>';
offerTypes.forEach(a => {
html += `<tr><td class="p-2 text-xs font-medium text-slate-600">${a}</td>`;
offerTypes.forEach(b => {
const count = a <= b ? coOccurrence[a][b] : coOccurrence[b][a];
const intensity = count / maxCount;
const bgColor = count === 0 ? 'bg-slate-100' :
intensity > 0.7 ? 'bg-purple-500 text-white' :
intensity > 0.4 ? 'bg-purple-300' : 'bg-purple-100';
html += `<td class="p-2 text-center ${bgColor} rounded">${count || '-'}</td>`;
});
html += '</tr>';
});
html += '</tbody></table>';
grid.innerHTML = html;
}
}
async function initDashboard() {
    // If Laravel data is available, use it immediately
    if (laravelData.length > 0) {
        console.log('[2.2] Initializing with Laravel data...');
        await fetchData();
        renderRisk();
        renderOffers();
        renderBundles();
        checkHash();
        document.getElementById('loading-overlay')?.classList.add('hidden');
        return;
    }
    
    // If no Laravel data, show message and hide loading overlay
    console.warn('[2.2] No Laravel data available');
    document.getElementById('loading-overlay')?.classList.add('hidden');
    // Show "No data available" message in cards container
    const cardsContainer = document.getElementById('risk-cards');
    if (cardsContainer) {
        cardsContainer.innerHTML = '<div class="col-span-full text-center py-8 text-slate-500">No data available. Please run seeders to populate the database.</div>';
    }
    
    // Otherwise, wait for CRMT dataLoader
    if (!window.CRMT?.dataLoader || !window.getActiveCompetitorsForReport) {
        setTimeout(initDashboard, 200);
        return;
    }
    competitors = window.getActiveCompetitorsForReport?.() || [];
    console.log('[2.2] Active competitors:', competitors.length);
    await fetchData();
    renderRisk();
    renderOffers();
    renderBundles();
    checkHash();
    // Hide loading overlay
    document.getElementById('loading-overlay')?.classList.add('hidden');
    window.addEventListener('dataLoaderChange', async () => {
        competitors = window.getActiveCompetitorsForReport?.() || [];
        await fetchData();
        renderRisk();
        renderOffers();
        renderBundles();
    });
}

// Initialize immediately
document.addEventListener('DOMContentLoaded', function() {
    console.log('[2.2] DOMContentLoaded - Initializing...');
    initDashboard();
});
</script>
@endsection

@push('page-scripts')
<script>
        let charts = {};
        let allOffers = [];
        let allHits = [];
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
            if (hash && ['risk', 'offers', 'bundles'].includes(hash)) {
                const btn = document.querySelector(`.tab-btn[onclick*="${hash}"]`);
                if (btn) btn.click();
            }
        }

        async function fetchData() {
            try {
                const offersRes = await window.CRMT?.dal?.getOffers();
                allOffers = offersRes?.data || [];
                console.log('[2.2] Loaded', allOffers.length, 'offers');

                const competitorIds = competitors.map(c => c.id);
                if (competitorIds.length > 0) {
                    const hitsRes = await window.CRMT?.dal?.getTimelineHits(competitorIds);
                    allHits = hitsRes?.data || [];
                    console.log('[2.2] Loaded', allHits.length, 'hits for risk analysis');
                }
            } catch (e) {
                console.error('[2.2] Failed to fetch data:', e);
            }
        }

        function assessRisk(hit) {
            const subject = (hit.subject || '').toLowerCase();
            const highRiskTerms = ['guaranteed', 'no deposit', 'risk-free', 'unlimited', 'jackpot', 'winner'];
            const mediumRiskTerms = ['bonus', 'free spins', 'cashback', 'offer', 'exclusive'];

            for (const term of highRiskTerms) {
                if (subject.includes(term)) return 'high';
            }
            for (const term of mediumRiskTerms) {
                if (subject.includes(term)) return 'medium';
            }
            return 'low';
        }

        function renderRisk() {
            const cardsContainer = document.getElementById('risk-cards');
            document.getElementById('risk-total').textContent = allHits.length;

            // Group by competitor and assess risk
            const riskByCompetitor = {};
            allHits.forEach(h => {
                const cid = h.competitor_id;
                if (!riskByCompetitor[cid]) riskByCompetitor[cid] = { high: 0, medium: 0, low: 0, total: 0 };
                const risk = assessRisk(h);
                riskByCompetitor[cid][risk]++;
                riskByCompetitor[cid].total++;
            });

            // Render risk cards
            cardsContainer.innerHTML = '';
            competitors.forEach((c, idx) => {
                const color = COLORS[idx % COLORS.length];
                const risk = riskByCompetitor[c.id] || { high: 0, medium: 0, low: 0, total: 0 };
                const score = risk.total > 0
                    ? Math.round((risk.high * 3 + risk.medium * 2 + risk.low) / risk.total * 33.3)
                    : 0;
                const riskLabel = score > 66 ? 'High' : score > 33 ? 'Med' : 'Low';
                const riskIcon = score > 66 ? 'fa-triangle-exclamation text-red-500' : score > 33 ? 'fa-exclamation-circle text-amber-500' : 'fa-check-circle text-green-500';

                cardsContainer.innerHTML += `
                    <div class="competitor-card ${color.bg} ${color.border} border-2 rounded-xl p-4 text-center">
                        <div class="font-bold ${color.text} text-sm mb-2 truncate" title="${c.name}">${c.shortName || c.name}</div>
                        <div class="text-3xl font-black ${color.text}">${score}%</div>
                        <div class="text-xs text-slate-500 mt-1"><i class="fa-solid ${riskIcon} mr-1"></i>${riskLabel} Risk</div>
                        <div class="grid grid-cols-3 gap-1 mt-3 pt-2 border-t border-slate-200 text-xs">
                            <div>
                                <div class="font-bold text-red-600">${risk.high}</div>
                                <div class="text-slate-400">High</div>
                            </div>
                            <div>
                                <div class="font-bold text-amber-600">${risk.medium}</div>
                                <div class="text-slate-400">Med</div>
                            </div>
                            <div>
                                <div class="font-bold text-green-600">${risk.low}</div>
                                <div class="text-slate-400">Low</div>
                            </div>
                        </div>
                    </div>
                `;
            });

            // Risk chart (stacked)
            if (charts['risk']) charts['risk'].destroy();
            const ctx = document.getElementById('chart-risk').getContext('2d');
            charts['risk'] = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: competitors.map(c => c.shortName || c.name),
                    datasets: [
                        {
                            label: 'High Risk',
                            data: competitors.map(c => (riskByCompetitor[c.id] || {}).high || 0),
                            backgroundColor: '#EF4444'
                        },
                        {
                            label: 'Medium Risk',
                            data: competitors.map(c => (riskByCompetitor[c.id] || {}).medium || 0),
                            backgroundColor: '#F59E0B'
                        },
                        {
                            label: 'Low Risk',
                            data: competitors.map(c => (riskByCompetitor[c.id] || {}).low || 0),
                            backgroundColor: '#10B981'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { x: { stacked: true }, y: { stacked: true } },
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }

        function renderOffers() {
            const cardsContainer = document.getElementById('offers-cards');

            // Group by competitor
            const byCompetitor = {};
            allHits.forEach(h => {
                const cid = h.competitor_id;
                if (!byCompetitor[cid]) byCompetitor[cid] = [];
                byCompetitor[cid].push(h);
            });

            // Find promotional hits
            const promoHits = allHits.filter(h => {
                const subject = (h.subject || '').toLowerCase();
                return subject.includes('bonus') || subject.includes('free') ||
                    subject.includes('offer') || subject.includes('jackpot') ||
                    subject.includes('spin') || subject.includes('deposit');
            });

            const promoByCompetitor = {};
            promoHits.forEach(h => {
                const cid = h.competitor_id;
                if (!promoByCompetitor[cid]) promoByCompetitor[cid] = [];
                promoByCompetitor[cid].push(h);
            });

            // Render offer cards
            cardsContainer.innerHTML = '';
            competitors.forEach((c, idx) => {
                const color = COLORS[idx % COLORS.length];
                const totalHits = (byCompetitor[c.id] || []).length;
                const promos = (promoByCompetitor[c.id] || []).length;
                const promoRate = totalHits > 0 ? Math.round(promos / totalHits * 100) : 0;

                cardsContainer.innerHTML += `
                    <div class="competitor-card ${color.bg} ${color.border} border-2 rounded-xl p-4 text-center">
                        <div class="font-bold ${color.text} text-sm mb-2 truncate" title="${c.name}">${c.shortName || c.name}</div>
                        <div class="text-3xl font-black ${color.text}">${promos}</div>
                        <div class="text-xs text-slate-500 mt-1">Promos</div>
                        <div class="mt-3 pt-2 border-t border-slate-200">
                            <div class="text-xs text-slate-500">Promo Rate</div>
                            <div class="font-bold ${color.text}">${promoRate}%</div>
                        </div>
                    </div>
                `;
            });

            // Type distribution
            const typeCount = { Bonus: 0, 'Free Spins': 0, Cashback: 0, Jackpot: 0, Other: 0 };
            promoHits.forEach(h => {
                const s = (h.subject || '').toLowerCase();
                if (s.includes('bonus')) typeCount.Bonus++;
                else if (s.includes('spin')) typeCount['Free Spins']++;
                else if (s.includes('cash')) typeCount.Cashback++;
                else if (s.includes('jackpot')) typeCount.Jackpot++;
                else typeCount.Other++;
            });

            if (charts['type']) charts['type'].destroy();
            const typeCtx = document.getElementById('chart-offers-type').getContext('2d');
            charts['type'] = new Chart(typeCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(typeCount),
                    datasets: [{
                        data: Object.values(typeCount),
                        backgroundColor: ['#F59E0B', '#8B5CF6', '#10B981', '#3B82F6', '#94A3B8']
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
            });

            if (charts['competitor']) charts['competitor'].destroy();
            const compCtx = document.getElementById('chart-offers-competitor').getContext('2d');
            charts['competitor'] = new Chart(compCtx, {
                type: 'bar',
                data: {
                    labels: competitors.map(c => c.shortName || c.name),
                    datasets: [{
                        label: 'Promos',
                        data: competitors.map(c => (promoByCompetitor[c.id] || []).length),
                        backgroundColor: competitors.map((_, idx) => COLORS[idx % COLORS.length].accent)
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });

            // Promotion Value Analysis
            renderPromoValues(promoHits);
        }

        function renderPromoValues(promoHits) {
            // Extract numerical values from subject lines
            const valuePatterns = {
                'Bonus %': /\b(\d+)\s*%/g,
                'Free Spins': /\b(\d+)\s*(?:free\s*)?spins?/gi,
                'Cash Amount': /[$€£](\d+)/g,
                'Deposit Match': /\b(\d+)x/gi
            };

            const extractedValues = { 'Bonus %': [], 'Free Spins': [], 'Cash Amount': [], 'Deposit Match': [] };

            promoHits.forEach(h => {
                const text = (h.subject || '');
                for (const [type, regex] of Object.entries(valuePatterns)) {
                    let match;
                    const re = new RegExp(regex.source, regex.flags);
                    while ((match = re.exec(text)) !== null) {
                        extractedValues[type].push(parseInt(match[1]));
                    }
                }
            });

            // Chart - average values by type
            if (charts['promoValues']) charts['promoValues'].destroy();
            const ctx = document.getElementById('chart-promo-values')?.getContext('2d');
            if (ctx) {
                const avgValues = Object.entries(extractedValues).map(([type, values]) => ({
                    type,
                    avg: values.length > 0 ? Math.round(values.reduce((a, b) => a + b, 0) / values.length) : 0,
                    count: values.length,
                    max: values.length > 0 ? Math.max(...values) : 0
                }));

                charts['promoValues'] = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: avgValues.map(v => v.type),
                        datasets: [{
                            label: 'Average Value',
                            data: avgValues.map(v => v.avg),
                            backgroundColor: ['#F59E0B', '#8B5CF6', '#10B981', '#3B82F6'],
                            borderRadius: 4
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } }
                    }
                });
            }

            // Breakdown panel
            const breakdown = document.getElementById('promo-value-breakdown');
            if (breakdown) {
                breakdown.innerHTML = Object.entries(extractedValues).map(([type, values]) => {
                    const count = values.length;
                    const avg = count > 0 ? Math.round(values.reduce((a, b) => a + b, 0) / count) : 0;
                    const max = count > 0 ? Math.max(...values) : 0;
                    const icon = type.includes('%') ? 'fa-percent' : type.includes('Spin') ? 'fa-dice' : type.includes('Cash') ? 'fa-dollar-sign' : 'fa-arrows-rotate';
                    return `
                        <div class="bg-white rounded-lg p-3 border border-slate-200 flex items-center gap-3">
                            <i class="fa-solid ${icon} text-amber-500"></i>
                            <div class="flex-1">
                                <div class="font-medium text-sm">${type}</div>
                                <div class="text-xs text-slate-500">${count} found</div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-amber-600">${avg}</div>
                                <div class="text-xs text-slate-400">avg</div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-indigo-600">${max}</div>
                                <div class="text-xs text-slate-400">max</div>
                            </div>
                        </div>
                    `;
                }).join('');
            }
        }

        function renderBundles() {
            const cardsContainer = document.getElementById('bundles-cards');
            const content = document.getElementById('bundles-content');

            // Find multi-day promotional patterns per competitor
            const bundlesByCompetitor = {};
            competitors.forEach(c => {
                const compHits = allHits.filter(h => h.competitor_id === c.id);
                const byDate = {};
                compHits.forEach(h => {
                    const date = new Date(h.received_at).toDateString();
                    if (!byDate[date]) byDate[date] = [];
                    byDate[date].push(h);
                });
                const multiDays = Object.values(byDate).filter(arr => arr.length > 1);
                bundlesByCompetitor[c.id] = {
                    bundleCount: multiDays.length,
                    totalBundled: multiDays.reduce((a, b) => a + b.length, 0),
                    avgSize: multiDays.length > 0 ? (multiDays.reduce((a, b) => a + b.length, 0) / multiDays.length).toFixed(1) : 0
                };
            });

            // Render bundle cards
            cardsContainer.innerHTML = '';
            competitors.forEach((c, idx) => {
                const color = COLORS[idx % COLORS.length];
                const bundle = bundlesByCompetitor[c.id] || { bundleCount: 0, totalBundled: 0, avgSize: 0 };

                cardsContainer.innerHTML += `
                    <div class="competitor-card ${color.bg} ${color.border} border-2 rounded-xl p-4 text-center">
                        <div class="font-bold ${color.text} text-sm mb-2 truncate" title="${c.name}">${c.shortName || c.name}</div>
                        <div class="text-3xl font-black ${color.text}">${bundle.bundleCount}</div>
                        <div class="text-xs text-slate-500 mt-1">Bundle Days</div>
                        <div class="grid grid-cols-2 gap-2 mt-3 pt-2 border-t border-slate-200">
                            <div>
                                <div class="text-xs text-slate-500">Bundled</div>
                                <div class="font-bold ${color.text}">${bundle.totalBundled}</div>
                            </div>
                            <div>
                                <div class="text-xs text-slate-500">Avg Size</div>
                                <div class="font-bold ${color.text}">${bundle.avgSize}</div>
                            </div>
                        </div>
                    </div>
                `;
            });

            content.innerHTML = `<div class="text-center py-2 text-slate-500">
                <p class="text-xs">Bundle days = days with 2+ promotional emails from the same competitor</p>
            </div>`;

            // Bundle Pairwise Heatmap
            renderBundleHeatmap();
        }

        function renderBundleHeatmap() {
            const offerTypes = ['Bonus', 'Spins', 'Cashback', 'Jackpot', 'Deposit'];
            const coOccurrence = {};
            offerTypes.forEach(a => {
                coOccurrence[a] = {};
                offerTypes.forEach(b => coOccurrence[a][b] = 0);
            });

            // Find days with multiple promos and count co-occurrences
            competitors.forEach(c => {
                const compHits = allHits.filter(h => h.competitor_id === c.id);
                const byDate = {};
                compHits.forEach(h => {
                    const date = new Date(h.received_at).toDateString();
                    if (!byDate[date]) byDate[date] = [];
                    byDate[date].push(h);
                });

                // For each bundle day, detect offer types and count pairs
                Object.values(byDate).filter(arr => arr.length > 1).forEach(dayHits => {
                    const typesFound = new Set();
                    dayHits.forEach(h => {
                        const s = (h.subject || '').toLowerCase();
                        if (s.includes('bonus')) typesFound.add('Bonus');
                        if (s.includes('spin')) typesFound.add('Spins');
                        if (s.includes('cash')) typesFound.add('Cashback');
                        if (s.includes('jackpot')) typesFound.add('Jackpot');
                        if (s.includes('deposit')) typesFound.add('Deposit');
                    });
                    const types = Array.from(typesFound);
                    types.forEach(a => {
                        types.forEach(b => {
                            if (a <= b) coOccurrence[a][b]++;
                        });
                    });
                });
            });

            // Find max for color scaling
            let maxCount = 1;
            offerTypes.forEach(a => offerTypes.forEach(b => {
                if (coOccurrence[a][b] > maxCount) maxCount = coOccurrence[a][b];
            }));

            // Render heatmap grid
            const grid = document.getElementById('bundle-heatmap-grid');
            if (grid) {
                let html = '<table class="w-full text-sm"><thead><tr><th class="p-2"></th>';
                offerTypes.forEach(t => html += `<th class="p-2 text-xs font-medium text-slate-600">${t}</th>`);
                html += '</tr></thead><tbody>';

                offerTypes.forEach(a => {
                    html += `<tr><td class="p-2 text-xs font-medium text-slate-600">${a}</td>`;
                    offerTypes.forEach(b => {
                        const count = a <= b ? coOccurrence[a][b] : coOccurrence[b][a];
                        const intensity = count / maxCount;
                        const bgColor = count === 0 ? 'bg-slate-100' :
                            intensity > 0.7 ? 'bg-purple-500 text-white' :
                                intensity > 0.4 ? 'bg-purple-300' : 'bg-purple-100';
                        html += `<td class="p-2 text-center ${bgColor} rounded">${count || '-'}</td>`;
                    });
                    html += '</tr>';
                });
                html += '</tbody></table>';
                grid.innerHTML = html;
            }
        }

        async function initDashboard() {
            if (!window.CRMT?.dataLoader || !window.getActiveCompetitorsForReport) {
                setTimeout(initDashboard, 200);
                return;
            }

            competitors = window.getActiveCompetitorsForReport?.() || [];
            console.log('[2.2] Active competitors:', competitors.length);

            await fetchData();

            renderRisk();
            renderOffers();
            renderBundles();
            checkHash();

            // Hide loading overlay
            document.getElementById('loading-overlay')?.classList.add('hidden');

            window.addEventListener('dataLoaderChange', async () => {
                competitors = window.getActiveCompetitorsForReport?.() || [];
                await fetchData();
                renderRisk();
                renderOffers();
                renderBundles();
            });
        }

        if (window.CRMT) initDashboard();
        else {
            window.addEventListener('crmtReady', initDashboard, { once: true });
            setTimeout(initDashboard, 1000);
        }
    </script>
@endpush
