@extends('layouts.dashboard')


@section('title', 'D.2 Licensing Registry | CRMTracker')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
            color: #334155;
        }

        .modal-backdrop {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        .status-active {
            @apply bg-green-100 text-green-700;
        }

        .status-pending {
            @apply bg-amber-100 text-amber-700;
        }

        .status-expired {
            @apply bg-purple-100 text-purple-700;
        }

        .status-grey {
            @apply bg-orange-100 text-orange-700;
        }

        .status-unlicensed {
            @apply bg-red-100 text-red-700;
        }
</style>
@endpush

@section('content')
<main class="flex-1 ml-20 p-8 max-w-[1800px] mx-auto overflow-x-hidden">
<header class="flex justify-between items-center mb-6">
<div class="flex items-center gap-3">
<span
class="text-xs font-medium bg-purple-50 text-purple-700 px-2 py-1 rounded border border-purple-200">
Data Module D.2
</span>
<span id="market-count-badge"
class="text-xs font-medium bg-green-50 text-green-700 px-2 py-1 rounded border border-green-200">
<i class="fa-solid fa-spinner fa-spin text-xs mr-1"></i>Loading...
</span>
</div>
<button onclick="openAddModal()"
class="px-6 py-2.5 rounded-lg font-medium shadow-sm flex items-center gap-2 transition-colors bg-purple-600 hover:bg-purple-700 text-white">
<i class="fa-solid fa-plus"></i> Add License
</button>
</header>
<!-- Filters -->
<div class="bg-white rounded-xl border border-slate-200 p-4 mb-6">
<div class="flex flex-wrap gap-4 items-center">
<div class="flex-1 min-w-[200px]">
<input type="text" id="filter-search" placeholder="Search brand or license..."
class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
onkeyup="debounceLoad()">
</div>
<select id="filter-regulator" onchange="loadLicenses()"
class="px-4 py-2 border border-slate-200 rounded-lg">
<option value="">All Regulators</option>
</select>
<select id="filter-status" onchange="loadLicenses()"
class="px-4 py-2 border border-slate-200 rounded-lg">
<option value="">All Status</option>
<option value="active">Active</option>
<option value="pending">Pending</option>
<option value="expired">Expired</option>
<option value="grey">Grey Market</option>
<option value="unlicensed">Unlicensed</option>
</select>
<button onclick="loadLicenses()"
class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-slate-600">
<i class="fa-solid fa-refresh"></i>
</button>
</div>
</div>
<!-- Stats Cards -->
<div id="stats-cards" class="grid grid-cols-5 gap-4 mb-6">
<div class="bg-white rounded-xl border border-slate-200 p-4">
<div id="stat-total" class="text-2xl font-bold text-slate-800">-</div>
<div class="text-sm text-slate-500">Total Licenses</div>
</div>
<div class="bg-white rounded-xl border border-slate-200 p-4">
<div id="stat-active" class="text-2xl font-bold text-green-600">-</div>
<div class="text-sm text-slate-500">Active</div>
</div>
<div class="bg-white rounded-xl border border-slate-200 p-4">
<div id="stat-pending" class="text-2xl font-bold text-amber-600">-</div>
<div class="text-sm text-slate-500">Pending</div>
</div>
<div class="bg-white rounded-xl border border-slate-200 p-4">
<div id="stat-expired" class="text-2xl font-bold text-purple-600">-</div>
<div class="text-sm text-slate-500">Expired</div>
</div>
<div class="bg-white rounded-xl border border-slate-200 p-4">
<div id="stat-grey" class="text-2xl font-bold text-red-600">-</div>
<div class="text-sm text-slate-500">Grey/Unlicensed</div>
</div>
</div>
<!-- Licenses Table -->
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
<table class="w-full text-sm">
<thead class="bg-slate-50 border-b border-slate-200">
<tr>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Brand</th>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Market</th>
<th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Status</th>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Regulator
</th>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">License #
</th>
<th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Expiry</th>
<th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Actions
</th>
</tr>
</thead>
<tbody id="licenses-table-body">
<tr>
<td colspan="7" class="py-8 text-center text-slate-400">
<i class="fa-solid fa-spinner fa-spin mr-2"></i>Loading licenses...
</td>
</tr>
</tbody>
</table>
</div>
</main>
</div>
<!-- Add/Edit Modal -->
<div id="license-modal" class="fixed inset-0 modal-backdrop hidden z-50 flex items-center justify-center">
<div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4">
<div class="flex items-center justify-between p-4 border-b border-slate-200">
<h3 id="modal-title" class="text-lg font-semibold">Add License</h3>
<button onclick="closeModal()" class="text-slate-400 hover:text-slate-600">
<i class="fa-solid fa-xmark text-xl"></i>
</button>
</div>
<form id="license-form" onsubmit="saveLicense(event)" class="p-4 space-y-4">
<input type="hidden" id="license-id">
<div>
<label class="block text-sm font-medium text-slate-700 mb-1">Brand *</label>
<select id="license-brand" required class="w-full px-4 py-2 border border-slate-200 rounded-lg">
<option value="">Select brand...</option>
</select>
</div>
<div class="grid grid-cols-2 gap-4">
<div>
<label class="block text-sm font-medium text-slate-700 mb-1">Regulator</label>
<select id="license-regulator" class="w-full px-4 py-2 border border-slate-200 rounded-lg">
<option value="">None (Grey/Unlicensed)</option>
</select>
</div>
<div>
<label class="block text-sm font-medium text-slate-700 mb-1">Status *</label>
<select id="license-status" required
class="w-full px-4 py-2 border border-slate-200 rounded-lg">
<option value="active">Active</option>
<option value="pending">Pending</option>
<option value="expired">Expired</option>
<option value="grey">Grey Market</option>
<option value="unlicensed">Unlicensed</option>
</select>
</div>
</div>
<div class="grid grid-cols-2 gap-4">
<div>
<label class="block text-sm font-medium text-slate-700 mb-1">License Number</label>
<input type="text" id="license-number"
class="w-full px-4 py-2 border border-slate-200 rounded-lg" placeholder="e.g. iGO-2022-001">
</div>
<div>
<label class="block text-sm font-medium text-slate-700 mb-1">License Type</label>
<select id="license-type" class="w-full px-4 py-2 border border-slate-200 rounded-lg">
<option value="">Not specified</option>
<option value="combined">Combined (Casino + Sports)</option>
<option value="casino">Casino Only</option>
<option value="betting">Betting Only</option>
<option value="software">Software Provider</option>
</select>
</div>
</div>
<div class="grid grid-cols-2 gap-4">
<div>
<label class="block text-sm font-medium text-slate-700 mb-1">Issued Date</label>
<input type="date" id="license-issued"
class="w-full px-4 py-2 border border-slate-200 rounded-lg">
</div>
<div>
<label class="block text-sm font-medium text-slate-700 mb-1">Expiry Date</label>
<input type="date" id="license-expiry"
class="w-full px-4 py-2 border border-slate-200 rounded-lg">
</div>
</div>
<div>
<label class="block text-sm font-medium text-slate-700 mb-1">Verification URL</label>
<input type="url" id="license-url" class="w-full px-4 py-2 border border-slate-200 rounded-lg"
placeholder="https://regulator.com/verify/...">
</div>
<div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
<button type="button" onclick="closeModal()"
class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg">
Cancel
</button>
<button type="submit"
class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium">
<i class="fa-solid fa-save mr-1"></i> Save
</button>
</div>
</form>
</div>
</div>
<script>
let licenses = [];
let brands = [];
let regulators = [];
let debounceTimer = null;
// Initialize on load
document.addEventListener('DOMContentLoaded', async () => {
await Promise.all([loadBrands(), loadRegulators()]);
loadLicenses();
});
// Debounce search input
function debounceLoad() {
clearTimeout(debounceTimer);
debounceTimer = setTimeout(loadLicenses, 300);
}
// Load licenses from API
async function loadLicenses() {
const search = document.getElementById('filter-search').value;
const regulator = document.getElementById('filter-regulator').value;
const status = document.getElementById('filter-status').value;
let url = '/.netlify/functions/licenses?type=licenses.list';
if (search) url += `&search=${encodeURIComponent(search)}`;
if (regulator) url += `&regulator_id=${regulator}`;
if (status) url += `&status=${status}`;
try {
const res = await fetch(url);
const json = await res.json();
licenses = json.data || [];
const stats = json.stats || {};
// Update stats
document.getElementById('stat-total').textContent = stats.total || 0;
document.getElementById('stat-active').textContent = stats.active || 0;
document.getElementById('stat-pending').textContent = stats.pending || 0;
document.getElementById('stat-expired').textContent = stats.expired || 0;
document.getElementById('stat-grey').textContent = stats.grey || 0;
document.getElementById('market-count-badge').innerHTML = `<i class="fa-solid fa-check text-xs mr-1"></i>${stats.total || 0} Licenses`;
renderTable();
} catch (e) {
console.error('Failed to load licenses:', e);
document.getElementById('licenses-table-body').innerHTML = `
<tr><td colspan="7" class="py-8 text-center text-red-500">
<i class="fa-solid fa-triangle-exclamation mr-2"></i>Failed to load licenses
</td></tr>`;
}
}
// Load brands for dropdown
async function loadBrands() {
try {
const res = await fetch('/.netlify/functions/brands?type=brands.list');
const json = await res.json();
brands = json.data || [];
const select = document.getElementById('license-brand');
select.innerHTML = '<option value="">Select brand...</option>' +
brands.map(b => `<option value="${b.id}">${b.name}</option>`).join('');
} catch (e) {
console.error('Failed to load brands:', e);
}
}
// Load regulators for dropdown
async function loadRegulators() {
try {
const res = await fetch('/.netlify/functions/licenses?type=regulators.list');
const json = await res.json();
regulators = json.data || [];
// Populate filter dropdown
const filterSelect = document.getElementById('filter-regulator');
filterSelect.innerHTML = '<option value="">All Regulators</option>' +
regulators.map(r => `<option value="${r.id}">${r.abbreviation || r.name}</option>`).join('');
// Populate form dropdown
const formSelect = document.getElementById('license-regulator');
formSelect.innerHTML = '<option value="">None (Grey/Unlicensed)</option>' +
regulators.map(r => `<option value="${r.id}">${r.abbreviation} - ${r.name}</option>`).join('');
} catch (e) {
console.error('Failed to load regulators:', e);
}
}
// Render licenses table
function renderTable() {
const tbody = document.getElementById('licenses-table-body');
if (licenses.length === 0) {
tbody.innerHTML = `<tr><td colspan="7" class="py-8 text-center text-slate-400">No licenses found</td></tr>`;
return;
}
tbody.innerHTML = licenses.map(lic => {
const statusClass = `status-${lic.status || 'unknown'}`;
const statusLabel = (lic.status || 'Unknown').charAt(0).toUpperCase() + (lic.status || '').slice(1);
const expiry = lic.expiry_date ? new Date(lic.expiry_date).toLocaleDateString('en-US', { month: 'short', year: 'numeric' }) : '-';
return `
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4 font-medium">${lic.brand_name || '-'}</td>
<td class="py-3 px-4 text-slate-600">${lic.market_id || '-'}</td>
<td class="py-3 px-4 text-center">
<span class="${statusClass} px-2 py-1 rounded text-xs font-medium">${statusLabel}</span>
</td>
<td class="py-3 px-4">${lic.regulator_abbr || '-'}</td>
<td class="py-3 px-4 font-mono text-xs">${lic.license_number || '-'}</td>
<td class="py-3 px-4 text-center text-slate-600">${expiry}</td>
<td class="py-3 px-4 text-center">
<button onclick="editLicense('${lic.id}')" class="text-blue-500 hover:text-blue-700 px-2" title="Edit">
<i class="fa-solid fa-pen"></i>
</button>
${lic.verification_url ? `
<a href="${lic.verification_url}" target="_blank" class="text-green-500 hover:text-green-700 px-2" title="Verify">
<i class="fa-solid fa-external-link"></i>
</a>` : ''}
<button onclick="deleteLicense('${lic.id}')" class="text-red-500 hover:text-red-700 px-2" title="Delete">
<i class="fa-solid fa-trash"></i>
</button>
</td>
</tr>`;
}).join('');
}
// Open add modal
function openAddModal() {
document.getElementById('modal-title').textContent = 'Add License';
document.getElementById('license-form').reset();
document.getElementById('license-id').value = '';
document.getElementById('license-modal').classList.remove('hidden');
}
// Edit license
function editLicense(id) {
const lic = licenses.find(l => l.id === id);
if (!lic) return;
document.getElementById('modal-title').textContent = 'Edit License';
document.getElementById('license-id').value = lic.id;
document.getElementById('license-brand').value = lic.brand_id || '';
document.getElementById('license-regulator').value = lic.regulator_id || '';
document.getElementById('license-status').value = lic.status || 'active';
document.getElementById('license-number').value = lic.license_number || '';
document.getElementById('license-type').value = lic.license_type || '';
document.getElementById('license-issued').value = lic.issued_date ? lic.issued_date.split('T')[0] : '';
document.getElementById('license-expiry').value = lic.expiry_date ? lic.expiry_date.split('T')[0] : '';
document.getElementById('license-url').value = lic.verification_url || '';
document.getElementById('license-modal').classList.remove('hidden');
}
// Close modal
function closeModal() {
document.getElementById('license-modal').classList.add('hidden');
}
// Save license (create or update)
async function saveLicense(event) {
event.preventDefault();
const id = document.getElementById('license-id').value;
const data = {
brand_id: document.getElementById('license-brand').value,
regulator_id: document.getElementById('license-regulator').value || null,
status: document.getElementById('license-status').value,
license_number: document.getElementById('license-number').value || null,
license_type: document.getElementById('license-type').value || null,
issued_date: document.getElementById('license-issued').value || null,
expiry_date: document.getElementById('license-expiry').value || null,
verification_url: document.getElementById('license-url').value || null
};
const url = id
? `/.netlify/functions/licenses?type=licenses.update&id=${id}`
: '/.netlify/functions/licenses?type=licenses.create';
try {
const res = await fetch(url, {
method: 'POST',
headers: { 'Content-Type': 'application/json' },
body: JSON.stringify(data)
});
const json = await res.json();
if (json.error) {
alert('Error: ' + json.error);
return;
}
closeModal();
loadLicenses();
} catch (e) {
console.error('Failed to save license:', e);
alert('Failed to save license');
}
}
// Delete license
async function deleteLicense(id) {
if (!confirm('Are you sure you want to delete this license?')) return;
try {
const res = await fetch(`/.netlify/functions/licenses?type=licenses.delete&id=${id}`, {
method: 'POST'
});
const json = await res.json();
if (json.error) {
alert('Error: ' + json.error);
return;
}
loadLicenses();
} catch (e) {
console.error('Failed to delete license:', e);
alert('Failed to delete license');
}
}
</script>
@endsection

@push('page-scripts')
<script>
        let licenses = [];
        let brands = [];
        let regulators = [];
        let debounceTimer = null;

        // Initialize on load
        document.addEventListener('DOMContentLoaded', async () => {
            await Promise.all([loadBrands(), loadRegulators()]);
            loadLicenses();
        });

        // Debounce search input
        function debounceLoad() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(loadLicenses, 300);
        }

        // Load licenses from API
        async function loadLicenses() {
            const search = document.getElementById('filter-search').value;
            const regulator = document.getElementById('filter-regulator').value;
            const status = document.getElementById('filter-status').value;

            let url = '/.netlify/functions/licenses?type=licenses.list';
            if (search) url += `&search=${encodeURIComponent(search)}`;
            if (regulator) url += `&regulator_id=${regulator}`;
            if (status) url += `&status=${status}`;

            try {
                const res = await fetch(url);
                const json = await res.json();
                licenses = json.data || [];
                const stats = json.stats || {};

                // Update stats
                document.getElementById('stat-total').textContent = stats.total || 0;
                document.getElementById('stat-active').textContent = stats.active || 0;
                document.getElementById('stat-pending').textContent = stats.pending || 0;
                document.getElementById('stat-expired').textContent = stats.expired || 0;
                document.getElementById('stat-grey').textContent = stats.grey || 0;
                document.getElementById('market-count-badge').innerHTML = `<i class="fa-solid fa-check text-xs mr-1"></i>${stats.total || 0} Licenses`;

                renderTable();
            } catch (e) {
                console.error('Failed to load licenses:', e);
                document.getElementById('licenses-table-body').innerHTML = `
                    <tr><td colspan="7" class="py-8 text-center text-red-500">
                        <i class="fa-solid fa-triangle-exclamation mr-2"></i>Failed to load licenses
                    </td></tr>`;
            }
        }

        // Load brands for dropdown
        async function loadBrands() {
            try {
                const res = await fetch('/.netlify/functions/brands?type=brands.list');
                const json = await res.json();
                brands = json.data || [];
                const select = document.getElementById('license-brand');
                select.innerHTML = '<option value="">Select brand...</option>' +
                    brands.map(b => `<option value="${b.id}">${b.name}</option>`).join('');
            } catch (e) {
                console.error('Failed to load brands:', e);
            }
        }

        // Load regulators for dropdown
        async function loadRegulators() {
            try {
                const res = await fetch('/.netlify/functions/licenses?type=regulators.list');
                const json = await res.json();
                regulators = json.data || [];

                // Populate filter dropdown
                const filterSelect = document.getElementById('filter-regulator');
                filterSelect.innerHTML = '<option value="">All Regulators</option>' +
                    regulators.map(r => `<option value="${r.id}">${r.abbreviation || r.name}</option>`).join('');

                // Populate form dropdown
                const formSelect = document.getElementById('license-regulator');
                formSelect.innerHTML = '<option value="">None (Grey/Unlicensed)</option>' +
                    regulators.map(r => `<option value="${r.id}">${r.abbreviation} - ${r.name}</option>`).join('');
            } catch (e) {
                console.error('Failed to load regulators:', e);
            }
        }

        // Render licenses table
        function renderTable() {
            const tbody = document.getElementById('licenses-table-body');

            if (licenses.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" class="py-8 text-center text-slate-400">No licenses found</td></tr>`;
                return;
            }

            tbody.innerHTML = licenses.map(lic => {
                const statusClass = `status-${lic.status || 'unknown'}`;
                const statusLabel = (lic.status || 'Unknown').charAt(0).toUpperCase() + (lic.status || '').slice(1);
                const expiry = lic.expiry_date ? new Date(lic.expiry_date).toLocaleDateString('en-US', { month: 'short', year: 'numeric' }) : '-';

                return `
                <tr class="border-b border-slate-100 hover:bg-slate-50">
                    <td class="py-3 px-4 font-medium">${lic.brand_name || '-'}</td>
                    <td class="py-3 px-4 text-slate-600">${lic.market_id || '-'}</td>
                    <td class="py-3 px-4 text-center">
                        <span class="${statusClass} px-2 py-1 rounded text-xs font-medium">${statusLabel}</span>
                    </td>
                    <td class="py-3 px-4">${lic.regulator_abbr || '-'}</td>
                    <td class="py-3 px-4 font-mono text-xs">${lic.license_number || '-'}</td>
                    <td class="py-3 px-4 text-center text-slate-600">${expiry}</td>
                    <td class="py-3 px-4 text-center">
                        <button onclick="editLicense('${lic.id}')" class="text-blue-500 hover:text-blue-700 px-2" title="Edit">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        ${lic.verification_url ? `
                        <a href="${lic.verification_url}" target="_blank" class="text-green-500 hover:text-green-700 px-2" title="Verify">
                            <i class="fa-solid fa-external-link"></i>
                        </a>` : ''}
                        <button onclick="deleteLicense('${lic.id}')" class="text-red-500 hover:text-red-700 px-2" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
            }).join('');
        }

        // Open add modal
        function openAddModal() {
            document.getElementById('modal-title').textContent = 'Add License';
            document.getElementById('license-form').reset();
            document.getElementById('license-id').value = '';
            document.getElementById('license-modal').classList.remove('hidden');
        }

        // Edit license
        function editLicense(id) {
            const lic = licenses.find(l => l.id === id);
            if (!lic) return;

            document.getElementById('modal-title').textContent = 'Edit License';
            document.getElementById('license-id').value = lic.id;
            document.getElementById('license-brand').value = lic.brand_id || '';
            document.getElementById('license-regulator').value = lic.regulator_id || '';
            document.getElementById('license-status').value = lic.status || 'active';
            document.getElementById('license-number').value = lic.license_number || '';
            document.getElementById('license-type').value = lic.license_type || '';
            document.getElementById('license-issued').value = lic.issued_date ? lic.issued_date.split('T')[0] : '';
            document.getElementById('license-expiry').value = lic.expiry_date ? lic.expiry_date.split('T')[0] : '';
            document.getElementById('license-url').value = lic.verification_url || '';

            document.getElementById('license-modal').classList.remove('hidden');
        }

        // Close modal
        function closeModal() {
            document.getElementById('license-modal').classList.add('hidden');
        }

        // Save license (create or update)
        async function saveLicense(event) {
            event.preventDefault();

            const id = document.getElementById('license-id').value;
            const data = {
                brand_id: document.getElementById('license-brand').value,
                regulator_id: document.getElementById('license-regulator').value || null,
                status: document.getElementById('license-status').value,
                license_number: document.getElementById('license-number').value || null,
                license_type: document.getElementById('license-type').value || null,
                issued_date: document.getElementById('license-issued').value || null,
                expiry_date: document.getElementById('license-expiry').value || null,
                verification_url: document.getElementById('license-url').value || null
            };

            const url = id
                ? `/.netlify/functions/licenses?type=licenses.update&id=${id}`
                : '/.netlify/functions/licenses?type=licenses.create';

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const json = await res.json();

                if (json.error) {
                    alert('Error: ' + json.error);
                    return;
                }

                closeModal();
                loadLicenses();
            } catch (e) {
                console.error('Failed to save license:', e);
                alert('Failed to save license');
            }
        }

        // Delete license
        async function deleteLicense(id) {
            if (!confirm('Are you sure you want to delete this license?')) return;

            try {
                const res = await fetch(`/.netlify/functions/licenses?type=licenses.delete&id=${id}`, {
                    method: 'POST'
                });
                const json = await res.json();

                if (json.error) {
                    alert('Error: ' + json.error);
                    return;
                }

                loadLicenses();
            } catch (e) {
                console.error('Failed to delete license:', e);
                alert('Failed to delete license');
            }
        }
    </script>
@endpush
