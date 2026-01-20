@extends('layouts.dashboard')


@section('title', 'Report 5.2: Compliance Exposure | CRMTracker')


@section('content')
<main class="flex-1 ml-20 p-8 max-w-[1600px] mx-auto overflow-x-hidden">
<!-- Header injected by reportTemplate.js -->
<div id="report-header"></div>
<script>
document.addEventListener('DOMContentLoaded', () => {
if (window.CRMT?.renderReportHeader) {
CRMT.renderReportHeader('#report-header', {
module: '5.2',
title: 'Compliance Exposure',
category: 'Enterprise',
isBeta: true,
stubData: ['Operational Efficiency', 'Brand Equity']
});
}
});
</script>
<!-- Legend -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 mb-6">
<div class="flex flex-wrap items-center justify-between gap-4">
<div class="flex items-center gap-6 text-xs">
<span class="font-bold text-slate-600 uppercase tracking-wide">Sections:</span>
<span class="px-2 py-1 rounded bg-red-100 text-red-700">Risk Exposure</span>
<span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700">CLV Forecast</span>
<span class="px-2 py-1 rounded bg-amber-100 text-amber-700">Churn Breakdown</span>
<span class="px-2 py-1 rounded bg-blue-100 text-blue-700">Operational</span>
<span class="px-2 py-1 rounded bg-purple-100 text-purple-700">Brand Equity</span>
</div>
<div class="flex items-center gap-3 text-xs">
<span class="font-bold text-slate-600 uppercase tracking-wide">Score:</span>
<div class="flex items-center gap-2">
<span class="w-3 h-3 rounded-full bg-green-500"></span>
<span class="text-slate-600">Strong (8-10)</span>
</div>
<div class="flex items-center gap-2">
<span class="w-3 h-3 rounded-full bg-amber-500"></span>
<span class="text-slate-600">Moderate (5-7)</span>
</div>
<div class="flex items-center gap-2">
<span class="w-3 h-3 rounded-full bg-red-500"></span>
<span class="text-slate-600">Weak (0-4)</span>
</div>
</div>
</div>
</div>
<!-- Main Scorecard Table -->
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
<!-- Formula Note -->
<div class="bg-slate-50 rounded-lg p-4 text-xs text-slate-500 border border-slate-200 mt-6">
<span class="font-bold text-slate-600">Formula:</span>
CRMTracker Score = (Risk Exposure × 0.30) + (CLV × 0.25) + (Churn × 0.25) + (Efficiency × 0.10) + (Brand
Equity × 0.10)
</div>
<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
<div class="bg-red-50 rounded-xl p-5 border border-red-200">
<div class="flex items-center gap-2 mb-3">
<i class="fa-solid fa-shield-halved text-red-500"></i>
<span class="font-bold text-red-800">Risk Exposure</span>
</div>
<p class="text-2xl font-bold text-red-700 mb-1" id="risk-score">-</p>
<p class="text-sm text-red-600">Avg compliance score</p>
</div>
<div class="bg-emerald-50 rounded-xl p-5 border border-emerald-200">
<div class="flex items-center gap-2 mb-3">
<i class="fa-solid fa-chart-line text-emerald-500"></i>
<span class="font-bold text-emerald-800">CLV Forecast</span>
</div>
<p class="text-2xl font-bold text-emerald-700 mb-1" id="clv-score">-</p>
<p class="text-sm text-emerald-600">Avg engagement score</p>
</div>
<div class="bg-amber-50 rounded-xl p-5 border border-amber-200">
<div class="flex items-center gap-2 mb-3">
<i class="fa-solid fa-user-clock text-amber-500"></i>
<span class="font-bold text-amber-800">Churn Risk</span>
</div>
<p class="text-2xl font-bold text-amber-700 mb-1" id="churn-score">-</p>
<p class="text-sm text-amber-600">Avg journey coverage</p>
</div>
<div class="bg-violet-50 rounded-xl p-5 border border-violet-200">
<div class="flex items-center gap-2 mb-3">
<i class="fa-solid fa-star text-violet-500"></i>
<span class="font-bold text-violet-800">CRMTracker Score</span>
</div>
<p class="text-2xl font-bold text-violet-700 mb-1" id="total-score">-</p>
<p class="text-sm text-violet-600">Weighted average</p>
</div>
</div>
</main>
</div>
<script>
// Report 5.2 Configuration - Compliance Exposure (Due Diligence)
const REPORT_CONFIG = {
sections: [
{
id: 'riskExposure',
title: 'Risk Exposure Report',
subtitle: '(Compliance + Regulatory + Data Handling)',
icon: 'fa-solid fa-shield-exclamation',
colorScheme: 'red',
rows: [
{ label: 'Footer Compliance', key: 'compliance.footerPresent.pct', type: 'pct' },
{ label: 'Unsubscribe Present', key: 'compliance.unsubscribeLink.pct', type: 'pct' },
{ label: 'T&C Present', key: 'compliance.legalDisclaimer.pct', type: 'pct' },
{ label: 'Risk Exposure Score', key: 'dueDiligence.riskExposure.sectionScore', type: 'score', isTotal: true }
]
},
{
id: 'clvForecast',
title: 'Customer Lifecycle Value Forecast',
subtitle: '(Offer Density + Saturation)',
icon: 'fa-solid fa-chart-line',
colorScheme: 'emerald',
rows: [
{ label: 'Promo Rate %', key: 'content.offerDensity', type: 'pct' },
{ label: 'Dominant Offer Type', key: 'content.dominantOfferType', type: 'text' },
{ label: 'Weekly Frequency', key: 'crmScorecard.frequency.metadata.weeklyAvg', type: 'number' },
{ label: 'CLV Score', key: 'dueDiligence.clvForecast.sectionScore', type: 'score', isTotal: true }
]
},
{
id: 'churnBreakdown',
title: 'Churn Type Breakdown',
subtitle: '(Churn, Lapse, Reactivation, Winback)',
icon: 'fa-solid fa-users-slash',
colorScheme: 'amber',
rows: [
{ label: 'Journey Coverage', key: 'crmScorecard.journey.metadata.coverage', type: 'number' },
{ label: 'Reactivation Signals', key: 'crmScorecard.journey.metadata.hasRetention', type: 'text' },
{ label: 'Winback Potential', key: 'crmScorecard.journey.metadata.hasWinback', type: 'text' },
{ label: 'Churn Risk Score', key: 'dueDiligence.churnRisk.sectionScore', type: 'score', isTotal: true }
]
},
{
id: 'operationalEfficiency',
title: 'Operational Efficiency Red Flags',
subtitle: '(Headcount, Duplication, Dependency)',
icon: 'fa-solid fa-gears',
colorScheme: 'blue',
isStub: true,
rows: [
{ label: 'Number of Brands', key: 'dueDiligence.operational.brandCount', type: 'number', isStub: true },
{ label: 'Tool Duplication', key: 'dueDiligence.operational.toolDuplication', type: 'text', isStub: true },
{ label: 'Efficiency Score', key: 'dueDiligence.operational.sectionScore', type: 'score', isTotal: true, isStub: true }
]
},
{
id: 'brandEquity',
title: 'Brand Equity Intelligence',
subtitle: '(Tone, Sentiment, Positioning)',
icon: 'fa-solid fa-star',
colorScheme: 'purple',
isStub: true,
rows: [
{ label: 'Brand Sentiment', key: 'dueDiligence.brandEquity.sentiment', type: 'text', isStub: true },
{ label: 'Competitive Position', key: 'dueDiligence.brandEquity.position', type: 'text', isStub: true },
{ label: 'Brand Equity Score', key: 'dueDiligence.brandEquity.sectionScore', type: 'score', isTotal: true, isStub: true }
]
}
]
};
function updateSummaryCards(competitors) {
let riskTotal = 0, clvTotal = 0, churnTotal = 0, count = 0;
competitors.forEach(c => {
const comp = c.compliance || {};
const scores = c.scores || {};
// Risk from compliance
let riskScore = 0;
if (comp.footerPresent?.pct) riskScore += comp.footerPresent.pct / 100 * 3;
if (comp.unsubscribeLink?.pct) riskScore += comp.unsubscribeLink.pct / 100 * 4;
if (comp.legalDisclaimer?.pct) riskScore += comp.legalDisclaimer.pct / 100 * 3;
riskTotal += riskScore;
// CLV from frequency
const freq = scores?.frequency?.metadata || {};
clvTotal += Math.min((freq.weeklyAvg || 0) * 2, 10);
// Churn from journey
const journey = scores?.journey?.metadata || {};
let journeyScore = 0;
if (journey.welcome) journeyScore += 2;
if (journey.day3) journeyScore += 2;
if (journey.day7) journeyScore += 2;
if (journey.retention) journeyScore += 2;
if (journey.winback) journeyScore += 2;
churnTotal += journeyScore;
count++;
});
if (count > 0) {
document.getElementById('risk-score').textContent = (riskTotal / count).toFixed(1);
document.getElementById('clv-score').textContent = (clvTotal / count).toFixed(1);
document.getElementById('churn-score').textContent = (churnTotal / count).toFixed(1);
const total = ((riskTotal * 0.30) + (clvTotal * 0.25) + (churnTotal * 0.25)) / count;
document.getElementById('total-score').textContent = total.toFixed(1);
}
}
function renderDynamicContent() {
if (!window.CRMT?.renderTable || !window.getActiveCompetitorsForReport) {
setTimeout(renderDynamicContent, 200);
return;
}
const competitors = window.getActiveCompetitorsForReport();
if (competitors.length === 0) {
setTimeout(renderDynamicContent, 200);
return;
}
// Render table using shared renderer
window.CRMT.renderTable('#dynamic-tbody', '#competitor-header-row', REPORT_CONFIG, competitors);
// Update summary cards
updateSummaryCards(competitors);
console.log('[5.2] Dynamic content rendered for', competitors.length, 'competitors');
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
                            module: '5.2',
                            title: 'Compliance Exposure',
                            category: 'Enterprise',
                            isBeta: true,
                            stubData: ['Operational Efficiency', 'Brand Equity']
                        });
                    }
                });
            </script>
