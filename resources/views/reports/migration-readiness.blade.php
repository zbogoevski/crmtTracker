@extends('layouts.dashboard')


@section('title', 'Report 5.3: Migration Readiness | CRMTracker')


@section('content')
<main class="flex-1 ml-20 p-8 max-w-[1600px] mx-auto overflow-x-hidden">
<!-- Header injected by reportTemplate.js -->
<div id="report-header"></div>
<script>
document.addEventListener('DOMContentLoaded', () => {
if (window.CRMT?.renderReportHeader) {
CRMT.renderReportHeader('#report-header', {
module: '5.3',
title: 'Migration Readiness',
category: 'Enterprise',
isBeta: true,
stubData: ['Stack Detection', 'Platform Inventory']
});
}
});
</script>
<!-- Report 5.3 shows only the migration readiness table for now -->
<!-- Gauge and widgets will be added back when real data is available -->
<!-- Detailed Readiness Table -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mt-6">
<div class="overflow-x-auto">
<table class="w-full text-sm min-w-[900px]">
<thead>
<tr class="border-b-2 border-slate-300" id="competitor-header-row">
<th
class="sticky-col text-left py-4 px-4 font-bold text-slate-600 uppercase text-xs tracking-wider bg-slate-50 min-w-[200px]">
Readiness Factor
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
// Report 5.3 Configuration - Migration Readiness
const REPORT_CONFIG = {
sections: [
{
id: 'dataQuality',
title: 'Data Quality Assessment',
subtitle: '(Completeness, structure, consistency)',
icon: 'fa-solid fa-database',
colorScheme: 'emerald',
rows: [
{ label: 'Data Completeness', key: 'migration.dataCompleteness', type: 'pct' },
{ label: 'Schema Consistency', key: 'migration.schemaConsistency', type: 'pct' },
{ label: 'Email History Depth', key: 'migration.historyDepth', type: 'text' }
]
},
{
id: 'documentation',
title: 'Documentation Status',
subtitle: '(API refs, schema diagrams, runbooks)',
icon: 'fa-solid fa-file-lines',
colorScheme: 'blue',
rows: [
{ label: 'API Documentation', key: 'migration.apiDocs', type: 'text', isStub: true },
{ label: 'Schema Diagrams', key: 'migration.schemaDiagrams', type: 'text', isStub: true },
{ label: 'Runbooks Available', key: 'migration.runbooks', type: 'text', isStub: true }
]
},
{
id: 'workflowLogic',
title: 'Workflow Logic Portability',
subtitle: '(Automation rules, triggers, journeys)',
icon: 'fa-solid fa-sitemap',
colorScheme: 'violet',
rows: [
{ label: 'Journey Complexity', key: 'crmScorecard.journey.score', type: 'score' },
{ label: 'Automation Portability', key: 'migration.automationPortability', type: 'text', isStub: true },
{ label: 'Hard-coded Logic', key: 'migration.hardcodedLogic', type: 'text', isStub: true }
]
},
{
id: 'integrations',
title: 'Integration Dependencies',
subtitle: '(Third-party APIs, data feeds)',
icon: 'fa-solid fa-plug',
colorScheme: 'amber',
isStub: true,
rows: [
{ label: 'External Integrations', key: 'migration.externalIntegrations', type: 'number', isStub: true },
{ label: 'Data Feeds', key: 'migration.dataFeeds', type: 'number', isStub: true },
{ label: 'API Dependencies', key: 'migration.apiDependencies', type: 'number', isStub: true }
]
}
]
};
function calculateReadinessScore(competitors) {
let totalScore = 0;
let count = 0;
competitors.forEach(c => {
let score = 0;
// Data from compliance (40%)
const comp = c.compliance || {};
if (comp.legalDisclaimer?.pct) score += (comp.legalDisclaimer.pct / 100) * 15;
if (comp.footerPresent?.pct) score += (comp.footerPresent.pct / 100) * 15;
if (comp.unsubscribeLink?.pct) score += (comp.unsubscribeLink.pct / 100) * 10;
// Journey complexity (30%)
const scores = c.scores || {};
const journey = scores?.journey || {};
score += (journey.score || 0) * 3;
// Frequency data availability (30%)
const freq = scores?.frequency?.metadata || {};
if (freq.dailyAvg > 0) score += 10;
if (freq.weeklyAvg > 0) score += 10;
if (freq.saturation > 0) score += 10;
totalScore += Math.min(score, 100);
count++;
});
return count > 0 ? Math.round(totalScore / count) : 0;
}
function updateGauge(score) {
const needle = document.getElementById('needle');
// Score 0 = -90deg, Score 100 = 90deg
const degrees = (score / 100) * 180 - 90;
if (needle) needle.style.transform = `rotate(${degrees}deg)`;
const scoreEl = document.getElementById('readiness-score');
if (scoreEl) scoreEl.textContent = score;
// Update status badge and label
const badge = document.getElementById('status-badge');
const label = document.getElementById('readiness-label');
if (score >= 70) {
if (badge) {
badge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800';
badge.textContent = 'Migration Ready';
}
if (label) {
label.className = 'text-sm font-medium text-emerald-600';
label.textContent = 'Low complexity migration';
}
} else if (score >= 40) {
if (badge) {
badge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800';
badge.textContent = 'Moderate Effort';
}
if (label) {
label.className = 'text-sm font-medium text-amber-600';
label.textContent = 'Some pre-work required';
}
} else {
if (badge) {
badge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800';
badge.textContent = 'Complex Migration';
}
if (label) {
label.className = 'text-sm font-medium text-red-600';
label.textContent = 'High Technical Debt Detected';
}
}
// Update timeline
const timeline = document.getElementById('timeline');
if (timeline) {
if (score >= 70) {
timeline.textContent = '2-4 Weeks';
} else if (score >= 40) {
timeline.textContent = '2-4 Months';
} else {
timeline.textContent = '6-8 Months';
}
}
}
function updateAuditItems(competitors) {
const container = document.getElementById('audit-items');
if (!container) return; // Element doesn't exist yet
const avgComp = competitors[0]?.compliance || {};
const avgScores = competitors[0]?.scores || {};
const items = [
{
title: 'Data Structure',
status: avgComp.legalDisclaimer?.pct > 50 ? 'pass' : 'fail',
statusText: avgComp.legalDisclaimer?.pct > 50 ? 'Clean Schema' : 'Needs Cleanup',
detail: avgComp.legalDisclaimer?.pct > 50 ? 'Consistent format across emails' : 'Inconsistent data structure'
},
{
title: 'Documentation',
status: 'fail',
statusText: 'Missing / Outdated',
detail: 'No API references or schema diagrams found.'
},
{
title: 'Workflow Logic',
status: avgScores?.journey?.score > 5 ? 'pass' : 'warn',
statusText: avgScores?.journey?.score > 5 ? 'Configurable' : 'Hard-coded',
detail: avgScores?.journey?.score > 5 ? 'Journeys defined in CRM UI.' : 'Logic embedded in codebase.'
}
];
container.innerHTML = items.map(item => `
<div class="flex items-start gap-3">
<div class="w-6 h-6 rounded-full ${item.status === 'pass' ? 'bg-emerald-100' : item.status === 'warn' ? 'bg-amber-100' : 'bg-red-100'} flex items-center justify-center shrink-0 mt-0.5">
${item.status === 'pass'
? '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600"><polyline points="20 6 9 17 4 12"/></svg>'
: item.status === 'warn'
? '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-amber-600"><line x1="12" y1="9" x2="12" y2="13"/><circle cx="12" cy="17" r="1"/></svg>'
: '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-red-600"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>'
}
</div>
<div>
<h4 class="text-sm font-bold text-slate-700">${item.title}</h4>
<p class="text-xs ${item.status === 'pass' ? 'text-emerald-600' : item.status === 'warn' ? 'text-amber-600' : 'text-red-600'} font-medium">${item.statusText}</p>
<p class="text-[10px] text-slate-400 mt-0.5">${item.detail}</p>
</div>
</div>
`).join('');
// Update primary blocker
const failedItem = items.find(i => i.status === 'fail') || items.find(i => i.status === 'warn');
if (failedItem) {
document.getElementById('primary-blocker').textContent = failedItem.title;
document.getElementById('blocker-detail').textContent = failedItem.statusText;
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
// Calculate and update gauge
const score = calculateReadinessScore(competitors);
updateGauge(score);
// Update audit items
updateAuditItems(competitors);
// Render table using shared renderer
window.CRMT.renderTable('#dynamic-tbody', '#competitor-header-row', REPORT_CONFIG, competitors);
console.log('[5.3] Dynamic content rendered for', competitors.length, 'competitors, readiness:', score);
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
                            module: '5.3',
                            title: 'Migration Readiness',
                            category: 'Enterprise',
                            isBeta: true,
                            stubData: ['Stack Detection', 'Platform Inventory']
                        });
                    }
                });
            </script>
