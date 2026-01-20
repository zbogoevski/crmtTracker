@extends('layouts.dashboard')


@section('title', 'Report 5.1: Valuation Uplift | CRMTracker')


@section('content')
<main class="flex-1 ml-20 p-8 max-w-[1600px] mx-auto overflow-x-hidden">
<!-- Header injected by reportTemplate.js -->
<div id="report-header"></div>
<script>
document.addEventListener('DOMContentLoaded', () => {
if (window.CRMT?.renderReportHeader) {
CRMT.renderReportHeader('#report-header', {
module: '5.1',
title: 'Valuation Uplift',
category: 'Strategy',
isBeta: true,
stubData: ['EBITDA Projections', 'Gap-to-Value Mapping']
});
}
});
</script>
<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
<div class="bg-emerald-50 rounded-xl p-5 border border-emerald-200">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-arrow-trend-up text-emerald-500"></i>
<span class="text-xs font-bold text-emerald-800 uppercase">Total EBITDA Uplift</span>
</div>
<p class="text-3xl font-bold text-emerald-700" id="total-ebitda">$-k</p>
<p class="text-sm text-emerald-600">Avg per competitor</p>
</div>
<div class="bg-blue-50 rounded-xl p-5 border border-blue-200">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-ghost text-blue-500"></i>
<span class="text-xs font-bold text-blue-800 uppercase">Ghost Costs</span>
</div>
<p class="text-3xl font-bold text-blue-700" id="ghost-costs">$-k</p>
<p class="text-sm text-blue-600">Savings potential</p>
</div>
<div class="bg-violet-50 rounded-xl p-5 border border-violet-200">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-rocket text-violet-500"></i>
<span class="text-xs font-bold text-violet-800 uppercase">Revenue Lift</span>
</div>
<p class="text-3xl font-bold text-violet-700" id="revenue-lift">$-k</p>
<p class="text-sm text-violet-600">From optimizations</p>
</div>
<div class="bg-rose-50 rounded-xl p-5 border border-rose-200">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-shield-halved text-rose-500"></i>
<span class="text-xs font-bold text-rose-800 uppercase">Risk Exposure</span>
</div>
<p class="text-3xl font-bold text-rose-700" id="risk-exposure">$-k</p>
<p class="text-sm text-rose-600">Mitigation value</p>
</div>
</div>
<!-- Legend -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 mb-6">
<div class="flex flex-wrap items-center justify-between gap-4">
<div class="flex items-center gap-6 text-xs">
<span class="font-bold text-slate-600 uppercase tracking-wide">Sections:</span>
<span class="px-2 py-1 rounded bg-blue-100 text-blue-700">Ghost Costs</span>
<span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700">Revenue Lift</span>
<span class="px-2 py-1 rounded bg-violet-100 text-violet-700">Tech Consolidation</span>
<span class="px-2 py-1 rounded bg-rose-100 text-rose-700">Risk Exposure</span>
</div>
<div class="text-xs text-slate-500">
<i class="fa-solid fa-info-circle mr-1"></i>
Values in $k (thousands)
</div>
</div>
</div>
<!-- Main Valuation Table -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
<div class="overflow-x-auto">
<table class="w-full text-sm min-w-[900px]">
<thead>
<tr class="border-b-2 border-slate-300" id="competitor-header-row">
<th
class="sticky-col text-left py-4 px-4 font-bold text-slate-600 uppercase text-xs tracking-wider bg-slate-50 min-w-[200px]">
Metric
</th>
<!-- Dynamic competitor headers inserted by JS -->
<th
class="text-center py-4 px-4 font-bold text-slate-600 uppercase text-xs tracking-wider bg-slate-50 min-w-[80px]">
Avg</th>
</tr>
</thead>
<tbody id="dynamic-tbody">
<!-- Dynamic content inserted by tableRenderer -->
</tbody>
</table>
</div>
</div>
</main>
</div>
<script>
// Report 5.1 Configuration - Valuation Uplift (M&A)
// Data source: valuation.* in competitorData.js
const REPORT_CONFIG = {
sections: [
{
id: 'ghostCosts',
title: 'Ghost Costs',
subtitle: '(Unused licenses, duplicate tools)',
icon: 'fa-solid fa-ghost',
colorScheme: 'blue',
rows: [
{ label: 'Total Ghost Costs', key: 'valuation.ghostCosts.total', type: 'currency', suffix: 'k' },
{ label: 'Unused Licenses', key: 'valuation.ghostCosts.unusedLicenses', type: 'currency', suffix: 'k' },
{ label: 'Duplicate Tools', key: 'valuation.ghostCosts.duplicateTools', type: 'currency', suffix: 'k' }
]
},
{
id: 'revenueLift',
title: 'Revenue Lift',
subtitle: '(Cart recovery, winback flows)',
icon: 'fa-solid fa-arrow-trend-up',
colorScheme: 'emerald',
rows: [
{ label: 'Total Revenue Lift', key: 'valuation.revenueLift.total', type: 'currency', suffix: 'k' },
{ label: 'Cart Abandonment Recovery', key: 'valuation.revenueLift.cartAbandonment', type: 'currency', suffix: 'k' },
{ label: 'Winback Flows', key: 'valuation.revenueLift.winbackFlows', type: 'currency', suffix: 'k' }
]
},
{
id: 'techConsolidation',
title: 'Tech Consolidation',
subtitle: '(ESP/CRM overlap, CPaaS savings)',
icon: 'fa-solid fa-layer-group',
colorScheme: 'violet',
rows: [
{ label: 'Total Consolidation', key: 'valuation.techConsolidation.total', type: 'currency', suffix: 'k' },
{ label: 'ESP/CRM Overlap', key: 'valuation.techConsolidation.espCrmOverlap', type: 'currency', suffix: 'k' },
{ label: 'CPaaS Savings', key: 'valuation.techConsolidation.cpaasSavings', type: 'currency', suffix: 'k' }
]
},
{
id: 'riskExposure',
title: 'Risk Exposure',
subtitle: '(Compliance gaps, data privacy)',
icon: 'fa-solid fa-shield-halved',
colorScheme: 'rose',
rows: [
{ label: 'Total Risk Exposure', key: 'valuation.riskExposure.total', type: 'currency', suffix: 'k' },
{ label: 'Compliance Gaps', key: 'valuation.riskExposure.complianceGaps', type: 'currency', suffix: 'k' },
{ label: 'Data Privacy', key: 'valuation.riskExposure.dataPrivacy', type: 'currency', suffix: 'k' }
]
}
]
};
function updateSummaryCards(competitors) {
let totalEbitda = 0, totalGhost = 0, totalRevenue = 0, totalRisk = 0;
let count = 0;
competitors.forEach(c => {
const val = c.valuation || {};
if (val.ebitdaImpact) {
totalEbitda += val.ebitdaImpact;
count++;
}
if (val.ghostCosts?.total) totalGhost += val.ghostCosts.total;
if (val.revenueLift?.total) totalRevenue += val.revenueLift.total;
if (val.riskExposure?.total) totalRisk += val.riskExposure.total;
});
document.getElementById('total-ebitda').textContent = count > 0 ? '$' + Math.round(totalEbitda / count) + 'k' : '-';
document.getElementById('ghost-costs').textContent = '$' + Math.round(totalGhost / Math.max(competitors.length, 1)) + 'k';
document.getElementById('revenue-lift').textContent = '$' + Math.round(totalRevenue / Math.max(competitors.length, 1)) + 'k';
document.getElementById('risk-exposure').textContent = '$' + Math.round(totalRisk / Math.max(competitors.length, 1)) + 'k';
}
async function renderDynamicContent() {
if (!window.CRMT?.renderTable || !window.getActiveCompetitorsForReport) {
setTimeout(renderDynamicContent, 200);
return;
}
const competitors = window.getActiveCompetitorsForReport();
if (competitors.length === 0) {
setTimeout(renderDynamicContent, 200);
return;
}
// Load scores from database via DAL (from scorecard calculation)
try {
const scoresResult = await CRMT.dal.getScores('5.1');
const scores = scoresResult?.data || [];
if (scores.length > 0) {
competitors.forEach(c => {
const scoreData = scores.find(s => s.competitor_id === c.id);
if (scoreData && scoreData.sections) {
const ghostCosts = scoreData.sections.ghostCosts?.metadata || {};
const revenueLift = scoreData.sections.revenueLift?.metadata || {};
const techConsolidation = scoreData.sections.techConsolidation?.metadata || {};
const riskExposure = scoreData.sections.riskExposure?.metadata || {};
c.valuation = {
ebitdaImpact: (ghostCosts.total || 0) + (revenueLift.total || 0) + (techConsolidation.total || 0),
ghostCosts: {
total: ghostCosts.total ?? 0,
unusedLicenses: ghostCosts.unusedLicenses ?? 0,
duplicateTools: ghostCosts.duplicateTools ?? 0
},
revenueLift: {
total: revenueLift.total ?? 0,
cartAbandonment: revenueLift.cartAbandonment ?? 0,
winbackFlows: revenueLift.winbackFlows ?? 0
},
techConsolidation: {
total: techConsolidation.total ?? 0,
espCrmOverlap: techConsolidation.espCrmOverlap ?? 0,
cpaasSavings: techConsolidation.cpaasSavings ?? 0
},
riskExposure: {
total: riskExposure.total ?? 0,
complianceGaps: riskExposure.complianceGaps ?? 0,
dataPrivacy: riskExposure.dataPrivacy ?? 0
}
};
}
});
console.log('[5.1] Loaded', scores.length, 'scores from database');
}
} catch (e) {
console.warn('[5.1] Could not load scores from DB:', e.message);
}
// Render table using shared renderer
window.CRMT.renderTable('#dynamic-tbody', '#competitor-header-row', REPORT_CONFIG, competitors);
// Update summary cards
updateSummaryCards(competitors);
console.log('[5.1] Dynamic content rendered for', competitors.length, 'competitors');
}
function initDynamicContent() {
if (window.setupGroupChangeListener) window.setupGroupChangeListener(renderDynamicContent);
else {
document.addEventListener('DOMContentLoaded', () => setTimeout(renderDynamicContent, 500));
window.addEventListener('competitorGroupActivated', renderDynamicContent);
}
}
if (window.CRMT) initDynamicContent();
else {
window.addEventListener('crmtReady', initDynamicContent, { once: true });
setTimeout(initDynamicContent, 1000);
}
</script>
@endsection

