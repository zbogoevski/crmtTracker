@extends('layouts.dashboard')


@section('title', 'Report 4.1: License & Entity Verification | CRMTracker')


        .score-good {
            color: #22c55e;
        }

        .score-mid {
            color: #f59e0b;
        }

        .score-bad {
            color: #ef4444;
        }

        .section-stub {
            background: repeating-linear-gradient(45deg,
                    #f8fafc,
                    #f8fafc 10px,
                    #f1f5f9 10px,
                    #f1f5f9 20px);
        }

        .entity-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px dashed #cbd5e1;
        }

        .entity-node {
            background: white;
            border: 2px solid #e2e8f0;
            transition: all 0.2s;
        }

        .entity-node:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }

        .entity-connector {
            position: relative;
        }

        .entity-connector::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #cbd5e1;
            border-style: dashed;
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
module: '4.1',
title: 'License & Entity Verification',
category: 'Regulatory',
isBeta: true,
stubData: ['Entity/Ownership (requires Companies House)']
});
}
});
</script>
<!-- Legend -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 mb-6">
<div class="flex flex-wrap items-center justify-between gap-4">
<div class="flex items-center gap-6 text-xs">
<span class="font-bold text-slate-600 uppercase tracking-wide">Verification Status:</span>
<div class="flex items-center gap-2">
<span class="w-3 h-3 rounded-full bg-green-500"></span>
<span class="text-slate-600">Verified (70-100)</span>
</div>
<div class="flex items-center gap-2">
<span class="w-3 h-3 rounded-full bg-amber-500"></span>
<span class="text-slate-600">Partial (40-69)</span>
</div>
<div class="flex items-center gap-2">
<span class="w-3 h-3 rounded-full bg-red-500"></span>
<span class="text-slate-600">Missing (0-39)</span>
</div>
</div>
<div class="flex items-center gap-3 text-xs">
<span class="font-bold text-slate-600 uppercase tracking-wide">Sections:</span>
<span class="px-2 py-1 rounded bg-slate-200 text-slate-500">License</span>
<span class="px-2 py-1 rounded bg-slate-200 text-slate-500">Entity</span>
<span class="px-2 py-1 rounded bg-green-100 text-green-700">RG Links</span>
<span class="px-2 py-1 rounded bg-blue-100 text-blue-700">Social</span>
</div>
</div>
</div>
<!-- Main Scorecard Table -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mb-6">
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
<!-- Dynamic content rendered by JS -->
</tbody>
</table>
</div>
</div>
<!-- Entity Ownership Graph Section -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
<div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
<div class="flex items-center gap-3">
<i class="fa-solid fa-sitemap text-slate-400"></i>
<h3 class="font-bold text-slate-700">Entity Ownership Graph</h3>
<span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-medium">Coming
Soon</span>
</div>
<span class="text-xs text-slate-400">Parent → Brand structure (requires Companies House API)</span>
</div>
<div class="p-6 grid grid-cols-5 gap-4" id="entity-cards-container">
<!-- Dynamic entity cards rendered by JS -->
<div class="col-span-5 text-center py-8 text-slate-400">
<i class="fa-solid fa-spinner fa-spin text-2xl mb-2"></i>
<p>Loading entity data...</p>
</div>
</div>
</div>
<!-- Summary Cards (Dynamic) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6" id="summary-cards-container">
<!-- Dynamic summary cards rendered by JS - shows empty state when no data -->
<div class="bg-slate-50 rounded-xl p-5 border border-slate-200 col-span-3 text-center">
<p class="text-slate-400 text-sm">Summary data will appear after competitor data is loaded</p>
</div>
</div>
</main>
</div>
<script src="../scripts/tableRenderer.js"></script>
<script>
// Report 4.1 Configuration - License & Entity Verification
const REPORT_CONFIG = {
showScoreRow: true,
sections: [
{
id: 'license',
title: 'License Info (from Email Footers)',
icon: 'fa-solid fa-id-card',
color: 'cyan',
badge: { text: 'Extracted', color: 'green' },
rows: [
{ label: 'Jurisdiction', key: 'license.jurisdiction', type: 'text' },
{ label: 'License Number', key: 'license.number', type: 'text' },
{ label: 'License Owner', key: 'license.owner', type: 'text' },
{ label: 'Issue Date', key: 'license.issueDate', type: 'text' }
]
},
{
id: 'entity',
title: 'Entity / Ownership Graph',
icon: 'fa-solid fa-sitemap',
color: 'slate',
badge: { text: 'Coming Soon', color: 'amber' },
stub: true,
rows: [
{ label: 'Parent Company', key: 'entity.parentCompany', type: 'stub' },
{ label: 'Ownership Structure', key: 'entity.ownershipStructure', type: 'stub' }
]
},
{
id: 'rgLinks',
title: 'Responsible Gaming Links',
icon: 'fa-solid fa-shield-heart',
color: 'green',
subtitle: '(BeGambleAware, GamStop, RG messaging)',
showSectionScore: true,
rows: [
{ label: 'BeGambleAware Link', key: 'compliance.rgLinks.beGambleAware', type: 'pct' },
{ label: 'GamStop Link', key: 'compliance.rgLinks.gamStop', type: 'pct' },
{ label: 'RG Messaging', key: 'compliance.rgMessaging.pct', type: 'pct' },
{ label: 'Age Verification (18+/21+)', key: 'compliance.ageVerification.pct', type: 'pct' }
]
},
{
id: 'social',
title: 'Social Media Mapping',
icon: 'fa-solid fa-share-nodes',
color: 'blue',
subtitle: '(Links detected in email content)',
showSectionScore: true,
rows: [
{ label: 'Twitter/X', key: 'social.twitter', type: 'pct', icon: 'fa-brands fa-x-twitter', iconColor: 'text-slate-600' },
{ label: 'Instagram', key: 'social.instagram', type: 'pct', icon: 'fa-brands fa-instagram', iconColor: 'text-pink-600' },
{ label: 'Facebook', key: 'social.facebook', type: 'pct', icon: 'fa-brands fa-facebook', iconColor: 'text-blue-600' },
{ label: 'YouTube', key: 'social.youtube', type: 'pct', icon: 'fa-brands fa-youtube', iconColor: 'text-red-600' },
{ label: 'Telegram', key: 'social.telegram', type: 'pct', icon: 'fa-brands fa-telegram', iconColor: 'text-sky-500' }
]
}
]
};
async function renderDynamicContent() {
if (!window.CRMT?.renderTable || !window.getActiveCompetitorsForReport) {
setTimeout(renderDynamicContent, 200);
return;
}
const competitors = window.getActiveCompetitorsForReport();
if (competitors.length === 0) {
// Show empty state for entity cards
document.getElementById('entity-cards-container').innerHTML = `
<div class="col-span-5 text-center py-8 text-slate-400">
<i class="fa-solid fa-sitemap text-3xl mb-3 opacity-40"></i>
<p class="font-medium">No competitor data available</p>
<p class="text-xs mt-1">Import data via D.10 to see entity ownership</p>
</div>`;
document.getElementById('summary-cards-container').innerHTML = `
<div class="bg-slate-50 rounded-xl p-5 border border-slate-200 col-span-3 text-center">
<p class="text-slate-400 text-sm">No data to summarize</p>
</div>`;
return;
}
// Load scores from database via DAL (from scorecard calculation)
try {
const scoresResult = await CRMT.dal.getScores('4.1');
const scores = scoresResult?.data || [];
if (scores.length > 0) {
competitors.forEach(c => {
const scoreData = scores.find(s => s.competitor_id === c.id);
if (scoreData && scoreData.sections) {
const rgLinks = scoreData.sections.rgLinks?.metadata || {};
const social = scoreData.sections.social?.metadata || {};
// Merge compliance and social data
c.compliance = c.compliance || {};
c.compliance.rgLinks = {
beGambleAware: rgLinks.beGambleAware ?? 0,
gamStop: rgLinks.gamStop ?? 0
};
c.compliance.rgMessaging = { pct: rgLinks.rgMessaging ?? 0 };
c.compliance.ageVerification = { pct: rgLinks.ageVerification ?? 0 };
c.social = {
twitter: social.twitter ?? 0,
instagram: social.instagram ?? 0,
facebook: social.facebook ?? 0,
youtube: social.youtube ?? 0,
telegram: social.telegram ?? 0
};
}
});
console.log('[4.1] Loaded', scores.length, 'scores from database');
}
} catch (e) {
console.warn('[4.1] Could not load scores from DB:', e.message);
}
// Render main table
window.CRMT.renderTable('#dynamic-tbody', '#competitor-header-row', REPORT_CONFIG, competitors);
// Render entity cards dynamically (first 5 competitors)
renderEntityCards(competitors.slice(0, 5));
// Render summary cards dynamically
renderSummaryCards(competitors);
}
function renderEntityCards(competitors) {
const container = document.getElementById('entity-cards-container');
if (!competitors || competitors.length === 0) {
container.innerHTML = `
<div class="col-span-5 text-center py-8 text-slate-400">
<i class="fa-solid fa-sitemap text-3xl mb-3 opacity-40"></i>
<p>No entity data available</p>
</div>`;
return;
}
container.innerHTML = competitors.map((c, i) => `
<div class="entity-card rounded-xl p-4 flex flex-col items-center">
<div class="entity-node rounded-lg px-4 py-3 mb-3 flex items-center gap-2">
${i === 0 ? '<i class="fa-solid fa-crown text-amber-500 text-xs"></i>' : ''}
<span class="font-bold text-slate-700 text-sm">${c.display_name || c.name}</span>
</div>
<div class="w-px h-6 border-l-2 border-dashed border-slate-300"></div>
<div class="entity-node rounded-lg px-3 py-2 bg-slate-50 border-slate-200">
<span class="text-xs text-slate-400">${c.parent_company || 'Parent TBD'}</span>
</div>
<div class="w-px h-6 border-l-2 border-dashed border-slate-300"></div>
<div class="entity-node rounded-lg px-3 py-2 bg-slate-50 border-slate-200">
<span class="text-xs text-slate-400">${c.license?.jurisdiction || 'Jurisdiction TBD'}</span>
</div>
</div>
`).join('');
}
function renderSummaryCards(competitors) {
const container = document.getElementById('summary-cards-container');
if (!competitors || competitors.length === 0) return;
// Find best overall (highest score or first competitor)
const bestCompetitor = competitors[0]; // Assumes sorted by score
container.innerHTML = `
<div class="bg-amber-50 rounded-xl p-5 border border-amber-200">
<div class="flex items-center gap-2 mb-3">
<i class="fa-solid fa-trophy text-amber-500"></i>
<span class="font-bold text-amber-800">Best Overall</span>
</div>
<p class="text-2xl font-bold text-amber-700 mb-1">${bestCompetitor.display_name || bestCompetitor.name}</p>
<p class="text-sm text-amber-600">Highest compliance score in selection</p>
</div>
<div class="bg-red-50 rounded-xl p-5 border border-red-200">
<div class="flex items-center gap-2 mb-3">
<i class="fa-solid fa-triangle-exclamation text-red-500"></i>
<span class="font-bold text-red-800">Critical Gap</span>
</div>
<p class="text-xl font-bold text-red-700 mb-1">${competitors.length} Competitor${competitors.length > 1 ? 's' : ''}</p>
<p class="text-sm text-red-600">RG link compliance varies by operator</p>
</div>
<div class="bg-blue-50 rounded-xl p-5 border border-blue-200">
<div class="flex items-center gap-2 mb-3">
<i class="fa-solid fa-share-nodes text-blue-500"></i>
<span class="font-bold text-blue-800">Social Presence</span>
</div>
<p class="text-lg font-bold text-blue-700 mb-1">${competitors.length} Active</p>
<p class="text-sm text-blue-600">Social links detected in email content</p>
</div>
`;
}
// Register for group changes
function initDynamicContent() {
if (window.setupGroupChangeListener) {
window.setupGroupChangeListener(renderDynamicContent);
} else {
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
                            module: '4.1',
                            title: 'License & Entity Verification',
                            category: 'Regulatory',
                            isBeta: true,
                            stubData: ['Entity/Ownership (requires Companies House)']
                        });
                    }
                });
            </script>