<script>
        // Report 5.3 Configuration - Migration Readiness
        const REPORT_CONFIG = {
            sections: [
                {
                    id: 'dataQuality',
                    title: 'Data Quality Assessment',
                    subtitle: '(Completeness, structure, consistency)',
                    icon: 'fa-solid fa-database',
                    colorScheme: 'emerald',
                    rows: [
                        { label: 'Data Completeness', key: 'migration.dataCompleteness', type: 'pct' },
                        { label: 'Schema Consistency', key: 'migration.schemaConsistency', type: 'pct' },
                        { label: 'Email History Depth', key: 'migration.historyDepth', type: 'text' }
                    ]
                },
                {
                    id: 'documentation',
                    title: 'Documentation Status',
                    subtitle: '(API refs, schema diagrams, runbooks)',
                    icon: 'fa-solid fa-file-lines',
                    colorScheme: 'blue',
                    rows: [
                        { label: 'API Documentation', key: 'migration.apiDocs', type: 'text', isStub: true },
                        { label: 'Schema Diagrams', key: 'migration.schemaDiagrams', type: 'text', isStub: true },
                        { label: 'Runbooks Available', key: 'migration.runbooks', type: 'text', isStub: true }
                    ]
                },
                {
                    id: 'workflowLogic',
                    title: 'Workflow Logic Portability',
                    subtitle: '(Automation rules, triggers, journeys)',
                    icon: 'fa-solid fa-sitemap',
                    colorScheme: 'violet',
                    rows: [
                        { label: 'Journey Complexity', key: 'crmScorecard.journey.score', type: 'score' },
                        { label: 'Automation Portability', key: 'migration.automationPortability', type: 'text', isStub: true },
                        { label: 'Hard-coded Logic', key: 'migration.hardcodedLogic', type: 'text', isStub: true }
                    ]
                },
                {
                    id: 'integrations',
                    title: 'Integration Dependencies',
                    subtitle: '(Third-party APIs, data feeds)',
                    icon: 'fa-solid fa-plug',
                    colorScheme: 'amber',
                    isStub: true,
                    rows: [
                        { label: 'External Integrations', key: 'migration.externalIntegrations', type: 'number', isStub: true },
                        { label: 'Data Feeds', key: 'migration.dataFeeds', type: 'number', isStub: true },
                        { label: 'API Dependencies', key: 'migration.apiDependencies', type: 'number', isStub: true }
                    ]
                }
            ]
        };

        function calculateReadinessScore(competitors) {
            let totalScore = 0;
            let count = 0;

            competitors.forEach(c => {
                let score = 0;

                // Data from compliance (40%)
                const comp = c.compliance || {};
                if (comp.legalDisclaimer?.pct) score += (comp.legalDisclaimer.pct / 100) * 15;
                if (comp.footerPresent?.pct) score += (comp.footerPresent.pct / 100) * 15;
                if (comp.unsubscribeLink?.pct) score += (comp.unsubscribeLink.pct / 100) * 10;

                // Journey complexity (30%)
                const scores = c.scores || {};
                const journey = scores?.journey || {};
                score += (journey.score || 0) * 3;

                // Frequency data availability (30%)
                const freq = scores?.frequency?.metadata || {};
                if (freq.dailyAvg > 0) score += 10;
                if (freq.weeklyAvg > 0) score += 10;
                if (freq.saturation > 0) score += 10;

                totalScore += Math.min(score, 100);
                count++;
            });

            return count > 0 ? Math.round(totalScore / count) : 0;
        }

        function updateGauge(score) {
            const needle = document.getElementById('needle');
            // Score 0 = -90deg, Score 100 = 90deg
            const degrees = (score / 100) * 180 - 90;
            if (needle) needle.style.transform = `rotate(${degrees}deg)`;

            const scoreEl = document.getElementById('readiness-score');
            if (scoreEl) scoreEl.textContent = score;

            // Update status badge and label
            const badge = document.getElementById('status-badge');
            const label = document.getElementById('readiness-label');

            if (score >= 70) {
                if (badge) {
                    badge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800';
                    badge.textContent = 'Migration Ready';
                }
                if (label) {
                    label.className = 'text-sm font-medium text-emerald-600';
                    label.textContent = 'Low complexity migration';
                }
            } else if (score >= 40) {
                if (badge) {
                    badge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800';
                    badge.textContent = 'Moderate Effort';
                }
                if (label) {
                    label.className = 'text-sm font-medium text-amber-600';
                    label.textContent = 'Some pre-work required';
                }
            } else {
                if (badge) {
                    badge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800';
                    badge.textContent = 'Complex Migration';
                }
                if (label) {
                    label.className = 'text-sm font-medium text-red-600';
                    label.textContent = 'High Technical Debt Detected';
                }
            }

            // Update timeline
            const timeline = document.getElementById('timeline');
            if (timeline) {
                if (score >= 70) {
                    timeline.textContent = '2-4 Weeks';
                } else if (score >= 40) {
                    timeline.textContent = '2-4 Months';
                } else {
                    timeline.textContent = '6-8 Months';
                }
            }
        }

        function updateAuditItems(competitors) {
            const container = document.getElementById('audit-items');
            if (!container) return; // Element doesn't exist yet
            const avgComp = competitors[0]?.compliance || {};
            const avgScores = competitors[0]?.scores || {};

            const items = [
                {
                    title: 'Data Structure',
                    status: avgComp.legalDisclaimer?.pct > 50 ? 'pass' : 'fail',
                    statusText: avgComp.legalDisclaimer?.pct > 50 ? 'Clean Schema' : 'Needs Cleanup',
                    detail: avgComp.legalDisclaimer?.pct > 50 ? 'Consistent format across emails' : 'Inconsistent data structure'
                },
                {
                    title: 'Documentation',
                    status: 'fail',
                    statusText: 'Missing / Outdated',
                    detail: 'No API references or schema diagrams found.'
                },
                {
                    title: 'Workflow Logic',
                    status: avgScores?.journey?.score > 5 ? 'pass' : 'warn',
                    statusText: avgScores?.journey?.score > 5 ? 'Configurable' : 'Hard-coded',
                    detail: avgScores?.journey?.score > 5 ? 'Journeys defined in CRM UI.' : 'Logic embedded in codebase.'
                }
            ];

            container.innerHTML = items.map(item => `
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full ${item.status === 'pass' ? 'bg-emerald-100' : item.status === 'warn' ? 'bg-amber-100' : 'bg-red-100'} flex items-center justify-center shrink-0 mt-0.5">
                        ${item.status === 'pass'
                    ? '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600"><polyline points="20 6 9 17 4 12"/></svg>'
                    : item.status === 'warn'
                        ? '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-amber-600"><line x1="12" y1="9" x2="12" y2="13"/><circle cx="12" cy="17" r="1"/></svg>'
                        : '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-red-600"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>'
                }
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-700">${item.title}</h4>
                        <p class="text-xs ${item.status === 'pass' ? 'text-emerald-600' : item.status === 'warn' ? 'text-amber-600' : 'text-red-600'} font-medium">${item.statusText}</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">${item.detail}</p>
                    </div>
                </div>
            `).join('');

            // Update primary blocker
            const failedItem = items.find(i => i.status === 'fail') || items.find(i => i.status === 'warn');
            if (failedItem) {
                document.getElementById('primary-blocker').textContent = failedItem.title;
                document.getElementById('blocker-detail').textContent = failedItem.statusText;
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

            // Calculate and update gauge
            const score = calculateReadinessScore(competitors);
            updateGauge(score);

            // Update audit items
            updateAuditItems(competitors);

            // Render table using shared renderer
            window.CRMT.renderTable('#dynamic-tbody', '#competitor-header-row', REPORT_CONFIG, competitors);

            console.log('[5.3] Dynamic content rendered for', competitors.length, 'competitors, readiness:', score);
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