@push('page-scripts')
<script>
                document.addEventListener('DOMContentLoaded', () => {
                    if (window.CRMT?.renderReportHeader) {
                        CRMT.renderReportHeader('#report-header', {
                            module: '5.1',
                            title: 'Valuation Uplift',
                            category: 'Strategy',
                            isBeta: true,
                            stubData: ['EBITDA Projections', 'Gap-to-Value Mapping']
                        });
                    }
                });
            </script>
<script>
        // Report 5.1 Configuration - Valuation Uplift (M&A)
        // Data source: valuation.* in competitorData.js
        const REPORT_CONFIG = {
            sections: [
                {
                    id: 'ghostCosts',
                    title: 'Ghost Costs',
                    subtitle: '(Unused licenses, duplicate tools)',
                    icon: 'fa-solid fa-ghost',
                    colorScheme: 'blue',
                    rows: [
                        { label: 'Total Ghost Costs', key: 'valuation.ghostCosts.total', type: 'currency', suffix: 'k' },
                        { label: 'Unused Licenses', key: 'valuation.ghostCosts.unusedLicenses', type: 'currency', suffix: 'k' },
                        { label: 'Duplicate Tools', key: 'valuation.ghostCosts.duplicateTools', type: 'currency', suffix: 'k' }
                    ]
                },
                {
                    id: 'revenueLift',
                    title: 'Revenue Lift',
                    subtitle: '(Cart recovery, winback flows)',
                    icon: 'fa-solid fa-arrow-trend-up',
                    colorScheme: 'emerald',
                    rows: [
                        { label: 'Total Revenue Lift', key: 'valuation.revenueLift.total', type: 'currency', suffix: 'k' },
                        { label: 'Cart Abandonment Recovery', key: 'valuation.revenueLift.cartAbandonment', type: 'currency', suffix: 'k' },
                        { label: 'Winback Flows', key: 'valuation.revenueLift.winbackFlows', type: 'currency', suffix: 'k' }
                    ]
                },
                {
                    id: 'techConsolidation',
                    title: 'Tech Consolidation',
                    subtitle: '(ESP/CRM overlap, CPaaS savings)',
                    icon: 'fa-solid fa-layer-group',
                    colorScheme: 'violet',
                    rows: [
                        { label: 'Total Consolidation', key: 'valuation.techConsolidation.total', type: 'currency', suffix: 'k' },
                        { label: 'ESP/CRM Overlap', key: 'valuation.techConsolidation.espCrmOverlap', type: 'currency', suffix: 'k' },
                        { label: 'CPaaS Savings', key: 'valuation.techConsolidation.cpaasSavings', type: 'currency', suffix: 'k' }
                    ]
                },
                {
                    id: 'riskExposure',
                    title: 'Risk Exposure',
                    subtitle: '(Compliance gaps, data privacy)',
                    icon: 'fa-solid fa-shield-halved',
                    colorScheme: 'rose',
                    rows: [
                        { label: 'Total Risk Exposure', key: 'valuation.riskExposure.total', type: 'currency', suffix: 'k' },
                        { label: 'Compliance Gaps', key: 'valuation.riskExposure.complianceGaps', type: 'currency', suffix: 'k' },
                        { label: 'Data Privacy', key: 'valuation.riskExposure.dataPrivacy', type: 'currency', suffix: 'k' }
                    ]
                }
            ]
        };

        function updateSummaryCards(competitors) {
            let totalEbitda = 0, totalGhost = 0, totalRevenue = 0, totalRisk = 0;
            let count = 0;

            competitors.forEach(c => {
                const val = c.valuation || {};
                if (val.ebitdaImpact) {
                    totalEbitda += val.ebitdaImpact;
                    count++;
                }
                if (val.ghostCosts?.total) totalGhost += val.ghostCosts.total;
                if (val.revenueLift?.total) totalRevenue += val.revenueLift.total;
                if (val.riskExposure?.total) totalRisk += val.riskExposure.total;
            });

            document.getElementById('total-ebitda').textContent = count > 0 ? '$' + Math.round(totalEbitda / count) + 'k' : '-';
            document.getElementById('ghost-costs').textContent = '$' + Math.round(totalGhost / Math.max(competitors.length, 1)) + 'k';
            document.getElementById('revenue-lift').textContent = '$' + Math.round(totalRevenue / Math.max(competitors.length, 1)) + 'k';
            document.getElementById('risk-exposure').textContent = '$' + Math.round(totalRisk / Math.max(competitors.length, 1)) + 'k';
        }

        async function renderDynamicContent() {
            if (!window.CRMT?.renderTable || !window.getActiveCompetitorsForReport) {
                setTimeout(renderDynamicContent, 200);
                return;
            }

            const competitors = window.getActiveCompetitorsForReport();
            if (competitors.length === 0) {
                setTimeout(renderDynamicContent, 200);
                return;
            }

            // Load scores from database via DAL (from scorecard calculation)
            try {
                const scoresResult = await CRMT.dal.getScores('5.1');
                const scores = scoresResult?.data || [];
                if (scores.length > 0) {
                    competitors.forEach(c => {
                        const scoreData = scores.find(s => s.competitor_id === c.id);
                        if (scoreData && scoreData.sections) {
                            const ghostCosts = scoreData.sections.ghostCosts?.metadata || {};
                            const revenueLift = scoreData.sections.revenueLift?.metadata || {};
                            const techConsolidation = scoreData.sections.techConsolidation?.metadata || {};
                            const riskExposure = scoreData.sections.riskExposure?.metadata || {};

                            c.valuation = {
                                ebitdaImpact: (ghostCosts.total || 0) + (revenueLift.total || 0) + (techConsolidation.total || 0),
                                ghostCosts: {
                                    total: ghostCosts.total ?? 0,
                                    unusedLicenses: ghostCosts.unusedLicenses ?? 0,
                                    duplicateTools: ghostCosts.duplicateTools ?? 0
                                },
                                revenueLift: {
                                    total: revenueLift.total ?? 0,
                                    cartAbandonment: revenueLift.cartAbandonment ?? 0,
                                    winbackFlows: revenueLift.winbackFlows ?? 0
                                },
                                techConsolidation: {
                                    total: techConsolidation.total ?? 0,
                                    espCrmOverlap: techConsolidation.espCrmOverlap ?? 0,
                                    cpaasSavings: techConsolidation.cpaasSavings ?? 0
                                },
                                riskExposure: {
                                    total: riskExposure.total ?? 0,
                                    complianceGaps: riskExposure.complianceGaps ?? 0,
                                    dataPrivacy: riskExposure.dataPrivacy ?? 0
                                }
                            };
                        }
                    });
                    console.log('[5.1] Loaded', scores.length, 'scores from database');
                }
            } catch (e) {
                console.warn('[5.1] Could not load scores from DB:', e.message);
            }

            // Render table using shared renderer
            window.CRMT.renderTable('#dynamic-tbody', '#competitor-header-row', REPORT_CONFIG, competitors);

            // Update summary cards
            updateSummaryCards(competitors);

            console.log('[5.1] Dynamic content rendered for', competitors.length, 'competitors');
        }

        function initDynamicContent() {
            if (window.setupGroupChangeListener) window.setupGroupChangeListener(renderDynamicContent);
            else {
                document.addEventListener('DOMContentLoaded', () => setTimeout(renderDynamicContent, 500));
                window.addEventListener('competitorGroupActivated', renderDynamicContent);
            }
        }

        if (window.CRMT) initDynamicContent();
        else {
            window.addEventListener('crmtReady', initDynamicContent, { once: true });
            setTimeout(initDynamicContent, 1000);
        }
    </script>
@endpush
