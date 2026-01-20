@extends('layouts.dashboard')


@section('title', 'CRMTracker - Competitive Benchmarking')

@push('styles')
<style>
body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
        }

        .focus-column {
            background-color: rgba(59, 130, 246, 0.1);
        }

        .radar-focus {
            fill: rgba(59, 130, 246, 0.3);
            stroke: #3b82f6;
            stroke-width: 2;
        }

        .radar-avg {
            fill: none;
            stroke: #94a3b8;
            stroke-width: 1.5;
            stroke-dasharray: 4, 2;
        }

        .score-good {
            color: #059669;
        }

        .score-mid {
            color: #d97706;
        }

        .score-bad {
            color: #dc2626;
        }
</style>
@endpush

@section('content')
<main class="flex-1 ml-20 p-8 max-w-[1600px] mx-auto">
<header class="flex justify-between items-center mb-6">
<div>
<div
class="flex items-center gap-2 text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">
Module 6.1: Consulting Suite
</div>
<h1 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
Benchmarking Dashboard
</h1>
</div>
<div class="flex items-center gap-3">
<!-- Focus Selector -->
<div
class="flex items-center gap-2 px-4 py-2 bg-white rounded-lg border border-slate-200 shadow-sm">
<label class="text-xs font-bold text-slate-500 uppercase">Focus:</label>
<select id="focus-selector"
class="text-sm font-semibold text-slate-700 bg-transparent border-none outline-none cursor-pointer">
<option value="">Select Client...</option>
</select>
<div id="date-range-container" class="flex items-center"></div>
<button
class="px-5 py-2.5 rounded-lg font-medium shadow-sm flex items-center gap-2 transition-colors bg-[#ea580c] hover:bg-[#c2410c] text-white">
<i class="fa-solid fa-file-powerpoint"></i>
Export PPTX
</button>
</div>
</header>
<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
<div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
<p class="text-xs font-bold text-blue-500 uppercase mb-2">Focus Score</p>
<div class="flex items-end gap-3">
<span id="focus-score" class="text-3xl font-bold text-blue-700">—</span>
<span class="text-xs text-slate-500 mb-1">/100</span>
</div>
</div>
<div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
<p class="text-xs font-bold text-slate-500 uppercase mb-2">Market Average</p>
<div class="flex items-end gap-3">
<span id="avg-score" class="text-3xl font-bold text-slate-400">—</span>
<span class="text-xs text-slate-500 mb-1">/100</span>
</div>
</div>
<div
class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-l-red-500 border-y border-r border-slate-200">
<p class="text-xs font-bold text-red-600 uppercase mb-2">Biggest Gap</p>
<div class="flex flex-col">
<span id="biggest-gap" class="text-lg font-bold text-slate-800">—</span>
<span id="gap-delta" class="text-xs text-red-600">—</span>
</div>
</div>
<div
class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-l-emerald-500 border-y border-r border-slate-200">
<p class="text-xs font-bold text-emerald-600 uppercase mb-2">Biggest Strength</p>
<div class="flex flex-col">
<span id="biggest-strength" class="text-lg font-bold text-slate-800">—</span>
<span id="strength-delta" class="text-xs text-emerald-600">—</span>
</div>
</div>
</div>
<!-- Main Content: Radar + Gap Panel -->
<div class="grid grid-cols-12 gap-6 mb-6" style="min-height: 480px;">
<!-- Radar Chart -->
<div class="col-span-12 lg:col-span-7">
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 h-full flex flex-col">
<div class="flex justify-between items-start mb-2">
<h3 class="font-bold text-lg text-slate-800">Market Positioning</h3>
<div class="flex items-center gap-4 text-xs">
<div class="flex items-center gap-2">
<div class="w-3 h-3 rounded bg-blue-500/30 border-2 border-blue-500"></div>
<span class="font-bold text-blue-700">Focus</span>
</div>
<div class="flex items-center gap-2">
<div class="w-3 h-0.5 border-t-2 border-dotted border-slate-400"></div>
<span class="text-slate-500">Average</span>
</div>
</div>
</div>
<!-- Competitor Toggles for Radar -->
<div class="flex flex-wrap gap-2 mb-2 pb-2 border-b border-slate-100">
<span class="text-xs font-bold text-slate-500 mr-2">Show:</span>
<div id="radar-toggles" class="flex flex-wrap gap-2">
<!-- Dynamic toggles -->
</div>
</div>
<div class="flex-1 flex items-center justify-center" style="min-height: 340px;">
<svg id="radar-chart" viewBox="0 0 300 300" style="width: 100%; height: 320px;"></svg>
</div>
</div>
</div>
<!-- Gap Breakdown -->
<div class="col-span-12 lg:col-span-5">
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 h-full flex flex-col">
<h3 class="font-bold text-lg text-slate-800 mb-1">Gap Breakdown</h3>
<p class="text-xs text-slate-500 mb-4">Detailed score analysis by dimension.</p>
<div id="gap-breakdown" class="flex-1 overflow-y-auto space-y-4">
<!-- Dynamic content -->
</div>
</div>
</div>
</div>
<!-- Comparison Table -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
<table class="w-full">
<thead>
<tr id="table-header" class="bg-slate-50 border-b border-slate-200">
<th class="text-left py-4 px-4 font-bold text-slate-600 uppercase text-xs sticky-col">
Dimension</th>
<!-- Dynamic competitor headers inserted here -->
<th class="text-center py-4 px-4 font-bold text-slate-600 uppercase text-xs bg-slate-100">
AVG</th>
</tr>
</thead>
<tbody id="table-body">
<!-- Dynamic rows -->
</tbody>
</table>
</div>
</main>
</div>
<script>
const DIMENSIONS = [
{ id: 'techStack', label: 'Technology Stack', icon: 'fa-microchip', angle: 90 },
{ id: 'creative', label: 'Creative & UX', icon: 'fa-palette', angle: 162 },
{ id: 'risk', label: 'Risk & Compliance', icon: 'fa-shield-halved', angle: 234 },
{ id: 'speed', label: 'Speed to Market', icon: 'fa-bolt', angle: 306 },
{ id: 'data', label: 'Data Quality', icon: 'fa-database', angle: 18 }
];
let competitorScores = {};  // { competitorId: { techStack: 70, creative: 80, ... } }
let focusId = null;
let visibleCompetitors = new Set();  // IDs of competitors to show in table
async function init() {
if (!window.CRMT?.groups) {
setTimeout(init, 200);
return;
}
// Load scores from database
await loadScores();
// Populate focus selector
populateFocusSelector();
// Populate competitor toggles
populateCompetitorToggles();
// Listen for focus changes
window.addEventListener('focusCompetitorChanged', () => renderAll());
// Listen for group/selection changes
window.addEventListener('competitorGroupActivated', async () => {
await loadScores();
populateFocusSelector();
populateCompetitorToggles();
renderAll();
});
// Initial render
renderAll();
}
async function loadScores() {
try {
const scores = await CRMT.dal.getScores('6.1');
if (scores && scores.length > 0) {
scores.forEach(s => {
if (!competitorScores[s.competitor_id]) competitorScores[s.competitor_id] = {};
if (s.sections) {
Object.entries(s.sections).forEach(([section, data]) => {
competitorScores[s.competitor_id][section] = (data.score || 5) * 10;
});
}
});
console.log('[6.1] Loaded scores from DB:', Object.keys(competitorScores).length);
}
} catch (e) {
console.warn('[6.1] Could not load scores:', e.message);
}
// Fallback: generate scores from CRM data
const group = CRMT.groups.getActive();
if (group) {
group.competitorIds.forEach(id => {
if (!competitorScores[id]) {
const c = CRMT.getCompetitor(id);
if (c) {
competitorScores[id] = {
techStack: (c.crmScorecard?.personalization?.sectionScore || 5) * 10,
creative: (c.content?.headers?.sectionScore || 5) * 10,
risk: (c.compliance?.completeness?.sectionScore || 5) * 10,
speed: (c.crmScorecard?.frequency?.sectionScore || 5) * 10,
data: (c.crmScorecard?.unsubscribe?.sectionScore || 5) * 10
};
}
}
});
}
}
function populateFocusSelector() {
const select = document.getElementById('focus-selector');
const group = CRMT.groups.getActive();
if (!group) return;
select.innerHTML = '<option value="">Select Client...</option>';
group.competitorIds.forEach(id => {
const c = CRMT.getCompetitor(id);
if (c) {
const opt = document.createElement('option');
opt.value = id;
opt.textContent = c.shortName || c.name;
select.appendChild(opt);
}
});
// Set current focus
const currentFocus = CRMT.selection.getFocusId();
if (currentFocus) select.value = currentFocus;
select.addEventListener('change', (e) => {
CRMT.selection.setFocusCompetitor(e.target.value || null);
});
}
function populateCompetitorToggles() {
const container = document.getElementById('radar-toggles');
const group = CRMT.groups.getActive();
if (!group || !container) return;
// Initialize visibleCompetitors (focus is always in blue, others togglable)
visibleCompetitors.clear();
group.competitorIds.forEach(id => visibleCompetitors.add(id));
const colors = ['#ef4444', '#22c55e', '#f59e0b', '#8b5cf6', '#06b6d4', '#ec4899', '#84cc16'];
container.innerHTML = group.competitorIds.map((id, i) => {
const c = CRMT.getCompetitor(id);
if (!c) return '';
const isFocus = id === CRMT.selection.getFocusId();
const color = isFocus ? '#3b82f6' : colors[i % colors.length];
return `
<label class="flex items-center gap-1.5 cursor-pointer px-2 py-1 rounded-full border-2 hover:bg-slate-50 transition-colors" 
style="border-color: ${color}${isFocus ? '' : '80'}; background: ${isFocus ? color + '15' : 'transparent'};">
<input type="checkbox" data-competitor="${id}" data-color="${color}" 
${isFocus ? 'checked disabled' : 'checked'}
class="w-3 h-3 rounded" style="accent-color: ${color};">
<span class="text-xs font-bold" style="color: ${color};">${c.shortName || c.name}</span>
</label>
`;
}).join('');
// Handle toggle changes - update radar only
container.querySelectorAll('input[type=checkbox]:not(:disabled)').forEach(cb => {
cb.addEventListener('change', (e) => {
const id = e.target.dataset.competitor;
if (e.target.checked) {
visibleCompetitors.add(id);
} else {
visibleCompetitors.delete(id);
}
renderRadar();
});
});
}
function renderAll() {
focusId = CRMT.selection.getFocusId();
renderSummaryCards();
renderRadar();
renderGapBreakdown();
renderTable();
}
function getCompetitors() {
const group = CRMT.groups.getActive();
if (!group) return [];
return group.competitorIds
.map(id => CRMT.getCompetitor(id))
.filter(c => c !== null);
}
function calcAverage(dimension) {
const vals = Object.values(competitorScores).map(s => s[dimension] || 50);
return vals.length ? Math.round(vals.reduce((a, b) => a + b, 0) / vals.length) : 50;
}
function renderSummaryCards() {
const focusScores = competitorScores[focusId] || {};
const focusTotal = Object.values(focusScores).length
? Math.round(Object.values(focusScores).reduce((a, b) => a + b, 0) / 5)
: null;
const avgTotal = Math.round(DIMENSIONS.map(d => calcAverage(d.id)).reduce((a, b) => a + b, 0) / 5);
document.getElementById('focus-score').textContent = focusTotal ?? '—';
document.getElementById('avg-score').textContent = avgTotal;
// Find biggest gap and strength
let maxGap = { dim: null, delta: 0 };
let maxStrength = { dim: null, delta: 0 };
if (focusId && focusScores) {
DIMENSIONS.forEach(d => {
const focusVal = focusScores[d.id] || 50;
const avgVal = calcAverage(d.id);
const delta = focusVal - avgVal;
if (delta < maxGap.delta) maxGap = { dim: d.label, delta };
if (delta > maxStrength.delta) maxStrength = { dim: d.label, delta };
});
}
document.getElementById('biggest-gap').textContent = maxGap.dim || '—';
document.getElementById('gap-delta').textContent = maxGap.dim ? `${maxGap.delta}% vs avg` : '';
document.getElementById('biggest-strength').textContent = maxStrength.dim || '—';
document.getElementById('strength-delta').textContent = maxStrength.dim ? `+${maxStrength.delta}% vs avg` : '';
}
function renderRadar() {
const svg = document.getElementById('radar-chart');
const cx = 150, cy = 150, maxR = 100;
const colors = ['#ef4444', '#22c55e', '#f59e0b', '#8b5cf6', '#06b6d4', '#ec4899', '#84cc16'];
// Grid lines
let html = '<g class="stroke-slate-100 fill-none" stroke-width="1">';
[20, 40, 60, 80, 100].forEach(pct => {
const r = maxR * pct / 100;
const points = DIMENSIONS.map(d => {
const rad = (d.angle - 90) * Math.PI / 180;
return `${cx + r * Math.cos(rad)},${cy + r * Math.sin(rad)}`;
}).join(' ');
html += `<polygon points="${points}" />`;
});
html += '</g>';
// Axis lines
html += '<g class="stroke-slate-100" stroke-width="1">';
DIMENSIONS.forEach(d => {
const rad = (d.angle - 90) * Math.PI / 180;
html += `<line x1="${cx}" y1="${cy}" x2="${cx + maxR * Math.cos(rad)}" y2="${cy + maxR * Math.sin(rad)}" />`;
});
html += '</g>';
// Labels
DIMENSIONS.forEach(d => {
const rad = (d.angle - 90) * Math.PI / 180;
const lx = cx + (maxR + 25) * Math.cos(rad);
const ly = cy + (maxR + 25) * Math.sin(rad);
html += `<text x="${lx}" y="${ly}" text-anchor="middle" class="text-[9px] fill-slate-500 font-bold uppercase">${d.label.split(' ')[0]}</text>`;
});
// Avg polygon (always visible, gray dashed)
const avgPoints = DIMENSIONS.map(d => {
const val = calcAverage(d.id);
const r = maxR * val / 100;
const rad = (d.angle - 90) * Math.PI / 180;
return `${cx + r * Math.cos(rad)},${cy + r * Math.sin(rad)}`;
}).join(' ');
html += `<polygon points="${avgPoints}" class="radar-avg" />`;
// Draw polygons for each toggled competitor
const group = CRMT.groups.getActive();
if (group) {
group.competitorIds.forEach((compId, i) => {
if (!visibleCompetitors.has(compId)) return;
if (!competitorScores[compId]) return;
const isFocus = compId === focusId;
const color = isFocus ? '#3b82f6' : colors[i % colors.length];
const opacity = isFocus ? '0.3' : '0.15';
const strokeWidth = isFocus ? '2.5' : '1.5';
const points = DIMENSIONS.map(d => {
const val = competitorScores[compId][d.id] || 50;
const r = maxR * val / 100;
const rad = (d.angle - 90) * Math.PI / 180;
return `${cx + r * Math.cos(rad)},${cy + r * Math.sin(rad)}`;
}).join(' ');
html += `<polygon points="${points}" fill="${color}" fill-opacity="${opacity}" stroke="${color}" stroke-width="${strokeWidth}" />`;
// Dots for focus only
if (isFocus) {
DIMENSIONS.forEach(d => {
const val = competitorScores[compId][d.id] || 50;
const r = maxR * val / 100;
const rad = (d.angle - 90) * Math.PI / 180;
html += `<circle cx="${cx + r * Math.cos(rad)}" cy="${cy + r * Math.sin(rad)}" r="4" fill="${color}" />`;
});
}
});
}
svg.innerHTML = html;
}
function renderGapBreakdown() {
const container = document.getElementById('gap-breakdown');
const focusScores = competitorScores[focusId] || {};
container.innerHTML = DIMENSIONS.map(d => {
const focusVal = focusScores[d.id] || 50;
const avgVal = calcAverage(d.id);
const delta = focusVal - avgVal;
const deltaColor = delta > 5 ? 'emerald' : delta < -5 ? 'red' : 'slate';
const deltaText = delta > 5 ? `Leading (+${delta}%)` : delta < -5 ? `Lagging (${delta}%)` : 'Parity (0%)';
const barColor = delta > 5 ? 'bg-emerald-500' : delta < -5 ? 'bg-red-500' : 'bg-blue-400';
return `
<div class="flex flex-col gap-1 pb-3 border-b border-slate-50">
<div class="flex justify-between items-center">
<span class="text-sm font-bold text-slate-700"><i class="fa-solid ${d.icon} mr-2 text-slate-400"></i>${d.label}</span>
<span class="text-xs font-bold text-${deltaColor}-600 bg-${deltaColor}-50 px-2 py-0.5 rounded border border-${deltaColor}-100">${deltaText}</span>
</div>
<div class="w-full h-1.5 bg-slate-100 rounded-full mt-1">
<div class="h-full ${barColor} rounded-full" style="width: ${focusVal}%"></div>
</div>
<div class="flex justify-between text-[10px] text-slate-400">
<span>Focus: ${focusVal}%</span>
<span>Avg: ${avgVal}%</span>
</div>
</div>
`;
}).join('');
}
function renderTable() {
const tbody = document.getElementById('table-body');
const headerRow = document.getElementById('table-header');
const competitors = getCompetitors();
// Clear dynamic headers
headerRow.querySelectorAll('.dynamic-competitor-header').forEach(h => h.remove());
// Add competitor headers
const avgTh = headerRow.querySelector('th:last-child');
competitors.forEach(c => {
const isFocus = c.id === focusId;
const th = document.createElement('th');
th.className = `text-center py-4 px-4 min-w-[80px] dynamic-competitor-header ${isFocus ? 'bg-blue-100 border-b-2 border-blue-500' : ''}`;
th.innerHTML = `<span class="font-bold ${isFocus ? 'text-blue-700' : 'text-slate-600'} text-xs">${c.shortName}</span>`;
headerRow.insertBefore(th, avgTh);
});
// Render dimension rows
tbody.innerHTML = DIMENSIONS.map(d => {
const avgVal = calcAverage(d.id);
const cells = competitors.map(c => {
const val = competitorScores[c.id]?.[d.id] || 50;
const isFocus = c.id === focusId;
const colorClass = val >= 70 ? 'score-good' : val >= 40 ? 'score-mid' : 'score-bad';
return `<td class="py-3 px-4 text-center ${isFocus ? 'bg-blue-50' : ''}">
<span class="text-lg font-bold ${colorClass}">${val}</span>
</td>`;
}).join('');
return `
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4">
<div class="flex items-center gap-2">
<i class="fa-solid ${d.icon} text-slate-400"></i>
<span class="text-slate-700 font-medium">${d.label}</span>
</div>
</td>
${cells}
<td class="py-3 px-4 text-center bg-slate-50">
<span class="text-sm font-medium text-slate-600">${avgVal}</span>
</td>
</tr>
`;
}).join('');
}
document.addEventListener('DOMContentLoaded', () => setTimeout(init, 300));
</script>
@endsection

