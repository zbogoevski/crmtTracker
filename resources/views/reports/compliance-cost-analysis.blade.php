@extends('layouts.dashboard')


@section('title', 'Report 4.2: Risk Exposure | CRMTracker')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
            color: #334155;
        }

        .sticky-col {
            position: sticky;
            left: 0;
            z-index: 10;
            background: linear-gradient(to right, #f8fafc 90%, transparent);
        }

        .total-row .sticky-col {
            background: linear-gradient(to right, #7c3aed 0%, #7c3aed 90%, transparent);
        }

        /* Receipt Zig-Zag Effect */
        .receipt-bottom-dark {
            background-image: linear-gradient(135deg, #0f172a 25%, transparent 25%), linear-gradient(225deg, #0f172a 25%, transparent 25%);
            background-position: 0 0;
            background-size: 16px 16px;
            height: 8px;
            width: 100%;
        }

        .font-mono {
            font-family: 'JetBrains Mono', 'Courier New', monospace;
        }
</style>
@endpush

@section('content')
<main class="flex-1 ml-20 p-8 max-w-[1600px] mx-auto overflow-x-hidden">
<!-- Header injected by reportTemplate.js -->
<div id="report-header"></div>
<script>
document.addEventListener('DOMContentLoaded', () => {
if (window.CRMT?.renderReportHeader) {
CRMT.renderReportHeader('#report-header', {
module: '4.2',
title: 'Risk Exposure',
category: 'Regulatory',
isBeta: true,
stubData: ['RG Messaging Depth', 'Exposure Calculations']
});
}
});
</script>
<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
<div class="bg-emerald-50 rounded-xl p-5 border border-emerald-200">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-shield-check text-emerald-500"></i>
<span class="text-xs font-bold text-emerald-800 uppercase">Lowest Risk</span>
</div>
<p class="text-2xl font-bold text-emerald-700" id="lowest-risk">-</p>
<p class="text-sm text-emerald-600" id="lowest-risk-score">Score: -</p>
</div>
<div class="bg-rose-50 rounded-xl p-5 border border-rose-200">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-triangle-exclamation text-rose-500"></i>
<span class="text-xs font-bold text-rose-800 uppercase">Highest Exposure</span>
</div>
<p class="text-2xl font-bold text-rose-700" id="highest-exposure">-</p>
<p class="text-sm text-rose-600" id="highest-exposure-value">€-</p>
</div>
<div class="bg-blue-50 rounded-xl p-5 border border-blue-200">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-scale-balanced text-blue-500"></i>
<span class="text-xs font-bold text-blue-800 uppercase">License Compliant</span>
</div>
<p class="text-2xl font-bold text-blue-700" id="license-compliant">-/-</p>
<p class="text-sm text-blue-600">Competitors</p>
</div>
<div class="bg-violet-50 rounded-xl p-5 border border-violet-200">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-envelope-open text-violet-500"></i>
<span class="text-xs font-bold text-violet-800 uppercase">Unsubscribe OK</span>
</div>
<p class="text-2xl font-bold text-violet-700" id="unsub-compliant">-/-</p>
<p class="text-sm text-violet-600">Competitors</p>
</div>
</div>
<!-- Legend -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 mb-6">
<div class="flex flex-wrap items-center justify-between gap-4">
<div class="flex items-center gap-6 text-xs">
<span class="font-bold text-slate-600 uppercase tracking-wide">Risk Factors:</span>
<span class="px-2 py-1 rounded bg-blue-100 text-blue-700">License</span>
<span class="px-2 py-1 rounded bg-violet-100 text-violet-700">Unsubscribe</span>
<span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700">RG Messaging</span>
<span class="px-2 py-1 rounded bg-rose-100 text-rose-700">Exposure</span>
</div>
<div class="text-xs text-slate-500">
<i class="fa-solid fa-info-circle mr-1"></i>
Risk Score: 0-100 (higher = better)
</div>
</div>
</div>
<!-- Receipt Cards - Bills Style -->
<div id="receipt-cards-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
<!-- Receipt cards generated dynamically by JS -->
</div>
<!-- Main Risk Table -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
<div class="overflow-x-auto">
<table class="w-full text-sm min-w-[900px]">
<thead>
<tr class="border-b-2 border-slate-300" id="competitor-header-row">
<th
class="sticky-col text-left py-4 px-4 font-bold text-slate-600 uppercase text-xs tracking-wider bg-slate-50 min-w-[200px]">
Metric
</th>
<th
class="text-left py-4 px-4 font-bold text-slate-600 uppercase text-xs tracking-wider bg-slate-50 min-w-[120px]">
Owner
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
// Report 4.2 Configuration - Risk Exposure
// Data source: risk.* in competitorData.js
const REPORT_CONFIG = {
showOwnerColumn: true,  // Enable Owner column for this report
sections: [
{
id: 'riskFactors',
title: 'Risk Factors',
subtitle: '(Compliance status)',
icon: 'fa-solid fa-shield-halved',
colorScheme: 'rose',
rows: [
{ label: 'Risk Score', key: 'risk.riskScore', type: 'score', suffix: '/100', owner: 'Risk Team' },
{ label: 'License Compliant', key: 'risk.licenseCompliant', type: 'bool', owner: 'Compliance Team' },
{ label: 'Unsubscribe Compliant', key: 'risk.unsubscribeCompliant', type: 'bool', owner: 'CRM Team' },
{ label: 'RG Messaging', key: 'risk.rgMessaging', type: 'bool', owner: 'Compliance Team' },
{ label: 'Total Exposure', key: 'risk.totalExposure', type: 'currency', prefix: '€', divisor: 1000, suffix: 'k', owner: 'Risk Team' }
]
}
]
};
function updateSummaryCards(competitors) {
let lowestRisk = { name: '-', score: 0 };
let highestExposure = { name: '-', exposure: 0 };
let licenseCount = 0, unsubCount = 0;
competitors.forEach(c => {
const r = c.risk || {};
if (r.riskScore > lowestRisk.score) {
lowestRisk = { name: c.shortName || c.name, score: r.riskScore };
}
if (r.totalExposure > highestExposure.exposure) {
highestExposure = { name: c.shortName || c.name, exposure: r.totalExposure };
}
if (r.licenseCompliant) licenseCount++;
if (r.unsubscribeCompliant) unsubCount++;
});
document.getElementById('lowest-risk').textContent = lowestRisk.name;
document.getElementById('lowest-risk-score').textContent = 'Score: ' + lowestRisk.score + '/100';
document.getElementById('highest-exposure').textContent = highestExposure.name;
document.getElementById('highest-exposure-value').textContent = '€' + (highestExposure.exposure / 1000).toFixed(0) + 'k';
document.getElementById('license-compliant').textContent = licenseCount + '/' + competitors.length;
document.getElementById('unsub-compliant').textContent = unsubCount + '/' + competitors.length;
}
// Bills-style receipt cards for visual risk display
function renderReceiptCards(competitors) {
const container = document.getElementById('receipt-cards-container');
if (!container) return;
// Update grid columns based on competitor count
const colCount = Math.min(competitors.length, 7);
container.className = `grid grid-cols-1 md:grid-cols-2 lg:grid-cols-${colCount} gap-4 mb-8`;
container.innerHTML = competitors.map(c => {
const risk = c.risk || {};
const license = c.license || {};
const hasLicense = risk.licenseCompliant || license.verified;
const hasUnsub = risk.unsubscribeCompliant !== false;
const hasRG = risk.rgMessaging !== false;
// Calculate risk level
const exposure = risk.totalExposure || 0;
let riskBg = 'bg-green-600', riskLabel = '✓ Lowest Risk';
if (exposure >= 500000) { riskBg = 'bg-red-700'; riskLabel = 'Highest Risk'; }
else if (exposure >= 300000) { riskBg = 'bg-red-600'; riskLabel = 'High Risk'; }
else if (exposure > 0) { riskBg = 'bg-amber-500'; riskLabel = 'Medium Risk'; }
return `
<div class="bg-white rounded-t-lg shadow-lg overflow-hidden transform hover:-translate-y-1 transition-transform ${exposure === 0 ? 'ring-2 ring-green-400' : ''}">
<div class="h-2" style="background: ${c.color || '#6366f1'}"></div>
<div class="p-4">
<div class="text-center border-b border-dashed border-slate-200 pb-3 mb-3">
<h3 class="font-bold text-slate-800 uppercase tracking-wider text-sm">${c.shortName || c.name}</h3>
<p class="text-[10px] text-slate-400 font-mono">REF: #CRMT-4.2-${(c.shortName || c.name).substring(0, 3).toUpperCase()}</p>
</div>
<div class="space-y-2 font-mono text-xs">
<div class="flex justify-between items-center">
<span class="text-slate-600">License</span>
<span class="${hasLicense ? 'text-green-600' : 'text-red-600'} font-bold">${hasLicense ? '✓' : '✗'}</span>
</div>
<div class="text-[10px] ${hasLicense ? 'text-green-500' : 'text-red-500'} pl-2">${hasLicense ? 'Licensed' : '€50k–200k risk'}</div>
<div class="flex justify-between items-center">
<span class="text-slate-600">Unsubscribe</span>
<span class="${hasUnsub ? 'text-green-600' : 'text-red-600'} font-bold">${hasUnsub ? '✓' : '✗'}</span>
</div>
<div class="text-[10px] ${hasUnsub ? 'text-green-500' : 'text-red-500'} pl-2">${hasUnsub ? '100% compliant' : '€100k–500k risk'}</div>
<div class="flex justify-between items-center">
<span class="text-slate-600">RG Messaging</span>
<span class="${hasRG ? 'text-green-600' : 'text-red-600'} font-bold">${hasRG ? '✓' : '✗'}</span>
</div>
<div class="text-[10px] ${hasRG ? 'text-green-500' : 'text-red-500'} pl-2">${hasRG ? 'Present' : '€25k–100k risk'}</div>
</div>
<div class="border-t border-dashed border-slate-300 mt-3 pt-3">
<div class="flex justify-between items-center font-bold text-sm">
<span class="text-slate-700">TOTAL RISK</span>
<span class="${exposure === 0 ? 'text-green-600' : exposure >= 500000 ? 'text-red-600' : 'text-amber-600'}">€${exposure > 0 ? (exposure / 1000).toFixed(0) + 'k' : '0'}</span>
</div>
</div>
</div>
<div class="${riskBg} p-2 text-center">
<span class="text-white text-xs font-bold uppercase">${riskLabel}</span>
</div>
<div class="receipt-bottom-dark"></div>
</div>`;
}).join('');
console.log('[4.2] Receipt cards rendered for', competitors.length, 'competitors');
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
// Render bills-style receipt cards
renderReceiptCards(competitors);
console.log('[4.2] Dynamic content rendered for', competitors.length, 'competitors');
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
                            module: '4.2',
                            title: 'Risk Exposure',
                            category: 'Regulatory',
                            isBeta: true,
                            stubData: ['RG Messaging Depth', 'Exposure Calculations']
                        });
                    }
                });
            </script>
