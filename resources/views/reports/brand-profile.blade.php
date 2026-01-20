@extends('layouts.dashboard')


@section('title', 'CRMTracker - Brand Profile')


@section('content')
<div class="flex-1 ml-20">
<div id="top-header"></div>
<div id="sub-header"></div>
<main class="p-6 max-w-7xl mx-auto">
<!-- Page Header -->
<div class="flex items-center justify-between mb-6">
<div>
<h1 class="text-2xl font-bold text-slate-800">
<i class="fa-solid fa-building text-indigo-600 mr-2"></i>
Brand Profile Wizard
</h1>
<p class="text-sm text-slate-500">Create comprehensive competitor intelligence by brand</p>
</div>
<div id="wizard-actions" class="hidden">
<button onclick="resetWizard()"
class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium">
<i class="fa-solid fa-arrow-rotate-left mr-1"></i> Start Over
</button>
</div>
</div>
<!-- Wizard Progress -->
<div id="wizard-progress" class="bg-white rounded-xl shadow-sm p-6 mb-6">
<div class="flex items-center justify-between">
<div class="wizard-step flex-1 border-2 rounded-lg p-4 mx-2 active" id="step-1-indicator">
<div class="flex items-center gap-3">
<div
class="step-number w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">
1</div>
<div>
<p class="font-semibold text-slate-800">Select Brand</p>
<p class="text-xs text-slate-500">Choose a brand to analyze</p>
</div>
</div>
</div>
<div class="wizard-step flex-1 border-2 border-slate-200 rounded-lg p-4 mx-2"
id="step-2-indicator">
<div class="flex items-center gap-3">
<div
class="step-number w-8 h-8 rounded-full bg-slate-300 text-white flex items-center justify-center font-bold">
2</div>
<div>
<p class="font-semibold text-slate-500">Select Reports</p>
<p class="text-xs text-slate-400">Choose reports to include</p>
</div>
</div>
</div>
<div class="wizard-step flex-1 border-2 border-slate-200 rounded-lg p-4 mx-2"
id="step-3-indicator">
<div class="flex items-center gap-3">
<div
class="step-number w-8 h-8 rounded-full bg-slate-300 text-white flex items-center justify-center font-bold">
3</div>
<div>
<p class="font-semibold text-slate-500">Options</p>
<p class="text-xs text-slate-400">Configure & generate</p>
</div>
</div>
</div>
</div>
</div>
<!-- Step 1: Brand Selection -->
<div id="step-1" class="bg-white rounded-xl shadow-sm p-6 mb-6">
<h2 class="text-lg font-bold text-slate-800 mb-4">
<i class="fa-solid fa-building text-indigo-600 mr-2"></i>
Select Brand
</h2>
<div class="space-y-2" id="brands-container">
<div class="text-center py-8 text-slate-400">
<i class="fa-solid fa-spinner fa-spin text-3xl mb-2"></i>
<p>Loading brands...</p>
</div>
</div>
</div>
<!-- Step 2: Report Selection (Hidden initially) -->
<div id="step-2" class="bg-white rounded-xl shadow-sm p-6 mb-6 hidden">
<h2 class="text-lg font-bold text-slate-800 mb-4">
<i class="fa-solid fa-file-lines text-indigo-600 mr-2"></i>
Select Reports
</h2>
<div class="mb-4 flex gap-3">
<button onclick="selectAllReports()"
class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition-colors">
<i class="fa-solid fa-check-double mr-1"></i>Select All
</button>
<button onclick="selectNoneReports()"
class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition-colors">
<i class="fa-solid fa-xmark mr-1"></i>Select None
</button>
</div>
<div id="reports-container" class="space-y-4"></div>
<div class="mt-6 flex justify-between">
<button onclick="goToStep(1)"
class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-6 py-2 rounded-lg font-medium">
<i class="fa-solid fa-arrow-left mr-1"></i> Back
</button>
<button onclick="goToStep(3)"
class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium">
Next <i class="fa-solid fa-arrow-right ml-1"></i>
</button>
</div>
</div>
<!-- Step 3: Options (Hidden initially) -->
<div id="step-3" class="bg-white rounded-xl shadow-sm p-6 mb-6 hidden">
<h2 class="text-lg font-bold text-slate-800 mb-4">
<i class="fa-solid fa-sliders text-indigo-600 mr-2"></i>
Profile Options
</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<!-- Date Range -->
<div>
<label class="block text-sm font-medium text-slate-700 mb-2">Date Range</label>
<p class="text-xs text-slate-500 mb-3">Uses the global date range from the header</p>
<div id="date-range-display" class="bg-slate-50 p-3 rounded-lg text-sm text-slate-600">
Loading date range...
</div>
</div>
<!-- Comparison Mode -->
<div>
<label class="block text-sm font-medium text-slate-700 mb-2">Comparison Mode</label>
<div class="space-y-2">
<label class="flex items-center gap-2 p-2 rounded hover:bg-slate-50 cursor-pointer">
<input type="radio" name="comparison-mode" value="by-market" checked
class="text-indigo-600">
<span class="text-sm">By Market (columns per market)</span>
</label>
<label class="flex items-center gap-2 p-2 rounded hover:bg-slate-50 cursor-pointer">
<input type="radio" name="comparison-mode" value="vs-average"
class="text-indigo-600">
<span class="text-sm">Vs. Market Average</span>
</label>
<label class="flex items-center gap-2 p-2 rounded hover:bg-slate-50 cursor-pointer">
<input type="radio" name="comparison-mode" value="vs-benchmark"
class="text-indigo-600">
<span class="text-sm">Vs. Benchmark Competitor</span>
</label>
</div>
</div>
</div>
<!-- Benchmark Selector (conditional) -->
<div id="benchmark-selector" class="mt-4 hidden">
<label class="block text-sm font-medium text-slate-700 mb-2">Benchmark Competitor</label>
<select id="benchmark-competitor"
class="w-full md:w-1/2 border border-slate-300 rounded-lg px-3 py-2">
<option value="">Select competitor...</option>
</select>
</div>
<div class="mt-6 flex justify-between items-center">
<button onclick="goToStep(2)"
class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-6 py-2 rounded-lg font-medium">
<i class="fa-solid fa-arrow-left mr-1"></i> Back
</button>
<button onclick="generateProfile()"
class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-bold text-lg">
<i class="fa-solid fa-wand-magic-sparkles mr-2"></i> Generate Profile
</button>
</div>
</div>
<!-- Profile Output (Hidden initially) -->
<div id="profile-output" class="hidden">
<!-- Profile Header -->
<div id="profile-header"
class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl p-6 mb-6 text-white sticky top-0 z-10">
<div class="flex items-center justify-between">
<div class="flex items-center gap-4">
<div
class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center text-2xl font-bold">
<span id="profile-logo">?</span>
</div>
<div>
<h1 id="profile-brand-name" class="text-2xl font-bold">Brand Name</h1>
<p id="profile-meta" class="text-indigo-100 text-sm">Markets: - | Hits: - | Date: -
</p>
</div>
</div>
<div class="flex gap-2">
<button onclick="resetWizard()"
class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg text-sm">
<i class="fa-solid fa-pen mr-1"></i> Edit
</button>
<button onclick="exportPDF()"
class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg text-sm" disabled
title="Coming soon">
<i class="fa-solid fa-file-pdf mr-1"></i> PDF
</button>
</div>
</div>
</div>
<!-- Report Sections -->
<div id="profile-sections" class="space-y-4">
<!-- Sections will be rendered here -->
</div>
</div>
</main>
<script>
// ========================================
// STATE
// ========================================
let brands = [];
let selectedBrand = null;
let selectedReports = [];
let comparisonMode = 'by-market';
let brandData = null;
let benchmarkBrand = null;
let benchmarkData = null;
// Available reports registry - matches current NAV_CONFIG in navigation.js
const REPORTS = [
// Marketing (M.1)
{ id: '1.1', name: 'Performance Dashboard', module: 'Marketing', icon: 'fa-chart-line', file: '1.1.html' },
{ id: '1.2', name: 'Journey Mapping', module: 'Marketing', icon: 'fa-route', file: '1.2.html' },
{ id: '1.3', name: 'Customer Lifecycle', module: 'Marketing', icon: 'fa-heart-pulse', file: '1.3.html' },
{ id: '1.4', name: 'Product Matrix', module: 'Marketing', icon: 'fa-th', file: '1.4.html' },
{ id: '1.5', name: 'Timing Intelligence', module: 'Marketing', icon: 'fa-clock', file: '1.5.html' },
// Content (M.2)
{ id: '2.1', name: 'Content Quality Hub', module: 'Content', icon: 'fa-file-lines', file: '2.1.html' },
{ id: '2.2', name: 'Offers & Risk', module: 'Content', icon: 'fa-tags', file: '2.2.html' },
// Compliance (M.3)
{ id: '3.1', name: 'Compliance Scorecard', module: 'Compliance', icon: 'fa-shield-halved', file: '3.1.html' },
{ id: '3.2', name: 'Compliance Alignment', module: 'Compliance', icon: 'fa-lock', file: '3.2.html' },
{ id: '3.3', name: 'Audit Preparedness', module: 'Compliance', icon: 'fa-clipboard-check', file: '3.3.html' },
// Risk/Regulatory (M.4)
{ id: '4.1', name: 'License & Entity Verification', module: 'Risk', icon: 'fa-id-card', file: '4.1.html' },
{ id: '4.2', name: 'Compliance Cost Analysis', module: 'Risk', icon: 'fa-calculator', file: '4.2.html' },
{ id: '4.3', name: 'Transparency Audit', module: 'Risk', icon: 'fa-eye', file: '4.3.html' },
// Product (M.5)
{ id: '5.1', name: 'Valuation Uplift', module: 'Product', icon: 'fa-chart-line', file: '5.1.html' },
{ id: '5.2', name: 'Compliance Exposure', module: 'Product', icon: 'fa-shield-halved', file: '5.2.html' },
{ id: '5.3', name: 'Migration Readiness', module: 'Product', icon: 'fa-arrows-rotate', file: '5.3.html' },
];
// Market flag emoji mapping
function getMarketFlag(marketId) {
const flags = {
'CA-ON': '🇨🇦', 'CA-BC': '🇨🇦', 'CA-AB': '🇨🇦', 'CA-QC': '🇨🇦',
'UK-GB': '🇬🇧', 'UK-ALL': '🇬🇧',
'US-NJ': '🇺🇸', 'US-PA': '🇺🇸', 'US-MI': '🇺🇸', 'US-WV': '🇺🇸', 'US-ALL': '🇺🇸',
'TR-ALL': '🇹🇷',
'CH-ALL': '🇨🇭',
'AR-BA': '🇦🇷', 'AR-ALL': '🇦🇷',
'MT-ALL': '🇲🇹',
'Global': '🌍'
};
const country = marketId?.split('-')[0];
return flags[marketId] || flags[country + '-ALL'] || '🏳️';
}
// ========================================
// INITIALIZATION
// ========================================
document.addEventListener('DOMContentLoaded', async () => {
await loadBrands();
setupComparisonModeListener();
updateDateRangeDisplay();
});
async function loadBrands() {
try {
const response = await fetch('/.netlify/functions/brands');
const data = await response.json();
brands = data.data || [];
renderBrands();
} catch (error) {
console.error('Error loading brands:', error);
document.getElementById('brands-container').innerHTML = `
<div class="col-span-3 text-center py-8 text-red-500">
<i class="fa-solid fa-exclamation-circle text-3xl mb-2"></i>
<p>Failed to load brands</p>
</div>
`;
}
}
function renderBrands() {
const container = document.getElementById('brands-container');
if (brands.length === 0) {
container.innerHTML = `
<div class="col-span-3 text-center py-8 text-slate-400">
<i class="fa-solid fa-building-circle-xmark text-3xl mb-2"></i>
<p>No brands found. Import data via D.10 Import Queue first.</p>
</div>
`;
return;
}
// Group brands: single market -> by market name, multi-market -> Global
const groups = {};
brands.forEach(b => {
const marketCount = parseInt(b.market_count) || 0;
let groupKey;
if (marketCount === 1 && b.markets && b.markets.length > 0) {
groupKey = b.markets[0]; // Use the single market as group
} else if (marketCount > 1) {
groupKey = 'Global';
} else {
groupKey = 'Unassigned';
}
if (!groups[groupKey]) groups[groupKey] = [];
groups[groupKey].push(b);
});
// Sort groups: Global first, then alphabetically
const sortedKeys = Object.keys(groups).sort((a, b) => {
if (a === 'Global') return -1;
if (b === 'Global') return 1;
if (a === 'Unassigned') return 1;
if (b === 'Unassigned') return -1;
return a.localeCompare(b);
});
// Track collapsed state (initialize if not exists)
if (!window.brandGroupCollapsed) window.brandGroupCollapsed = {};
container.innerHTML = sortedKeys.map(groupKey => {
const groupBrands = groups[groupKey];
const isCollapsed = window.brandGroupCollapsed[groupKey] || false;
const icon = groupKey === 'Global' ? 'fa-globe' : groupKey === 'Unassigned' ? 'fa-question-circle' : 'fa-map-marker-alt';
const color = groupKey === 'Global' ? 'from-purple-500 to-indigo-500' : groupKey === 'Unassigned' ? 'from-slate-400 to-slate-500' : 'from-blue-500 to-cyan-500';
return `
<div class="mb-4">
<button onclick="toggleBrandGroup('${groupKey}')" 
class="w-full flex items-center justify-between bg-gradient-to-r ${color} text-white px-4 py-2.5 rounded-t-lg font-semibold shadow-sm hover:shadow-md transition-all">
<div class="flex items-center gap-3">
<i class="fa-solid ${icon}"></i>
<span>${groupKey}</span>
<span class="bg-white/20 text-white text-xs px-2 py-0.5 rounded-full">${groupBrands.length} brand${groupBrands.length !== 1 ? 's' : ''}</span>
</div>
<i class="fa-solid fa-chevron-${isCollapsed ? 'down' : 'up'} transition-transform"></i>
</button>
<div id="brand-group-${groupKey.replace(/[^a-zA-Z0-9]/g, '-')}" class="${isCollapsed ? 'hidden' : ''} border border-t-0 border-slate-200 rounded-b-lg overflow-hidden">
<table class="w-full text-sm">
<thead class="bg-slate-50 text-xs text-slate-500 uppercase">
<tr>
<th class="py-2 px-4 text-left font-semibold w-8"></th>
<th class="py-2 px-4 text-left font-semibold">Brand</th>
<th class="py-2 px-4 text-left font-semibold">Domain</th>
<th class="py-2 px-3 text-left font-semibold">Markets</th>
<th class="py-2 px-3 text-center font-semibold">Ent</th>
<th class="py-2 px-3 text-center font-semibold">Hits</th>
</tr>
</thead>
<tbody>
${groupBrands.sort((a, b) => (parseInt(b.total_hits) || 0) - (parseInt(a.total_hits) || 0)).map(b => {
const markets = b.markets || [];
const shown = markets.slice(0, 3);
const remaining = markets.slice(3);
const flagHtml = shown.map(m => getMarketFlag(m)).join(' ');
const moreHtml = remaining.length > 0
? `<span class="bg-slate-200 text-slate-600 px-1.5 py-0.5 rounded text-xs cursor-default" title="${remaining.join('\\n')}">+${remaining.length}</span>`
: '';
return `
<tr onclick="selectBrand('${b.id}')" 
class="border-b border-slate-100 hover:bg-indigo-50 cursor-pointer transition-colors ${selectedBrand === b.id ? 'bg-indigo-100' : ''}">
<td class="py-2.5 px-4">
<div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-500 rounded flex items-center justify-center text-white font-bold text-sm">
${b.name.charAt(0).toUpperCase()}
</div>
</td>
<td class="py-2.5 px-4 font-medium text-slate-800">${b.name}</td>
<td class="py-2.5 px-4 text-slate-500 text-xs">${b.website || '-'}</td>
<td class="py-2.5 px-3 text-sm">${flagHtml} ${moreHtml}</td>
<td class="py-2.5 px-3 text-center">
<span class="bg-purple-100 text-purple-700 px-2 py-0.5 rounded text-xs font-medium">${b.competitor_count || 0}</span>
</td>
<td class="py-2.5 px-3 text-center">
<span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-medium">${(parseInt(b.total_hits) || 0).toLocaleString()}</span>
</td>
</tr>`;
}).join('')}
</tbody>
</table>
</div>
</div>
`;
}).join('');
}
function toggleBrandGroup(groupKey) {
if (!window.brandGroupCollapsed) window.brandGroupCollapsed = {};
window.brandGroupCollapsed[groupKey] = !window.brandGroupCollapsed[groupKey];
renderBrands();
}
function selectBrand(brandId) {
selectedBrand = brandId;
renderBrands();
setTimeout(() => goToStep(2), 300);
}
// ========================================
// STEP NAVIGATION
// ========================================
function goToStep(step) {
// Hide all steps
document.getElementById('step-1').classList.add('hidden');
document.getElementById('step-2').classList.add('hidden');
document.getElementById('step-3').classList.add('hidden');
document.getElementById('profile-output').classList.add('hidden');
document.getElementById('wizard-progress').classList.remove('hidden');
document.getElementById('wizard-actions').classList.add('hidden');
// Update indicators - reset all to inactive state
for (let i = 1; i <= 3; i++) {
const indicator = document.getElementById(`step-${i}-indicator`);
indicator.classList.remove('active', 'completed');
const stepNum = indicator.querySelector('.step-number');
stepNum.classList.remove('bg-indigo-600');
stepNum.classList.add('bg-slate-300');
}
// Mark completed steps (before current)
for (let i = 1; i < step; i++) {
const indicator = document.getElementById(`step-${i}-indicator`);
indicator.classList.add('completed');
// CSS handles the completed styling - no need to change step-number bg
}
// Mark current step as active
const currentIndicator = document.getElementById(`step-${step}-indicator`);
currentIndicator.classList.add('active');
const currentNum = currentIndicator.querySelector('.step-number');
currentNum.classList.remove('bg-slate-300');
currentNum.classList.add('bg-indigo-600');
document.getElementById(`step-${step}`).classList.remove('hidden');
// Step-specific logic
if (step === 2) {
renderReportSelector();
} else if (step === 3) {
updateDateRangeDisplay();
}
}
function resetWizard() {
selectedBrand = null;
selectedReports = [];
brandData = null;
renderBrands();
goToStep(1);
}
// ========================================
// REPORT SELECTION
// ========================================
function renderReportSelector() {
const container = document.getElementById('reports-container');
// Group by module
const modules = {};
REPORTS.forEach(r => {
if (!modules[r.module]) modules[r.module] = [];
modules[r.module].push(r);
});
container.innerHTML = Object.entries(modules).map(([module, reports]) => `
<div class="border border-slate-200 rounded-lg p-4">
<div class="flex items-center justify-between mb-3">
<h3 class="font-semibold text-slate-700">${module}</h3>
<button onclick="toggleModule('${module}')" class="text-xs text-indigo-600 hover:underline">Toggle All</button>
</div>
<div class="grid grid-cols-2 md:grid-cols-4 gap-2">
${reports.map(r => `
<label class="flex items-center gap-2 p-2 rounded hover:bg-slate-50 cursor-pointer">
<input type="checkbox" value="${r.id}" class="report-checkbox text-indigo-600" 
${selectedReports.includes(r.id) ? 'checked' : ''}
onchange="toggleReport('${r.id}')">
<span class="text-sm">
<i class="fa-solid ${r.icon} text-slate-400 mr-1"></i>
${r.id} ${r.name}
</span>
</label>
`).join('')}
</div>
</div>
`).join('');
}
function toggleReport(reportId) {
if (selectedReports.includes(reportId)) {
selectedReports = selectedReports.filter(r => r !== reportId);
} else {
selectedReports.push(reportId);
}
}
function toggleModule(module) {
const moduleReports = REPORTS.filter(r => r.module === module).map(r => r.id);
const allSelected = moduleReports.every(id => selectedReports.includes(id));
if (allSelected) {
selectedReports = selectedReports.filter(r => !moduleReports.includes(r));
} else {
moduleReports.forEach(id => {
if (!selectedReports.includes(id)) selectedReports.push(id);
});
}
renderReportSelector();
}
function selectAllReports() {
selectedReports = REPORTS.map(r => r.id);
renderReportSelector();
}
function selectNoneReports() {
selectedReports = [];
renderReportSelector();
}
// ========================================
// OPTIONS
// ========================================
function setupComparisonModeListener() {
document.querySelectorAll('input[name="comparison-mode"]').forEach(radio => {
radio.addEventListener('change', (e) => {
comparisonMode = e.target.value;
const benchmarkSelector = document.getElementById('benchmark-selector');
benchmarkSelector.classList.toggle('hidden', comparisonMode !== 'vs-benchmark');
// Populate benchmark dropdown when vs-benchmark is selected
if (comparisonMode === 'vs-benchmark') {
populateBenchmarkDropdown();
}
});
});
// Listen for benchmark selection
document.getElementById('benchmark-competitor').addEventListener('change', (e) => {
benchmarkBrand = e.target.value || null;
});
}
function populateBenchmarkDropdown() {
const dropdown = document.getElementById('benchmark-competitor');
const availableBrands = brands.filter(b => b.id !== selectedBrand);
dropdown.innerHTML = `
<option value="">Select a benchmark brand...</option>
${availableBrands.map(b => `
<option value="${b.id}">${b.name} (${b.market_count} markets, ${parseInt(b.total_hits || 0).toLocaleString()} hits)</option>
`).join('')}
`;
benchmarkBrand = null;
}
function updateDateRangeDisplay() {
const display = document.getElementById('date-range-display');
if (typeof CRMT !== 'undefined' && CRMT.dateRange && CRMT.dateRange.getRange) {
const range = CRMT.dateRange.getRange();
if (range && range.from && range.to) {
display.innerHTML = `<i class="fa-solid fa-calendar mr-2 text-indigo-500"></i> ${range.from} to ${range.to}`;
} else {
display.innerHTML = '<i class="fa-solid fa-infinity mr-2 text-indigo-500"></i> All time';
}
} else {
display.innerHTML = '<i class="fa-solid fa-infinity mr-2 text-indigo-500"></i> All time (date picker not loaded)';
}
}
// ========================================
// GENERATE PROFILE
// ========================================
async function generateProfile() {
if (!selectedBrand) {
alert('Please select a brand first');
return;
}
if (selectedReports.length === 0) {
alert('Please select at least one report');
return;
}
// Validate benchmark selection if in vs-benchmark mode
if (comparisonMode === 'vs-benchmark' && !benchmarkBrand) {
alert('Please select a benchmark brand to compare against');
return;
}
// Show loading
document.getElementById('step-3').innerHTML = `
<div class="text-center py-12">
<i class="fa-solid fa-spinner fa-spin text-4xl text-indigo-600 mb-4"></i>
<p class="text-lg font-medium text-slate-700">Generating Brand Profile...</p>
<p class="text-sm text-slate-500">Aggregating data across ${brands.find(b => b.id === selectedBrand)?.market_count || 0} markets</p>
</div>
`;
try {
// Get date range for filtering
let dateParams = '';
if (typeof CRMT !== 'undefined' && CRMT.dateRange && CRMT.dateRange.getRange) {
const range = CRMT.dateRange.getRange();
if (range && range.from && range.to) {
dateParams = `&from=${range.from}&to=${range.to}`;
}
}
// Fetch brand data with stats for comparison modes
const needStats = comparisonMode === 'vs-average' || comparisonMode === 'vs-benchmark';
const response = await fetch(`/.netlify/functions/brands/${selectedBrand}?include_stats=${needStats}${dateParams}`);
brandData = await response.json();
// Fetch benchmark brand data if in vs-benchmark mode
if (comparisonMode === 'vs-benchmark' && benchmarkBrand) {
const benchmarkResponse = await fetch(`/.netlify/functions/brands/${benchmarkBrand}?include_stats=true${dateParams}`);
benchmarkData = await benchmarkResponse.json();
} else {
benchmarkData = null;
}
// Fetch lifecycle stats for Report 1.2 (if report selected)
if (selectedReports.includes('1.2')) {
try {
const lifecycleUrl = `/.netlify/functions/trackings?group_by=lifecycle_stage&brand_id=${selectedBrand}${dateParams.replace('&', '&start_date=').replace('&to=', '&end_date=')}`;
const lifecycleResponse = await fetch(lifecycleUrl);
const lifecycleData = await lifecycleResponse.json();
brandData.lifecycleStats = {};
(lifecycleData.data || []).forEach(row => {
brandData.lifecycleStats[row.lifecycle_stage] = row.count;
});
} catch (e) {
console.warn('Lifecycle data not available:', e);
brandData.lifecycleStats = null;
}
}
// Fetch lifecycle stats for reports 1.5, 1.6, 1.7 (Acquisition, Retention, Reactivation)
if (selectedReports.some(r => ['1.5', '1.6', '1.7'].includes(r))) {
try {
const url = `/.netlify/functions/hits-analytics?type=lifecycle.stats&brand_id=${selectedBrand}${dateParams}`;
const res = await fetch(url);
const json = await res.json();
brandData.lifecycleData = json.data;
} catch (e) {
console.warn('Lifecycle stats not available:', e);
brandData.lifecycleData = null;
}
}
// Fetch content analytics for content-related reports (2.1, 2.3)
if (selectedReports.some(r => ['2.1', '2.3'].includes(r))) {
try {
const url = `/.netlify/functions/hits-analytics?type=content.stats&brand_id=${selectedBrand}${dateParams}`;
const res = await fetch(url);
const json = await res.json();
brandData.contentStats = json.data;
} catch (e) {
console.warn('Content stats not available:', e);
brandData.contentStats = null;
}
}
// Fetch offer analytics for offer-related reports (2.2)
if (selectedReports.includes('2.2')) {
try {
const url = `/.netlify/functions/hits-analytics?type=offer.stats&brand_id=${selectedBrand}${dateParams}`;
const res = await fetch(url);
const json = await res.json();
brandData.offerStats = json.data;
} catch (e) {
console.warn('Offer stats not available:', e);
brandData.offerStats = null;
}
}
// Fetch compliance analytics for compliance-related reports (3.1, 3.2, 3.3)
if (selectedReports.some(r => ['3.1', '3.2', '3.3'].includes(r))) {
try {
const url = `/.netlify/functions/hits-analytics?type=compliance.stats&brand_id=${selectedBrand}${dateParams}`;
const res = await fetch(url);
const json = await res.json();
brandData.complianceStats = json.data;
} catch (e) {
console.warn('Compliance stats not available:', e);
brandData.complianceStats = null;
}
}
// Fetch product analytics for product-related reports (5.1, 5.2, 5.3)
if (selectedReports.some(r => ['5.1', '5.2', '5.3'].includes(r))) {
try {
const url = `/.netlify/functions/hits-analytics?type=product.stats&brand_id=${selectedBrand}`;
const res = await fetch(url);
const json = await res.json();
brandData.productStats = json.data;
} catch (e) {
console.warn('Product stats not available:', e);
brandData.productStats = null;
}
}
// Fetch license stats for risk/license reports (4.1, 4.3)
if (selectedReports.some(r => ['4.1', '4.3'].includes(r))) {
try {
const url = `/.netlify/functions/licenses?type=licenses.stats&brand_id=${selectedBrand}`;
const res = await fetch(url);
const json = await res.json();
brandData.licenseStats = json.data;
} catch (e) {
console.warn('License stats not available:', e);
brandData.licenseStats = null;
}
}
// Hide wizard, show profile
document.getElementById('wizard-progress').classList.add('hidden');
document.getElementById('step-3').classList.add('hidden');
document.getElementById('profile-output').classList.remove('hidden');
document.getElementById('wizard-actions').classList.remove('hidden');
// Render profile header with comparison mode indicator
const brand = brandData.brand;
let modeLabel = '';
if (comparisonMode === 'by-market') modeLabel = 'By Market';
else if (comparisonMode === 'vs-average') modeLabel = 'Vs. Market Average';
else if (comparisonMode === 'vs-benchmark') modeLabel = `Vs. ${benchmarkData?.brand?.name || 'Benchmark'}`;
document.getElementById('profile-logo').textContent = brand.name.charAt(0).toUpperCase();
document.getElementById('profile-brand-name').textContent = brand.name + ' Profile';
document.getElementById('profile-meta').textContent =
`Markets: ${brandData.markets.join(', ')} | Total Hits: ${brandData.totals.hits.toLocaleString()} | Mode: ${modeLabel}`;
// Render sections
renderProfileSections();
} catch (error) {
console.error('Error generating profile:', error);
alert('Failed to generate profile: ' + error.message);
goToStep(3);
}
}
function renderProfileSections() {
const container = document.getElementById('profile-sections');
container.innerHTML = selectedReports.map(reportId => {
const report = REPORTS.find(r => r.id === reportId);
if (!report) return '';
return `
<div class="report-section bg-white rounded-xl shadow-sm overflow-hidden" id="section-${reportId}">
<div class="p-4 bg-slate-50 border-b flex items-center justify-between cursor-pointer" 
onclick="toggleSection('${reportId}')">
<div class="flex items-center gap-3">
<i class="fa-solid ${report.icon} text-indigo-600"></i>
<span class="font-semibold text-slate-800">${reportId} ${report.name}</span>
<span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded">${report.module}</span>
</div>
<i class="fa-solid fa-chevron-down text-slate-400 section-toggle"></i>
</div>
<div class="section-content p-6">
${renderReportContent(reportId)}
</div>
</div>
`;
}).join('');
}
function renderReportContent(reportId) {
const markets = brandData.markets || [];
const competitors = brandData.competitors || [];
const marketComparison = brandData.marketComparison || {};
if (markets.length === 0) {
return `<p class="text-slate-500 text-center py-4">No market data available for this brand</p>`;
}
// ===== REPORT-SPECIFIC RENDERERS =====
// Only render reports that exist in the current navigation
switch (reportId) {
case '1.1':
return render1_1(competitors, markets);
case '1.2':
return render1_2(competitors, markets);
case '1.3':
return render1_3(competitors, markets);
case '1.4':
return render1_4(competitors, markets);
case '1.5':
return render1_5(competitors, markets);
case '2.1':
return render2_1(competitors, markets);
case '2.2':
return render2_2(competitors, markets);
case '3.1':
return render3_1(competitors, markets);
case '3.2':
return render3_2(competitors, markets);
case '3.3':
return render3_3(competitors, markets);
case '4.1':
return render4_1(competitors, markets);
case '4.2':
return render4_2(competitors, markets);
case '4.3':
return render4_3(competitors, markets);
case '5.1':
return render5_1(competitors, markets);
case '5.2':
return render5_2(competitors, markets);
case '5.3':
return render5_3(competitors, markets);
}
// For other reports, use comparison mode-based generic rendering
// Build market data for generic comparison tables
const marketHits = {};
competitors.forEach(c => {
marketHits[c.market_id] = parseInt(c.total_hits) || 0;
});
const headerCols = markets.map(m => `<th class="px-4 py-2 text-center">${m}</th>`).join('');
// Helper for delta formatting
const formatDelta = (delta) => {
if (delta === null || delta === undefined) return '-';
const sign = delta >= 0 ? '+' : '';
const colorClass = delta >= 0 ? 'text-green-600' : 'text-red-600';
return `<span class="${colorClass} font-semibold">${sign}${delta}%</span>`;
};
// Helper for rank formatting
const formatRank = (rank, total, percentile) => {
if (!rank || !total) return '-';
const badgeColor = percentile >= 75 ? 'bg-green-100 text-green-700'
: percentile >= 50 ? 'bg-yellow-100 text-yellow-700'
: 'bg-red-100 text-red-700';
return `<span class="text-xs ${badgeColor} px-2 py-0.5 rounded">#${rank}/${total}</span> <span class="text-slate-400">(Top ${percentile}%)</span>`;
};
// ===== BY MARKET MODE =====
if (comparisonMode === 'by-market') {
return `
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-100">
<tr>
<th class="px-4 py-2 text-left">Metric</th>
${headerCols}
<th class="px-4 py-2 text-center bg-indigo-50">Total</th>
</tr>
</thead>
<tbody>
<tr class="border-b">
<td class="px-4 py-3 font-medium">Total Hits</td>
${markets.map(m => `<td class="px-4 py-3 text-center">${(marketHits[m] || 0).toLocaleString()}</td>`).join('')}
<td class="px-4 py-3 text-center font-bold bg-indigo-50">${brandData.totals.hits.toLocaleString()}</td>
</tr>
<tr class="border-b bg-slate-50">
<td class="px-4 py-3 font-medium">Competitor Entity</td>
${markets.map(m => {
const comp = competitors.find(c => c.market_id === m);
return `<td class="px-4 py-3 text-center text-xs">${comp?.short_name || comp?.name || '-'}</td>`;
}).join('')}
<td class="px-4 py-3 text-center bg-indigo-50">${competitors.length}</td>
</tr>
</tbody>
</table>
</div>
`;
}
// ===== VS MARKET AVERAGE MODE =====
if (comparisonMode === 'vs-average') {
return `
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-100">
<tr>
<th class="px-4 py-2 text-left">Metric</th>
${headerCols}
<th class="px-4 py-2 text-center bg-indigo-50">Overall</th>
</tr>
</thead>
<tbody>
<tr class="border-b">
<td class="px-4 py-3 font-medium"><i class="fa-solid fa-building text-indigo-500 mr-2"></i>Brand Hits</td>
${markets.map(m => `<td class="px-4 py-3 text-center font-semibold">${(marketComparison[m]?.brandHits || marketHits[m] || 0).toLocaleString()}</td>`).join('')}
<td class="px-4 py-3 text-center font-bold bg-indigo-50">${brandData.totals.hits.toLocaleString()}</td>
</tr>
<tr class="border-b bg-slate-50">
<td class="px-4 py-3 font-medium"><i class="fa-solid fa-chart-simple text-slate-400 mr-2"></i>Market Average</td>
${markets.map(m => `<td class="px-4 py-3 text-center text-slate-600">${(marketComparison[m]?.avgHits || 0).toLocaleString()}</td>`).join('')}
<td class="px-4 py-3 text-center bg-indigo-50">-</td>
</tr>
<tr class="border-b">
<td class="px-4 py-3 font-medium"><i class="fa-solid fa-arrows-up-down text-blue-500 mr-2"></i>Delta vs Avg</td>
${markets.map(m => `<td class="px-4 py-3 text-center">${formatDelta(marketComparison[m]?.delta)}</td>`).join('')}
<td class="px-4 py-3 text-center bg-indigo-50">-</td>
</tr>
<tr class="border-b bg-yellow-50">
<td class="px-4 py-3 font-medium"><i class="fa-solid fa-ranking-star text-yellow-500 mr-2"></i>Market Rank</td>
${markets.map(m => {
const stats = marketComparison[m];
return `<td class="px-4 py-3 text-center">${formatRank(stats?.brandRank, stats?.totalCompetitors, stats?.percentile)}</td>`;
}).join('')}
<td class="px-4 py-3 text-center bg-indigo-50">-</td>
</tr>
</tbody>
</table>
</div>
<p class="text-xs text-slate-400 mt-4 text-center">
<i class="fa-solid fa-info-circle mr-1"></i>
Comparison against all active competitors in each market
</p>
`;
}
// ===== VS BENCHMARK MODE =====
if (comparisonMode === 'vs-benchmark') {
// Build benchmark hits per market
const benchmarkHits = {};
if (benchmarkData && benchmarkData.competitors) {
benchmarkData.competitors.forEach(c => {
benchmarkHits[c.market_id] = c.stats?.hits || 0;
});
}
// Calculate delta per market
const calculateDelta = (brandVal, benchmarkVal) => {
if (!benchmarkVal || benchmarkVal === 0) return null;
return Math.round(((brandVal - benchmarkVal) / benchmarkVal) * 100);
};
const benchmarkName = benchmarkData?.brand?.name || 'Benchmark';
const benchmarkTotalHits = benchmarkData?.totals?.hits || 0;
return `
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-100">
<tr>
<th class="px-4 py-2 text-left">Metric</th>
${headerCols}
<th class="px-4 py-2 text-center bg-indigo-50">Total</th>
</tr>
</thead>
<tbody>
<tr class="border-b">
<td class="px-4 py-3 font-medium"><i class="fa-solid fa-building text-indigo-500 mr-2"></i>${brandData.brand.name}</td>
${markets.map(m => `<td class="px-4 py-3 text-center font-semibold">${(marketHits[m] || 0).toLocaleString()}</td>`).join('')}
<td class="px-4 py-3 text-center font-bold bg-indigo-50">${brandData.totals.hits.toLocaleString()}</td>
</tr>
<tr class="border-b bg-orange-50">
<td class="px-4 py-3 font-medium"><i class="fa-solid fa-crosshairs text-orange-500 mr-2"></i>${benchmarkName}</td>
${markets.map(m => `<td class="px-4 py-3 text-center">${(benchmarkHits[m] || 0).toLocaleString()}</td>`).join('')}
<td class="px-4 py-3 text-center font-bold bg-indigo-50">${benchmarkTotalHits.toLocaleString()}</td>
</tr>
<tr class="border-b">
<td class="px-4 py-3 font-medium"><i class="fa-solid fa-arrows-up-down text-blue-500 mr-2"></i>Delta</td>
${markets.map(m => {
const delta = calculateDelta(marketHits[m] || 0, benchmarkHits[m] || 0);
return `<td class="px-4 py-3 text-center">${formatDelta(delta)}</td>`;
}).join('')}
<td class="px-4 py-3 text-center bg-indigo-50">${formatDelta(calculateDelta(brandData.totals.hits, benchmarkTotalHits))}</td>
</tr>
</tbody>
</table>
</div>
<p class="text-xs text-slate-400 mt-4 text-center">
<i class="fa-solid fa-info-circle mr-1"></i>
Direct comparison: ${brandData.brand.name} vs ${benchmarkName}
</p>
`;
}
// Fallback for other reports not handled above
return renderGenericReport(reportId, competitors, markets);
}
// ========================================
// REPORT-SPECIFIC RENDERERS
// ========================================
// 1.1 Channel Mechanics
function render1_1(competitors, markets) {
const marketData = {};
markets.forEach(m => {
const comp = competitors.find(c => c.market_id === m);
marketData[m] = {
email: parseInt(comp?.email_count) || 0,
sms: parseInt(comp?.sms_count) || 0,
push: parseInt(comp?.push_count) || 0,
call: parseInt(comp?.call_count) || 0,
total: parseInt(comp?.total_hits) || 0
};
});
const totals = { email: 0, sms: 0, push: 0, call: 0, total: 0 };
Object.values(marketData).forEach(d => {
totals.email += d.email;
totals.sms += d.sms;
totals.push += d.push;
totals.call += d.call;
totals.total += d.total;
});
const headerCols = markets.map(m => `<th class="px-3 py-2 text-center text-xs">${m}</th>`).join('');
return `
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-100">
<tr>
<th class="px-4 py-2 text-left">Channel</th>
${headerCols}
<th class="px-4 py-2 text-center bg-indigo-50 font-bold">Total</th>
</tr>
</thead>
<tbody>
<tr class="border-b">
<td class="px-4 py-3 font-medium"><i class="fa-solid fa-envelope text-blue-500 mr-2"></i>Email</td>
${markets.map(m => `<td class="px-3 py-3 text-center"><span class="font-semibold text-blue-600">${marketData[m].email}</span></td>`).join('')}
<td class="px-4 py-3 text-center font-bold bg-indigo-50">${totals.email}</td>
</tr>
<tr class="border-b bg-slate-50">
<td class="px-4 py-3 font-medium"><i class="fa-solid fa-comment-sms text-purple-500 mr-2"></i>SMS</td>
${markets.map(m => `<td class="px-3 py-3 text-center"><span class="font-semibold text-purple-600">${marketData[m].sms}</span></td>`).join('')}
<td class="px-4 py-3 text-center font-bold bg-indigo-50">${totals.sms}</td>
</tr>
<tr class="border-b">
<td class="px-4 py-3 font-medium"><i class="fa-solid fa-bell text-amber-500 mr-2"></i>Push</td>
${markets.map(m => `<td class="px-3 py-3 text-center"><span class="font-semibold text-amber-600">${marketData[m].push}</span></td>`).join('')}
<td class="px-4 py-3 text-center font-bold bg-indigo-50">${totals.push}</td>
</tr>
<tr class="border-b bg-slate-50">
<td class="px-4 py-3 font-medium"><i class="fa-solid fa-phone text-cyan-500 mr-2"></i>Calls</td>
${markets.map(m => `<td class="px-3 py-3 text-center"><span class="font-semibold text-cyan-600">${marketData[m].call}</span></td>`).join('')}
<td class="px-4 py-3 text-center font-bold bg-indigo-50">${totals.call}</td>
</tr>
<tr class="bg-indigo-100 border-t-2 border-indigo-200">
<td class="px-4 py-3 font-bold">Total</td>
${markets.map(m => `<td class="px-3 py-3 text-center font-bold">${marketData[m].total}</td>`).join('')}
<td class="px-4 py-3 text-center font-bold text-lg bg-indigo-200">${totals.total}</td>
</tr>
</tbody>
</table>
</div>
`;
}
// 1.2 Customer Journey
function render1_2(competitors, markets) {
const lifecycleStats = brandData.lifecycleStats || {};
const acquisition = lifecycleStats['acquisition'] || lifecycleStats['Acquisition'] || 0;
const retention = lifecycleStats['retention'] || lifecycleStats['Retention'] || 0;
const reactivation = lifecycleStats['reactivation'] || lifecycleStats['Reactivation'] || 0;
const total = acquisition + retention + reactivation;
const hasData = total > 0;
return `
<div class="overflow-x-auto">
<p class="text-sm text-slate-500 mb-4">Customer journey lifecycle stages for this brand</p>
<table class="w-full text-sm">
<thead class="bg-slate-100">
<tr>
<th class="px-4 py-2 text-left">Stage</th>
<th class="px-4 py-2 text-center">Count</th>
<th class="px-4 py-2 text-center">%</th>
</tr>
</thead>
<tbody>
<tr class="border-b">
<td class="px-4 py-3 font-medium"><i class="fa-solid fa-bullseye text-green-500 mr-2"></i>Acquisition</td>
<td class="px-4 py-3 text-center font-semibold">${acquisition}</td>
<td class="px-4 py-3 text-center">${total > 0 ? Math.round(acquisition / total * 100) : 0}%</td>
</tr>
<tr class="border-b bg-slate-50">
<td class="px-4 py-3 font-medium"><i class="fa-solid fa-heart text-pink-500 mr-2"></i>Retention</td>
<td class="px-4 py-3 text-center font-semibold">${retention}</td>
<td class="px-4 py-3 text-center">${total > 0 ? Math.round(retention / total * 100) : 0}%</td>
</tr>
<tr class="border-b">
<td class="px-4 py-3 font-medium"><i class="fa-solid fa-rotate text-orange-500 mr-2"></i>Reactivation</td>
<td class="px-4 py-3 text-center font-semibold">${reactivation}</td>
<td class="px-4 py-3 text-center">${total > 0 ? Math.round(reactivation / total * 100) : 0}%</td>
</tr>
<tr class="bg-indigo-100 border-t-2 border-indigo-200">
<td class="px-4 py-3 font-bold">Total</td>
<td class="px-4 py-3 text-center font-bold text-lg">${total}</td>
<td class="px-4 py-3 text-center font-bold">100%</td>
</tr>
</tbody>
</table>
</div>
${!hasData ? `
<p class="text-xs text-slate-400 mt-4 text-center">
<i class="fa-solid fa-info-circle mr-1"></i>
No lifecycle stage data available. Configure tracking lifecycles in D.8 Tracking Manager.
</p>
` : ''}
`;
}
// 1.3 Hit Frequency
function render1_3(competitors, markets) {
const marketData = {};
let totalHits = 0;
// Calculate days from actual date range
let DAYS = 365; // default
let periodLabel = 'Year';
if (typeof CRMT !== 'undefined' && CRMT.dateRange && CRMT.dateRange.getRange) {
const range = CRMT.dateRange.getRange();
if (range && range.from && range.to) {
const fromDate = new Date(range.from);
const toDate = new Date(range.to);
DAYS = Math.max(1, Math.round((toDate - fromDate) / (1000 * 60 * 60 * 24)));
periodLabel = `${DAYS} days`;
}
}
const WEEKS = Math.max(1, Math.round(DAYS / 7));
markets.forEach(m => {
const comp = competitors.find(c => c.market_id === m);
const hits = parseInt(comp?.total_hits) || 0;
marketData[m] = { hits, daily: (hits / DAYS).toFixed(2), weekly: (hits / WEEKS).toFixed(1) };
totalHits += hits;
});
const headerCols = markets.map(m => `<th class="px-3 py-2 text-center text-xs">${m}</th>`).join('');
return `
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-100">
<tr>
<th class="px-4 py-2 text-left">Frequency</th>
${headerCols}
<th class="px-4 py-2 text-center bg-indigo-50">Avg</th>
</tr>
</thead>
<tbody>
<tr class="border-b">
<td class="px-4 py-3 font-medium"><i class="fa-solid fa-calendar-day text-blue-500 mr-2"></i>Daily Avg</td>
${markets.map(m => `<td class="px-3 py-3 text-center">${marketData[m].daily}</td>`).join('')}
<td class="px-4 py-3 text-center font-bold bg-indigo-50">${(totalHits / DAYS / markets.length).toFixed(2)}</td>
</tr>
<tr class="border-b bg-slate-50">
<td class="px-4 py-3 font-medium"><i class="fa-solid fa-calendar-week text-purple-500 mr-2"></i>Weekly Avg</td>
${markets.map(m => `<td class="px-3 py-3 text-center">${marketData[m].weekly}</td>`).join('')}
<td class="px-4 py-3 text-center font-bold bg-indigo-50">${(totalHits / WEEKS / markets.length).toFixed(1)}</td>
</tr>
<tr class="border-b">
<td class="px-4 py-3 font-medium"><i class="fa-solid fa-hashtag text-slate-500 mr-2"></i>Total Hits</td>
${markets.map(m => `<td class="px-3 py-3 text-center font-semibold">${marketData[m].hits}</td>`).join('')}
<td class="px-4 py-3 text-center font-bold bg-indigo-50">${totalHits}</td>
</tr>
</tbody>
</table>
</div>
`;
}
// 1.10 Analytics Dashboard
function render1_10(competitors, markets) {
const totalHits = competitors.reduce((sum, c) => sum + (parseInt(c.total_hits) || 0), 0);
const totalEmail = competitors.reduce((sum, c) => sum + (parseInt(c.email_count) || 0), 0);
const totalSms = competitors.reduce((sum, c) => sum + (parseInt(c.sms_count) || 0), 0);
const totalPush = competitors.reduce((sum, c) => sum + (parseInt(c.push_count) || 0), 0);
const totalCall = competitors.reduce((sum, c) => sum + (parseInt(c.call_count) || 0), 0);
const avgPerMarket = markets.length > 0 ? Math.round(totalHits / markets.length) : 0;
const maxMarket = competitors.reduce((max, c) =>
(parseInt(c.total_hits) || 0) > (parseInt(max?.total_hits) || 0) ? c : max, competitors[0]);
return `
<div class="grid grid-cols-4 gap-4 mb-6">
<div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 text-white">
<p class="text-blue-100 text-sm">Total Hits</p>
<p class="text-2xl font-bold">${totalHits.toLocaleString()}</p>
</div>
<div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-4 text-white">
<p class="text-green-100 text-sm">Avg/Market</p>
<p class="text-2xl font-bold">${avgPerMarket.toLocaleString()}</p>
</div>
<div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-4 text-white">
<p class="text-purple-100 text-sm">Markets</p>
<p class="text-2xl font-bold">${markets.length}</p>
</div>
<div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl p-4 text-white">
<p class="text-amber-100 text-sm">Top Market</p>
<p class="text-2xl font-bold">${maxMarket?.market_id || '-'}</p>
</div>
</div>
<div class="grid grid-cols-2 gap-6">
<div class="bg-slate-50 rounded-lg p-4">
<h4 class="font-semibold mb-3"><i class="fa-solid fa-chart-pie text-indigo-500 mr-2"></i>Channel Split</h4>
<div class="space-y-2">
<div class="flex justify-between"><span>Email</span><span class="font-semibold">${totalEmail.toLocaleString()} <span class="text-slate-400">(${totalHits > 0 ? Math.round(totalEmail / totalHits * 100) : 0}%)</span></span></div>
<div class="flex justify-between"><span>SMS</span><span class="font-semibold">${totalSms.toLocaleString()} <span class="text-slate-400">(${totalHits > 0 ? Math.round(totalSms / totalHits * 100) : 0}%)</span></span></div>
<div class="flex justify-between"><span>Push</span><span class="font-semibold">${totalPush.toLocaleString()} <span class="text-slate-400">(${totalHits > 0 ? Math.round(totalPush / totalHits * 100) : 0}%)</span></span></div>
<div class="flex justify-between"><span>Call</span><span class="font-semibold">${totalCall.toLocaleString()} <span class="text-slate-400">(${totalHits > 0 ? Math.round(totalCall / totalHits * 100) : 0}%)</span></span></div>
</div>
</div>
<div class="bg-slate-50 rounded-lg p-4">
<h4 class="font-semibold mb-3"><i class="fa-solid fa-ranking-star text-indigo-500 mr-2"></i>Market Rankings</h4>
<div class="space-y-2">
${competitors.slice().sort((a, b) => (parseInt(b.total_hits) || 0) - (parseInt(a.total_hits) || 0)).slice(0, 4).map((c, i) => `
<div class="flex justify-between">
<span><span class="text-slate-400">#${i + 1}</span> ${c.market_id}</span>
<span class="font-semibold">${(parseInt(c.total_hits) || 0).toLocaleString()}</span>
</div>
`).join('')}
</div>
</div>
</div>
`;
}
// 2.1 Content Analysis
function render2_1(competitors, markets) {
const cs = brandData.contentStats || {};
const totalHits = cs.total_hits || competitors.reduce((sum, c) => sum + (parseInt(c.total_hits) || 0), 0);
const subjectData = cs.subject || {};
const personalization = cs.personalization || {};
const images = cs.images || {};
return `
<div class="text-center py-6">
<div class="inline-flex items-center gap-4 mb-6">
<div class="bg-blue-100 rounded-full p-4">
<i class="fa-solid fa-file-lines text-3xl text-blue-600"></i>
</div>
<div class="text-left">
<p class="text-3xl font-bold text-slate-800">${totalHits.toLocaleString()}</p>
<p class="text-slate-500">Total communications analyzed</p>
</div>
</div>
</div>
<div class="grid grid-cols-3 gap-4">
<div class="bg-slate-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-envelope-open-text text-2xl text-indigo-500 mb-2"></i>
<p class="text-xl font-bold">${subjectData.with_subject || 0}</p>
<p class="text-sm text-slate-500">With Subject</p>
<p class="text-xs text-slate-400 mt-1">Avg: ${subjectData.avg_length || 0} chars</p>
</div>
<div class="bg-slate-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-user-tag text-2xl text-purple-500 mb-2"></i>
<p class="text-xl font-bold">${personalization.rate || 0}%</p>
<p class="text-sm text-slate-500">Personalized</p>
<p class="text-xs text-slate-400 mt-1">${personalization.count || 0} emails</p>
</div>
<div class="bg-slate-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-images text-2xl text-emerald-500 mb-2"></i>
<p class="text-xl font-bold">${images.avg_count || 0}</p>
<p class="text-sm text-slate-500">Avg Images</p>
</div>
</div>
<div class="mt-4 bg-slate-50 rounded-lg p-4">
<h4 class="font-semibold mb-3"><i class="fa-solid fa-ruler text-indigo-500 mr-2"></i>Subject Length Distribution</h4>
<div class="grid grid-cols-3 gap-4 text-center">
<div><p class="text-lg font-bold">${subjectData.short || 0}</p><p class="text-xs text-slate-500">&lt;30 chars</p></div>
<div><p class="text-lg font-bold">${subjectData.medium || 0}</p><p class="text-xs text-slate-500">30-60 chars</p></div>
<div><p class="text-lg font-bold">${subjectData.long || 0}</p><p class="text-xs text-slate-500">&gt;60 chars</p></div>
</div>
</div>
`;
}
// 2.2 Offer Strategy
function render2_2(competitors, markets) {
const os = brandData.offerStats || {};
const total = os.total_hits || 1;
const promoTypes = os.promotion_types || [];
const values = os.values || {};
const bonusCodes = os.bonus_codes || {};
const wagering = os.wagering || {};
const hasData = promoTypes.length > 0;
return `
<div class="grid grid-cols-4 gap-4 mb-6">
<div class="bg-indigo-50 rounded-lg p-4 text-center">
<p class="text-2xl font-bold text-indigo-600">${total.toLocaleString()}</p>
<p class="text-sm text-indigo-500">Total Offers</p>
</div>
<div class="bg-green-50 rounded-lg p-4 text-center">
<p class="text-2xl font-bold text-green-600">${values.with_value_pct || 0}%</p>
<p class="text-sm text-green-500">With Value</p>
</div>
<div class="bg-purple-50 rounded-lg p-4 text-center">
<p class="text-2xl font-bold text-purple-600">${bonusCodes.rate || 0}%</p>
<p class="text-sm text-purple-500">With Bonus Code</p>
</div>
<div class="bg-amber-50 rounded-lg p-4 text-center">
<p class="text-2xl font-bold text-amber-600">${wagering.disclosure_rate || 0}%</p>
<p class="text-sm text-amber-500">Wagering Disclosed</p>
</div>
</div>
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-100">
<tr>
<th class="px-4 py-2 text-left">Promotion Type</th>
<th class="px-4 py-2 text-center">Count</th>
<th class="px-4 py-2 text-center">%</th>
</tr>
</thead>
<tbody>
${hasData ? promoTypes.slice(0, 6).map((p, i) => `
<tr class="border-b ${i % 2 ? 'bg-slate-50' : ''}">
<td class="px-4 py-3"><i class="fa-solid fa-tag text-indigo-400 mr-2"></i>${p.promotion_type}</td>
<td class="px-4 py-3 text-center font-semibold">${p.count}</td>
<td class="px-4 py-3 text-center">${Math.round(p.count / total * 100)}%</td>
</tr>
`).join('') : `
<tr><td colspan="3" class="px-4 py-6 text-center text-slate-400">No promotion type data available</td></tr>
`}
</tbody>
</table>
</div>
`;
}
// 2.3 Subject Lines
function render2_3(competitors, markets) {
const cs = brandData.contentStats || {};
const subject = cs.subject || {};
const personalization = cs.personalization || {};
const totalWithSubject = subject.with_subject || 0;
const short = subject.short || 0;
const medium = subject.medium || 0;
const long = subject.long || 0;
const totalSubjects = short + medium + long || 1;
return `
<div class="grid grid-cols-4 gap-4 mb-6">
<div class="border border-blue-200 bg-blue-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-envelope-open text-2xl text-blue-600 mb-2"></i>
<p class="text-xl font-bold text-blue-700">${totalWithSubject.toLocaleString()}</p>
<p class="text-sm text-blue-600">With Subject</p>
</div>
<div class="border border-purple-200 bg-purple-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-ruler text-2xl text-purple-600 mb-2"></i>
<p class="text-xl font-bold text-purple-700">${subject.avg_length || 0}</p>
<p class="text-sm text-purple-600">Avg Characters</p>
</div>
<div class="border border-green-200 bg-green-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-user-tag text-2xl text-green-600 mb-2"></i>
<p class="text-xl font-bold text-green-700">${personalization.rate || 0}%</p>
<p class="text-sm text-green-600">Personalized</p>
</div>
<div class="border border-amber-200 bg-amber-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-signature text-2xl text-amber-600 mb-2"></i>
<p class="text-xl font-bold text-amber-700">${personalization.count || 0}</p>
<p class="text-sm text-amber-600">Using First Name</p>
</div>
</div>
<div class="bg-slate-50 rounded-lg p-4 mb-4">
<h4 class="font-semibold mb-4"><i class="fa-solid fa-chart-bar text-indigo-500 mr-2"></i>Subject Length Distribution</h4>
<div class="space-y-3">
<div class="flex items-center gap-3">
<span class="w-24 text-sm text-slate-600">Short (&lt;30)</span>
<div class="flex-1 h-4 bg-slate-200 rounded-full overflow-hidden">
<div class="h-full bg-green-500 rounded-full" style="width: ${Math.round(short / totalSubjects * 100)}%"></div>
</div>
<span class="w-12 text-right text-sm font-medium">${short}</span>
</div>
<div class="flex items-center gap-3">
<span class="w-24 text-sm text-slate-600">Medium</span>
<div class="flex-1 h-4 bg-slate-200 rounded-full overflow-hidden">
<div class="h-full bg-blue-500 rounded-full" style="width: ${Math.round(medium / totalSubjects * 100)}%"></div>
</div>
<span class="w-12 text-right text-sm font-medium">${medium}</span>
</div>
<div class="flex items-center gap-3">
<span class="w-24 text-sm text-slate-600">Long (&gt;60)</span>
<div class="flex-1 h-4 bg-slate-200 rounded-full overflow-hidden">
<div class="h-full bg-purple-500 rounded-full" style="width: ${Math.round(long / totalSubjects * 100)}%"></div>
</div>
<span class="w-12 text-right text-sm font-medium">${long}</span>
</div>
</div>
</div>
<p class="text-xs text-slate-400 text-center">
<i class="fa-solid fa-info-circle mr-1"></i>
Subject line analysis based on ${totalWithSubject.toLocaleString()} emails with subjects
</p>
`;
}
// 3.1 Compliance Overview
function render3_1(competitors, markets) {
const cs = brandData.complianceStats || {};
const disclaimer = cs.legal_disclaimer || {};
const rg = cs.rg_messaging || {};
const wagering = cs.wagering_disclosure || {};
const cashout = cs.cashout_disclosure || {};
const deposit = cs.deposit_disclosure || {};
const completeness = cs.completeness || {};
return `
<div class="grid grid-cols-4 gap-4 mb-6">
<div class="border border-blue-200 bg-blue-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-file-contract text-2xl text-blue-600 mb-2"></i>
<p class="text-xl font-bold text-blue-700">${disclaimer.rate || 0}%</p>
<p class="text-sm text-blue-600">Legal Disclaimer</p>
</div>
<div class="border border-green-200 bg-green-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-dice text-2xl text-green-600 mb-2"></i>
<p class="text-xl font-bold text-green-700">${wagering.rate || 0}%</p>
<p class="text-sm text-green-600">Wagering Disclosed</p>
</div>
<div class="border border-amber-200 bg-amber-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-coins text-2xl text-amber-600 mb-2"></i>
<p class="text-xl font-bold text-amber-700">${cashout.rate || 0}%</p>
<p class="text-sm text-amber-600">Cashout Disclosed</p>
</div>
<div class="border border-purple-200 bg-purple-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-chart-simple text-2xl text-purple-600 mb-2"></i>
<p class="text-xl font-bold text-purple-700">${completeness.average || 0}%</p>
<p class="text-sm text-purple-600">Avg Completeness</p>
</div>
</div>
<div class="bg-slate-50 rounded-lg p-4">
<h4 class="font-semibold mb-3"><i class="fa-solid fa-list-check text-indigo-500 mr-2"></i>Data Completeness</h4>
<div class="grid grid-cols-3 gap-4 text-center">
<div class="bg-green-100 rounded p-3">
<p class="text-xl font-bold text-green-700">${completeness.high || 0}</p>
<p class="text-xs text-green-600">Complete (&gt;80%)</p>
</div>
<div class="bg-amber-100 rounded p-3">
<p class="text-xl font-bold text-amber-700">${completeness.partial || 0}</p>
<p class="text-xs text-amber-600">Partial (40-80%)</p>
</div>
<div class="bg-red-100 rounded p-3">
<p class="text-xl font-bold text-red-700">${completeness.low || 0}</p>
<p class="text-xs text-red-600">Incomplete (&lt;40%)</p>
</div>
</div>
</div>
`;
}
// 3.2 GDPR Readiness
function render3_2(competitors, markets) {
const euMarkets = markets.filter(m => ['ES', 'PT', 'IT', 'FR', 'DE', 'NL', 'BE', 'AT', 'GR', 'MT', 'IE', 'UK'].some(c => m.includes(c)));
return `
<div class="grid grid-cols-2 gap-6 mb-6">
<div class="bg-slate-50 rounded-lg p-4">
<h4 class="font-semibold mb-3"><i class="fa-solid fa-globe-europe text-blue-500 mr-2"></i>EU Markets</h4>
<p class="text-3xl font-bold text-blue-600">${euMarkets.length} <span class="text-lg text-slate-400">/ ${markets.length}</span></p>
<p class="text-sm text-slate-500 mt-1">Markets in GDPR scope</p>
</div>
<div class="bg-slate-50 rounded-lg p-4">
<h4 class="font-semibold mb-3"><i class="fa-solid fa-shield-halved text-green-500 mr-2"></i>Privacy Score</h4>
<p class="text-3xl font-bold text-slate-400">-</p>
<p class="text-sm text-slate-500 mt-1">Not yet assessed</p>
</div>
</div>
<div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
<h4 class="font-semibold text-amber-800 mb-2"><i class="fa-solid fa-lock mr-2"></i>GDPR Requirements</h4>
<ul class="text-sm text-amber-700 space-y-1">
<li><i class="fa-regular fa-square mr-2"></i>Consent mechanism verification</li>
<li><i class="fa-regular fa-square mr-2"></i>Data retention policy check</li>
<li><i class="fa-regular fa-square mr-2"></i>Right to erasure compliance</li>
<li><i class="fa-regular fa-square mr-2"></i>Privacy notice accessibility</li>
</ul>
</div>
`;
}
// 3.3 Responsible Gaming
function render3_3(competitors, markets) {
const cs = brandData.complianceStats || {};
const disclaimer = cs.legal_disclaimer || {};
const rg = cs.rg_messaging || {};
const wagering = cs.wagering_disclosure || {};
const completeness = cs.completeness || {};
return `
<div class="grid grid-cols-4 gap-4 mb-6">
<div class="border border-green-200 bg-green-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-check text-xl text-green-600 mb-2"></i>
<p class="text-lg font-bold text-green-700">${rg.present || 0}</p>
<p class="text-xs text-green-600">RG Messaging</p>
</div>
<div class="border border-blue-200 bg-blue-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-dice text-xl text-blue-600 mb-2"></i>
<p class="text-lg font-bold text-blue-700">${wagering.rate || 0}%</p>
<p class="text-xs text-blue-600">Wagering Shown</p>
</div>
<div class="border border-amber-200 bg-amber-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-hand text-xl text-amber-600 mb-2"></i>
<p class="text-lg font-bold text-amber-700">${completeness.high || 0}</p>
<p class="text-xs text-amber-600">Fully Compliant</p>
</div>
<div class="border border-purple-200 bg-purple-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-info text-xl text-purple-600 mb-2"></i>
<p class="text-lg font-bold text-purple-700">${completeness.average || 0}%</p>
<p class="text-xs text-purple-600">Avg Compliance</p>
</div>
</div>
<div class="bg-slate-50 rounded-lg p-4">
<h4 class="font-semibold mb-3"><i class="fa-solid fa-shield-check text-indigo-500 mr-2"></i>RG Compliance Markers</h4>
<div class="grid grid-cols-2 gap-4">
<div class="space-y-2">
<div class="flex items-center gap-2">
<i class="fa-solid fa-${disclaimer.rate > 0 ? 'check text-green-500' : 'xmark text-slate-300'}"></i>
<span class="text-sm">Legal disclaimer visibility</span>
</div>
<div class="flex items-center gap-2">
<i class="fa-solid fa-${wagering.rate > 0 ? 'check text-green-500' : 'xmark text-slate-300'}"></i>
<span class="text-sm">Wagering requirements disclosed</span>
</div>
</div>
<div class="space-y-2">
<div class="flex items-center gap-2">
<i class="fa-solid fa-xmark text-slate-300"></i>
<span class="text-sm text-slate-400">Self-exclusion links (not tracked)</span>
</div>
<div class="flex items-center gap-2">
<i class="fa-solid fa-xmark text-slate-300"></i>
<span class="text-sm text-slate-400">Help resources (not tracked)</span>
</div>
</div>
</div>
</div>
<p class="text-xs text-slate-400 mt-4 text-center">
<i class="fa-solid fa-info-circle mr-1"></i>
Responsible gaming compliance based on email content analysis
</p>
`;
}
// 4.1 Regulatory Risk
function render4_1(competitors, markets) {
const ls = brandData.licenseStats || {};
const summary = ls.summary || {};
const byMarket = ls.by_market || {};
const riskScore = ls.risk_score ?? 100;
const riskLabel = riskScore <= 30 ? 'Low Risk' : riskScore <= 60 ? 'Medium Risk' : 'High Risk';
const riskColor = riskScore <= 30 ? 'green' : riskScore <= 60 ? 'amber' : 'red';
const riskBg = riskScore <= 30 ? 'bg-green-100 text-green-700' : riskScore <= 60 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700';
const marketKeys = Object.keys(byMarket);
const hasData = marketKeys.length > 0;
return `
<div class="mb-6">
<div class="flex items-center justify-between mb-4">
<h4 class="font-semibold"><i class="fa-solid fa-gauge-high text-indigo-500 mr-2"></i>Risk Assessment</h4>
<span class="${riskBg} px-3 py-1 rounded-full text-sm font-medium">${hasData ? riskLabel : 'Not Assessed'}</span>
</div>
<div class="h-4 bg-slate-200 rounded-full overflow-hidden">
<div class="h-full bg-gradient-to-r from-green-500 via-amber-500 to-red-500" style="width: ${hasData ? riskScore : 0}%"></div>
</div>
<div class="flex justify-between text-xs text-slate-400 mt-1">
<span>Low Risk</span>
<span>Medium</span>
<span>High Risk</span>
</div>
</div>
<div class="grid grid-cols-4 gap-4 mb-4">
<div class="bg-green-50 rounded-lg p-3 text-center">
<p class="text-xl font-bold text-green-700">${summary.active || 0}</p>
<p class="text-xs text-green-600">Active Licenses</p>
</div>
<div class="bg-amber-50 rounded-lg p-3 text-center">
<p class="text-xl font-bold text-amber-700">${summary.pending || 0}</p>
<p class="text-xs text-amber-600">Pending</p>
</div>
<div class="bg-red-50 rounded-lg p-3 text-center">
<p class="text-xl font-bold text-red-700">${summary.grey || 0}</p>
<p class="text-xs text-red-600">Grey Market</p>
</div>
<div class="bg-blue-50 rounded-lg p-3 text-center">
<p class="text-xl font-bold text-blue-700">${(summary.regulators || []).length}</p>
<p class="text-xs text-blue-600">Regulators</p>
</div>
</div>
${!hasData ? '<p class="text-slate-400 text-center py-4">No license data available for this brand</p>' : `
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-100">
<tr>
<th class="px-4 py-2 text-left">Market</th>
<th class="px-4 py-2 text-center">Status</th>
<th class="px-4 py-2 text-center">Regulator</th>
<th class="px-4 py-2 text-center">Risk Level</th>
</tr>
</thead>
<tbody>
${marketKeys.map(m => {
const lic = byMarket[m];
const status = lic.status || 'unknown';
const statusBadge = status === 'active' ? 'bg-green-100 text-green-700' :
status === 'pending' ? 'bg-amber-100 text-amber-700' :
status === 'grey' ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700';
const riskLevel = status === 'active' ? '🟢 Low' : status === 'pending' ? '🟡 Medium' : '🔴 High';
return `
<tr class="border-b hover:bg-slate-50">
<td class="px-4 py-3 font-medium"><i class="fa-solid fa-map-marker-alt text-blue-500 mr-2"></i>${m}</td>
<td class="px-4 py-3 text-center"><span class="${statusBadge} px-2 py-1 rounded text-xs font-medium">${status.charAt(0).toUpperCase() + status.slice(1)}</span></td>
<td class="px-4 py-3 text-center">${lic.regulator || '-'}</td>
<td class="px-4 py-3 text-center text-sm">${riskLevel}</td>
</tr>`;
}).join('')}
</tbody>
</table>
</div>
`}
`;
}
// 1.4 Product Matrix
function render1_4(competitors, markets) {
const products = ['Casino', 'Sports', 'Poker', 'Bingo', 'Lottery'];
return `
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-100">
<tr>
<th class="px-4 py-2 text-left">Product</th>
${markets.map(m => `<th class="px-3 py-2 text-center text-xs">${m}</th>`).join('')}
<th class="px-4 py-2 text-center bg-indigo-50">Total</th>
</tr>
</thead>
<tbody>
${products.map((p, i) => `
<tr class="border-b ${i % 2 ? 'bg-slate-50' : ''}">
<td class="px-4 py-3 font-medium">${p}</td>
${markets.map(m => `<td class="px-3 py-3 text-center text-slate-400">-</td>`).join('')}
<td class="px-4 py-3 text-center bg-indigo-50 text-slate-400">-</td>
</tr>
`).join('')}
</tbody>
</table>
</div>
<p class="text-xs text-slate-400 mt-4 text-center">
<i class="fa-solid fa-info-circle mr-1"></i>
Product classification requires content tagging setup
</p>
`;
}
// 1.5 Acquisition Timeline
function render1_5(competitors, markets) {
const acqCount = brandData.lifecycleStats?.acquisition || brandData.lifecycleStats?.Acquisition || 0;
return `
<div class="grid grid-cols-3 gap-4 mb-6">
<div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
<i class="fa-solid fa-bullseye text-2xl text-green-600 mb-2"></i>
<p class="text-2xl font-bold text-green-700">${acqCount}</p>
<p class="text-sm text-green-600">Acquisition Hits</p>
</div>
<div class="bg-slate-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-clock text-2xl text-slate-400 mb-2"></i>
<p class="text-2xl font-bold text-slate-600">-</p>
<p class="text-sm text-slate-500">Avg. Days to Convert</p>
</div>
<div class="bg-slate-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-arrow-trend-up text-2xl text-slate-400 mb-2"></i>
<p class="text-2xl font-bold text-slate-600">-</p>
<p class="text-sm text-slate-500">Conversion Rate</p>
</div>
</div>
<p class="text-xs text-slate-400 text-center">
Timeline analysis requires tracking start/end dates
</p>
`;
}
// 1.6 Retention Pulse
function render1_6(competitors, markets) {
const retCount = brandData.lifecycleStats?.retention || brandData.lifecycleStats?.Retention || 0;
return `
<div class="grid grid-cols-3 gap-4 mb-6">
<div class="bg-pink-50 border border-pink-200 rounded-lg p-4 text-center">
<i class="fa-solid fa-heart text-2xl text-pink-600 mb-2"></i>
<p class="text-2xl font-bold text-pink-700">${retCount}</p>
<p class="text-sm text-pink-600">Retention Hits</p>
</div>
<div class="bg-slate-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-repeat text-2xl text-slate-400 mb-2"></i>
<p class="text-2xl font-bold text-slate-600">-</p>
<p class="text-sm text-slate-500">Avg. Frequency</p>
</div>
<div class="bg-slate-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-user-check text-2xl text-slate-400 mb-2"></i>
<p class="text-2xl font-bold text-slate-600">-</p>
<p class="text-sm text-slate-500">Retention Rate</p>
</div>
</div>
<p class="text-xs text-slate-400 text-center">
Retention metrics require lifecycle tracking configuration
</p>
`;
}
// 1.7 Reactivation Analysis
function render1_7(competitors, markets) {
const reactCount = brandData.lifecycleStats?.reactivation || brandData.lifecycleStats?.Reactivation || 0;
return `
<div class="grid grid-cols-3 gap-4 mb-6">
<div class="bg-orange-50 border border-orange-200 rounded-lg p-4 text-center">
<i class="fa-solid fa-rotate text-2xl text-orange-600 mb-2"></i>
<p class="text-2xl font-bold text-orange-700">${reactCount}</p>
<p class="text-sm text-orange-600">Reactivation Hits</p>
</div>
<div class="bg-slate-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-hourglass text-2xl text-slate-400 mb-2"></i>
<p class="text-2xl font-bold text-slate-600">-</p>
<p class="text-sm text-slate-500">Avg. Dormancy (days)</p>
</div>
<div class="bg-slate-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-arrow-rotate-left text-2xl text-slate-400 mb-2"></i>
<p class="text-2xl font-bold text-slate-600">-</p>
<p class="text-sm text-slate-500">Win-back Rate</p>
</div>
</div>
<p class="text-xs text-slate-400 text-center">
Reactivation metrics require dormancy tracking
</p>
`;
}
// 1.8 Seasonal Patterns
function render1_8(competitors, markets) {
const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
return `
<div class="mb-6">
<h4 class="font-semibold mb-3"><i class="fa-solid fa-calendar-days text-indigo-500 mr-2"></i>Monthly Distribution</h4>
<div class="grid grid-cols-12 gap-1 h-24">
${months.map((m, i) => `
<div class="flex flex-col items-center justify-end">
<div class="w-full bg-slate-200 rounded-t" style="height: ${10 + Math.random() * 70}%"></div>
<span class="text-xs text-slate-400 mt-1">${m}</span>
</div>
`).join('')}
</div>
</div>
<p class="text-xs text-slate-400 text-center">
<i class="fa-solid fa-info-circle mr-1"></i>
Pattern visualization placeholder - requires historical data
</p>
`;
}
// 1.9 Cross-Channel Sync
function render1_9(competitors, markets) {
const totalEmail = competitors.reduce((sum, c) => sum + (parseInt(c.email_count) || 0), 0);
const totalSms = competitors.reduce((sum, c) => sum + (parseInt(c.sms_count) || 0), 0);
const totalPush = competitors.reduce((sum, c) => sum + (parseInt(c.push_count) || 0), 0);
return `
<div class="grid grid-cols-3 gap-4 mb-6">
<div class="bg-blue-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-envelope text-2xl text-blue-500 mb-2"></i>
<p class="text-xl font-bold">${totalEmail.toLocaleString()}</p>
<p class="text-sm text-slate-500">Email</p>
</div>
<div class="bg-green-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-comment-sms text-2xl text-green-500 mb-2"></i>
<p class="text-xl font-bold">${totalSms.toLocaleString()}</p>
<p class="text-sm text-slate-500">SMS</p>
</div>
<div class="bg-purple-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-bell text-2xl text-purple-500 mb-2"></i>
<p class="text-xl font-bold">${totalPush.toLocaleString()}</p>
<p class="text-sm text-slate-500">Push</p>
</div>
</div>
<div class="bg-slate-50 rounded-lg p-4">
<h4 class="font-semibold mb-2"><i class="fa-solid fa-arrows-rotate text-indigo-500 mr-2"></i>Sync Analysis</h4>
<p class="text-sm text-slate-500">Cross-channel coordination metrics not yet available</p>
</div>
`;
}
// 2.3 Subject Lines
function render2_3(competitors, markets) {
return `
<div class="grid grid-cols-2 gap-6 mb-6">
<div class="bg-slate-50 rounded-lg p-4">
<h4 class="font-semibold mb-3"><i class="fa-solid fa-heading text-indigo-500 mr-2"></i>Length Analysis</h4>
<div class="space-y-2">
<div class="flex justify-between text-sm">
<span>Short (&lt;30 chars)</span>
<span class="text-slate-400">-</span>
</div>
<div class="flex justify-between text-sm">
<span>Medium (30-60 chars)</span>
<span class="text-slate-400">-</span>
</div>
<div class="flex justify-between text-sm">
<span>Long (&gt;60 chars)</span>
<span class="text-slate-400">-</span>
</div>
</div>
</div>
<div class="bg-slate-50 rounded-lg p-4">
<h4 class="font-semibold mb-3"><i class="fa-solid fa-sparkles text-amber-500 mr-2"></i>Common Patterns</h4>
<ul class="text-sm text-slate-500 space-y-1">
<li><i class="fa-regular fa-circle text-slate-300 mr-2"></i>Emoji usage</li>
<li><i class="fa-regular fa-circle text-slate-300 mr-2"></i>Personalization</li>
<li><i class="fa-regular fa-circle text-slate-300 mr-2"></i>Urgency words</li>
</ul>
</div>
</div>
<p class="text-xs text-slate-400 text-center">
<i class="fa-solid fa-info-circle mr-1"></i>
Subject line analysis requires email content extraction
</p>
`;
}
// 3.3 Responsible Gaming
function render3_3(competitors, markets) {
return `
<div class="grid grid-cols-4 gap-4 mb-6">
<div class="border border-green-200 bg-green-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-check text-xl text-green-600 mb-2"></i>
<p class="text-lg font-bold text-green-700">-</p>
<p class="text-xs text-green-600">Self-Limits Offered</p>
</div>
<div class="border border-blue-200 bg-blue-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-clock text-xl text-blue-600 mb-2"></i>
<p class="text-lg font-bold text-blue-700">-</p>
<p class="text-xs text-blue-600">Cool-Off Periods</p>
</div>
<div class="border border-amber-200 bg-amber-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-hand text-xl text-amber-600 mb-2"></i>
<p class="text-lg font-bold text-amber-700">-</p>
<p class="text-xs text-amber-600">Self-Exclusion</p>
</div>
<div class="border border-purple-200 bg-purple-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-info text-xl text-purple-600 mb-2"></i>
<p class="text-lg font-bold text-purple-700">-</p>
<p class="text-xs text-purple-600">Help Resources</p>
</div>
</div>
<div class="bg-slate-50 rounded-lg p-4">
<h4 class="font-semibold mb-2"><i class="fa-solid fa-dice text-indigo-500 mr-2"></i>RG Compliance Markers</h4>
<p class="text-sm text-slate-500">Awaiting responsible gaming content classification</p>
</div>
`;
}
// 4.2 Market Exposure
function render4_2(competitors, markets) {
return `
<div class="grid grid-cols-2 gap-6 mb-6">
<div class="bg-slate-50 rounded-lg p-4">
<h4 class="font-semibold mb-3"><i class="fa-solid fa-globe text-blue-500 mr-2"></i>Geographic Spread</h4>
<p class="text-3xl font-bold">${markets.length}</p>
<p class="text-sm text-slate-500">Active Markets</p>
</div>
<div class="bg-slate-50 rounded-lg p-4">
<h4 class="font-semibold mb-3"><i class="fa-solid fa-building text-indigo-500 mr-2"></i>Brand Presence</h4>
<p class="text-3xl font-bold">${competitors.length}</p>
<p class="text-sm text-slate-500">Competitors Tracked</p>
</div>
</div>
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-100">
<tr>
<th class="px-4 py-2 text-left">Market</th>
<th class="px-4 py-2 text-center">Hits</th>
<th class="px-4 py-2 text-center">Exposure</th>
</tr>
</thead>
<tbody>
${competitors.slice().sort((a, b) => (parseInt(b.total_hits) || 0) - (parseInt(a.total_hits) || 0)).slice(0, 3).map(c => `
<tr class="border-b">
<td class="px-4 py-3 font-medium">${c.market_id}</td>
<td class="px-4 py-3 text-center">${(parseInt(c.total_hits) || 0).toLocaleString()}</td>
<td class="px-4 py-3 text-center"><span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">High</span></td>
</tr>
`).join('')}
</tbody>
</table>
</div>
`;
}
// 4.3 License Status
function render4_3(competitors, markets) {
const ls = brandData.licenseStats || {};
const summary = ls.summary || {};
const byMarket = ls.by_market || {};
const marketKeys = Object.keys(byMarket);
const hasData = marketKeys.length > 0;
const formatExpiry = (date) => {
if (!date) return '-';
const d = new Date(date);
const now = new Date();
const daysLeft = Math.floor((d - now) / (1000 * 60 * 60 * 24));
const formatted = d.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
if (daysLeft < 0) return `<span class="text-red-600">${formatted} (Expired)</span>`;
if (daysLeft < 90) return `<span class="text-amber-600">${formatted} (${daysLeft}d)</span>`;
return formatted;
};
return `
<div class="grid grid-cols-4 gap-4 mb-6">
<div class="border border-green-200 bg-green-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-certificate text-2xl text-green-600 mb-2"></i>
<p class="text-xl font-bold text-green-700">${summary.active || 0}</p>
<p class="text-sm text-green-600">Licensed</p>
</div>
<div class="border border-amber-200 bg-amber-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-hourglass-half text-2xl text-amber-600 mb-2"></i>
<p class="text-xl font-bold text-amber-700">${summary.pending || 0}</p>
<p class="text-sm text-amber-600">Pending</p>
</div>
<div class="border border-purple-200 bg-purple-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-clock text-2xl text-purple-600 mb-2"></i>
<p class="text-xl font-bold text-purple-700">${summary.expired || 0}</p>
<p class="text-sm text-purple-600">Expired</p>
</div>
<div class="border border-red-200 bg-red-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-ban text-2xl text-red-600 mb-2"></i>
<p class="text-xl font-bold text-red-700">${summary.grey || 0}</p>
<p class="text-sm text-red-600">Grey/Unlicensed</p>
</div>
</div>
${!hasData ? '<p class="text-slate-400 text-center py-4">No license data available for this brand</p>' : `
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-100">
<tr>
<th class="px-4 py-2 text-left">Market</th>
<th class="px-4 py-2 text-left">License #</th>
<th class="px-4 py-2 text-center">Regulator</th>
<th class="px-4 py-2 text-center">Expiry</th>
<th class="px-4 py-2 text-center">Verify</th>
</tr>
</thead>
<tbody>
${marketKeys.map(m => {
const lic = byMarket[m];
return `
<tr class="border-b hover:bg-slate-50">
<td class="px-4 py-3 font-medium"><i class="fa-solid fa-map-marker-alt text-blue-500 mr-2"></i>${m}</td>
<td class="px-4 py-3 font-mono text-xs">${lic.license_number || '<span class="text-slate-400">—</span>'}</td>
<td class="px-4 py-3 text-center">${lic.regulator || '-'}</td>
<td class="px-4 py-3 text-center">${formatExpiry(lic.expiry)}</td>
<td class="px-4 py-3 text-center">${lic.verification_url ? `<a href="${lic.verification_url}" target="_blank" class="text-blue-500 hover:underline"><i class="fa-solid fa-external-link"></i></a>` : '-'}</td>
</tr>`;
}).join('')}
</tbody>
</table>
</div>
`}
${(summary.regulators || []).length > 0 ? `
<div class="mt-4 flex flex-wrap gap-2">
<span class="text-xs text-slate-500">Regulators:</span>
${(summary.regulators || []).map(r => `<span class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded text-xs font-medium">${r}</span>`).join('')}
</div>
` : ''}
`;
}
// 5.1 Payment Methods
function render5_1(competitors, markets) {
const ps = brandData.productStats?.payment_methods || {};
const byMarket = ps.by_market || {};
const marketKeys = Object.keys(byMarket);
return `
<div class="grid grid-cols-3 gap-4 mb-6">
<div class="border border-green-200 bg-green-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-credit-card text-2xl text-green-600 mb-2"></i>
<p class="text-xl font-bold text-green-700">${ps.total || 0}</p>
<p class="text-sm text-green-600">Payment Methods</p>
</div>
<div class="border border-blue-200 bg-blue-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-arrow-down text-2xl text-blue-600 mb-2"></i>
<p class="text-xl font-bold text-blue-700">${ps.min_deposit != null ? '$' + ps.min_deposit : '-'}</p>
<p class="text-sm text-blue-600">Min Deposit</p>
</div>
<div class="border border-purple-200 bg-purple-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-arrow-up text-2xl text-purple-600 mb-2"></i>
<p class="text-xl font-bold text-purple-700">${ps.max_deposit != null ? '$' + ps.max_deposit.toLocaleString() : '-'}</p>
<p class="text-sm text-purple-600">Max Deposit</p>
</div>
</div>
${marketKeys.length === 0 ? '<p class="text-slate-400 text-center py-4">No payment method data available</p>' : `
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-100">
<tr>
<th class="px-4 py-2 text-left font-semibold">Market</th>
<th class="px-4 py-2 text-center font-semibold">Count</th>
<th class="px-4 py-2 text-left font-semibold">Methods</th>
</tr>
</thead>
<tbody>
${marketKeys.map(market => {
const methods = byMarket[market] || [];
const methodNames = methods.slice(0, 3).map(m => m.name).join(', ');
const moreCount = methods.length > 3 ? ' +' + (methods.length - 3) + ' more' : '';
return '<tr class="border-b hover:bg-slate-50 cursor-pointer" onclick="this.nextElementSibling.classList.toggle(\'hidden\')">' +
'<td class="px-4 py-3 font-medium"><i class="fa-solid fa-map-marker-alt text-blue-500 mr-2"></i>' + market + ' <i class="fa-solid fa-chevron-down text-slate-300 ml-2 text-xs"></i></td>' +
'<td class="px-4 py-3 text-center"><span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">' + methods.length + '</span></td>' +
'<td class="px-4 py-3 text-slate-600">' + methodNames + moreCount + '</td>' +
'</tr>' +
'<tr class="hidden bg-slate-50"><td colspan="3" class="px-4 py-3">' +
'<table class="w-full text-xs"><thead class="bg-slate-200"><tr>' +
'<th class="px-3 py-1 text-left">Method</th><th class="px-3 py-1 text-left">Category</th><th class="px-3 py-1 text-center">Min</th><th class="px-3 py-1 text-center">Max</th>' +
'</tr></thead><tbody>' +
methods.map(m => '<tr class="border-b border-slate-100"><td class="px-3 py-2 font-medium">' + m.name + '</td><td class="px-3 py-2 text-slate-500">' + (m.category || '-') + '</td><td class="px-3 py-2 text-center">' + (m.min ? '$' + m.min : '-') + '</td><td class="px-3 py-2 text-center">' + (m.max ? '$' + m.max.toLocaleString() : '-') + '</td></tr>').join('') +
'</tbody></table></td></tr>';
}).join('')}
</tbody>
</table>
</div>
`}
`;
}
// 5.2 Support Channels
function render5_2(competitors, markets) {
const sc = brandData.productStats?.support_channels || {};
const channels = sc.channels || [];
const byMarket = sc.by_market || {};
const marketKeys = Object.keys(byMarket);
const channelIcons = {
'Live Chat': 'fa-comments',
'Email': 'fa-envelope',
'Call': 'fa-phone',
'Telegram': 'fa-paper-plane',
'Form': 'fa-file-lines',
'FAQ': 'fa-circle-question'
};
return `
<div class="grid grid-cols-${Math.min(channels.length, 4)} gap-4 mb-6">
${channels.slice(0, 4).map(ch => `
<div class="border border-blue-200 bg-blue-50 rounded-lg p-4 text-center">
<i class="fa-solid ${channelIcons[ch] || 'fa-headset'} text-2xl text-blue-600 mb-2"></i>
<p class="text-sm font-semibold text-blue-700">${ch}</p>
</div>
`).join('')}
</div>
${channels.length === 0 ? '<p class="text-slate-400 text-center py-4">No support channel data available</p>' : `
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-100">
<tr>
<th class="px-4 py-2 text-left font-semibold">Market</th>
<th class="px-4 py-2 text-left font-semibold">Channels</th>
</tr>
</thead>
<tbody>
${marketKeys.map(market => {
const marketChannels = byMarket[market] || [];
return '<tr class="border-b hover:bg-slate-50">' +
'<td class="px-4 py-3 font-medium"><i class="fa-solid fa-map-marker-alt text-green-500 mr-2"></i>' + market + '</td>' +
'<td class="px-4 py-3"><div class="flex flex-wrap gap-2">' +
marketChannels.map(ch => '<span class="inline-flex items-center gap-1 bg-green-100 text-green-700 rounded-full px-3 py-1 text-xs"><i class="fa-solid ' + (channelIcons[ch] || 'fa-headset') + '"></i> ' + ch + '</span>').join('') +
'</div></td></tr>';
}).join('')}
</tbody>
</table>
</div>
`}
`;
}
// 5.3 KYC Requirements
function render5_3(competitors, markets) {
const kyc = brandData.productStats?.kyc_requirements || {};
const byMarket = kyc.by_market || {};
const marketKeys = Object.keys(byMarket);
const levelColors = {
'Light': { bg: 'bg-green-50', border: 'border-green-200', text: 'text-green-700', icon: 'fa-check-circle' },
'Standard': { bg: 'bg-amber-50', border: 'border-amber-200', text: 'text-amber-700', icon: 'fa-id-card' },
'Heavy': { bg: 'bg-red-50', border: 'border-red-200', text: 'text-red-700', icon: 'fa-shield-halved' }
};
const level = levelColors[kyc.level] || levelColors['Standard'];
return `
<div class="grid grid-cols-2 gap-4 mb-6">
<div class="border ${level.border} ${level.bg} rounded-lg p-4 text-center">
<i class="fa-solid ${level.icon} text-2xl ${level.text} mb-2"></i>
<p class="text-xl font-bold ${level.text}">${kyc.level || 'Unknown'}</p>
<p class="text-sm ${level.text.replace('700', '600')}">KYC Level</p>
</div>
<div class="border border-slate-200 bg-slate-50 rounded-lg p-4 text-center">
<i class="fa-solid fa-list-check text-2xl text-slate-600 mb-2"></i>
<p class="text-xl font-bold text-slate-700">${kyc.avg_fields || 0}</p>
<p class="text-sm text-slate-600">Avg. Required Fields</p>
</div>
</div>
${marketKeys.length === 0 ? '<p class="text-slate-400 text-center py-4">No KYC data available</p>' : `
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-100">
<tr>
<th class="px-4 py-2 text-left font-semibold">Market</th>
<th class="px-4 py-2 text-center font-semibold">Fields</th>
<th class="px-4 py-2 text-left font-semibold">Requirements</th>
</tr>
</thead>
<tbody>
${marketKeys.map(market => {
const fields = byMarket[market] || [];
const fieldNames = fields.slice(0, 3).join(', ');
const moreCount = fields.length > 3 ? ' +' + (fields.length - 3) + ' more' : '';
return '<tr class="border-b hover:bg-slate-50 cursor-pointer" onclick="this.nextElementSibling.classList.toggle(\'hidden\')">' +
'<td class="px-4 py-3 font-medium"><i class="fa-solid fa-map-marker-alt text-amber-500 mr-2"></i>' + market + ' <i class="fa-solid fa-chevron-down text-slate-300 ml-2 text-xs"></i></td>' +
'<td class="px-4 py-3 text-center"><span class="bg-amber-100 text-amber-700 px-2 py-1 rounded text-xs font-medium">' + fields.length + '</span></td>' +
'<td class="px-4 py-3 text-slate-600">' + fieldNames + moreCount + '</td>' +
'</tr>' +
'<tr class="hidden bg-slate-50"><td colspan="3" class="px-4 py-3"><div class="flex flex-wrap gap-2">' +
fields.map(f => '<span class="inline-flex items-center gap-1 bg-white border border-slate-200 rounded px-2 py-1 text-xs"><i class="fa-solid fa-check text-green-500"></i> ' + f + '</span>').join('') +
'</div></td></tr>';
}).join('')}
</tbody>
</table>
</div>
`}
`;
}
// Generic fallback
function renderGenericReport(reportId, competitors, markets) {
const report = REPORTS.find(r => r.id === reportId);
return `
<div class="text-center py-8 bg-slate-50 rounded-lg">
<i class="fa-solid ${report?.icon || 'fa-file'} text-4xl text-slate-300 mb-3"></i>
<p class="text-slate-500 font-medium">${report?.name || reportId}</p>
<p class="text-xs text-slate-400 mt-2">Report-specific data coming soon</p>
<p class="text-xs text-slate-300 mt-1">${competitors.length} competitors across ${markets.length} markets</p>
</div>
`;
}
function toggleSection(reportId) {
const section = document.getElementById(`section-${reportId}`);
section.classList.toggle('collapsed');
const icon = section.querySelector('.section-toggle');
icon.classList.toggle('fa-chevron-down');
icon.classList.toggle('fa-chevron-right');
}
function exportPDF() {
alert('PDF export coming in Phase 3!');
}
</script>
</div>
@endsection