<script>
        // Report 4.1 Configuration - License & Entity Verification
        const REPORT_CONFIG = {
            showScoreRow: true,
            sections: [
                {
                    id: 'license',
                    title: 'License Info (from Email Footers)',
                    icon: 'fa-solid fa-id-card',
                    color: 'cyan',
                    badge: { text: 'Extracted', color: 'green' },
                    rows: [
                        { label: 'Jurisdiction', key: 'license.jurisdiction', type: 'text' },
                        { label: 'License Number', key: 'license.number', type: 'text' },
                        { label: 'License Owner', key: 'license.owner', type: 'text' },
                        { label: 'Issue Date', key: 'license.issueDate', type: 'text' }
                    ]
                },
                {
                    id: 'entity',
                    title: 'Entity / Ownership Graph',
                    icon: 'fa-solid fa-sitemap',
                    color: 'slate',
                    badge: { text: 'Coming Soon', color: 'amber' },
                    stub: true,
                    rows: [
                        { label: 'Parent Company', key: 'entity.parentCompany', type: 'stub' },
                        { label: 'Ownership Structure', key: 'entity.ownershipStructure', type: 'stub' }
                    ]
                },
                {
                    id: 'rgLinks',
                    title: 'Responsible Gaming Links',
                    icon: 'fa-solid fa-shield-heart',
                    color: 'green',
                    subtitle: '(BeGambleAware, GamStop, RG messaging)',
                    showSectionScore: true,
                    rows: [
                        { label: 'BeGambleAware Link', key: 'compliance.rgLinks.beGambleAware', type: 'pct' },
                        { label: 'GamStop Link', key: 'compliance.rgLinks.gamStop', type: 'pct' },
                        { label: 'RG Messaging', key: 'compliance.rgMessaging.pct', type: 'pct' },
                        { label: 'Age Verification (18+/21+)', key: 'compliance.ageVerification.pct', type: 'pct' }
                    ]
                },
                {
                    id: 'social',
                    title: 'Social Media Mapping',
                    icon: 'fa-solid fa-share-nodes',
                    color: 'blue',
                    subtitle: '(Links detected in email content)',
                    showSectionScore: true,
                    rows: [
                        { label: 'Twitter/X', key: 'social.twitter', type: 'pct', icon: 'fa-brands fa-x-twitter', iconColor: 'text-slate-600' },
                        { label: 'Instagram', key: 'social.instagram', type: 'pct', icon: 'fa-brands fa-instagram', iconColor: 'text-pink-600' },
                        { label: 'Facebook', key: 'social.facebook', type: 'pct', icon: 'fa-brands fa-facebook', iconColor: 'text-blue-600' },
                        { label: 'YouTube', key: 'social.youtube', type: 'pct', icon: 'fa-brands fa-youtube', iconColor: 'text-red-600' },
                        { label: 'Telegram', key: 'social.telegram', type: 'pct', icon: 'fa-brands fa-telegram', iconColor: 'text-sky-500' }
                    ]
                }
            ]
        };

        async function renderDynamicContent() {
            if (!window.CRMT?.renderTable || !window.getActiveCompetitorsForReport) {
                setTimeout(renderDynamicContent, 200);
                return;
            }
            const competitors = window.getActiveCompetitorsForReport();
            if (competitors.length === 0) {
                // Show empty state for entity cards
                document.getElementById('entity-cards-container').innerHTML = `
                    <div class="col-span-5 text-center py-8 text-slate-400">
                        <i class="fa-solid fa-sitemap text-3xl mb-3 opacity-40"></i>
                        <p class="font-medium">No competitor data available</p>
                        <p class="text-xs mt-1">Import data via D.10 to see entity ownership</p>
                    </div>`;
                document.getElementById('summary-cards-container').innerHTML = `
                    <div class="bg-slate-50 rounded-xl p-5 border border-slate-200 col-span-3 text-center">
                        <p class="text-slate-400 text-sm">No data to summarize</p>
                    </div>`;
                return;
            }

            // Load scores from database via DAL (from scorecard calculation)
            try {
                const scoresResult = await CRMT.dal.getScores('4.1');
                const scores = scoresResult?.data || [];
                if (scores.length > 0) {
                    competitors.forEach(c => {
                        const scoreData = scores.find(s => s.competitor_id === c.id);
                        if (scoreData && scoreData.sections) {
                            const rgLinks = scoreData.sections.rgLinks?.metadata || {};
                            const social = scoreData.sections.social?.metadata || {};

                            // Merge compliance and social data
                            c.compliance = c.compliance || {};
                            c.compliance.rgLinks = {
                                beGambleAware: rgLinks.beGambleAware ?? 0,
                                gamStop: rgLinks.gamStop ?? 0
                            };
                            c.compliance.rgMessaging = { pct: rgLinks.rgMessaging ?? 0 };
                            c.compliance.ageVerification = { pct: rgLinks.ageVerification ?? 0 };

                            c.social = {
                                twitter: social.twitter ?? 0,
                                instagram: social.instagram ?? 0,
                                facebook: social.facebook ?? 0,
                                youtube: social.youtube ?? 0,
                                telegram: social.telegram ?? 0
                            };
                        }
                    });
                    console.log('[4.1] Loaded', scores.length, 'scores from database');
                }
            } catch (e) {
                console.warn('[4.1] Could not load scores from DB:', e.message);
            }

            // Render main table
            window.CRMT.renderTable('#dynamic-tbody', '#competitor-header-row', REPORT_CONFIG, competitors);

            // Render entity cards dynamically (first 5 competitors)
            renderEntityCards(competitors.slice(0, 5));

            // Render summary cards dynamically
            renderSummaryCards(competitors);
        }

        function renderEntityCards(competitors) {
            const container = document.getElementById('entity-cards-container');
            if (!competitors || competitors.length === 0) {
                container.innerHTML = `
                    <div class="col-span-5 text-center py-8 text-slate-400">
                        <i class="fa-solid fa-sitemap text-3xl mb-3 opacity-40"></i>
                        <p>No entity data available</p>
                    </div>`;
                return;
            }

            container.innerHTML = competitors.map((c, i) => `
                <div class="entity-card rounded-xl p-4 flex flex-col items-center">
                    <div class="entity-node rounded-lg px-4 py-3 mb-3 flex items-center gap-2">
                        ${i === 0 ? '<i class="fa-solid fa-crown text-amber-500 text-xs"></i>' : ''}
                        <span class="font-bold text-slate-700 text-sm">${c.display_name || c.name}</span>
                    </div>
                    <div class="w-px h-6 border-l-2 border-dashed border-slate-300"></div>
                    <div class="entity-node rounded-lg px-3 py-2 bg-slate-50 border-slate-200">
                        <span class="text-xs text-slate-400">${c.parent_company || 'Parent TBD'}</span>
                    </div>
                    <div class="w-px h-6 border-l-2 border-dashed border-slate-300"></div>
                    <div class="entity-node rounded-lg px-3 py-2 bg-slate-50 border-slate-200">
                        <span class="text-xs text-slate-400">${c.license?.jurisdiction || 'Jurisdiction TBD'}</span>
                    </div>
                </div>
            `).join('');
        }

        function renderSummaryCards(competitors) {
            const container = document.getElementById('summary-cards-container');
            if (!competitors || competitors.length === 0) return;

            // Find best overall (highest score or first competitor)
            const bestCompetitor = competitors[0]; // Assumes sorted by score

            container.innerHTML = `
                <div class="bg-amber-50 rounded-xl p-5 border border-amber-200">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fa-solid fa-trophy text-amber-500"></i>
                        <span class="font-bold text-amber-800">Best Overall</span>
                    </div>
                    <p class="text-2xl font-bold text-amber-700 mb-1">${bestCompetitor.display_name || bestCompetitor.name}</p>
                    <p class="text-sm text-amber-600">Highest compliance score in selection</p>
                </div>

                <div class="bg-red-50 rounded-xl p-5 border border-red-200">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
                        <span class="font-bold text-red-800">Critical Gap</span>
                    </div>
                    <p class="text-xl font-bold text-red-700 mb-1">${competitors.length} Competitor${competitors.length > 1 ? 's' : ''}</p>
                    <p class="text-sm text-red-600">RG link compliance varies by operator</p>
                </div>

                <div class="bg-blue-50 rounded-xl p-5 border border-blue-200">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fa-solid fa-share-nodes text-blue-500"></i>
                        <span class="font-bold text-blue-800">Social Presence</span>
                    </div>
                    <p class="text-lg font-bold text-blue-700 mb-1">${competitors.length} Active</p>
                    <p class="text-sm text-blue-600">Social links detected in email content</p>
                </div>
            `;
        }

        // Register for group changes
        function initDynamicContent() {
            if (window.setupGroupChangeListener) {
                window.setupGroupChangeListener(renderDynamicContent);
            } else {
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