<script>
        // Report 4.2 Configuration - Risk Exposure
        // Data source: risk.* in competitorData.js
        const REPORT_CONFIG = {
            showOwnerColumn: true,  // Enable Owner column for this report
            sections: [
                {
                    id: 'riskFactors',
                    title: 'Risk Factors',
                    subtitle: '(Compliance status)',
                    icon: 'fa-solid fa-shield-halved',
                    colorScheme: 'rose',
                    rows: [
                        { label: 'Risk Score', key: 'risk.riskScore', type: 'score', suffix: '/100', owner: 'Risk Team' },
                        { label: 'License Compliant', key: 'risk.licenseCompliant', type: 'bool', owner: 'Compliance Team' },
                        { label: 'Unsubscribe Compliant', key: 'risk.unsubscribeCompliant', type: 'bool', owner: 'CRM Team' },
                        { label: 'RG Messaging', key: 'risk.rgMessaging', type: 'bool', owner: 'Compliance Team' },
                        { label: 'Total Exposure', key: 'risk.totalExposure', type: 'currency', prefix: '€', divisor: 1000, suffix: 'k', owner: 'Risk Team' }
                    ]
                }
            ]
        };

        function updateSummaryCards(competitors) {
            let lowestRisk = { name: '-', score: 0 };
            let highestExposure = { name: '-', exposure: 0 };
            let licenseCount = 0, unsubCount = 0;

            competitors.forEach(c => {
                const r = c.risk || {};

                if (r.riskScore > lowestRisk.score) {
                    lowestRisk = { name: c.shortName || c.name, score: r.riskScore };
                }

                if (r.totalExposure > highestExposure.exposure) {
                    highestExposure = { name: c.shortName || c.name, exposure: r.totalExposure };
                }

                if (r.licenseCompliant) licenseCount++;
                if (r.unsubscribeCompliant) unsubCount++;
            });

            document.getElementById('lowest-risk').textContent = lowestRisk.name;
            document.getElementById('lowest-risk-score').textContent = 'Score: ' + lowestRisk.score + '/100';
            document.getElementById('highest-exposure').textContent = highestExposure.name;
            document.getElementById('highest-exposure-value').textContent = '€' + (highestExposure.exposure / 1000).toFixed(0) + 'k';
            document.getElementById('license-compliant').textContent = licenseCount + '/' + competitors.length;
            document.getElementById('unsub-compliant').textContent = unsubCount + '/' + competitors.length;
        }

        // Bills-style receipt cards for visual risk display
        function renderReceiptCards(competitors) {
            const container = document.getElementById('receipt-cards-container');
            if (!container) return;

            // Update grid columns based on competitor count
            const colCount = Math.min(competitors.length, 7);
            container.className = `grid grid-cols-1 md:grid-cols-2 lg:grid-cols-${colCount} gap-4 mb-8`;

            container.innerHTML = competitors.map(c => {
                const risk = c.risk || {};
                const license = c.license || {};
                const hasLicense = risk.licenseCompliant || license.verified;
                const hasUnsub = risk.unsubscribeCompliant !== false;
                const hasRG = risk.rgMessaging !== false;

                // Calculate risk level
                const exposure = risk.totalExposure || 0;
                let riskBg = 'bg-green-600', riskLabel = '✓ Lowest Risk';
                if (exposure >= 500000) { riskBg = 'bg-red-700'; riskLabel = 'Highest Risk'; }
                else if (exposure >= 300000) { riskBg = 'bg-red-600'; riskLabel = 'High Risk'; }
                else if (exposure > 0) { riskBg = 'bg-amber-500'; riskLabel = 'Medium Risk'; }

                return `
                <div class="bg-white rounded-t-lg shadow-lg overflow-hidden transform hover:-translate-y-1 transition-transform ${exposure === 0 ? 'ring-2 ring-green-400' : ''}">
                    <div class="h-2" style="background: ${c.color || '#6366f1'}"></div>
                    <div class="p-4">
                        <div class="text-center border-b border-dashed border-slate-200 pb-3 mb-3">
                            <h3 class="font-bold text-slate-800 uppercase tracking-wider text-sm">${c.shortName || c.name}</h3>
                            <p class="text-[10px] text-slate-400 font-mono">REF: #CRMT-4.2-${(c.shortName || c.name).substring(0, 3).toUpperCase()}</p>
                        </div>
                        <div class="space-y-2 font-mono text-xs">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-600">License</span>
                                <span class="${hasLicense ? 'text-green-600' : 'text-red-600'} font-bold">${hasLicense ? '✓' : '✗'}</span>
                            </div>
                            <div class="text-[10px] ${hasLicense ? 'text-green-500' : 'text-red-500'} pl-2">${hasLicense ? 'Licensed' : '€50k–200k risk'}</div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-slate-600">Unsubscribe</span>
                                <span class="${hasUnsub ? 'text-green-600' : 'text-red-600'} font-bold">${hasUnsub ? '✓' : '✗'}</span>
                            </div>
                            <div class="text-[10px] ${hasUnsub ? 'text-green-500' : 'text-red-500'} pl-2">${hasUnsub ? '100% compliant' : '€100k–500k risk'}</div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-slate-600">RG Messaging</span>
                                <span class="${hasRG ? 'text-green-600' : 'text-red-600'} font-bold">${hasRG ? '✓' : '✗'}</span>
                            </div>
                            <div class="text-[10px] ${hasRG ? 'text-green-500' : 'text-red-500'} pl-2">${hasRG ? 'Present' : '€25k–100k risk'}</div>
                        </div>
                        <div class="border-t border-dashed border-slate-300 mt-3 pt-3">
                            <div class="flex justify-between items-center font-bold text-sm">
                                <span class="text-slate-700">TOTAL RISK</span>
                                <span class="${exposure === 0 ? 'text-green-600' : exposure >= 500000 ? 'text-red-600' : 'text-amber-600'}">€${exposure > 0 ? (exposure / 1000).toFixed(0) + 'k' : '0'}</span>
                            </div>
                        </div>
                    </div>
                    <div class="${riskBg} p-2 text-center">
                        <span class="text-white text-xs font-bold uppercase">${riskLabel}</span>
                    </div>
                    <div class="receipt-bottom-dark"></div>
                </div>`;
            }).join('');

            console.log('[4.2] Receipt cards rendered for', competitors.length, 'competitors');
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

            // Render bills-style receipt cards
            renderReceiptCards(competitors);

            console.log('[4.2] Dynamic content rendered for', competitors.length, 'competitors');
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
