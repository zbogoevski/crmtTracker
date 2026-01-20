@extends('layouts.dashboard')


@section('title', 'CRMTracker® - Data Import Wizard')

@push('styles')
<style>
body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .gradient-hero {
            background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #475569 100%);
        }

        .drop-zone {
            border: 2px dashed #cbd5e1;
            transition: all 0.3s ease;
        }

        .drop-zone.dragover {
            border-color: #7c3aed;
            background: rgba(124, 58, 237, 0.05);
        }

        .drop-zone:hover {
            border-color: #7c3aed;
        }

        .wizard-step {
            opacity: 0.4;
            pointer-events: none;
        }

        .wizard-step.active {
            opacity: 1;
            pointer-events: auto;
        }

        .column-mapper-row {
            transition: background 0.2s;
        }

        .column-mapper-row:hover {
            background: #f8fafc;
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
<h2 class="text-3xl font-bold">Data Import Wizard</h2>
<span class="text-xs bg-green-500/20 text-green-300 px-2 py-1 rounded-full">
<i class="fa-solid fa-magic-wand-sparkles mr-1"></i>v7.2
</span>
</div>
<p class="text-slate-300 max-w-xl">Upload Excel or CSV files with automatic column detection and
mapping.
Preview data before importing into CRMTracker®.</p>
</div>
<div class="text-right">
<p class="text-xs font-bold text-slate-300 uppercase mb-1">Progress</p>
<div class="flex gap-2">
<div id="step1-dot"
class="w-8 h-8 rounded-full bg-purple-500 flex items-center justify-center text-sm font-bold">
1</div>
<div id="step2-dot"
class="w-8 h-8 rounded-full bg-slate-600 flex items-center justify-center text-sm font-bold">
2</div>
<div id="step3-dot"
class="w-8 h-8 rounded-full bg-slate-600 flex items-center justify-center text-sm font-bold">
3</div>
<div id="step4-dot"
class="w-8 h-8 rounded-full bg-slate-600 flex items-center justify-center text-sm font-bold">
4</div>
</div>
</div>
</div>
</div>
<!-- Step 1: Upload File -->
<div id="step1" class="wizard-step active bg-white rounded-xl shadow-lg border border-slate-200 p-8 mb-6">
<h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
<span
class="w-8 h-8 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center text-sm font-bold">1</span>
Select File
</h3>
<!-- Drop Zone -->
<div id="drop-zone" class="drop-zone rounded-xl p-12 text-center cursor-pointer mb-6">
<input type="file" id="file-input" accept=".csv,.xlsx,.xls" class="hidden">
<div class="mb-4">
<i class="fa-solid fa-file-excel text-6xl text-slate-300"></i>
</div>
<p class="text-lg font-medium text-slate-600 mb-2">Drop your file here or click to browse</p>
<p class="text-sm text-slate-400">Supports .xlsx, .xls, and .csv files</p>
</div>
<!-- File Info -->
<div id="file-info" class="hidden bg-slate-50 rounded-lg p-4">
<div class="flex items-center justify-between">
<div class="flex items-center gap-3">
<i class="fa-solid fa-file-excel text-2xl text-green-600"></i>
<div>
<p class="font-medium text-slate-700" id="file-name">filename.xlsx</p>
<p class="text-sm text-slate-400">
<span id="file-size">0 KB</span> •
<span id="sheet-count">1 sheet</span> •
<span id="row-count">0 rows</span>
</p>
</div>
</div>
<div class="flex gap-2">
<select id="sheet-select" class="text-sm border border-slate-200 rounded px-2 py-1"></select>
<button id="remove-file" class="text-red-500 hover:text-red-700 px-2">
<i class="fa-solid fa-times"></i>
</button>
</div>
</div>
</div>
</div>
<!-- Step 2: Column Mapping -->
<div id="step2" class="wizard-step bg-white rounded-xl shadow-lg border border-slate-200 p-8 mb-6">
<h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
<span
class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-bold">2</span>
Map Columns
<span class="ml-auto text-sm font-normal text-slate-500">
<span id="mapped-count">0</span>/<span id="required-count">0</span> required fields mapped
</span>
</h3>
<!-- Column Mapper Table -->
<div class="overflow-x-auto mb-6">
<table class="w-full text-sm">
<thead class="bg-slate-100">
<tr>
<th class="text-left py-2 px-4 font-medium text-slate-600">CRMTracker Field</th>
<th class="text-left py-2 px-4 font-medium text-slate-600">Your Column</th>
<th class="text-center py-2 px-4 font-medium text-slate-600">Status</th>
<th class="text-left py-2 px-4 font-medium text-slate-600">Sample Data</th>
</tr>
</thead>
<tbody id="column-mapper">
<!-- Dynamic rows -->
</tbody>
</table>
</div>
<!-- Mapping Templates -->
<div class="flex items-center gap-4 flex-wrap">
<span class="text-sm text-slate-500">Templates:</span>
<button onclick="applyTemplate('standard')"
class="text-sm bg-slate-100 hover:bg-slate-200 px-3 py-1 rounded">
Auto-detect
</button>
<select id="saved-templates" onchange="loadSavedTemplate(this.value)"
class="text-sm bg-slate-100 border-0 px-3 py-1 rounded">
<option value="">-- Saved Templates --</option>
</select>
<button onclick="saveCurrentTemplate()"
class="text-sm bg-purple-100 hover:bg-purple-200 text-purple-700 px-3 py-1 rounded">
<i class="fa-solid fa-save mr-1"></i>Save Template
</button>
<button onclick="deleteSavedTemplate()"
class="text-sm bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1 rounded">
<i class="fa-solid fa-trash mr-1"></i>Delete
</button>
</div>
</div>
<!-- Step 3: Preview & Import -->
<div id="step3" class="wizard-step bg-white rounded-xl shadow-lg border border-slate-200 p-8 mb-6">
<h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
<span
class="w-8 h-8 rounded-full bg-green-100 text-green-700 flex items-center justify-center text-sm font-bold">3</span>
Preview & Import
</h3>
<!-- Import Settings -->
<div class="grid grid-cols-2 gap-6 mb-6">
<div>
<label class="block text-sm font-medium text-slate-700 mb-2">Market</label>
<select id="import-market" class="w-full px-3 py-2 border border-slate-200 rounded-lg">
<option value="CA-ON">🇨🇦 Canada - Ontario (AGCO)</option>
<option value="TR-ALL">🇹🇷 Turkey - All</option>
<option value="CH-ALL">🇨🇭 Switzerland - All</option>
<option value="UK-ALL">🇬🇧 UK - All</option>
<option value="US-NJ">🇺🇸 USA - New Jersey</option>
</select>
</div>
<div>
<label class="block text-sm font-medium text-slate-700 mb-2">Import Name</label>
<input type="text" id="import-name" placeholder="e.g., Q4 2025 Email Campaign"
class="w-full px-3 py-2 border border-slate-200 rounded-lg">
</div>
</div>
<!-- Preview Table -->
<div class="border border-slate-200 rounded-lg overflow-hidden mb-6">
<div class="bg-slate-50 px-4 py-2 border-b border-slate-200 flex justify-between items-center">
<span class="font-medium text-slate-700">Data Preview</span>
<span class="text-sm text-slate-500">Showing first 5 rows</span>
</div>
<div class="overflow-x-auto max-h-64">
<table class="w-full text-sm">
<thead class="bg-slate-100 sticky top-0">
<tr id="preview-header">
<!-- Dynamic headers -->
</tr>
</thead>
<tbody id="preview-body">
<!-- Dynamic rows -->
</tbody>
</table>
</div>
</div>
<!-- Import Stats -->
<div class="grid grid-cols-4 gap-4 mb-6">
<div class="bg-green-50 rounded-lg p-4 text-center">
<p class="text-2xl font-bold text-green-700" id="stat-valid">0</p>
<p class="text-xs text-green-600">Valid Rows</p>
</div>
<div class="bg-amber-50 rounded-lg p-4 text-center">
<p class="text-2xl font-bold text-amber-700" id="stat-skipped">0</p>
<p class="text-xs text-amber-600">Skipped (Duplicates)</p>
</div>
<div class="bg-red-50 rounded-lg p-4 text-center">
<p class="text-2xl font-bold text-red-700" id="stat-errors">0</p>
<p class="text-xs text-red-600">Errors</p>
</div>
<div class="bg-blue-50 rounded-lg p-4 text-center">
<p class="text-2xl font-bold text-blue-700" id="stat-competitors">0</p>
<p class="text-xs text-blue-600">Competitors</p>
</div>
</div>
<!-- Progress Bar -->
<div id="import-progress" class="hidden mb-6">
<div class="flex justify-between text-sm text-slate-600 mb-2">
<span id="import-status">Importing...</span>
<span id="import-percent">0%</span>
</div>
<div class="h-2 bg-slate-200 rounded-full overflow-hidden">
<div id="import-bar" class="h-full bg-purple-600 rounded-full transition-all" style="width: 0%">
</div>
</div>
</div>
</div>
<!-- Step 4: Queue Review & Approve -->
<div id="step4" class="wizard-step bg-white rounded-xl shadow-lg border border-slate-200 p-8 mb-6">
<h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
<span
class="w-8 h-8 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-sm font-bold">4</span>
Review & Approve
</h3>
<!-- Queue Status -->
<div id="queue-status" class="mb-6">
<div class="bg-slate-50 rounded-lg p-6 text-center">
<i class="fa-solid fa-spinner fa-spin text-4xl text-slate-400 mb-3"></i>
<p class="text-slate-600">Submitting to queue...</p>
</div>
</div>
<!-- Validation Results (shown after submit) -->
<div id="validation-results" class="hidden mb-6">
<div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
<div class="flex items-center gap-2 mb-3">
<i class="fa-solid fa-check-circle text-green-600 text-xl"></i>
<span class="font-semibold text-green-800">Validation Complete</span>
</div>
<div class="grid grid-cols-4 gap-4">
<div class="text-center">
<p class="text-2xl font-bold text-green-700" id="queue-hits">0</p>
<p class="text-xs text-green-600">Hits</p>
</div>
<div class="text-center">
<p class="text-2xl font-bold text-blue-700" id="queue-brands">0</p>
<p class="text-xs text-blue-600">New Brands</p>
</div>
<div class="text-center">
<p class="text-2xl font-bold text-purple-700" id="queue-competitors">0</p>
<p class="text-xs text-purple-600">New Competitors</p>
</div>
<div class="text-center">
<p class="text-2xl font-bold text-red-700" id="queue-errors">0</p>
<p class="text-xs text-red-600">Errors</p>
</div>
</div>
</div>
<!-- Queue Item Info -->
<div class="bg-slate-50 rounded-lg p-4 flex items-center justify-between">
<div>
<p class="text-sm text-slate-500">Queue ID</p>
<p class="font-mono text-slate-700" id="queue-id">-</p>
</div>
<div>
<p class="text-sm text-slate-500">Status</p>
<span id="queue-item-status"
class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-sm font-medium">
pending_review
</span>
</div>
</div>
</div>
<!-- Error Display -->
<div id="queue-errors-panel" class="hidden mb-6">
<div class="bg-red-50 border border-red-200 rounded-lg p-4">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-exclamation-triangle text-red-600"></i>
<span class="font-semibold text-red-800">Validation Errors</span>
</div>
<ul id="queue-error-list" class="text-sm text-red-700 list-disc list-inside"></ul>
</div>
</div>
<!-- Import Progress (shown during approve) -->
<div id="approve-progress" class="hidden mb-6">
<div class="flex justify-between text-sm text-slate-600 mb-2">
<span id="approve-status">Importing...</span>
<span id="approve-percent">0%</span>
</div>
<div class="h-2 bg-slate-200 rounded-full overflow-hidden">
<div id="approve-bar" class="h-full bg-green-600 rounded-full transition-all" style="width: 0%">
</div>
</div>
</div>
<!-- Approve/Reject Buttons -->
<div id="queue-actions" class="hidden flex gap-4 justify-end">
<button id="reject-btn" onclick="rejectImport()"
class="px-6 py-3 border border-red-300 text-red-600 rounded-lg hover:bg-red-50">
<i class="fa-solid fa-times mr-2"></i>Reject
</button>
<button id="approve-btn" onclick="approveImport()"
class="px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700">
<i class="fa-solid fa-check mr-2"></i>Approve & Import
</button>
</div>
</div>
<!-- Action Buttons -->
<div class="flex justify-between">
<button id="back-btn"
class="hidden px-6 py-3 border border-slate-300 rounded-lg text-slate-600 hover:bg-slate-50">
<i class="fa-solid fa-arrow-left mr-2"></i>Back
</button>
<div class="flex gap-4 ml-auto">
<button id="cancel-btn" onclick="window.location.href='../dashboard.html'"
class="px-6 py-3 border border-slate-300 rounded-lg text-slate-600 hover:bg-slate-50">
Cancel
</button>
<button id="next-btn" disabled
class="px-6 py-3 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 disabled:opacity-50 disabled:cursor-not-allowed">
<span id="next-text">Next: Map Columns</span>
<i class="fa-solid fa-arrow-right ml-2"></i>
</button>
</div>
</div>
</main>
<script>
// ====================================
// STATE
// ====================================
let currentStep = 1;
let workbook = null;
let currentSheet = null;
let parsedData = [];
let columnMapping = {};
let fileHeaders = [];
// Required fields for import - Comprehensive list
const REQUIRED_FIELDS = [
// Core identifiers
{ key: 'tracking_hit_id', label: 'Tracking Hit ID', required: true },
{ key: 'competitor_id', label: 'Competitor ID', required: true },
{ key: 'competitor_name', label: 'Competitor Name', required: false },
{ key: 'subject', label: 'Subject Line', required: true },
{ key: 'local_created_at', label: 'Date/Time', required: true },
// Channel & Classification
{ key: 'channel', label: 'Channel', required: false, default: 'email' },
{ key: 'lifecycle', label: 'Lifecycle Stage', required: false },
{ key: 'vertical', label: 'Vertical', required: false },
{ key: 'industry', label: 'Industry', required: false },
{ key: 'classification', label: 'Classification', required: false },
{ key: 'category', label: 'Category', required: false },
{ key: 'website', label: 'Website', required: false },
{ key: 'country', label: 'Country/Market', required: false },
// Sender info
{ key: 'from_email', label: 'From Email', required: false },
{ key: 'from_ip', label: 'Sender IP', required: false },
{ key: 'to_email', label: 'To Email', required: false },
{ key: 'spam_score', label: 'Spam Score', required: false },
// Offer/Promo details
{ key: 'offer_type', label: 'Offer Type', required: false },
{ key: 'offer_value', label: 'Offer Value', required: false },
{ key: 'bonus_code', label: 'Bonus Code', required: false },
{ key: 'wagering', label: 'Wagering Requirement', required: false },
{ key: 'max_cashout', label: 'Max Cashout', required: false },
{ key: 'min_deposit', label: 'Min Deposit', required: false },
// Content features
{ key: 'has_personalization', label: 'Has Personalization', required: false },
{ key: 'has_offer', label: 'Has Offer', required: false },
{ key: 'urgency_score', label: 'Urgency Score', required: false },
{ key: 'tone', label: 'Tone/Voice', required: false },
{ key: 'target_segment', label: 'Target Segment', required: false },
{ key: 'image_count', label: 'Image Count', required: false },
{ key: 'word_count', label: 'Word Count', required: false },
// Text fields (content excluded - HTML breaks tables)
{ key: 'game_suggestions', label: 'Game Suggestions', required: false },
{ key: 'holiday', label: 'Holiday Reference', required: false },
{ key: 'terms_conditions', label: 'Terms & Conditions', required: false },
{ key: 'content_category', label: 'Content Category', required: false }
];
// Auto-mapping rules (file column → CRMTracker field)
const AUTO_MAP_RULES = {
// Core
'tracking_hit_id': ['tracking_hit_id', 'hit_id', 'message_id', 'tracking_Hit_id'],
'competitor_id': ['competitor_id', 'competitor_Id', 'competitorId'],
'competitor_name': ['competitor_name', 'brand_name', 'operator_name', 'competitor', 'brand', 'operator'],
'subject': ['subject', 'subject_line', 'email_subject', 'subject_line_text'],
'local_created_at': ['local_created_at', 'received_at', 'date', 'sent_date', 'created_at', 'timestamp', 'sent_at'],
// Channel & Classification
'channel': ['channel', 'tracking_channel_type', 'type', 'message_type', 'communication_type'],
'lifecycle': ['lifecycle', 'lifecycle_stage', 'customer_stage'],
'vertical': ['vertical', 'business_vertical'],
'industry': ['industry', 'sector'],
'category': ['category', 'hit_category'],
'classification': ['classification', 'email_type'],
'website': ['website', 'site', 'domain', 'url'],
'country': ['country', 'market', 'region', 'geo'],
// Sender
'from_email': ['from_email', 'sender', 'sender_email'],
'from_ip': ['from_ip', 'sender_ip', 'ip_address', 'ip'],
'to_email': ['to_email', 'to', 'recipient', 'to_address'],
'spam_score': ['spam_score', 'spam', 'spam_rating'],
// Offers
'offer_type': ['offer_type', 'promotion_type', 'promo_type', 'type_of_offer'],
'offer_value': ['offer_value', 'promo_value', 'value', 'bonus_value'],
'bonus_code': ['bonus_code', 'promo_code', 'code'],
'wagering': ['wagering', 'wagering_req', 'playthrough', 'rollover'],
'max_cashout': ['max_cashout', 'max_withdrawal', 'cashout_limit'],
'min_deposit': ['min_deposit', 'minimum_deposit', 'deposit_requirement'],
// Content features
'has_personalization': ['has_personalization', 'personalization_first_name_used', 'first_name', 'personalized'],
'has_offer': ['has_offer', 'has_promo', 'contains_offer'],
'urgency_score': ['urgency_score', 'urgency', 'urgency_level'],
'tone': ['tone', 'tonality', 'tone_of_voice', 'voice', 'sentiment'],
'target_segment': ['target_segment', 'segment', 'audience', 'customer_segment'],
'image_count': ['image_count', 'number_of_images', 'images'],
'word_count': ['word_count', 'words', 'text_length'],
// Content
'content': ['content', 'body_text_only', 'body', 'email_body', 'html_content'],
'game_suggestions': ['game_suggestions', 'games', 'featured_games'],
'holiday': ['holiday', 'holiday_reference', 'occasion'],
'terms_conditions': ['terms_conditions', 'terms_and_conditions', 'tc', 'fine_print'],
'content_category': ['content_category', 'email_category', 'message_category', 'category_type']
};
// ====================================
// DOM ELEMENTS
// ====================================
const dropZone = document.getElementById('drop-zone');
const fileInput = document.getElementById('file-input');
const fileInfo = document.getElementById('file-info');
const sheetSelect = document.getElementById('sheet-select');
const columnMapper = document.getElementById('column-mapper');
const nextBtn = document.getElementById('next-btn');
const backBtn = document.getElementById('back-btn');
// ====================================
// STEP 1: FILE UPLOAD
// ====================================
dropZone.addEventListener('click', () => fileInput.click());
dropZone.addEventListener('dragover', (e) => {
e.preventDefault();
dropZone.classList.add('dragover');
});
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
dropZone.addEventListener('drop', (e) => {
e.preventDefault();
dropZone.classList.remove('dragover');
if (e.dataTransfer.files.length > 0) handleFile(e.dataTransfer.files[0]);
});
fileInput.addEventListener('change', (e) => {
if (e.target.files.length > 0) handleFile(e.target.files[0]);
});
document.getElementById('remove-file').addEventListener('click', resetFile);
async function handleFile(file) {
const ext = file.name.split('.').pop().toLowerCase();
if (!['xlsx', 'xls', 'csv'].includes(ext)) {
alert('Please upload an Excel (.xlsx, .xls) or CSV file');
return;
}
document.getElementById('file-name').textContent = file.name;
document.getElementById('file-size').textContent = formatSize(file.size);
try {
const data = await file.arrayBuffer();
workbook = XLSX.read(data, { type: 'array' });
// Populate sheet selector
sheetSelect.innerHTML = workbook.SheetNames.map((name, i) =>
`<option value="${name}">${name}</option>`
).join('');
document.getElementById('sheet-count').textContent = `${workbook.SheetNames.length} sheet(s)`;
// Load first sheet
loadSheet(workbook.SheetNames[0]);
fileInfo.classList.remove('hidden');
nextBtn.disabled = false;
} catch (e) {
console.error('File parse error:', e);
alert('Error parsing file: ' + e.message);
}
}
sheetSelect.addEventListener('change', (e) => loadSheet(e.target.value));
function loadSheet(sheetName) {
currentSheet = workbook.Sheets[sheetName];
parsedData = XLSX.utils.sheet_to_json(currentSheet, { defval: '' });
fileHeaders = parsedData.length > 0 ? Object.keys(parsedData[0]) : [];
document.getElementById('row-count').textContent = `${parsedData.length} rows`;
// Auto-map columns
autoMapColumns();
}
function resetFile() {
workbook = null;
currentSheet = null;
parsedData = [];
fileHeaders = [];
columnMapping = {};
fileInfo.classList.add('hidden');
fileInput.value = '';
nextBtn.disabled = true;
goToStep(1);
}
// ====================================
// STEP 2: COLUMN MAPPING
// ====================================
function autoMapColumns() {
columnMapping = {};
REQUIRED_FIELDS.forEach(field => {
const rules = AUTO_MAP_RULES[field.key] || [];
const match = fileHeaders.find(h =>
rules.some(r => h.toLowerCase().includes(r.toLowerCase()))
);
if (match) columnMapping[field.key] = match;
});
}
function renderColumnMapper() {
columnMapper.innerHTML = REQUIRED_FIELDS.map(field => {
const mapped = columnMapping[field.key];
const sample = mapped && parsedData[0] ? parsedData[0][mapped] : '';
const status = mapped ? 'mapped' : (field.required ? 'missing' : 'optional');
return `
<tr class="column-mapper-row border-b border-slate-100">
<td class="py-3 px-4">
<span class="font-medium text-slate-700">${field.label}</span>
${field.required ? '<span class="text-red-500 ml-1">*</span>' : ''}
</td>
<td class="py-3 px-4">
<select onchange="updateMapping('${field.key}', this.value)"
class="w-full px-2 py-1 border border-slate-200 rounded text-sm">
<option value="">-- Select column --</option>
${fileHeaders.map(h => `
<option value="${h}" ${mapped === h ? 'selected' : ''}>${h}</option>
`).join('')}
</select>
</td>
<td class="py-3 px-4 text-center">
${status === 'mapped' ?
'<i class="fa-solid fa-check-circle text-green-500"></i>' :
status === 'missing' ?
'<i class="fa-solid fa-exclamation-circle text-red-500"></i>' :
'<i class="fa-solid fa-minus-circle text-slate-300"></i>'}
</td>
<td class="py-3 px-4 text-slate-500 text-xs truncate max-w-xs" title="${sample}">
${sample || '<span class="text-slate-300">No data</span>'}
</td>
</tr>
`;
}).join('');
updateMappingStats();
}
function updateMapping(field, column) {
if (column) {
columnMapping[field] = column;
} else {
delete columnMapping[field];
}
renderColumnMapper();
}
function updateMappingStats() {
const required = REQUIRED_FIELDS.filter(f => f.required);
const mapped = required.filter(f => columnMapping[f.key]);
document.getElementById('mapped-count').textContent = mapped.length;
document.getElementById('required-count').textContent = required.length;
// Enable next if all required are mapped
if (currentStep === 2) {
nextBtn.disabled = mapped.length < required.length;
}
}
function applyTemplate(template) {
// Reset and apply template-specific mappings
if (template === 'standard') {
autoMapColumns();
}
renderColumnMapper();
}
// ====================================
// TEMPLATE SAVE/LOAD (localStorage)
// ====================================
const TEMPLATES_KEY = 'crmt_import_templates';
function getSavedTemplates() {
try {
return JSON.parse(localStorage.getItem(TEMPLATES_KEY) || '{}');
} catch (e) {
return {};
}
}
function refreshTemplatesDropdown() {
const select = document.getElementById('saved-templates');
if (!select) return;
const templates = getSavedTemplates();
const names = Object.keys(templates);
select.innerHTML = '<option value="">-- Saved Templates (' + names.length + ') --</option>' +
names.map(n => `<option value="${n}">${n}</option>`).join('');
}
function saveCurrentTemplate() {
const name = prompt('Enter template name:', 'My Template');
if (!name || !name.trim()) return;
const templates = getSavedTemplates();
templates[name.trim()] = { ...columnMapping };
localStorage.setItem(TEMPLATES_KEY, JSON.stringify(templates));
refreshTemplatesDropdown();
document.getElementById('saved-templates').value = name.trim();
alert(`Template "${name.trim()}" saved!`);
}
function loadSavedTemplate(name) {
if (!name) return;
const templates = getSavedTemplates();
if (templates[name]) {
columnMapping = { ...templates[name] };
renderColumnMapper();
}
}
function deleteSavedTemplate() {
const select = document.getElementById('saved-templates');
const name = select?.value;
if (!name) {
alert('Please select a template to delete');
return;
}
if (!confirm(`Delete template "${name}"?`)) return;
const templates = getSavedTemplates();
delete templates[name];
localStorage.setItem(TEMPLATES_KEY, JSON.stringify(templates));
refreshTemplatesDropdown();
alert(`Template "${name}" deleted`);
}
// Initialize templates dropdown on page load
document.addEventListener('DOMContentLoaded', refreshTemplatesDropdown);
// ====================================
// STEP 3: PREVIEW & IMPORT
// ====================================
function renderPreview() {
const mappedKeys = Object.keys(columnMapping).filter(k => columnMapping[k]);
// Header
document.getElementById('preview-header').innerHTML = mappedKeys.map(k =>
`<th class="text-left py-2 px-3 font-medium text-slate-600 text-xs">${REQUIRED_FIELDS.find(f => f.key === k)?.label || k}</th>`
).join('');
// Body (first 5 rows)
const previewRows = parsedData.slice(0, 5);
document.getElementById('preview-body').innerHTML = previewRows.map(row =>
`<tr class="border-b border-slate-100">
${mappedKeys.map(k => `<td class="py-2 px-3 text-slate-700 text-xs truncate max-w-xs">${row[columnMapping[k]] || ''}</td>`).join('')}
</tr>`
).join('');
// Stats
const uniqueCompetitors = new Set(parsedData.map(r => r[columnMapping['competitor_id']])).size;
document.getElementById('stat-valid').textContent = parsedData.length;
document.getElementById('stat-competitors').textContent = uniqueCompetitors;
}
async function startImport() {
const progressBar = document.getElementById('import-bar');
const progressPercent = document.getElementById('import-percent');
const progressSection = document.getElementById('import-progress');
const statusEl = document.getElementById('import-status');
// Show the Step 4 queue status panel
document.getElementById('queue-status').classList.remove('hidden');
document.getElementById('queue-status').innerHTML = `
<div class="bg-slate-50 rounded-lg p-6 flex flex-col items-center justify-center">
<i class="fa-solid fa-spinner fa-spin text-purple-600 text-2xl mb-2"></i>
<p class="text-slate-600">Importing data...</p>
</div>
`;
progressSection.classList.remove('hidden');
nextBtn.disabled = true;
const market = document.getElementById('import-market').value;
const importName = document.getElementById('import-name').value || `Import ${new Date().toISOString().split('T')[0]}`;
// Helper: Convert Excel serial date to ISO string
const excelSerialToISO = (serial) => {
if (!serial) return null;
// If already a date string, return as-is
if (typeof serial === 'string' && isNaN(serial)) return serial;
// Excel serial: days since 1900-01-01 (with 1900 bug adjustment)
const numSerial = parseFloat(serial);
if (isNaN(numSerial)) return serial;
const date = new Date((numSerial - 25569) * 86400 * 1000);
return date.toISOString();
};
// Transform data according to mapping
// Pass ALL original row properties so import.js gets tracking_hit_id, competitor_name, etc.
const transformedData = parsedData.map(row => {
// Start with all original row data
const hit = { ...row, market_id: market };
// Override with mapped fields  
Object.keys(columnMapping).forEach(key => {
if (columnMapping[key]) {
hit[key] = row[columnMapping[key]];
}
});
// Convert Excel serial dates to ISO format for date fields
if (hit.received_at) {
hit.received_at = excelSerialToISO(hit.received_at);
}
if (hit.created_at) {
hit.created_at = excelSerialToISO(hit.created_at);
}
if (hit.local_created_at) {
hit.local_created_at = excelSerialToISO(hit.local_created_at);
}
// Set defaults
if (!hit.channel) hit.channel = 'email';
return hit;
});
// Batch import (100 at a time)
const batchSize = 100;
let inserted = 0;
let updated = 0;
let skipped = 0;
let errors = 0;
for (let i = 0; i < transformedData.length; i += batchSize) {
const batch = transformedData.slice(i, i + batchSize);
try {
const response = await fetch('/.netlify/functions/import', {
method: 'POST',
headers: { 'Content-Type': 'application/json' },
body: JSON.stringify({
rows: batch,
market_id: market,
source_file: importName
})
});
const result = await response.json();
if (!response.ok || result.error) {
console.error('Import batch error:', result.error || 'Unknown error');
errors += batch.length;
} else if (result.stats) {
inserted += result.stats.hits_inserted || 0;
updated += result.stats.hits_updated || 0;
skipped += result.stats.hits_skipped || 0;
errors += result.stats.errors?.length || 0;
} else {
inserted += batch.length;
}
} catch (e) {
console.error('Batch error:', e);
errors += batch.length;
}
// Update progress
const pct = Math.round(((i + batch.length) / transformedData.length) * 100);
progressBar.style.width = pct + '%';
progressPercent.textContent = pct + '%';
statusEl.textContent = `Importing... ${i + batch.length} of ${transformedData.length}`;
}
// Final stats
document.getElementById('stat-valid').textContent = inserted + updated;
document.getElementById('stat-skipped').textContent = skipped;
document.getElementById('stat-errors').textContent = errors;
statusEl.textContent = 'Import complete!';
progressBar.style.width = '100%';
progressBar.classList.remove('bg-purple-600');
progressBar.classList.add('bg-green-500');
// Update queue status panel with summary
const queueStatus = document.getElementById('queue-status');
queueStatus.innerHTML = `
<div class="bg-slate-50 rounded-lg p-6 flex flex-col items-center justify-center">
<i class="fa-solid fa-circle-check text-green-500 text-3xl mb-3"></i>
<p class="text-lg font-semibold text-slate-800 mb-4">Import Complete!</p>
<div class="grid grid-cols-4 gap-3 text-center mb-4 w-full max-w-lg">
<div class="bg-green-50 rounded-lg p-3">
<p class="text-2xl font-bold text-green-600">${inserted}</p>
<p class="text-xs text-slate-600">Inserted</p>
</div>
<div class="bg-blue-50 rounded-lg p-3">
<p class="text-2xl font-bold text-blue-600">${updated}</p>
<p class="text-xs text-slate-600">Updated</p>
</div>
<div class="bg-yellow-50 rounded-lg p-3">
<p class="text-2xl font-bold text-yellow-600">${skipped}</p>
<p class="text-xs text-slate-600">Skipped</p>
</div>
<div class="bg-red-50 rounded-lg p-3">
<p class="text-2xl font-bold text-red-600">${errors}</p>
<p class="text-xs text-slate-600">Errors</p>
</div>
</div>
<button onclick="window.location.href='../dashboard.html'" 
class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
<i class="fa-solid fa-chart-line mr-2"></i>Go to Dashboard
</button>
</div>
`;
// Hide the old Done button
nextBtn.classList.add('hidden');
}
// ====================================
// NAVIGATION
// ====================================
let currentQueueId = null; // Track the current queue item
function goToStep(step) {
currentStep = step;
// Update step visibility
[1, 2, 3, 4].forEach(s => {
const stepEl = document.getElementById(`step${s}`);
const dotEl = document.getElementById(`step${s}-dot`);
if (stepEl) stepEl.classList.toggle('active', s <= step);
if (dotEl) {
dotEl.classList.toggle('bg-purple-500', s <= step);
dotEl.classList.toggle('bg-slate-600', s > step);
}
});
// Update buttons
backBtn.classList.toggle('hidden', step === 1 || step === 4);
if (step === 1) {
document.getElementById('next-text').textContent = 'Next: Map Columns';
nextBtn.disabled = !workbook;
nextBtn.classList.remove('hidden');
} else if (step === 2) {
document.getElementById('next-text').textContent = 'Next: Preview';
renderColumnMapper();
updateMappingStats();
nextBtn.classList.remove('hidden');
} else if (step === 3) {
document.getElementById('next-text').textContent = 'Submit for Review';
renderPreview();
nextBtn.disabled = false;
nextBtn.classList.remove('hidden');
} else if (step === 4) {
// Use direct batched import (bypasses queue API issue with large payloads)
nextBtn.classList.add('hidden');
startImport(); // Uses batching to /api/import which handles large datasets
}
}
nextBtn.addEventListener('click', () => {
if (currentStep < 4) {
goToStep(currentStep + 1);
}
});
backBtn.addEventListener('click', () => {
if (currentStep > 1 && currentStep < 4) goToStep(currentStep - 1);
});
// ====================================
// STEP 4: QUEUE FUNCTIONS
// ====================================
async function submitToQueue() {
// Reset UI
document.getElementById('queue-status').classList.remove('hidden');
document.getElementById('validation-results').classList.add('hidden');
document.getElementById('queue-errors-panel').classList.add('hidden');
document.getElementById('queue-actions').classList.add('hidden');
const market = document.getElementById('import-market').value;
const importName = document.getElementById('import-name').value || `Import ${new Date().toISOString().split('T')[0]}`;
// Transform data according to mapping
const transformedData = parsedData.map(row => {
const hit = { ...row, market_id: market };
Object.keys(columnMapping).forEach(key => {
if (columnMapping[key]) {
hit[key] = row[columnMapping[key]];
}
});
// Excel date conversion
if (hit.received_at && typeof hit.received_at === 'number') {
const date = new Date((hit.received_at - 25569) * 86400 * 1000);
hit.received_at = date.toISOString();
}
if (!hit.channel) hit.channel = 'email';
return hit;
});
try {
const response = await fetch('/.netlify/functions/import-queue', {
method: 'POST',
headers: { 'Content-Type': 'application/json' },
body: JSON.stringify({
source: `excel:${document.getElementById('file-name').textContent}`,
market_id: market,
data: transformedData
})
});
const result = await response.json();
if (!response.ok || result.error) {
throw new Error(result.error || 'Failed to submit to queue');
}
// Store queue ID
currentQueueId = result.data?.id || result.id;
// Update UI with validation results
document.getElementById('queue-status').classList.add('hidden');
document.getElementById('validation-results').classList.remove('hidden');
document.getElementById('queue-actions').classList.remove('hidden');
document.getElementById('queue-actions').classList.add('flex');
document.getElementById('queue-id').textContent = currentQueueId;
document.getElementById('queue-hits').textContent = result.data?.hit_count || transformedData.length;
document.getElementById('queue-brands').textContent = result.data?.new_brands_count || 0;
document.getElementById('queue-competitors').textContent = result.data?.new_competitors_count || 0;
document.getElementById('queue-errors').textContent = result.data?.error_count || 0;
// Show errors if any
if (result.data?.error_details?.length > 0) {
document.getElementById('queue-errors-panel').classList.remove('hidden');
document.getElementById('queue-error-list').innerHTML = result.data.error_details
.slice(0, 10)
.map(e => `<li>${e}</li>`)
.join('');
}
} catch (error) {
console.error('Queue submit error:', error);
document.getElementById('queue-status').innerHTML = `
<div class="bg-red-50 rounded-lg p-6 text-center">
<i class="fa-solid fa-exclamation-triangle text-4xl text-red-400 mb-3"></i>
<p class="text-red-600 font-medium">Failed to submit to queue</p>
<p class="text-red-500 text-sm">${error.message}</p>
<button onclick="goToStep(3)" class="mt-4 px-4 py-2 bg-slate-200 rounded-lg text-slate-700">
Go Back
</button>
</div>
`;
}
}
async function approveImport() {
if (!currentQueueId) {
alert('No queue item to approve');
return;
}
// Disable buttons and show progress
document.getElementById('approve-btn').disabled = true;
document.getElementById('reject-btn').disabled = true;
document.getElementById('approve-progress').classList.remove('hidden');
document.getElementById('approve-status').textContent = 'Approving and importing...';
try {
const response = await fetch(`/.netlify/functions/import-queue/${currentQueueId}/approve`, {
method: 'POST'
});
const result = await response.json();
if (!response.ok || result.error) {
throw new Error(result.error || 'Failed to approve import');
}
// Success
document.getElementById('approve-bar').style.width = '100%';
document.getElementById('approve-percent').textContent = '100%';
document.getElementById('approve-status').textContent = 'Import complete!';
document.getElementById('approve-bar').classList.add('bg-green-500');
document.getElementById('queue-item-status').textContent = 'approved';
document.getElementById('queue-item-status').className = 'px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium';
// Hide approve/reject, show done button
document.getElementById('queue-actions').innerHTML = `
<button onclick="window.location.href='../dashboard.html'"
class="px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700">
<i class="fa-solid fa-check mr-2"></i>Done - Go to Dashboard
</button>
`;
} catch (error) {
console.error('Approve error:', error);
document.getElementById('approve-status').textContent = 'Error: ' + error.message;
document.getElementById('approve-bar').classList.add('bg-red-500');
document.getElementById('approve-btn').disabled = false;
document.getElementById('reject-btn').disabled = false;
}
}
async function rejectImport() {
if (!currentQueueId) {
alert('No queue item to reject');
return;
}
if (!confirm('Are you sure you want to reject this import?')) return;
try {
const response = await fetch(`/.netlify/functions/import-queue/${currentQueueId}/reject`, {
method: 'POST'
});
const result = await response.json();
if (!response.ok || result.error) {
throw new Error(result.error || 'Failed to reject import');
}
// Update status
document.getElementById('queue-item-status').textContent = 'rejected';
document.getElementById('queue-item-status').className = 'px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-medium';
// Show done button
document.getElementById('queue-actions').innerHTML = `
<button onclick="window.location.href='upload.html'"
class="px-6 py-3 bg-slate-600 text-white rounded-lg font-medium hover:bg-slate-700">
<i class="fa-solid fa-redo mr-2"></i>Start Over
</button>
`;
} catch (error) {
console.error('Reject error:', error);
alert('Failed to reject: ' + error.message);
}
}
// ====================================
// UTILITIES
// ====================================
function formatSize(bytes) {
if (bytes < 1024) return bytes + ' B';
if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
}
</script>
@endsection

@push('page-scripts')
<script>
        // ====================================
        // STATE
        // ====================================
        let currentStep = 1;
        let workbook = null;
        let currentSheet = null;
        let parsedData = [];
        let columnMapping = {};
        let fileHeaders = [];

        // Required fields for import - Comprehensive list
        const REQUIRED_FIELDS = [
            // Core identifiers
            { key: 'tracking_hit_id', label: 'Tracking Hit ID', required: true },
            { key: 'competitor_id', label: 'Competitor ID', required: true },
            { key: 'competitor_name', label: 'Competitor Name', required: false },
            { key: 'subject', label: 'Subject Line', required: true },
            { key: 'local_created_at', label: 'Date/Time', required: true },

            // Channel & Classification
            { key: 'channel', label: 'Channel', required: false, default: 'email' },
            { key: 'lifecycle', label: 'Lifecycle Stage', required: false },
            { key: 'vertical', label: 'Vertical', required: false },
            { key: 'industry', label: 'Industry', required: false },
            { key: 'classification', label: 'Classification', required: false },
            { key: 'category', label: 'Category', required: false },
            { key: 'website', label: 'Website', required: false },
            { key: 'country', label: 'Country/Market', required: false },

            // Sender info
            { key: 'from_email', label: 'From Email', required: false },
            { key: 'from_ip', label: 'Sender IP', required: false },
            { key: 'to_email', label: 'To Email', required: false },
            { key: 'spam_score', label: 'Spam Score', required: false },

            // Offer/Promo details
            { key: 'offer_type', label: 'Offer Type', required: false },
            { key: 'offer_value', label: 'Offer Value', required: false },
            { key: 'bonus_code', label: 'Bonus Code', required: false },
            { key: 'wagering', label: 'Wagering Requirement', required: false },
            { key: 'max_cashout', label: 'Max Cashout', required: false },
            { key: 'min_deposit', label: 'Min Deposit', required: false },

            // Content features
            { key: 'has_personalization', label: 'Has Personalization', required: false },
            { key: 'has_offer', label: 'Has Offer', required: false },
            { key: 'urgency_score', label: 'Urgency Score', required: false },
            { key: 'tone', label: 'Tone/Voice', required: false },
            { key: 'target_segment', label: 'Target Segment', required: false },
            { key: 'image_count', label: 'Image Count', required: false },
            { key: 'word_count', label: 'Word Count', required: false },

            // Text fields (content excluded - HTML breaks tables)
            { key: 'game_suggestions', label: 'Game Suggestions', required: false },
            { key: 'holiday', label: 'Holiday Reference', required: false },
            { key: 'terms_conditions', label: 'Terms & Conditions', required: false },
            { key: 'content_category', label: 'Content Category', required: false }
        ];

        // Auto-mapping rules (file column → CRMTracker field)
        const AUTO_MAP_RULES = {
            // Core
            'tracking_hit_id': ['tracking_hit_id', 'hit_id', 'message_id', 'tracking_Hit_id'],
            'competitor_id': ['competitor_id', 'competitor_Id', 'competitorId'],
            'competitor_name': ['competitor_name', 'brand_name', 'operator_name', 'competitor', 'brand', 'operator'],
            'subject': ['subject', 'subject_line', 'email_subject', 'subject_line_text'],
            'local_created_at': ['local_created_at', 'received_at', 'date', 'sent_date', 'created_at', 'timestamp', 'sent_at'],

            // Channel & Classification
            'channel': ['channel', 'tracking_channel_type', 'type', 'message_type', 'communication_type'],
            'lifecycle': ['lifecycle', 'lifecycle_stage', 'customer_stage'],
            'vertical': ['vertical', 'business_vertical'],
            'industry': ['industry', 'sector'],
            'category': ['category', 'hit_category'],
            'classification': ['classification', 'email_type'],
            'website': ['website', 'site', 'domain', 'url'],
            'country': ['country', 'market', 'region', 'geo'],

            // Sender
            'from_email': ['from_email', 'sender', 'sender_email'],
            'from_ip': ['from_ip', 'sender_ip', 'ip_address', 'ip'],
            'to_email': ['to_email', 'to', 'recipient', 'to_address'],
            'spam_score': ['spam_score', 'spam', 'spam_rating'],

            // Offers
            'offer_type': ['offer_type', 'promotion_type', 'promo_type', 'type_of_offer'],
            'offer_value': ['offer_value', 'promo_value', 'value', 'bonus_value'],
            'bonus_code': ['bonus_code', 'promo_code', 'code'],
            'wagering': ['wagering', 'wagering_req', 'playthrough', 'rollover'],
            'max_cashout': ['max_cashout', 'max_withdrawal', 'cashout_limit'],
            'min_deposit': ['min_deposit', 'minimum_deposit', 'deposit_requirement'],

            // Content features
            'has_personalization': ['has_personalization', 'personalization_first_name_used', 'first_name', 'personalized'],
            'has_offer': ['has_offer', 'has_promo', 'contains_offer'],
            'urgency_score': ['urgency_score', 'urgency', 'urgency_level'],
            'tone': ['tone', 'tonality', 'tone_of_voice', 'voice', 'sentiment'],
            'target_segment': ['target_segment', 'segment', 'audience', 'customer_segment'],
            'image_count': ['image_count', 'number_of_images', 'images'],
            'word_count': ['word_count', 'words', 'text_length'],

            // Content
            'content': ['content', 'body_text_only', 'body', 'email_body', 'html_content'],
            'game_suggestions': ['game_suggestions', 'games', 'featured_games'],
            'holiday': ['holiday', 'holiday_reference', 'occasion'],
            'terms_conditions': ['terms_conditions', 'terms_and_conditions', 'tc', 'fine_print'],
            'content_category': ['content_category', 'email_category', 'message_category', 'category_type']
        };

        // ====================================
        // DOM ELEMENTS
        // ====================================
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('file-input');
        const fileInfo = document.getElementById('file-info');
        const sheetSelect = document.getElementById('sheet-select');
        const columnMapper = document.getElementById('column-mapper');
        const nextBtn = document.getElementById('next-btn');
        const backBtn = document.getElementById('back-btn');

        // ====================================
        // STEP 1: FILE UPLOAD
        // ====================================
        dropZone.addEventListener('click', () => fileInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });

        dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            if (e.dataTransfer.files.length > 0) handleFile(e.dataTransfer.files[0]);
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) handleFile(e.target.files[0]);
        });

        document.getElementById('remove-file').addEventListener('click', resetFile);

        async function handleFile(file) {
            const ext = file.name.split('.').pop().toLowerCase();
            if (!['xlsx', 'xls', 'csv'].includes(ext)) {
                alert('Please upload an Excel (.xlsx, .xls) or CSV file');
                return;
            }

            document.getElementById('file-name').textContent = file.name;
            document.getElementById('file-size').textContent = formatSize(file.size);

            try {
                const data = await file.arrayBuffer();
                workbook = XLSX.read(data, { type: 'array' });

                // Populate sheet selector
                sheetSelect.innerHTML = workbook.SheetNames.map((name, i) =>
                    `<option value="${name}">${name}</option>`
                ).join('');

                document.getElementById('sheet-count').textContent = `${workbook.SheetNames.length} sheet(s)`;

                // Load first sheet
                loadSheet(workbook.SheetNames[0]);

                fileInfo.classList.remove('hidden');
                nextBtn.disabled = false;
            } catch (e) {
                console.error('File parse error:', e);
                alert('Error parsing file: ' + e.message);
            }
        }

        sheetSelect.addEventListener('change', (e) => loadSheet(e.target.value));

        function loadSheet(sheetName) {
            currentSheet = workbook.Sheets[sheetName];
            parsedData = XLSX.utils.sheet_to_json(currentSheet, { defval: '' });
            fileHeaders = parsedData.length > 0 ? Object.keys(parsedData[0]) : [];

            document.getElementById('row-count').textContent = `${parsedData.length} rows`;

            // Auto-map columns
            autoMapColumns();
        }

        function resetFile() {
            workbook = null;
            currentSheet = null;
            parsedData = [];
            fileHeaders = [];
            columnMapping = {};
            fileInfo.classList.add('hidden');
            fileInput.value = '';
            nextBtn.disabled = true;
            goToStep(1);
        }

        // ====================================
        // STEP 2: COLUMN MAPPING
        // ====================================
        function autoMapColumns() {
            columnMapping = {};

            REQUIRED_FIELDS.forEach(field => {
                const rules = AUTO_MAP_RULES[field.key] || [];
                const match = fileHeaders.find(h =>
                    rules.some(r => h.toLowerCase().includes(r.toLowerCase()))
                );
                if (match) columnMapping[field.key] = match;
            });
        }

        function renderColumnMapper() {
            columnMapper.innerHTML = REQUIRED_FIELDS.map(field => {
                const mapped = columnMapping[field.key];
                const sample = mapped && parsedData[0] ? parsedData[0][mapped] : '';
                const status = mapped ? 'mapped' : (field.required ? 'missing' : 'optional');

                return `
                    <tr class="column-mapper-row border-b border-slate-100">
                        <td class="py-3 px-4">
                            <span class="font-medium text-slate-700">${field.label}</span>
                            ${field.required ? '<span class="text-red-500 ml-1">*</span>' : ''}
                        </td>
                        <td class="py-3 px-4">
                            <select onchange="updateMapping('${field.key}', this.value)"
                                class="w-full px-2 py-1 border border-slate-200 rounded text-sm">
                                <option value="">-- Select column --</option>
                                ${fileHeaders.map(h => `
                                    <option value="${h}" ${mapped === h ? 'selected' : ''}>${h}</option>
                                `).join('')}
                            </select>
                        </td>
                        <td class="py-3 px-4 text-center">
                            ${status === 'mapped' ?
                        '<i class="fa-solid fa-check-circle text-green-500"></i>' :
                        status === 'missing' ?
                            '<i class="fa-solid fa-exclamation-circle text-red-500"></i>' :
                            '<i class="fa-solid fa-minus-circle text-slate-300"></i>'}
                        </td>
                        <td class="py-3 px-4 text-slate-500 text-xs truncate max-w-xs" title="${sample}">
                            ${sample || '<span class="text-slate-300">No data</span>'}
                        </td>
                    </tr>
                `;
            }).join('');

            updateMappingStats();
        }

        function updateMapping(field, column) {
            if (column) {
                columnMapping[field] = column;
            } else {
                delete columnMapping[field];
            }
            renderColumnMapper();
        }

        function updateMappingStats() {
            const required = REQUIRED_FIELDS.filter(f => f.required);
            const mapped = required.filter(f => columnMapping[f.key]);
            document.getElementById('mapped-count').textContent = mapped.length;
            document.getElementById('required-count').textContent = required.length;

            // Enable next if all required are mapped
            if (currentStep === 2) {
                nextBtn.disabled = mapped.length < required.length;
            }
        }

        function applyTemplate(template) {
            // Reset and apply template-specific mappings
            if (template === 'standard') {
                autoMapColumns();
            }
            renderColumnMapper();
        }

        // ====================================
        // TEMPLATE SAVE/LOAD (localStorage)
        // ====================================
        const TEMPLATES_KEY = 'crmt_import_templates';

        function getSavedTemplates() {
            try {
                return JSON.parse(localStorage.getItem(TEMPLATES_KEY) || '{}');
            } catch (e) {
                return {};
            }
        }

        function refreshTemplatesDropdown() {
            const select = document.getElementById('saved-templates');
            if (!select) return;
            const templates = getSavedTemplates();
            const names = Object.keys(templates);
            select.innerHTML = '<option value="">-- Saved Templates (' + names.length + ') --</option>' +
                names.map(n => `<option value="${n}">${n}</option>`).join('');
        }

        function saveCurrentTemplate() {
            const name = prompt('Enter template name:', 'My Template');
            if (!name || !name.trim()) return;

            const templates = getSavedTemplates();
            templates[name.trim()] = { ...columnMapping };
            localStorage.setItem(TEMPLATES_KEY, JSON.stringify(templates));

            refreshTemplatesDropdown();
            document.getElementById('saved-templates').value = name.trim();
            alert(`Template "${name.trim()}" saved!`);
        }

        function loadSavedTemplate(name) {
            if (!name) return;
            const templates = getSavedTemplates();
            if (templates[name]) {
                columnMapping = { ...templates[name] };
                renderColumnMapper();
            }
        }

        function deleteSavedTemplate() {
            const select = document.getElementById('saved-templates');
            const name = select?.value;
            if (!name) {
                alert('Please select a template to delete');
                return;
            }
            if (!confirm(`Delete template "${name}"?`)) return;

            const templates = getSavedTemplates();
            delete templates[name];
            localStorage.setItem(TEMPLATES_KEY, JSON.stringify(templates));

            refreshTemplatesDropdown();
            alert(`Template "${name}" deleted`);
        }

        // Initialize templates dropdown on page load
        document.addEventListener('DOMContentLoaded', refreshTemplatesDropdown);

        // ====================================
        // STEP 3: PREVIEW & IMPORT
        // ====================================
        function renderPreview() {
            const mappedKeys = Object.keys(columnMapping).filter(k => columnMapping[k]);

            // Header
            document.getElementById('preview-header').innerHTML = mappedKeys.map(k =>
                `<th class="text-left py-2 px-3 font-medium text-slate-600 text-xs">${REQUIRED_FIELDS.find(f => f.key === k)?.label || k}</th>`
            ).join('');

            // Body (first 5 rows)
            const previewRows = parsedData.slice(0, 5);
            document.getElementById('preview-body').innerHTML = previewRows.map(row =>
                `<tr class="border-b border-slate-100">
                    ${mappedKeys.map(k => `<td class="py-2 px-3 text-slate-700 text-xs truncate max-w-xs">${row[columnMapping[k]] || ''}</td>`).join('')}
                </tr>`
            ).join('');

            // Stats
            const uniqueCompetitors = new Set(parsedData.map(r => r[columnMapping['competitor_id']])).size;
            document.getElementById('stat-valid').textContent = parsedData.length;
            document.getElementById('stat-competitors').textContent = uniqueCompetitors;
        }

        async function startImport() {
            const progressBar = document.getElementById('import-bar');
            const progressPercent = document.getElementById('import-percent');
            const progressSection = document.getElementById('import-progress');
            const statusEl = document.getElementById('import-status');

            // Show the Step 4 queue status panel
            document.getElementById('queue-status').classList.remove('hidden');
            document.getElementById('queue-status').innerHTML = `
                <div class="bg-slate-50 rounded-lg p-6 flex flex-col items-center justify-center">
                    <i class="fa-solid fa-spinner fa-spin text-purple-600 text-2xl mb-2"></i>
                    <p class="text-slate-600">Importing data...</p>
                </div>
            `;

            progressSection.classList.remove('hidden');
            nextBtn.disabled = true;

            const market = document.getElementById('import-market').value;
            const importName = document.getElementById('import-name').value || `Import ${new Date().toISOString().split('T')[0]}`;

            // Helper: Convert Excel serial date to ISO string
            const excelSerialToISO = (serial) => {
                if (!serial) return null;
                // If already a date string, return as-is
                if (typeof serial === 'string' && isNaN(serial)) return serial;
                // Excel serial: days since 1900-01-01 (with 1900 bug adjustment)
                const numSerial = parseFloat(serial);
                if (isNaN(numSerial)) return serial;
                const date = new Date((numSerial - 25569) * 86400 * 1000);
                return date.toISOString();
            };

            // Transform data according to mapping
            // Pass ALL original row properties so import.js gets tracking_hit_id, competitor_name, etc.
            const transformedData = parsedData.map(row => {
                // Start with all original row data
                const hit = { ...row, market_id: market };
                // Override with mapped fields  
                Object.keys(columnMapping).forEach(key => {
                    if (columnMapping[key]) {
                        hit[key] = row[columnMapping[key]];
                    }
                });
                // Convert Excel serial dates to ISO format for date fields
                if (hit.received_at) {
                    hit.received_at = excelSerialToISO(hit.received_at);
                }
                if (hit.created_at) {
                    hit.created_at = excelSerialToISO(hit.created_at);
                }
                if (hit.local_created_at) {
                    hit.local_created_at = excelSerialToISO(hit.local_created_at);
                }
                // Set defaults
                if (!hit.channel) hit.channel = 'email';
                return hit;
            });

            // Batch import (100 at a time)
            const batchSize = 100;
            let inserted = 0;
            let updated = 0;
            let skipped = 0;
            let errors = 0;

            for (let i = 0; i < transformedData.length; i += batchSize) {
                const batch = transformedData.slice(i, i + batchSize);

                try {
                    const response = await fetch('/.netlify/functions/import', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            rows: batch,
                            market_id: market,
                            source_file: importName
                        })
                    });

                    const result = await response.json();

                    if (!response.ok || result.error) {
                        console.error('Import batch error:', result.error || 'Unknown error');
                        errors += batch.length;
                    } else if (result.stats) {
                        inserted += result.stats.hits_inserted || 0;
                        updated += result.stats.hits_updated || 0;
                        skipped += result.stats.hits_skipped || 0;
                        errors += result.stats.errors?.length || 0;
                    } else {
                        inserted += batch.length;
                    }
                } catch (e) {
                    console.error('Batch error:', e);
                    errors += batch.length;
                }

                // Update progress
                const pct = Math.round(((i + batch.length) / transformedData.length) * 100);
                progressBar.style.width = pct + '%';
                progressPercent.textContent = pct + '%';
                statusEl.textContent = `Importing... ${i + batch.length} of ${transformedData.length}`;
            }

            // Final stats
            document.getElementById('stat-valid').textContent = inserted + updated;
            document.getElementById('stat-skipped').textContent = skipped;
            document.getElementById('stat-errors').textContent = errors;

            statusEl.textContent = 'Import complete!';
            progressBar.style.width = '100%';
            progressBar.classList.remove('bg-purple-600');
            progressBar.classList.add('bg-green-500');

            // Update queue status panel with summary
            const queueStatus = document.getElementById('queue-status');
            queueStatus.innerHTML = `
                <div class="bg-slate-50 rounded-lg p-6 flex flex-col items-center justify-center">
                    <i class="fa-solid fa-circle-check text-green-500 text-3xl mb-3"></i>
                    <p class="text-lg font-semibold text-slate-800 mb-4">Import Complete!</p>
                    <div class="grid grid-cols-4 gap-3 text-center mb-4 w-full max-w-lg">
                        <div class="bg-green-50 rounded-lg p-3">
                            <p class="text-2xl font-bold text-green-600">${inserted}</p>
                            <p class="text-xs text-slate-600">Inserted</p>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-3">
                            <p class="text-2xl font-bold text-blue-600">${updated}</p>
                            <p class="text-xs text-slate-600">Updated</p>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-3">
                            <p class="text-2xl font-bold text-yellow-600">${skipped}</p>
                            <p class="text-xs text-slate-600">Skipped</p>
                        </div>
                        <div class="bg-red-50 rounded-lg p-3">
                            <p class="text-2xl font-bold text-red-600">${errors}</p>
                            <p class="text-xs text-slate-600">Errors</p>
                        </div>
                    </div>
                    <button onclick="window.location.href='../dashboard.html'" 
                        class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                        <i class="fa-solid fa-chart-line mr-2"></i>Go to Dashboard
                    </button>
                </div>
            `;

            // Hide the old Done button
            nextBtn.classList.add('hidden');
        }

        // ====================================
        // NAVIGATION
        // ====================================
        let currentQueueId = null; // Track the current queue item

        function goToStep(step) {
            currentStep = step;

            // Update step visibility
            [1, 2, 3, 4].forEach(s => {
                const stepEl = document.getElementById(`step${s}`);
                const dotEl = document.getElementById(`step${s}-dot`);
                if (stepEl) stepEl.classList.toggle('active', s <= step);
                if (dotEl) {
                    dotEl.classList.toggle('bg-purple-500', s <= step);
                    dotEl.classList.toggle('bg-slate-600', s > step);
                }
            });

            // Update buttons
            backBtn.classList.toggle('hidden', step === 1 || step === 4);

            if (step === 1) {
                document.getElementById('next-text').textContent = 'Next: Map Columns';
                nextBtn.disabled = !workbook;
                nextBtn.classList.remove('hidden');
            } else if (step === 2) {
                document.getElementById('next-text').textContent = 'Next: Preview';
                renderColumnMapper();
                updateMappingStats();
                nextBtn.classList.remove('hidden');
            } else if (step === 3) {
                document.getElementById('next-text').textContent = 'Submit for Review';
                renderPreview();
                nextBtn.disabled = false;
                nextBtn.classList.remove('hidden');
            } else if (step === 4) {
                // Use direct batched import (bypasses queue API issue with large payloads)
                nextBtn.classList.add('hidden');
                startImport(); // Uses batching to /api/import which handles large datasets
            }
        }

        nextBtn.addEventListener('click', () => {
            if (currentStep < 4) {
                goToStep(currentStep + 1);
            }
        });

        backBtn.addEventListener('click', () => {
            if (currentStep > 1 && currentStep < 4) goToStep(currentStep - 1);
        });

        // ====================================
        // STEP 4: QUEUE FUNCTIONS
        // ====================================
        async function submitToQueue() {
            // Reset UI
            document.getElementById('queue-status').classList.remove('hidden');
            document.getElementById('validation-results').classList.add('hidden');
            document.getElementById('queue-errors-panel').classList.add('hidden');
            document.getElementById('queue-actions').classList.add('hidden');

            const market = document.getElementById('import-market').value;
            const importName = document.getElementById('import-name').value || `Import ${new Date().toISOString().split('T')[0]}`;

            // Transform data according to mapping
            const transformedData = parsedData.map(row => {
                const hit = { ...row, market_id: market };
                Object.keys(columnMapping).forEach(key => {
                    if (columnMapping[key]) {
                        hit[key] = row[columnMapping[key]];
                    }
                });
                // Excel date conversion
                if (hit.received_at && typeof hit.received_at === 'number') {
                    const date = new Date((hit.received_at - 25569) * 86400 * 1000);
                    hit.received_at = date.toISOString();
                }
                if (!hit.channel) hit.channel = 'email';
                return hit;
            });

            try {
                const response = await fetch('/.netlify/functions/import-queue', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        source: `excel:${document.getElementById('file-name').textContent}`,
                        market_id: market,
                        data: transformedData
                    })
                });

                const result = await response.json();

                if (!response.ok || result.error) {
                    throw new Error(result.error || 'Failed to submit to queue');
                }

                // Store queue ID
                currentQueueId = result.data?.id || result.id;

                // Update UI with validation results
                document.getElementById('queue-status').classList.add('hidden');
                document.getElementById('validation-results').classList.remove('hidden');
                document.getElementById('queue-actions').classList.remove('hidden');
                document.getElementById('queue-actions').classList.add('flex');

                document.getElementById('queue-id').textContent = currentQueueId;
                document.getElementById('queue-hits').textContent = result.data?.hit_count || transformedData.length;
                document.getElementById('queue-brands').textContent = result.data?.new_brands_count || 0;
                document.getElementById('queue-competitors').textContent = result.data?.new_competitors_count || 0;
                document.getElementById('queue-errors').textContent = result.data?.error_count || 0;

                // Show errors if any
                if (result.data?.error_details?.length > 0) {
                    document.getElementById('queue-errors-panel').classList.remove('hidden');
                    document.getElementById('queue-error-list').innerHTML = result.data.error_details
                        .slice(0, 10)
                        .map(e => `<li>${e}</li>`)
                        .join('');
                }

            } catch (error) {
                console.error('Queue submit error:', error);
                document.getElementById('queue-status').innerHTML = `
                    <div class="bg-red-50 rounded-lg p-6 text-center">
                        <i class="fa-solid fa-exclamation-triangle text-4xl text-red-400 mb-3"></i>
                        <p class="text-red-600 font-medium">Failed to submit to queue</p>
                        <p class="text-red-500 text-sm">${error.message}</p>
                        <button onclick="goToStep(3)" class="mt-4 px-4 py-2 bg-slate-200 rounded-lg text-slate-700">
                            Go Back
                        </button>
                    </div>
                `;
            }
        }

        async function approveImport() {
            if (!currentQueueId) {
                alert('No queue item to approve');
                return;
            }

            // Disable buttons and show progress
            document.getElementById('approve-btn').disabled = true;
            document.getElementById('reject-btn').disabled = true;
            document.getElementById('approve-progress').classList.remove('hidden');
            document.getElementById('approve-status').textContent = 'Approving and importing...';

            try {
                const response = await fetch(`/.netlify/functions/import-queue/${currentQueueId}/approve`, {
                    method: 'POST'
                });

                const result = await response.json();

                if (!response.ok || result.error) {
                    throw new Error(result.error || 'Failed to approve import');
                }

                // Success
                document.getElementById('approve-bar').style.width = '100%';
                document.getElementById('approve-percent').textContent = '100%';
                document.getElementById('approve-status').textContent = 'Import complete!';
                document.getElementById('approve-bar').classList.add('bg-green-500');

                document.getElementById('queue-item-status').textContent = 'approved';
                document.getElementById('queue-item-status').className = 'px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium';

                // Hide approve/reject, show done button
                document.getElementById('queue-actions').innerHTML = `
                    <button onclick="window.location.href='../dashboard.html'"
                        class="px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700">
                        <i class="fa-solid fa-check mr-2"></i>Done - Go to Dashboard
                    </button>
                `;

            } catch (error) {
                console.error('Approve error:', error);
                document.getElementById('approve-status').textContent = 'Error: ' + error.message;
                document.getElementById('approve-bar').classList.add('bg-red-500');
                document.getElementById('approve-btn').disabled = false;
                document.getElementById('reject-btn').disabled = false;
            }
        }

        async function rejectImport() {
            if (!currentQueueId) {
                alert('No queue item to reject');
                return;
            }

            if (!confirm('Are you sure you want to reject this import?')) return;

            try {
                const response = await fetch(`/.netlify/functions/import-queue/${currentQueueId}/reject`, {
                    method: 'POST'
                });

                const result = await response.json();

                if (!response.ok || result.error) {
                    throw new Error(result.error || 'Failed to reject import');
                }

                // Update status
                document.getElementById('queue-item-status').textContent = 'rejected';
                document.getElementById('queue-item-status').className = 'px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-medium';

                // Show done button
                document.getElementById('queue-actions').innerHTML = `
                    <button onclick="window.location.href='upload.html'"
                        class="px-6 py-3 bg-slate-600 text-white rounded-lg font-medium hover:bg-slate-700">
                        <i class="fa-solid fa-redo mr-2"></i>Start Over
                    </button>
                `;

            } catch (error) {
                console.error('Reject error:', error);
                alert('Failed to reject: ' + error.message);
            }
        }

        // ====================================
        // UTILITIES
        // ====================================
        function formatSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        }
    </script>
@endpush
