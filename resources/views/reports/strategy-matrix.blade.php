@extends('layouts.dashboard')


@section('title', 'CRMTracker - Strategy Matrix')


@section('content')
<main class="flex-1 ml-20 p-8 max-w-[1600px] mx-auto">
<header class="flex justify-between items-center mb-6">
<div>
<div
class="flex items-center gap-2 text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">
Module 6.2: Strategic Roadmap
</div>
<h1 class="text-2xl font-bold text-slate-800">Risk & Opportunity Heatmap</h1>
</div>
<div class="flex items-center gap-3">
<!-- Focus Selector -->
<div
class="flex items-center gap-2 px-4 py-2 bg-white rounded-lg border border-slate-200 shadow-sm">
<label class="text-xs font-bold text-slate-500 uppercase">Focus:</label>
<select id="focus-selector"
class="text-sm font-semibold text-slate-700 bg-transparent border-none outline-none cursor-pointer">
<option value="">All Competitors</option>
</select>
</div>
<!-- View Toggle -->
<div
class="flex items-center bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
<button id="view-all" class="px-3 py-2 text-xs font-bold bg-blue-600 text-white">All</button>
<button id="view-focus"
class="px-3 py-2 text-xs font-bold text-slate-500 hover:bg-slate-50">Focus Only</button>
<div id="date-range-container" class="flex items-center"></div>
<button
class="px-4 py-2 rounded-lg font-medium shadow-sm flex items-center gap-2 transition-colors bg-white border border-slate-200 text-slate-700 hover:bg-slate-50">
<i class="fa-solid fa-calendar"></i> Sync to Jira
</button>
</div>
</header>
<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
<div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
<p class="text-xs font-bold text-emerald-500 uppercase mb-2">Total Opportunity</p>
<div class="flex items-end gap-2">
<span id="total-opportunity" class="text-3xl font-bold text-emerald-600">€—</span>
</div>
</div>
<div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
<p class="text-xs font-bold text-emerald-500 uppercase mb-2">Quick Wins</p>
<span id="quick-wins-total" class="text-3xl font-bold text-emerald-600">€—</span>
</div>
<div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
<p class="text-xs font-bold text-blue-500 uppercase mb-2">Big Bets</p>
<span id="big-bets-total" class="text-3xl font-bold text-blue-600">€—</span>
</div>
<div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
<p class="text-xs font-bold text-slate-500 uppercase mb-2">Action Items</p>
<span id="action-items" class="text-3xl font-bold text-slate-700">—</span>
</div>
</div>
<!-- Opportunity Table -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
<table class="w-full">
<thead>
<tr id="table-header" class="bg-slate-50 border-b border-slate-200">
<th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs">Metric</th>
<!-- Dynamic competitor headers -->
<th class="text-center py-3 px-4 font-bold text-slate-600 uppercase text-xs bg-slate-100">
AVG</th>
</tr>
</thead>
<tbody id="table-body"></tbody>
</table>
</div>
<!-- Strategy Matrix 2x2 -->
<div class="grid grid-cols-12 gap-6">
<div class="col-span-12 lg:col-span-9">
<div class="matrix-grid" style="min-height: 520px;">
<!-- Quick Wins (Q1) - High Impact, Low Effort -->
<div id="quadrant-q1"
class="bg-emerald-50/30 p-4 relative hover:bg-emerald-50/50 transition-colors">
<div class="flex justify-between items-center mb-3">
<span
class="text-xs font-bold text-emerald-700 uppercase tracking-wider bg-emerald-100 px-2 py-1 rounded">Quick
Wins</span>
<span class="text-[10px] text-emerald-600 font-bold">Q1</span>
</div>
<div id="items-q1" class="space-y-2 overflow-y-auto max-h-[280px]"></div>
</div>
<!-- Big Bets (Q2) - High Impact, High Effort -->
<div id="quadrant-q2" class="bg-blue-50/30 p-4 relative hover:bg-blue-50/50 transition-colors">
<div class="flex justify-between items-center mb-3">
<span
class="text-xs font-bold text-blue-700 uppercase tracking-wider bg-blue-100 px-2 py-1 rounded">Big
Bets</span>
<span class="text-[10px] text-blue-600 font-bold">Q2</span>
</div>
<div id="items-q2" class="space-y-2 overflow-y-auto max-h-[280px]"></div>
</div>
<!-- Maintenance (Q4) - Low Impact, Low Effort -->
<div id="quadrant-q4" class="bg-slate-50 p-4 relative hover:bg-slate-100/50 transition-colors">
<div class="flex justify-between items-center mb-3">
<span
class="text-xs font-bold text-slate-500 uppercase tracking-wider bg-slate-200 px-2 py-1 rounded">Maintenance</span>
<span class="text-[10px] text-slate-400 font-bold">Q4</span>
</div>
<div id="items-q4" class="space-y-2 overflow-y-auto max-h-[280px]"></div>
</div>
<!-- Distractions (Q3) - Low Impact, High Effort -->
<div id="quadrant-q3"
class="bg-orange-50/30 p-4 relative hover:bg-orange-50/50 transition-colors">
<div class="flex justify-between items-center mb-3">
<span
class="text-xs font-bold text-orange-700 uppercase tracking-wider bg-orange-100 px-2 py-1 rounded">Distractions</span>
<span class="text-[10px] text-orange-600 font-bold">Q3</span>
</div>
<div id="items-q3" class="space-y-2 overflow-y-auto max-h-[280px]"></div>
</div>
</div>
<div class="flex justify-between mt-3">
<span class="text-xs font-bold text-slate-400 uppercase tracking-wide">← Low Effort</span>
<span class="text-xs font-bold text-slate-400 uppercase tracking-wide">High Effort →</span>
</div>
</div>
<!-- Priority Backlog -->
<div class="col-span-12 lg:col-span-3">
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 h-full">
<h3 class="font-bold text-sm text-slate-800 mb-4">Priority Backlog</h3>
<div id="priority-backlog" class="space-y-3"></div>
</div>
</div>
</div>
</main>
</div>
<script>
const METRICS = [
{ id: 'quickWins', label: 'Quick Wins', type: 'currency', color: 'emerald' },
{ id: 'bigBets', label: 'Big Bets', type: 'currency', color: 'blue' },
{ id: 'opportunity', label: 'Total Opportunity', type: 'currency', color: 'slate' },
{ id: 'risk', label: 'Risk Level', type: 'text', color: 'slate' }
];
// Pre-defined action items per competitor (based on gaps)
const ACTION_TEMPLATES = [
{ title: 'Fix Compliance Footers', quadrant: 'q1', category: 'Risk', baseValue: 12500 },
{ title: 'Cut Unused Licenses', quadrant: 'q1', category: 'Ops', baseValue: 45000 },
{ title: 'Launch Mobile Push', quadrant: 'q2', category: 'Strategic', baseValue: 120000 },
{ title: 'Implement Winback Flow', quadrant: 'q2', category: 'Revenue', baseValue: 80000 },
{ title: 'Update RG Messaging', quadrant: 'q4', category: 'Compliance', baseValue: 8000 },
{ title: 'Advanced AI Modeling', quadrant: 'q3', category: 'Future', baseValue: 0 }
];
let competitorScores = {};
let viewMode = 'all';  // 'all' or 'focus'
let focusId = null;
async function init() {
if (!window.CRMT?.groups) {
setTimeout(init, 200);
return;
}
await loadScores();
populateFocusSelector();
setupViewToggle();
window.addEventListener('focusCompetitorChanged', () => renderAll());
window.addEventListener('competitorGroupActivated', async () => {
console.log('[6.2] competitorGroupActivated event received');
await loadScores();
populateFocusSelector();
renderAll();
});
// Also use setupGroupChangeListener as backup
if (window.setupGroupChangeListener) {
window.setupGroupChangeListener(async () => {
console.log('[6.2] setupGroupChangeListener callback triggered');
await loadScores();
populateFocusSelector();
renderAll();
});
}
renderAll();
}
async function loadScores() {
// Clear old scores when group changes
competitorScores = {};
try {
const scores = await CRMT.dal.getScores('6.2');
if (scores && scores.length > 0) {
scores.forEach(s => {
if (!competitorScores[s.competitor_id]) competitorScores[s.competitor_id] = {};
if (s.sections) {
Object.entries(s.sections).forEach(([section, data]) => {
competitorScores[s.competitor_id][section] = data.score || 5;
if (data.metadata) {
Object.assign(competitorScores[s.competitor_id], data.metadata);
}
});
}
});
}
} catch (e) {
console.warn('[6.2] Could not load scores:', e.message);
}
// Fallback scores for current group
const group = CRMT.groups.getActive();
if (group) {
group.competitorIds.forEach(id => {
if (!competitorScores[id]) {
const c = CRMT.getCompetitor(id);
const complianceGap = 10 - (c?.compliance?.completeness?.sectionScore || 5);
const techGap = 10 - (c?.crmScorecard?.personalization?.sectionScore || 5);
competitorScores[id] = {
quickWins: Math.round(complianceGap * 12500),
bigBets: Math.round(techGap * 30000),
opportunity: Math.round(complianceGap * 12500 + techGap * 30000),
riskLevel: complianceGap > 5 ? 'High' : complianceGap > 2 ? 'Medium' : 'Low'
};
}
});
}
}
function populateFocusSelector() {
const select = document.getElementById('focus-selector');
const group = CRMT.groups.getActive();
if (!group) return;
select.innerHTML = '<option value="">All Competitors</option>';
group.competitorIds.forEach(id => {
const c = CRMT.getCompetitor(id);
if (c) {
const opt = document.createElement('option');
opt.value = id;
opt.textContent = c.shortName || c.name;
select.appendChild(opt);
}
});
const currentFocus = CRMT.selection.getFocusId();
if (currentFocus) select.value = currentFocus;
select.addEventListener('change', (e) => {
CRMT.selection.setFocusCompetitor(e.target.value || null);
});
}
function setupViewToggle() {
document.getElementById('view-all').addEventListener('click', () => {
viewMode = 'all';
document.getElementById('view-all').className = 'px-3 py-2 text-xs font-bold bg-blue-600 text-white';
document.getElementById('view-focus').className = 'px-3 py-2 text-xs font-bold text-slate-500 hover:bg-slate-50';
renderAll();
});
document.getElementById('view-focus').addEventListener('click', () => {
viewMode = 'focus';
document.getElementById('view-focus').className = 'px-3 py-2 text-xs font-bold bg-blue-600 text-white';
document.getElementById('view-all').className = 'px-3 py-2 text-xs font-bold text-slate-500 hover:bg-slate-50';
renderAll();
});
}
function getCompetitors() {
const group = CRMT.groups.getActive();
if (!group) return [];
return group.competitorIds.map(id => CRMT.getCompetitor(id)).filter(c => c);
}
function renderAll() {
focusId = CRMT.selection.getFocusId();
renderSummaryCards();
renderTable();
renderMatrix();
renderBacklog();
}
function renderSummaryCards() {
const competitors = getCompetitors();
let totalQuickWins = 0, totalBigBets = 0, totalActions = 0;
competitors.forEach(c => {
const s = competitorScores[c.id] || {};
if (viewMode === 'focus' && focusId && c.id !== focusId) return;
totalQuickWins += s.quickWins || 0;
totalBigBets += s.bigBets || 0;
totalActions += 2; // Estimate
});
document.getElementById('total-opportunity').textContent = `€${Math.round((totalQuickWins + totalBigBets) / 1000)}k`;
document.getElementById('quick-wins-total').textContent = `€${Math.round(totalQuickWins / 1000)}k`;
document.getElementById('big-bets-total').textContent = `€${Math.round(totalBigBets / 1000)}k`;
document.getElementById('action-items').textContent = totalActions;
}
function renderTable() {
const headerRow = document.getElementById('table-header');
const tbody = document.getElementById('table-body');
const competitors = getCompetitors();
headerRow.querySelectorAll('.dynamic-competitor-header').forEach(h => h.remove());
const avgTh = headerRow.querySelector('th:last-child');
competitors.forEach(c => {
const isFocus = c.id === focusId;
const th = document.createElement('th');
th.className = `text-center py-3 px-4 min-w-[80px] dynamic-competitor-header ${isFocus ? 'bg-blue-100' : ''}`;
th.innerHTML = `<span class="font-bold ${isFocus ? 'text-blue-700' : 'text-slate-600'} text-xs">${c.shortName}</span>`;
headerRow.insertBefore(th, avgTh);
});
tbody.innerHTML = METRICS.map(m => {
const cells = competitors.map(c => {
const s = competitorScores[c.id] || {};
const val = s[m.id];
const isFocus = c.id === focusId;
let display = '—';
if (m.type === 'currency') display = `€${Math.round((val || 0) / 1000)}k`;
else if (val) display = val;
return `<td class="py-3 px-4 text-center ${isFocus ? 'bg-blue-50' : ''}">
<span class="text-sm font-bold text-${m.color}-600">${display}</span>
</td>`;
}).join('');
// Calc avg
const vals = competitors.map(c => competitorScores[c.id]?.[m.id] || 0);
const avg = m.type === 'currency' && vals.length > 0 ? `€${Math.round(vals.reduce((a, b) => a + b, 0) / vals.length / 1000)}k` : '—';
return `<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4"><span class="text-slate-700 font-medium">${m.label}</span></td>
${cells}
<td class="py-3 px-4 text-center bg-slate-50"><span class="text-sm text-slate-600">${avg}</span></td>
</tr>`;
}).join('');
}
function renderMatrix() {
const competitors = getCompetitors();
const quadrants = { q1: [], q2: [], q3: [], q4: [] };
competitors.forEach(c => {
if (viewMode === 'focus' && focusId && c.id !== focusId) return;
const isFocus = c.id === focusId;
const color = isFocus ? 'blue' : 'slate';
ACTION_TEMPLATES.forEach(tmpl => {
const multiplier = (competitorScores[c.id]?.quickWins || 10000) / 12500;
const value = Math.round(tmpl.baseValue * multiplier);
if (value > 0 || tmpl.quadrant === 'q3') {
quadrants[tmpl.quadrant].push({
title: tmpl.title,
competitor: c.shortName,
category: tmpl.category,
value,
isFocus,
color
});
}
});
});
Object.keys(quadrants).forEach(q => {
const container = document.getElementById(`items-${q}`);
const items = quadrants[q].slice(0, 4); // Max 4 per quadrant
container.innerHTML = items.map(item => `
<div class="bg-white p-2 rounded shadow-sm border-l-4 border-l-${item.isFocus ? 'blue' : 'slate'}-400 hover:-translate-y-0.5 transition-transform">
<h4 class="text-xs font-bold text-slate-800">${item.title}</h4>
<div class="flex items-center justify-between mt-1">
<span class="text-[10px] text-slate-500">${item.competitor}</span>
<span class="text-[10px] font-bold text-${item.color}-600">€${Math.round(item.value / 1000)}k</span>
</div>
</div>
`).join('') || '<p class="text-xs text-slate-400 italic">No items</p>';
});
}
function renderBacklog() {
const competitors = getCompetitors();
const allItems = [];
competitors.forEach(c => {
if (viewMode === 'focus' && focusId && c.id !== focusId) return;
ACTION_TEMPLATES.filter(t => t.quadrant === 'q1' || t.quadrant === 'q2').forEach(tmpl => {
const multiplier = (competitorScores[c.id]?.quickWins || 10000) / 12500;
allItems.push({
title: tmpl.title,
competitor: c.shortName,
value: Math.round(tmpl.baseValue * multiplier),
quadrant: tmpl.quadrant
});
});
});
allItems.sort((a, b) => b.value - a.value);
const top5 = allItems.slice(0, 5);
document.getElementById('priority-backlog').innerHTML = top5.map((item, i) => `
<div class="flex gap-3 items-start">
<div class="w-6 h-6 rounded-full ${item.quadrant === 'q1' ? 'bg-emerald-100 text-emerald-600' : 'bg-blue-100 text-blue-600'} flex items-center justify-center text-xs font-bold shrink-0">${i + 1}</div>
<div class="flex-1">
<p class="text-xs font-bold text-slate-800">${item.title}</p>
<p class="text-[10px] text-slate-400">${item.competitor}</p>
</div>
<span class="text-[10px] font-bold text-slate-500">€${Math.round(item.value / 1000)}k</span>
</div>
`).join('');
}
document.addEventListener('DOMContentLoaded', () => setTimeout(init, 300));
</script>
@endsection

