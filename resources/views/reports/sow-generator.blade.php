@extends('layouts.dashboard')


@section('title', 'CRMTracker - Proposal Generator')


@section('content')
<main class="flex-1 ml-20 p-8 max-w-[1600px] mx-auto">
<header class="flex justify-between items-center mb-6">
<div>
<div
class="flex items-center gap-2 text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">
Module 6.3: Proposal Generator
</div>
<h1 class="text-2xl font-bold text-slate-800">Scope of Work (SOW)</h1>
</div>
<div class="flex items-center gap-3">
<div class="flex items-center gap-2 px-3 py-1.5 bg-emerald-50 border border-emerald-100 rounded-lg">
<span class="text-[10px] font-bold text-emerald-600 uppercase">Total Pipeline</span>
<span id="total-pipeline" class="text-sm font-bold text-emerald-800">€—</span>
<div id="date-range-container" class="flex items-center"></div>
<button onclick="exportPDF()"
class="px-4 py-2 rounded-lg font-medium shadow-sm flex items-center gap-2 transition-colors bg-slate-800 hover:bg-slate-700 text-white">
<i class="fa-solid fa-file-pdf"></i> Export PDF
</button>
</div>
</header>
<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
<div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
<p class="text-xs font-bold text-emerald-500 uppercase mb-2">Total Pipeline</p>
<span id="summary-pipeline" class="text-3xl font-bold text-emerald-600">€—</span>
</div>
<div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
<p class="text-xs font-bold text-blue-500 uppercase mb-2">Highest Value</p>
<div>
<span id="highest-value" class="text-xl font-bold text-blue-700">€—</span>
<p id="highest-name" class="text-xs text-slate-500 mt-1">—</p>
</div>
</div>
<div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
<p class="text-xs font-bold text-slate-500 uppercase mb-2">Average Contract</p>
<span id="avg-contract" class="text-3xl font-bold text-slate-600">€—</span>
</div>
<div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
<p class="text-xs font-bold text-slate-500 uppercase mb-2">Proposals</p>
<span id="proposal-count" class="text-3xl font-bold text-slate-700">—</span>
</div>
</div>
<div class="grid grid-cols-12 gap-6">
<!-- Proposal Cards Grid -->
<div class="col-span-12 lg:col-span-5">
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
<h3 class="font-bold text-sm text-slate-800 mb-4 flex items-center gap-2">
<i class="fa-solid fa-layer-group text-blue-500"></i>
Proposal Pipeline
</h3>
<div id="proposal-cards" class="grid grid-cols-1 md:grid-cols-2 gap-4">
<!-- Dynamic cards -->
</div>
</div>
</div>
<!-- SOW Preview Panel -->
<div class="col-span-12 lg:col-span-7">
<div class="bg-slate-100/50 rounded-xl border border-slate-200 p-4 h-full overflow-y-auto"
style="max-height: 700px;">
<div id="sow-preview" class="a4-preview font-serif text-slate-800 min-h-[600px]">
<div class="flex items-center justify-center h-full text-slate-400">
<div class="text-center">
<i class="fa-solid fa-file-contract text-4xl mb-3"></i>
<p class="text-sm">Select a proposal card to preview</p>
</div>
</div>
</div>
</div>
</div>
</div>
</main>
</div>
<script>
let competitorScores = {};
let selectedCompetitorId = null;
async function init() {
if (!window.CRMT?.groups) {
setTimeout(init, 200);
return;
}
await loadScores();
window.addEventListener('focusCompetitorChanged', () => renderAll());
window.addEventListener('competitorGroupActivated', async () => {
await loadScores();
renderAll();
});
renderAll();
}
async function loadScores() {
try {
const scores = await CRMT.dal.getScores('6.3');
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
console.warn('[6.3] Could not load scores:', e.message);
}
// Fallback scores
const group = CRMT.groups.getActive();
if (group) {
group.competitorIds.forEach(id => {
if (!competitorScores[id]) {
const c = CRMT.getCompetitor(id);
const complianceGap = 10 - (c?.compliance?.completeness?.sectionScore || 5);
const crmGap = 10 - (c?.crmScorecard?.personalization?.sectionScore || 5);
competitorScores[id] = {
phase1: Math.round(complianceGap * 8500),
phase2: Math.round(crmGap * 4500),
phase3: Math.round(crmGap * 6000),
contractValue: Math.round(complianceGap * 8500 + crmGap * 10500),
currentScore: Math.round((c?.scores?.crmtrackerScore || 50)),
targetScore: Math.min(90, Math.round((c?.scores?.crmtrackerScore || 50)) + 20)
};
}
});
}
}
function getCompetitors() {
const group = CRMT.groups.getActive();
if (!group) return [];
return group.competitorIds.map(id => CRMT.getCompetitor(id)).filter(c => c);
}
function renderAll() {
const focusId = CRMT.selection.getFocusId();
renderSummaryCards();
renderProposalCards(focusId);
if (selectedCompetitorId || focusId) {
renderSOWPreview(selectedCompetitorId || focusId);
}
}
function renderSummaryCards() {
const competitors = getCompetitors();
let totalPipeline = 0;
let highest = { value: 0, name: '' };
competitors.forEach(c => {
const val = competitorScores[c.id]?.contractValue || 0;
totalPipeline += val;
if (val > highest.value) {
highest = { value: val, name: c.shortName };
}
});
const avg = competitors.length ? Math.round(totalPipeline / competitors.length) : 0;
document.getElementById('total-pipeline').textContent = `€${Math.round(totalPipeline / 1000)}k`;
document.getElementById('summary-pipeline').textContent = `€${Math.round(totalPipeline / 1000)}k`;
document.getElementById('highest-value').textContent = `€${Math.round(highest.value / 1000)}k`;
document.getElementById('highest-name').textContent = highest.name;
document.getElementById('avg-contract').textContent = `€${Math.round(avg / 1000)}k`;
document.getElementById('proposal-count').textContent = competitors.length;
}
function renderProposalCards(focusId) {
const competitors = getCompetitors();
const container = document.getElementById('proposal-cards');
container.innerHTML = competitors.map(c => {
const s = competitorScores[c.id] || {};
const value = s.contractValue || 0;
const phases = [s.phase1, s.phase2, s.phase3].filter(p => p > 0).length;
const riskLevel = value > 80000 ? 'High' : value > 40000 ? 'Medium' : 'Low';
const riskColor = value > 80000 ? 'red' : value > 40000 ? 'amber' : 'emerald';
const isFocus = c.id === focusId;
const isSelected = c.id === selectedCompetitorId;
return `
<div onclick="selectProposal('${c.id}')" 
class="proposal-card cursor-pointer bg-white border-2 ${isFocus ? 'border-blue-400' : 'border-slate-200'} ${isSelected ? 'selected' : ''} rounded-xl p-4 hover:shadow-md">
<div class="flex justify-between items-start mb-3">
<h4 class="font-bold text-slate-800">${c.shortName}</h4>
${isFocus ? '<span class="text-[9px] bg-blue-600 text-white px-1.5 py-0.5 rounded font-bold">FOCUS</span>' : ''}
</div>
<div class="text-2xl font-bold text-emerald-600 mb-2">€${Math.round(value / 1000)}k</div>
<div class="flex items-center justify-between text-xs text-slate-500">
<span>${phases} Phases</span>
<span class="font-bold text-${riskColor}-600">${riskLevel} Value</span>
<div id="date-range-container" class="flex items-center"></div>
<button class="w-full mt-3 py-2 text-xs font-bold text-blue-600 bg-blue-50 rounded hover:bg-blue-100 transition-colors">
View SOW
</button>
</div>
`;
}).join('');
}
function selectProposal(competitorId) {
selectedCompetitorId = competitorId;
const focusId = CRMT.selection.getFocusId();
renderProposalCards(focusId);
renderSOWPreview(competitorId);
}
function renderSOWPreview(competitorId) {
const c = CRMT.getCompetitor(competitorId);
const s = competitorScores[competitorId] || {};
if (!c) return;
const today = new Date().toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' });
const contractValue = s.contractValue || 0;
document.getElementById('sow-preview').innerHTML = `
<div class="flex justify-between items-end border-b-2 border-slate-800 pb-4 mb-8">
<div>
<h1 class="text-2xl font-bold text-slate-900 mb-2">Statement of Work</h1>
<p class="text-sm text-slate-500 italic">Reference: CRM-${new Date().getFullYear()}-${Math.floor(Math.random() * 999).toString().padStart(3, '0')}</p>
</div>
<div class="text-right">
<p class="text-xs font-bold uppercase tracking-wider text-slate-400">Prepared For</p>
<p class="text-lg font-bold text-slate-900 dynamic-field">${c.name}</p>
</div>
</div>
<div class="mb-6">
<h2 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-2">1. Executive Summary</h2>
<p class="text-sm leading-relaxed text-slate-700">
This agreement outlines the services to be provided to remediate compliance risks and optimize the CRM technology stack. 
Based on the audit conducted on <span class="dynamic-field">${today}</span>, we have identified critical gaps that require attention.
</p>
</div>
<div class="mb-6">
<h2 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4">2. Proposed Scope</h2>
${s.phase1 > 0 ? `
<div class="mb-4">
<h3 class="font-bold text-slate-900 mb-1">Phase 1: Regulatory Audit & Remediation</h3>
<p class="text-xs font-bold text-blue-600 mb-2">Timeline: Weeks 1-4</p>
<ul class="list-disc list-inside text-sm text-slate-700 pl-2 space-y-1">
<li>Audit of all active automated triggers</li>
<li>Implementation of missing <span class="dynamic-field">RG Footer Elements</span></li>
<li>Validation of suppression lists for self-excluded players</li>
</ul>
<p class="text-right text-sm font-bold text-slate-600 mt-2">Estimated: €${Math.round(s.phase1 / 1000)}k</p>
</div>
` : ''}
${s.phase2 > 0 ? `
<div class="mb-4">
<h3 class="font-bold text-slate-900 mb-1">Phase 2: Cost Rationalization</h3>
<p class="text-xs font-bold text-blue-600 mb-2">Timeline: Weeks 5-8</p>
<ul class="list-disc list-inside text-sm text-slate-700 pl-2 space-y-1">
<li>Decommissioning of unused legacy seat licenses</li>
<li>Consolidation of duplicate data pipelines</li>
<li>Projected Savings: <span class="dynamic-field">€${Math.round(s.phase2 / 1000)}k / year</span></li>
</ul>
<p class="text-right text-sm font-bold text-slate-600 mt-2">Estimated: €${Math.round(s.phase2 / 1000)}k</p>
</div>
` : ''}
${s.phase3 > 0 ? `
<div class="mb-4">
<h3 class="font-bold text-slate-900 mb-1">Phase 3: Journey Implementation</h3>
<p class="text-xs font-bold text-blue-600 mb-2">Timeline: Weeks 9-12</p>
<ul class="list-disc list-inside text-sm text-slate-700 pl-2 space-y-1">
<li>Design and build of "Week 1" Welcome Journey</li>
<li>Integration of Mobile Push channel</li>
</ul>
<p class="text-right text-sm font-bold text-slate-600 mt-2">Estimated: €${Math.round(s.phase3 / 1000)}k</p>
</div>
` : ''}
</div>
<div class="mb-6 p-4 bg-slate-50 border-l-4 border-slate-800">
<h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">3. Success Criteria</h2>
<p class="text-sm font-medium text-slate-900">
The project will be deemed complete upon achieving a Validated CRMTracker Score of <span class="dynamic-field">${s.targetScore || 70}</span> by Month 6.
Current Score: <span class="dynamic-field">${s.currentScore || 50}</span>
</p>
</div>
<div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-600 rounded">
<h2 class="text-xs font-bold uppercase tracking-wider text-emerald-600 mb-2">Total Contract Value</h2>
<p class="text-2xl font-bold text-emerald-700">€${Math.round(contractValue / 1000)},000</p>
</div>
<div class="mt-12 grid grid-cols-2 gap-12">
<div>
<div class="border-b border-slate-300 h-8"></div>
<p class="text-xs mt-2 text-slate-500">Authorized Signature (Client)</p>
</div>
<div>
<div class="border-b border-slate-300 h-8"></div>
<p class="text-xs mt-2 text-slate-500">Authorized Signature (Agency)</p>
</div>
</div>
`;
}
function exportPDF() {
alert('PDF export would be implemented with a library like jsPDF or server-side PDF generation.');
}
document.addEventListener('DOMContentLoaded', () => setTimeout(init, 300));
</script>
@endsection

