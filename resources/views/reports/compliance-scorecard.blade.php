@extends('layouts.dashboard')

@section('title', 'Report 3.1: Compliance Scorecard | CRMTracker')

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
<div class="space-y-6">
<header class="flex justify-between items-center mb-6">
<div class="flex items-center gap-3">
<span class="text-xs font-medium bg-emerald-50 text-emerald-700 px-2 py-1 rounded border border-emerald-200">Module 3.1</span>
<span class="text-xs font-medium bg-blue-50 text-blue-700 px-2 py-1 rounded border border-blue-200">Compliance</span>
</div>
<button class="px-6 py-2.5 rounded-lg font-medium shadow-sm flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white">
<i class="fa-solid fa-download"></i> Export
</button>
</header>
<!-- Legend -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 mb-6">
<div class="flex flex-wrap items-center justify-between gap-4">
<div class="flex items-center gap-6 text-xs">
<span class="font-bold text-slate-600 uppercase tracking-wide">Sections:</span>
<span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700">Completeness</span>
<span class="px-2 py-1 rounded bg-blue-100 text-blue-700">Header/Footer</span>
<span class="px-2 py-1 rounded bg-violet-100 text-violet-700">Opt-in/Unsubscribe</span>
<span class="px-2 py-1 rounded bg-rose-100 text-rose-700">Traceability</span>
</div>
<div class="flex items-center gap-3 text-xs">
<span class="font-bold text-slate-600 uppercase tracking-wide">Score:</span>
<div class="flex items-center gap-2">
<span class="w-3 h-3 rounded-full bg-green-500"></span>
<span class="text-slate-600">Good (7+)</span>
</div>
<div class="flex items-center gap-2">
<span class="w-3 h-3 rounded-full bg-amber-500"></span>
<span class="text-slate-600">Average (4-7)</span>
</div>
<div class="flex items-center gap-2">
<span class="w-3 h-3 rounded-full bg-red-500"></span>
<span class="text-slate-600">Low (0-4)</span>
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
<th class="sticky-col text-left py-4 px-4 font-bold text-slate-600 uppercase text-xs tracking-wider bg-slate-50 min-w-[200px]">
Metric
</th>
<!-- Dynamic competitor headers inserted by JS -->
<th class="text-center py-4 px-4 font-bold text-slate-600 uppercase text-xs tracking-wider bg-slate-50 min-w-[80px]">
Avg</th>
</tr>
</thead>
<tbody id="dynamic-tbody">
<!-- Dynamic content inserted by JS -->
</tbody>
</table>
</div>
</div>
<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
<div class="bg-emerald-50 rounded-xl p-5 border border-emerald-200">
<div class="flex items-center gap-2 mb-3">
<i class="fa-solid fa-crown text-emerald-500"></i>
<span class="font-bold text-emerald-800">Market Leader</span>
</div>
<p class="text-2xl font-bold text-emerald-700 mb-1" id="best-compliance">-</p>
<p class="text-sm text-emerald-600" id="best-compliance-score">Score: -/10</p>
</div>
<div class="bg-blue-50 rounded-xl p-5 border border-blue-200">
<div class="flex items-center gap-2 mb-3">
<i class="fa-solid fa-clipboard-check text-blue-500"></i>
<span class="font-bold text-blue-800">T&C Present</span>
</div>
<p class="text-2xl font-bold text-blue-700 mb-1" id="tc-pct">-%</p>
<p class="text-sm text-blue-600">Across competitors</p>
</div>
<div class="bg-amber-50 rounded-xl p-5 border border-amber-200">
<div class="flex items-center gap-2 mb-3">
<i class="fa-solid fa-triangle-exclamation text-amber-500"></i>
<span class="font-bold text-amber-800">Critical Gap</span>
</div>
<p class="text-2xl font-bold text-amber-700 mb-1" id="critical-gap">-</p>
<p class="text-sm text-amber-600" id="critical-gap-detail">-</p>
</div>
<div class="bg-violet-50 rounded-xl p-5 border border-violet-200">
<div class="flex items-center gap-2 mb-3">
<i class="fa-solid fa-chart-line text-violet-500"></i>
<span class="font-bold text-violet-800">Industry Avg</span>
</div>
<p class="text-2xl font-bold text-violet-700 mb-1" id="industry-avg">-</p>
<p class="text-sm text-violet-600">Compliance Score</p>
</div>
</div>
</div>

@push('scripts')
<script>
const laravelData = @json($data ?? []);
console.log('[3.1] Laravel data loaded:', laravelData.length, 'competitors');

