@extends('layouts.dashboard')


@section('title', 'CRMTracker - Data Governance')

@push('styles')
<style>
body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .tab-btn.active {
            border-bottom-color: #6366f1;
            color: #4f46e5;
            font-weight: 600;
        }

        .expandable-row {
            cursor: pointer;
        }

        .expandable-row:hover {
            background-color: #f8fafc;
        }

        .child-row {
            display: none;
            background-color: #fafafa;
        }

        .child-row.show {
            display: table-row;
        }

        /* Make mermaid graph larger and more visible */
        #mermaid-container {
            min-height: 400px;
            padding: 2rem;
            overflow-x: auto;
        }

        #mermaid-container .mermaid {
            min-width: 800px;
        }

        #mermaid-container svg {
            min-width: 700px;
            min-height: 350px;
        }

        .status-active {
            color: #059669;
        }

        .status-incomplete {
            color: #d97706;
        }

        .status-orphaned {
            color: #dc2626;
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
class="text-xs font-medium bg-purple-50 text-purple-700 px-2 py-1 rounded border border-purple-200">A.2</span>
<h1 class="text-xl font-bold text-slate-800 ml-2">Data Governance</h1>
</div>
<div class="flex items-center gap-2">
<button onclick="recalculateAllScores()" id="recalc-btn"
class="px-4 py-2 rounded-lg font-medium shadow-sm flex items-center gap-2 transition-colors bg-emerald-600 hover:bg-emerald-700 text-white">
<i class="fa-solid fa-calculator"></i> Recalculate Scores
</button>
<button onclick="refreshRegistry()"
class="px-4 py-2 rounded-lg font-medium shadow-sm flex items-center gap-2 transition-colors bg-blue-600 hover:bg-blue-700 text-white">
<i class="fa-solid fa-rotate"></i> Refresh
</button>
</div>
</header>
<!-- Health Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
<div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-database text-blue-500"></i>
<span class="text-xs font-bold text-blue-800 uppercase">Total Fields</span>
</div>
<p class="text-3xl font-bold text-blue-700" id="stat-total">—</p>
</div>
<div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-check-circle text-emerald-500"></i>
<span class="text-xs font-bold text-emerald-800 uppercase">Active Fields</span>
</div>
<p class="text-3xl font-bold text-emerald-700" id="stat-used">—</p>
</div>
<div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-ghost text-rose-500"></i>
<span class="text-xs font-bold text-rose-800 uppercase">Orphaned</span>
</div>
<p class="text-3xl font-bold text-rose-700" id="stat-orphaned">—</p>
</div>
<div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-exclamation-triangle text-amber-500"></i>
<span class="text-xs font-bold text-amber-800 uppercase">Incomplete Data</span>
</div>
<p class="text-3xl font-bold text-amber-700" id="stat-incomplete">—</p>
</div>
</div>
<!-- Tabs -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
<div class="flex border-b border-slate-200">
<button
class="tab-btn active px-6 py-4 text-sm border-b-2 border-transparent hover:bg-slate-50 transition-colors"
data-tab="bySource">
<i class="fa-solid fa-layer-group mr-2"></i>By Data Source
</button>
<button
class="tab-btn px-6 py-4 text-sm border-b-2 border-transparent hover:bg-slate-50 transition-colors"
data-tab="byReport">
<i class="fa-solid fa-file-alt mr-2"></i>By Report
</button>
<button
class="tab-btn px-6 py-4 text-sm border-b-2 border-transparent hover:bg-slate-50 transition-colors"
data-tab="graph">
<i class="fa-solid fa-project-diagram mr-2"></i>Data Flow Graph
</button>
<button
class="tab-btn px-6 py-4 text-sm border-b-2 border-transparent hover:bg-slate-50 transition-colors"
data-tab="calcLogic">
<i class="fa-solid fa-calculator mr-2"></i>Calculation Logic
</button>
<button
class="tab-btn px-6 py-4 text-sm border-b-2 border-transparent hover:bg-slate-50 transition-colors"
data-tab="completeness">
<i class="fa-solid fa-chart-pie mr-2"></i>Data Completeness
</button>
</div>
<!-- Tab Content -->
<div id="tab-bySource" class="tab-content p-6">
<!-- Search/Filter -->
<div class="flex items-center gap-4 mb-4">
<input type="text" id="source-search" placeholder="Search fields..."
oninput="filterSourceTable()"
class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-64">
<select id="source-filter" onchange="filterSourceTable()"
class="px-4 py-2 border border-slate-300 rounded-lg">
<option value="all">All Status</option>
<option value="active">Active Only</option>
<option value="incomplete">Incomplete Only</option>
<option value="orphaned">Orphaned Only</option>
<option value="no-data">No Data Only</option>
<option value="api-sourced">API-Sourced Only</option>
</select>
</div>
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-50 border-b border-slate-200">
<tr>
<th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs">Data
Block</th>
<th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs">Field
</th>
<th class="text-center py-3 px-4 font-bold text-slate-600 uppercase text-xs">Type
</th>
<th class="text-center py-3 px-4 font-bold text-slate-600 uppercase text-xs">Origin
</th>
<th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs">Used In
</th>
<th class="text-center py-3 px-4 font-bold text-slate-600 uppercase text-xs">
Completeness</th>
<th class="text-center py-3 px-4 font-bold text-slate-600 uppercase text-xs">Status
</th>
<th class="text-center py-3 px-4 font-bold text-slate-600 uppercase text-xs">Edit
</th>
</tr>
</thead>
<tbody id="source-tbody" class="divide-y divide-slate-100"></tbody>
</table>
</div>
</div>
<div id="tab-byReport" class="tab-content p-6 hidden">
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-50 border-b border-slate-200">
<tr>
<th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs">Report
</th>
<th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs">Name</th>
<th class="text-center py-3 px-4 font-bold text-slate-600 uppercase text-xs">Fields
</th>
<th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs">Data
Blocks</th>
<th class="text-center py-3 px-4 font-bold text-slate-600 uppercase text-xs">Health
</th>
<th class="text-center py-3 px-4 font-bold text-slate-600 uppercase text-xs">Link
</th>
</tr>
</thead>
<tbody id="report-tbody" class="divide-y divide-slate-100"></tbody>
</table>
</div>
</div>
<div id="tab-graph" class="tab-content p-6 hidden">
<div id="mermaid-container" class="flex justify-center"></div>
</div>
<div id="tab-calcLogic" class="tab-content p-6 hidden">
<div class="flex items-center justify-between mb-4 gap-4">
<div class="flex items-center gap-4">
<input type="text" id="calcLogic-search" placeholder="Search metrics..."
oninput="filterCalcLogic()"
class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-64">
<select id="calcLogic-filter" onchange="filterCalcLogic()"
class="px-4 py-2 border border-slate-300 rounded-lg">
<option value="all">All Data Types</option>
<option value="real">Real Data Only</option>
<option value="partial">Partial Stub</option>
<option value="stub">Full Stub</option>
</select>
<div id="date-range-container" class="flex items-center"></div>
<button onclick="loadCalculationLogic()"
class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
<i class="fa-solid fa-sync mr-2"></i>Refresh from DB
</button>
</div>
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-50 border-b border-slate-200">
<tr>
<th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs">
Report
</th>
<th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs">
Metric
</th>
<th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs">Label
</th>
<th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs">
Description</th>
<th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs">
Source
</th>
<th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs">
Formula
</th>
<th class="text-center py-3 px-4 font-bold text-slate-600 uppercase text-xs">
Stub?
</th>
</tr>
</thead>
<tbody id="calcLogic-tbody" class="divide-y divide-slate-100"></tbody>
</table>
</div>
</div>
<div id="tab-completeness" class="tab-content p-6 hidden">
<div class="flex items-center justify-between mb-4">
<h3 class="text-lg font-bold text-slate-700">
<i class="fa-solid fa-chart-pie text-purple-600 mr-2"></i>
Competitor Data Completeness
</h3>
<button onclick="loadCompleteness()"
class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm">
<i class="fa-solid fa-sync mr-2"></i>Refresh
</button>
</div>
<p class="text-sm text-slate-500 mb-4">
Shows how complete the data is for each competitor across different modules.
Higher completeness = more fields available for reports.
</p>
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-50 border-b border-slate-200">
<tr>
<th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs">
Competitor</th>
<th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs">
Market
</th>
<th class="text-center py-3 px-4 font-bold text-slate-600 uppercase text-xs">
Hits
</th>
<th class="text-center py-3 px-4 font-bold text-blue-600 uppercase text-xs">D.5
Offers</th>
<th class="text-center py-3 px-4 font-bold text-purple-600 uppercase text-xs">
M.2
Content</th>
<th class="text-center py-3 px-4 font-bold text-emerald-600 uppercase text-xs">
M.3
Compliance</th>
<th class="text-center py-3 px-4 font-bold text-slate-600 uppercase text-xs">
Overall
</th>
<th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs">Top
Gap
</th>
</tr>
</thead>
<tbody id="completeness-tbody" class="divide-y divide-slate-100">
<tr>
<td colspan="8" class="py-8 text-center text-slate-400">Click Refresh to load
completeness data</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</main>
</div>
<!-- Edit Annotation Modal -->
<div id="edit-modal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center"
onclick="if(event.target===this) closeEditModal()">
<div class="bg-white rounded-xl shadow-2xl w-[500px] max-h-[80vh] overflow-hidden flex flex-col">
<div
class="p-4 border-b border-slate-200 flex items-center justify-between flex-shrink-0 bg-gradient-to-r from-purple-600 to-blue-600">
<h3 class="font-semibold text-white">Edit Field Annotation</h3>
<button onclick="closeEditModal()" class="text-white/80 hover:text-white">
<i class="fa-solid fa-xmark"></i>
</button>
</div>
<div class="p-6 flex-1 overflow-auto">
<div class="mb-4">
<label class="block text-xs font-bold text-slate-600 uppercase mb-1">Field Key</label>
<code id="edit-field-key" class="block text-sm bg-slate-100 px-3 py-2 rounded"></code>
</div>
<div class="mb-4">
<label class="block text-xs font-bold text-slate-600 uppercase mb-1">Description</label>
<textarea id="edit-description" rows="3"
class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500"
placeholder="Describe what this field represents..."></textarea>
</div>
<div class="mb-4">
<label class="block text-xs font-bold text-slate-600 uppercase mb-1">Owner</label>
<select id="edit-owner"
class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">
<option value="">-- Select Owner --</option>
<option value="Compliance Team">Compliance Team</option>
<option value="CRM Team">CRM Team</option>
<option value="M&A Team">M&A Team</option>
<option value="Risk Team">Risk Team</option>
<option value="Audit Team">Audit Team</option>
<option value="Engineering">Engineering</option>
</select>
</div>
<div class="mb-4">
<label class="flex items-center gap-2 cursor-pointer">
<input type="checkbox" id="edit-deprecated"
class="w-4 h-4 text-rose-600 rounded border-slate-300 focus:ring-rose-500">
<span class="text-sm font-medium text-slate-700">Mark as Deprecated</span>
</label>
</div>
<div id="deprecated-reason-container" class="mb-4 hidden">
<label class="block text-xs font-bold text-slate-600 uppercase mb-1">Deprecation Reason</label>
<textarea id="edit-deprecated-reason" rows="2"
class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-rose-500"
placeholder="Why is this field deprecated?"></textarea>
</div>
</div>
<div class="p-4 border-t border-slate-200 bg-slate-50 flex justify-between items-center">
<span id="edit-status" class="text-xs text-slate-500"></span>
<div class="flex gap-2">
<button onclick="closeEditModal()"
class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">Cancel</button>
<button onclick="saveAnnotation()"
class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 flex items-center gap-2">
<i class="fa-solid fa-save"></i> Save Changes
</button>
</div>
</div>
</div>
</div>
<script>
// Tab switching
document.querySelectorAll('.tab-btn').forEach(btn => {
btn.addEventListener('click', () => {
document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
btn.classList.add('active');
document.getElementById('tab-' + btn.dataset.tab).classList.remove('hidden');
if (btn.dataset.tab === 'graph') renderGraph();
});
});
let currentRegistry = null;
function refreshRegistry() {
if (!window.CRMT?.buildFieldRegistry) {
setTimeout(refreshRegistry, 300);
return;
}
currentRegistry = window.CRMT.buildFieldRegistry();
updateStats();
renderSourceTable();
renderReportTable();
}
function updateStats() {
if (!currentRegistry) return;
document.getElementById('stat-total').textContent = currentRegistry.stats.totalFields;
document.getElementById('stat-used').textContent = currentRegistry.stats.usedFields;
document.getElementById('stat-orphaned').textContent = currentRegistry.stats.orphanedFields;
document.getElementById('stat-incomplete').textContent = currentRegistry.stats.incompleteFields;
}
function getStatusBadge(status) {
const badges = {
'active': '<span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded">✓ Active</span>',
'incomplete': '<span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded">⚠ Incomplete</span>',
'orphaned': '<span class="text-xs bg-rose-100 text-rose-700 px-2 py-0.5 rounded">✗ Orphaned</span>',
'api-sourced': '<span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded">🗄 DB-Sourced</span>',
'no-data': '<span class="text-xs bg-slate-200 text-slate-600 px-2 py-0.5 rounded">⊘ No Data</span>'
};
return badges[status] || status;
}
function getTypeBadge(type) {
const colors = { bool: 'purple', pct: 'blue', score: 'emerald', text: 'slate', number: 'indigo', check: 'green' };
return `<span class="text-xs bg-${colors[type] || 'slate'}-100 text-${colors[type] || 'slate'}-700 px-2 py-0.5 rounded">${type || 'unknown'}</span>`;
}
// Get origin from field key: Excel Import / API / Calculated / Stub
function getFieldOrigin(fieldKey) {
// Check definitions first
const def = window.definitions?.find(d => d.metric_path === fieldKey);
if (def) {
const description = def.label || '';
if (def.is_stub) return { type: 'stub', badge: '<span class="px-2 py-0.5 rounded text-xs bg-amber-100 text-amber-700">Stub</span>', description };
if (def.source_table === 'hits') return { type: 'excel', badge: '<span class="px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-700">Excel Import</span>', description };
if (def.source_table === 'competitors') return { type: 'profile', badge: '<span class="px-2 py-0.5 rounded text-xs bg-green-100 text-green-700">Profile</span>', description };
if (def.source_table === 'calculated') return { type: 'calc', badge: '<span class="px-2 py-0.5 rounded text-xs bg-purple-100 text-purple-700">Calculated</span>', description };
return { type: 'api', badge: '<span class="px-2 py-0.5 rounded text-xs bg-cyan-100 text-cyan-700">API</span>', description };
}
// Infer from field key pattern
if (fieldKey.includes('compliance') || fieldKey.includes('content')) {
return { type: 'excel', badge: '<span class="px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-700">Excel Import</span>', description: '' };
}
if (fieldKey.includes('license') || fieldKey.includes('profile')) {
return { type: 'profile', badge: '<span class="px-2 py-0.5 rounded text-xs bg-green-100 text-green-700">Profile</span>', description: '' };
}
if (fieldKey.includes('score') || fieldKey.includes('Index')) {
return { type: 'calc', badge: '<span class="px-2 py-0.5 rounded text-xs bg-purple-100 text-purple-700">Calculated</span>', description: '' };
}
return { type: 'unknown', badge: '<span class="px-2 py-0.5 rounded text-xs bg-slate-100 text-slate-600">TBD</span>', description: '' };
}
function renderSourceTable() {
if (!currentRegistry) return;
const tbody = document.getElementById('source-tbody');
const fields = Object.values(currentRegistry.fields);
// Group by data block
const grouped = {};
for (const f of fields) {
if (!grouped[f.dataBlock]) grouped[f.dataBlock] = [];
grouped[f.dataBlock].push(f);
}
let html = '';
for (const [block, blockFields] of Object.entries(grouped)) {
for (const f of blockFields) {
const usedIn = f.usedIn.map(r => `<a href="${currentRegistry.reports[r]?.link || '#'}" class="text-blue-600 hover:underline">${r}</a>`).join(', ') || '—';
const origin = getFieldOrigin(f.key);
html += `
<tr class="hover:bg-slate-50 field-row" data-status="${f.status}" data-key="${f.key}" data-origin="${origin.type}">
<td class="py-3 px-4"><code class="text-xs bg-slate-100 px-2 py-0.5 rounded">${f.dataBlock}</code></td>
<td class="py-3 px-4">
<code class="text-xs">${f.key.replace(f.dataBlock + '.', '')}</code>
${origin.description && !f.description ? `<p class="text-xs text-slate-500 mt-1">${origin.description}</p>` : ''}
${f.description ? `<p class="text-xs text-slate-500 mt-1">${f.description}</p>` : ''}
${f.noDataReason ? `<p class="text-xs text-slate-400 mt-0.5 italic">⊘ ${f.noDataReason}</p>` : ''}
</td>
<td class="py-3 px-4 text-center">${getTypeBadge(f.type)}</td>
<td class="py-3 px-4 text-center">${origin.badge}</td>
<td class="py-3 px-4">${usedIn}</td>
<td class="py-3 px-4 text-center">
<div class="flex items-center gap-2 justify-center">
<div class="w-16 h-2 bg-slate-200 rounded-full overflow-hidden">
<div class="h-full ${f.completeness >= 80 ? 'bg-emerald-500' : f.completeness >= 50 ? 'bg-amber-500' : 'bg-rose-500'}" style="width: ${f.completeness}%"></div>
</div>
<span class="text-xs text-slate-600">${f.completeness}%</span>
</div>
</td>
<td class="py-3 px-4 text-center">${getStatusBadge(f.status)}</td>
<td class="py-3 px-4 text-center">
<button onclick="openEditModal('${f.key}')" class="text-blue-600 hover:text-blue-800" title="Edit annotation">
<i class="fa-solid fa-pen-to-square"></i>
</button>
</td>
</tr>
`;
}
}
tbody.innerHTML = html;
}
function filterSourceTable() {
const search = document.getElementById('source-search').value.toLowerCase();
const statusFilter = document.getElementById('source-filter').value;
document.querySelectorAll('.field-row').forEach(row => {
const key = row.dataset.key.toLowerCase();
const status = row.dataset.status;
const matchesSearch = !search || key.includes(search);
const matchesStatus = statusFilter === 'all' || status === statusFilter;
row.style.display = matchesSearch && matchesStatus ? '' : 'none';
});
}
// Filter Calculation Logic table by search and stub status
function filterCalcLogic() {
const search = document.getElementById('calcLogic-search').value.toLowerCase();
const stubFilter = document.getElementById('calcLogic-filter').value;
document.querySelectorAll('.calcLogic-row').forEach(row => {
const metric = row.dataset.metric || '';
const stubStatus = row.dataset.stubstatus || 'real';
const matchesSearch = !search || metric.includes(search);
const matchesStub = stubFilter === 'all' ||
(stubFilter === 'stub' && stubStatus === 'stub') ||
(stubFilter === 'partial' && stubStatus === 'partial') ||
(stubFilter === 'real' && stubStatus === 'real');
row.style.display = matchesSearch && matchesStub ? '' : 'none';
});
}
function renderReportTable() {
if (!currentRegistry) return;
const tbody = document.getElementById('report-tbody');
// Sort reports by module and submodule (1.1, 1.2, 1.3, ... 2.1, 2.2, ... D.1, D.2, ...)
const sortedReports = Object.entries(currentRegistry.reports).sort(([a], [b]) => {
// Extract module prefix and number
const parseId = (id) => {
const match = id.match(/^([A-Z]?)(\d*)\.?(\d*)$/i);
if (!match) return { prefix: id, major: 0, minor: 0 };
const prefix = match[1] || '';
const major = parseInt(match[2]) || 0;
const minor = parseInt(match[3]) || 0;
return { prefix, major, minor };
};
const aP = parseId(a), bP = parseId(b);
// Numeric reports (1.x, 2.x, etc.) come before letter reports (A.x, D.x)
if (aP.prefix !== bP.prefix) {
if (!aP.prefix) return -1;
if (!bP.prefix) return 1;
return aP.prefix.localeCompare(bP.prefix);
}
if (aP.major !== bP.major) return aP.major - bP.major;
return aP.minor - bP.minor;
});
let html = '';
for (const [reportId, report] of sortedReports) {
const healthColor = report.health >= 80 ? 'emerald' : report.health >= 50 ? 'amber' : 'rose';
const blocks = report.dataBlocks.map(b => `<code class="text-xs bg-slate-100 px-2 py-0.5 rounded">${b}</code>`).join(' ');
html += `
<tr class="hover:bg-slate-50">
<td class="py-3 px-4"><span class="font-bold text-blue-600">${reportId}</span></td>
<td class="py-3 px-4 font-medium">${report.name}</td>
<td class="py-3 px-4 text-center">${report.fields.length}</td>
<td class="py-3 px-4">${blocks}</td>
<td class="py-3 px-4 text-center">
<span class="text-lg font-bold text-${healthColor}-600">${report.health}%</span>
</td>
<td class="py-3 px-4 text-center">
<a href="${report.link}" class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-arrow-right"></i></a>
</td>
</tr>
`;
}
tbody.innerHTML = html;
}
async function renderGraph() {
const container = document.getElementById('mermaid-container');
container.innerHTML = '<div class="py-8 text-center text-slate-500"><i class="fa-solid fa-spinner fa-spin mr-2"></i>Loading data flow...</div>';
let definitions = [];
try {
if (window.CRMT?.dal?.getDataPointDefinitions) {
const result = await CRMT.dal.getDataPointDefinitions();
definitions = result.data || [];
}
} catch (e) {
console.warn('[A.2] Could not load definitions:', e.message);
}
if (definitions.length === 0) {
container.innerHTML = '<div class="py-8 text-center text-slate-500">No definitions found. Run seedDataPointDefinitions() first.</div>';
return;
}
// Collect unique source columns and group metrics by report
const sourceColumns = new Map(); // column -> [{def, aggregation}]
const calculatedMetrics = [];
const byReport = {};
definitions.forEach(def => {
const reportId = def.report_id || 'unk';
if (!byReport[reportId]) byReport[reportId] = [];
byReport[reportId].push(def);
if (def.source_table === 'hits' && def.source_column) {
if (!sourceColumns.has(def.source_column)) {
sourceColumns.set(def.source_column, []);
}
sourceColumns.get(def.source_column).push(def);
} else if (def.source_table === 'calculated') {
calculatedMetrics.push(def);
}
});
// Collect unique aggregation types
const aggTypes = new Set();
definitions.forEach(def => {
if (def.aggregation && def.source_table === 'hits') {
aggTypes.add(def.aggregation);
}
});
// Helper to sanitize node IDs (only alphanumeric and underscore)
function sanitizeId(str) {
return (str || 'unknown').replace(/[^a-zA-Z0-9]/g, '_');
}
// Helper to escape labels for Mermaid (remove quotes, brackets, special chars)
function sanitizeLabel(str) {
return (str || '')
.replace(/"/g, "'")
.replace(/[<>]/g, '')
.replace(/[\[\]()&]/g, '')
.replace(/\n/g, ' ')
.substring(0, 25);
}
// Build simpler graph without nested subgraphs
// Use actual newline character, not escaped
const NL = '\n';
let graph = 'flowchart LR' + NL;
// Track all defined nodes to avoid referencing undefined nodes
const definedNodes = new Set();
// Source columns
graph += NL + '    %% Source Columns' + NL;
[...sourceColumns.keys()].sort().forEach(col => {
const nodeId = 'src_' + sanitizeId(col);
definedNodes.add(nodeId);
graph += '    ' + nodeId + '[[\"' + sanitizeLabel(col) + '\"]]' + NL;
});
// Aggregation types
graph += NL + '    %% Aggregations' + NL;
[...aggTypes].sort().forEach(agg => {
const nodeId = 'agg_' + sanitizeId(agg);
definedNodes.add(nodeId);
graph += '    ' + nodeId + '((\"' + sanitizeLabel(agg) + '\"))' + NL;
});
// Report metrics - use full path for unique ID
graph += NL + '    %% Report Metrics' + NL;
definitions.forEach(def => {
const nodeId = 'M_' + sanitizeId(def.metric_path);
definedNodes.add(nodeId);
const label = sanitizeLabel(def.label);
const stub = def.is_stub ? '*' : '';
graph += '    ' + nodeId + '[\"' + def.report_id + ': ' + label + stub + '\"]' + NL;
});
// Edges: Source -> Aggregation -> Metric
graph += NL + '    %% Data Flow Edges' + NL;
definitions.forEach(def => {
if (def.source_table === 'hits' && def.source_column && def.aggregation) {
const srcNode = 'src_' + sanitizeId(def.source_column);
const aggNode = 'agg_' + sanitizeId(def.aggregation);
const metricNode = 'M_' + sanitizeId(def.metric_path);
if (definedNodes.has(srcNode) && definedNodes.has(aggNode)) {
graph += '    ' + srcNode + ' --> ' + aggNode + NL;
}
if (definedNodes.has(aggNode) && definedNodes.has(metricNode)) {
graph += '    ' + aggNode + ' --> ' + metricNode + NL;
}
}
});
// Calculated metric dependencies (use dotted lines)
graph += NL + '    %% Calculated Dependencies' + NL;
calculatedMetrics.forEach(def => {
const metricNode = 'M_' + sanitizeId(def.metric_path);
if (def.dependencies && def.dependencies.length > 0) {
def.dependencies.forEach(depPath => {
const depNode = 'M_' + sanitizeId(depPath);
if (definedNodes.has(depNode) && definedNodes.has(metricNode)) {
graph += '    ' + depNode + ' -.-> ' + metricNode + NL;
}
});
}
});
// Style nodes
graph += NL + '    %% Styling' + NL;
graph += '    classDef source fill:#dbeafe,stroke:#3b82f6' + NL;
graph += '    classDef agg fill:#fef3c7,stroke:#f59e0b' + NL;
graph += '    classDef metric fill:#f1f5f9,stroke:#64748b' + NL;
[...sourceColumns.keys()].forEach(col => {
graph += '    class src_' + sanitizeId(col) + ' source' + NL;
});
[...aggTypes].forEach(agg => {
graph += '    class agg_' + sanitizeId(agg) + ' agg' + NL;
});
console.log('[A.2] Graph with', definitions.length, 'definitions,', definedNodes.size, 'nodes');
console.log('[A.2] Generated Mermaid graph:', graph.substring(0, 500) + '...');
try {
container.innerHTML = `<pre class="mermaid">${graph}</pre>`;
await mermaid.init(undefined, container.querySelector('.mermaid'));
} catch (err) {
console.error('[A.2] Mermaid render error:', err);
container.innerHTML = `
<div class="p-4 bg-red-50 border border-red-200 rounded-lg">
<p class="text-red-600 font-medium mb-2"><i class="fa-solid fa-exclamation-triangle mr-2"></i>Graph render failed</p>
<p class="text-sm text-red-500 mb-2">Mermaid error: ${err?.message || 'Unknown error'}</p>
<details class="mt-2">
<summary class="text-xs text-slate-500 cursor-pointer">Show raw graph code</summary>
<pre class="mt-2 p-2 bg-slate-100 text-xs overflow-auto max-h-64">${graph.replace(/</g, '&lt;').replace(/>/g, '&gt;')}</pre>
</details>
</div>
`;
}
}
// ===== CALCULATION LOGIC TAB =====
async function loadCalculationLogic() {
const tbody = document.getElementById('calcLogic-tbody');
tbody.innerHTML = '<tr><td colspan="7" class="py-8 text-center text-slate-500"><i class="fa-solid fa-spinner fa-spin mr-2"></i>Loading...</td></tr>';
try {
if (!window.CRMT?.dal?.getDataPointDefinitions) {
throw new Error('DAL not available');
}
const result = await CRMT.dal.getDataPointDefinitions();
if (!result.data || result.data.length === 0) {
tbody.innerHTML = '<tr><td colspan="7" class="py-8 text-center text-slate-500">No definitions found. Run seedDataPointDefinitions() to populate.</td></tr>';
return;
}
tbody.innerHTML = result.data.map(def => {
// Generate description from source or formula info
let description = '';
if (def.is_stub) {
description = 'Requires: ' + (def.formula || 'external data').substring(0, 40);
} else if (def.source_table === 'hits' && def.source_column) {
description = 'From Excel: ' + def.source_column;
} else if (def.source_table === 'competitors') {
description = 'From competitor profile';
} else if (def.source_table === 'calculated') {
const deps = def.dependencies ? def.dependencies.slice(0, 2).map(d => d.split('.').pop()).join(', ') : '';
description = deps ? 'Derived from: ' + deps : 'Calculated value';
}
// Determine stub status: real, partial, or stub
// Partial = has any dependency that is a stub
let stubStatus = 'real';
if (def.is_stub) {
stubStatus = 'stub';
} else if (def.dependencies && def.dependencies.length > 0) {
const hasStubDep = def.dependencies.some(depPath => {
const depDef = result.data.find(d => d.metric_path === depPath);
return depDef && depDef.is_stub;
});
if (hasStubDep) stubStatus = 'partial';
}
// Badge rendering
let stubBadge = '';
if (stubStatus === 'stub') {
stubBadge = '<span class="px-2 py-1 rounded bg-amber-100 text-amber-700 text-xs">Full Stub</span>';
} else if (stubStatus === 'partial') {
stubBadge = '<span class="px-2 py-1 rounded bg-orange-100 text-orange-700 text-xs">Partial</span>';
} else {
stubBadge = '<span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-xs">Real</span>';
}
return `
<tr class="hover:bg-slate-50 calcLogic-row" data-stubstatus="${stubStatus}" data-metric="${def.metric_path.toLowerCase()}">
<td class="py-3 px-4">
<span class="px-2 py-1 rounded bg-blue-100 text-blue-700 text-xs font-medium">${def.report_id || '-'}</span>
</td>
<td class="py-3 px-4 font-mono text-xs text-purple-600">${def.metric_path}</td>
<td class="py-3 px-4 font-medium">${def.label}</td>
<td class="py-3 px-4 text-xs text-slate-500">${description}</td>
<td class="py-3 px-4">
<span class="text-slate-600">${def.source_table || '-'}</span>
${def.source_column ? `<span class="text-slate-400">.${def.source_column}</span>` : ''}
</td>
<td class="py-3 px-4 font-mono text-xs">${def.formula || '-'}</td>
<td class="py-3 px-4 text-center">
${stubBadge}
</td>
</tr>
`}).join('');
console.log('[A.2] Loaded', result.data.length, 'data point definitions');
} catch (e) {
console.warn('[A.2] Could not load calculation logic:', e.message);
tbody.innerHTML = `<tr><td colspan="7" class="py-8 text-center text-rose-500"><i class="fa-solid fa-exclamation-triangle mr-2"></i>Error: ${e.message}</td></tr>`;
}
}
// Initialize - use larger font/node size for visibility
mermaid.initialize({
startOnLoad: false,
theme: 'neutral',
flowchart: {
nodeSpacing: 80,
rankSpacing: 100,
padding: 20,
useMaxWidth: false
},
themeVariables: {
fontSize: '16px',
fontFamily: 'ui-sans-serif, system-ui, sans-serif'
}
});
async function init() {
// Wait for CRMT.dal to be loaded (from dataLoader.js)
if (!window.CRMT || !window.CRMT.dal) {
console.log('[A.2] Waiting for CRMT.dal...');
setTimeout(init, 300);
return;
}
console.log('[A.2] CRMT.dal loaded, fetching competitors and scores from database...');
// Fetch competitors from database and store in CRMT.competitors
try {
const competitorResult = await CRMT.dal.getCompetitors();
if (competitorResult.data && competitorResult.data.length > 0) {
// Build CRMT.competitors map for use by other functions
window.CRMT.competitors = {};
for (const comp of competitorResult.data) {
window.CRMT.competitors[comp.id] = comp;
}
console.log(`[A.2] Loaded ${competitorResult.data.length} competitors from database`);
} else {
console.warn('[A.2] No competitors found in database');
window.CRMT.competitors = {};
}
} catch (e) {
console.warn('[A.2] Could not load competitors from DB:', e.message);
window.CRMT.competitors = {};
}
// Fetch scores from database and merge into competitors
try {
const reportIds = ['1.3', '2.1', '2.2', '2.3', '3.1', '3.2', '3.3', '4.1', '4.2', '4.3', '5.1', '5.2', '5.3', '6.1', '6.2', '6.3'];
for (const reportId of reportIds) {
const scores = await CRMT.dal.getScores(reportId);
if (scores && scores.length > 0) {
console.log(`[A.2] Loaded ${scores.length} scores for report ${reportId}`);
mergeScoresToCompetitors(reportId, scores);
}
}
} catch (e) {
console.warn('[A.2] Could not load scores from DB:', e.message);
}
refreshRegistry();
}
function mergeScoresToCompetitors(reportId, scores) {
const blockMap = {
'1.3': 'crmScorecard',
'2.1': 'content',
'2.2': 'content',
'2.3': 'content',
'3.1': 'compliance',
'3.2': 'alignment',
'3.3': 'compliance',
'4.1': 'license',
'4.2': 'risk',
'4.3': 'transparency',
'5.1': 'valuation',
'5.2': 'dueDiligence',
'5.3': 'migration',
'6.1': 'benchmarking',
'6.2': 'strategy',
'6.3': 'proposal'
};
const block = blockMap[reportId];
if (!block) return;
for (const scoreData of scores) {
const comp = CRMT.competitors[scoreData.competitor_id];
if (!comp) continue;
// Initialize block if missing
if (!comp[block]) comp[block] = {};
// Add section scores
for (const [section, data] of Object.entries(scoreData.sections || {})) {
if (!comp[block][section]) comp[block][section] = {};
comp[block][section].sectionScore = data.score;
}
// Add total
if (scoreData.total) comp[block].total = scoreData.total;
}
}
document.addEventListener('DOMContentLoaded', () => setTimeout(init, 500));
// === Recalculate All Scores ===
async function recalculateAllScores() {
const btn = document.getElementById('recalc-btn');
const originalHtml = btn.innerHTML;
try {
btn.disabled = true;
btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Calculating...';
btn.classList.replace('bg-emerald-600', 'bg-slate-400');
// Call scorecard API to recalculate for all competitors
const response = await fetch('/.netlify/functions/scorecard', { method: 'POST' });
if (response.ok) {
const result = await response.json();
console.log('[A.2] Score recalculation complete:', result);
btn.innerHTML = '<i class="fa-solid fa-check"></i> Done!';
btn.classList.replace('bg-slate-400', 'bg-green-500');
// Refresh the page data after 1 second
setTimeout(() => {
btn.innerHTML = originalHtml;
btn.classList.replace('bg-green-500', 'bg-emerald-600');
btn.disabled = false;
refreshRegistry();
}, 1500);
} else {
throw new Error(`API returned ${response.status}`);
}
} catch (error) {
console.error('[A.2] Score recalculation failed:', error);
btn.innerHTML = '<i class="fa-solid fa-xmark"></i> Failed';
btn.classList.replace('bg-slate-400', 'bg-red-500');
setTimeout(() => {
btn.innerHTML = originalHtml;
btn.classList.replace('bg-red-500', 'bg-emerald-600');
btn.disabled = false;
}, 2000);
}
}
// === Edit Modal Functions ===
let currentEditField = null;
// Toggle deprecated reason visibility
document.getElementById('edit-deprecated')?.addEventListener('change', function () {
document.getElementById('deprecated-reason-container').classList.toggle('hidden', !this.checked);
});
function openEditModal(fieldKey) {
currentEditField = fieldKey;
const field = currentRegistry?.fields[fieldKey];
if (!field) return;
document.getElementById('edit-field-key').textContent = fieldKey;
document.getElementById('edit-description').value = field.description || '';
document.getElementById('edit-owner').value = field.owner || '';
document.getElementById('edit-deprecated').checked = field.deprecated || false;
document.getElementById('edit-deprecated-reason').value = field.deprecatedReason || '';
document.getElementById('deprecated-reason-container').classList.toggle('hidden', !field.deprecated);
document.getElementById('edit-status').textContent = field.fromDatabase ? 'Loaded from database' : 'Local only';
const modal = document.getElementById('edit-modal');
modal.classList.remove('hidden');
modal.classList.add('flex');
}
function closeEditModal() {
currentEditField = null;
const modal = document.getElementById('edit-modal');
modal.classList.add('hidden');
modal.classList.remove('flex');
}
async function saveAnnotation() {
if (!currentEditField) return;
const data = {
description: document.getElementById('edit-description').value,
owner: document.getElementById('edit-owner').value,
deprecated: document.getElementById('edit-deprecated').checked,
deprecated_reason: document.getElementById('edit-deprecated-reason').value,
updated_by: 'admin'
};
const statusEl = document.getElementById('edit-status');
statusEl.textContent = 'Saving...';
try {
if (window.CRMT?.annotationsApi) {
await window.CRMT.annotationsApi.save(currentEditField, data);
statusEl.textContent = '✓ Saved to database';
statusEl.classList.add('text-emerald-600');
} else {
// Fallback: update local only
if (!window.CRMT.fieldAnnotations) window.CRMT.fieldAnnotations = {};
window.CRMT.fieldAnnotations[currentEditField] = data;
statusEl.textContent = '✓ Saved locally (database not connected)';
statusEl.classList.add('text-amber-600');
}
// Refresh table after save
setTimeout(() => {
refreshRegistry();
closeEditModal();
}, 800);
} catch (error) {
statusEl.textContent = '✗ Error: ' + error.message;
statusEl.classList.add('text-rose-600');
}
}
// Load Completeness Data
async function loadCompleteness() {
const tbody = document.getElementById('completeness-tbody');
tbody.innerHTML = '<tr><td colspan="8" class="py-8 text-center text-slate-400"><i class="fa-solid fa-spinner fa-spin mr-2"></i>Loading...</td></tr>';
try {
const result = await CRMT.dal.getHitsCompleteness();
const data = result.data || [];
if (data.length === 0) {
tbody.innerHTML = '<tr><td colspan="8" class="py-8 text-center text-slate-400">No completeness data yet. Upload hits to see stats.</td></tr>';
return;
}
let html = '';
for (const row of data) {
const d5Pct = row.avg_d5_pct || 0;
const m2Pct = row.avg_m2_pct || 0;
const m3Pct = row.avg_m3_pct || 0;
const overall = row.avg_overall_pct || 0;
const getPctClass = (pct) => pct >= 80 ? 'text-emerald-700 bg-emerald-50' : pct >= 50 ? 'text-amber-700 bg-amber-50' : 'text-rose-700 bg-rose-50';
const getPctBar = (pct) => `
<div class="flex items-center gap-2">
<div class="w-12 h-2 bg-slate-200 rounded-full overflow-hidden">
<div class="h-full ${pct >= 80 ? 'bg-emerald-500' : pct >= 50 ? 'bg-amber-500' : 'bg-rose-500'}" style="width: ${pct}%"></div>
</div>
<span class="text-xs ${getPctClass(pct)} px-1.5 py-0.5 rounded">${pct}%</span>
</div>
`;
// Find top gap (lowest module)
const modules = [
{ name: 'D.5', pct: d5Pct },
{ name: 'M.2', pct: m2Pct },
{ name: 'M.3', pct: m3Pct }
].sort((a, b) => a.pct - b.pct);
const topGap = modules[0].pct < 100 ? `${modules[0].name} (${100 - modules[0].pct}% missing)` : '—';
html += `
<tr class="hover:bg-slate-50">
<td class="py-3 px-4 font-medium">${row.competitor_name || row.competitor_id || '—'}</td>
<td class="py-3 px-4 text-slate-600">${row.market_id || '—'}</td>
<td class="py-3 px-4 text-center font-bold">${row.total_hits || 0}</td>
<td class="py-3 px-4">${getPctBar(d5Pct)}</td>
<td class="py-3 px-4">${getPctBar(m2Pct)}</td>
<td class="py-3 px-4">${getPctBar(m3Pct)}</td>
<td class="py-3 px-4 text-center">
<span class="font-bold ${getPctClass(overall)} px-2 py-1 rounded">${overall}%</span>
</td>
<td class="py-3 px-4 text-xs text-slate-500">${topGap}</td>
</tr>
`;
}
tbody.innerHTML = html;
console.log('[A.2 Completeness] Loaded', data.length, 'competitors');
} catch (error) {
console.error('[A.2 Completeness] Error:', error);
tbody.innerHTML = `<tr><td colspan="8" class="py-8 text-center text-rose-500">Error loading data: ${error.message}</td></tr>`;
}
}
</script>
@endsection

@push('page-scripts')
<script>
        // Tab switching
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
                btn.classList.add('active');
                document.getElementById('tab-' + btn.dataset.tab).classList.remove('hidden');

                if (btn.dataset.tab === 'graph') renderGraph();
            });
        });

        let currentRegistry = null;

        function refreshRegistry() {
            if (!window.CRMT?.buildFieldRegistry) {
                setTimeout(refreshRegistry, 300);
                return;
            }
            currentRegistry = window.CRMT.buildFieldRegistry();
            updateStats();
            renderSourceTable();
            renderReportTable();
        }

        function updateStats() {
            if (!currentRegistry) return;
            document.getElementById('stat-total').textContent = currentRegistry.stats.totalFields;
            document.getElementById('stat-used').textContent = currentRegistry.stats.usedFields;
            document.getElementById('stat-orphaned').textContent = currentRegistry.stats.orphanedFields;
            document.getElementById('stat-incomplete').textContent = currentRegistry.stats.incompleteFields;
        }

        function getStatusBadge(status) {
            const badges = {
                'active': '<span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded">✓ Active</span>',
                'incomplete': '<span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded">⚠ Incomplete</span>',
                'orphaned': '<span class="text-xs bg-rose-100 text-rose-700 px-2 py-0.5 rounded">✗ Orphaned</span>',
                'api-sourced': '<span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded">🗄 DB-Sourced</span>',
                'no-data': '<span class="text-xs bg-slate-200 text-slate-600 px-2 py-0.5 rounded">⊘ No Data</span>'
            };
            return badges[status] || status;
        }

        function getTypeBadge(type) {
            const colors = { bool: 'purple', pct: 'blue', score: 'emerald', text: 'slate', number: 'indigo', check: 'green' };
            return `<span class="text-xs bg-${colors[type] || 'slate'}-100 text-${colors[type] || 'slate'}-700 px-2 py-0.5 rounded">${type || 'unknown'}</span>`;
        }

        // Get origin from field key: Excel Import / API / Calculated / Stub
        function getFieldOrigin(fieldKey) {
            // Check definitions first
            const def = window.definitions?.find(d => d.metric_path === fieldKey);
            if (def) {
                const description = def.label || '';
                if (def.is_stub) return { type: 'stub', badge: '<span class="px-2 py-0.5 rounded text-xs bg-amber-100 text-amber-700">Stub</span>', description };
                if (def.source_table === 'hits') return { type: 'excel', badge: '<span class="px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-700">Excel Import</span>', description };
                if (def.source_table === 'competitors') return { type: 'profile', badge: '<span class="px-2 py-0.5 rounded text-xs bg-green-100 text-green-700">Profile</span>', description };
                if (def.source_table === 'calculated') return { type: 'calc', badge: '<span class="px-2 py-0.5 rounded text-xs bg-purple-100 text-purple-700">Calculated</span>', description };
                return { type: 'api', badge: '<span class="px-2 py-0.5 rounded text-xs bg-cyan-100 text-cyan-700">API</span>', description };
            }
            // Infer from field key pattern
            if (fieldKey.includes('compliance') || fieldKey.includes('content')) {
                return { type: 'excel', badge: '<span class="px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-700">Excel Import</span>', description: '' };
            }
            if (fieldKey.includes('license') || fieldKey.includes('profile')) {
                return { type: 'profile', badge: '<span class="px-2 py-0.5 rounded text-xs bg-green-100 text-green-700">Profile</span>', description: '' };
            }
            if (fieldKey.includes('score') || fieldKey.includes('Index')) {
                return { type: 'calc', badge: '<span class="px-2 py-0.5 rounded text-xs bg-purple-100 text-purple-700">Calculated</span>', description: '' };
            }
            return { type: 'unknown', badge: '<span class="px-2 py-0.5 rounded text-xs bg-slate-100 text-slate-600">TBD</span>', description: '' };
        }

        function renderSourceTable() {
            if (!currentRegistry) return;
            const tbody = document.getElementById('source-tbody');
            const fields = Object.values(currentRegistry.fields);

            // Group by data block
            const grouped = {};
            for (const f of fields) {
                if (!grouped[f.dataBlock]) grouped[f.dataBlock] = [];
                grouped[f.dataBlock].push(f);
            }

            let html = '';
            for (const [block, blockFields] of Object.entries(grouped)) {
                for (const f of blockFields) {
                    const usedIn = f.usedIn.map(r => `<a href="${currentRegistry.reports[r]?.link || '#'}" class="text-blue-600 hover:underline">${r}</a>`).join(', ') || '—';
                    const origin = getFieldOrigin(f.key);
                    html += `
                        <tr class="hover:bg-slate-50 field-row" data-status="${f.status}" data-key="${f.key}" data-origin="${origin.type}">
                            <td class="py-3 px-4"><code class="text-xs bg-slate-100 px-2 py-0.5 rounded">${f.dataBlock}</code></td>
                            <td class="py-3 px-4">
                                <code class="text-xs">${f.key.replace(f.dataBlock + '.', '')}</code>
                                ${origin.description && !f.description ? `<p class="text-xs text-slate-500 mt-1">${origin.description}</p>` : ''}
                                ${f.description ? `<p class="text-xs text-slate-500 mt-1">${f.description}</p>` : ''}
                                ${f.noDataReason ? `<p class="text-xs text-slate-400 mt-0.5 italic">⊘ ${f.noDataReason}</p>` : ''}
                            </td>
                            <td class="py-3 px-4 text-center">${getTypeBadge(f.type)}</td>
                            <td class="py-3 px-4 text-center">${origin.badge}</td>
                            <td class="py-3 px-4">${usedIn}</td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center gap-2 justify-center">
                                    <div class="w-16 h-2 bg-slate-200 rounded-full overflow-hidden">
                                        <div class="h-full ${f.completeness >= 80 ? 'bg-emerald-500' : f.completeness >= 50 ? 'bg-amber-500' : 'bg-rose-500'}" style="width: ${f.completeness}%"></div>
                                    </div>
                                    <span class="text-xs text-slate-600">${f.completeness}%</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">${getStatusBadge(f.status)}</td>
                            <td class="py-3 px-4 text-center">
                                <button onclick="openEditModal('${f.key}')" class="text-blue-600 hover:text-blue-800" title="Edit annotation">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                }
            }
            tbody.innerHTML = html;
        }

        function filterSourceTable() {
            const search = document.getElementById('source-search').value.toLowerCase();
            const statusFilter = document.getElementById('source-filter').value;

            document.querySelectorAll('.field-row').forEach(row => {
                const key = row.dataset.key.toLowerCase();
                const status = row.dataset.status;
                const matchesSearch = !search || key.includes(search);
                const matchesStatus = statusFilter === 'all' || status === statusFilter;
                row.style.display = matchesSearch && matchesStatus ? '' : 'none';
            });
        }

        // Filter Calculation Logic table by search and stub status
        function filterCalcLogic() {
            const search = document.getElementById('calcLogic-search').value.toLowerCase();
            const stubFilter = document.getElementById('calcLogic-filter').value;

            document.querySelectorAll('.calcLogic-row').forEach(row => {
                const metric = row.dataset.metric || '';
                const stubStatus = row.dataset.stubstatus || 'real';
                const matchesSearch = !search || metric.includes(search);
                const matchesStub = stubFilter === 'all' ||
                    (stubFilter === 'stub' && stubStatus === 'stub') ||
                    (stubFilter === 'partial' && stubStatus === 'partial') ||
                    (stubFilter === 'real' && stubStatus === 'real');
                row.style.display = matchesSearch && matchesStub ? '' : 'none';
            });
        }

        function renderReportTable() {
            if (!currentRegistry) return;
            const tbody = document.getElementById('report-tbody');

            // Sort reports by module and submodule (1.1, 1.2, 1.3, ... 2.1, 2.2, ... D.1, D.2, ...)
            const sortedReports = Object.entries(currentRegistry.reports).sort(([a], [b]) => {
                // Extract module prefix and number
                const parseId = (id) => {
                    const match = id.match(/^([A-Z]?)(\d*)\.?(\d*)$/i);
                    if (!match) return { prefix: id, major: 0, minor: 0 };
                    const prefix = match[1] || '';
                    const major = parseInt(match[2]) || 0;
                    const minor = parseInt(match[3]) || 0;
                    return { prefix, major, minor };
                };

                const aP = parseId(a), bP = parseId(b);
                // Numeric reports (1.x, 2.x, etc.) come before letter reports (A.x, D.x)
                if (aP.prefix !== bP.prefix) {
                    if (!aP.prefix) return -1;
                    if (!bP.prefix) return 1;
                    return aP.prefix.localeCompare(bP.prefix);
                }
                if (aP.major !== bP.major) return aP.major - bP.major;
                return aP.minor - bP.minor;
            });

            let html = '';
            for (const [reportId, report] of sortedReports) {
                const healthColor = report.health >= 80 ? 'emerald' : report.health >= 50 ? 'amber' : 'rose';
                const blocks = report.dataBlocks.map(b => `<code class="text-xs bg-slate-100 px-2 py-0.5 rounded">${b}</code>`).join(' ');

                html += `
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4"><span class="font-bold text-blue-600">${reportId}</span></td>
                        <td class="py-3 px-4 font-medium">${report.name}</td>
                        <td class="py-3 px-4 text-center">${report.fields.length}</td>
                        <td class="py-3 px-4">${blocks}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="text-lg font-bold text-${healthColor}-600">${report.health}%</span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <a href="${report.link}" class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-arrow-right"></i></a>
                        </td>
                    </tr>
                `;
            }
            tbody.innerHTML = html;
        }

        async function renderGraph() {
            const container = document.getElementById('mermaid-container');
            container.innerHTML = '<div class="py-8 text-center text-slate-500"><i class="fa-solid fa-spinner fa-spin mr-2"></i>Loading data flow...</div>';

            let definitions = [];
            try {
                if (window.CRMT?.dal?.getDataPointDefinitions) {
                    const result = await CRMT.dal.getDataPointDefinitions();
                    definitions = result.data || [];
                }
            } catch (e) {
                console.warn('[A.2] Could not load definitions:', e.message);
            }

            if (definitions.length === 0) {
                container.innerHTML = '<div class="py-8 text-center text-slate-500">No definitions found. Run seedDataPointDefinitions() first.</div>';
                return;
            }

            // Collect unique source columns and group metrics by report
            const sourceColumns = new Map(); // column -> [{def, aggregation}]
            const calculatedMetrics = [];
            const byReport = {};

            definitions.forEach(def => {
                const reportId = def.report_id || 'unk';
                if (!byReport[reportId]) byReport[reportId] = [];
                byReport[reportId].push(def);

                if (def.source_table === 'hits' && def.source_column) {
                    if (!sourceColumns.has(def.source_column)) {
                        sourceColumns.set(def.source_column, []);
                    }
                    sourceColumns.get(def.source_column).push(def);
                } else if (def.source_table === 'calculated') {
                    calculatedMetrics.push(def);
                }
            });

            // Collect unique aggregation types
            const aggTypes = new Set();
            definitions.forEach(def => {
                if (def.aggregation && def.source_table === 'hits') {
                    aggTypes.add(def.aggregation);
                }
            });

            // Helper to sanitize node IDs (only alphanumeric and underscore)
            function sanitizeId(str) {
                return (str || 'unknown').replace(/[^a-zA-Z0-9]/g, '_');
            }

            // Helper to escape labels for Mermaid (remove quotes, brackets, special chars)
            function sanitizeLabel(str) {
                return (str || '')
                    .replace(/"/g, "'")
                    .replace(/[<>]/g, '')
                    .replace(/[\[\]()&]/g, '')
                    .replace(/\n/g, ' ')
                    .substring(0, 25);
            }

            // Build simpler graph without nested subgraphs
            // Use actual newline character, not escaped
            const NL = '\n';
            let graph = 'flowchart LR' + NL;

            // Track all defined nodes to avoid referencing undefined nodes
            const definedNodes = new Set();

            // Source columns
            graph += NL + '    %% Source Columns' + NL;
            [...sourceColumns.keys()].sort().forEach(col => {
                const nodeId = 'src_' + sanitizeId(col);
                definedNodes.add(nodeId);
                graph += '    ' + nodeId + '[[\"' + sanitizeLabel(col) + '\"]]' + NL;
            });

            // Aggregation types
            graph += NL + '    %% Aggregations' + NL;
            [...aggTypes].sort().forEach(agg => {
                const nodeId = 'agg_' + sanitizeId(agg);
                definedNodes.add(nodeId);
                graph += '    ' + nodeId + '((\"' + sanitizeLabel(agg) + '\"))' + NL;
            });

            // Report metrics - use full path for unique ID
            graph += NL + '    %% Report Metrics' + NL;
            definitions.forEach(def => {
                const nodeId = 'M_' + sanitizeId(def.metric_path);
                definedNodes.add(nodeId);
                const label = sanitizeLabel(def.label);
                const stub = def.is_stub ? '*' : '';
                graph += '    ' + nodeId + '[\"' + def.report_id + ': ' + label + stub + '\"]' + NL;
            });

            // Edges: Source -> Aggregation -> Metric
            graph += NL + '    %% Data Flow Edges' + NL;
            definitions.forEach(def => {
                if (def.source_table === 'hits' && def.source_column && def.aggregation) {
                    const srcNode = 'src_' + sanitizeId(def.source_column);
                    const aggNode = 'agg_' + sanitizeId(def.aggregation);
                    const metricNode = 'M_' + sanitizeId(def.metric_path);

                    if (definedNodes.has(srcNode) && definedNodes.has(aggNode)) {
                        graph += '    ' + srcNode + ' --> ' + aggNode + NL;
                    }
                    if (definedNodes.has(aggNode) && definedNodes.has(metricNode)) {
                        graph += '    ' + aggNode + ' --> ' + metricNode + NL;
                    }
                }
            });

            // Calculated metric dependencies (use dotted lines)
            graph += NL + '    %% Calculated Dependencies' + NL;
            calculatedMetrics.forEach(def => {
                const metricNode = 'M_' + sanitizeId(def.metric_path);
                if (def.dependencies && def.dependencies.length > 0) {
                    def.dependencies.forEach(depPath => {
                        const depNode = 'M_' + sanitizeId(depPath);
                        if (definedNodes.has(depNode) && definedNodes.has(metricNode)) {
                            graph += '    ' + depNode + ' -.-> ' + metricNode + NL;
                        }
                    });
                }
            });

            // Style nodes
            graph += NL + '    %% Styling' + NL;
            graph += '    classDef source fill:#dbeafe,stroke:#3b82f6' + NL;
            graph += '    classDef agg fill:#fef3c7,stroke:#f59e0b' + NL;
            graph += '    classDef metric fill:#f1f5f9,stroke:#64748b' + NL;
            [...sourceColumns.keys()].forEach(col => {
                graph += '    class src_' + sanitizeId(col) + ' source' + NL;
            });
            [...aggTypes].forEach(agg => {
                graph += '    class agg_' + sanitizeId(agg) + ' agg' + NL;
            });

            console.log('[A.2] Graph with', definitions.length, 'definitions,', definedNodes.size, 'nodes');
            console.log('[A.2] Generated Mermaid graph:', graph.substring(0, 500) + '...');

            try {
                container.innerHTML = `<pre class="mermaid">${graph}</pre>`;
                await mermaid.init(undefined, container.querySelector('.mermaid'));
            } catch (err) {
                console.error('[A.2] Mermaid render error:', err);
                container.innerHTML = `
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-red-600 font-medium mb-2"><i class="fa-solid fa-exclamation-triangle mr-2"></i>Graph render failed</p>
                        <p class="text-sm text-red-500 mb-2">Mermaid error: ${err?.message || 'Unknown error'}</p>
                        <details class="mt-2">
                            <summary class="text-xs text-slate-500 cursor-pointer">Show raw graph code</summary>
                            <pre class="mt-2 p-2 bg-slate-100 text-xs overflow-auto max-h-64">${graph.replace(/</g, '&lt;').replace(/>/g, '&gt;')}</pre>
                        </details>
                    </div>
                `;
            }
        }

        // ===== CALCULATION LOGIC TAB =====
        async function loadCalculationLogic() {
            const tbody = document.getElementById('calcLogic-tbody');
            tbody.innerHTML = '<tr><td colspan="7" class="py-8 text-center text-slate-500"><i class="fa-solid fa-spinner fa-spin mr-2"></i>Loading...</td></tr>';

            try {
                if (!window.CRMT?.dal?.getDataPointDefinitions) {
                    throw new Error('DAL not available');
                }

                const result = await CRMT.dal.getDataPointDefinitions();
                if (!result.data || result.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" class="py-8 text-center text-slate-500">No definitions found. Run seedDataPointDefinitions() to populate.</td></tr>';
                    return;
                }

                tbody.innerHTML = result.data.map(def => {
                    // Generate description from source or formula info
                    let description = '';
                    if (def.is_stub) {
                        description = 'Requires: ' + (def.formula || 'external data').substring(0, 40);
                    } else if (def.source_table === 'hits' && def.source_column) {
                        description = 'From Excel: ' + def.source_column;
                    } else if (def.source_table === 'competitors') {
                        description = 'From competitor profile';
                    } else if (def.source_table === 'calculated') {
                        const deps = def.dependencies ? def.dependencies.slice(0, 2).map(d => d.split('.').pop()).join(', ') : '';
                        description = deps ? 'Derived from: ' + deps : 'Calculated value';
                    }

                    // Determine stub status: real, partial, or stub
                    // Partial = has any dependency that is a stub
                    let stubStatus = 'real';
                    if (def.is_stub) {
                        stubStatus = 'stub';
                    } else if (def.dependencies && def.dependencies.length > 0) {
                        const hasStubDep = def.dependencies.some(depPath => {
                            const depDef = result.data.find(d => d.metric_path === depPath);
                            return depDef && depDef.is_stub;
                        });
                        if (hasStubDep) stubStatus = 'partial';
                    }

                    // Badge rendering
                    let stubBadge = '';
                    if (stubStatus === 'stub') {
                        stubBadge = '<span class="px-2 py-1 rounded bg-amber-100 text-amber-700 text-xs">Full Stub</span>';
                    } else if (stubStatus === 'partial') {
                        stubBadge = '<span class="px-2 py-1 rounded bg-orange-100 text-orange-700 text-xs">Partial</span>';
                    } else {
                        stubBadge = '<span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-xs">Real</span>';
                    }

                    return `
                    <tr class="hover:bg-slate-50 calcLogic-row" data-stubstatus="${stubStatus}" data-metric="${def.metric_path.toLowerCase()}">
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded bg-blue-100 text-blue-700 text-xs font-medium">${def.report_id || '-'}</span>
                        </td>
                        <td class="py-3 px-4 font-mono text-xs text-purple-600">${def.metric_path}</td>
                        <td class="py-3 px-4 font-medium">${def.label}</td>
                        <td class="py-3 px-4 text-xs text-slate-500">${description}</td>
                        <td class="py-3 px-4">
                            <span class="text-slate-600">${def.source_table || '-'}</span>
                            ${def.source_column ? `<span class="text-slate-400">.${def.source_column}</span>` : ''}
                        </td>
                        <td class="py-3 px-4 font-mono text-xs">${def.formula || '-'}</td>
                        <td class="py-3 px-4 text-center">
                            ${stubBadge}
                        </td>
                    </tr>
                `}).join('');

                console.log('[A.2] Loaded', result.data.length, 'data point definitions');
            } catch (e) {
                console.warn('[A.2] Could not load calculation logic:', e.message);
                tbody.innerHTML = `<tr><td colspan="7" class="py-8 text-center text-rose-500"><i class="fa-solid fa-exclamation-triangle mr-2"></i>Error: ${e.message}</td></tr>`;
            }
        }

        // Initialize - use larger font/node size for visibility
        mermaid.initialize({
            startOnLoad: false,
            theme: 'neutral',
            flowchart: {
                nodeSpacing: 80,
                rankSpacing: 100,
                padding: 20,
                useMaxWidth: false
            },
            themeVariables: {
                fontSize: '16px',
                fontFamily: 'ui-sans-serif, system-ui, sans-serif'
            }
        });

        async function init() {
            // Wait for CRMT.dal to be loaded (from dataLoader.js)
            if (!window.CRMT || !window.CRMT.dal) {
                console.log('[A.2] Waiting for CRMT.dal...');
                setTimeout(init, 300);
                return;
            }
            console.log('[A.2] CRMT.dal loaded, fetching competitors and scores from database...');

            // Fetch competitors from database and store in CRMT.competitors
            try {
                const competitorResult = await CRMT.dal.getCompetitors();
                if (competitorResult.data && competitorResult.data.length > 0) {
                    // Build CRMT.competitors map for use by other functions
                    window.CRMT.competitors = {};
                    for (const comp of competitorResult.data) {
                        window.CRMT.competitors[comp.id] = comp;
                    }
                    console.log(`[A.2] Loaded ${competitorResult.data.length} competitors from database`);
                } else {
                    console.warn('[A.2] No competitors found in database');
                    window.CRMT.competitors = {};
                }
            } catch (e) {
                console.warn('[A.2] Could not load competitors from DB:', e.message);
                window.CRMT.competitors = {};
            }

            // Fetch scores from database and merge into competitors
            try {
                const reportIds = ['1.3', '2.1', '2.2', '2.3', '3.1', '3.2', '3.3', '4.1', '4.2', '4.3', '5.1', '5.2', '5.3', '6.1', '6.2', '6.3'];
                for (const reportId of reportIds) {
                    const scores = await CRMT.dal.getScores(reportId);
                    if (scores && scores.length > 0) {
                        console.log(`[A.2] Loaded ${scores.length} scores for report ${reportId}`);
                        mergeScoresToCompetitors(reportId, scores);
                    }
                }
            } catch (e) {
                console.warn('[A.2] Could not load scores from DB:', e.message);
            }

            refreshRegistry();
        }

        function mergeScoresToCompetitors(reportId, scores) {
            const blockMap = {
                '1.3': 'crmScorecard',
                '2.1': 'content',
                '2.2': 'content',
                '2.3': 'content',
                '3.1': 'compliance',
                '3.2': 'alignment',
                '3.3': 'compliance',
                '4.1': 'license',
                '4.2': 'risk',
                '4.3': 'transparency',
                '5.1': 'valuation',
                '5.2': 'dueDiligence',
                '5.3': 'migration',
                '6.1': 'benchmarking',
                '6.2': 'strategy',
                '6.3': 'proposal'
            };
            const block = blockMap[reportId];
            if (!block) return;

            for (const scoreData of scores) {
                const comp = CRMT.competitors[scoreData.competitor_id];
                if (!comp) continue;

                // Initialize block if missing
                if (!comp[block]) comp[block] = {};

                // Add section scores
                for (const [section, data] of Object.entries(scoreData.sections || {})) {
                    if (!comp[block][section]) comp[block][section] = {};
                    comp[block][section].sectionScore = data.score;
                }

                // Add total
                if (scoreData.total) comp[block].total = scoreData.total;
            }
        }

        document.addEventListener('DOMContentLoaded', () => setTimeout(init, 500));

        // === Recalculate All Scores ===
        async function recalculateAllScores() {
            const btn = document.getElementById('recalc-btn');
            const originalHtml = btn.innerHTML;

            try {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Calculating...';
                btn.classList.replace('bg-emerald-600', 'bg-slate-400');

                // Call scorecard API to recalculate for all competitors
                const response = await fetch('/.netlify/functions/scorecard', { method: 'POST' });

                if (response.ok) {
                    const result = await response.json();
                    console.log('[A.2] Score recalculation complete:', result);
                    btn.innerHTML = '<i class="fa-solid fa-check"></i> Done!';
                    btn.classList.replace('bg-slate-400', 'bg-green-500');

                    // Refresh the page data after 1 second
                    setTimeout(() => {
                        btn.innerHTML = originalHtml;
                        btn.classList.replace('bg-green-500', 'bg-emerald-600');
                        btn.disabled = false;
                        refreshRegistry();
                    }, 1500);
                } else {
                    throw new Error(`API returned ${response.status}`);
                }
            } catch (error) {
                console.error('[A.2] Score recalculation failed:', error);
                btn.innerHTML = '<i class="fa-solid fa-xmark"></i> Failed';
                btn.classList.replace('bg-slate-400', 'bg-red-500');

                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                    btn.classList.replace('bg-red-500', 'bg-emerald-600');
                    btn.disabled = false;
                }, 2000);
            }
        }

        // === Edit Modal Functions ===
        let currentEditField = null;

        // Toggle deprecated reason visibility
        document.getElementById('edit-deprecated')?.addEventListener('change', function () {
            document.getElementById('deprecated-reason-container').classList.toggle('hidden', !this.checked);
        });

        function openEditModal(fieldKey) {
            currentEditField = fieldKey;
            const field = currentRegistry?.fields[fieldKey];
            if (!field) return;

            document.getElementById('edit-field-key').textContent = fieldKey;
            document.getElementById('edit-description').value = field.description || '';
            document.getElementById('edit-owner').value = field.owner || '';
            document.getElementById('edit-deprecated').checked = field.deprecated || false;
            document.getElementById('edit-deprecated-reason').value = field.deprecatedReason || '';
            document.getElementById('deprecated-reason-container').classList.toggle('hidden', !field.deprecated);
            document.getElementById('edit-status').textContent = field.fromDatabase ? 'Loaded from database' : 'Local only';

            const modal = document.getElementById('edit-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEditModal() {
            currentEditField = null;
            const modal = document.getElementById('edit-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        async function saveAnnotation() {
            if (!currentEditField) return;

            const data = {
                description: document.getElementById('edit-description').value,
                owner: document.getElementById('edit-owner').value,
                deprecated: document.getElementById('edit-deprecated').checked,
                deprecated_reason: document.getElementById('edit-deprecated-reason').value,
                updated_by: 'admin'
            };

            const statusEl = document.getElementById('edit-status');
            statusEl.textContent = 'Saving...';

            try {
                if (window.CRMT?.annotationsApi) {
                    await window.CRMT.annotationsApi.save(currentEditField, data);
                    statusEl.textContent = '✓ Saved to database';
                    statusEl.classList.add('text-emerald-600');
                } else {
                    // Fallback: update local only
                    if (!window.CRMT.fieldAnnotations) window.CRMT.fieldAnnotations = {};
                    window.CRMT.fieldAnnotations[currentEditField] = data;
                    statusEl.textContent = '✓ Saved locally (database not connected)';
                    statusEl.classList.add('text-amber-600');
                }

                // Refresh table after save
                setTimeout(() => {
                    refreshRegistry();
                    closeEditModal();
                }, 800);
            } catch (error) {
                statusEl.textContent = '✗ Error: ' + error.message;
                statusEl.classList.add('text-rose-600');
            }
        }

        // Load Completeness Data
        async function loadCompleteness() {
            const tbody = document.getElementById('completeness-tbody');
            tbody.innerHTML = '<tr><td colspan="8" class="py-8 text-center text-slate-400"><i class="fa-solid fa-spinner fa-spin mr-2"></i>Loading...</td></tr>';

            try {
                const result = await CRMT.dal.getHitsCompleteness();
                const data = result.data || [];

                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="8" class="py-8 text-center text-slate-400">No completeness data yet. Upload hits to see stats.</td></tr>';
                    return;
                }

                let html = '';
                for (const row of data) {
                    const d5Pct = row.avg_d5_pct || 0;
                    const m2Pct = row.avg_m2_pct || 0;
                    const m3Pct = row.avg_m3_pct || 0;
                    const overall = row.avg_overall_pct || 0;

                    const getPctClass = (pct) => pct >= 80 ? 'text-emerald-700 bg-emerald-50' : pct >= 50 ? 'text-amber-700 bg-amber-50' : 'text-rose-700 bg-rose-50';
                    const getPctBar = (pct) => `
                        <div class="flex items-center gap-2">
                            <div class="w-12 h-2 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full ${pct >= 80 ? 'bg-emerald-500' : pct >= 50 ? 'bg-amber-500' : 'bg-rose-500'}" style="width: ${pct}%"></div>
                            </div>
                            <span class="text-xs ${getPctClass(pct)} px-1.5 py-0.5 rounded">${pct}%</span>
                        </div>
                    `;

                    // Find top gap (lowest module)
                    const modules = [
                        { name: 'D.5', pct: d5Pct },
                        { name: 'M.2', pct: m2Pct },
                        { name: 'M.3', pct: m3Pct }
                    ].sort((a, b) => a.pct - b.pct);
                    const topGap = modules[0].pct < 100 ? `${modules[0].name} (${100 - modules[0].pct}% missing)` : '—';

                    html += `
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 font-medium">${row.competitor_name || row.competitor_id || '—'}</td>
                            <td class="py-3 px-4 text-slate-600">${row.market_id || '—'}</td>
                            <td class="py-3 px-4 text-center font-bold">${row.total_hits || 0}</td>
                            <td class="py-3 px-4">${getPctBar(d5Pct)}</td>
                            <td class="py-3 px-4">${getPctBar(m2Pct)}</td>
                            <td class="py-3 px-4">${getPctBar(m3Pct)}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="font-bold ${getPctClass(overall)} px-2 py-1 rounded">${overall}%</span>
                            </td>
                            <td class="py-3 px-4 text-xs text-slate-500">${topGap}</td>
                        </tr>
                    `;
                }
                tbody.innerHTML = html;
                console.log('[A.2 Completeness] Loaded', data.length, 'competitors');
            } catch (error) {
                console.error('[A.2 Completeness] Error:', error);
                tbody.innerHTML = `<tr><td colspan="8" class="py-8 text-center text-rose-500">Error loading data: ${error.message}</td></tr>`;
            }
        }
    </script>
@endpush