@push('page-scripts')
<script>
        const DIMENSIONS = [
            { id: 'techStack', label: 'Technology Stack', icon: 'fa-microchip', angle: 90 },
            { id: 'creative', label: 'Creative & UX', icon: 'fa-palette', angle: 162 },
            { id: 'risk', label: 'Risk & Compliance', icon: 'fa-shield-halved', angle: 234 },
            { id: 'speed', label: 'Speed to Market', icon: 'fa-bolt', angle: 306 },
            { id: 'data', label: 'Data Quality', icon: 'fa-database', angle: 18 }
        ];

        let competitorScores = {};  // { competitorId: { techStack: 70, creative: 80, ... } }
        let focusId = null;
        let visibleCompetitors = new Set();  // IDs of competitors to show in table

        async function init() {
            if (!window.CRMT?.groups) {
                setTimeout(init, 200);
                return;
            }

            // Load scores from database
            await loadScores();

            // Populate focus selector
            populateFocusSelector();

            // Populate competitor toggles
            populateCompetitorToggles();

            // Listen for focus changes
            window.addEventListener('focusCompetitorChanged', () => renderAll());

            // Listen for group/selection changes
            window.addEventListener('competitorGroupActivated', async () => {
                await loadScores();
                populateFocusSelector();
                populateCompetitorToggles();
                renderAll();
            });

            // Initial render
            renderAll();
        }

        async function loadScores() {
            try {
                const scores = await CRMT.dal.getScores('6.1');
                if (scores && scores.length > 0) {
                    scores.forEach(s => {
                        if (!competitorScores[s.competitor_id]) competitorScores[s.competitor_id] = {};
                        if (s.sections) {
                            Object.entries(s.sections).forEach(([section, data]) => {
                                competitorScores[s.competitor_id][section] = (data.score || 5) * 10;
                            });
                        }
                    });
                    console.log('[6.1] Loaded scores from DB:', Object.keys(competitorScores).length);
                }
            } catch (e) {
                console.warn('[6.1] Could not load scores:', e.message);
            }

            // Fallback: generate scores from CRM data
            const group = CRMT.groups.getActive();
            if (group) {
                group.competitorIds.forEach(id => {
                    if (!competitorScores[id]) {
                        const c = CRMT.getCompetitor(id);
                        if (c) {
                            competitorScores[id] = {
                                techStack: (c.crmScorecard?.personalization?.sectionScore || 5) * 10,
                                creative: (c.content?.headers?.sectionScore || 5) * 10,
                                risk: (c.compliance?.completeness?.sectionScore || 5) * 10,
                                speed: (c.crmScorecard?.frequency?.sectionScore || 5) * 10,
                                data: (c.crmScorecard?.unsubscribe?.sectionScore || 5) * 10
                            };
                        }
                    }
                });
            }
        }

        function populateFocusSelector() {
            const select = document.getElementById('focus-selector');
            const group = CRMT.groups.getActive();
            if (!group) return;

            select.innerHTML = '<option value="">Select Client...</option>';
            group.competitorIds.forEach(id => {
                const c = CRMT.getCompetitor(id);
                if (c) {
                    const opt = document.createElement('option');
                    opt.value = id;
                    opt.textContent = c.shortName || c.name;
                    select.appendChild(opt);
                }
            });

            // Set current focus
            const currentFocus = CRMT.selection.getFocusId();
            if (currentFocus) select.value = currentFocus;

            select.addEventListener('change', (e) => {
                CRMT.selection.setFocusCompetitor(e.target.value || null);
            });
        }

        function populateCompetitorToggles() {
            const container = document.getElementById('radar-toggles');
            const group = CRMT.groups.getActive();
            if (!group || !container) return;

            // Initialize visibleCompetitors (focus is always in blue, others togglable)
            visibleCompetitors.clear();
            group.competitorIds.forEach(id => visibleCompetitors.add(id));

            const colors = ['#ef4444', '#22c55e', '#f59e0b', '#8b5cf6', '#06b6d4', '#ec4899', '#84cc16'];

            container.innerHTML = group.competitorIds.map((id, i) => {
                const c = CRMT.getCompetitor(id);
                if (!c) return '';
                const isFocus = id === CRMT.selection.getFocusId();
                const color = isFocus ? '#3b82f6' : colors[i % colors.length];
                return `
                    <label class="flex items-center gap-1.5 cursor-pointer px-2 py-1 rounded-full border-2 hover:bg-slate-50 transition-colors" 
                           style="border-color: ${color}${isFocus ? '' : '80'}; background: ${isFocus ? color + '15' : 'transparent'};">
                        <input type="checkbox" data-competitor="${id}" data-color="${color}" 
                               ${isFocus ? 'checked disabled' : 'checked'}
                               class="w-3 h-3 rounded" style="accent-color: ${color};">
                        <span class="text-xs font-bold" style="color: ${color};">${c.shortName || c.name}</span>
                    </label>
                `;
            }).join('');

            // Handle toggle changes - update radar only
            container.querySelectorAll('input[type=checkbox]:not(:disabled)').forEach(cb => {
                cb.addEventListener('change', (e) => {
                    const id = e.target.dataset.competitor;
                    if (e.target.checked) {
                        visibleCompetitors.add(id);
                    } else {
                        visibleCompetitors.delete(id);
                    }
                    renderRadar();
                });
            });
        }

        function renderAll() {
            focusId = CRMT.selection.getFocusId();
            renderSummaryCards();
            renderRadar();
            renderGapBreakdown();
            renderTable();
        }

        function getCompetitors() {
            const group = CRMT.groups.getActive();
            if (!group) return [];
            return group.competitorIds
                .map(id => CRMT.getCompetitor(id))
                .filter(c => c !== null);
        }

        function calcAverage(dimension) {
            const vals = Object.values(competitorScores).map(s => s[dimension] || 50);
            return vals.length ? Math.round(vals.reduce((a, b) => a + b, 0) / vals.length) : 50;
        }

        function renderSummaryCards() {
            const focusScores = competitorScores[focusId] || {};
            const focusTotal = Object.values(focusScores).length
                ? Math.round(Object.values(focusScores).reduce((a, b) => a + b, 0) / 5)
                : null;

            const avgTotal = Math.round(DIMENSIONS.map(d => calcAverage(d.id)).reduce((a, b) => a + b, 0) / 5);

            document.getElementById('focus-score').textContent = focusTotal ?? '—';
            document.getElementById('avg-score').textContent = avgTotal;

            // Find biggest gap and strength
            let maxGap = { dim: null, delta: 0 };
            let maxStrength = { dim: null, delta: 0 };

            if (focusId && focusScores) {
                DIMENSIONS.forEach(d => {
                    const focusVal = focusScores[d.id] || 50;
                    const avgVal = calcAverage(d.id);
                    const delta = focusVal - avgVal;
                    if (delta < maxGap.delta) maxGap = { dim: d.label, delta };
                    if (delta > maxStrength.delta) maxStrength = { dim: d.label, delta };
                });
            }

            document.getElementById('biggest-gap').textContent = maxGap.dim || '—';
            document.getElementById('gap-delta').textContent = maxGap.dim ? `${maxGap.delta}% vs avg` : '';
            document.getElementById('biggest-strength').textContent = maxStrength.dim || '—';
            document.getElementById('strength-delta').textContent = maxStrength.dim ? `+${maxStrength.delta}% vs avg` : '';
        }

        function renderRadar() {
            const svg = document.getElementById('radar-chart');
            const cx = 150, cy = 150, maxR = 100;
            const colors = ['#ef4444', '#22c55e', '#f59e0b', '#8b5cf6', '#06b6d4', '#ec4899', '#84cc16'];

            // Grid lines
            let html = '<g class="stroke-slate-100 fill-none" stroke-width="1">';
            [20, 40, 60, 80, 100].forEach(pct => {
                const r = maxR * pct / 100;
                const points = DIMENSIONS.map(d => {
                    const rad = (d.angle - 90) * Math.PI / 180;
                    return `${cx + r * Math.cos(rad)},${cy + r * Math.sin(rad)}`;
                }).join(' ');
                html += `<polygon points="${points}" />`;
            });
            html += '</g>';

            // Axis lines
            html += '<g class="stroke-slate-100" stroke-width="1">';
            DIMENSIONS.forEach(d => {
                const rad = (d.angle - 90) * Math.PI / 180;
                html += `<line x1="${cx}" y1="${cy}" x2="${cx + maxR * Math.cos(rad)}" y2="${cy + maxR * Math.sin(rad)}" />`;
            });
            html += '</g>';

            // Labels
            DIMENSIONS.forEach(d => {
                const rad = (d.angle - 90) * Math.PI / 180;
                const lx = cx + (maxR + 25) * Math.cos(rad);
                const ly = cy + (maxR + 25) * Math.sin(rad);
                html += `<text x="${lx}" y="${ly}" text-anchor="middle" class="text-[9px] fill-slate-500 font-bold uppercase">${d.label.split(' ')[0]}</text>`;
            });

            // Avg polygon (always visible, gray dashed)
            const avgPoints = DIMENSIONS.map(d => {
                const val = calcAverage(d.id);
                const r = maxR * val / 100;
                const rad = (d.angle - 90) * Math.PI / 180;
                return `${cx + r * Math.cos(rad)},${cy + r * Math.sin(rad)}`;
            }).join(' ');
            html += `<polygon points="${avgPoints}" class="radar-avg" />`;

            // Draw polygons for each toggled competitor
            const group = CRMT.groups.getActive();
            if (group) {
                group.competitorIds.forEach((compId, i) => {
                    if (!visibleCompetitors.has(compId)) return;
                    if (!competitorScores[compId]) return;

                    const isFocus = compId === focusId;
                    const color = isFocus ? '#3b82f6' : colors[i % colors.length];
                    const opacity = isFocus ? '0.3' : '0.15';
                    const strokeWidth = isFocus ? '2.5' : '1.5';

                    const points = DIMENSIONS.map(d => {
                        const val = competitorScores[compId][d.id] || 50;
                        const r = maxR * val / 100;
                        const rad = (d.angle - 90) * Math.PI / 180;
                        return `${cx + r * Math.cos(rad)},${cy + r * Math.sin(rad)}`;
                    }).join(' ');

                    html += `<polygon points="${points}" fill="${color}" fill-opacity="${opacity}" stroke="${color}" stroke-width="${strokeWidth}" />`;

                    // Dots for focus only
                    if (isFocus) {
                        DIMENSIONS.forEach(d => {
                            const val = competitorScores[compId][d.id] || 50;
                            const r = maxR * val / 100;
                            const rad = (d.angle - 90) * Math.PI / 180;
                            html += `<circle cx="${cx + r * Math.cos(rad)}" cy="${cy + r * Math.sin(rad)}" r="4" fill="${color}" />`;
                        });
                    }
                });
            }

            svg.innerHTML = html;
        }

        function renderGapBreakdown() {
            const container = document.getElementById('gap-breakdown');
            const focusScores = competitorScores[focusId] || {};

            container.innerHTML = DIMENSIONS.map(d => {
                const focusVal = focusScores[d.id] || 50;
                const avgVal = calcAverage(d.id);
                const delta = focusVal - avgVal;
                const deltaColor = delta > 5 ? 'emerald' : delta < -5 ? 'red' : 'slate';
                const deltaText = delta > 5 ? `Leading (+${delta}%)` : delta < -5 ? `Lagging (${delta}%)` : 'Parity (0%)';
                const barColor = delta > 5 ? 'bg-emerald-500' : delta < -5 ? 'bg-red-500' : 'bg-blue-400';

                return `
                    <div class="flex flex-col gap-1 pb-3 border-b border-slate-50">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-bold text-slate-700"><i class="fa-solid ${d.icon} mr-2 text-slate-400"></i>${d.label}</span>
                            <span class="text-xs font-bold text-${deltaColor}-600 bg-${deltaColor}-50 px-2 py-0.5 rounded border border-${deltaColor}-100">${deltaText}</span>
                        </div>
                        <div class="w-full h-1.5 bg-slate-100 rounded-full mt-1">
                            <div class="h-full ${barColor} rounded-full" style="width: ${focusVal}%"></div>
                        </div>
                        <div class="flex justify-between text-[10px] text-slate-400">
                            <span>Focus: ${focusVal}%</span>
                            <span>Avg: ${avgVal}%</span>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function renderTable() {
            const tbody = document.getElementById('table-body');
            const headerRow = document.getElementById('table-header');
            const competitors = getCompetitors();

            // Clear dynamic headers
            headerRow.querySelectorAll('.dynamic-competitor-header').forEach(h => h.remove());

            // Add competitor headers
            const avgTh = headerRow.querySelector('th:last-child');
            competitors.forEach(c => {
                const isFocus = c.id === focusId;
                const th = document.createElement('th');
                th.className = `text-center py-4 px-4 min-w-[80px] dynamic-competitor-header ${isFocus ? 'bg-blue-100 border-b-2 border-blue-500' : ''}`;
                th.innerHTML = `<span class="font-bold ${isFocus ? 'text-blue-700' : 'text-slate-600'} text-xs">${c.shortName}</span>`;
                headerRow.insertBefore(th, avgTh);
            });

            // Render dimension rows
            tbody.innerHTML = DIMENSIONS.map(d => {
                const avgVal = calcAverage(d.id);
                const cells = competitors.map(c => {
                    const val = competitorScores[c.id]?.[d.id] || 50;
                    const isFocus = c.id === focusId;
                    const colorClass = val >= 70 ? 'score-good' : val >= 40 ? 'score-mid' : 'score-bad';
                    return `<td class="py-3 px-4 text-center ${isFocus ? 'bg-blue-50' : ''}">
                        <span class="text-lg font-bold ${colorClass}">${val}</span>
                    </td>`;
                }).join('');

                return `
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid ${d.icon} text-slate-400"></i>
                                <span class="text-slate-700 font-medium">${d.label}</span>
                            </div>
                        </td>
                        ${cells}
                        <td class="py-3 px-4 text-center bg-slate-50">
                            <span class="text-sm font-medium text-slate-600">${avgVal}</span>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        document.addEventListener('DOMContentLoaded', () => setTimeout(init, 300));
    </script>
@endpush