// Report 3.1 Configuration - Compliance Scorecard
const REPORT_CONFIG = {
    sections: [
        {
            id: 'completeness',
            title: 'Completeness Checklist',
            subtitle: '(Legal, footer, address, ownership)',
            icon: 'fa-solid fa-clipboard-check',
            colorScheme: 'emerald',
            rows: [
                { label: 'Legal Disclaimer (T&C)', key: 'compliance.legal_disclaimer.pct', type: 'pct' },
                { label: 'Footer Present', key: 'compliance.footer.pct', type: 'pct' },
                { label: 'RG Messaging', key: 'compliance.rg_messaging.pct', type: 'pct' }
            ]
        },
        {
            id: 'headerFooter',
            title: 'Header/Footer Compliance',
            subtitle: '(Marketing standards, age verification)',
            icon: 'fa-solid fa-file-lines',
            colorScheme: 'blue',
            rows: [
                { label: 'Age Verification (18+)', key: 'compliance.age_verification.pct', type: 'pct' }
            ]
        },
        {
            id: 'optIn',
            title: 'Opt-in / Unsubscribe',
            subtitle: '(Mechanism presence, clarity)',
            icon: 'fa-solid fa-envelope-open-text',
            colorScheme: 'violet',
            rows: [
                { label: 'Unsubscribe Link', key: 'compliance.unsubscribe.pct', type: 'pct' }
            ]
        },
        {
            id: 'traceability',
            title: 'Traceability / Audit',
            subtitle: '(Transparency, domain, evidence)',
            icon: 'fa-solid fa-magnifying-glass',
            colorScheme: 'rose',
            rows: [
                { label: 'Domain Consistency', key: 'compliance.sender_transparency.domain_consistency_pct', type: 'pct' }
            ]
        }
    ]
};

// Transform Laravel data to match expected format
function transformLaravelData(data) {
    return data.map(comp => ({
        id: comp.competitor_id,
        name: comp.competitor_name,
        shortName: comp.short_name,
        compliance: {
            legal_disclaimer: {
                pct: comp.compliance?.legal_disclaimer?.pct ?? 0,
                present: (comp.compliance?.legal_disclaimer?.pct ?? 0) > 50
            },
            footer: {
                pct: comp.compliance?.footer?.pct ?? 0,
                present: (comp.compliance?.footer?.pct ?? 0) > 50
            },
            rg_messaging: {
                pct: comp.compliance?.rg_messaging?.pct ?? 0,
                present: (comp.compliance?.rg_messaging?.pct ?? 0) > 50
            },
            age_verification: {
                pct: comp.compliance?.age_verification?.pct ?? 0,
                present: (comp.compliance?.age_verification?.pct ?? 0) > 50
            },
            unsubscribe: {
                pct: comp.compliance?.unsubscribe?.pct ?? 0,
                present: (comp.compliance?.unsubscribe?.pct ?? 0) > 50
            },
            sender_transparency: {
                domain_consistency_pct: comp.compliance?.sender_transparency?.domain_consistency_pct ?? 0
            }
        },
        overall_score: comp.overall_score ?? 0,
        score_grade: comp.score_grade ?? 'low'
    }));
}

// Helper to get value from nested object path
function getValue(obj, path) {
    return path.split('.').reduce((current, key) => current?.[key] ?? 0, obj);
}

// Format value based on type
function formatValue(value, type) {
    if (type === 'pct') {
        return typeof value === 'number' ? value.toFixed(1) + '%' : '0%';
    }
    if (type === 'bool') {
        return value ? '✓' : '✗';
    }
    if (type === 'score') {
        return typeof value === 'number' ? value.toFixed(1) : '0';
    }
    return value ?? '-';
}

// Get score color class
function getScoreColor(score, type) {
    if (type === 'pct') {
        const num = typeof score === 'number' ? score : parseFloat(score) || 0;
        if (num >= 70) return 'text-green-600 font-bold';
        if (num >= 40) return 'text-amber-600 font-semibold';
        return 'text-red-600';
    }
    if (type === 'bool') {
        return score ? 'text-green-600' : 'text-red-600';
    }
    return '';
}