@push('page-scripts')
<script>
        let competitorScores = {};
        let selectedCompetitorId = null;

        async function init() {
            if (!window.CRMT?.groups) {
                setTimeout(init, 200);
                return;
            }

            await loadScores();
            window.addEventListener('focusCompetitorChanged', () => renderAll());
            window.addEventListener('competitorGroupActivated', async () => {
                await loadScores();
                renderAll();
            });
            renderAll();
        }

        async function loadScores() {
            try {
                const scores = await CRMT.dal.getScores('6.3');
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
                console.warn('[6.3] Could not load scores:', e.message);
            }

            // Fallback scores
            const group = CRMT.groups.getActive();
            if (group) {
                group.competitorIds.forEach(id => {
                    if (!competitorScores[id]) {
                        const c = CRMT.getCompetitor(id);
                        const complianceGap = 10 - (c?.compliance?.completeness?.sectionScore || 5);
                        const crmGap = 10 - (c?.crmScorecard?.personalization?.sectionScore || 5);
                        competitorScores[id] = {
                            phase1: Math.round(complianceGap * 8500),
                            phase2: Math.round(crmGap * 4500),
                            phase3: Math.round(crmGap * 6000),
                            contractValue: Math.round(complianceGap * 8500 + crmGap * 10500),
                            currentScore: Math.round((c?.scores?.crmtrackerScore || 50)),
                            targetScore: Math.min(90, Math.round((c?.scores?.crmtrackerScore || 50)) + 20)
                        };
                    }
                });
            }
        }

        function getCompetitors() {
            const group = CRMT.groups.getActive();
            if (!group) return [];
            return group.competitorIds.map(id => CRMT.getCompetitor(id)).filter(c => c);
        }

        function renderAll() {
            const focusId = CRMT.selection.getFocusId();
            renderSummaryCards();
            renderProposalCards(focusId);
            if (selectedCompetitorId || focusId) {
                renderSOWPreview(selectedCompetitorId || focusId);
            }
        }

        function renderSummaryCards() {
            const competitors = getCompetitors();
            let totalPipeline = 0;
            let highest = { value: 0, name: '' };

            competitors.forEach(c => {
                const val = competitorScores[c.id]?.contractValue || 0;
                totalPipeline += val;
                if (val > highest.value) {
                    highest = { value: val, name: c.shortName };
                }
            });

            const avg = competitors.length ? Math.round(totalPipeline / competitors.length) : 0;

            document.getElementById('total-pipeline').textContent = `€${Math.round(totalPipeline / 1000)}k`;
            document.getElementById('summary-pipeline').textContent = `€${Math.round(totalPipeline / 1000)}k`;
            document.getElementById('highest-value').textContent = `€${Math.round(highest.value / 1000)}k`;
            document.getElementById('highest-name').textContent = highest.name;
            document.getElementById('avg-contract').textContent = `€${Math.round(avg / 1000)}k`;
            document.getElementById('proposal-count').textContent = competitors.length;
        }

        function renderProposalCards(focusId) {
            const competitors = getCompetitors();
            const container = document.getElementById('proposal-cards');

            container.innerHTML = competitors.map(c => {
                const s = competitorScores[c.id] || {};
                const value = s.contractValue || 0;
                const phases = [s.phase1, s.phase2, s.phase3].filter(p => p > 0).length;
                const riskLevel = value > 80000 ? 'High' : value > 40000 ? 'Medium' : 'Low';
                const riskColor = value > 80000 ? 'red' : value > 40000 ? 'amber' : 'emerald';
                const isFocus = c.id === focusId;
                const isSelected = c.id === selectedCompetitorId;

                return `
                    <div onclick="selectProposal('${c.id}')" 
                         class="proposal-card cursor-pointer bg-white border-2 ${isFocus ? 'border-blue-400' : 'border-slate-200'} ${isSelected ? 'selected' : ''} rounded-xl p-4 hover:shadow-md">
                        <div class="flex justify-between items-start mb-3">
                            <h4 class="font-bold text-slate-800">${c.shortName}</h4>
                            ${isFocus ? '<span class="text-[9px] bg-blue-600 text-white px-1.5 py-0.5 rounded font-bold">FOCUS</span>' : ''}
                        </div>
                        <div class="text-2xl font-bold text-emerald-600 mb-2">€${Math.round(value / 1000)}k</div>
                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <span>${phases} Phases</span>
                            <span class="font-bold text-${riskColor}-600">${riskLevel} Value</span>
                        
                <div id="date-range-container" class="flex items-center"></div>
                <button class="w-full mt-3 py-2 text-xs font-bold text-blue-600 bg-blue-50 rounded hover:bg-blue-100 transition-colors">
                            View SOW
                        </button>
                    </div>
                `;
            }).join('');
        }

        function selectProposal(competitorId) {
            selectedCompetitorId = competitorId;
            const focusId = CRMT.selection.getFocusId();
            renderProposalCards(focusId);
            renderSOWPreview(competitorId);
        }

        function renderSOWPreview(competitorId) {
            const c = CRMT.getCompetitor(competitorId);
            const s = competitorScores[competitorId] || {};
            if (!c) return;

            const today = new Date().toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' });
            const contractValue = s.contractValue || 0;

            document.getElementById('sow-preview').innerHTML = `
                <div class="flex justify-between items-end border-b-2 border-slate-800 pb-4 mb-8">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 mb-2">Statement of Work</h1>
                        <p class="text-sm text-slate-500 italic">Reference: CRM-${new Date().getFullYear()}-${Math.floor(Math.random() * 999).toString().padStart(3, '0')}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Prepared For</p>
                        <p class="text-lg font-bold text-slate-900 dynamic-field">${c.name}</p>
                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-2">1. Executive Summary</h2>
                    <p class="text-sm leading-relaxed text-slate-700">
                        This agreement outlines the services to be provided to remediate compliance risks and optimize the CRM technology stack. 
                        Based on the audit conducted on <span class="dynamic-field">${today}</span>, we have identified critical gaps that require attention.
                    </p>
                </div>

                <div class="mb-6">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4">2. Proposed Scope</h2>
                    
                    ${s.phase1 > 0 ? `
                    <div class="mb-4">
                        <h3 class="font-bold text-slate-900 mb-1">Phase 1: Regulatory Audit & Remediation</h3>
                        <p class="text-xs font-bold text-blue-600 mb-2">Timeline: Weeks 1-4</p>
                        <ul class="list-disc list-inside text-sm text-slate-700 pl-2 space-y-1">
                            <li>Audit of all active automated triggers</li>
                            <li>Implementation of missing <span class="dynamic-field">RG Footer Elements</span></li>
                            <li>Validation of suppression lists for self-excluded players</li>
                        </ul>
                        <p class="text-right text-sm font-bold text-slate-600 mt-2">Estimated: €${Math.round(s.phase1 / 1000)}k</p>
                    </div>
                    ` : ''}

                    ${s.phase2 > 0 ? `
                    <div class="mb-4">
                        <h3 class="font-bold text-slate-900 mb-1">Phase 2: Cost Rationalization</h3>
                        <p class="text-xs font-bold text-blue-600 mb-2">Timeline: Weeks 5-8</p>
                        <ul class="list-disc list-inside text-sm text-slate-700 pl-2 space-y-1">
                            <li>Decommissioning of unused legacy seat licenses</li>
                            <li>Consolidation of duplicate data pipelines</li>
                            <li>Projected Savings: <span class="dynamic-field">€${Math.round(s.phase2 / 1000)}k / year</span></li>
                        </ul>
                        <p class="text-right text-sm font-bold text-slate-600 mt-2">Estimated: €${Math.round(s.phase2 / 1000)}k</p>
                    </div>
                    ` : ''}

                    ${s.phase3 > 0 ? `
                    <div class="mb-4">
                        <h3 class="font-bold text-slate-900 mb-1">Phase 3: Journey Implementation</h3>
                        <p class="text-xs font-bold text-blue-600 mb-2">Timeline: Weeks 9-12</p>
                        <ul class="list-disc list-inside text-sm text-slate-700 pl-2 space-y-1">
                            <li>Design and build of "Week 1" Welcome Journey</li>
                            <li>Integration of Mobile Push channel</li>
                        </ul>
                        <p class="text-right text-sm font-bold text-slate-600 mt-2">Estimated: €${Math.round(s.phase3 / 1000)}k</p>
                    </div>
                    ` : ''}
                </div>

                <div class="mb-6 p-4 bg-slate-50 border-l-4 border-slate-800">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">3. Success Criteria</h2>
                    <p class="text-sm font-medium text-slate-900">
                        The project will be deemed complete upon achieving a Validated CRMTracker Score of <span class="dynamic-field">${s.targetScore || 70}</span> by Month 6.
                        Current Score: <span class="dynamic-field">${s.currentScore || 50}</span>
                    </p>
                </div>

                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-600 rounded">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-emerald-600 mb-2">Total Contract Value</h2>
                    <p class="text-2xl font-bold text-emerald-700">€${Math.round(contractValue / 1000)},000</p>
                </div>

                <div class="mt-12 grid grid-cols-2 gap-12">
                    <div>
                        <div class="border-b border-slate-300 h-8"></div>
                        <p class="text-xs mt-2 text-slate-500">Authorized Signature (Client)</p>
                    </div>
                    <div>
                        <div class="border-b border-slate-300 h-8"></div>
                        <p class="text-xs mt-2 text-slate-500">Authorized Signature (Agency)</p>
                    </div>
                </div>
            `;
        }

        function exportPDF() {
            alert('PDF export would be implemented with a library like jsPDF or server-side PDF generation.');
        }

        document.addEventListener('DOMContentLoaded', () => setTimeout(init, 300));
    </script>
@endpush