@push('page-scripts')
<script>
        const METRICS = [
            { id: 'quickWins', label: 'Quick Wins', type: 'currency', color: 'emerald' },
            { id: 'bigBets', label: 'Big Bets', type: 'currency', color: 'blue' },
            { id: 'opportunity', label: 'Total Opportunity', type: 'currency', color: 'slate' },
            { id: 'risk', label: 'Risk Level', type: 'text', color: 'slate' }
        ];

        // Pre-defined action items per competitor (based on gaps)
        const ACTION_TEMPLATES = [
            { title: 'Fix Compliance Footers', quadrant: 'q1', category: 'Risk', baseValue: 12500 },
            { title: 'Cut Unused Licenses', quadrant: 'q1', category: 'Ops', baseValue: 45000 },
            { title: 'Launch Mobile Push', quadrant: 'q2', category: 'Strategic', baseValue: 120000 },
            { title: 'Implement Winback Flow', quadrant: 'q2', category: 'Revenue', baseValue: 80000 },
            { title: 'Update RG Messaging', quadrant: 'q4', category: 'Compliance', baseValue: 8000 },
            { title: 'Advanced AI Modeling', quadrant: 'q3', category: 'Future', baseValue: 0 }
        ];

        let competitorScores = {};
        let viewMode = 'all';  // 'all' or 'focus'
        let focusId = null;

        async function init() {
            if (!window.CRMT?.groups) {
                setTimeout(init, 200);
                return;
            }

            await loadScores();
            populateFocusSelector();
            setupViewToggle();
            window.addEventListener('focusCompetitorChanged', () => renderAll());
            window.addEventListener('competitorGroupActivated', async () => {
                console.log('[6.2] competitorGroupActivated event received');
                await loadScores();
                populateFocusSelector();
                renderAll();
            });
            // Also use setupGroupChangeListener as backup
            if (window.setupGroupChangeListener) {
                window.setupGroupChangeListener(async () => {
                    console.log('[6.2] setupGroupChangeListener callback triggered');
                    await loadScores();
                    populateFocusSelector();
                    renderAll();
                });
            }
            renderAll();
        }

        async function loadScores() {
            // Clear old scores when group changes
            competitorScores = {};

            try {
                const scores = await CRMT.dal.getScores('6.2');
                if (scores && scores.length > 0) {
                    scores.forEach(s => {
                        if (!competitorScores[s.competitor_id]) competitorScores[s.competitor_id] = {};
                        if (s.sections) {
                            Object.entries(s.sections).forEach(([section, data]) => {
                                competitorScores[s.competitor_id][section] = data.score || 5;
                                if (data.metadata) {
                                    Object.assign(competitorScores[s.competitor_id], data.metadata);
                                }
                            });
                        }
                    });
                }
            } catch (e) {
                console.warn('[6.2] Could not load scores:', e.message);
            }

            // Fallback scores for current group
            const group = CRMT.groups.getActive();
            if (group) {
                group.competitorIds.forEach(id => {
                    if (!competitorScores[id]) {
                        const c = CRMT.getCompetitor(id);
                        const complianceGap = 10 - (c?.compliance?.completeness?.sectionScore || 5);
                        const techGap = 10 - (c?.crmScorecard?.personalization?.sectionScore || 5);
                        competitorScores[id] = {
                            quickWins: Math.round(complianceGap * 12500),
                            bigBets: Math.round(techGap * 30000),
                            opportunity: Math.round(complianceGap * 12500 + techGap * 30000),
                            riskLevel: complianceGap > 5 ? 'High' : complianceGap > 2 ? 'Medium' : 'Low'
                        };
                    }
                });
            }
        }

        function populateFocusSelector() {
            const select = document.getElementById('focus-selector');
            const group = CRMT.groups.getActive();
            if (!group) return;

            select.innerHTML = '<option value="">All Competitors</option>';
            group.competitorIds.forEach(id => {
                const c = CRMT.getCompetitor(id);
                if (c) {
                    const opt = document.createElement('option');
                    opt.value = id;
                    opt.textContent = c.shortName || c.name;
                    select.appendChild(opt);
                }
            });

            const currentFocus = CRMT.selection.getFocusId();
            if (currentFocus) select.value = currentFocus;

            select.addEventListener('change', (e) => {
                CRMT.selection.setFocusCompetitor(e.target.value || null);
            });
        }

        function setupViewToggle() {
            document.getElementById('view-all').addEventListener('click', () => {
                viewMode = 'all';
                document.getElementById('view-all').className = 'px-3 py-2 text-xs font-bold bg-blue-600 text-white';
                document.getElementById('view-focus').className = 'px-3 py-2 text-xs font-bold text-slate-500 hover:bg-slate-50';
                renderAll();
            });
            document.getElementById('view-focus').addEventListener('click', () => {
                viewMode = 'focus';
                document.getElementById('view-focus').className = 'px-3 py-2 text-xs font-bold bg-blue-600 text-white';
                document.getElementById('view-all').className = 'px-3 py-2 text-xs font-bold text-slate-500 hover:bg-slate-50';
                renderAll();
            });
        }

        function getCompetitors() {
            const group = CRMT.groups.getActive();
            if (!group) return [];
            return group.competitorIds.map(id => CRMT.getCompetitor(id)).filter(c => c);
        }

        function renderAll() {
            focusId = CRMT.selection.getFocusId();
            renderSummaryCards();
            renderTable();
            renderMatrix();
            renderBacklog();
        }

        function renderSummaryCards() {
            const competitors = getCompetitors();
            let totalQuickWins = 0, totalBigBets = 0, totalActions = 0;

            competitors.forEach(c => {
                const s = competitorScores[c.id] || {};
                if (viewMode === 'focus' && focusId && c.id !== focusId) return;
                totalQuickWins += s.quickWins || 0;
                totalBigBets += s.bigBets || 0;
                totalActions += 2; // Estimate
            });

            document.getElementById('total-opportunity').textContent = `€${Math.round((totalQuickWins + totalBigBets) / 1000)}k`;
            document.getElementById('quick-wins-total').textContent = `€${Math.round(totalQuickWins / 1000)}k`;
            document.getElementById('big-bets-total').textContent = `€${Math.round(totalBigBets / 1000)}k`;
            document.getElementById('action-items').textContent = totalActions;
        }

        function renderTable() {
            const headerRow = document.getElementById('table-header');
            const tbody = document.getElementById('table-body');
            const competitors = getCompetitors();

            headerRow.querySelectorAll('.dynamic-competitor-header').forEach(h => h.remove());
            const avgTh = headerRow.querySelector('th:last-child');

            competitors.forEach(c => {
                const isFocus = c.id === focusId;
                const th = document.createElement('th');
                th.className = `text-center py-3 px-4 min-w-[80px] dynamic-competitor-header ${isFocus ? 'bg-blue-100' : ''}`;
                th.innerHTML = `<span class="font-bold ${isFocus ? 'text-blue-700' : 'text-slate-600'} text-xs">${c.shortName}</span>`;
                headerRow.insertBefore(th, avgTh);
            });

            tbody.innerHTML = METRICS.map(m => {
                const cells = competitors.map(c => {
                    const s = competitorScores[c.id] || {};
                    const val = s[m.id];
                    const isFocus = c.id === focusId;
                    let display = '—';
                    if (m.type === 'currency') display = `€${Math.round((val || 0) / 1000)}k`;
                    else if (val) display = val;

                    return `<td class="py-3 px-4 text-center ${isFocus ? 'bg-blue-50' : ''}">
                        <span class="text-sm font-bold text-${m.color}-600">${display}</span>
                    </td>`;
                }).join('');

                // Calc avg
                const vals = competitors.map(c => competitorScores[c.id]?.[m.id] || 0);
                const avg = m.type === 'currency' && vals.length > 0 ? `€${Math.round(vals.reduce((a, b) => a + b, 0) / vals.length / 1000)}k` : '—';

                return `<tr class="border-b border-slate-100 hover:bg-slate-50">
                    <td class="py-3 px-4"><span class="text-slate-700 font-medium">${m.label}</span></td>
                    ${cells}
                    <td class="py-3 px-4 text-center bg-slate-50"><span class="text-sm text-slate-600">${avg}</span></td>
                </tr>`;
            }).join('');
        }

        function renderMatrix() {
            const competitors = getCompetitors();
            const quadrants = { q1: [], q2: [], q3: [], q4: [] };

            competitors.forEach(c => {
                if (viewMode === 'focus' && focusId && c.id !== focusId) return;
                const isFocus = c.id === focusId;
                const color = isFocus ? 'blue' : 'slate';

                ACTION_TEMPLATES.forEach(tmpl => {
                    const multiplier = (competitorScores[c.id]?.quickWins || 10000) / 12500;
                    const value = Math.round(tmpl.baseValue * multiplier);
                    if (value > 0 || tmpl.quadrant === 'q3') {
                        quadrants[tmpl.quadrant].push({
                            title: tmpl.title,
                            competitor: c.shortName,
                            category: tmpl.category,
                            value,
                            isFocus,
                            color
                        });
                    }
                });
            });

            Object.keys(quadrants).forEach(q => {
                const container = document.getElementById(`items-${q}`);
                const items = quadrants[q].slice(0, 4); // Max 4 per quadrant
                container.innerHTML = items.map(item => `
                    <div class="bg-white p-2 rounded shadow-sm border-l-4 border-l-${item.isFocus ? 'blue' : 'slate'}-400 hover:-translate-y-0.5 transition-transform">
                        <h4 class="text-xs font-bold text-slate-800">${item.title}</h4>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-[10px] text-slate-500">${item.competitor}</span>
                            <span class="text-[10px] font-bold text-${item.color}-600">€${Math.round(item.value / 1000)}k</span>
                        </div>
                    </div>
                `).join('') || '<p class="text-xs text-slate-400 italic">No items</p>';
            });
        }

        function renderBacklog() {
            const competitors = getCompetitors();
            const allItems = [];

            competitors.forEach(c => {
                if (viewMode === 'focus' && focusId && c.id !== focusId) return;
                ACTION_TEMPLATES.filter(t => t.quadrant === 'q1' || t.quadrant === 'q2').forEach(tmpl => {
                    const multiplier = (competitorScores[c.id]?.quickWins || 10000) / 12500;
                    allItems.push({
                        title: tmpl.title,
                        competitor: c.shortName,
                        value: Math.round(tmpl.baseValue * multiplier),
                        quadrant: tmpl.quadrant
                    });
                });
            });

            allItems.sort((a, b) => b.value - a.value);
            const top5 = allItems.slice(0, 5);

            document.getElementById('priority-backlog').innerHTML = top5.map((item, i) => `
                <div class="flex gap-3 items-start">
                    <div class="w-6 h-6 rounded-full ${item.quadrant === 'q1' ? 'bg-emerald-100 text-emerald-600' : 'bg-blue-100 text-blue-600'} flex items-center justify-center text-xs font-bold shrink-0">${i + 1}</div>
                    <div class="flex-1">
                        <p class="text-xs font-bold text-slate-800">${item.title}</p>
                        <p class="text-[10px] text-slate-400">${item.competitor}</p>
                    </div>
                    <span class="text-[10px] font-bold text-slate-500">€${Math.round(item.value / 1000)}k</span>
                </div>
            `).join('');
        }

        document.addEventListener('DOMContentLoaded', () => setTimeout(init, 300));
    </script>
@endpush