// Render table
function renderTable() {
    if (laravelData.length === 0) {
        document.getElementById('dynamic-tbody').innerHTML = '<tr><td colspan="100" class="py-8 text-center text-slate-400">No compliance data available</td></tr>';
        return;
    }

    const competitors = transformLaravelData(laravelData);
    const headerRow = document.getElementById('competitor-header-row');
    const tbody = document.getElementById('dynamic-tbody');

    // Clear existing headers (except first and last)
    const existingHeaders = headerRow.querySelectorAll('th:not(:first-child):not(:last-child)');
    existingHeaders.forEach(h => h.remove());

    // Add competitor headers
    competitors.forEach((comp, idx) => {
        const th = document.createElement('th');
        th.className = 'text-center py-4 px-4 font-bold text-slate-600 uppercase text-xs tracking-wider bg-slate-50 min-w-[80px]';
        th.textContent = comp.shortName || comp.name;
        headerRow.insertBefore(th, headerRow.lastElementChild);
    });

    // Clear tbody
    tbody.innerHTML = '';

    // Render sections
    REPORT_CONFIG.sections.forEach(section => {
        // Section header
        const sectionRow = document.createElement('tr');
        sectionRow.className = 'bg-slate-50';
        const sectionCell = document.createElement('td');
        sectionCell.colSpan = competitors.length + 2;
        sectionCell.className = 'py-3 px-4';
        sectionCell.innerHTML = `
            <div class="flex items-center gap-3">
                <i class="${section.icon} text-${section.colorScheme}-600"></i>
                <div>
                    <div class="font-bold text-slate-700">${section.title}</div>
                    <div class="text-xs text-slate-500">${section.subtitle}</div>
                </div>
            </div>
        `;
        sectionRow.appendChild(sectionCell);
        tbody.appendChild(sectionRow);

        // Section rows
        section.rows.forEach(row => {
            const tr = document.createElement('tr');
            tr.className = 'border-b border-slate-100 hover:bg-slate-50';

            // Metric label
            const labelCell = document.createElement('td');
            labelCell.className = 'sticky-col py-3 px-4 text-slate-700 bg-white';
            labelCell.textContent = row.label;
            tr.appendChild(labelCell);

            // Competitor values
            let total = 0;
            let count = 0;
            competitors.forEach(comp => {
                const value = getValue(comp, row.key);
                const formatted = formatValue(value, row.type);
                const colorClass = getScoreColor(value, row.type);

                const cell = document.createElement('td');
                cell.className = `py-3 px-4 text-center ${colorClass}`;
                cell.textContent = formatted;
                tr.appendChild(cell);

                if (row.type === 'pct' && typeof value === 'number') {
                    total += value;
                    count++;
                }
            });

            // Average
            const avgCell = document.createElement('td');
            avgCell.className = 'py-3 px-4 text-center font-semibold text-slate-600 bg-slate-50';
            if (row.type === 'pct' && count > 0) {
                avgCell.textContent = (total / count).toFixed(1) + '%';
            } else {
                avgCell.textContent = '-';
            }
            tr.appendChild(avgCell);

            tbody.appendChild(tr);
        });
    });

    // Total row
    const totalRow = document.createElement('tr');
    totalRow.className = 'total-row bg-gradient-to-r from-purple-600 to-blue-600';
    const totalLabel = document.createElement('td');
    totalLabel.className = 'sticky-col py-4 px-4';
    totalLabel.innerHTML = `
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-star text-yellow-300"></i>
            <span class="font-bold text-white tracking-wide">CRMTracker® Score</span>
        </div>
    `;
    totalRow.appendChild(totalLabel);

    let totalScore = 0;
    competitors.forEach(comp => {
        const score = comp.overall_score ?? 0;
        const cell = document.createElement('td');
        cell.className = 'py-4 px-4 text-center';
        cell.innerHTML = `<span class="text-2xl font-bold text-white">${score.toFixed(0)}</span>`;
        totalRow.appendChild(cell);
        totalScore += score;
    });

    const avgCell = document.createElement('td');
    avgCell.className = 'py-4 px-4 text-center bg-purple-700/50';
    avgCell.innerHTML = `<span class="text-2xl font-bold text-white">${competitors.length > 0 ? (totalScore / competitors.length).toFixed(0) : 0}</span>`;
    totalRow.appendChild(avgCell);

    tbody.appendChild(totalRow);

    // Update summary cards
    updateSummaryCards(competitors);
}

function updateSummaryCards(competitors) {
    if (competitors.length === 0) return;

    // Find best compliance
    let bestCompliance = { name: '-', score: 0 };
    let totalTc = 0, tcCount = 0;
    let worstGap = { metric: '-', pct: 100 };

    competitors.forEach(c => {
        const comp = c.compliance || {};
        const score = c.overall_score ?? 0;

        if (score > bestCompliance.score) {
            bestCompliance = { name: c.shortName || c.name, score };
        }

        // T&C percentage
        const legalPct = comp.legal_disclaimer?.pct ?? 0;
        if (legalPct > 0) {
            totalTc += legalPct;
            tcCount++;
        }

        // Find worst gap
        const agePct = comp.age_verification?.pct ?? 0;
        const unsubPct = comp.unsubscribe?.pct ?? 0;
        if (agePct < worstGap.pct) {
            worstGap = { metric: 'Age Verification', pct: agePct };
        }
        if (unsubPct < worstGap.pct) {
            worstGap = { metric: 'Unsubscribe', pct: unsubPct };
        }
    });

    // Update DOM
    document.getElementById('best-compliance').textContent = bestCompliance.name;
    document.getElementById('best-compliance-score').textContent = `Score: ${bestCompliance.score.toFixed(1)}/10`;
    document.getElementById('tc-pct').textContent = tcCount > 0 ? Math.round(totalTc / tcCount) + '%' : '-';
    document.getElementById('critical-gap').textContent = worstGap.metric;
    document.getElementById('critical-gap-detail').textContent = worstGap.pct.toFixed(1) + '% across competitors';
    document.getElementById('industry-avg').textContent = competitors.length > 0 ? (competitors.reduce((sum, c) => sum + (c.overall_score ?? 0), 0) / competitors.length).toFixed(1) : '-';
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    renderTable();
});
</script>
@endpush
@endsection