@push('page-scripts')
<script>
                // ========================================
                // STATE
                // ========================================
                let brands = [];
                let selectedBrand = null;
                let selectedReports = [];
                let comparisonMode = 'by-market';
                let brandData = null;
                let benchmarkBrand = null;
                let benchmarkData = null;

                // Available reports registry - matches current NAV_CONFIG in navigation.js
                const REPORTS = [
                    // Marketing (M.1)
                    { id: '1.1', name: 'Performance Dashboard', module: 'Marketing', icon: 'fa-chart-line', file: '1.1.html' },
                    { id: '1.2', name: 'Journey Mapping', module: 'Marketing', icon: 'fa-route', file: '1.2.html' },
                    { id: '1.3', name: 'Customer Lifecycle', module: 'Marketing', icon: 'fa-heart-pulse', file: '1.3.html' },
                    { id: '1.4', name: 'Product Matrix', module: 'Marketing', icon: 'fa-th', file: '1.4.html' },
                    { id: '1.5', name: 'Timing Intelligence', module: 'Marketing', icon: 'fa-clock', file: '1.5.html' },
                    // Content (M.2)
                    { id: '2.1', name: 'Content Quality Hub', module: 'Content', icon: 'fa-file-lines', file: '2.1.html' },
                    { id: '2.2', name: 'Offers & Risk', module: 'Content', icon: 'fa-tags', file: '2.2.html' },
                    // Compliance (M.3)
                    { id: '3.1', name: 'Compliance Scorecard', module: 'Compliance', icon: 'fa-shield-halved', file: '3.1.html' },
                    { id: '3.2', name: 'Compliance Alignment', module: 'Compliance', icon: 'fa-lock', file: '3.2.html' },
                    { id: '3.3', name: 'Audit Preparedness', module: 'Compliance', icon: 'fa-clipboard-check', file: '3.3.html' },
                    // Risk/Regulatory (M.4)
                    { id: '4.1', name: 'License & Entity Verification', module: 'Risk', icon: 'fa-id-card', file: '4.1.html' },
                    { id: '4.2', name: 'Compliance Cost Analysis', module: 'Risk', icon: 'fa-calculator', file: '4.2.html' },
                    { id: '4.3', name: 'Transparency Audit', module: 'Risk', icon: 'fa-eye', file: '4.3.html' },
                    // Product (M.5)
                    { id: '5.1', name: 'Valuation Uplift', module: 'Product', icon: 'fa-chart-line', file: '5.1.html' },
                    { id: '5.2', name: 'Compliance Exposure', module: 'Product', icon: 'fa-shield-halved', file: '5.2.html' },
                    { id: '5.3', name: 'Migration Readiness', module: 'Product', icon: 'fa-arrows-rotate', file: '5.3.html' },
                ];

                // Market flag emoji mapping
                function getMarketFlag(marketId) {
                    const flags = {
                        'CA-ON': '🇨🇦', 'CA-BC': '🇨🇦', 'CA-AB': '🇨🇦', 'CA-QC': '🇨🇦',
                        'UK-GB': '🇬🇧', 'UK-ALL': '🇬🇧',
                        'US-NJ': '🇺🇸', 'US-PA': '🇺🇸', 'US-MI': '🇺🇸', 'US-WV': '🇺🇸', 'US-ALL': '🇺🇸',
                        'TR-ALL': '🇹🇷',
                        'CH-ALL': '🇨🇭',
                        'AR-BA': '🇦🇷', 'AR-ALL': '🇦🇷',
                        'MT-ALL': '🇲🇹',
                        'Global': '🌍'
                    };
                    const country = marketId?.split('-')[0];
                    return flags[marketId] || flags[country + '-ALL'] || '🏳️';
                }
                // ========================================
                // INITIALIZATION
                // ========================================
                document.addEventListener('DOMContentLoaded', async () => {
                    await loadBrands();
                    setupComparisonModeListener();
                    updateDateRangeDisplay();
                });

                async function loadBrands() {
                    try {
                        const response = await fetch('/.netlify/functions/brands');
                        const data = await response.json();
                        brands = data.data || [];
                        renderBrands();
                    } catch (error) {
                        console.error('Error loading brands:', error);
                        document.getElementById('brands-container').innerHTML = `
                    <div class="col-span-3 text-center py-8 text-red-500">
                        <i class="fa-solid fa-exclamation-circle text-3xl mb-2"></i>
                        <p>Failed to load brands</p>
                    </div>
                `;
                    }
                }

                function renderBrands() {
                    const container = document.getElementById('brands-container');

                    if (brands.length === 0) {
                        container.innerHTML = `
                    <div class="col-span-3 text-center py-8 text-slate-400">
                        <i class="fa-solid fa-building-circle-xmark text-3xl mb-2"></i>
                        <p>No brands found. Import data via D.10 Import Queue first.</p>
                    </div>
                `;
                        return;
                    }

                    // Group brands: single market -> by market name, multi-market -> Global
                    const groups = {};
                    brands.forEach(b => {
                        const marketCount = parseInt(b.market_count) || 0;
                        let groupKey;
                        if (marketCount === 1 && b.markets && b.markets.length > 0) {
                            groupKey = b.markets[0]; // Use the single market as group
                        } else if (marketCount > 1) {
                            groupKey = 'Global';
                        } else {
                            groupKey = 'Unassigned';
                        }
                        if (!groups[groupKey]) groups[groupKey] = [];
                        groups[groupKey].push(b);
                    });

                    // Sort groups: Global first, then alphabetically
                    const sortedKeys = Object.keys(groups).sort((a, b) => {
                        if (a === 'Global') return -1;
                        if (b === 'Global') return 1;
                        if (a === 'Unassigned') return 1;
                        if (b === 'Unassigned') return -1;
                        return a.localeCompare(b);
                    });

                    // Track collapsed state (initialize if not exists)
                    if (!window.brandGroupCollapsed) window.brandGroupCollapsed = {};

                    container.innerHTML = sortedKeys.map(groupKey => {
                        const groupBrands = groups[groupKey];
                        const isCollapsed = window.brandGroupCollapsed[groupKey] || false;
                        const icon = groupKey === 'Global' ? 'fa-globe' : groupKey === 'Unassigned' ? 'fa-question-circle' : 'fa-map-marker-alt';
                        const color = groupKey === 'Global' ? 'from-purple-500 to-indigo-500' : groupKey === 'Unassigned' ? 'from-slate-400 to-slate-500' : 'from-blue-500 to-cyan-500';

                        return `
                    <div class="mb-4">
                        <button onclick="toggleBrandGroup('${groupKey}')" 
                                class="w-full flex items-center justify-between bg-gradient-to-r ${color} text-white px-4 py-2.5 rounded-t-lg font-semibold shadow-sm hover:shadow-md transition-all">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid ${icon}"></i>
                                <span>${groupKey}</span>
                                <span class="bg-white/20 text-white text-xs px-2 py-0.5 rounded-full">${groupBrands.length} brand${groupBrands.length !== 1 ? 's' : ''}</span>
                            </div>
                            <i class="fa-solid fa-chevron-${isCollapsed ? 'down' : 'up'} transition-transform"></i>
                        </button>
                        <div id="brand-group-${groupKey.replace(/[^a-zA-Z0-9]/g, '-')}" class="${isCollapsed ? 'hidden' : ''} border border-t-0 border-slate-200 rounded-b-lg overflow-hidden">
                            <table class="w-full text-sm">
                                <thead class="bg-slate-50 text-xs text-slate-500 uppercase">
                                    <tr>
                                        <th class="py-2 px-4 text-left font-semibold w-8"></th>
                                        <th class="py-2 px-4 text-left font-semibold">Brand</th>
                                        <th class="py-2 px-4 text-left font-semibold">Domain</th>
                                        <th class="py-2 px-3 text-left font-semibold">Markets</th>
                                        <th class="py-2 px-3 text-center font-semibold">Ent</th>
                                        <th class="py-2 px-3 text-center font-semibold">Hits</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${groupBrands.sort((a, b) => (parseInt(b.total_hits) || 0) - (parseInt(a.total_hits) || 0)).map(b => {
                            const markets = b.markets || [];
                            const shown = markets.slice(0, 3);
                            const remaining = markets.slice(3);
                            const flagHtml = shown.map(m => getMarketFlag(m)).join(' ');
                            const moreHtml = remaining.length > 0
                                ? `<span class="bg-slate-200 text-slate-600 px-1.5 py-0.5 rounded text-xs cursor-default" title="${remaining.join('\\n')}">+${remaining.length}</span>`
                                : '';
                            return `
                                    <tr onclick="selectBrand('${b.id}')" 
                                        class="border-b border-slate-100 hover:bg-indigo-50 cursor-pointer transition-colors ${selectedBrand === b.id ? 'bg-indigo-100' : ''}">
                                        <td class="py-2.5 px-4">
                                            <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-500 rounded flex items-center justify-center text-white font-bold text-sm">
                                                ${b.name.charAt(0).toUpperCase()}
                                            </div>
                                        </td>
                                        <td class="py-2.5 px-4 font-medium text-slate-800">${b.name}</td>
                                        <td class="py-2.5 px-4 text-slate-500 text-xs">${b.website || '-'}</td>
                                        <td class="py-2.5 px-3 text-sm">${flagHtml} ${moreHtml}</td>
                                        <td class="py-2.5 px-3 text-center">
                                            <span class="bg-purple-100 text-purple-700 px-2 py-0.5 rounded text-xs font-medium">${b.competitor_count || 0}</span>
                                        </td>
                                        <td class="py-2.5 px-3 text-center">
                                            <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-medium">${(parseInt(b.total_hits) || 0).toLocaleString()}</span>
                                        </td>
                                    </tr>`;
                        }).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>
                        `;
                    }).join('');
                }

                function toggleBrandGroup(groupKey) {
                    if (!window.brandGroupCollapsed) window.brandGroupCollapsed = {};
                    window.brandGroupCollapsed[groupKey] = !window.brandGroupCollapsed[groupKey];
                    renderBrands();
                }

                function selectBrand(brandId) {
                    selectedBrand = brandId;
                    renderBrands();
                    setTimeout(() => goToStep(2), 300);
                }

                // ========================================
                // STEP NAVIGATION
                // ========================================
                function goToStep(step) {
                    // Hide all steps
                    document.getElementById('step-1').classList.add('hidden');
                    document.getElementById('step-2').classList.add('hidden');
                    document.getElementById('step-3').classList.add('hidden');
                    document.getElementById('profile-output').classList.add('hidden');
                    document.getElementById('wizard-progress').classList.remove('hidden');
                    document.getElementById('wizard-actions').classList.add('hidden');

                    // Update indicators - reset all to inactive state
                    for (let i = 1; i <= 3; i++) {
                        const indicator = document.getElementById(`step-${i}-indicator`);
                        indicator.classList.remove('active', 'completed');
                        const stepNum = indicator.querySelector('.step-number');
                        stepNum.classList.remove('bg-indigo-600');
                        stepNum.classList.add('bg-slate-300');
                    }

                    // Mark completed steps (before current)
                    for (let i = 1; i < step; i++) {
                        const indicator = document.getElementById(`step-${i}-indicator`);
                        indicator.classList.add('completed');
                        // CSS handles the completed styling - no need to change step-number bg
                    }

                    // Mark current step as active
                    const currentIndicator = document.getElementById(`step-${step}-indicator`);
                    currentIndicator.classList.add('active');
                    const currentNum = currentIndicator.querySelector('.step-number');
                    currentNum.classList.remove('bg-slate-300');
                    currentNum.classList.add('bg-indigo-600');

                    document.getElementById(`step-${step}`).classList.remove('hidden');

                    // Step-specific logic
                    if (step === 2) {
                        renderReportSelector();
                    } else if (step === 3) {
                        updateDateRangeDisplay();
                    }
                }

                function resetWizard() {
                    selectedBrand = null;
                    selectedReports = [];
                    brandData = null;
                    renderBrands();
                    goToStep(1);
                }

                // ========================================
                // REPORT SELECTION
                // ========================================
                function renderReportSelector() {
                    const container = document.getElementById('reports-container');

                    // Group by module
                    const modules = {};
                    REPORTS.forEach(r => {
                        if (!modules[r.module]) modules[r.module] = [];
                        modules[r.module].push(r);
                    });

                    container.innerHTML = Object.entries(modules).map(([module, reports]) => `
                <div class="border border-slate-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold text-slate-700">${module}</h3>
                        <button onclick="toggleModule('${module}')" class="text-xs text-indigo-600 hover:underline">Toggle All</button>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        ${reports.map(r => `
                            <label class="flex items-center gap-2 p-2 rounded hover:bg-slate-50 cursor-pointer">
                                <input type="checkbox" value="${r.id}" class="report-checkbox text-indigo-600" 
                                       ${selectedReports.includes(r.id) ? 'checked' : ''}
                                       onchange="toggleReport('${r.id}')">
                                <span class="text-sm">
                                    <i class="fa-solid ${r.icon} text-slate-400 mr-1"></i>
                                    ${r.id} ${r.name}
                                </span>
                            </label>
                        `).join('')}
                    </div>
                </div>
            `).join('');
                }

                function toggleReport(reportId) {
                    if (selectedReports.includes(reportId)) {
                        selectedReports = selectedReports.filter(r => r !== reportId);
                    } else {
                        selectedReports.push(reportId);
                    }
                }

                function toggleModule(module) {
                    const moduleReports = REPORTS.filter(r => r.module === module).map(r => r.id);
                    const allSelected = moduleReports.every(id => selectedReports.includes(id));

                    if (allSelected) {
                        selectedReports = selectedReports.filter(r => !moduleReports.includes(r));
                    } else {
                        moduleReports.forEach(id => {
                            if (!selectedReports.includes(id)) selectedReports.push(id);
                        });
                    }
                    renderReportSelector();
                }

                function selectAllReports() {
                    selectedReports = REPORTS.map(r => r.id);
                    renderReportSelector();
                }

                function selectNoneReports() {
                    selectedReports = [];
                    renderReportSelector();
                }

                // ========================================
                // OPTIONS
                // ========================================
                function setupComparisonModeListener() {
                    document.querySelectorAll('input[name="comparison-mode"]').forEach(radio => {
                        radio.addEventListener('change', (e) => {
                            comparisonMode = e.target.value;
                            const benchmarkSelector = document.getElementById('benchmark-selector');
                            benchmarkSelector.classList.toggle('hidden', comparisonMode !== 'vs-benchmark');

                            // Populate benchmark dropdown when vs-benchmark is selected
                            if (comparisonMode === 'vs-benchmark') {
                                populateBenchmarkDropdown();
                            }
                        });
                    });

                    // Listen for benchmark selection
                    document.getElementById('benchmark-competitor').addEventListener('change', (e) => {
                        benchmarkBrand = e.target.value || null;
                    });
                }

                function populateBenchmarkDropdown() {
                    const dropdown = document.getElementById('benchmark-competitor');
                    const availableBrands = brands.filter(b => b.id !== selectedBrand);

                    dropdown.innerHTML = `
                <option value="">Select a benchmark brand...</option>
                ${availableBrands.map(b => `
                    <option value="${b.id}">${b.name} (${b.market_count} markets, ${parseInt(b.total_hits || 0).toLocaleString()} hits)</option>
                `).join('')}
            `;

                    benchmarkBrand = null;
                }

                function updateDateRangeDisplay() {
                    const display = document.getElementById('date-range-display');
                    if (typeof CRMT !== 'undefined' && CRMT.dateRange && CRMT.dateRange.getRange) {
                        const range = CRMT.dateRange.getRange();
                        if (range && range.from && range.to) {
                            display.innerHTML = `<i class="fa-solid fa-calendar mr-2 text-indigo-500"></i> ${range.from} to ${range.to}`;
                        } else {
                            display.innerHTML = '<i class="fa-solid fa-infinity mr-2 text-indigo-500"></i> All time';
                        }
                    } else {
                        display.innerHTML = '<i class="fa-solid fa-infinity mr-2 text-indigo-500"></i> All time (date picker not loaded)';
                    }
                }

                // ========================================
                // GENERATE PROFILE
                // ========================================
                async function generateProfile() {
                    if (!selectedBrand) {
                        alert('Please select a brand first');
                        return;
                    }

                    if (selectedReports.length === 0) {
                        alert('Please select at least one report');
                        return;
                    }

                    // Validate benchmark selection if in vs-benchmark mode
                    if (comparisonMode === 'vs-benchmark' && !benchmarkBrand) {
                        alert('Please select a benchmark brand to compare against');
                        return;
                    }

                    // Show loading
                    document.getElementById('step-3').innerHTML = `
                <div class="text-center py-12">
                    <i class="fa-solid fa-spinner fa-spin text-4xl text-indigo-600 mb-4"></i>
                    <p class="text-lg font-medium text-slate-700">Generating Brand Profile...</p>
                    <p class="text-sm text-slate-500">Aggregating data across ${brands.find(b => b.id === selectedBrand)?.market_count || 0} markets</p>
                </div>
            `;

                    try {
                        // Get date range for filtering
                        let dateParams = '';
                        if (typeof CRMT !== 'undefined' && CRMT.dateRange && CRMT.dateRange.getRange) {
                            const range = CRMT.dateRange.getRange();
                            if (range && range.from && range.to) {
                                dateParams = `&from=${range.from}&to=${range.to}`;
                            }
                        }

                        // Fetch brand data with stats for comparison modes
                        const needStats = comparisonMode === 'vs-average' || comparisonMode === 'vs-benchmark';
                        const response = await fetch(`/.netlify/functions/brands/${selectedBrand}?include_stats=${needStats}${dateParams}`);
                        brandData = await response.json();

                        // Fetch benchmark brand data if in vs-benchmark mode
                        if (comparisonMode === 'vs-benchmark' && benchmarkBrand) {
                            const benchmarkResponse = await fetch(`/.netlify/functions/brands/${benchmarkBrand}?include_stats=true${dateParams}`);
                            benchmarkData = await benchmarkResponse.json();
                        } else {
                            benchmarkData = null;
                        }

                        // Fetch lifecycle stats for Report 1.2 (if report selected)
                        if (selectedReports.includes('1.2')) {
                            try {
                                const lifecycleUrl = `/.netlify/functions/trackings?group_by=lifecycle_stage&brand_id=${selectedBrand}${dateParams.replace('&', '&start_date=').replace('&to=', '&end_date=')}`;
                                const lifecycleResponse = await fetch(lifecycleUrl);
                                const lifecycleData = await lifecycleResponse.json();
                                brandData.lifecycleStats = {};
                                (lifecycleData.data || []).forEach(row => {
                                    brandData.lifecycleStats[row.lifecycle_stage] = row.count;
                                });
                            } catch (e) {
                                console.warn('Lifecycle data not available:', e);
                                brandData.lifecycleStats = null;
                            }
                        }

                        // Fetch lifecycle stats for reports 1.5, 1.6, 1.7 (Acquisition, Retention, Reactivation)
                        if (selectedReports.some(r => ['1.5', '1.6', '1.7'].includes(r))) {
                            try {
                                const url = `/.netlify/functions/hits-analytics?type=lifecycle.stats&brand_id=${selectedBrand}${dateParams}`;
                                const res = await fetch(url);
                                const json = await res.json();
                                brandData.lifecycleData = json.data;
                            } catch (e) {
                                console.warn('Lifecycle stats not available:', e);
                                brandData.lifecycleData = null;
                            }
                        }

                        // Fetch content analytics for content-related reports (2.1, 2.3)
                        if (selectedReports.some(r => ['2.1', '2.3'].includes(r))) {
                            try {
                                const url = `/.netlify/functions/hits-analytics?type=content.stats&brand_id=${selectedBrand}${dateParams}`;
                                const res = await fetch(url);
                                const json = await res.json();
                                brandData.contentStats = json.data;
                            } catch (e) {
                                console.warn('Content stats not available:', e);
                                brandData.contentStats = null;
                            }
                        }

                        // Fetch offer analytics for offer-related reports (2.2)
                        if (selectedReports.includes('2.2')) {
                            try {
                                const url = `/.netlify/functions/hits-analytics?type=offer.stats&brand_id=${selectedBrand}${dateParams}`;
                                const res = await fetch(url);
                                const json = await res.json();
                                brandData.offerStats = json.data;
                            } catch (e) {
                                console.warn('Offer stats not available:', e);
                                brandData.offerStats = null;
                            }
                        }

                        // Fetch compliance analytics for compliance-related reports (3.1, 3.2, 3.3)
                        if (selectedReports.some(r => ['3.1', '3.2', '3.3'].includes(r))) {
                            try {
                                const url = `/.netlify/functions/hits-analytics?type=compliance.stats&brand_id=${selectedBrand}${dateParams}`;
                                const res = await fetch(url);
                                const json = await res.json();
                                brandData.complianceStats = json.data;
                            } catch (e) {
                                console.warn('Compliance stats not available:', e);
                                brandData.complianceStats = null;
                            }
                        }

                        // Fetch product analytics for product-related reports (5.1, 5.2, 5.3)
                        if (selectedReports.some(r => ['5.1', '5.2', '5.3'].includes(r))) {
                            try {
                                const url = `/.netlify/functions/hits-analytics?type=product.stats&brand_id=${selectedBrand}`;
                                const res = await fetch(url);
                                const json = await res.json();
                                brandData.productStats = json.data;
                            } catch (e) {
                                console.warn('Product stats not available:', e);
                                brandData.productStats = null;
                            }
                        }

                        // Fetch license stats for risk/license reports (4.1, 4.3)
                        if (selectedReports.some(r => ['4.1', '4.3'].includes(r))) {
                            try {
                                const url = `/.netlify/functions/licenses?type=licenses.stats&brand_id=${selectedBrand}`;
                                const res = await fetch(url);
                                const json = await res.json();
                                brandData.licenseStats = json.data;
                            } catch (e) {
                                console.warn('License stats not available:', e);
                                brandData.licenseStats = null;
                            }
                        }

                        // Hide wizard, show profile
                        document.getElementById('wizard-progress').classList.add('hidden');
                        document.getElementById('step-3').classList.add('hidden');
                        document.getElementById('profile-output').classList.remove('hidden');
                        document.getElementById('wizard-actions').classList.remove('hidden');

                        // Render profile header with comparison mode indicator
                        const brand = brandData.brand;
                        let modeLabel = '';
                        if (comparisonMode === 'by-market') modeLabel = 'By Market';
                        else if (comparisonMode === 'vs-average') modeLabel = 'Vs. Market Average';
                        else if (comparisonMode === 'vs-benchmark') modeLabel = `Vs. ${benchmarkData?.brand?.name || 'Benchmark'}`;

                        document.getElementById('profile-logo').textContent = brand.name.charAt(0).toUpperCase();
                        document.getElementById('profile-brand-name').textContent = brand.name + ' Profile';
                        document.getElementById('profile-meta').textContent =
                            `Markets: ${brandData.markets.join(', ')} | Total Hits: ${brandData.totals.hits.toLocaleString()} | Mode: ${modeLabel}`;

                        // Render sections
                        renderProfileSections();

                    } catch (error) {
                        console.error('Error generating profile:', error);
                        alert('Failed to generate profile: ' + error.message);
                        goToStep(3);
                    }
                }

                function renderProfileSections() {
                    const container = document.getElementById('profile-sections');

                    container.innerHTML = selectedReports.map(reportId => {
                        const report = REPORTS.find(r => r.id === reportId);
                        if (!report) return '';

                        return `
                    <div class="report-section bg-white rounded-xl shadow-sm overflow-hidden" id="section-${reportId}">
                        <div class="p-4 bg-slate-50 border-b flex items-center justify-between cursor-pointer" 
                             onclick="toggleSection('${reportId}')">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid ${report.icon} text-indigo-600"></i>
                                <span class="font-semibold text-slate-800">${reportId} ${report.name}</span>
                                <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded">${report.module}</span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-slate-400 section-toggle"></i>
                        </div>
                        <div class="section-content p-6">
                            ${renderReportContent(reportId)}
                        </div>
                    </div>
                `;
                    }).join('');
                }

                function renderReportContent(reportId) {
                    const markets = brandData.markets || [];
                    const competitors = brandData.competitors || [];
                    const marketComparison = brandData.marketComparison || {};

                    if (markets.length === 0) {
                        return `<p class="text-slate-500 text-center py-4">No market data available for this brand</p>`;
                    }

                    // ===== REPORT-SPECIFIC RENDERERS =====
                    // Only render reports that exist in the current navigation
                    switch (reportId) {
                        case '1.1':
                            return render1_1(competitors, markets);
                        case '1.2':
                            return render1_2(competitors, markets);
                        case '1.3':
                            return render1_3(competitors, markets);
                        case '1.4':
                            return render1_4(competitors, markets);
                        case '1.5':
                            return render1_5(competitors, markets);
                        case '2.1':
                            return render2_1(competitors, markets);
                        case '2.2':
                            return render2_2(competitors, markets);
                        case '3.1':
                            return render3_1(competitors, markets);
                        case '3.2':
                            return render3_2(competitors, markets);
                        case '3.3':
                            return render3_3(competitors, markets);
                        case '4.1':
                            return render4_1(competitors, markets);
                        case '4.2':
                            return render4_2(competitors, markets);
                        case '4.3':
                            return render4_3(competitors, markets);
                        case '5.1':
                            return render5_1(competitors, markets);
                        case '5.2':
                            return render5_2(competitors, markets);
                        case '5.3':
                            return render5_3(competitors, markets);
                    }

                    // For other reports, use comparison mode-based generic rendering
                    // Build market data for generic comparison tables
                    const marketHits = {};
                    competitors.forEach(c => {
                        marketHits[c.market_id] = parseInt(c.total_hits) || 0;
                    });

                    const headerCols = markets.map(m => `<th class="px-4 py-2 text-center">${m}</th>`).join('');

                    // Helper for delta formatting
                    const formatDelta = (delta) => {
                        if (delta === null || delta === undefined) return '-';
                        const sign = delta >= 0 ? '+' : '';
                        const colorClass = delta >= 0 ? 'text-green-600' : 'text-red-600';
                        return `<span class="${colorClass} font-semibold">${sign}${delta}%</span>`;
                    };

                    // Helper for rank formatting
                    const formatRank = (rank, total, percentile) => {
                        if (!rank || !total) return '-';
                        const badgeColor = percentile >= 75 ? 'bg-green-100 text-green-700'
                            : percentile >= 50 ? 'bg-yellow-100 text-yellow-700'
                                : 'bg-red-100 text-red-700';
                        return `<span class="text-xs ${badgeColor} px-2 py-0.5 rounded">#${rank}/${total}</span> <span class="text-slate-400">(Top ${percentile}%)</span>`;
                    };

                    // ===== BY MARKET MODE =====
                    if (comparisonMode === 'by-market') {
                        return `
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-100">
                                <tr>
                                    <th class="px-4 py-2 text-left">Metric</th>
                                    ${headerCols}
                                    <th class="px-4 py-2 text-center bg-indigo-50">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b">
                                    <td class="px-4 py-3 font-medium">Total Hits</td>
                                    ${markets.map(m => `<td class="px-4 py-3 text-center">${(marketHits[m] || 0).toLocaleString()}</td>`).join('')}
                                    <td class="px-4 py-3 text-center font-bold bg-indigo-50">${brandData.totals.hits.toLocaleString()}</td>
                                </tr>
                                <tr class="border-b bg-slate-50">
                                    <td class="px-4 py-3 font-medium">Competitor Entity</td>
                                    ${markets.map(m => {
                            const comp = competitors.find(c => c.market_id === m);
                            return `<td class="px-4 py-3 text-center text-xs">${comp?.short_name || comp?.name || '-'}</td>`;
                        }).join('')}
                                    <td class="px-4 py-3 text-center bg-indigo-50">${competitors.length}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                `;
                    }

                    // ===== VS MARKET AVERAGE MODE =====
                    if (comparisonMode === 'vs-average') {
                        return `
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-100">
                                <tr>
                                    <th class="px-4 py-2 text-left">Metric</th>
                                    ${headerCols}
                                    <th class="px-4 py-2 text-center bg-indigo-50">Overall</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b">
                                    <td class="px-4 py-3 font-medium"><i class="fa-solid fa-building text-indigo-500 mr-2"></i>Brand Hits</td>
                                    ${markets.map(m => `<td class="px-4 py-3 text-center font-semibold">${(marketComparison[m]?.brandHits || marketHits[m] || 0).toLocaleString()}</td>`).join('')}
                                    <td class="px-4 py-3 text-center font-bold bg-indigo-50">${brandData.totals.hits.toLocaleString()}</td>
                                </tr>
                                <tr class="border-b bg-slate-50">
                                    <td class="px-4 py-3 font-medium"><i class="fa-solid fa-chart-simple text-slate-400 mr-2"></i>Market Average</td>
                                    ${markets.map(m => `<td class="px-4 py-3 text-center text-slate-600">${(marketComparison[m]?.avgHits || 0).toLocaleString()}</td>`).join('')}
                                    <td class="px-4 py-3 text-center bg-indigo-50">-</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="px-4 py-3 font-medium"><i class="fa-solid fa-arrows-up-down text-blue-500 mr-2"></i>Delta vs Avg</td>
                                    ${markets.map(m => `<td class="px-4 py-3 text-center">${formatDelta(marketComparison[m]?.delta)}</td>`).join('')}
                                    <td class="px-4 py-3 text-center bg-indigo-50">-</td>
                                </tr>
                                <tr class="border-b bg-yellow-50">
                                    <td class="px-4 py-3 font-medium"><i class="fa-solid fa-ranking-star text-yellow-500 mr-2"></i>Market Rank</td>
                                    ${markets.map(m => {
                            const stats = marketComparison[m];
                            return `<td class="px-4 py-3 text-center">${formatRank(stats?.brandRank, stats?.totalCompetitors, stats?.percentile)}</td>`;
                        }).join('')}
                                    <td class="px-4 py-3 text-center bg-indigo-50">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="text-xs text-slate-400 mt-4 text-center">
                        <i class="fa-solid fa-info-circle mr-1"></i>
                        Comparison against all active competitors in each market
                    </p>
                `;
                    }

                    // ===== VS BENCHMARK MODE =====
                    if (comparisonMode === 'vs-benchmark') {
                        // Build benchmark hits per market
                        const benchmarkHits = {};
                        if (benchmarkData && benchmarkData.competitors) {
                            benchmarkData.competitors.forEach(c => {
                                benchmarkHits[c.market_id] = c.stats?.hits || 0;
                            });
                        }

                        // Calculate delta per market
                        const calculateDelta = (brandVal, benchmarkVal) => {
                            if (!benchmarkVal || benchmarkVal === 0) return null;
                            return Math.round(((brandVal - benchmarkVal) / benchmarkVal) * 100);
                        };

                        const benchmarkName = benchmarkData?.brand?.name || 'Benchmark';
                        const benchmarkTotalHits = benchmarkData?.totals?.hits || 0;

                        return `
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-100">
                                <tr>
                                    <th class="px-4 py-2 text-left">Metric</th>
                                    ${headerCols}
                                    <th class="px-4 py-2 text-center bg-indigo-50">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b">
                                    <td class="px-4 py-3 font-medium"><i class="fa-solid fa-building text-indigo-500 mr-2"></i>${brandData.brand.name}</td>
                                    ${markets.map(m => `<td class="px-4 py-3 text-center font-semibold">${(marketHits[m] || 0).toLocaleString()}</td>`).join('')}
                                    <td class="px-4 py-3 text-center font-bold bg-indigo-50">${brandData.totals.hits.toLocaleString()}</td>
                                </tr>
                                <tr class="border-b bg-orange-50">
                                    <td class="px-4 py-3 font-medium"><i class="fa-solid fa-crosshairs text-orange-500 mr-2"></i>${benchmarkName}</td>
                                    ${markets.map(m => `<td class="px-4 py-3 text-center">${(benchmarkHits[m] || 0).toLocaleString()}</td>`).join('')}
                                    <td class="px-4 py-3 text-center font-bold bg-indigo-50">${benchmarkTotalHits.toLocaleString()}</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="px-4 py-3 font-medium"><i class="fa-solid fa-arrows-up-down text-blue-500 mr-2"></i>Delta</td>
                                    ${markets.map(m => {
                            const delta = calculateDelta(marketHits[m] || 0, benchmarkHits[m] || 0);
                            return `<td class="px-4 py-3 text-center">${formatDelta(delta)}</td>`;
                        }).join('')}
                                    <td class="px-4 py-3 text-center bg-indigo-50">${formatDelta(calculateDelta(brandData.totals.hits, benchmarkTotalHits))}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="text-xs text-slate-400 mt-4 text-center">
                        <i class="fa-solid fa-info-circle mr-1"></i>
                        Direct comparison: ${brandData.brand.name} vs ${benchmarkName}
                    </p>
                `;
                    }

                    // Fallback for other reports not handled above
                    return renderGenericReport(reportId, competitors, markets);
                }

                // ========================================
                // REPORT-SPECIFIC RENDERERS
                // ========================================

                // 1.1 Channel Mechanics
                function render1_1(competitors, markets) {
                    const marketData = {};
                    markets.forEach(m => {
                        const comp = competitors.find(c => c.market_id === m);
                        marketData[m] = {
                            email: parseInt(comp?.email_count) || 0,
                            sms: parseInt(comp?.sms_count) || 0,
                            push: parseInt(comp?.push_count) || 0,
                            call: parseInt(comp?.call_count) || 0,
                            total: parseInt(comp?.total_hits) || 0
                        };
                    });
                    const totals = { email: 0, sms: 0, push: 0, call: 0, total: 0 };
                    Object.values(marketData).forEach(d => {
                        totals.email += d.email;
                        totals.sms += d.sms;
                        totals.push += d.push;
                        totals.call += d.call;
                        totals.total += d.total;
                    });
                    const headerCols = markets.map(m => `<th class="px-3 py-2 text-center text-xs">${m}</th>`).join('');
                    return `
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Channel</th>
                                ${headerCols}
                                <th class="px-4 py-2 text-center bg-indigo-50 font-bold">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b">
                                <td class="px-4 py-3 font-medium"><i class="fa-solid fa-envelope text-blue-500 mr-2"></i>Email</td>
                                ${markets.map(m => `<td class="px-3 py-3 text-center"><span class="font-semibold text-blue-600">${marketData[m].email}</span></td>`).join('')}
                                <td class="px-4 py-3 text-center font-bold bg-indigo-50">${totals.email}</td>
                            </tr>
                            <tr class="border-b bg-slate-50">
                                <td class="px-4 py-3 font-medium"><i class="fa-solid fa-comment-sms text-purple-500 mr-2"></i>SMS</td>
                                ${markets.map(m => `<td class="px-3 py-3 text-center"><span class="font-semibold text-purple-600">${marketData[m].sms}</span></td>`).join('')}
                                <td class="px-4 py-3 text-center font-bold bg-indigo-50">${totals.sms}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="px-4 py-3 font-medium"><i class="fa-solid fa-bell text-amber-500 mr-2"></i>Push</td>
                                ${markets.map(m => `<td class="px-3 py-3 text-center"><span class="font-semibold text-amber-600">${marketData[m].push}</span></td>`).join('')}
                                <td class="px-4 py-3 text-center font-bold bg-indigo-50">${totals.push}</td>
                            </tr>
                            <tr class="border-b bg-slate-50">
                                <td class="px-4 py-3 font-medium"><i class="fa-solid fa-phone text-cyan-500 mr-2"></i>Calls</td>
                                ${markets.map(m => `<td class="px-3 py-3 text-center"><span class="font-semibold text-cyan-600">${marketData[m].call}</span></td>`).join('')}
                                <td class="px-4 py-3 text-center font-bold bg-indigo-50">${totals.call}</td>
                            </tr>
                            <tr class="bg-indigo-100 border-t-2 border-indigo-200">
                                <td class="px-4 py-3 font-bold">Total</td>
                                ${markets.map(m => `<td class="px-3 py-3 text-center font-bold">${marketData[m].total}</td>`).join('')}
                                <td class="px-4 py-3 text-center font-bold text-lg bg-indigo-200">${totals.total}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            `;
                }

                // 1.2 Customer Journey
                function render1_2(competitors, markets) {
                    const lifecycleStats = brandData.lifecycleStats || {};
                    const acquisition = lifecycleStats['acquisition'] || lifecycleStats['Acquisition'] || 0;
                    const retention = lifecycleStats['retention'] || lifecycleStats['Retention'] || 0;
                    const reactivation = lifecycleStats['reactivation'] || lifecycleStats['Reactivation'] || 0;
                    const total = acquisition + retention + reactivation;

                    const hasData = total > 0;

                    return `
                <div class="overflow-x-auto">
                    <p class="text-sm text-slate-500 mb-4">Customer journey lifecycle stages for this brand</p>
                    <table class="w-full text-sm">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Stage</th>
                                <th class="px-4 py-2 text-center">Count</th>
                                <th class="px-4 py-2 text-center">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b">
                                <td class="px-4 py-3 font-medium"><i class="fa-solid fa-bullseye text-green-500 mr-2"></i>Acquisition</td>
                                <td class="px-4 py-3 text-center font-semibold">${acquisition}</td>
                                <td class="px-4 py-3 text-center">${total > 0 ? Math.round(acquisition / total * 100) : 0}%</td>
                            </tr>
                            <tr class="border-b bg-slate-50">
                                <td class="px-4 py-3 font-medium"><i class="fa-solid fa-heart text-pink-500 mr-2"></i>Retention</td>
                                <td class="px-4 py-3 text-center font-semibold">${retention}</td>
                                <td class="px-4 py-3 text-center">${total > 0 ? Math.round(retention / total * 100) : 0}%</td>
                            </tr>
                            <tr class="border-b">
                                <td class="px-4 py-3 font-medium"><i class="fa-solid fa-rotate text-orange-500 mr-2"></i>Reactivation</td>
                                <td class="px-4 py-3 text-center font-semibold">${reactivation}</td>
                                <td class="px-4 py-3 text-center">${total > 0 ? Math.round(reactivation / total * 100) : 0}%</td>
                            </tr>
                            <tr class="bg-indigo-100 border-t-2 border-indigo-200">
                                <td class="px-4 py-3 font-bold">Total</td>
                                <td class="px-4 py-3 text-center font-bold text-lg">${total}</td>
                                <td class="px-4 py-3 text-center font-bold">100%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                ${!hasData ? `
                <p class="text-xs text-slate-400 mt-4 text-center">
                    <i class="fa-solid fa-info-circle mr-1"></i>
                    No lifecycle stage data available. Configure tracking lifecycles in D.8 Tracking Manager.
                </p>
                ` : ''}
            `;
                }

                // 1.3 Hit Frequency
                function render1_3(competitors, markets) {
                    const marketData = {};
                    let totalHits = 0;

                    // Calculate days from actual date range
                    let DAYS = 365; // default
                    let periodLabel = 'Year';
                    if (typeof CRMT !== 'undefined' && CRMT.dateRange && CRMT.dateRange.getRange) {
                        const range = CRMT.dateRange.getRange();
                        if (range && range.from && range.to) {
                            const fromDate = new Date(range.from);
                            const toDate = new Date(range.to);
                            DAYS = Math.max(1, Math.round((toDate - fromDate) / (1000 * 60 * 60 * 24)));
                            periodLabel = `${DAYS} days`;
                        }
                    }
                    const WEEKS = Math.max(1, Math.round(DAYS / 7));

                    markets.forEach(m => {
                        const comp = competitors.find(c => c.market_id === m);
                        const hits = parseInt(comp?.total_hits) || 0;
                        marketData[m] = { hits, daily: (hits / DAYS).toFixed(2), weekly: (hits / WEEKS).toFixed(1) };
                        totalHits += hits;
                    });
                    const headerCols = markets.map(m => `<th class="px-3 py-2 text-center text-xs">${m}</th>`).join('');
                    return `
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Frequency</th>
                                ${headerCols}
                                <th class="px-4 py-2 text-center bg-indigo-50">Avg</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b">
                                <td class="px-4 py-3 font-medium"><i class="fa-solid fa-calendar-day text-blue-500 mr-2"></i>Daily Avg</td>
                                ${markets.map(m => `<td class="px-3 py-3 text-center">${marketData[m].daily}</td>`).join('')}
                                <td class="px-4 py-3 text-center font-bold bg-indigo-50">${(totalHits / DAYS / markets.length).toFixed(2)}</td>
                            </tr>
                            <tr class="border-b bg-slate-50">
                                <td class="px-4 py-3 font-medium"><i class="fa-solid fa-calendar-week text-purple-500 mr-2"></i>Weekly Avg</td>
                                ${markets.map(m => `<td class="px-3 py-3 text-center">${marketData[m].weekly}</td>`).join('')}
                                <td class="px-4 py-3 text-center font-bold bg-indigo-50">${(totalHits / WEEKS / markets.length).toFixed(1)}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="px-4 py-3 font-medium"><i class="fa-solid fa-hashtag text-slate-500 mr-2"></i>Total Hits</td>
                                ${markets.map(m => `<td class="px-3 py-3 text-center font-semibold">${marketData[m].hits}</td>`).join('')}
                                <td class="px-4 py-3 text-center font-bold bg-indigo-50">${totalHits}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            `;
                }

                // 1.10 Analytics Dashboard
                function render1_10(competitors, markets) {
                    const totalHits = competitors.reduce((sum, c) => sum + (parseInt(c.total_hits) || 0), 0);
                    const totalEmail = competitors.reduce((sum, c) => sum + (parseInt(c.email_count) || 0), 0);
                    const totalSms = competitors.reduce((sum, c) => sum + (parseInt(c.sms_count) || 0), 0);
                    const totalPush = competitors.reduce((sum, c) => sum + (parseInt(c.push_count) || 0), 0);
                    const totalCall = competitors.reduce((sum, c) => sum + (parseInt(c.call_count) || 0), 0);

                    const avgPerMarket = markets.length > 0 ? Math.round(totalHits / markets.length) : 0;
                    const maxMarket = competitors.reduce((max, c) =>
                        (parseInt(c.total_hits) || 0) > (parseInt(max?.total_hits) || 0) ? c : max, competitors[0]);

                    return `
                <div class="grid grid-cols-4 gap-4 mb-6">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 text-white">
                        <p class="text-blue-100 text-sm">Total Hits</p>
                        <p class="text-2xl font-bold">${totalHits.toLocaleString()}</p>
                    </div>
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-4 text-white">
                        <p class="text-green-100 text-sm">Avg/Market</p>
                        <p class="text-2xl font-bold">${avgPerMarket.toLocaleString()}</p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-4 text-white">
                        <p class="text-purple-100 text-sm">Markets</p>
                        <p class="text-2xl font-bold">${markets.length}</p>
                    </div>
                    <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl p-4 text-white">
                        <p class="text-amber-100 text-sm">Top Market</p>
                        <p class="text-2xl font-bold">${maxMarket?.market_id || '-'}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div class="bg-slate-50 rounded-lg p-4">
                        <h4 class="font-semibold mb-3"><i class="fa-solid fa-chart-pie text-indigo-500 mr-2"></i>Channel Split</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between"><span>Email</span><span class="font-semibold">${totalEmail.toLocaleString()} <span class="text-slate-400">(${totalHits > 0 ? Math.round(totalEmail / totalHits * 100) : 0}%)</span></span></div>
                            <div class="flex justify-between"><span>SMS</span><span class="font-semibold">${totalSms.toLocaleString()} <span class="text-slate-400">(${totalHits > 0 ? Math.round(totalSms / totalHits * 100) : 0}%)</span></span></div>
                            <div class="flex justify-between"><span>Push</span><span class="font-semibold">${totalPush.toLocaleString()} <span class="text-slate-400">(${totalHits > 0 ? Math.round(totalPush / totalHits * 100) : 0}%)</span></span></div>
                            <div class="flex justify-between"><span>Call</span><span class="font-semibold">${totalCall.toLocaleString()} <span class="text-slate-400">(${totalHits > 0 ? Math.round(totalCall / totalHits * 100) : 0}%)</span></span></div>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4">
                        <h4 class="font-semibold mb-3"><i class="fa-solid fa-ranking-star text-indigo-500 mr-2"></i>Market Rankings</h4>
                        <div class="space-y-2">
                            ${competitors.slice().sort((a, b) => (parseInt(b.total_hits) || 0) - (parseInt(a.total_hits) || 0)).slice(0, 4).map((c, i) => `
                            <div class="flex justify-between">
                                <span><span class="text-slate-400">#${i + 1}</span> ${c.market_id}</span>
                                <span class="font-semibold">${(parseInt(c.total_hits) || 0).toLocaleString()}</span>
                            </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            `;
                }

                // 2.1 Content Analysis
                function render2_1(competitors, markets) {
                    const cs = brandData.contentStats || {};
                    const totalHits = cs.total_hits || competitors.reduce((sum, c) => sum + (parseInt(c.total_hits) || 0), 0);
                    const subjectData = cs.subject || {};
                    const personalization = cs.personalization || {};
                    const images = cs.images || {};

                    return `
                <div class="text-center py-6">
                    <div class="inline-flex items-center gap-4 mb-6">
                        <div class="bg-blue-100 rounded-full p-4">
                            <i class="fa-solid fa-file-lines text-3xl text-blue-600"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-3xl font-bold text-slate-800">${totalHits.toLocaleString()}</p>
                            <p class="text-slate-500">Total communications analyzed</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-slate-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-envelope-open-text text-2xl text-indigo-500 mb-2"></i>
                        <p class="text-xl font-bold">${subjectData.with_subject || 0}</p>
                        <p class="text-sm text-slate-500">With Subject</p>
                        <p class="text-xs text-slate-400 mt-1">Avg: ${subjectData.avg_length || 0} chars</p>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-user-tag text-2xl text-purple-500 mb-2"></i>
                        <p class="text-xl font-bold">${personalization.rate || 0}%</p>
                        <p class="text-sm text-slate-500">Personalized</p>
                        <p class="text-xs text-slate-400 mt-1">${personalization.count || 0} emails</p>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-images text-2xl text-emerald-500 mb-2"></i>
                        <p class="text-xl font-bold">${images.avg_count || 0}</p>
                        <p class="text-sm text-slate-500">Avg Images</p>
                    </div>
                </div>
                <div class="mt-4 bg-slate-50 rounded-lg p-4">
                    <h4 class="font-semibold mb-3"><i class="fa-solid fa-ruler text-indigo-500 mr-2"></i>Subject Length Distribution</h4>
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div><p class="text-lg font-bold">${subjectData.short || 0}</p><p class="text-xs text-slate-500">&lt;30 chars</p></div>
                        <div><p class="text-lg font-bold">${subjectData.medium || 0}</p><p class="text-xs text-slate-500">30-60 chars</p></div>
                        <div><p class="text-lg font-bold">${subjectData.long || 0}</p><p class="text-xs text-slate-500">&gt;60 chars</p></div>
                    </div>
                </div>
            `;
                }

                // 2.2 Offer Strategy
                function render2_2(competitors, markets) {
                    const os = brandData.offerStats || {};
                    const total = os.total_hits || 1;
                    const promoTypes = os.promotion_types || [];
                    const values = os.values || {};
                    const bonusCodes = os.bonus_codes || {};
                    const wagering = os.wagering || {};

                    const hasData = promoTypes.length > 0;

                    return `
                <div class="grid grid-cols-4 gap-4 mb-6">
                    <div class="bg-indigo-50 rounded-lg p-4 text-center">
                        <p class="text-2xl font-bold text-indigo-600">${total.toLocaleString()}</p>
                        <p class="text-sm text-indigo-500">Total Offers</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4 text-center">
                        <p class="text-2xl font-bold text-green-600">${values.with_value_pct || 0}%</p>
                        <p class="text-sm text-green-500">With Value</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4 text-center">
                        <p class="text-2xl font-bold text-purple-600">${bonusCodes.rate || 0}%</p>
                        <p class="text-sm text-purple-500">With Bonus Code</p>
                    </div>
                    <div class="bg-amber-50 rounded-lg p-4 text-center">
                        <p class="text-2xl font-bold text-amber-600">${wagering.disclosure_rate || 0}%</p>
                        <p class="text-sm text-amber-500">Wagering Disclosed</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Promotion Type</th>
                                <th class="px-4 py-2 text-center">Count</th>
                                <th class="px-4 py-2 text-center">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${hasData ? promoTypes.slice(0, 6).map((p, i) => `
                            <tr class="border-b ${i % 2 ? 'bg-slate-50' : ''}">
                                <td class="px-4 py-3"><i class="fa-solid fa-tag text-indigo-400 mr-2"></i>${p.promotion_type}</td>
                                <td class="px-4 py-3 text-center font-semibold">${p.count}</td>
                                <td class="px-4 py-3 text-center">${Math.round(p.count / total * 100)}%</td>
                            </tr>
                            `).join('') : `
                            <tr><td colspan="3" class="px-4 py-6 text-center text-slate-400">No promotion type data available</td></tr>
                            `}
                        </tbody>
                    </table>
                </div>
            `;
                }

                // 2.3 Subject Lines
                function render2_3(competitors, markets) {
                    const cs = brandData.contentStats || {};
                    const subject = cs.subject || {};
                    const personalization = cs.personalization || {};
                    const totalWithSubject = subject.with_subject || 0;

                    const short = subject.short || 0;
                    const medium = subject.medium || 0;
                    const long = subject.long || 0;
                    const totalSubjects = short + medium + long || 1;

                    return `
                <div class="grid grid-cols-4 gap-4 mb-6">
                    <div class="border border-blue-200 bg-blue-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-envelope-open text-2xl text-blue-600 mb-2"></i>
                        <p class="text-xl font-bold text-blue-700">${totalWithSubject.toLocaleString()}</p>
                        <p class="text-sm text-blue-600">With Subject</p>
                    </div>
                    <div class="border border-purple-200 bg-purple-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-ruler text-2xl text-purple-600 mb-2"></i>
                        <p class="text-xl font-bold text-purple-700">${subject.avg_length || 0}</p>
                        <p class="text-sm text-purple-600">Avg Characters</p>
                    </div>
                    <div class="border border-green-200 bg-green-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-user-tag text-2xl text-green-600 mb-2"></i>
                        <p class="text-xl font-bold text-green-700">${personalization.rate || 0}%</p>
                        <p class="text-sm text-green-600">Personalized</p>
                    </div>
                    <div class="border border-amber-200 bg-amber-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-signature text-2xl text-amber-600 mb-2"></i>
                        <p class="text-xl font-bold text-amber-700">${personalization.count || 0}</p>
                        <p class="text-sm text-amber-600">Using First Name</p>
                    </div>
                </div>
                <div class="bg-slate-50 rounded-lg p-4 mb-4">
                    <h4 class="font-semibold mb-4"><i class="fa-solid fa-chart-bar text-indigo-500 mr-2"></i>Subject Length Distribution</h4>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <span class="w-24 text-sm text-slate-600">Short (&lt;30)</span>
                            <div class="flex-1 h-4 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full bg-green-500 rounded-full" style="width: ${Math.round(short / totalSubjects * 100)}%"></div>
                            </div>
                            <span class="w-12 text-right text-sm font-medium">${short}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-24 text-sm text-slate-600">Medium</span>
                            <div class="flex-1 h-4 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500 rounded-full" style="width: ${Math.round(medium / totalSubjects * 100)}%"></div>
                            </div>
                            <span class="w-12 text-right text-sm font-medium">${medium}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-24 text-sm text-slate-600">Long (&gt;60)</span>
                            <div class="flex-1 h-4 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full bg-purple-500 rounded-full" style="width: ${Math.round(long / totalSubjects * 100)}%"></div>
                            </div>
                            <span class="w-12 text-right text-sm font-medium">${long}</span>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-slate-400 text-center">
                    <i class="fa-solid fa-info-circle mr-1"></i>
                    Subject line analysis based on ${totalWithSubject.toLocaleString()} emails with subjects
                </p>
            `;
                }

                // 3.1 Compliance Overview
                function render3_1(competitors, markets) {
                    const cs = brandData.complianceStats || {};
                    const disclaimer = cs.legal_disclaimer || {};
                    const rg = cs.rg_messaging || {};
                    const wagering = cs.wagering_disclosure || {};
                    const cashout = cs.cashout_disclosure || {};
                    const deposit = cs.deposit_disclosure || {};
                    const completeness = cs.completeness || {};

                    return `
                <div class="grid grid-cols-4 gap-4 mb-6">
                    <div class="border border-blue-200 bg-blue-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-file-contract text-2xl text-blue-600 mb-2"></i>
                        <p class="text-xl font-bold text-blue-700">${disclaimer.rate || 0}%</p>
                        <p class="text-sm text-blue-600">Legal Disclaimer</p>
                    </div>
                    <div class="border border-green-200 bg-green-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-dice text-2xl text-green-600 mb-2"></i>
                        <p class="text-xl font-bold text-green-700">${wagering.rate || 0}%</p>
                        <p class="text-sm text-green-600">Wagering Disclosed</p>
                    </div>
                    <div class="border border-amber-200 bg-amber-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-coins text-2xl text-amber-600 mb-2"></i>
                        <p class="text-xl font-bold text-amber-700">${cashout.rate || 0}%</p>
                        <p class="text-sm text-amber-600">Cashout Disclosed</p>
                    </div>
                    <div class="border border-purple-200 bg-purple-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-chart-simple text-2xl text-purple-600 mb-2"></i>
                        <p class="text-xl font-bold text-purple-700">${completeness.average || 0}%</p>
                        <p class="text-sm text-purple-600">Avg Completeness</p>
                    </div>
                </div>
                <div class="bg-slate-50 rounded-lg p-4">
                    <h4 class="font-semibold mb-3"><i class="fa-solid fa-list-check text-indigo-500 mr-2"></i>Data Completeness</h4>
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div class="bg-green-100 rounded p-3">
                            <p class="text-xl font-bold text-green-700">${completeness.high || 0}</p>
                            <p class="text-xs text-green-600">Complete (&gt;80%)</p>
                        </div>
                        <div class="bg-amber-100 rounded p-3">
                            <p class="text-xl font-bold text-amber-700">${completeness.partial || 0}</p>
                            <p class="text-xs text-amber-600">Partial (40-80%)</p>
                        </div>
                        <div class="bg-red-100 rounded p-3">
                            <p class="text-xl font-bold text-red-700">${completeness.low || 0}</p>
                            <p class="text-xs text-red-600">Incomplete (&lt;40%)</p>
                        </div>
                    </div>
                </div>
            `;
                }

                // 3.2 GDPR Readiness
                function render3_2(competitors, markets) {
                    const euMarkets = markets.filter(m => ['ES', 'PT', 'IT', 'FR', 'DE', 'NL', 'BE', 'AT', 'GR', 'MT', 'IE', 'UK'].some(c => m.includes(c)));
                    return `
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div class="bg-slate-50 rounded-lg p-4">
                        <h4 class="font-semibold mb-3"><i class="fa-solid fa-globe-europe text-blue-500 mr-2"></i>EU Markets</h4>
                        <p class="text-3xl font-bold text-blue-600">${euMarkets.length} <span class="text-lg text-slate-400">/ ${markets.length}</span></p>
                        <p class="text-sm text-slate-500 mt-1">Markets in GDPR scope</p>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4">
                        <h4 class="font-semibold mb-3"><i class="fa-solid fa-shield-halved text-green-500 mr-2"></i>Privacy Score</h4>
                        <p class="text-3xl font-bold text-slate-400">-</p>
                        <p class="text-sm text-slate-500 mt-1">Not yet assessed</p>
                    </div>
                </div>
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <h4 class="font-semibold text-amber-800 mb-2"><i class="fa-solid fa-lock mr-2"></i>GDPR Requirements</h4>
                    <ul class="text-sm text-amber-700 space-y-1">
                        <li><i class="fa-regular fa-square mr-2"></i>Consent mechanism verification</li>
                        <li><i class="fa-regular fa-square mr-2"></i>Data retention policy check</li>
                        <li><i class="fa-regular fa-square mr-2"></i>Right to erasure compliance</li>
                        <li><i class="fa-regular fa-square mr-2"></i>Privacy notice accessibility</li>
                    </ul>
                </div>
            `;
                }

                // 3.3 Responsible Gaming
                function render3_3(competitors, markets) {
                    const cs = brandData.complianceStats || {};
                    const disclaimer = cs.legal_disclaimer || {};
                    const rg = cs.rg_messaging || {};
                    const wagering = cs.wagering_disclosure || {};
                    const completeness = cs.completeness || {};

                    return `
                <div class="grid grid-cols-4 gap-4 mb-6">
                    <div class="border border-green-200 bg-green-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-check text-xl text-green-600 mb-2"></i>
                        <p class="text-lg font-bold text-green-700">${rg.present || 0}</p>
                        <p class="text-xs text-green-600">RG Messaging</p>
                    </div>
                    <div class="border border-blue-200 bg-blue-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-dice text-xl text-blue-600 mb-2"></i>
                        <p class="text-lg font-bold text-blue-700">${wagering.rate || 0}%</p>
                        <p class="text-xs text-blue-600">Wagering Shown</p>
                    </div>
                    <div class="border border-amber-200 bg-amber-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-hand text-xl text-amber-600 mb-2"></i>
                        <p class="text-lg font-bold text-amber-700">${completeness.high || 0}</p>
                        <p class="text-xs text-amber-600">Fully Compliant</p>
                    </div>
                    <div class="border border-purple-200 bg-purple-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-info text-xl text-purple-600 mb-2"></i>
                        <p class="text-lg font-bold text-purple-700">${completeness.average || 0}%</p>
                        <p class="text-xs text-purple-600">Avg Compliance</p>
                    </div>
                </div>
                <div class="bg-slate-50 rounded-lg p-4">
                    <h4 class="font-semibold mb-3"><i class="fa-solid fa-shield-check text-indigo-500 mr-2"></i>RG Compliance Markers</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-${disclaimer.rate > 0 ? 'check text-green-500' : 'xmark text-slate-300'}"></i>
                                <span class="text-sm">Legal disclaimer visibility</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-${wagering.rate > 0 ? 'check text-green-500' : 'xmark text-slate-300'}"></i>
                                <span class="text-sm">Wagering requirements disclosed</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-xmark text-slate-300"></i>
                                <span class="text-sm text-slate-400">Self-exclusion links (not tracked)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-xmark text-slate-300"></i>
                                <span class="text-sm text-slate-400">Help resources (not tracked)</span>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-slate-400 mt-4 text-center">
                    <i class="fa-solid fa-info-circle mr-1"></i>
                    Responsible gaming compliance based on email content analysis
                </p>
            `;
                }

                // 4.1 Regulatory Risk
                function render4_1(competitors, markets) {
                    const ls = brandData.licenseStats || {};
                    const summary = ls.summary || {};
                    const byMarket = ls.by_market || {};
                    const riskScore = ls.risk_score ?? 100;

                    const riskLabel = riskScore <= 30 ? 'Low Risk' : riskScore <= 60 ? 'Medium Risk' : 'High Risk';
                    const riskColor = riskScore <= 30 ? 'green' : riskScore <= 60 ? 'amber' : 'red';
                    const riskBg = riskScore <= 30 ? 'bg-green-100 text-green-700' : riskScore <= 60 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700';

                    const marketKeys = Object.keys(byMarket);
                    const hasData = marketKeys.length > 0;

                    return `
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-semibold"><i class="fa-solid fa-gauge-high text-indigo-500 mr-2"></i>Risk Assessment</h4>
                        <span class="${riskBg} px-3 py-1 rounded-full text-sm font-medium">${hasData ? riskLabel : 'Not Assessed'}</span>
                    </div>
                    <div class="h-4 bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-green-500 via-amber-500 to-red-500" style="width: ${hasData ? riskScore : 0}%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-slate-400 mt-1">
                        <span>Low Risk</span>
                        <span>Medium</span>
                        <span>High Risk</span>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-4 mb-4">
                    <div class="bg-green-50 rounded-lg p-3 text-center">
                        <p class="text-xl font-bold text-green-700">${summary.active || 0}</p>
                        <p class="text-xs text-green-600">Active Licenses</p>
                    </div>
                    <div class="bg-amber-50 rounded-lg p-3 text-center">
                        <p class="text-xl font-bold text-amber-700">${summary.pending || 0}</p>
                        <p class="text-xs text-amber-600">Pending</p>
                    </div>
                    <div class="bg-red-50 rounded-lg p-3 text-center">
                        <p class="text-xl font-bold text-red-700">${summary.grey || 0}</p>
                        <p class="text-xs text-red-600">Grey Market</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-3 text-center">
                        <p class="text-xl font-bold text-blue-700">${(summary.regulators || []).length}</p>
                        <p class="text-xs text-blue-600">Regulators</p>
                    </div>
                </div>
                ${!hasData ? '<p class="text-slate-400 text-center py-4">No license data available for this brand</p>' : `
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Market</th>
                                <th class="px-4 py-2 text-center">Status</th>
                                <th class="px-4 py-2 text-center">Regulator</th>
                                <th class="px-4 py-2 text-center">Risk Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${marketKeys.map(m => {
                        const lic = byMarket[m];
                        const status = lic.status || 'unknown';
                        const statusBadge = status === 'active' ? 'bg-green-100 text-green-700' :
                            status === 'pending' ? 'bg-amber-100 text-amber-700' :
                                status === 'grey' ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700';
                        const riskLevel = status === 'active' ? '🟢 Low' : status === 'pending' ? '🟡 Medium' : '🔴 High';
                        return `
                            <tr class="border-b hover:bg-slate-50">
                                <td class="px-4 py-3 font-medium"><i class="fa-solid fa-map-marker-alt text-blue-500 mr-2"></i>${m}</td>
                                <td class="px-4 py-3 text-center"><span class="${statusBadge} px-2 py-1 rounded text-xs font-medium">${status.charAt(0).toUpperCase() + status.slice(1)}</span></td>
                                <td class="px-4 py-3 text-center">${lic.regulator || '-'}</td>
                                <td class="px-4 py-3 text-center text-sm">${riskLevel}</td>
                            </tr>`;
                    }).join('')}
                        </tbody>
                    </table>
                </div>
                `}
            `;
                }

                // 1.4 Product Matrix
                function render1_4(competitors, markets) {
                    const products = ['Casino', 'Sports', 'Poker', 'Bingo', 'Lottery'];
                    return `
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Product</th>
                                ${markets.map(m => `<th class="px-3 py-2 text-center text-xs">${m}</th>`).join('')}
                                <th class="px-4 py-2 text-center bg-indigo-50">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${products.map((p, i) => `
                            <tr class="border-b ${i % 2 ? 'bg-slate-50' : ''}">
                                <td class="px-4 py-3 font-medium">${p}</td>
                                ${markets.map(m => `<td class="px-3 py-3 text-center text-slate-400">-</td>`).join('')}
                                <td class="px-4 py-3 text-center bg-indigo-50 text-slate-400">-</td>
                            </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
                <p class="text-xs text-slate-400 mt-4 text-center">
                    <i class="fa-solid fa-info-circle mr-1"></i>
                    Product classification requires content tagging setup
                </p>
            `;
                }

                // 1.5 Acquisition Timeline
                function render1_5(competitors, markets) {
                    const acqCount = brandData.lifecycleStats?.acquisition || brandData.lifecycleStats?.Acquisition || 0;
                    return `
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-bullseye text-2xl text-green-600 mb-2"></i>
                        <p class="text-2xl font-bold text-green-700">${acqCount}</p>
                        <p class="text-sm text-green-600">Acquisition Hits</p>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-clock text-2xl text-slate-400 mb-2"></i>
                        <p class="text-2xl font-bold text-slate-600">-</p>
                        <p class="text-sm text-slate-500">Avg. Days to Convert</p>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-arrow-trend-up text-2xl text-slate-400 mb-2"></i>
                        <p class="text-2xl font-bold text-slate-600">-</p>
                        <p class="text-sm text-slate-500">Conversion Rate</p>
                    </div>
                </div>
                <p class="text-xs text-slate-400 text-center">
                    Timeline analysis requires tracking start/end dates
                </p>
            `;
                }

                // 1.6 Retention Pulse
                function render1_6(competitors, markets) {
                    const retCount = brandData.lifecycleStats?.retention || brandData.lifecycleStats?.Retention || 0;
                    return `
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-pink-50 border border-pink-200 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-heart text-2xl text-pink-600 mb-2"></i>
                        <p class="text-2xl font-bold text-pink-700">${retCount}</p>
                        <p class="text-sm text-pink-600">Retention Hits</p>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-repeat text-2xl text-slate-400 mb-2"></i>
                        <p class="text-2xl font-bold text-slate-600">-</p>
                        <p class="text-sm text-slate-500">Avg. Frequency</p>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-user-check text-2xl text-slate-400 mb-2"></i>
                        <p class="text-2xl font-bold text-slate-600">-</p>
                        <p class="text-sm text-slate-500">Retention Rate</p>
                    </div>
                </div>
                <p class="text-xs text-slate-400 text-center">
                    Retention metrics require lifecycle tracking configuration
                </p>
            `;
                }

                // 1.7 Reactivation Analysis
                function render1_7(competitors, markets) {
                    const reactCount = brandData.lifecycleStats?.reactivation || brandData.lifecycleStats?.Reactivation || 0;
                    return `
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-rotate text-2xl text-orange-600 mb-2"></i>
                        <p class="text-2xl font-bold text-orange-700">${reactCount}</p>
                        <p class="text-sm text-orange-600">Reactivation Hits</p>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-hourglass text-2xl text-slate-400 mb-2"></i>
                        <p class="text-2xl font-bold text-slate-600">-</p>
                        <p class="text-sm text-slate-500">Avg. Dormancy (days)</p>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-arrow-rotate-left text-2xl text-slate-400 mb-2"></i>
                        <p class="text-2xl font-bold text-slate-600">-</p>
                        <p class="text-sm text-slate-500">Win-back Rate</p>
                    </div>
                </div>
                <p class="text-xs text-slate-400 text-center">
                    Reactivation metrics require dormancy tracking
                </p>
            `;
                }

                // 1.8 Seasonal Patterns
                function render1_8(competitors, markets) {
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    return `
                <div class="mb-6">
                    <h4 class="font-semibold mb-3"><i class="fa-solid fa-calendar-days text-indigo-500 mr-2"></i>Monthly Distribution</h4>
                    <div class="grid grid-cols-12 gap-1 h-24">
                        ${months.map((m, i) => `
                        <div class="flex flex-col items-center justify-end">
                            <div class="w-full bg-slate-200 rounded-t" style="height: ${10 + Math.random() * 70}%"></div>
                            <span class="text-xs text-slate-400 mt-1">${m}</span>
                        </div>
                        `).join('')}
                    </div>
                </div>
                <p class="text-xs text-slate-400 text-center">
                    <i class="fa-solid fa-info-circle mr-1"></i>
                    Pattern visualization placeholder - requires historical data
                </p>
            `;
                }

                // 1.9 Cross-Channel Sync
                function render1_9(competitors, markets) {
                    const totalEmail = competitors.reduce((sum, c) => sum + (parseInt(c.email_count) || 0), 0);
                    const totalSms = competitors.reduce((sum, c) => sum + (parseInt(c.sms_count) || 0), 0);
                    const totalPush = competitors.reduce((sum, c) => sum + (parseInt(c.push_count) || 0), 0);
                    return `
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-envelope text-2xl text-blue-500 mb-2"></i>
                        <p class="text-xl font-bold">${totalEmail.toLocaleString()}</p>
                        <p class="text-sm text-slate-500">Email</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-comment-sms text-2xl text-green-500 mb-2"></i>
                        <p class="text-xl font-bold">${totalSms.toLocaleString()}</p>
                        <p class="text-sm text-slate-500">SMS</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-bell text-2xl text-purple-500 mb-2"></i>
                        <p class="text-xl font-bold">${totalPush.toLocaleString()}</p>
                        <p class="text-sm text-slate-500">Push</p>
                    </div>
                </div>
                <div class="bg-slate-50 rounded-lg p-4">
                    <h4 class="font-semibold mb-2"><i class="fa-solid fa-arrows-rotate text-indigo-500 mr-2"></i>Sync Analysis</h4>
                    <p class="text-sm text-slate-500">Cross-channel coordination metrics not yet available</p>
                </div>
            `;
                }

                // 2.3 Subject Lines
                function render2_3(competitors, markets) {
                    return `
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div class="bg-slate-50 rounded-lg p-4">
                        <h4 class="font-semibold mb-3"><i class="fa-solid fa-heading text-indigo-500 mr-2"></i>Length Analysis</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span>Short (&lt;30 chars)</span>
                                <span class="text-slate-400">-</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Medium (30-60 chars)</span>
                                <span class="text-slate-400">-</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Long (&gt;60 chars)</span>
                                <span class="text-slate-400">-</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4">
                        <h4 class="font-semibold mb-3"><i class="fa-solid fa-sparkles text-amber-500 mr-2"></i>Common Patterns</h4>
                        <ul class="text-sm text-slate-500 space-y-1">
                            <li><i class="fa-regular fa-circle text-slate-300 mr-2"></i>Emoji usage</li>
                            <li><i class="fa-regular fa-circle text-slate-300 mr-2"></i>Personalization</li>
                            <li><i class="fa-regular fa-circle text-slate-300 mr-2"></i>Urgency words</li>
                        </ul>
                    </div>
                </div>
                <p class="text-xs text-slate-400 text-center">
                    <i class="fa-solid fa-info-circle mr-1"></i>
                    Subject line analysis requires email content extraction
                </p>
            `;
                }

                // 3.3 Responsible Gaming
                function render3_3(competitors, markets) {
                    return `
                <div class="grid grid-cols-4 gap-4 mb-6">
                    <div class="border border-green-200 bg-green-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-check text-xl text-green-600 mb-2"></i>
                        <p class="text-lg font-bold text-green-700">-</p>
                        <p class="text-xs text-green-600">Self-Limits Offered</p>
                    </div>
                    <div class="border border-blue-200 bg-blue-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-clock text-xl text-blue-600 mb-2"></i>
                        <p class="text-lg font-bold text-blue-700">-</p>
                        <p class="text-xs text-blue-600">Cool-Off Periods</p>
                    </div>
                    <div class="border border-amber-200 bg-amber-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-hand text-xl text-amber-600 mb-2"></i>
                        <p class="text-lg font-bold text-amber-700">-</p>
                        <p class="text-xs text-amber-600">Self-Exclusion</p>
                    </div>
                    <div class="border border-purple-200 bg-purple-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-info text-xl text-purple-600 mb-2"></i>
                        <p class="text-lg font-bold text-purple-700">-</p>
                        <p class="text-xs text-purple-600">Help Resources</p>
                    </div>
                </div>
                <div class="bg-slate-50 rounded-lg p-4">
                    <h4 class="font-semibold mb-2"><i class="fa-solid fa-dice text-indigo-500 mr-2"></i>RG Compliance Markers</h4>
                    <p class="text-sm text-slate-500">Awaiting responsible gaming content classification</p>
                </div>
            `;
                }

                // 4.2 Market Exposure
                function render4_2(competitors, markets) {
                    return `
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div class="bg-slate-50 rounded-lg p-4">
                        <h4 class="font-semibold mb-3"><i class="fa-solid fa-globe text-blue-500 mr-2"></i>Geographic Spread</h4>
                        <p class="text-3xl font-bold">${markets.length}</p>
                        <p class="text-sm text-slate-500">Active Markets</p>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4">
                        <h4 class="font-semibold mb-3"><i class="fa-solid fa-building text-indigo-500 mr-2"></i>Brand Presence</h4>
                        <p class="text-3xl font-bold">${competitors.length}</p>
                        <p class="text-sm text-slate-500">Competitors Tracked</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Market</th>
                                <th class="px-4 py-2 text-center">Hits</th>
                                <th class="px-4 py-2 text-center">Exposure</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${competitors.slice().sort((a, b) => (parseInt(b.total_hits) || 0) - (parseInt(a.total_hits) || 0)).slice(0, 3).map(c => `
                            <tr class="border-b">
                                <td class="px-4 py-3 font-medium">${c.market_id}</td>
                                <td class="px-4 py-3 text-center">${(parseInt(c.total_hits) || 0).toLocaleString()}</td>
                                <td class="px-4 py-3 text-center"><span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">High</span></td>
                            </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
                }

                // 4.3 License Status
                function render4_3(competitors, markets) {
                    const ls = brandData.licenseStats || {};
                    const summary = ls.summary || {};
                    const byMarket = ls.by_market || {};
                    const marketKeys = Object.keys(byMarket);
                    const hasData = marketKeys.length > 0;

                    const formatExpiry = (date) => {
                        if (!date) return '-';
                        const d = new Date(date);
                        const now = new Date();
                        const daysLeft = Math.floor((d - now) / (1000 * 60 * 60 * 24));
                        const formatted = d.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
                        if (daysLeft < 0) return `<span class="text-red-600">${formatted} (Expired)</span>`;
                        if (daysLeft < 90) return `<span class="text-amber-600">${formatted} (${daysLeft}d)</span>`;
                        return formatted;
                    };

                    return `
                <div class="grid grid-cols-4 gap-4 mb-6">
                    <div class="border border-green-200 bg-green-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-certificate text-2xl text-green-600 mb-2"></i>
                        <p class="text-xl font-bold text-green-700">${summary.active || 0}</p>
                        <p class="text-sm text-green-600">Licensed</p>
                    </div>
                    <div class="border border-amber-200 bg-amber-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-hourglass-half text-2xl text-amber-600 mb-2"></i>
                        <p class="text-xl font-bold text-amber-700">${summary.pending || 0}</p>
                        <p class="text-sm text-amber-600">Pending</p>
                    </div>
                    <div class="border border-purple-200 bg-purple-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-clock text-2xl text-purple-600 mb-2"></i>
                        <p class="text-xl font-bold text-purple-700">${summary.expired || 0}</p>
                        <p class="text-sm text-purple-600">Expired</p>
                    </div>
                    <div class="border border-red-200 bg-red-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-ban text-2xl text-red-600 mb-2"></i>
                        <p class="text-xl font-bold text-red-700">${summary.grey || 0}</p>
                        <p class="text-sm text-red-600">Grey/Unlicensed</p>
                    </div>
                </div>
                ${!hasData ? '<p class="text-slate-400 text-center py-4">No license data available for this brand</p>' : `
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Market</th>
                                <th class="px-4 py-2 text-left">License #</th>
                                <th class="px-4 py-2 text-center">Regulator</th>
                                <th class="px-4 py-2 text-center">Expiry</th>
                                <th class="px-4 py-2 text-center">Verify</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${marketKeys.map(m => {
                        const lic = byMarket[m];
                        return `
                            <tr class="border-b hover:bg-slate-50">
                                <td class="px-4 py-3 font-medium"><i class="fa-solid fa-map-marker-alt text-blue-500 mr-2"></i>${m}</td>
                                <td class="px-4 py-3 font-mono text-xs">${lic.license_number || '<span class="text-slate-400">—</span>'}</td>
                                <td class="px-4 py-3 text-center">${lic.regulator || '-'}</td>
                                <td class="px-4 py-3 text-center">${formatExpiry(lic.expiry)}</td>
                                <td class="px-4 py-3 text-center">${lic.verification_url ? `<a href="${lic.verification_url}" target="_blank" class="text-blue-500 hover:underline"><i class="fa-solid fa-external-link"></i></a>` : '-'}</td>
                            </tr>`;
                    }).join('')}
                        </tbody>
                    </table>
                </div>
                `}
                ${(summary.regulators || []).length > 0 ? `
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="text-xs text-slate-500">Regulators:</span>
                    ${(summary.regulators || []).map(r => `<span class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded text-xs font-medium">${r}</span>`).join('')}
                </div>
                ` : ''}
            `;
                }

                // 5.1 Payment Methods
                function render5_1(competitors, markets) {
                    const ps = brandData.productStats?.payment_methods || {};
                    const byMarket = ps.by_market || {};
                    const marketKeys = Object.keys(byMarket);

                    return `
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="border border-green-200 bg-green-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-credit-card text-2xl text-green-600 mb-2"></i>
                        <p class="text-xl font-bold text-green-700">${ps.total || 0}</p>
                        <p class="text-sm text-green-600">Payment Methods</p>
                    </div>
                    <div class="border border-blue-200 bg-blue-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-arrow-down text-2xl text-blue-600 mb-2"></i>
                        <p class="text-xl font-bold text-blue-700">${ps.min_deposit != null ? '$' + ps.min_deposit : '-'}</p>
                        <p class="text-sm text-blue-600">Min Deposit</p>
                    </div>
                    <div class="border border-purple-200 bg-purple-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-arrow-up text-2xl text-purple-600 mb-2"></i>
                        <p class="text-xl font-bold text-purple-700">${ps.max_deposit != null ? '$' + ps.max_deposit.toLocaleString() : '-'}</p>
                        <p class="text-sm text-purple-600">Max Deposit</p>
                    </div>
                </div>
                ${marketKeys.length === 0 ? '<p class="text-slate-400 text-center py-4">No payment method data available</p>' : `
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold">Market</th>
                                <th class="px-4 py-2 text-center font-semibold">Count</th>
                                <th class="px-4 py-2 text-left font-semibold">Methods</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${marketKeys.map(market => {
                        const methods = byMarket[market] || [];
                        const methodNames = methods.slice(0, 3).map(m => m.name).join(', ');
                        const moreCount = methods.length > 3 ? ' +' + (methods.length - 3) + ' more' : '';
                        return '<tr class="border-b hover:bg-slate-50 cursor-pointer" onclick="this.nextElementSibling.classList.toggle(\'hidden\')">' +
                            '<td class="px-4 py-3 font-medium"><i class="fa-solid fa-map-marker-alt text-blue-500 mr-2"></i>' + market + ' <i class="fa-solid fa-chevron-down text-slate-300 ml-2 text-xs"></i></td>' +
                            '<td class="px-4 py-3 text-center"><span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">' + methods.length + '</span></td>' +
                            '<td class="px-4 py-3 text-slate-600">' + methodNames + moreCount + '</td>' +
                            '</tr>' +
                            '<tr class="hidden bg-slate-50"><td colspan="3" class="px-4 py-3">' +
                            '<table class="w-full text-xs"><thead class="bg-slate-200"><tr>' +
                            '<th class="px-3 py-1 text-left">Method</th><th class="px-3 py-1 text-left">Category</th><th class="px-3 py-1 text-center">Min</th><th class="px-3 py-1 text-center">Max</th>' +
                            '</tr></thead><tbody>' +
                            methods.map(m => '<tr class="border-b border-slate-100"><td class="px-3 py-2 font-medium">' + m.name + '</td><td class="px-3 py-2 text-slate-500">' + (m.category || '-') + '</td><td class="px-3 py-2 text-center">' + (m.min ? '$' + m.min : '-') + '</td><td class="px-3 py-2 text-center">' + (m.max ? '$' + m.max.toLocaleString() : '-') + '</td></tr>').join('') +
                            '</tbody></table></td></tr>';
                    }).join('')}
                        </tbody>
                    </table>
                </div>
                `}
            `;
                }

                // 5.2 Support Channels
                function render5_2(competitors, markets) {
                    const sc = brandData.productStats?.support_channels || {};
                    const channels = sc.channels || [];
                    const byMarket = sc.by_market || {};
                    const marketKeys = Object.keys(byMarket);

                    const channelIcons = {
                        'Live Chat': 'fa-comments',
                        'Email': 'fa-envelope',
                        'Call': 'fa-phone',
                        'Telegram': 'fa-paper-plane',
                        'Form': 'fa-file-lines',
                        'FAQ': 'fa-circle-question'
                    };

                    return `
                <div class="grid grid-cols-${Math.min(channels.length, 4)} gap-4 mb-6">
                    ${channels.slice(0, 4).map(ch => `
                    <div class="border border-blue-200 bg-blue-50 rounded-lg p-4 text-center">
                        <i class="fa-solid ${channelIcons[ch] || 'fa-headset'} text-2xl text-blue-600 mb-2"></i>
                        <p class="text-sm font-semibold text-blue-700">${ch}</p>
                    </div>
                    `).join('')}
                </div>
                ${channels.length === 0 ? '<p class="text-slate-400 text-center py-4">No support channel data available</p>' : `
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold">Market</th>
                                <th class="px-4 py-2 text-left font-semibold">Channels</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${marketKeys.map(market => {
                        const marketChannels = byMarket[market] || [];
                        return '<tr class="border-b hover:bg-slate-50">' +
                            '<td class="px-4 py-3 font-medium"><i class="fa-solid fa-map-marker-alt text-green-500 mr-2"></i>' + market + '</td>' +
                            '<td class="px-4 py-3"><div class="flex flex-wrap gap-2">' +
                            marketChannels.map(ch => '<span class="inline-flex items-center gap-1 bg-green-100 text-green-700 rounded-full px-3 py-1 text-xs"><i class="fa-solid ' + (channelIcons[ch] || 'fa-headset') + '"></i> ' + ch + '</span>').join('') +
                            '</div></td></tr>';
                    }).join('')}
                        </tbody>
                    </table>
                </div>
                `}
            `;
                }

                // 5.3 KYC Requirements
                function render5_3(competitors, markets) {
                    const kyc = brandData.productStats?.kyc_requirements || {};
                    const byMarket = kyc.by_market || {};
                    const marketKeys = Object.keys(byMarket);

                    const levelColors = {
                        'Light': { bg: 'bg-green-50', border: 'border-green-200', text: 'text-green-700', icon: 'fa-check-circle' },
                        'Standard': { bg: 'bg-amber-50', border: 'border-amber-200', text: 'text-amber-700', icon: 'fa-id-card' },
                        'Heavy': { bg: 'bg-red-50', border: 'border-red-200', text: 'text-red-700', icon: 'fa-shield-halved' }
                    };
                    const level = levelColors[kyc.level] || levelColors['Standard'];

                    return `
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="border ${level.border} ${level.bg} rounded-lg p-4 text-center">
                        <i class="fa-solid ${level.icon} text-2xl ${level.text} mb-2"></i>
                        <p class="text-xl font-bold ${level.text}">${kyc.level || 'Unknown'}</p>
                        <p class="text-sm ${level.text.replace('700', '600')}">KYC Level</p>
                    </div>
                    <div class="border border-slate-200 bg-slate-50 rounded-lg p-4 text-center">
                        <i class="fa-solid fa-list-check text-2xl text-slate-600 mb-2"></i>
                        <p class="text-xl font-bold text-slate-700">${kyc.avg_fields || 0}</p>
                        <p class="text-sm text-slate-600">Avg. Required Fields</p>
                    </div>
                </div>
                ${marketKeys.length === 0 ? '<p class="text-slate-400 text-center py-4">No KYC data available</p>' : `
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold">Market</th>
                                <th class="px-4 py-2 text-center font-semibold">Fields</th>
                                <th class="px-4 py-2 text-left font-semibold">Requirements</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${marketKeys.map(market => {
                        const fields = byMarket[market] || [];
                        const fieldNames = fields.slice(0, 3).join(', ');
                        const moreCount = fields.length > 3 ? ' +' + (fields.length - 3) + ' more' : '';
                        return '<tr class="border-b hover:bg-slate-50 cursor-pointer" onclick="this.nextElementSibling.classList.toggle(\'hidden\')">' +
                            '<td class="px-4 py-3 font-medium"><i class="fa-solid fa-map-marker-alt text-amber-500 mr-2"></i>' + market + ' <i class="fa-solid fa-chevron-down text-slate-300 ml-2 text-xs"></i></td>' +
                            '<td class="px-4 py-3 text-center"><span class="bg-amber-100 text-amber-700 px-2 py-1 rounded text-xs font-medium">' + fields.length + '</span></td>' +
                            '<td class="px-4 py-3 text-slate-600">' + fieldNames + moreCount + '</td>' +
                            '</tr>' +
                            '<tr class="hidden bg-slate-50"><td colspan="3" class="px-4 py-3"><div class="flex flex-wrap gap-2">' +
                            fields.map(f => '<span class="inline-flex items-center gap-1 bg-white border border-slate-200 rounded px-2 py-1 text-xs"><i class="fa-solid fa-check text-green-500"></i> ' + f + '</span>').join('') +
                            '</div></td></tr>';
                    }).join('')}
                        </tbody>
                    </table>
                </div>
                `}
            `;
                }

                // Generic fallback
                function renderGenericReport(reportId, competitors, markets) {
                    const report = REPORTS.find(r => r.id === reportId);
                    return `
                <div class="text-center py-8 bg-slate-50 rounded-lg">
                    <i class="fa-solid ${report?.icon || 'fa-file'} text-4xl text-slate-300 mb-3"></i>
                    <p class="text-slate-500 font-medium">${report?.name || reportId}</p>
                    <p class="text-xs text-slate-400 mt-2">Report-specific data coming soon</p>
                    <p class="text-xs text-slate-300 mt-1">${competitors.length} competitors across ${markets.length} markets</p>
                </div>
            `;
                }

                function toggleSection(reportId) {
                    const section = document.getElementById(`section-${reportId}`);
                    section.classList.toggle('collapsed');
                    const icon = section.querySelector('.section-toggle');
                    icon.classList.toggle('fa-chevron-down');
                    icon.classList.toggle('fa-chevron-right');
                }

                function exportPDF() {
                    alert('PDF export coming in Phase 3!');
                }
            </script>
@endpush
