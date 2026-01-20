@extends('layouts.dashboard')


@section('title', 'A.5 User Management | CRMTracker®')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
        }

        .tab-btn.active {
            color: #6366f1;
            border-bottom: 2px solid #6366f1;
        }
</style>
@endpush

@section('content')
<main class="ml-72 p-8 max-w-[1400px] mx-auto">
<!-- Header -->
<div class="bg-gradient-to-r from-red-600 to-red-800 rounded-2xl p-8 mb-8 text-white shadow-xl">
<div class="flex items-start justify-between">
<div>
<div class="flex items-center gap-3 mb-2">
<span class="text-xs bg-red-500/30 text-red-100 px-2 py-1 rounded font-medium">Admin A.5</span>
</div>
<h2 class="text-3xl font-bold">User Management</h2>
<p class="text-red-100 max-w-xl mt-2">Manage users, roles, and view system audit logs.</p>
</div>
<button onclick="openModal('create')"
class="px-4 py-2 bg-white text-red-700 rounded-lg font-medium hover:bg-red-50 transition-colors flex items-center gap-2">
<i class="fa-solid fa-user-plus"></i> Add User
</button>
</div>
</div>
<!-- Tabs -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 mb-6">
<div class="flex border-b border-slate-200">
<button onclick="switchTab('users')" id="tab-users"
class="tab-btn active px-6 py-4 font-medium text-slate-600 hover:text-indigo-600 transition-colors">
<i class="fa-solid fa-users mr-2"></i>Users
</button>
<button onclick="switchTab('roles')" id="tab-roles"
class="tab-btn px-6 py-4 font-medium text-slate-600 hover:text-indigo-600 transition-colors">
<i class="fa-solid fa-shield-halved mr-2"></i>Roles & Permissions
</button>
<button onclick="switchTab('audit')" id="tab-audit"
class="tab-btn px-6 py-4 font-medium text-slate-600 hover:text-indigo-600 transition-colors">
<i class="fa-solid fa-clock-rotate-left mr-2"></i>Audit Log
</button>
</div>
<!-- Users Tab -->
<div id="panel-users" class="p-6">
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-50">
<tr>
<th class="text-left py-3 px-4 font-semibold text-slate-600">User</th>
<th class="text-left py-3 px-4 font-semibold text-slate-600">Email</th>
<th class="text-center py-3 px-4 font-semibold text-slate-600">Role</th>
<th class="text-center py-3 px-4 font-semibold text-slate-600">Status</th>
<th class="text-center py-3 px-4 font-semibold text-slate-600">Last Login</th>
<th class="text-center py-3 px-4 font-semibold text-slate-600">Actions</th>
</tr>
</thead>
<tbody id="users-tbody">
<tr>
<td colspan="6" class="text-center py-8 text-slate-400">
<i class="fa-solid fa-spinner fa-spin text-2xl mb-2"></i>
<p>Loading users...</p>
</td>
</tr>
</tbody>
</table>
</div>
</div>
<!-- Roles Tab -->
<div id="panel-roles" class="p-6 hidden">
<div class="mb-4">
<p class="text-sm text-slate-500">Role hierarchy determines what actions users can perform. Higher
levels inherit lower level permissions.</p>
</div>
<div class="grid grid-cols-4 gap-4" id="roles-grid">
<!-- Dynamically populated -->
</div>
<div class="mt-6">
<h4 class="font-semibold text-slate-700 mb-3">Permission Matrix</h4>
<table class="w-full text-sm border border-slate-200 rounded-lg overflow-hidden">
<thead class="bg-slate-100">
<tr>
<th class="text-left py-2 px-3 font-medium text-slate-600">Resource</th>
<th class="text-center py-2 px-3 font-medium text-slate-600">Viewer</th>
<th class="text-center py-2 px-3 font-medium text-slate-600">Analyst</th>
<th class="text-center py-2 px-3 font-medium text-slate-600">Editor</th>
<th class="text-center py-2 px-3 font-medium text-slate-600">Admin</th>
</tr>
</thead>
<tbody id="permissions-tbody">
<!-- Dynamically populated -->
</tbody>
</table>
</div>
</div>
<!-- Audit Tab -->
<div id="panel-audit" class="p-6 hidden">
<div class="flex items-center gap-4 mb-4">
<input type="text" id="audit-search" placeholder="Search by user or action..."
class="flex-1 px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
<select id="audit-filter" class="px-4 py-2 border border-slate-200 rounded-lg">
<option value="">All Actions</option>
<option value="login">Login</option>
<option value="create">Create</option>
<option value="update">Update</option>
<option value="delete">Delete</option>
</select>
</div>
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-50">
<tr>
<th class="text-left py-3 px-4 font-semibold text-slate-600">User</th>
<th class="text-left py-3 px-4 font-semibold text-slate-600">Action</th>
<th class="text-left py-3 px-4 font-semibold text-slate-600">Resource</th>
<th class="text-left py-3 px-4 font-semibold text-slate-600">Details</th>
<th class="text-left py-3 px-4 font-semibold text-slate-600">Timestamp</th>
</tr>
</thead>
<tbody id="audit-tbody">
<tr>
<td colspan="5" class="text-center py-8 text-slate-400">Loading audit log...</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</main>
<!-- User Modal -->
<div id="user-modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
<div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
<div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
<h3 id="modal-title" class="text-lg font-bold text-slate-800">Add User</h3>
<button onclick="closeModal()" class="text-slate-400 hover:text-slate-600">
<i class="fa-solid fa-times text-lg"></i>
</button>
</div>
<form id="user-form" onsubmit="saveUser(event)" class="p-6">
<input type="hidden" id="user-id">
<div class="mb-4">
<label class="block text-sm font-medium text-slate-700 mb-1">Name</label>
<input type="text" id="user-name" required
class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
</div>
<div class="mb-4">
<label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
<input type="email" id="user-email" required
class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
</div>
<div class="mb-4">
<label class="block text-sm font-medium text-slate-700 mb-1">Role</label>
<select id="user-role" class="w-full px-3 py-2 border border-slate-200 rounded-lg">
<option value="viewer">Viewer</option>
<option value="analyst">Analyst</option>
<option value="editor">Editor</option>
<option value="admin">Admin</option>
</select>
</div>
<div class="mb-4">
<label class="block text-sm font-medium text-slate-700 mb-1">
Password <span class="text-slate-400 font-normal">(leave blank to keep current)</span>
</label>
<input type="password" id="user-password" minlength="8"
class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500"
placeholder="Minimum 8 characters">
</div>
<div class="mb-6">
<label class="flex items-center gap-2 cursor-pointer">
<input type="checkbox" id="user-active" checked
class="w-4 h-4 text-indigo-600 border-slate-300 rounded">
<span class="text-sm text-slate-700">Active account</span>
</label>
</div>
<div class="flex gap-3 justify-end">
<button type="button" onclick="closeModal()"
class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg hover:bg-slate-50">
Cancel
</button>
<button type="submit"
class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
Save User
</button>
</div>
</form>
</div>
</div>
<!-- Password Modal -->
<div id="password-modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
<div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
<div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
<h3 class="text-lg font-bold text-slate-800">Update Password</h3>
<button onclick="closePasswordModal()" class="text-slate-400 hover:text-slate-600">
<i class="fa-solid fa-times text-lg"></i>
</button>
</div>
<form onsubmit="updatePassword(event)" class="p-6">
<input type="hidden" id="password-user-id">
<div class="mb-4">
<p class="text-sm text-slate-600">Setting new password for: <strong
id="password-user-email"></strong></p>
</div>
<div class="mb-4">
<label class="block text-sm font-medium text-slate-700 mb-1">New Password</label>
<input type="password" id="new-password" required minlength="8"
class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500"
placeholder="Minimum 8 characters">
</div>
<div class="mb-6">
<label class="block text-sm font-medium text-slate-700 mb-1">Confirm Password</label>
<input type="password" id="confirm-password" required minlength="8"
class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500"
placeholder="Confirm new password">
</div>
<div class="flex gap-3 justify-end">
<button type="button" onclick="closePasswordModal()"
class="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg hover:bg-slate-50">
Cancel
</button>
<button type="submit"
class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium">
Update Password
</button>
</div>
</form>
</div>
</div>
<script>
const API_BASE = '/.netlify/functions/auth';
let currentUser = null;
// Check authentication
async function checkAuth() {
const token = localStorage.getItem('crmt_session');
if (!token) {
window.location.href = 'login.html?redirect=' + encodeURIComponent(window.location.pathname);
return false;
}
try {
const res = await fetch(`${API_BASE}?action=me`, {
headers: { 'Authorization': `Bearer ${token}` }
});
if (!res.ok) {
localStorage.removeItem('crmt_session');
window.location.href = 'login.html?redirect=' + encodeURIComponent(window.location.pathname);
return false;
}
const data = await res.json();
currentUser = data.user;
// Admin-only page
if (currentUser.role !== 'admin') {
alert('Access denied. Admin role required.');
window.location.href = '../dashboard.html';
return false;
}
return true;
} catch (e) {
console.error('Auth check failed:', e);
return false;
}
}
// Load users
async function loadUsers() {
const token = localStorage.getItem('crmt_session');
try {
const res = await fetch(`${API_BASE}?action=users`, {
headers: { 'Authorization': `Bearer ${token}` }
});
const data = await res.json();
renderUsers(data.users || []);
} catch (e) {
console.error('Load users failed:', e);
}
}
function renderUsers(users) {
const tbody = document.getElementById('users-tbody');
if (users.length === 0) {
tbody.innerHTML = '<tr><td colspan="6" class="text-center py-8 text-slate-400">No users found</td></tr>';
return;
}
tbody.innerHTML = users.map(u => {
const roleColors = {
admin: 'bg-red-100 text-red-700',
editor: 'bg-blue-100 text-blue-700',
analyst: 'bg-green-100 text-green-700',
viewer: 'bg-slate-100 text-slate-600'
};
const roleColor = roleColors[u.role] || roleColors.viewer;
const initials = (u.name || u.email || '?')[0].toUpperCase();
const lastLogin = u.last_login_at ? new Date(u.last_login_at).toLocaleDateString() : 'Never';
const safeEmail = (u.email || '').replace(/'/g, "\\'");
return `
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4">
<div class="flex items-center gap-3">
<div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
${initials}
</div>
<span class="font-medium text-slate-800">${u.name || '-'}</span>
</div>
</td>
<td class="py-3 px-4 text-slate-600">${u.email}</td>
<td class="py-3 px-4 text-center">
<span class="px-2 py-1 rounded-full text-xs font-medium ${roleColor}">${u.role}</span>
</td>
<td class="py-3 px-4 text-center">
${u.is_active
? '<span class="text-green-600"><i class="fa-solid fa-check-circle"></i> Active</span>'
: '<span class="text-slate-400"><i class="fa-solid fa-minus-circle"></i> Inactive</span>'}
</td>
<td class="py-3 px-4 text-center text-slate-500">${lastLogin}</td>
<td class="py-3 px-4 text-center">
<button onclick="editUser('${u.id}')" class="text-indigo-600 hover:text-indigo-800 mr-2" title="Edit">
<i class="fa-solid fa-pen-to-square"></i>
</button>
<button onclick="openPasswordModal('${u.id}', '${safeEmail}')" class="text-purple-600 hover:text-purple-800 mr-2" title="Update Password">
<i class="fa-solid fa-key"></i>
</button>
<button onclick="toggleUserStatus('${u.id}', ${u.is_active})" 
class="${u.is_active ? 'text-amber-600 hover:text-amber-800' : 'text-green-600 hover:text-green-800'} mr-2" 
title="${u.is_active ? 'Deactivate' : 'Activate'}">
<i class="fa-solid ${u.is_active ? 'fa-user-slash' : 'fa-user-check'}"></i>
</button>
<button onclick="deleteUser('${u.id}', '${safeEmail}')" class="text-red-600 hover:text-red-800" title="Delete">
<i class="fa-solid fa-trash"></i>
</button>
</td>
</tr>
`;
}).join('');
}
// Load roles
async function loadRoles() {
const token = localStorage.getItem('crmt_session');
try {
const res = await fetch(`${API_BASE}?action=roles`, {
headers: { 'Authorization': `Bearer ${token}` }
});
const data = await res.json();
renderRoles(data.roles || []);
} catch (e) {
console.error('Load roles failed:', e);
}
}
function renderRoles(roles) {
const grid = document.getElementById('roles-grid');
const colors = ['bg-slate-100', 'bg-green-100', 'bg-blue-100', 'bg-red-100'];
grid.innerHTML = roles.map((r, i) => `
<div class="${colors[i % colors.length]} rounded-xl p-4">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-shield text-slate-600"></i>
<span class="font-bold text-slate-700 capitalize">${r.name}</span>
</div>
<p class="text-sm text-slate-600">${r.description || 'No description'}</p>
<p class="text-xs text-slate-400 mt-2">Level: ${r.level}</p>
</div>
`).join('');
// Render permission matrix
renderPermissionMatrix();
}
function renderPermissionMatrix() {
const resources = [
{ name: 'Reports', perms: ['view', 'export'] },
{ name: 'Competitors', perms: ['view', 'edit', 'create', 'delete'] },
{ name: 'Hits/Data', perms: ['view', 'import', 'delete'] },
{ name: 'Users', perms: ['view', 'edit', 'create', 'delete'] },
{ name: 'Settings', perms: ['view', 'edit'] }
];
const levels = { viewer: 10, analyst: 20, editor: 30, admin: 100 };
const requiredLevels = {
'Reports.view': 10, 'Reports.export': 20,
'Competitors.view': 10, 'Competitors.edit': 30, 'Competitors.create': 30, 'Competitors.delete': 100,
'Hits/Data.view': 10, 'Hits/Data.import': 30, 'Hits/Data.delete': 100,
'Users.view': 100, 'Users.edit': 100, 'Users.create': 100, 'Users.delete': 100,
'Settings.view': 30, 'Settings.edit': 100
};
const tbody = document.getElementById('permissions-tbody');
tbody.innerHTML = resources.map(r => {
const check = (role, perm) => {
const required = requiredLevels[`${r.name}.${perm}`] || 100;
return levels[role] >= required
? '<i class="fa-solid fa-check text-green-600"></i>'
: '<i class="fa-solid fa-times text-slate-300"></i>';
};
return `
<tr class="border-b border-slate-100">
<td class="py-2 px-3 font-medium text-slate-700">${r.name}</td>
<td class="py-2 px-3 text-center">${r.perms.map(p => check('viewer', p)).join(' ')}</td>
<td class="py-2 px-3 text-center">${r.perms.map(p => check('analyst', p)).join(' ')}</td>
<td class="py-2 px-3 text-center">${r.perms.map(p => check('editor', p)).join(' ')}</td>
<td class="py-2 px-3 text-center">${r.perms.map(p => check('admin', p)).join(' ')}</td>
</tr>
`;
}).join('');
}
// Load audit log
async function loadAuditLog() {
const token = localStorage.getItem('crmt_session');
try {
const res = await fetch('/.netlify/functions/audit', {
headers: { 'Authorization': `Bearer ${token}` }
});
if (res.ok) {
const data = await res.json();
renderAuditLog(data.logs || []);
} else {
// API might not exist yet, show placeholder
document.getElementById('audit-tbody').innerHTML =
'<tr><td colspan="5" class="text-center py-8 text-slate-400">Audit log API coming soon</td></tr>';
}
} catch (e) {
console.error('Load audit failed:', e);
document.getElementById('audit-tbody').innerHTML =
'<tr><td colspan="5" class="text-center py-8 text-slate-400">Failed to load audit log</td></tr>';
}
}
function renderAuditLog(logs) {
const tbody = document.getElementById('audit-tbody');
if (logs.length === 0) {
tbody.innerHTML = '<tr><td colspan="5" class="text-center py-8 text-slate-400">No audit records</td></tr>';
return;
}
tbody.innerHTML = logs.map(l => `
<tr class="border-b border-slate-100">
<td class="py-2 px-4 text-slate-700">${l.user_email || 'System'}</td>
<td class="py-2 px-4 text-slate-600">${l.action}</td>
<td class="py-2 px-4 text-slate-600">${l.resource}</td>
<td class="py-2 px-4 text-slate-500 text-xs">${JSON.stringify(l.changes || {}).substring(0, 50)}</td>
<td class="py-2 px-4 text-slate-400 text-xs">${new Date(l.created_at).toLocaleString()}</td>
</tr>
`).join('');
}
// Tab switching
function switchTab(tab) {
document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
document.getElementById(`tab-${tab}`).classList.add('active');
document.getElementById('panel-users').classList.toggle('hidden', tab !== 'users');
document.getElementById('panel-roles').classList.toggle('hidden', tab !== 'roles');
document.getElementById('panel-audit').classList.toggle('hidden', tab !== 'audit');
if (tab === 'roles') loadRoles();
if (tab === 'audit') loadAuditLog();
}
// Modal functions
function openModal(mode, userId = null) {
const modal = document.getElementById('user-modal');
const title = document.getElementById('modal-title');
document.getElementById('user-form').reset();
document.getElementById('user-id').value = userId || '';
title.textContent = mode === 'create' ? 'Add User' : 'Edit User';
modal.classList.remove('hidden');
modal.classList.add('flex');
}
function closeModal() {
document.getElementById('user-modal').classList.add('hidden');
document.getElementById('user-modal').classList.remove('flex');
}
async function saveUser(e) {
e.preventDefault();
const token = localStorage.getItem('crmt_session');
const userId = document.getElementById('user-id').value;
const email = document.getElementById('user-email').value;
const name = document.getElementById('user-name').value;
const role = document.getElementById('user-role').value;
const password = document.getElementById('user-password').value;
try {
if (userId) {
// Update role
await fetch(API_BASE, {
method: 'POST',
headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
body: JSON.stringify({ action: 'update_role', user_id: userId, role })
});
// Set password if provided
if (password) {
await fetch(API_BASE, {
method: 'POST',
headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
body: JSON.stringify({ action: 'set_password', user_id: userId, password })
});
}
} else {
// Create user
const res = await fetch(API_BASE, {
method: 'POST',
headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
body: JSON.stringify({ action: 'register', email, name, role })
});
if (!res.ok) {
const data = await res.json();
throw new Error(data.error || 'Failed to create user');
}
const userData = await res.json();
// Set password if provided
if (password && userData.user) {
await fetch(API_BASE, {
method: 'POST',
headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
body: JSON.stringify({ action: 'set_password', user_id: userData.user.id, password })
});
}
}
closeModal();
loadUsers();
} catch (e) {
alert('Error: ' + e.message);
}
}
function editUser(userId) {
// Would need to fetch user details first
openModal('edit', userId);
}
async function toggleUserStatus(userId, isActive) {
if (!confirm(`${isActive ? 'Deactivate' : 'Activate'} this user?`)) return;
const token = localStorage.getItem('crmt_session');
const actionType = isActive ? 'deactivate' : 'activate';
try {
const res = await fetch(API_BASE, {
method: 'POST',
headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
body: JSON.stringify({ action: actionType, user_id: userId })
});
if (!res.ok) {
const data = await res.json();
throw new Error(data.error || `Failed to ${actionType} user`);
}
loadUsers();
} catch (e) {
alert('Error: ' + e.message);
}
}
// Delete user
async function deleteUser(userId, userEmail) {
if (currentUser && currentUser.id === userId) {
alert('You cannot delete your own account.');
return;
}
if (!confirm(`Are you sure you want to permanently delete the user "${userEmail}"? This action cannot be undone.`)) return;
const token = localStorage.getItem('crmt_session');
try {
const res = await fetch(API_BASE, {
method: 'POST',
headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
body: JSON.stringify({ action: 'delete', user_id: userId })
});
if (!res.ok) {
const data = await res.json();
throw new Error(data.error || 'Failed to delete user');
}
loadUsers();
} catch (e) {
alert('Error: ' + e.message);
}
}
// Password Modal functions
function openPasswordModal(userId, userEmail) {
document.getElementById('password-user-id').value = userId;
document.getElementById('password-user-email').textContent = userEmail;
document.getElementById('new-password').value = '';
document.getElementById('confirm-password').value = '';
const modal = document.getElementById('password-modal');
modal.classList.remove('hidden');
modal.classList.add('flex');
}
function closePasswordModal() {
document.getElementById('password-modal').classList.add('hidden');
document.getElementById('password-modal').classList.remove('flex');
}
async function updatePassword(e) {
e.preventDefault();
const userId = document.getElementById('password-user-id').value;
const newPassword = document.getElementById('new-password').value;
const confirmPassword = document.getElementById('confirm-password').value;
if (newPassword !== confirmPassword) {
alert('Passwords do not match.');
return;
}
if (newPassword.length < 8) {
alert('Password must be at least 8 characters.');
return;
}
const token = localStorage.getItem('crmt_session');
try {
const res = await fetch(API_BASE, {
method: 'POST',
headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
body: JSON.stringify({ action: 'set_password', user_id: userId, password: newPassword })
});
if (!res.ok) {
const data = await res.json();
throw new Error(data.error || 'Failed to update password');
}
alert('Password updated successfully.');
closePasswordModal();
} catch (e) {
alert('Error: ' + e.message);
}
}
// Initialize
document.addEventListener('DOMContentLoaded', async () => {
if (await checkAuth()) {
loadUsers();
}
});
</script>
@endsection

@push('page-scripts')
<script>
        const API_BASE = '/.netlify/functions/auth';
        let currentUser = null;

        // Check authentication
        async function checkAuth() {
            const token = localStorage.getItem('crmt_session');
            if (!token) {
                window.location.href = 'login.html?redirect=' + encodeURIComponent(window.location.pathname);
                return false;
            }

            try {
                const res = await fetch(`${API_BASE}?action=me`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                if (!res.ok) {
                    localStorage.removeItem('crmt_session');
                    window.location.href = 'login.html?redirect=' + encodeURIComponent(window.location.pathname);
                    return false;
                }

                const data = await res.json();
                currentUser = data.user;

                // Admin-only page
                if (currentUser.role !== 'admin') {
                    alert('Access denied. Admin role required.');
                    window.location.href = '../dashboard.html';
                    return false;
                }

                return true;
            } catch (e) {
                console.error('Auth check failed:', e);
                return false;
            }
        }

        // Load users
        async function loadUsers() {
            const token = localStorage.getItem('crmt_session');
            try {
                const res = await fetch(`${API_BASE}?action=users`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const data = await res.json();
                renderUsers(data.users || []);
            } catch (e) {
                console.error('Load users failed:', e);
            }
        }

        function renderUsers(users) {
            const tbody = document.getElementById('users-tbody');
            if (users.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-8 text-slate-400">No users found</td></tr>';
                return;
            }

            tbody.innerHTML = users.map(u => {
                const roleColors = {
                    admin: 'bg-red-100 text-red-700',
                    editor: 'bg-blue-100 text-blue-700',
                    analyst: 'bg-green-100 text-green-700',
                    viewer: 'bg-slate-100 text-slate-600'
                };
                const roleColor = roleColors[u.role] || roleColors.viewer;
                const initials = (u.name || u.email || '?')[0].toUpperCase();
                const lastLogin = u.last_login_at ? new Date(u.last_login_at).toLocaleDateString() : 'Never';
                const safeEmail = (u.email || '').replace(/'/g, "\\'");

                return `
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                    ${initials}
                                </div>
                                <span class="font-medium text-slate-800">${u.name || '-'}</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-slate-600">${u.email}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="px-2 py-1 rounded-full text-xs font-medium ${roleColor}">${u.role}</span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            ${u.is_active
                        ? '<span class="text-green-600"><i class="fa-solid fa-check-circle"></i> Active</span>'
                        : '<span class="text-slate-400"><i class="fa-solid fa-minus-circle"></i> Inactive</span>'}
                        </td>
                        <td class="py-3 px-4 text-center text-slate-500">${lastLogin}</td>
                        <td class="py-3 px-4 text-center">
                            <button onclick="editUser('${u.id}')" class="text-indigo-600 hover:text-indigo-800 mr-2" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button onclick="openPasswordModal('${u.id}', '${safeEmail}')" class="text-purple-600 hover:text-purple-800 mr-2" title="Update Password">
                                <i class="fa-solid fa-key"></i>
                            </button>
                            <button onclick="toggleUserStatus('${u.id}', ${u.is_active})" 
                                class="${u.is_active ? 'text-amber-600 hover:text-amber-800' : 'text-green-600 hover:text-green-800'} mr-2" 
                                title="${u.is_active ? 'Deactivate' : 'Activate'}">
                                <i class="fa-solid ${u.is_active ? 'fa-user-slash' : 'fa-user-check'}"></i>
                            </button>
                            <button onclick="deleteUser('${u.id}', '${safeEmail}')" class="text-red-600 hover:text-red-800" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Load roles
        async function loadRoles() {
            const token = localStorage.getItem('crmt_session');
            try {
                const res = await fetch(`${API_BASE}?action=roles`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const data = await res.json();
                renderRoles(data.roles || []);
            } catch (e) {
                console.error('Load roles failed:', e);
            }
        }

        function renderRoles(roles) {
            const grid = document.getElementById('roles-grid');
            const colors = ['bg-slate-100', 'bg-green-100', 'bg-blue-100', 'bg-red-100'];

            grid.innerHTML = roles.map((r, i) => `
                <div class="${colors[i % colors.length]} rounded-xl p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fa-solid fa-shield text-slate-600"></i>
                        <span class="font-bold text-slate-700 capitalize">${r.name}</span>
                    </div>
                    <p class="text-sm text-slate-600">${r.description || 'No description'}</p>
                    <p class="text-xs text-slate-400 mt-2">Level: ${r.level}</p>
                </div>
            `).join('');

            // Render permission matrix
            renderPermissionMatrix();
        }

        function renderPermissionMatrix() {
            const resources = [
                { name: 'Reports', perms: ['view', 'export'] },
                { name: 'Competitors', perms: ['view', 'edit', 'create', 'delete'] },
                { name: 'Hits/Data', perms: ['view', 'import', 'delete'] },
                { name: 'Users', perms: ['view', 'edit', 'create', 'delete'] },
                { name: 'Settings', perms: ['view', 'edit'] }
            ];

            const levels = { viewer: 10, analyst: 20, editor: 30, admin: 100 };
            const requiredLevels = {
                'Reports.view': 10, 'Reports.export': 20,
                'Competitors.view': 10, 'Competitors.edit': 30, 'Competitors.create': 30, 'Competitors.delete': 100,
                'Hits/Data.view': 10, 'Hits/Data.import': 30, 'Hits/Data.delete': 100,
                'Users.view': 100, 'Users.edit': 100, 'Users.create': 100, 'Users.delete': 100,
                'Settings.view': 30, 'Settings.edit': 100
            };

            const tbody = document.getElementById('permissions-tbody');
            tbody.innerHTML = resources.map(r => {
                const check = (role, perm) => {
                    const required = requiredLevels[`${r.name}.${perm}`] || 100;
                    return levels[role] >= required
                        ? '<i class="fa-solid fa-check text-green-600"></i>'
                        : '<i class="fa-solid fa-times text-slate-300"></i>';
                };

                return `
                    <tr class="border-b border-slate-100">
                        <td class="py-2 px-3 font-medium text-slate-700">${r.name}</td>
                        <td class="py-2 px-3 text-center">${r.perms.map(p => check('viewer', p)).join(' ')}</td>
                        <td class="py-2 px-3 text-center">${r.perms.map(p => check('analyst', p)).join(' ')}</td>
                        <td class="py-2 px-3 text-center">${r.perms.map(p => check('editor', p)).join(' ')}</td>
                        <td class="py-2 px-3 text-center">${r.perms.map(p => check('admin', p)).join(' ')}</td>
                    </tr>
                `;
            }).join('');
        }

        // Load audit log
        async function loadAuditLog() {
            const token = localStorage.getItem('crmt_session');
            try {
                const res = await fetch('/.netlify/functions/audit', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                if (res.ok) {
                    const data = await res.json();
                    renderAuditLog(data.logs || []);
                } else {
                    // API might not exist yet, show placeholder
                    document.getElementById('audit-tbody').innerHTML =
                        '<tr><td colspan="5" class="text-center py-8 text-slate-400">Audit log API coming soon</td></tr>';
                }
            } catch (e) {
                console.error('Load audit failed:', e);
                document.getElementById('audit-tbody').innerHTML =
                    '<tr><td colspan="5" class="text-center py-8 text-slate-400">Failed to load audit log</td></tr>';
            }
        }

        function renderAuditLog(logs) {
            const tbody = document.getElementById('audit-tbody');
            if (logs.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center py-8 text-slate-400">No audit records</td></tr>';
                return;
            }

            tbody.innerHTML = logs.map(l => `
                <tr class="border-b border-slate-100">
                    <td class="py-2 px-4 text-slate-700">${l.user_email || 'System'}</td>
                    <td class="py-2 px-4 text-slate-600">${l.action}</td>
                    <td class="py-2 px-4 text-slate-600">${l.resource}</td>
                    <td class="py-2 px-4 text-slate-500 text-xs">${JSON.stringify(l.changes || {}).substring(0, 50)}</td>
                    <td class="py-2 px-4 text-slate-400 text-xs">${new Date(l.created_at).toLocaleString()}</td>
                </tr>
            `).join('');
        }

        // Tab switching
        function switchTab(tab) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.getElementById(`tab-${tab}`).classList.add('active');

            document.getElementById('panel-users').classList.toggle('hidden', tab !== 'users');
            document.getElementById('panel-roles').classList.toggle('hidden', tab !== 'roles');
            document.getElementById('panel-audit').classList.toggle('hidden', tab !== 'audit');

            if (tab === 'roles') loadRoles();
            if (tab === 'audit') loadAuditLog();
        }

        // Modal functions
        function openModal(mode, userId = null) {
            const modal = document.getElementById('user-modal');
            const title = document.getElementById('modal-title');

            document.getElementById('user-form').reset();
            document.getElementById('user-id').value = userId || '';

            title.textContent = mode === 'create' ? 'Add User' : 'Edit User';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            document.getElementById('user-modal').classList.add('hidden');
            document.getElementById('user-modal').classList.remove('flex');
        }

        async function saveUser(e) {
            e.preventDefault();
            const token = localStorage.getItem('crmt_session');
            const userId = document.getElementById('user-id').value;
            const email = document.getElementById('user-email').value;
            const name = document.getElementById('user-name').value;
            const role = document.getElementById('user-role').value;
            const password = document.getElementById('user-password').value;

            try {
                if (userId) {
                    // Update role
                    await fetch(API_BASE, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
                        body: JSON.stringify({ action: 'update_role', user_id: userId, role })
                    });

                    // Set password if provided
                    if (password) {
                        await fetch(API_BASE, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
                            body: JSON.stringify({ action: 'set_password', user_id: userId, password })
                        });
                    }
                } else {
                    // Create user
                    const res = await fetch(API_BASE, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
                        body: JSON.stringify({ action: 'register', email, name, role })
                    });

                    if (!res.ok) {
                        const data = await res.json();
                        throw new Error(data.error || 'Failed to create user');
                    }

                    const userData = await res.json();

                    // Set password if provided
                    if (password && userData.user) {
                        await fetch(API_BASE, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
                            body: JSON.stringify({ action: 'set_password', user_id: userData.user.id, password })
                        });
                    }
                }

                closeModal();
                loadUsers();
            } catch (e) {
                alert('Error: ' + e.message);
            }
        }

        function editUser(userId) {
            // Would need to fetch user details first
            openModal('edit', userId);
        }

        async function toggleUserStatus(userId, isActive) {
            if (!confirm(`${isActive ? 'Deactivate' : 'Activate'} this user?`)) return;

            const token = localStorage.getItem('crmt_session');
            const actionType = isActive ? 'deactivate' : 'activate';
            try {
                const res = await fetch(API_BASE, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
                    body: JSON.stringify({ action: actionType, user_id: userId })
                });

                if (!res.ok) {
                    const data = await res.json();
                    throw new Error(data.error || `Failed to ${actionType} user`);
                }

                loadUsers();
            } catch (e) {
                alert('Error: ' + e.message);
            }
        }

        // Delete user
        async function deleteUser(userId, userEmail) {
            if (currentUser && currentUser.id === userId) {
                alert('You cannot delete your own account.');
                return;
            }

            if (!confirm(`Are you sure you want to permanently delete the user "${userEmail}"? This action cannot be undone.`)) return;

            const token = localStorage.getItem('crmt_session');
            try {
                const res = await fetch(API_BASE, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
                    body: JSON.stringify({ action: 'delete', user_id: userId })
                });

                if (!res.ok) {
                    const data = await res.json();
                    throw new Error(data.error || 'Failed to delete user');
                }

                loadUsers();
            } catch (e) {
                alert('Error: ' + e.message);
            }
        }

        // Password Modal functions
        function openPasswordModal(userId, userEmail) {
            document.getElementById('password-user-id').value = userId;
            document.getElementById('password-user-email').textContent = userEmail;
            document.getElementById('new-password').value = '';
            document.getElementById('confirm-password').value = '';

            const modal = document.getElementById('password-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closePasswordModal() {
            document.getElementById('password-modal').classList.add('hidden');
            document.getElementById('password-modal').classList.remove('flex');
        }

        async function updatePassword(e) {
            e.preventDefault();
            const userId = document.getElementById('password-user-id').value;
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            if (newPassword !== confirmPassword) {
                alert('Passwords do not match.');
                return;
            }

            if (newPassword.length < 8) {
                alert('Password must be at least 8 characters.');
                return;
            }

            const token = localStorage.getItem('crmt_session');
            try {
                const res = await fetch(API_BASE, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
                    body: JSON.stringify({ action: 'set_password', user_id: userId, password: newPassword })
                });

                if (!res.ok) {
                    const data = await res.json();
                    throw new Error(data.error || 'Failed to update password');
                }

                alert('Password updated successfully.');
                closePasswordModal();
            } catch (e) {
                alert('Error: ' + e.message);
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', async () => {
            if (await checkAuth()) {
                loadUsers();
            }
        });
    </script>
@endpush
