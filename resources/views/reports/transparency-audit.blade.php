@extends('layouts.dashboard')


@section('title', 'Report 4.3: Transparency Audit | CRMTracker')

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
module: '4.3',
title: 'Transparency Audit',
category: 'Regulatory',
isBeta: false,
stubData: ['AML Messaging Depth', 'Rollover Clarity Analysis']
});
}
});
</script>
<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
<div class="bg-emerald-50 rounded-xl p-5 border border-emerald-200">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-star text-emerald-500"></i>
<span class="text-xs font-bold text-emerald-800 uppercase">Top Score</span>
</div>
<p class="text-2xl font-bold text-emerald-700" id="top-score">-</p>
<p class="text-sm text-emerald-600" id="top-score-value">-/100</p>
</div>
<div class="bg-blue-50 rounded-xl p-5 border border-blue-200">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-credit-card text-blue-500"></i>
<span class="text-xs font-bold text-blue-800 uppercase">Payment Score</span>
</div>
<p class="text-2xl font-bold text-blue-700" id="payment-avg">-/10</p>
<p class="text-sm text-blue-600">Industry avg</p>
</div>
<div class="bg-violet-50 rounded-xl p-5 border border-violet-200">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-gift text-violet-500"></i>
<span class="text-xs font-bold text-violet-800 uppercase">Offer Transparency</span>
</div>
<p class="text-2xl font-bold text-violet-700" id="offer-avg">-/10</p>
<p class="text-sm text-violet-600">Industry avg</p>
</div>
<div class="bg-rose-50 rounded-xl p-5 border border-rose-200">
<div class="flex items-center gap-2 mb-2">
<i class="fa-solid fa-shield text-rose-500"></i>
<span class="text-xs font-bold text-rose-800 uppercase">Blacklist Status</span>
</div>
<p class="text-2xl font-bold text-rose-700" id="blacklist-clean">-</p>
<p class="text-sm text-rose-600">Clean competitors</p>
</div>
</div>
<!-- Legend -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 mb-6">
<div class="flex flex-wrap items-center justify-between gap-4">
<div class="flex items-center gap-6 text-xs">
<span class="font-bold text-slate-600 uppercase tracking-wide">Sections:</span>
<span class="px-2 py-1 rounded bg-blue-100 text-blue-700">Payment Providers</span>
<span class="px-2 py-1 rounded bg-violet-100 text-violet-700">Offer Transparency</span>
<span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700">AML Messaging</span>
<span class="px-2 py-1 rounded bg-rose-100 text-rose-700">Blacklist Status</span>
</div>
</div>
</div>
<!-- Main Transparency Table -->
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
// Report 4.3 Configuration - Transparency Audit
// Data source: transparency.* in competitorData.js
const REPORT_CONFIG = {
sections: [
{
id: 'paymentProviders',
title: 'Payment Providers',
subtitle: '(Accepted methods, clarity)',
icon: 'fa-solid fa-credit-card',
colorScheme: 'blue',
rows: [
{ label: 'Section Score', key: 'transparency.paymentProviders.score', type: 'score' },
{ label: 'Visa Accepted', key: 'transparency.paymentProviders.visa', type: 'bool' },
{ label: 'Mastercard Accepted', key: 'transparency.paymentProviders.mastercard', type: 'bool' },
{ label: 'Crypto Accepted', key: 'transparency.paymentProviders.crypto', type: 'bool' },
{ label: 'E-Wallet', key: 'transparency.paymentProviders.ewallet', type: 'bool' },
{ label: 'Interac', key: 'transparency.paymentProviders.interac', type: 'bool' }
]
},
{
id: 'offerTransparency',
title: 'Offer Transparency',
subtitle: '(T&C clarity, rollover disclosure)',
icon: 'fa-solid fa-gift',
colorScheme: 'violet',
rows: [
{ label: 'Section Score', key: 'transparency.offerTransparency.score', type: 'score' },
{ label: 'T&C Score', key: 'transparency.offerTransparency.tcScore', type: 'pct' },
{ label: 'Rollover Disclosed', key: 'transparency.offerTransparency.rolloverDisclosure', type: 'bool' },
{ label: 'Bogo Terms Clear', key: 'transparency.offerTransparency.bogoTermsClear', type: 'bool' }
]
},
{
id: 'amlMessaging',
title: 'AML Messaging',
subtitle: '(Banking, finance, gambling disclosures)',
icon: 'fa-solid fa-building-columns',
colorScheme: 'emerald',
rows: [
{ label: 'Section Score', key: 'transparency.amlMessaging.score', type: 'score' },
{ label: 'Banking Disclosure', key: 'transparency.amlMessaging.banking', type: 'bool' },
{ label: 'Finance Disclosure', key: 'transparency.amlMessaging.finance', type: 'bool' },
{ label: 'Gambling Disclosure', key: 'transparency.amlMessaging.gambling', type: 'bool' },
{ label: 'Crypto Disclosure', key: 'transparency.amlMessaging.crypto', type: 'bool' }
]
},
{
id: 'blacklistStatus',
title: 'Blacklist Status',
subtitle: '(Industry reputation)',
icon: 'fa-solid fa-shield',
colorScheme: 'rose',
rows: [
{ label: 'Section Score', key: 'transparency.blacklistStatus.score', type: 'score' },
{ label: 'Status', key: 'transparency.blacklistStatus.status', type: 'text' }
]
}
]
};
function updateSummaryCards(competitors) {
let topScore = { name: '-', score: 0 };
let totalPayment = 0, totalOffer = 0, cleanCount = 0;
competitors.forEach(c => {
const t = c.transparency || {};
if (t.totalScore > topScore.score) {
topScore = { name: c.shortName || c.name, score: t.totalScore };
}
if (t.paymentProviders?.score) totalPayment += t.paymentProviders.score;
if (t.offerTransparency?.score) totalOffer += t.offerTransparency.score;
if (t.blacklistStatus?.status === 'clean') cleanCount++;
});
const count = competitors.length || 1;
document.getElementById('top-score').textContent = topScore.name;
document.getElementById('top-score-value').textContent = topScore.score + '/100';
document.getElementById('payment-avg').textContent = (totalPayment / count).toFixed(1) + '/10';
document.getElementById('offer-avg').textContent = (totalOffer / count).toFixed(1) + '/10';
document.getElementById('blacklist-clean').textContent = cleanCount + '/' + competitors.length;
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
console.log('[4.3] Dynamic content rendered for', competitors.length, 'competitors');
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
                            module: '4.3',
                            title: 'Transparency Audit',
                            category: 'Regulatory',
                            isBeta: false,
                            stubData: ['AML Messaging Depth', 'Rollover Clarity Analysis']
                        });
                    }
                });
            </script>
