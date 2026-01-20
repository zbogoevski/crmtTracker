@extends('layouts.dashboard')


@section('title', 'CRMTracker® Documentation')

@push('styles')
<style>
body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-hero {
            background: linear-gradient(135deg, #1E3A5F 0%, #4F46E5 50%, #7C3AED 100%);
        }

        .doc-nav-item {
            cursor: pointer;
            transition: all 0.2s;
        }

        .doc-nav-item:hover {
            background: #F1F5F9;
        }

        .doc-nav-item.active {
            background: #EEF2FF;
            border-left: 3px solid #4F46E5;
        }

        /* Markdown styling */
        .markdown-content h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #1E293B;
            border-bottom: 2px solid #E2E8F0;
            padding-bottom: 0.5rem;
        }

        .markdown-content h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-top: 2rem;
            margin-bottom: 0.75rem;
            color: #334155;
        }

        .markdown-content h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
            color: #475569;
        }

        .markdown-content p {
            margin-bottom: 1rem;
            line-height: 1.7;
            color: #475569;
        }

        .markdown-content ul,
        .markdown-content ol {
            margin-bottom: 1rem;
            padding-left: 1.5rem;
        }

        .markdown-content li {
            margin-bottom: 0.5rem;
            color: #475569;
        }

        .markdown-content table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }

        .markdown-content th {
            background: #F1F5F9;
            padding: 0.75rem 1rem;
            text-align: left;
            font-weight: 600;
            border: 1px solid #E2E8F0;
        }

        .markdown-content td {
            padding: 0.75rem 1rem;
            border: 1px solid #E2E8F0;
        }

        .markdown-content code {
            background: #F1F5F9;
            padding: 0.125rem 0.375rem;
            border-radius: 4px;
            font-size: 0.9em;
            color: #7C3AED;
        }

        .markdown-content pre {
            background: #1E293B;
            color: #E2E8F0;
            padding: 1rem;
            border-radius: 8px;
            overflow-x: auto;
            margin-bottom: 1.5rem;
        }

        .markdown-content pre code {
            background: transparent;
            color: inherit;
            padding: 0;
        }

        .markdown-content blockquote {
            border-left: 4px solid #4F46E5;
            padding-left: 1rem;
            margin: 1rem 0;
            color: #64748B;
            font-style: italic;
        }

        .markdown-content hr {
            border: none;
            border-top: 1px solid #E2E8F0;
            margin: 2rem 0;
        }

        /* Collapsible feature cards */
        .markdown-content details {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            margin-bottom: 0.75rem;
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .markdown-content details:hover {
            border-color: #CBD5E1;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .markdown-content details[open] {
            background: #FFFFFF;
            border-color: #4F46E5;
        }

        .markdown-content details summary {
            padding: 0.875rem 1rem;
            cursor: pointer;
            list-style: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            color: #334155;
            user-select: none;
        }

        .markdown-content details summary::-webkit-details-marker {
            display: none;
        }

        .markdown-content details summary::before {
            content: '▶';
            font-size: 0.7rem;
            color: #94A3B8;
            transition: transform 0.2s ease;
        }

        .markdown-content details[open] summary::before {
            transform: rotate(90deg);
            color: #4F46E5;
        }

        .markdown-content details summary:hover {
            background: #F1F5F9;
        }

        .markdown-content details> :not(summary) {
            padding: 0 1rem 1rem 1rem;
        }

        .markdown-content details table {
            font-size: 0.875rem;
            margin-bottom: 0;
        }

        .markdown-content details th {
            background: #F1F5F9;
            padding: 0.5rem 0.75rem;
        }

        .markdown-content details td {
            padding: 0.5rem 0.75rem;
        }

        /* Status icon buttons in subtasks */
        .markdown-content details td button {
            width: 2rem;
            text-align: center;
            padding: 0.25rem;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .ml-72 {
                margin-left: 0 !important;
            }
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
<h2 class="text-3xl font-bold">Documentation Hub</h2>
<span class="text-xs bg-white/20 text-white px-2 py-1 rounded-full">
<i class="fa-solid fa-book-open mr-1"></i>A.8
</span>
</div>
<p class="text-slate-200 max-w-xl">Complete guides for users, admins, and developers.</p>
</div>
<div class="flex gap-3 no-print">
<button onclick="window.print()" class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg text-sm">
<i class="fa-solid fa-print mr-2"></i>Print
</button>
</div>
</div>
</div>
<!-- Content Layout -->
<div class="flex gap-6">
<!-- Doc Navigation Sidebar -->
<div class="w-64 flex-shrink-0 no-print">
<div class="bg-white rounded-xl shadow-sm border border-slate-200 sticky top-8">
<div class="p-4 border-b border-slate-100">
<h3 class="font-bold text-slate-800">Documentation</h3>
</div>
<div class="p-2">
<div class="doc-nav-item active rounded-lg p-3" onclick="loadDoc('user-guide')">
<div class="flex items-center gap-3">
<i class="fa-solid fa-user text-blue-600"></i>
<span class="font-medium text-slate-700">User Guide</span>
</div>
</div>
<div class="doc-nav-item rounded-lg p-3" onclick="loadDoc('admin-guide')">
<div class="flex items-center gap-3">
<i class="fa-solid fa-user-shield text-red-600"></i>
<span class="font-medium text-slate-700">Admin Guide</span>
</div>
</div>
<div class="doc-nav-item rounded-lg p-3" onclick="loadDoc('developer-guide')">
<div class="flex items-center gap-3">
<i class="fa-solid fa-code text-green-600"></i>
<span class="font-medium text-slate-700">Developer Guide</span>
</div>
</div>
<div class="doc-nav-item rounded-lg p-3" onclick="loadDoc('data-dictionary')">
<div class="flex items-center gap-3">
<i class="fa-solid fa-database text-purple-600"></i>
<span class="font-medium text-slate-700">Data Dictionary</span>
</div>
</div>
<div class="doc-nav-item rounded-lg p-3" onclick="loadDoc('compliance')">
<div class="flex items-center gap-3">
<i class="fa-solid fa-shield text-amber-600"></i>
<span class="font-medium text-slate-700">Compliance Pack</span>
</div>
</div>
<div class="doc-nav-item rounded-lg p-3" onclick="loadDoc('roadmap')">
<div class="flex items-center gap-3">
<i class="fa-solid fa-road text-indigo-600"></i>
<span class="font-medium text-slate-700">Product Roadmap</span>
</div>
</div>
<div class="doc-nav-item rounded-lg p-3" onclick="loadDoc('demo')">
<div class="flex items-center gap-3">
<i class="fa-solid fa-flask text-emerald-600"></i>
<span class="font-medium text-slate-700">Demo Exhibition</span>
</div>
</div>
<div class="doc-nav-item rounded-lg p-3" onclick="loadDoc('laravel')">
<div class="flex items-center gap-3">
<i class="fa-brands fa-laravel text-rose-600"></i>
<span class="font-medium text-slate-700">Laravel Migration</span>
</div>
</div>
</div>
</div>
</div>
<!-- Doc Content Area -->
<div class="flex-1">
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
<div id="doc-content" class="markdown-content">
<div class="flex items-center justify-center h-64">
<div class="text-center">
<i class="fa-solid fa-spinner fa-spin text-4xl text-slate-300 mb-4"></i>
<p class="text-slate-500">Loading documentation...</p>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="text-center text-sm text-slate-400 py-6 border-t border-slate-100 mt-8">
<p>CRMTracker® v3.60 | Last updated: January 12, 2026</p>
</div>
</main>
<script>
// Document paths and descriptions
const DOCS = {
'user-guide': {
path: '../docs/user-guide/README.md',
title: 'User Guide',
icon: 'fa-user',
color: 'blue',
desc: 'Learn how to use CRMTracker® for competitive intelligence analysis. Covers dashboard, modules M.1-M.7, and data views.'
},
'admin-guide': {
path: '../docs/admin-guide/README.md',
title: 'Admin Guide',
icon: 'fa-user-shield',
color: 'red',
desc: 'Configuration and management for administrators. User roles, weight settings, pipeline management, and data governance.'
},
'developer-guide': {
path: '../docs/developer-guide/README.md',
title: 'Developer Guide',
icon: 'fa-code',
color: 'green',
desc: 'API reference and integration guide. REST endpoints, authentication, and data schemas.'
},
'data-dictionary': {
path: '../docs/data-dictionary/README.md',
title: 'Data Dictionary',
icon: 'fa-database',
color: 'purple',
desc: 'Complete field definitions and data model documentation. Entity schemas, field types, and relationships.'
},
'compliance': {
path: '../docs/compliance/README.md',
title: 'Compliance Pack',
icon: 'fa-shield',
color: 'amber',
desc: 'Data lineage, audit trail, and regulatory compliance documentation for governance requirements.'
},
'roadmap': {
path: '../docs/roadmap/README.md',
title: 'Product Roadmap',
icon: 'fa-road',
color: 'indigo',
desc: 'Track all identified gaps across modules M.1-M.6. Progress, priorities, and development status for each feature.'
},
'demo': {
path: '../docs/demo/README.md',
title: 'Demo Exhibition Strategy',
icon: 'fa-flask',
color: 'emerald',
desc: 'Strategic overview of the High-Fidelity Exhibition suite, including density tuning and pattern calibrations.'
},
'laravel': {
path: '../docs/laravel/README.md',
title: 'Laravel Migration',
icon: 'fa-laravel',
color: 'rose',
desc: 'Phased migration strategy from Node.js/HTML to Laravel 11. Core patterns, architecture, and developer hand-off.'
}
};
let currentDoc = null;
// Status icons and their cycle order
const STATUS_ICONS = ['⚪', '🟡', '🔴', '✅'];
const STATUS_LABELS = {
'⚪': 'Not Started',
'🟡': 'In Progress',
'🔴': 'Blocked',
'✅': 'Complete'
};
// Load saved roadmap statuses from localStorage
function getRoadmapStatuses() {
try {
return JSON.parse(localStorage.getItem('roadmap_statuses') || '{}');
} catch {
return {};
}
}
// Save roadmap statuses to localStorage
function saveRoadmapStatuses(statuses) {
localStorage.setItem('roadmap_statuses', JSON.stringify(statuses));
}
// Cycle to next status and save
function cycleStatus(gapId, currentIcon) {
const currentIndex = STATUS_ICONS.indexOf(currentIcon);
const nextIndex = (currentIndex + 1) % STATUS_ICONS.length;
const newIcon = STATUS_ICONS[nextIndex];
// Save to localStorage
const statuses = getRoadmapStatuses();
statuses[gapId] = newIcon;
saveRoadmapStatuses(statuses);
// Update the button
const btn = document.querySelector(`[data-gap-id="${gapId}"]`);
if (btn) {
btn.textContent = newIcon;
btn.title = `${STATUS_LABELS[newIcon]} (click to change)`;
}
// Show toast notification
showStatusToast(gapId, newIcon);
}
// Show toast notification
function showStatusToast(gapId, newIcon) {
const toast = document.createElement('div');
toast.className = 'fixed bottom-4 right-4 bg-slate-800 text-white px-4 py-2 rounded-lg shadow-lg z-50 flex items-center gap-2';
toast.innerHTML = `<span class="text-lg">${newIcon}</span> Gap #${gapId} → ${STATUS_LABELS[newIcon]}`;
document.body.appendChild(toast);
setTimeout(() => toast.remove(), 2000);
}
// Make status icons interactive after roadmap loads
function enhanceRoadmapTable() {
const contentEl = document.getElementById('doc-content');
const savedStatuses = getRoadmapStatuses();
// Find all table cells containing status icons
const allCells = contentEl.querySelectorAll('td');
allCells.forEach(cell => {
const text = cell.textContent.trim();
// Check if this cell contains only a status icon
if (STATUS_ICONS.includes(text)) {
// Get the row to find identifier
const row = cell.closest('tr');
const firstCell = row?.querySelector('td');
let itemId = firstCell?.textContent.trim();
// For numbered rows (main tables)
if (itemId && !isNaN(parseInt(itemId))) {
const savedStatus = savedStatuses[itemId] || text;
cell.innerHTML = `<button 
data-gap-id="${itemId}" 
onclick="cycleStatus('${itemId}', this.textContent.trim())"
class="text-xl cursor-pointer hover:scale-125 transition-transform px-2 py-1 rounded hover:bg-slate-100"
title="${STATUS_LABELS[savedStatus]} (click to change)"
>${savedStatus}</button>`;
}
// For subtask rows inside details (use subtask name as ID)
else if (itemId && itemId.length > 0) {
// Create a unique ID from subtask name
const subtaskId = 'sub_' + itemId.replace(/[^a-zA-Z0-9]/g, '_').substring(0, 30);
const savedStatus = savedStatuses[subtaskId] || text;
cell.innerHTML = `<button 
data-gap-id="${subtaskId}" 
onclick="cycleStatus('${subtaskId}', this.textContent.trim())"
class="text-lg cursor-pointer hover:scale-110 transition-transform px-1 rounded hover:bg-slate-200"
title="${STATUS_LABELS[savedStatus]} (click to change)"
>${savedStatus}</button>`;
}
}
});
}
// Show landing page
function showLandingPage() {
const contentEl = document.getElementById('doc-content');
document.querySelectorAll('.doc-nav-item').forEach(el => el.classList.remove('active'));
window.location.hash = '';
currentDoc = null;
contentEl.innerHTML = `
<h1>Welcome to CRMTracker® Documentation</h1>
<p>Select a guide below to get started, or use the navigation on the left.</p>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
${Object.entries(DOCS).map(([id, doc]) => `
<div class="border border-slate-200 rounded-xl p-6 hover:border-${doc.color}-300 hover:shadow-md transition cursor-pointer" onclick="loadDoc('${id}')">
<div class="flex items-center gap-3 mb-3">
<div class="w-10 h-10 rounded-lg bg-${doc.color}-100 flex items-center justify-center">
<i class="fa-solid ${doc.icon} text-${doc.color}-600"></i>
</div>
<h3 class="font-semibold text-slate-800">${doc.title}</h3>
</div>
<p class="text-slate-600 text-sm">${doc.desc}</p>
</div>
`).join('')}
</div>
<hr class="my-8">
<h2>Quick Links</h2>
<ul>
<li><strong>New users:</strong> Start with the <a href="#user-guide" class="text-indigo-600 hover:underline" onclick="loadDoc('user-guide')">User Guide</a></li>
<li><strong>System admins:</strong> Check the <a href="#admin-guide" class="text-indigo-600 hover:underline" onclick="loadDoc('admin-guide')">Admin Guide</a> for configuration</li>
<li><strong>Integrations:</strong> See the <a href="#developer-guide" class="text-indigo-600 hover:underline" onclick="loadDoc('developer-guide')">Developer Guide</a> for API docs</li>
<li><strong>Auditors:</strong> Review the <a href="#compliance" class="text-indigo-600 hover:underline" onclick="loadDoc('compliance')">Compliance Pack</a></li>
</ul>
`;
}
// Load documentation
async function loadDoc(docId) {
const contentEl = document.getElementById('doc-content');
const doc = DOCS[docId];
if (!doc) {
showLandingPage();
return;
}
// Update nav - find the matching nav item by doc ID
document.querySelectorAll('.doc-nav-item').forEach((el, idx) => {
el.classList.remove('active');
if (Object.keys(DOCS)[idx] === docId) {
el.classList.add('active');
}
});
// Show loading
contentEl.innerHTML = `
<div class="flex items-center justify-center h-64">
<div class="text-center">
<i class="fa-solid fa-spinner fa-spin text-4xl text-slate-300 mb-4"></i>
<p class="text-slate-500">Loading ${doc.title}...</p>
</div>
</div>
`;
try {
const response = await fetch(doc.path);
if (!response.ok) throw new Error('Failed to load');
const markdown = await response.text();
contentEl.innerHTML = marked.parse(markdown);
currentDoc = docId;
// Update URL hash
window.location.hash = docId;
// If roadmap, make status icons interactive
if (docId === 'roadmap') {
enhanceRoadmapTable();
}
} catch (error) {
contentEl.innerHTML = `
<div class="flex items-center justify-center h-64">
<div class="text-center">
<i class="fa-solid fa-exclamation-triangle text-4xl text-amber-500 mb-4"></i>
<p class="text-slate-700 font-medium">Failed to load ${doc.title}</p>
<p class="text-slate-500 text-sm mt-2">${error.message}</p>
<button onclick="showLandingPage()" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Back to Home</button>
</div>
</div>
`;
}
}
// Initialize
document.addEventListener('DOMContentLoaded', () => {
// Check URL hash
const hash = window.location.hash.slice(1);
if (hash && DOCS[hash]) {
loadDoc(hash);
} else {
// Show landing page by default
showLandingPage();
}
});
</script>
@endsection

@push('page-scripts')
<script>
        // Document paths and descriptions
        const DOCS = {
            'user-guide': {
                path: '../docs/user-guide/README.md',
                title: 'User Guide',
                icon: 'fa-user',
                color: 'blue',
                desc: 'Learn how to use CRMTracker® for competitive intelligence analysis. Covers dashboard, modules M.1-M.7, and data views.'
            },
            'admin-guide': {
                path: '../docs/admin-guide/README.md',
                title: 'Admin Guide',
                icon: 'fa-user-shield',
                color: 'red',
                desc: 'Configuration and management for administrators. User roles, weight settings, pipeline management, and data governance.'
            },
            'developer-guide': {
                path: '../docs/developer-guide/README.md',
                title: 'Developer Guide',
                icon: 'fa-code',
                color: 'green',
                desc: 'API reference and integration guide. REST endpoints, authentication, and data schemas.'
            },
            'data-dictionary': {
                path: '../docs/data-dictionary/README.md',
                title: 'Data Dictionary',
                icon: 'fa-database',
                color: 'purple',
                desc: 'Complete field definitions and data model documentation. Entity schemas, field types, and relationships.'
            },
            'compliance': {
                path: '../docs/compliance/README.md',
                title: 'Compliance Pack',
                icon: 'fa-shield',
                color: 'amber',
                desc: 'Data lineage, audit trail, and regulatory compliance documentation for governance requirements.'
            },
            'roadmap': {
                path: '../docs/roadmap/README.md',
                title: 'Product Roadmap',
                icon: 'fa-road',
                color: 'indigo',
                desc: 'Track all identified gaps across modules M.1-M.6. Progress, priorities, and development status for each feature.'
            },
            'demo': {
                path: '../docs/demo/README.md',
                title: 'Demo Exhibition Strategy',
                icon: 'fa-flask',
                color: 'emerald',
                desc: 'Strategic overview of the High-Fidelity Exhibition suite, including density tuning and pattern calibrations.'
            },
            'laravel': {
                path: '../docs/laravel/README.md',
                title: 'Laravel Migration',
                icon: 'fa-laravel',
                color: 'rose',
                desc: 'Phased migration strategy from Node.js/HTML to Laravel 11. Core patterns, architecture, and developer hand-off.'
            }
        };

        let currentDoc = null;

        // Status icons and their cycle order
        const STATUS_ICONS = ['⚪', '🟡', '🔴', '✅'];
        const STATUS_LABELS = {
            '⚪': 'Not Started',
            '🟡': 'In Progress',
            '🔴': 'Blocked',
            '✅': 'Complete'
        };

        // Load saved roadmap statuses from localStorage
        function getRoadmapStatuses() {
            try {
                return JSON.parse(localStorage.getItem('roadmap_statuses') || '{}');
            } catch {
                return {};
            }
        }

        // Save roadmap statuses to localStorage
        function saveRoadmapStatuses(statuses) {
            localStorage.setItem('roadmap_statuses', JSON.stringify(statuses));
        }

        // Cycle to next status and save
        function cycleStatus(gapId, currentIcon) {
            const currentIndex = STATUS_ICONS.indexOf(currentIcon);
            const nextIndex = (currentIndex + 1) % STATUS_ICONS.length;
            const newIcon = STATUS_ICONS[nextIndex];

            // Save to localStorage
            const statuses = getRoadmapStatuses();
            statuses[gapId] = newIcon;
            saveRoadmapStatuses(statuses);

            // Update the button
            const btn = document.querySelector(`[data-gap-id="${gapId}"]`);
            if (btn) {
                btn.textContent = newIcon;
                btn.title = `${STATUS_LABELS[newIcon]} (click to change)`;
            }

            // Show toast notification
            showStatusToast(gapId, newIcon);
        }

        // Show toast notification
        function showStatusToast(gapId, newIcon) {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 bg-slate-800 text-white px-4 py-2 rounded-lg shadow-lg z-50 flex items-center gap-2';
            toast.innerHTML = `<span class="text-lg">${newIcon}</span> Gap #${gapId} → ${STATUS_LABELS[newIcon]}`;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2000);
        }

        // Make status icons interactive after roadmap loads
        function enhanceRoadmapTable() {
            const contentEl = document.getElementById('doc-content');
            const savedStatuses = getRoadmapStatuses();

            // Find all table cells containing status icons
            const allCells = contentEl.querySelectorAll('td');
            allCells.forEach(cell => {
                const text = cell.textContent.trim();
                // Check if this cell contains only a status icon
                if (STATUS_ICONS.includes(text)) {
                    // Get the row to find identifier
                    const row = cell.closest('tr');
                    const firstCell = row?.querySelector('td');
                    let itemId = firstCell?.textContent.trim();

                    // For numbered rows (main tables)
                    if (itemId && !isNaN(parseInt(itemId))) {
                        const savedStatus = savedStatuses[itemId] || text;
                        cell.innerHTML = `<button 
                            data-gap-id="${itemId}" 
                            onclick="cycleStatus('${itemId}', this.textContent.trim())"
                            class="text-xl cursor-pointer hover:scale-125 transition-transform px-2 py-1 rounded hover:bg-slate-100"
                            title="${STATUS_LABELS[savedStatus]} (click to change)"
                        >${savedStatus}</button>`;
                    }
                    // For subtask rows inside details (use subtask name as ID)
                    else if (itemId && itemId.length > 0) {
                        // Create a unique ID from subtask name
                        const subtaskId = 'sub_' + itemId.replace(/[^a-zA-Z0-9]/g, '_').substring(0, 30);
                        const savedStatus = savedStatuses[subtaskId] || text;
                        cell.innerHTML = `<button 
                            data-gap-id="${subtaskId}" 
                            onclick="cycleStatus('${subtaskId}', this.textContent.trim())"
                            class="text-lg cursor-pointer hover:scale-110 transition-transform px-1 rounded hover:bg-slate-200"
                            title="${STATUS_LABELS[savedStatus]} (click to change)"
                        >${savedStatus}</button>`;
                    }
                }
            });
        }

        // Show landing page
        function showLandingPage() {
            const contentEl = document.getElementById('doc-content');
            document.querySelectorAll('.doc-nav-item').forEach(el => el.classList.remove('active'));
            window.location.hash = '';
            currentDoc = null;

            contentEl.innerHTML = `
                <h1>Welcome to CRMTracker® Documentation</h1>
                <p>Select a guide below to get started, or use the navigation on the left.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                    ${Object.entries(DOCS).map(([id, doc]) => `
                        <div class="border border-slate-200 rounded-xl p-6 hover:border-${doc.color}-300 hover:shadow-md transition cursor-pointer" onclick="loadDoc('${id}')">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-lg bg-${doc.color}-100 flex items-center justify-center">
                                    <i class="fa-solid ${doc.icon} text-${doc.color}-600"></i>
                                </div>
                                <h3 class="font-semibold text-slate-800">${doc.title}</h3>
                            </div>
                            <p class="text-slate-600 text-sm">${doc.desc}</p>
                        </div>
                    `).join('')}
                </div>

                <hr class="my-8">

                <h2>Quick Links</h2>
                <ul>
                    <li><strong>New users:</strong> Start with the <a href="#user-guide" class="text-indigo-600 hover:underline" onclick="loadDoc('user-guide')">User Guide</a></li>
                    <li><strong>System admins:</strong> Check the <a href="#admin-guide" class="text-indigo-600 hover:underline" onclick="loadDoc('admin-guide')">Admin Guide</a> for configuration</li>
                    <li><strong>Integrations:</strong> See the <a href="#developer-guide" class="text-indigo-600 hover:underline" onclick="loadDoc('developer-guide')">Developer Guide</a> for API docs</li>
                    <li><strong>Auditors:</strong> Review the <a href="#compliance" class="text-indigo-600 hover:underline" onclick="loadDoc('compliance')">Compliance Pack</a></li>
                </ul>
            `;
        }

        // Load documentation
        async function loadDoc(docId) {
            const contentEl = document.getElementById('doc-content');
            const doc = DOCS[docId];
            if (!doc) {
                showLandingPage();
                return;
            }

            // Update nav - find the matching nav item by doc ID
            document.querySelectorAll('.doc-nav-item').forEach((el, idx) => {
                el.classList.remove('active');
                if (Object.keys(DOCS)[idx] === docId) {
                    el.classList.add('active');
                }
            });

            // Show loading
            contentEl.innerHTML = `
                <div class="flex items-center justify-center h-64">
                    <div class="text-center">
                        <i class="fa-solid fa-spinner fa-spin text-4xl text-slate-300 mb-4"></i>
                        <p class="text-slate-500">Loading ${doc.title}...</p>
                    </div>
                </div>
            `;

            try {
                const response = await fetch(doc.path);
                if (!response.ok) throw new Error('Failed to load');

                const markdown = await response.text();
                contentEl.innerHTML = marked.parse(markdown);
                currentDoc = docId;

                // Update URL hash
                window.location.hash = docId;

                // If roadmap, make status icons interactive
                if (docId === 'roadmap') {
                    enhanceRoadmapTable();
                }
            } catch (error) {
                contentEl.innerHTML = `
                    <div class="flex items-center justify-center h-64">
                        <div class="text-center">
                            <i class="fa-solid fa-exclamation-triangle text-4xl text-amber-500 mb-4"></i>
                            <p class="text-slate-700 font-medium">Failed to load ${doc.title}</p>
                            <p class="text-slate-500 text-sm mt-2">${error.message}</p>
                            <button onclick="showLandingPage()" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Back to Home</button>
                        </div>
                    </div>
                `;
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            // Check URL hash
            const hash = window.location.hash.slice(1);
            if (hash && DOCS[hash]) {
                loadDoc(hash);
            } else {
                // Show landing page by default
                showLandingPage();
            }
        });
    </script>
@endpush
