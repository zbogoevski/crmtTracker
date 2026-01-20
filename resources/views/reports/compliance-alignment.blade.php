@extends('layouts.dashboard')

@section('title', 'Report 3.2: Compliance Alignment | CRMTracker')


        .score-good {
            color: #22c55e;
        }

        .score-mid {
            color: #f59e0b;
        }

        .score-bad {
            color: #ef4444;
        }

        .section-coming-soon {
            background: repeating-linear-gradient(45deg,
                    #f8fafc,
                    #f8fafc 10px,
                    #f1f5f9 10px,
                    #f1f5f9 20px);
        }
</style>
@endpush

@section('content')
<div class="space-y-6">
<header class="flex justify-between items-center mb-6">
<div class="flex items-center gap-3">
<span class="text-xs font-medium bg-cyan-50 text-cyan-700 px-2 py-1 rounded border border-cyan-200">Module 3.2</span>
<span class="text-xs font-medium bg-indigo-50 text-indigo-700 px-2 py-1 rounded border border-indigo-200">Compliance</span>
</div>
<button class="px-6 py-2.5 rounded-lg font-medium shadow-sm flex items-center gap-2 bg-cyan-600 hover:bg-cyan-700 text-white">
<i class="fa-solid fa-download"></i> Export
</button>
</header>
<!-- Legend -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 mb-6">
<div class="flex flex-wrap items-center justify-between gap-4">
<div class="flex items-center gap-6 text-xs">
<span class="font-bold text-slate-600 uppercase tracking-wide">Score Legend:</span>
<div class="flex items-center gap-2">
<span class="w-3 h-3 rounded-full bg-green-500"></span>
<span class="text-slate-600">Aligned (70-100)</span>
</div>
<div class="flex items-center gap-2">
<span class="w-3 h-3 rounded-full bg-amber-500"></span>
<span class="text-slate-600">Partial (40-69)</span>
</div>
<div class="flex items-center gap-2">
<span class="w-3 h-3 rounded-full bg-red-500"></span>
<span class="text-slate-600">Misaligned (0-39)</span>
</div>
</div>
<div class="flex items-center gap-4 text-xs">
<span class="font-bold text-slate-600 uppercase tracking-wide">Sections:</span>
<span class="px-2 py-1 rounded bg-cyan-100 text-cyan-700">Responsibility</span>
<span class="px-2 py-1 rounded bg-slate-200 text-slate-500">Historical</span>
<span class="px-2 py-1 rounded bg-indigo-100 text-indigo-700">Data Minimization</span>
<span class="px-2 py-1 rounded bg-rose-100 text-rose-700">Compliance Grade</span>
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
<i class="fa-solid fa-trophy text-emerald-500"></i>
<span class="font-bold text-emerald-800">Top Aligned</span>
</div>
<p class="text-2xl font-bold text-emerald-700 mb-1" id="top-aligned">-</p>
<p class="text-sm text-emerald-600" id="top-aligned-score">Score: -/100</p>
</div>
<div class="bg-cyan-50 rounded-xl p-5 border border-cyan-200">
<div class="flex items-center gap-2 mb-3">
<i class="fa-solid fa-crown text-cyan-500"></i>
<span class="font-bold text-cyan-800">RG Leader</span>
</div>
<p class="text-2xl font-bold text-cyan-700 mb-1" id="rg-leader">-</p>
<p class="text-sm text-cyan-600" id="rg-leader-detail">-</p>
</div>
<div class="bg-amber-50 rounded-xl p-5 border border-amber-200">
<div class="flex items-center gap-2 mb-3">
<i class="fa-solid fa-triangle-exclamation text-amber-500"></i>
<span class="font-bold text-amber-800">Critical Gap</span>
</div>
<p class="text-2xl font-bold text-amber-700 mb-1" id="critical-gap">-</p>
<p class="text-sm text-amber-600" id="critical-gap-detail">-</p>
</div>
<div class="bg-indigo-50 rounded-xl p-5 border border-indigo-200">
<div class="flex items-center gap-2 mb-3">
<i class="fa-solid fa-lightbulb text-indigo-500"></i>
<span class="font-bold text-indigo-800">Data Insight</span>
</div>
<p class="text-lg font-bold text-indigo-700 mb-1" id="data-insight">-</p>
<p class="text-sm text-indigo-600" id="data-insight-detail">-</p>
</div>
</div>
</div>

@push('scripts')
<script>
const laravelData = @json($data ?? []);
console.log('[3.2] Laravel data loaded:', laravelData.length, 'competitors');

// Transform Laravel data to match expected format
function transformLaravelData(data) {
    return data.map(comp => ({
        id: comp.competitor_id,
        name: comp.competitor_name,
        shortName: comp.short_name,
        total_hits: comp.total_hits ?? 0,
        alignment: {
            responsibility: {
                score: comp.alignment?.responsibility?.score ?? 0,
                rgCount: comp.alignment?.responsibility?.rg_count ?? 0,
                ageCount: comp.alignment?.responsibility?.age_count ?? 0,
                grade: comp.alignment?.responsibility?.grade ?? 'misaligned'
            },
            data_minimization: {
                score: comp.alignment?.data_minimization?.score ?? 0,
                unsubscribeCount: comp.alignment?.data_minimization?.unsubscribe_count ?? 0,
                footerCount: comp.alignment?.data_minimization?.footer_count ?? 0,
                grade: comp.alignment?.data_minimization?.grade ?? 'misaligned'
            },
            compliance_grade: {
                score: comp.alignment?.compliance_grade?.score ?? 0,
                legalCount: comp.alignment?.compliance_grade?.legal_count ?? 0,
                grade: comp.alignment?.compliance_grade?.grade ?? 'misaligned'
            }
        },
        historical_compliance: comp.historical_compliance ?? {},
        overall_score: comp.overall_score ?? 0,
        overall_grade: comp.overall_grade ?? 'misaligned'
    }));
}

// Get score color class
function getScoreClass(score) {
    if (score >= 70) return 'score-good';
    if (score >= 40) return 'score-mid';
    return 'score-bad';
}

// Format percentage
function formatPct(value) {
    return typeof value === 'number' ? value.toFixed(0) + '%' : '0%';
}

// Render table
function renderTable() {
    if (laravelData.length === 0) {
        document.getElementById('dynamic-tbody').innerHTML = '<tr><td colspan="100" class="py-8 text-center text-slate-400">No compliance alignment data available</td></tr>';
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

    // CRMTracker Score row
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

    // SECTION 1: RESPONSIBILITY MATCHING
    const section1Row = document.createElement('tr');
    section1Row.innerHTML = `
        <td colspan="${competitors.length + 2}" class="py-2">
            <div class="flex items-center justify-center gap-3 py-3 bg-gradient-to-r from-cyan-50 via-cyan-100/50 to-cyan-50 rounded-lg mx-2">
                <i class="fa-solid fa-user-shield text-cyan-600"></i>
                <span class="font-bold text-cyan-700 uppercase text-xs tracking-widest">Responsibility Matching</span>
                <span class="text-xs text-cyan-500">(Adult targeting, RG language)</span>
            </div>
        </td>
    `;
    tbody.appendChild(section1Row);

    // Adult Language (18+) - Always 0% for now
    const adultRow = document.createElement('tr');
    adultRow.className = 'border-b border-slate-100 hover:bg-slate-50';
    adultRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-slate-700 font-medium pl-4">Adult Language (18+/21+)</span>
        </td>
    `;
    let adultTotal = 0;
    competitors.forEach(() => {
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        cell.innerHTML = '<span class="text-lg font-bold score-bad">0%</span>';
        adultRow.appendChild(cell);
    });
    const adultAvg = document.createElement('td');
    adultAvg.className = 'py-3 px-4 text-center bg-slate-50 text-slate-600 font-medium';
    adultAvg.textContent = '0%';
    adultRow.appendChild(adultAvg);
    tbody.appendChild(adultRow);

    // RG Language Present
    const rgRow = document.createElement('tr');
    rgRow.className = 'border-b border-slate-100 hover:bg-slate-50';
    rgRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-slate-700 font-medium pl-4">RG Language Present</span>
        </td>
    `;
    let rgTotal = 0;
    competitors.forEach(comp => {
        const rgPct = comp.alignment?.responsibility?.rgCount > 0 && comp.total_hits > 0
            ? Math.round((comp.alignment.responsibility.rgCount / comp.total_hits) * 100)
            : 0;
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        cell.innerHTML = `<span class="text-lg font-bold ${getScoreClass(rgPct)}">${rgPct}%</span>`;
        rgRow.appendChild(cell);
        rgTotal += rgPct;
    });
    const rgAvg = document.createElement('td');
    rgAvg.className = 'py-3 px-4 text-center bg-slate-50 text-slate-600 font-medium';
    rgAvg.textContent = competitors.length > 0 ? Math.round(rgTotal / competitors.length) + '%' : '0%';
    rgRow.appendChild(rgAvg);
    tbody.appendChild(rgRow);

    // Encouraging Tonality - Use responsibility score as proxy
    const tonalityRow = document.createElement('tr');
    tonalityRow.className = 'border-b border-slate-100 hover:bg-slate-50';
    tonalityRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-slate-700 font-medium pl-4">Encouraging Tonality</span>
        </td>
    `;
    let tonalityTotal = 0;
    competitors.forEach(comp => {
        const score = comp.alignment?.responsibility?.score ?? 0;
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        cell.innerHTML = `<span class="text-lg font-bold ${getScoreClass(score)}">${score.toFixed(0)}%</span>`;
        tonalityRow.appendChild(cell);
        tonalityTotal += score;
    });
    const tonalityAvg = document.createElement('td');
    tonalityAvg.className = 'py-3 px-4 text-center bg-slate-50 text-slate-600 font-medium';
    tonalityAvg.textContent = competitors.length > 0 ? Math.round(tonalityTotal / competitors.length) + '%' : '0%';
    tonalityRow.appendChild(tonalityAvg);
    tbody.appendChild(tonalityRow);

    // Section Score
    const section1ScoreRow = document.createElement('tr');
    section1ScoreRow.className = 'border-b border-slate-200 bg-cyan-50/30';
    section1ScoreRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-cyan-700 font-bold pl-4">Section Score</span>
        </td>
    `;
    let section1Total = 0;
    competitors.forEach(comp => {
        const score = comp.alignment?.responsibility?.score ?? 0;
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        cell.innerHTML = `<span class="text-lg font-bold text-cyan-600">${score.toFixed(0)}</span>`;
        section1ScoreRow.appendChild(cell);
        section1Total += score;
    });
    const section1Avg = document.createElement('td');
    section1Avg.className = 'py-3 px-4 text-center bg-cyan-100 text-cyan-700 font-bold';
    section1Avg.textContent = competitors.length > 0 ? Math.round(section1Total / competitors.length) : '0';
    section1ScoreRow.appendChild(section1Avg);
    tbody.appendChild(section1ScoreRow);

    // SECTION 2: HISTORICAL COMPLIANCE
    const section2Row = document.createElement('tr');
    section2Row.innerHTML = `
        <td colspan="${competitors.length + 2}" class="py-2">
            <div class="flex items-center justify-center gap-3 py-3 bg-gradient-to-r from-slate-50 via-slate-100/50 to-slate-50 rounded-lg mx-2">
                <i class="fa-solid fa-clock-rotate-left text-slate-600"></i>
                <span class="font-bold text-slate-700 uppercase text-xs tracking-widest">Historical Compliance</span>
                <span class="text-xs text-slate-500">(12-month trends)</span>
            </div>
        </td>
    `;
    tbody.appendChild(section2Row);

    // Compliance Trend (12mo)
    const trendRow = document.createElement('tr');
    trendRow.className = 'border-b border-slate-100 hover:bg-slate-50';
    trendRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-slate-700 font-medium pl-4">Compliance Trend (12mo)</span>
        </td>
    `;
    let trendTotal = 0;
    competitors.forEach(comp => {
        const trend = comp.historical_compliance?.compliance_trend ?? {};
        let displayValue = '—';
        if (trend.direction) {
            const arrow = trend.direction === 'up' ? '↑' : (trend.direction === 'down' ? '↓' : '→');
            const color = trend.direction === 'up' ? 'score-good' : (trend.direction === 'down' ? 'score-bad' : 'score-mid');
            displayValue = `${arrow} ${Math.abs(trend.percentage ?? 0).toFixed(1)}%`;
            const cell = document.createElement('td');
            cell.className = 'py-3 px-4 text-center';
            cell.innerHTML = `<span class="text-lg font-bold ${color}">${displayValue}</span>`;
            trendRow.appendChild(cell);
            trendTotal += Math.abs(trend.percentage ?? 0);
        } else {
            const cell = document.createElement('td');
            cell.className = 'py-3 px-4 text-center text-slate-400';
            cell.textContent = '—';
            trendRow.appendChild(cell);
        }
    });
    const trendAvg = document.createElement('td');
    trendAvg.className = 'py-3 px-4 text-center bg-slate-50 text-slate-600 font-medium';
    trendAvg.textContent = competitors.length > 0 && trendTotal > 0 ? (trendTotal / competitors.length).toFixed(1) + '%' : '—';
    trendRow.appendChild(trendAvg);
    tbody.appendChild(trendRow);

    // Competitor Index
    const indexRow = document.createElement('tr');
    indexRow.className = 'border-b border-slate-100 hover:bg-slate-50';
    indexRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-slate-700 font-medium pl-4">Competitor Index</span>
        </td>
    `;
    let indexTotal = 0;
    competitors.forEach(comp => {
        const index = comp.historical_compliance?.competitor_index ?? null;
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        if (index !== null) {
            const color = index === 1 ? 'score-good' : (index <= 3 ? 'score-mid' : 'score-bad');
            cell.innerHTML = `<span class="text-lg font-bold ${color}">#${index}</span>`;
            indexRow.appendChild(cell);
            indexTotal += index;
        } else {
            cell.className = 'py-3 px-4 text-center text-slate-400';
            cell.textContent = '—';
            indexRow.appendChild(cell);
        }
    });
    const indexAvg = document.createElement('td');
    indexAvg.className = 'py-3 px-4 text-center bg-slate-50 text-slate-600 font-medium';
    indexAvg.textContent = competitors.length > 0 && indexTotal > 0 ? '#' + Math.round(indexTotal / competitors.length) : '—';
    indexRow.appendChild(indexAvg);
    tbody.appendChild(indexRow);

    // Complaints Registered
    const complaintsRow = document.createElement('tr');
    complaintsRow.className = 'border-b border-slate-100 hover:bg-slate-50';
    complaintsRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-slate-700 font-medium pl-4">Complaints Registered</span>
        </td>
    `;
    let complaintsTotal = 0;
    competitors.forEach(comp => {
        const count = comp.historical_compliance?.complaints_registered ?? 0;
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        const color = count === 0 ? 'score-good' : (count <= 2 ? 'score-mid' : 'score-bad');
        cell.innerHTML = `<span class="text-lg font-bold ${color}">${count}</span>`;
        complaintsRow.appendChild(cell);
        complaintsTotal += count;
    });
    const complaintsAvg = document.createElement('td');
    complaintsAvg.className = 'py-3 px-4 text-center bg-slate-50 text-slate-600 font-medium';
    complaintsAvg.textContent = competitors.length > 0 ? (complaintsTotal / competitors.length).toFixed(1) : '0';
    complaintsRow.appendChild(complaintsAvg);
    tbody.appendChild(complaintsRow);

    // SECTION 3: DATA MINIMIZATION
    const section3Row = document.createElement('tr');
    section3Row.innerHTML = `
        <td colspan="${competitors.length + 2}" class="py-2">
            <div class="flex items-center justify-center gap-3 py-3 bg-gradient-to-r from-indigo-50 via-indigo-100/50 to-indigo-50 rounded-lg mx-2">
                <i class="fa-solid fa-database text-indigo-600"></i>
                <span class="font-bold text-indigo-700 uppercase text-xs tracking-widest">Data Minimization</span>
                <span class="text-xs text-indigo-500">(GDPR/PIPEDA, leanness)</span>
            </div>
        </td>
    `;
    tbody.appendChild(section3Row);

    // Privacy/Opt-out Mentions (using unsubscribe + footer)
    const privacyRow = document.createElement('tr');
    privacyRow.className = 'border-b border-slate-100 hover:bg-slate-50';
    privacyRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-slate-700 font-medium pl-4">Privacy/Opt-out Mentions</span>
        </td>
    `;
    let privacyTotal = 0;
    competitors.forEach(comp => {
        const unsubscribePct = comp.alignment?.data_minimization?.unsubscribeCount > 0 && comp.total_hits > 0
            ? Math.round((comp.alignment.data_minimization.unsubscribeCount / comp.total_hits) * 100)
            : 0;
        const footerPct = comp.alignment?.data_minimization?.footerCount > 0 && comp.total_hits > 0
            ? Math.round((comp.alignment.data_minimization.footerCount / comp.total_hits) * 100)
            : 0;
        const combinedPct = Math.max(unsubscribePct, footerPct);
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        cell.innerHTML = `<span class="text-lg font-bold ${getScoreClass(combinedPct)}">${combinedPct}%</span>`;
        privacyRow.appendChild(cell);
        privacyTotal += combinedPct;
    });
    const privacyAvg = document.createElement('td');
    privacyAvg.className = 'py-3 px-4 text-center bg-slate-50 text-slate-600 font-medium';
    privacyAvg.textContent = competitors.length > 0 ? Math.round(privacyTotal / competitors.length) + '%' : '0%';
    privacyRow.appendChild(privacyAvg);
    tbody.appendChild(privacyRow);

    // Section Score
    const section3ScoreRow = document.createElement('tr');
    section3ScoreRow.className = 'border-b border-slate-200 bg-indigo-50/30';
    section3ScoreRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-indigo-700 font-bold pl-4">Section Score</span>
        </td>
    `;
    let section3Total = 0;
    competitors.forEach(comp => {
        const score = comp.alignment?.data_minimization?.score ?? 0;
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        cell.innerHTML = `<span class="text-lg font-bold text-indigo-600">${score.toFixed(0)}</span>`;
        section3ScoreRow.appendChild(cell);
        section3Total += score;
    });
    const section3Avg = document.createElement('td');
    section3Avg.className = 'py-3 px-4 text-center bg-indigo-100 text-indigo-700 font-bold';
    section3Avg.textContent = competitors.length > 0 ? Math.round(section3Total / competitors.length) : '0';
    section3ScoreRow.appendChild(section3Avg);
    tbody.appendChild(section3ScoreRow);

    // SECTION 4: COMPLIANCE GRADE
    const section4Row = document.createElement('tr');
    section4Row.innerHTML = `
        <td colspan="${competitors.length + 2}" class="py-2">
            <div class="flex items-center justify-center gap-3 py-3 bg-gradient-to-r from-rose-50 via-rose-100/50 to-rose-50 rounded-lg mx-2">
                <i class="fa-solid fa-graduation-cap text-rose-600"></i>
                <span class="font-bold text-rose-700 uppercase text-xs tracking-widest">Compliance Grade</span>
                <span class="text-xs text-rose-500">(Required elements, SPAM)</span>
            </div>
        </td>
    `;
    tbody.appendChild(section4Row);

    // T&C Present
    const tcRow = document.createElement('tr');
    tcRow.className = 'border-b border-slate-100 hover:bg-slate-50';
    tcRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-slate-700 font-medium pl-4">T&C Present</span>
        </td>
    `;
    let tcTotal = 0;
    competitors.forEach(comp => {
        const score = comp.alignment?.compliance_grade?.score ?? 0;
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        cell.innerHTML = `<span class="text-lg font-bold ${getScoreClass(score)}">${score.toFixed(0)}%</span>`;
        tcRow.appendChild(cell);
        tcTotal += score;
    });
    const tcAvg = document.createElement('td');
    tcAvg.className = 'py-3 px-4 text-center bg-slate-50 text-slate-600 font-medium';
    tcAvg.textContent = competitors.length > 0 ? Math.round(tcTotal / competitors.length) + '%' : '0%';
    tcRow.appendChild(tcAvg);
    tbody.appendChild(tcRow);

    // Unsubscribe Link
    const unsubRow = document.createElement('tr');
    unsubRow.className = 'border-b border-slate-100 hover:bg-slate-50';
    unsubRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-slate-700 font-medium pl-4">Unsubscribe Link</span>
        </td>
    `;
    let unsubTotal = 0;
    competitors.forEach(comp => {
        const unsubPct = comp.alignment?.data_minimization?.unsubscribeCount > 0 && comp.total_hits > 0
            ? Math.round((comp.alignment.data_minimization.unsubscribeCount / comp.total_hits) * 100)
            : 0;
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        cell.innerHTML = `<span class="text-lg font-bold ${getScoreClass(unsubPct)}">${unsubPct}%</span>`;
        unsubRow.appendChild(cell);
        unsubTotal += unsubPct;
    });
    const unsubAvg = document.createElement('td');
    unsubAvg.className = 'py-3 px-4 text-center bg-slate-50 text-slate-600 font-medium';
    unsubAvg.textContent = competitors.length > 0 ? Math.round(unsubTotal / competitors.length) + '%' : '0%';
    unsubRow.appendChild(unsubAvg);
    tbody.appendChild(unsubRow);

    // Footer Present
    const footerRow = document.createElement('tr');
    footerRow.className = 'border-b border-slate-100 hover:bg-slate-50';
    footerRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-slate-700 font-medium pl-4">Footer Present</span>
        </td>
    `;
    let footerTotal = 0;
    competitors.forEach(comp => {
        const footerPct = comp.alignment?.data_minimization?.footerCount > 0 && comp.total_hits > 0
            ? Math.round((comp.alignment.data_minimization.footerCount / comp.total_hits) * 100)
            : 0;
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        cell.innerHTML = `<span class="text-lg font-bold ${getScoreClass(footerPct)}">${footerPct}%</span>`;
        footerRow.appendChild(cell);
        footerTotal += footerPct;
    });
    const footerAvg = document.createElement('td');
    footerAvg.className = 'py-3 px-4 text-center bg-slate-50 text-slate-600 font-medium';
    footerAvg.textContent = competitors.length > 0 ? Math.round(footerTotal / competitors.length) + '%' : '0%';
    footerRow.appendChild(footerAvg);
    tbody.appendChild(footerRow);

    // Section Score
    const section4ScoreRow = document.createElement('tr');
    section4ScoreRow.className = 'border-b border-slate-200 bg-rose-50/30';
    section4ScoreRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-rose-700 font-bold pl-4">Section Score</span>
        </td>
    `;
    let section4Total = 0;
    competitors.forEach(comp => {
        const score = comp.alignment?.compliance_grade?.score ?? 0;
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        cell.innerHTML = `<span class="text-lg font-bold text-rose-600">${score.toFixed(0)}</span>`;
        section4ScoreRow.appendChild(cell);
        section4Total += score;
    });
    const section4Avg = document.createElement('td');
    section4Avg.className = 'py-3 px-4 text-center bg-rose-100 text-rose-700 font-bold';
    section4Avg.textContent = competitors.length > 0 ? Math.round(section4Total / competitors.length) : '0';
    section4ScoreRow.appendChild(section4Avg);
    tbody.appendChild(section4ScoreRow);

    // Update summary cards
    updateSummaryCards(competitors);
}

function updateSummaryCards(competitors) {
    if (competitors.length === 0) return;

    // Find top aligned
    let topAligned = { name: '-', score: 0 };
    let rgLeader = { name: '-', rgPct: 0 };
    let criticalGap = { metric: '-', pct: 100 };

    competitors.forEach(c => {
        if ((c.overall_score ?? 0) > topAligned.score) {
            topAligned = { name: c.shortName || c.name, score: c.overall_score ?? 0 };
        }

        const rgPct = c.alignment?.responsibility?.rgCount > 0 && c.total_hits > 0
            ? Math.round((c.alignment.responsibility.rgCount / c.total_hits) * 100)
            : 0;
        if (rgPct > rgLeader.rgPct) {
            rgLeader = { name: c.shortName || c.name, rgPct };
        }

        const unsubPct = c.alignment?.data_minimization?.unsubscribeCount > 0 && c.total_hits > 0
            ? Math.round((c.alignment.data_minimization.unsubscribeCount / c.total_hits) * 100)
            : 0;
        if (unsubPct < criticalGap.pct) {
            criticalGap = { metric: 'Unsubscribe', pct: unsubPct };
        }
    });

    document.getElementById('top-aligned').textContent = topAligned.name;
    document.getElementById('top-aligned-score').textContent = `Score: ${topAligned.score.toFixed(0)}/100`;
    document.getElementById('rg-leader').textContent = rgLeader.name;
    document.getElementById('rg-leader-detail').textContent = rgLeader.rgPct > 0 ? `Only competitor with ${rgLeader.rgPct}% RG language` : 'No RG language found';
    document.getElementById('critical-gap').textContent = criticalGap.metric;
    document.getElementById('critical-gap-detail').textContent = criticalGap.pct + '% across competitors';
    document.getElementById('data-insight').textContent = 'Data Minimization';
    document.getElementById('data-insight-detail').textContent = 'Average alignment score: ' + (competitors.length > 0 ? (competitors.reduce((sum, c) => sum + (c.alignment?.data_minimization?.score ?? 0), 0) / competitors.length).toFixed(0) : 0);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    renderTable();
});
</script>
@endpush
@endsection