<script>
        // Report 4.3 Configuration - Transparency Audit
        // Data source: transparency.* in competitorData.js
        const REPORT_CONFIG = {
            sections: [
                {
                    id: 'paymentProviders',
                    title: 'Payment Providers',
                    subtitle: '(Accepted methods, clarity)',
                    icon: 'fa-solid fa-credit-card',
                    colorScheme: 'blue',
                    rows: [
                        { label: 'Section Score', key: 'transparency.paymentProviders.score', type: 'score' },
                        { label: 'Visa Accepted', key: 'transparency.paymentProviders.visa', type: 'bool' },
                        { label: 'Mastercard Accepted', key: 'transparency.paymentProviders.mastercard', type: 'bool' },
                        { label: 'Crypto Accepted', key: 'transparency.paymentProviders.crypto', type: 'bool' },
                        { label: 'E-Wallet', key: 'transparency.paymentProviders.ewallet', type: 'bool' },
                        { label: 'Interac', key: 'transparency.paymentProviders.interac', type: 'bool' }
                    ]
                },
                {
                    id: 'offerTransparency',
                    title: 'Offer Transparency',
                    subtitle: '(T&C clarity, rollover disclosure)',
                    icon: 'fa-solid fa-gift',
                    colorScheme: 'violet',
                    rows: [
                        { label: 'Section Score', key: 'transparency.offerTransparency.score', type: 'score' },
                        { label: 'T&C Score', key: 'transparency.offerTransparency.tcScore', type: 'pct' },
                        { label: 'Rollover Disclosed', key: 'transparency.offerTransparency.rolloverDisclosure', type: 'bool' },
                        { label: 'Bogo Terms Clear', key: 'transparency.offerTransparency.bogoTermsClear', type: 'bool' }
                    ]
                },
                {
                    id: 'amlMessaging',
                    title: 'AML Messaging',
                    subtitle: '(Banking, finance, gambling disclosures)',
                    icon: 'fa-solid fa-building-columns',
                    colorScheme: 'emerald',
                    rows: [
                        { label: 'Section Score', key: 'transparency.amlMessaging.score', type: 'score' },
                        { label: 'Banking Disclosure', key: 'transparency.amlMessaging.banking', type: 'bool' },
                        { label: 'Finance Disclosure', key: 'transparency.amlMessaging.finance', type: 'bool' },
                        { label: 'Gambling Disclosure', key: 'transparency.amlMessaging.gambling', type: 'bool' },
                        { label: 'Crypto Disclosure', key: 'transparency.amlMessaging.crypto', type: 'bool' }
                    ]
                },
                {
                    id: 'blacklistStatus',
                    title: 'Blacklist Status',
                    subtitle: '(Industry reputation)',
                    icon: 'fa-solid fa-shield',
                    colorScheme: 'rose',
                    rows: [
                        { label: 'Section Score', key: 'transparency.blacklistStatus.score', type: 'score' },
                        { label: 'Status', key: 'transparency.blacklistStatus.status', type: 'text' }
                    ]
                }
            ]
        };

        function updateSummaryCards(competitors) {
            let topScore = { name: '-', score: 0 };
            let totalPayment = 0, totalOffer = 0, cleanCount = 0;

            competitors.forEach(c => {
                const t = c.transparency || {};

                if (t.totalScore > topScore.score) {
                    topScore = { name: c.shortName || c.name, score: t.totalScore };
                }

                if (t.paymentProviders?.score) totalPayment += t.paymentProviders.score;
                if (t.offerTransparency?.score) totalOffer += t.offerTransparency.score;
                if (t.blacklistStatus?.status === 'clean') cleanCount++;
            });

            const count = competitors.length || 1;
            document.getElementById('top-score').textContent = topScore.name;
            document.getElementById('top-score-value').textContent = topScore.score + '/100';
            document.getElementById('payment-avg').textContent = (totalPayment / count).toFixed(1) + '/10';
            document.getElementById('offer-avg').textContent = (totalOffer / count).toFixed(1) + '/10';
            document.getElementById('blacklist-clean').textContent = cleanCount + '/' + competitors.length;
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

            console.log('[4.3] Dynamic content rendered for', competitors.length, 'competitors');
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