<script>
        // Report 5.2 Configuration - Compliance Exposure (Due Diligence)
        const REPORT_CONFIG = {
            sections: [
                {
                    id: 'riskExposure',
                    title: 'Risk Exposure Report',
                    subtitle: '(Compliance + Regulatory + Data Handling)',
                    icon: 'fa-solid fa-shield-exclamation',
                    colorScheme: 'red',
                    rows: [
                        { label: 'Footer Compliance', key: 'compliance.footerPresent.pct', type: 'pct' },
                        { label: 'Unsubscribe Present', key: 'compliance.unsubscribeLink.pct', type: 'pct' },
                        { label: 'T&C Present', key: 'compliance.legalDisclaimer.pct', type: 'pct' },
                        { label: 'Risk Exposure Score', key: 'dueDiligence.riskExposure.sectionScore', type: 'score', isTotal: true }
                    ]
                },
                {
                    id: 'clvForecast',
                    title: 'Customer Lifecycle Value Forecast',
                    subtitle: '(Offer Density + Saturation)',
                    icon: 'fa-solid fa-chart-line',
                    colorScheme: 'emerald',
                    rows: [
                        { label: 'Promo Rate %', key: 'content.offerDensity', type: 'pct' },
                        { label: 'Dominant Offer Type', key: 'content.dominantOfferType', type: 'text' },
                        { label: 'Weekly Frequency', key: 'crmScorecard.frequency.metadata.weeklyAvg', type: 'number' },
                        { label: 'CLV Score', key: 'dueDiligence.clvForecast.sectionScore', type: 'score', isTotal: true }
                    ]
                },
                {
                    id: 'churnBreakdown',
                    title: 'Churn Type Breakdown',
                    subtitle: '(Churn, Lapse, Reactivation, Winback)',
                    icon: 'fa-solid fa-users-slash',
                    colorScheme: 'amber',
                    rows: [
                        { label: 'Journey Coverage', key: 'crmScorecard.journey.metadata.coverage', type: 'number' },
                        { label: 'Reactivation Signals', key: 'crmScorecard.journey.metadata.hasRetention', type: 'text' },
                        { label: 'Winback Potential', key: 'crmScorecard.journey.metadata.hasWinback', type: 'text' },
                        { label: 'Churn Risk Score', key: 'dueDiligence.churnRisk.sectionScore', type: 'score', isTotal: true }
                    ]
                },
                {
                    id: 'operationalEfficiency',
                    title: 'Operational Efficiency Red Flags',
                    subtitle: '(Headcount, Duplication, Dependency)',
                    icon: 'fa-solid fa-gears',
                    colorScheme: 'blue',
                    isStub: true,
                    rows: [
                        { label: 'Number of Brands', key: 'dueDiligence.operational.brandCount', type: 'number', isStub: true },
                        { label: 'Tool Duplication', key: 'dueDiligence.operational.toolDuplication', type: 'text', isStub: true },
                        { label: 'Efficiency Score', key: 'dueDiligence.operational.sectionScore', type: 'score', isTotal: true, isStub: true }
                    ]
                },
                {
                    id: 'brandEquity',
                    title: 'Brand Equity Intelligence',
                    subtitle: '(Tone, Sentiment, Positioning)',
                    icon: 'fa-solid fa-star',
                    colorScheme: 'purple',
                    isStub: true,
                    rows: [
                        { label: 'Brand Sentiment', key: 'dueDiligence.brandEquity.sentiment', type: 'text', isStub: true },
                        { label: 'Competitive Position', key: 'dueDiligence.brandEquity.position', type: 'text', isStub: true },
                        { label: 'Brand Equity Score', key: 'dueDiligence.brandEquity.sectionScore', type: 'score', isTotal: true, isStub: true }
                    ]
                }
            ]
        };

        function updateSummaryCards(competitors) {
            let riskTotal = 0, clvTotal = 0, churnTotal = 0, count = 0;

            competitors.forEach(c => {
                const comp = c.compliance || {};
                const scores = c.scores || {};

                // Risk from compliance
                let riskScore = 0;
                if (comp.footerPresent?.pct) riskScore += comp.footerPresent.pct / 100 * 3;
                if (comp.unsubscribeLink?.pct) riskScore += comp.unsubscribeLink.pct / 100 * 4;
                if (comp.legalDisclaimer?.pct) riskScore += comp.legalDisclaimer.pct / 100 * 3;
                riskTotal += riskScore;

                // CLV from frequency
                const freq = scores?.frequency?.metadata || {};
                clvTotal += Math.min((freq.weeklyAvg || 0) * 2, 10);

                // Churn from journey
                const journey = scores?.journey?.metadata || {};
                let journeyScore = 0;
                if (journey.welcome) journeyScore += 2;
                if (journey.day3) journeyScore += 2;
                if (journey.day7) journeyScore += 2;
                if (journey.retention) journeyScore += 2;
                if (journey.winback) journeyScore += 2;
                churnTotal += journeyScore;

                count++;
            });

            if (count > 0) {
                document.getElementById('risk-score').textContent = (riskTotal / count).toFixed(1);
                document.getElementById('clv-score').textContent = (clvTotal / count).toFixed(1);
                document.getElementById('churn-score').textContent = (churnTotal / count).toFixed(1);

                const total = ((riskTotal * 0.30) + (clvTotal * 0.25) + (churnTotal * 0.25)) / count;
                document.getElementById('total-score').textContent = total.toFixed(1);
            }
        }

        function renderDynamicContent() {
            if (!window.CRMT?.renderTable || !window.getActiveCompetitorsForReport) {
                setTimeout(renderDynamicContent, 200);
                return;
            }

            const competitors = window.getActiveCompetitorsForReport();
            if (competitors.length === 0) {
                setTimeout(renderDynamicContent, 200);
                return;
            }

            // Render table using shared renderer
            window.CRMT.renderTable('#dynamic-tbody', '#competitor-header-row', REPORT_CONFIG, competitors);

            // Update summary cards
            updateSummaryCards(competitors);

            console.log('[5.2] Dynamic content rendered for', competitors.length, 'competitors');
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
