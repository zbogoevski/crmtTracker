@extends('layouts.dashboard')


@section('title', 'CRMTracker® Data Pipeline Manager')

@push('styles')
<style>
body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-hero {
            background: linear-gradient(135deg, #0F172A 0%, #4F46E5 50%, #0F172A 100%);
        }

        .tab-active {
            background: #4F46E5;
            color: white;
        }

        .tab-inactive {
            background: #E2E8F0;
            color: #475569;
        }

        .entity-row:hover {
            background: #F8FAFC;
        }

        .status-pass {
            background: #DCFCE7;
            color: #166534;
        }

        .status-warning {
            background: #FEF9C3;
            color: #854D0E;
        }

        .status-fail {
            background: #FEE2E2;
            color: #991B1B;
        }
</style>
@endpush

@section('content')
<!-- Sidebar Navigation -->
<!-- Main Content -->
<main class="ml-72 p-8">
<!-- Header -->
<div class="gradient-hero rounded-2xl p-8 mb-8 text-white shadow-xl">
<div class="flex items-start justify-between">
<div>
<div class="flex items-center gap-3 mb-2">
<h2 class="text-3xl font-bold">Data Pipeline Manager</h2>
<span class="text-xs bg-white/20 text-white px-2 py-1 rounded-full">
<i class="fa-solid fa-database mr-1"></i>A.4
</span>
</div>
<p class="text-slate-300 max-w-xl">Manage external datasets: jurisdictions, brands, licenses, and
more.</p>
</div>
<div class="flex gap-4">
<div id="stats-box" class="bg-white/10 rounded-xl p-4 backdrop-blur border border-white/20">
<p class="text-xs font-bold text-slate-300 uppercase mb-1">Total Records</p>
<p id="total-records" class="text-2xl font-bold">--</p>
</div>
<div class="bg-white/10 rounded-xl p-4 backdrop-blur border border-white/20">
<p class="text-xs font-bold text-slate-300 uppercase mb-1">Last Health Check</p>
<p id="last-check" class="text-sm">Never</p>
</div>
</div>
</div>
</div>
<!-- Tab Navigation -->
<div class="flex gap-2 mb-6 flex-wrap">
<!-- Regulatory Bundle -->
<button onclick="showTab('regulators')" id="tab-regulators"
class="tab-btn px-4 py-2 rounded-lg font-medium text-sm tab-active">
<i class="fa-solid fa-gavel mr-2"></i>Regulators
</button>
<button onclick="showTab('markets')" id="tab-markets"
class="tab-btn px-4 py-2 rounded-lg font-medium text-sm tab-inactive">
<i class="fa-solid fa-globe mr-2"></i>Jurisdictions
</button>
<button onclick="showTab('regulations')" id="tab-regulations"
class="tab-btn px-4 py-2 rounded-lg font-medium text-sm tab-inactive">
<i class="fa-solid fa-scale-balanced mr-2"></i>Regulations
</button>
<span class="border-l border-slate-300 mx-1"></span>
<!-- Brand/Company Bundle -->
<button onclick="showTab('companies')" id="tab-companies"
class="tab-btn px-4 py-2 rounded-lg font-medium text-sm tab-inactive">
<i class="fa-solid fa-briefcase mr-2"></i>Companies
</button>
<button onclick="showTab('brands')" id="tab-brands"
class="tab-btn px-4 py-2 rounded-lg font-medium text-sm tab-inactive">
<i class="fa-solid fa-building mr-2"></i>Brands
</button>
<button onclick="showTab('licenses')" id="tab-licenses"
class="tab-btn px-4 py-2 rounded-lg font-medium text-sm tab-inactive">
<i class="fa-solid fa-certificate mr-2"></i>Licenses
</button>
<button onclick="showTab('brand_websites')" id="tab-brand_websites"
class="tab-btn px-4 py-2 rounded-lg font-medium text-sm tab-inactive">
<i class="fa-solid fa-link mr-2"></i>Brand Websites
</button>
<span class="border-l border-slate-300 mx-1"></span>
<!-- Other -->
<button onclick="showTab('payment_methods')" id="tab-payment_methods"
class="tab-btn px-4 py-2 rounded-lg font-medium text-sm tab-inactive">
<i class="fa-solid fa-credit-card mr-2"></i>Payment Methods
</button>
</div>
<!-- Entity Panel -->
<div id="entity-panel" class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
<!-- Toolbar -->
<div class="flex justify-between items-center mb-4">
<div class="flex gap-2">
<input type="text" id="search-input" placeholder="Search..."
class="px-4 py-2 border border-slate-200 rounded-lg text-sm w-64 focus:outline-none focus:ring-2 focus:ring-indigo-500">
<button onclick="loadData()"
class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm hover:bg-slate-200">
<i class="fa-solid fa-magnifying-glass"></i>
</button>
</div>
<div class="flex gap-2">
<button onclick="showImportModal()"
class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
<i class="fa-solid fa-file-import mr-2"></i>Import
</button>
<button onclick="showAddModal()"
class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700">
<i class="fa-solid fa-plus mr-2"></i>Add New
</button>
</div>
</div>
<!-- Data Table -->
<div class="overflow-x-auto">
<table class="w-full text-sm" id="data-table">
<thead class="bg-slate-50 border-b border-slate-200">
<tr id="table-header">
<!-- Dynamic headers -->
</tr>
</thead>
<tbody id="table-body">
<!-- Dynamic rows -->
</tbody>
</table>
</div>
<!-- Pagination -->
<div class="flex justify-between items-center mt-4">
<p id="pagination-info" class="text-sm text-slate-500">Showing 0 of 0</p>
<div class="flex gap-2">
<button onclick="prevPage()" id="prev-btn"
class="px-3 py-1 bg-slate-100 rounded text-sm disabled:opacity-50" disabled>
<i class="fa-solid fa-chevron-left"></i> Prev
</button>
<button onclick="nextPage()" id="next-btn"
class="px-3 py-1 bg-slate-100 rounded text-sm disabled:opacity-50" disabled>
Next <i class="fa-solid fa-chevron-right"></i>
</button>
</div>
</div>
</div>
</main>
<!-- Import Modal -->
<div id="import-modal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
<div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
<div class="p-6 border-b border-slate-200">
<div class="flex justify-between items-center">
<h3 class="text-xl font-bold text-slate-800">
<i class="fa-solid fa-file-import text-blue-500 mr-2"></i>Import <span
id="import-type">Data</span>
</h3>
<button onclick="closeImportModal()" class="text-slate-400 hover:text-slate-600">
<i class="fa-solid fa-xmark text-xl"></i>
</button>
</div>
</div>
<div class="p-6">
<!-- Import Type Toggle -->
<div class="flex gap-4 mb-4">
<label class="flex items-center gap-2 cursor-pointer">
<input type="radio" name="import-format" value="csv" checked class="text-indigo-600">
<span class="text-sm">CSV Upload</span>
</label>
<label class="flex items-center gap-2 cursor-pointer">
<input type="radio" name="import-format" value="json" class="text-indigo-600">
<span class="text-sm">Paste JSON</span>
</label>
</div>
<!-- CSV Upload -->
<div id="csv-upload" class="mb-4">
<div
class="border-2 border-dashed border-slate-300 rounded-lg p-8 text-center hover:border-indigo-500 transition-colors">
<input type="file" id="csv-file" accept=".csv" class="hidden">
<label for="csv-file" class="cursor-pointer">
<i class="fa-solid fa-cloud-arrow-up text-4xl text-slate-400 mb-2"></i>
<p class="text-slate-600">Drop CSV file here or <span
class="text-indigo-600 font-medium">browse</span></p>
<p class="text-xs text-slate-400 mt-1">First row should be column headers</p>
</label>
</div>
</div>
<!-- JSON Input -->
<div id="json-input" class="mb-4 hidden">
<textarea id="json-data" rows="10"
class="w-full px-4 py-3 border border-slate-200 rounded-lg font-mono text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
placeholder='[{"id": "test-1", "name": "Test Brand", "website": "test.com"}]'></textarea>
</div>
<!-- Preview -->
<div id="import-preview" class="mb-4 hidden">
<h4 class="font-medium text-slate-700 mb-2">Preview (first 5 rows)</h4>
<div class="overflow-x-auto">
<table class="w-full text-xs border border-slate-200" id="preview-table">
<!-- Dynamic preview -->
</table>
</div>
<div class="mt-2 flex gap-4">
<span id="valid-count" class="text-green-600"><i class="fa-solid fa-check mr-1"></i>0
valid</span>
<span id="error-count" class="text-red-600"><i class="fa-solid fa-xmark mr-1"></i>0
errors</span>
</div>
</div>
</div>
<div class="p-6 border-t border-slate-200 bg-slate-50 flex justify-end gap-3">
<button onclick="closeImportModal()"
class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300">
Cancel
</button>
<button onclick="executeImport()" id="import-btn"
class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
<i class="fa-solid fa-file-import mr-2"></i>Import
</button>
</div>
</div>
</div>
<!-- Add/Edit Modal -->
<div id="edit-modal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
<div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4">
<div class="p-6 border-b border-slate-200">
<div class="flex justify-between items-center">
<h3 id="modal-title" class="text-xl font-bold text-slate-800">Add New</h3>
<button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600">
<i class="fa-solid fa-xmark text-xl"></i>
</button>
</div>
</div>
<div class="p-6" id="modal-form">
<!-- Dynamic form fields -->
</div>
<div class="p-6 border-t border-slate-200 bg-slate-50 flex justify-end gap-3">
<button onclick="closeEditModal()"
class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300">
Cancel
</button>
<button onclick="saveEntity()" id="save-btn"
class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
<i class="fa-solid fa-save mr-2"></i>Save
</button>
</div>
</div>
</div>
<script>
// State
let currentType = 'regulators';
let currentData = [];
let currentOffset = 0;
const LIMIT = 20;
let editingId = null;
let sortColumn = null;
let sortDirection = 'asc'; // 'asc' or 'desc'
// Entity field definitions
const ENTITY_FIELDS = {
markets: [
{ key: 'id', label: 'ID', required: true },
{ key: 'name', label: 'Name', required: true },
{ key: 'country', label: 'Country', required: true },
{ key: 'country_code', label: 'Country Code', required: true },
{ key: 'flag', label: 'Flag Emoji' }
],
brands: [
{ key: 'id', label: 'ID', required: true },
{ key: 'name', label: 'Name', required: true },
{ key: 'website', label: 'Website' },
{ key: 'headquarters', label: 'Headquarters' },
{ key: 'parent_company', label: 'Parent Company' }
],
licenses: [
{ key: 'id', label: 'ID', required: true },
{ key: 'brand_id', label: 'Brand ID', required: true },
{ key: 'license_number', label: 'License Number' },
{ key: 'regulator_id', label: 'Regulator ID' },
{ key: 'status', label: 'Status', options: ['active', 'expired', 'suspended', 'pending', 'grey', 'unlicensed'] }
],
regulators: [
{ key: 'id', label: 'ID', required: true },
{ key: 'name', label: 'Name', required: true },
{ key: 'abbreviation', label: 'Abbreviation' },
{ key: 'country', label: 'Country', required: true },
{ key: 'website', label: 'Website' }
],
regulations: [
{ key: 'id', label: 'ID', required: true },
{ key: 'market_id', label: 'Jurisdiction ID', required: true },
{ key: 'category', label: 'Category', required: true, options: ['RG Messaging', 'Bonus Terms', 'Advertising', 'Communications', 'Licensing', 'Criminal'] },
{ key: 'requirement', label: 'Requirement', required: true },
{ key: 'description', label: 'Description' },
{ key: 'severity', label: 'Severity', required: true, options: ['Critical', 'High', 'Medium', 'Low', 'Criminal'] },
{ key: 'fine_range', label: 'Fine Range' }
],
companies: [
{ key: 'id', label: 'ID', required: true },
{ key: 'name', label: 'Name', required: true },
{ key: 'legal_name', label: 'Legal Name' },
{ key: 'country', label: 'Country' },
{ key: 'website', label: 'Website' }
],
payment_methods: [
{ key: 'id', label: 'ID', required: true },
{ key: 'name', label: 'Name', required: true },
{ key: 'category', label: 'Category', options: ['card', 'crypto', 'ewallet', 'bank'] },
{ key: 'icon_class', label: 'Icon Class' }
],
brand_websites: [
{ key: 'brand_id', label: 'Brand ID', required: true },
{ key: 'domain', label: 'Domain', required: true },
{ key: 'locale', label: 'Locale (e.g. es-ES)' },
{ key: 'market_id', label: 'Market ID' },
{ key: 'is_primary', label: 'Primary', options: ['true', 'false'] },
{ key: 'status', label: 'Status', options: ['active', 'inactive', 'blocked'] }
]
};
// Table columns for each type
const TABLE_COLUMNS = {
markets: ['id', 'name', 'country', 'flag'],
brands: ['id', 'name', 'website', 'headquarters'],
licenses: ['id', 'brand_name', 'license_number', 'regulator_name', 'status'],
regulators: ['id', 'name', 'abbreviation', 'country'],
regulations: ['id', 'market_id', 'category', 'requirement', 'severity', 'fine_range'],
companies: ['id', 'name', 'country', 'website'],
payment_methods: ['id', 'name', 'category', 'icon_class'],
brand_websites: ['brand_name', 'domain', 'locale', 'market_name', 'is_primary', 'status']
};
// Initialize
document.addEventListener('DOMContentLoaded', async () => {
await loadStats();
await loadData();
// Setup import format toggle
document.querySelectorAll('input[name="import-format"]').forEach(radio => {
radio.addEventListener('change', (e) => {
document.getElementById('csv-upload').classList.toggle('hidden', e.target.value !== 'csv');
document.getElementById('json-input').classList.toggle('hidden', e.target.value !== 'json');
});
});
// Setup CSV file handler
document.getElementById('csv-file').addEventListener('change', handleCsvUpload);
// Setup search on enter
document.getElementById('search-input').addEventListener('keyup', (e) => {
if (e.key === 'Enter') loadData();
});
});
async function loadStats() {
try {
const res = await fetch('/.netlify/functions/pipeline/stats');
const data = await res.json();
const total = Object.values(data.stats).reduce((sum, s) => sum + (s.total || 0), 0);
document.getElementById('total-records').textContent = total.toLocaleString();
if (data.lastHealthCheck?.checked_at) {
const ago = getTimeAgo(new Date(data.lastHealthCheck.checked_at));
document.getElementById('last-check').textContent = ago;
}
} catch (e) {
console.warn('Failed to load stats:', e);
}
}
async function loadData() {
const search = document.getElementById('search-input').value;
const params = new URLSearchParams({
type: currentType,
limit: LIMIT,
offset: currentOffset
});
if (search) params.append('search', search);
try {
const res = await fetch(`/.netlify/functions/pipeline?${params}`);
const result = await res.json();
currentData = result.data || [];
renderTable();
updatePagination(result.total);
} catch (e) {
console.error('Failed to load data:', e);
showToast('Failed to load data', 'error');
}
}
function renderTable() {
const columns = TABLE_COLUMNS[currentType] || ['id', 'name'];
// Render header with sort controls
const headerHtml = columns.map(col => {
const isSorted = sortColumn === col;
const icon = isSorted ? (sortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort';
const iconColor = isSorted ? 'text-indigo-600' : 'text-slate-400';
return `<th class="text-left py-3 px-4 font-medium text-slate-600 uppercase text-xs cursor-pointer hover:bg-slate-100 select-none" onclick="sortBy('${col}')">
<span class="flex items-center gap-1">
${formatColumnName(col)}
<i class="fa-solid ${icon} ${iconColor} text-xs"></i>
</span>
</th>`;
}).join('') + '<th class="text-right py-3 px-4 w-24">Actions</th>';
document.getElementById('table-header').innerHTML = headerHtml;
// Render body
if (currentData.length === 0) {
document.getElementById('table-body').innerHTML = `
<tr><td colspan="${columns.length + 1}" class="py-8 text-center text-slate-400">
<i class="fa-solid fa-inbox text-4xl mb-2"></i>
<p>No records found</p>
</td></tr>
`;
return;
}
// Sort data if column selected
let displayData = [...currentData];
if (sortColumn) {
displayData.sort((a, b) => {
let valA = a[sortColumn] || '';
let valB = b[sortColumn] || '';
// Handle numeric sorting
if (!isNaN(valA) && !isNaN(valB)) {
valA = Number(valA);
valB = Number(valB);
} else {
valA = String(valA).toLowerCase();
valB = String(valB).toLowerCase();
}
if (valA < valB) return sortDirection === 'asc' ? -1 : 1;
if (valA > valB) return sortDirection === 'asc' ? 1 : -1;
return 0;
});
}
const bodyHtml = displayData.map(row => {
const cells = columns.map(col => {
let val = row[col] ?? '';
if (col === 'status') {
const statusClass = val === 'active' ? 'status-pass' : (val === 'expired' || val === 'suspended') ? 'status-fail' : 'status-warning';
return `<td class="py-3 px-4"><span class="px-2 py-1 rounded text-xs ${statusClass}">${val}</span></td>`;
}
if (col === 'severity') {
const sevClass = val === 'Critical' || val === 'Criminal' ? 'bg-red-100 text-red-700' : val === 'High' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600';
return `<td class="py-3 px-4"><span class="px-2 py-1 rounded text-xs font-medium ${sevClass}">${val}</span></td>`;
}
return `<td class="py-3 px-4 truncate max-w-[200px]" title="${val}">${val}</td>`;
}).join('');
return `<tr class="entity-row border-b border-slate-100" data-id="${row.id}">
${cells}
<td class="py-3 px-4 text-right">
<button onclick="editEntity('${row.id}')" class="text-indigo-500 hover:text-indigo-700 mr-2">
<i class="fa-solid fa-pen-to-square"></i>
</button>
<button onclick="deleteEntity('${row.id}')" class="text-red-500 hover:text-red-700">
<i class="fa-solid fa-trash"></i>
</button>
</td>
</tr>`;
}).join('');
document.getElementById('table-body').innerHTML = bodyHtml;
}
function sortBy(column) {
if (sortColumn === column) {
// Toggle direction
sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
} else {
sortColumn = column;
sortDirection = 'asc';
}
renderTable();
}
function updatePagination(total) {
const start = currentOffset + 1;
const end = Math.min(currentOffset + LIMIT, total);
document.getElementById('pagination-info').textContent = `Showing ${start}-${end} of ${total}`;
document.getElementById('prev-btn').disabled = currentOffset === 0;
document.getElementById('next-btn').disabled = currentOffset + LIMIT >= total;
}
function prevPage() {
currentOffset = Math.max(0, currentOffset - LIMIT);
loadData();
}
function nextPage() {
currentOffset += LIMIT;
loadData();
}
function showTab(type) {
currentType = type;
currentOffset = 0;
// Update tab styles
document.querySelectorAll('.tab-btn').forEach(btn => {
btn.classList.remove('tab-active');
btn.classList.add('tab-inactive');
});
document.getElementById(`tab-${type}`).classList.remove('tab-inactive');
document.getElementById(`tab-${type}`).classList.add('tab-active');
// Reload data
loadData();
}
// Add/Edit Modal
function showAddModal() {
editingId = null;
document.getElementById('modal-title').textContent = `Add New ${formatTypeName(currentType)}`;
renderForm({});
document.getElementById('edit-modal').classList.remove('hidden');
}
function editEntity(id) {
editingId = id;
const entity = currentData.find(e => e.id == id);
if (!entity) return;
document.getElementById('modal-title').textContent = `Edit ${formatTypeName(currentType)}`;
renderForm(entity);
document.getElementById('edit-modal').classList.remove('hidden');
}
function renderForm(data) {
const fields = ENTITY_FIELDS[currentType] || [];
const html = fields.map(field => {
const value = data[field.key] || '';
const required = field.required ? 'required' : '';
const disabled = editingId && field.key === 'id' ? 'disabled bg-slate-100' : '';
if (field.options) {
const options = field.options.map(opt =>
`<option value="${opt}" ${value === opt ? 'selected' : ''}>${opt}</option>`
).join('');
return `<div class="mb-4">
<label class="block text-sm font-medium text-slate-700 mb-1">${field.label}${field.required ? ' *' : ''}</label>
<select name="${field.key}" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" ${required}>
<option value="">Select...</option>
${options}
</select>
</div>`;
}
return `<div class="mb-4">
<label class="block text-sm font-medium text-slate-700 mb-1">${field.label}${field.required ? ' *' : ''}</label>
<input type="text" name="${field.key}" value="${value}" 
class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 ${disabled}"
${required} ${disabled}>
</div>`;
}).join('');
document.getElementById('modal-form').innerHTML = html;
}
async function saveEntity() {
const form = document.getElementById('modal-form');
const formData = {};
form.querySelectorAll('input, select').forEach(el => {
if (el.name && el.value) formData[el.name] = el.value;
});
formData.type = currentType;
const method = editingId ? 'PUT' : 'POST';
try {
const res = await fetch('/.netlify/functions/pipeline', {
method,
headers: { 'Content-Type': 'application/json' },
body: JSON.stringify(formData)
});
const result = await res.json();
if (!res.ok) {
showToast(result.error || 'Failed to save', 'error');
return;
}
showToast(editingId ? 'Updated successfully' : 'Created successfully', 'success');
closeEditModal();
loadData();
loadStats();
} catch (e) {
showToast('Failed to save: ' + e.message, 'error');
}
}
async function deleteEntity(id) {
if (!confirm('Are you sure you want to delete this record?')) return;
try {
const res = await fetch(`/.netlify/functions/pipeline?type=${currentType}&id=${id}`, {
method: 'DELETE'
});
if (!res.ok) {
const result = await res.json();
showToast(result.error || 'Failed to delete', 'error');
return;
}
showToast('Deleted successfully', 'success');
loadData();
loadStats();
} catch (e) {
showToast('Failed to delete: ' + e.message, 'error');
}
}
function closeEditModal() {
document.getElementById('edit-modal').classList.add('hidden');
editingId = null;
}
// Import Modal
function showImportModal() {
document.getElementById('import-type').textContent = formatTypeName(currentType);
document.getElementById('import-preview').classList.add('hidden');
document.getElementById('csv-file').value = '';
document.getElementById('json-data').value = '';
document.getElementById('import-modal').classList.remove('hidden');
}
function closeImportModal() {
document.getElementById('import-modal').classList.add('hidden');
}
function handleCsvUpload(e) {
const file = e.target.files[0];
if (!file) return;
const reader = new FileReader();
reader.onload = (event) => {
const csv = event.target.result;
const records = parseCsv(csv);
showPreview(records);
};
reader.readAsText(file);
}
function parseCsv(csv) {
const lines = csv.trim().split('\n');
if (lines.length < 2) return [];
const headers = lines[0].split(',').map(h => h.trim().toLowerCase().replace(/[^a-z_]/g, '_'));
return lines.slice(1).map(line => {
const values = line.split(',').map(v => v.trim().replace(/^"|"$/g, ''));
const obj = {};
headers.forEach((h, i) => obj[h] = values[i] || '');
return obj;
});
}
function showPreview(records) {
if (records.length === 0) return;
const preview = records.slice(0, 5);
const headers = Object.keys(preview[0]);
const headerHtml = '<tr class="bg-slate-100">' +
headers.map(h => `<th class="py-2 px-3 text-left">${h}</th>`).join('') + '</tr>';
const bodyHtml = preview.map(row =>
'<tr class="border-t border-slate-200">' +
headers.map(h => `<td class="py-2 px-3">${row[h] || ''}</td>`).join('') + '</tr>'
).join('');
document.getElementById('preview-table').innerHTML = headerHtml + bodyHtml;
document.getElementById('valid-count').innerHTML = `<i class="fa-solid fa-check mr-1"></i>${records.length} records`;
document.getElementById('import-preview').classList.remove('hidden');
// Store for import
window.importRecords = records;
}
async function executeImport() {
const format = document.querySelector('input[name="import-format"]:checked').value;
let records;
if (format === 'json') {
try {
records = JSON.parse(document.getElementById('json-data').value);
} catch (e) {
showToast('Invalid JSON format', 'error');
return;
}
} else {
records = window.importRecords;
}
if (!records || records.length === 0) {
showToast('No records to import', 'error');
return;
}
document.getElementById('import-btn').disabled = true;
document.getElementById('import-btn').innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Importing...';
try {
const res = await fetch('/.netlify/functions/pipeline/import', {
method: 'POST',
headers: { 'Content-Type': 'application/json' },
body: JSON.stringify({ type: currentType, records })
});
const result = await res.json();
if (result.status === 'success') {
showToast(`Imported ${result.success} records successfully`, 'success');
} else if (result.status === 'partial') {
showToast(`Imported ${result.success} records, ${result.failed} failed`, 'warning');
} else {
showToast(`Import failed: ${result.errors?.[0]?.errors?.[0] || 'Unknown error'}`, 'error');
}
closeImportModal();
loadData();
loadStats();
} catch (e) {
showToast('Import failed: ' + e.message, 'error');
} finally {
document.getElementById('import-btn').disabled = false;
document.getElementById('import-btn').innerHTML = '<i class="fa-solid fa-file-import mr-2"></i>Import';
}
}
// Helpers
function formatTypeName(type) {
const names = {
markets: 'Jurisdiction',
brands: 'Brand',
licenses: 'License',
regulators: 'Regulator',
regulations: 'Regulation',
companies: 'Company',
payment_methods: 'Payment Method',
brand_websites: 'Brand Website'
};
return names[type] || type;
}
function formatColumnName(col) {
return col.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
}
function getTimeAgo(date) {
const seconds = Math.floor((new Date() - date) / 1000);
if (seconds < 60) return 'Just now';
if (seconds < 3600) return `${Math.floor(seconds / 60)}m ago`;
if (seconds < 86400) return `${Math.floor(seconds / 3600)}h ago`;
return `${Math.floor(seconds / 86400)}d ago`;
}
function showToast(message, type = 'info') {
const colors = {
success: 'bg-green-500',
error: 'bg-red-500',
warning: 'bg-amber-500',
info: 'bg-blue-500'
};
const toast = document.createElement('div');
toast.className = `fixed bottom-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-pulse`;
toast.innerHTML = `<i class="fa-solid fa-${type === 'success' ? 'check' : type === 'error' ? 'xmark' : 'info'} mr-2"></i>${message}`;
document.body.appendChild(toast);
setTimeout(() => toast.remove(), 4000);
}
</script>
@endsection

@push('page-scripts')
<script>
        // State
        let currentType = 'regulators';
        let currentData = [];
        let currentOffset = 0;
        const LIMIT = 20;
        let editingId = null;
        let sortColumn = null;
        let sortDirection = 'asc'; // 'asc' or 'desc'

        // Entity field definitions
        const ENTITY_FIELDS = {
            markets: [
                { key: 'id', label: 'ID', required: true },
                { key: 'name', label: 'Name', required: true },
                { key: 'country', label: 'Country', required: true },
                { key: 'country_code', label: 'Country Code', required: true },
                { key: 'flag', label: 'Flag Emoji' }
            ],
            brands: [
                { key: 'id', label: 'ID', required: true },
                { key: 'name', label: 'Name', required: true },
                { key: 'website', label: 'Website' },
                { key: 'headquarters', label: 'Headquarters' },
                { key: 'parent_company', label: 'Parent Company' }
            ],
            licenses: [
                { key: 'id', label: 'ID', required: true },
                { key: 'brand_id', label: 'Brand ID', required: true },
                { key: 'license_number', label: 'License Number' },
                { key: 'regulator_id', label: 'Regulator ID' },
                { key: 'status', label: 'Status', options: ['active', 'expired', 'suspended', 'pending', 'grey', 'unlicensed'] }
            ],
            regulators: [
                { key: 'id', label: 'ID', required: true },
                { key: 'name', label: 'Name', required: true },
                { key: 'abbreviation', label: 'Abbreviation' },
                { key: 'country', label: 'Country', required: true },
                { key: 'website', label: 'Website' }
            ],
            regulations: [
                { key: 'id', label: 'ID', required: true },
                { key: 'market_id', label: 'Jurisdiction ID', required: true },
                { key: 'category', label: 'Category', required: true, options: ['RG Messaging', 'Bonus Terms', 'Advertising', 'Communications', 'Licensing', 'Criminal'] },
                { key: 'requirement', label: 'Requirement', required: true },
                { key: 'description', label: 'Description' },
                { key: 'severity', label: 'Severity', required: true, options: ['Critical', 'High', 'Medium', 'Low', 'Criminal'] },
                { key: 'fine_range', label: 'Fine Range' }
            ],
            companies: [
                { key: 'id', label: 'ID', required: true },
                { key: 'name', label: 'Name', required: true },
                { key: 'legal_name', label: 'Legal Name' },
                { key: 'country', label: 'Country' },
                { key: 'website', label: 'Website' }
            ],
            payment_methods: [
                { key: 'id', label: 'ID', required: true },
                { key: 'name', label: 'Name', required: true },
                { key: 'category', label: 'Category', options: ['card', 'crypto', 'ewallet', 'bank'] },
                { key: 'icon_class', label: 'Icon Class' }
            ],
            brand_websites: [
                { key: 'brand_id', label: 'Brand ID', required: true },
                { key: 'domain', label: 'Domain', required: true },
                { key: 'locale', label: 'Locale (e.g. es-ES)' },
                { key: 'market_id', label: 'Market ID' },
                { key: 'is_primary', label: 'Primary', options: ['true', 'false'] },
                { key: 'status', label: 'Status', options: ['active', 'inactive', 'blocked'] }
            ]
        };

        // Table columns for each type
        const TABLE_COLUMNS = {
            markets: ['id', 'name', 'country', 'flag'],
            brands: ['id', 'name', 'website', 'headquarters'],
            licenses: ['id', 'brand_name', 'license_number', 'regulator_name', 'status'],
            regulators: ['id', 'name', 'abbreviation', 'country'],
            regulations: ['id', 'market_id', 'category', 'requirement', 'severity', 'fine_range'],
            companies: ['id', 'name', 'country', 'website'],
            payment_methods: ['id', 'name', 'category', 'icon_class'],
            brand_websites: ['brand_name', 'domain', 'locale', 'market_name', 'is_primary', 'status']
        };

        // Initialize
        document.addEventListener('DOMContentLoaded', async () => {
            await loadStats();
            await loadData();

            // Setup import format toggle
            document.querySelectorAll('input[name="import-format"]').forEach(radio => {
                radio.addEventListener('change', (e) => {
                    document.getElementById('csv-upload').classList.toggle('hidden', e.target.value !== 'csv');
                    document.getElementById('json-input').classList.toggle('hidden', e.target.value !== 'json');
                });
            });

            // Setup CSV file handler
            document.getElementById('csv-file').addEventListener('change', handleCsvUpload);

            // Setup search on enter
            document.getElementById('search-input').addEventListener('keyup', (e) => {
                if (e.key === 'Enter') loadData();
            });
        });

        async function loadStats() {
            try {
                const res = await fetch('/.netlify/functions/pipeline/stats');
                const data = await res.json();

                const total = Object.values(data.stats).reduce((sum, s) => sum + (s.total || 0), 0);
                document.getElementById('total-records').textContent = total.toLocaleString();

                if (data.lastHealthCheck?.checked_at) {
                    const ago = getTimeAgo(new Date(data.lastHealthCheck.checked_at));
                    document.getElementById('last-check').textContent = ago;
                }
            } catch (e) {
                console.warn('Failed to load stats:', e);
            }
        }

        async function loadData() {
            const search = document.getElementById('search-input').value;
            const params = new URLSearchParams({
                type: currentType,
                limit: LIMIT,
                offset: currentOffset
            });
            if (search) params.append('search', search);

            try {
                const res = await fetch(`/.netlify/functions/pipeline?${params}`);
                const result = await res.json();
                currentData = result.data || [];
                renderTable();
                updatePagination(result.total);
            } catch (e) {
                console.error('Failed to load data:', e);
                showToast('Failed to load data', 'error');
            }
        }

        function renderTable() {
            const columns = TABLE_COLUMNS[currentType] || ['id', 'name'];

            // Render header with sort controls
            const headerHtml = columns.map(col => {
                const isSorted = sortColumn === col;
                const icon = isSorted ? (sortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort';
                const iconColor = isSorted ? 'text-indigo-600' : 'text-slate-400';
                return `<th class="text-left py-3 px-4 font-medium text-slate-600 uppercase text-xs cursor-pointer hover:bg-slate-100 select-none" onclick="sortBy('${col}')">
                    <span class="flex items-center gap-1">
                        ${formatColumnName(col)}
                        <i class="fa-solid ${icon} ${iconColor} text-xs"></i>
                    </span>
                </th>`;
            }).join('') + '<th class="text-right py-3 px-4 w-24">Actions</th>';
            document.getElementById('table-header').innerHTML = headerHtml;

            // Render body
            if (currentData.length === 0) {
                document.getElementById('table-body').innerHTML = `
                    <tr><td colspan="${columns.length + 1}" class="py-8 text-center text-slate-400">
                        <i class="fa-solid fa-inbox text-4xl mb-2"></i>
                        <p>No records found</p>
                    </td></tr>
                `;
                return;
            }

            // Sort data if column selected
            let displayData = [...currentData];
            if (sortColumn) {
                displayData.sort((a, b) => {
                    let valA = a[sortColumn] || '';
                    let valB = b[sortColumn] || '';
                    // Handle numeric sorting
                    if (!isNaN(valA) && !isNaN(valB)) {
                        valA = Number(valA);
                        valB = Number(valB);
                    } else {
                        valA = String(valA).toLowerCase();
                        valB = String(valB).toLowerCase();
                    }
                    if (valA < valB) return sortDirection === 'asc' ? -1 : 1;
                    if (valA > valB) return sortDirection === 'asc' ? 1 : -1;
                    return 0;
                });
            }

            const bodyHtml = displayData.map(row => {
                const cells = columns.map(col => {
                    let val = row[col] ?? '';
                    if (col === 'status') {
                        const statusClass = val === 'active' ? 'status-pass' : (val === 'expired' || val === 'suspended') ? 'status-fail' : 'status-warning';
                        return `<td class="py-3 px-4"><span class="px-2 py-1 rounded text-xs ${statusClass}">${val}</span></td>`;
                    }
                    if (col === 'severity') {
                        const sevClass = val === 'Critical' || val === 'Criminal' ? 'bg-red-100 text-red-700' : val === 'High' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600';
                        return `<td class="py-3 px-4"><span class="px-2 py-1 rounded text-xs font-medium ${sevClass}">${val}</span></td>`;
                    }
                    return `<td class="py-3 px-4 truncate max-w-[200px]" title="${val}">${val}</td>`;
                }).join('');

                return `<tr class="entity-row border-b border-slate-100" data-id="${row.id}">
                    ${cells}
                    <td class="py-3 px-4 text-right">
                        <button onclick="editEntity('${row.id}')" class="text-indigo-500 hover:text-indigo-700 mr-2">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button onclick="deleteEntity('${row.id}')" class="text-red-500 hover:text-red-700">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
            }).join('');

            document.getElementById('table-body').innerHTML = bodyHtml;
        }

        function sortBy(column) {
            if (sortColumn === column) {
                // Toggle direction
                sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                sortColumn = column;
                sortDirection = 'asc';
            }
            renderTable();
        }

        function updatePagination(total) {
            const start = currentOffset + 1;
            const end = Math.min(currentOffset + LIMIT, total);
            document.getElementById('pagination-info').textContent = `Showing ${start}-${end} of ${total}`;

            document.getElementById('prev-btn').disabled = currentOffset === 0;
            document.getElementById('next-btn').disabled = currentOffset + LIMIT >= total;
        }

        function prevPage() {
            currentOffset = Math.max(0, currentOffset - LIMIT);
            loadData();
        }

        function nextPage() {
            currentOffset += LIMIT;
            loadData();
        }

        function showTab(type) {
            currentType = type;
            currentOffset = 0;

            // Update tab styles
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('tab-active');
                btn.classList.add('tab-inactive');
            });
            document.getElementById(`tab-${type}`).classList.remove('tab-inactive');
            document.getElementById(`tab-${type}`).classList.add('tab-active');

            // Reload data
            loadData();
        }

        // Add/Edit Modal
        function showAddModal() {
            editingId = null;
            document.getElementById('modal-title').textContent = `Add New ${formatTypeName(currentType)}`;
            renderForm({});
            document.getElementById('edit-modal').classList.remove('hidden');
        }

        function editEntity(id) {
            editingId = id;
            const entity = currentData.find(e => e.id == id);
            if (!entity) return;

            document.getElementById('modal-title').textContent = `Edit ${formatTypeName(currentType)}`;
            renderForm(entity);
            document.getElementById('edit-modal').classList.remove('hidden');
        }

        function renderForm(data) {
            const fields = ENTITY_FIELDS[currentType] || [];
            const html = fields.map(field => {
                const value = data[field.key] || '';
                const required = field.required ? 'required' : '';
                const disabled = editingId && field.key === 'id' ? 'disabled bg-slate-100' : '';

                if (field.options) {
                    const options = field.options.map(opt =>
                        `<option value="${opt}" ${value === opt ? 'selected' : ''}>${opt}</option>`
                    ).join('');
                    return `<div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">${field.label}${field.required ? ' *' : ''}</label>
                        <select name="${field.key}" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" ${required}>
                            <option value="">Select...</option>
                            ${options}
                        </select>
                    </div>`;
                }

                return `<div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">${field.label}${field.required ? ' *' : ''}</label>
                    <input type="text" name="${field.key}" value="${value}" 
                        class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 ${disabled}"
                        ${required} ${disabled}>
                </div>`;
            }).join('');

            document.getElementById('modal-form').innerHTML = html;
        }

        async function saveEntity() {
            const form = document.getElementById('modal-form');
            const formData = {};
            form.querySelectorAll('input, select').forEach(el => {
                if (el.name && el.value) formData[el.name] = el.value;
            });
            formData.type = currentType;

            const method = editingId ? 'PUT' : 'POST';

            try {
                const res = await fetch('/.netlify/functions/pipeline', {
                    method,
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });

                const result = await res.json();

                if (!res.ok) {
                    showToast(result.error || 'Failed to save', 'error');
                    return;
                }

                showToast(editingId ? 'Updated successfully' : 'Created successfully', 'success');
                closeEditModal();
                loadData();
                loadStats();
            } catch (e) {
                showToast('Failed to save: ' + e.message, 'error');
            }
        }

        async function deleteEntity(id) {
            if (!confirm('Are you sure you want to delete this record?')) return;

            try {
                const res = await fetch(`/.netlify/functions/pipeline?type=${currentType}&id=${id}`, {
                    method: 'DELETE'
                });

                if (!res.ok) {
                    const result = await res.json();
                    showToast(result.error || 'Failed to delete', 'error');
                    return;
                }

                showToast('Deleted successfully', 'success');
                loadData();
                loadStats();
            } catch (e) {
                showToast('Failed to delete: ' + e.message, 'error');
            }
        }

        function closeEditModal() {
            document.getElementById('edit-modal').classList.add('hidden');
            editingId = null;
        }

        // Import Modal
        function showImportModal() {
            document.getElementById('import-type').textContent = formatTypeName(currentType);
            document.getElementById('import-preview').classList.add('hidden');
            document.getElementById('csv-file').value = '';
            document.getElementById('json-data').value = '';
            document.getElementById('import-modal').classList.remove('hidden');
        }

        function closeImportModal() {
            document.getElementById('import-modal').classList.add('hidden');
        }

        function handleCsvUpload(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (event) => {
                const csv = event.target.result;
                const records = parseCsv(csv);
                showPreview(records);
            };
            reader.readAsText(file);
        }

        function parseCsv(csv) {
            const lines = csv.trim().split('\n');
            if (lines.length < 2) return [];

            const headers = lines[0].split(',').map(h => h.trim().toLowerCase().replace(/[^a-z_]/g, '_'));

            return lines.slice(1).map(line => {
                const values = line.split(',').map(v => v.trim().replace(/^"|"$/g, ''));
                const obj = {};
                headers.forEach((h, i) => obj[h] = values[i] || '');
                return obj;
            });
        }

        function showPreview(records) {
            if (records.length === 0) return;

            const preview = records.slice(0, 5);
            const headers = Object.keys(preview[0]);

            const headerHtml = '<tr class="bg-slate-100">' +
                headers.map(h => `<th class="py-2 px-3 text-left">${h}</th>`).join('') + '</tr>';
            const bodyHtml = preview.map(row =>
                '<tr class="border-t border-slate-200">' +
                headers.map(h => `<td class="py-2 px-3">${row[h] || ''}</td>`).join('') + '</tr>'
            ).join('');

            document.getElementById('preview-table').innerHTML = headerHtml + bodyHtml;
            document.getElementById('valid-count').innerHTML = `<i class="fa-solid fa-check mr-1"></i>${records.length} records`;
            document.getElementById('import-preview').classList.remove('hidden');

            // Store for import
            window.importRecords = records;
        }

        async function executeImport() {
            const format = document.querySelector('input[name="import-format"]:checked').value;
            let records;

            if (format === 'json') {
                try {
                    records = JSON.parse(document.getElementById('json-data').value);
                } catch (e) {
                    showToast('Invalid JSON format', 'error');
                    return;
                }
            } else {
                records = window.importRecords;
            }

            if (!records || records.length === 0) {
                showToast('No records to import', 'error');
                return;
            }

            document.getElementById('import-btn').disabled = true;
            document.getElementById('import-btn').innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Importing...';

            try {
                const res = await fetch('/.netlify/functions/pipeline/import', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ type: currentType, records })
                });

                const result = await res.json();

                if (result.status === 'success') {
                    showToast(`Imported ${result.success} records successfully`, 'success');
                } else if (result.status === 'partial') {
                    showToast(`Imported ${result.success} records, ${result.failed} failed`, 'warning');
                } else {
                    showToast(`Import failed: ${result.errors?.[0]?.errors?.[0] || 'Unknown error'}`, 'error');
                }

                closeImportModal();
                loadData();
                loadStats();
            } catch (e) {
                showToast('Import failed: ' + e.message, 'error');
            } finally {
                document.getElementById('import-btn').disabled = false;
                document.getElementById('import-btn').innerHTML = '<i class="fa-solid fa-file-import mr-2"></i>Import';
            }
        }

        // Helpers
        function formatTypeName(type) {
            const names = {
                markets: 'Jurisdiction',
                brands: 'Brand',
                licenses: 'License',
                regulators: 'Regulator',
                regulations: 'Regulation',
                companies: 'Company',
                payment_methods: 'Payment Method',
                brand_websites: 'Brand Website'
            };
            return names[type] || type;
        }

        function formatColumnName(col) {
            return col.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        }

        function getTimeAgo(date) {
            const seconds = Math.floor((new Date() - date) / 1000);
            if (seconds < 60) return 'Just now';
            if (seconds < 3600) return `${Math.floor(seconds / 60)}m ago`;
            if (seconds < 86400) return `${Math.floor(seconds / 3600)}h ago`;
            return `${Math.floor(seconds / 86400)}d ago`;
        }

        function showToast(message, type = 'info') {
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-amber-500',
                info: 'bg-blue-500'
            };

            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-pulse`;
            toast.innerHTML = `<i class="fa-solid fa-${type === 'success' ? 'check' : type === 'error' ? 'xmark' : 'info'} mr-2"></i>${message}`;
            document.body.appendChild(toast);

            setTimeout(() => toast.remove(), 4000);
        }
    </script>
@endpush
