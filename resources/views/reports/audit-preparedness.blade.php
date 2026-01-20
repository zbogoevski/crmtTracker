@extends('layouts.dashboard')

@section('title', 'Report 3.3: Audit Preparedness | CRMTracker')

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

        .stub-tooltip:hover::after {
            opacity: 1;
        }

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

        .risk-low {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            color: #166534;
        }

        .risk-medium {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
        }

        .risk-high {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
        }
</style>
@endpush

@section('content')
<div class="space-y-6">
<header class="flex justify-between items-center mb-6">
<div class="flex items-center gap-3">
<span class="text-xs font-medium bg-teal-50 text-teal-700 px-2 py-1 rounded border border-teal-200">Module 3.3</span>
<span class="text-xs font-medium bg-rose-50 text-rose-700 px-2 py-1 rounded border border-rose-200">Compliance</span>
</div>
<button class="px-6 py-2.5 rounded-lg font-medium shadow-sm flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white">
<i class="fa-solid fa-download"></i> Export
</button>
</header>
<!-- Legend -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 mb-6">
<div class="flex flex-wrap items-center justify-between gap-4">
<div class="flex items-center gap-6 text-xs">
<span class="font-bold text-slate-600 uppercase tracking-wide">Risk Level:</span>
<div class="flex items-center gap-2">
<span class="w-3 h-3 rounded-full bg-green-500"></span>
<span class="text-slate-600">Low Risk (70-100)</span>
</div>
<div class="flex items-center gap-2">
<span class="w-3 h-3 rounded-full bg-amber-500"></span>
<span class="text-slate-600">Medium (40-69)</span>
</div>
<div class="flex items-center gap-2">
<span class="w-3 h-3 rounded-full bg-red-500"></span>
<span class="text-slate-600">High Risk (0-39)</span>
</div>
</div>
<div class="flex items-center gap-3 text-xs">
<span class="font-bold text-slate-600 uppercase tracking-wide">Sections:</span>
<span class="px-2 py-1 rounded bg-blue-100 text-blue-700">SPAM</span>
<span class="px-2 py-1 rounded bg-violet-100 text-violet-700">Language</span>
<span class="px-2 py-1 rounded bg-slate-200 text-slate-500">IP</span>
<span class="px-2 py-1 rounded bg-slate-200 text-slate-500">Cookie</span>
<span class="px-2 py-1 rounded bg-teal-100 text-teal-700">Opt-out</span>
<span class="px-2 py-1 rounded bg-rose-100 text-rose-700">Risk</span>
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
<span class="font-bold text-emerald-800">Best Overall</span>
</div>
<p class="text-2xl font-bold text-emerald-700 mb-1" id="best-overall">-</p>
<p class="text-sm text-emerald-600" id="best-overall-score">Score: -/100</p>
</div>
<div class="bg-blue-50 rounded-xl p-5 border border-blue-200">
<div class="flex items-center gap-2 mb-3">
<i class="fa-solid fa-envelope-circle-check text-blue-500"></i>
<span class="font-bold text-blue-800">SPAM Champion</span>
</div>
<p class="text-2xl font-bold text-blue-700 mb-1" id="spam-champion">-</p>
<p class="text-sm text-blue-600" id="spam-champion-detail">-</p>
</div>
<div class="bg-amber-50 rounded-xl p-5 border border-amber-200">
<div class="flex items-center gap-2 mb-3">
<i class="fa-solid fa-triangle-exclamation text-amber-500"></i>
<span class="font-bold text-amber-800">Critical Gap</span>
</div>
<p class="text-xl font-bold text-amber-700 mb-1" id="critical-gap">-</p>
<p class="text-sm text-amber-600" id="critical-gap-detail">-</p>
</div>
<div class="bg-teal-50 rounded-xl p-5 border border-teal-200">
<div class="flex items-center gap-2 mb-3">
<i class="fa-solid fa-hand text-teal-500"></i>
<span class="font-bold text-teal-800">Opt-out Best</span>
</div>
<p class="text-lg font-bold text-teal-700 mb-1" id="optout-best">-</p>
<p class="text-sm text-teal-600" id="optout-best-detail">-</p>
</div>
</div>
</div>

@push('scripts')
<script>
const laravelData = @json($data ?? []);
console.log('[3.3] Laravel data loaded:', laravelData.length, 'competitors');

// Transform Laravel data
function transformLaravelData(data) {
    return data.map(comp => ({
        id: comp.competitor_id,
        name: comp.competitor_name,
        shortName: comp.short_name,
        total_hits: comp.total_hits ?? 0,
        audit_readiness: comp.audit_readiness ?? {},
        spam_metrics: comp.spam_metrics ?? {},
        ip_strategy: comp.ip_strategy ?? {},
        cookie_tracking: comp.cookie_tracking ?? {},
        overall_score: comp.overall_score ?? 0,
        risk_level: comp.risk_level ?? 'high'
    }));
}

// Get score color class
function getScoreClass(score) {
    if (score >= 70) return 'score-good';
    if (score >= 40) return 'score-mid';
    return 'score-bad';
}

// Get risk level class
function getRiskClass(level) {
    return `risk-${level}`;
}

// Format spam score
function formatSpamScore(score) {
    return typeof score === 'number' ? score.toFixed(2) : '0.00';
}

// Format percentage
function formatPct(value) {
    return typeof value === 'number' ? value.toFixed(0) + '%' : '0%';
}

// Render table
function renderTable() {
    if (laravelData.length === 0) {
        document.getElementById('dynamic-tbody').innerHTML = '<tr><td colspan="100" class="py-8 text-center text-slate-400">No audit preparedness data available</td></tr>';
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

    // SECTION 1: SPAM FILTER TESTING
    const section1Row = document.createElement('tr');
    section1Row.innerHTML = `
        <td colspan="${competitors.length + 2}" class="py-2">
            <div class="flex items-center justify-center gap-3 py-3 bg-gradient-to-r from-blue-50 via-blue-100/50 to-blue-50 rounded-lg mx-2">
                <i class="fa-solid fa-envelope-circle-check text-blue-600"></i>
                <span class="font-bold text-blue-700 uppercase text-xs tracking-widest">SPAM Filter Testing</span>
                <span class="text-xs text-blue-500">(Multi-filter pass rate)</span>
            </div>
        </td>
    `;
    tbody.appendChild(section1Row);

    // Avg SPAM Score
    const spamScoreRow = document.createElement('tr');
    spamScoreRow.className = 'border-b border-slate-100 hover:bg-slate-50';
    spamScoreRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-slate-700 font-medium pl-4">Avg SPAM Score</span>
        </td>
    `;
    let spamTotal = 0;
    competitors.forEach(comp => {
        const score = comp.spam_metrics?.avg_score ?? 0;
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        const scoreClass = score < 0.5 ? 'score-good' : (score < 1.0 ? 'score-mid' : 'score-bad');
        cell.innerHTML = `<span class="text-lg font-bold ${scoreClass}">${formatSpamScore(score)}</span>`;
        spamScoreRow.appendChild(cell);
        spamTotal += score;
    });
    const spamAvg = document.createElement('td');
    spamAvg.className = 'py-3 px-4 text-center bg-slate-50 text-slate-600 font-medium';
    spamAvg.textContent = competitors.length > 0 ? formatSpamScore(spamTotal / competitors.length) : '0.00';
    spamScoreRow.appendChild(spamAvg);
    tbody.appendChild(spamScoreRow);

    // Pass Rate (<0.5)
    const passRateRow = document.createElement('tr');
    passRateRow.className = 'border-b border-slate-100 hover:bg-slate-50';
    passRateRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-slate-700 font-medium pl-4">Pass Rate (&lt;0.5)</span>
        </td>
    `;
    let passRateTotal = 0;
    competitors.forEach(comp => {
        const rate = comp.spam_metrics?.pass_rate ?? 0;
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        cell.innerHTML = `<span class="text-lg font-bold ${getScoreClass(rate)}">${formatPct(rate)}</span>`;
        passRateRow.appendChild(cell);
        passRateTotal += rate;
    });
    const passRateAvg = document.createElement('td');
    passRateAvg.className = 'py-3 px-4 text-center bg-slate-50 text-slate-600 font-medium';
    passRateAvg.textContent = competitors.length > 0 ? formatPct(passRateTotal / competitors.length) : '0%';
    passRateRow.appendChild(passRateAvg);
    tbody.appendChild(passRateRow);

    // Multi-Filter Pass (<1.0)
    const multiFilterRow = document.createElement('tr');
    multiFilterRow.className = 'border-b border-slate-100 hover:bg-slate-50';
    multiFilterRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-slate-700 font-medium pl-4">Multi-Filter Pass (&lt;1.0)</span>
        </td>
    `;
    let multiFilterTotal = 0;
    competitors.forEach(comp => {
        const rate = comp.spam_metrics?.multi_filter_pass ?? 0;
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        cell.innerHTML = `<span class="text-lg font-bold ${getScoreClass(rate)}">${formatPct(rate)}</span>`;
        multiFilterRow.appendChild(cell);
        multiFilterTotal += rate;
    });
    const multiFilterAvg = document.createElement('td');
    multiFilterAvg.className = 'py-3 px-4 text-center bg-slate-50 text-slate-600 font-medium';
    multiFilterAvg.textContent = competitors.length > 0 ? formatPct(multiFilterTotal / competitors.length) : '0%';
    multiFilterRow.appendChild(multiFilterAvg);
    tbody.appendChild(multiFilterRow);

    // Section Score (using pass rate as proxy)
    const section1ScoreRow = document.createElement('tr');
    section1ScoreRow.className = 'border-b border-slate-200 bg-blue-50/30';
    section1ScoreRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-blue-700 font-bold pl-4">Section Score</span>
        </td>
    `;
    let section1Total = 0;
    competitors.forEach(comp => {
        const score = comp.spam_metrics?.pass_rate ?? 0;
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        cell.innerHTML = `<span class="text-lg font-bold text-blue-600">${score.toFixed(0)}</span>`;
        section1ScoreRow.appendChild(cell);
        section1Total += score;
    });
    const section1Avg = document.createElement('td');
    section1Avg.className = 'py-3 px-4 text-center bg-blue-100 text-blue-700 font-bold';
    section1Avg.textContent = competitors.length > 0 ? Math.round(section1Total / competitors.length) : '0';
    section1ScoreRow.appendChild(section1Avg);
    tbody.appendChild(section1ScoreRow);

    // SECTION 2: LANGUAGE / ADVERTISING STANDARDS
    const section2Row = document.createElement('tr');
    section2Row.innerHTML = `
        <td colspan="${competitors.length + 2}" class="py-2">
            <div class="flex items-center justify-center gap-3 py-3 bg-gradient-to-r from-violet-50 via-violet-100/50 to-violet-50 rounded-lg mx-2">
                <i class="fa-solid fa-language text-violet-600"></i>
                <span class="font-bold text-violet-700 uppercase text-xs tracking-widest">Language / Advertising Standards</span>
                <span class="text-xs text-violet-500">(% standard attained)</span>
            </div>
        </td>
    `;
    tbody.appendChild(section2Row);

    // Gambling Disclosure - Use legal disclaimer as proxy
    const gamblingRow = document.createElement('tr');
    gamblingRow.className = 'border-b border-slate-100 hover:bg-slate-50';
    gamblingRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-slate-700 font-medium pl-4">Gambling Disclosure</span>
        </td>
    `;
    let gamblingTotal = 0;
    competitors.forEach(comp => {
        const legalPct = comp.audit_readiness?.compliance_flags?.legal_count > 0 && comp.total_hits > 0
            ? Math.round((comp.audit_readiness.compliance_flags.legal_count / comp.total_hits) * 100)
            : 0;
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        cell.innerHTML = `<span class="text-lg font-bold ${getScoreClass(legalPct)}">${legalPct}%</span>`;
        gamblingRow.appendChild(cell);
        gamblingTotal += legalPct;
    });
    const gamblingAvg = document.createElement('td');
    gamblingAvg.className = 'py-3 px-4 text-center bg-slate-50 text-slate-600 font-medium';
    gamblingAvg.textContent = competitors.length > 0 ? Math.round(gamblingTotal / competitors.length) + '%' : '0%';
    gamblingRow.appendChild(gamblingAvg);
    tbody.appendChild(gamblingRow);

    // Promo Disclosure - Always low for now
    const promoRow = document.createElement('tr');
    promoRow.className = 'border-b border-slate-100 hover:bg-slate-50';
    promoRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-slate-700 font-medium pl-4">Promo Disclosure</span>
        </td>
    `;
    competitors.forEach(() => {
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        cell.innerHTML = '<span class="text-lg font-bold score-bad">0%</span>';
        promoRow.appendChild(cell);
    });
    const promoAvg = document.createElement('td');
    promoAvg.className = 'py-3 px-4 text-center bg-slate-50 text-slate-600 font-medium';
    promoAvg.textContent = '0%';
    promoRow.appendChild(promoAvg);
    tbody.appendChild(promoRow);

    // Section Score
    const section2ScoreRow = document.createElement('tr');
    section2ScoreRow.className = 'border-b border-slate-200 bg-violet-50/30';
    section2ScoreRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-violet-700 font-bold pl-4">Section Score</span>
        </td>
    `;
    let section2Total = 0;
    competitors.forEach(comp => {
        const legalPct = comp.audit_readiness?.compliance_flags?.legal_count > 0 && comp.total_hits > 0
            ? Math.round((comp.audit_readiness.compliance_flags.legal_count / comp.total_hits) * 100)
            : 0;
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        cell.innerHTML = `<span class="text-lg font-bold text-violet-600">${legalPct}</span>`;
        section2ScoreRow.appendChild(cell);
        section2Total += legalPct;
    });
    const section2Avg = document.createElement('td');
    section2Avg.className = 'py-3 px-4 text-center bg-violet-100 text-violet-700 font-bold';
    section2Avg.textContent = competitors.length > 0 ? Math.round(section2Total / competitors.length) : '0';
    section2ScoreRow.appendChild(section2Avg);
    tbody.appendChild(section2ScoreRow);

    // SECTION 3: IP STRATEGY
    const section3Row = document.createElement('tr');
    section3Row.innerHTML = `
        <td colspan="${competitors.length + 2}" class="py-2">
            <div class="flex items-center justify-center gap-3 py-3 bg-gradient-to-r from-indigo-50 via-indigo-100/50 to-indigo-50 rounded-lg mx-2">
                <i class="fa-solid fa-trademark text-indigo-600"></i>
                <span class="font-bold text-indigo-700 uppercase text-xs tracking-widest">IP Strategy</span>
                <span class="text-xs text-indigo-500">(Domain, brand, trademark)</span>
            </div>
        </td>
    `;
    tbody.appendChild(section3Row);

    // Domain Consistency
    const domainRow = document.createElement('tr');
    domainRow.className = 'border-b border-slate-100 hover:bg-slate-50';
    domainRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-slate-700 font-medium pl-4">Domain Consistency</span>
        </td>
    `;
    let domainTotal = 0;
    competitors.forEach(comp => {
        const score = comp.ip_strategy?.domain_consistency ?? 0;
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        cell.innerHTML = `<span class="text-lg font-bold ${getScoreClass(score)}">${formatPct(score)}</span>`;
        domainRow.appendChild(cell);
        domainTotal += score;
    });
    const domainAvg = document.createElement('td');
    domainAvg.className = 'py-3 px-4 text-center bg-slate-50 text-slate-600 font-medium';
    domainAvg.textContent = competitors.length > 0 ? formatPct(domainTotal / competitors.length) : '0%';
    domainRow.appendChild(domainAvg);
    tbody.appendChild(domainRow);

    // Brand Protection
    const brandRow = document.createElement('tr');
    brandRow.className = 'border-b border-slate-100 hover:bg-slate-50';
    brandRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-slate-700 font-medium pl-4">Brand Protection</span>
        </td>
    `;
    let brandTotal = 0;
    competitors.forEach(comp => {
        const score = comp.ip_strategy?.brand_protection ?? 0;
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        cell.innerHTML = `<span class="text-lg font-bold ${getScoreClass(score)}">${formatPct(score)}</span>`;
        brandRow.appendChild(cell);
        brandTotal += score;
    });
    const brandAvg = document.createElement('td');
    brandAvg.className = 'py-3 px-4 text-center bg-slate-50 text-slate-600 font-medium';
    brandAvg.textContent = competitors.length > 0 ? formatPct(brandTotal / competitors.length) : '0%';
    brandRow.appendChild(brandAvg);
    tbody.appendChild(brandRow);

    // Trademark Usage
    const trademarkRow = document.createElement('tr');
    trademarkRow.className = 'border-b border-slate-100 hover:bg-slate-50';
    trademarkRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-slate-700 font-medium pl-4">Trademark Usage</span>
        </td>
    `;
    let trademarkTotal = 0;
    competitors.forEach(comp => {
        const score = comp.ip_strategy?.trademark_usage ?? 0;
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        cell.innerHTML = `<span class="text-lg font-bold ${getScoreClass(score)}">${formatPct(score)}</span>`;
        trademarkRow.appendChild(cell);
        trademarkTotal += score;
    });
    const trademarkAvg = document.createElement('td');
    trademarkAvg.className = 'py-3 px-4 text-center bg-slate-50 text-slate-600 font-medium';
    trademarkAvg.textContent = competitors.length > 0 ? formatPct(trademarkTotal / competitors.length) : '0%';
    trademarkRow.appendChild(trademarkAvg);
    tbody.appendChild(trademarkRow);

    // SECTION 4: COOKIE / TRACKING AUDIT
    const section4Row = document.createElement('tr');
    section4Row.innerHTML = `
        <td colspan="${competitors.length + 2}" class="py-2">
            <div class="flex items-center justify-center gap-3 py-3 bg-gradient-to-r from-teal-50 via-teal-100/50 to-teal-50 rounded-lg mx-2">
                <i class="fa-solid fa-cookie text-teal-600"></i>
                <span class="font-bold text-teal-700 uppercase text-xs tracking-widest">Cookie / Tracking Audit</span>
                <span class="text-xs text-teal-500">(Consent, GDPR compliance)</span>
            </div>
        </td>
    `;
    tbody.appendChild(section4Row);

    // Cookie Consent Present
    const cookieRow = document.createElement('tr');
    cookieRow.className = 'border-b border-slate-100 hover:bg-slate-50';
    cookieRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-slate-700 font-medium pl-4">Cookie Consent Present</span>
        </td>
    `;
    let cookieTotal = 0;
    competitors.forEach(comp => {
        const score = comp.cookie_tracking?.cookie_consent_present ?? 0;
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        cell.innerHTML = `<span class="text-lg font-bold ${getScoreClass(score)}">${formatPct(score)}</span>`;
        cookieRow.appendChild(cell);
        cookieTotal += score;
    });
    const cookieAvg = document.createElement('td');
    cookieAvg.className = 'py-3 px-4 text-center bg-slate-50 text-slate-600 font-medium';
    cookieAvg.textContent = competitors.length > 0 ? formatPct(cookieTotal / competitors.length) : '0%';
    cookieRow.appendChild(cookieAvg);
    tbody.appendChild(cookieRow);

    // GDPR Compliant
    const gdprRow = document.createElement('tr');
    gdprRow.className = 'border-b border-slate-100 hover:bg-slate-50';
    gdprRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-slate-700 font-medium pl-4">GDPR Compliant</span>
        </td>
    `;
    let gdprTotal = 0;
    competitors.forEach(comp => {
        const score = comp.cookie_tracking?.gdpr_compliant ?? 0;
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        cell.innerHTML = `<span class="text-lg font-bold ${getScoreClass(score)}">${formatPct(score)}</span>`;
        gdprRow.appendChild(cell);
        gdprTotal += score;
    });
    const gdprAvg = document.createElement('td');
    gdprAvg.className = 'py-3 px-4 text-center bg-slate-50 text-slate-600 font-medium';
    gdprAvg.textContent = competitors.length > 0 ? formatPct(gdprTotal / competitors.length) : '0%';
    gdprRow.appendChild(gdprAvg);
    tbody.appendChild(gdprRow);

    // SECTION 5: OPT-OUT STATE HANDLING
    const section5Row = document.createElement('tr');
    section5Row.innerHTML = `
        <td colspan="${competitors.length + 2}" class="py-2">
            <div class="flex items-center justify-center gap-3 py-3 bg-gradient-to-r from-teal-50 via-teal-100/50 to-teal-50 rounded-lg mx-2">
                <i class="fa-solid fa-hand text-teal-600"></i>
                <span class="font-bold text-teal-700 uppercase text-xs tracking-widest">Opt-out State Handling</span>
                <span class="text-xs text-teal-500">(1-click or impossible)</span>
            </div>
        </td>
    `;
    tbody.appendChild(section5Row);

    // Standard Unsubscribe Link
    const unsubRow = document.createElement('tr');
    unsubRow.className = 'border-b border-slate-100 hover:bg-slate-50';
    unsubRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-slate-700 font-medium pl-4">Standard Unsubscribe Link</span>
        </td>
    `;
    let unsubTotal = 0;
    competitors.forEach(comp => {
        const unsubPct = comp.audit_readiness?.compliance_flags?.unsubscribe_count > 0 && comp.total_hits > 0
            ? Math.round((comp.audit_readiness.compliance_flags.unsubscribe_count / comp.total_hits) * 100)
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

    // No Unsubscribe (Impossible)
    const noUnsubRow = document.createElement('tr');
    noUnsubRow.className = 'border-b border-slate-100 hover:bg-slate-50';
    noUnsubRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-slate-700 font-medium pl-4">No Unsubscribe (Impossible)</span>
        </td>
    `;
    let noUnsubTotal = 0;
    competitors.forEach(comp => {
        const unsubPct = comp.audit_readiness?.compliance_flags?.unsubscribe_count > 0 && comp.total_hits > 0
            ? Math.round((comp.audit_readiness.compliance_flags.unsubscribe_count / comp.total_hits) * 100)
            : 0;
        const noUnsubPct = 100 - unsubPct;
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        cell.innerHTML = `<span class="text-lg font-bold ${getScoreClass(100 - noUnsubPct)}">${noUnsubPct}%</span>`;
        noUnsubRow.appendChild(cell);
        noUnsubTotal += noUnsubPct;
    });
    const noUnsubAvg = document.createElement('td');
    noUnsubAvg.className = 'py-3 px-4 text-center bg-slate-50 text-slate-600 font-medium';
    noUnsubAvg.textContent = competitors.length > 0 ? Math.round(noUnsubTotal / competitors.length) + '%' : '0%';
    noUnsubRow.appendChild(noUnsubAvg);
    tbody.appendChild(noUnsubRow);

    // Section Score
    const section5ScoreRow = document.createElement('tr');
    section5ScoreRow.className = 'border-b border-slate-200 bg-teal-50/30';
    section5ScoreRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-teal-700 font-bold pl-4">Section Score</span>
        </td>
    `;
    let section5Total = 0;
    competitors.forEach(comp => {
        const unsubPct = comp.audit_readiness?.compliance_flags?.unsubscribe_count > 0 && comp.total_hits > 0
            ? Math.round((comp.audit_readiness.compliance_flags.unsubscribe_count / comp.total_hits) * 100)
            : 0;
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        cell.innerHTML = `<span class="text-lg font-bold text-teal-600">${unsubPct}</span>`;
        section5ScoreRow.appendChild(cell);
        section5Total += unsubPct;
    });
    const section5Avg = document.createElement('td');
    section5Avg.className = 'py-3 px-4 text-center bg-teal-100 text-teal-700 font-bold';
    section5Avg.textContent = competitors.length > 0 ? Math.round(section5Total / competitors.length) : '0';
    section5ScoreRow.appendChild(section5Avg);
    tbody.appendChild(section5ScoreRow);

    // SECTION 6: RISK EXPOSURE FORECASTING
    const section6Row = document.createElement('tr');
    section6Row.innerHTML = `
        <td colspan="${competitors.length + 2}" class="py-2">
            <div class="flex items-center justify-center gap-3 py-3 bg-gradient-to-r from-rose-50 via-rose-100/50 to-rose-50 rounded-lg mx-2">
                <i class="fa-solid fa-chart-line text-rose-600"></i>
                <span class="font-bold text-rose-700 uppercase text-xs tracking-widest">Risk Exposure Forecasting</span>
                <span class="text-xs text-rose-500">(Weighted averages)</span>
            </div>
        </td>
    `;
    tbody.appendChild(section6Row);

    // SPAM Risk Level
    const spamRiskRow = document.createElement('tr');
    spamRiskRow.className = 'border-b border-slate-100 hover:bg-slate-50';
    spamRiskRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-slate-700 font-medium pl-4">SPAM Risk Level</span>
        </td>
    `;
    competitors.forEach(comp => {
        const avgSpam = comp.spam_metrics?.avg_score ?? 0;
        const riskLevel = avgSpam < 0.5 ? 'low' : (avgSpam < 1.0 ? 'medium' : 'high');
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        cell.innerHTML = `<span class="px-3 py-1 rounded-full text-xs font-bold ${getRiskClass(riskLevel)}">${riskLevel.charAt(0).toUpperCase() + riskLevel.slice(1)}</span>`;
        spamRiskRow.appendChild(cell);
    });
    const spamRiskAvg = document.createElement('td');
    spamRiskAvg.className = 'py-3 px-4 text-center bg-slate-50 text-green-600 font-medium';
    spamRiskAvg.textContent = 'Low';
    spamRiskRow.appendChild(spamRiskAvg);
    tbody.appendChild(spamRiskRow);

    // Opt-out Risk Level
    const optoutRiskRow = document.createElement('tr');
    optoutRiskRow.className = 'border-b border-slate-100 hover:bg-slate-50';
    optoutRiskRow.innerHTML = `
        <td class="sticky-col py-3 px-4">
            <span class="text-slate-700 font-medium pl-4">Opt-out Risk Level</span>
        </td>
    `;
    competitors.forEach(comp => {
        const unsubPct = comp.audit_readiness?.compliance_flags?.unsubscribe_count > 0 && comp.total_hits > 0
            ? Math.round((comp.audit_readiness.compliance_flags.unsubscribe_count / comp.total_hits) * 100)
            : 0;
        const riskLevel = unsubPct >= 70 ? 'low' : (unsubPct >= 40 ? 'medium' : 'high');
        const cell = document.createElement('td');
        cell.className = 'py-3 px-4 text-center';
        cell.innerHTML = `<span class="px-3 py-1 rounded-full text-xs font-bold ${getRiskClass(riskLevel)}">${riskLevel.charAt(0).toUpperCase() + riskLevel.slice(1)}</span>`;
        optoutRiskRow.appendChild(cell);
    });
    const optoutRiskAvg = document.createElement('td');
    optoutRiskAvg.className = 'py-3 px-4 text-center bg-slate-50 text-amber-600 font-medium';
    optoutRiskAvg.textContent = 'Medium';
    optoutRiskRow.appendChild(optoutRiskAvg);
    tbody.appendChild(optoutRiskRow);

    // Update summary cards
    updateSummaryCards(competitors);
}

function updateSummaryCards(competitors) {
    if (competitors.length === 0) return;

    // Find best overall
    let bestOverall = { name: '-', score: 0 };
    let spamChampion = { name: '-', passRate: 0, avgScore: 0 };
    let criticalGap = { name: '-', unsubPct: 100 };
    let optoutBest = [];

    competitors.forEach(c => {
        if ((c.overall_score ?? 0) > bestOverall.score) {
            bestOverall = { name: c.shortName || c.name, score: c.overall_score ?? 0 };
        }

        const passRate = c.spam_metrics?.pass_rate ?? 0;
        const avgScore = c.spam_metrics?.avg_score ?? 0;
        if (passRate > spamChampion.passRate || (passRate === spamChampion.passRate && avgScore < spamChampion.avgScore)) {
            spamChampion = { name: c.shortName || c.name, passRate, avgScore };
        }

        const unsubPct = c.audit_readiness?.compliance_flags?.unsubscribe_count > 0 && c.total_hits > 0
            ? Math.round((c.audit_readiness.compliance_flags.unsubscribe_count / c.total_hits) * 100)
            : 0;
        if (unsubPct < criticalGap.unsubPct) {
            criticalGap = { name: c.shortName || c.name, unsubPct };
        }

        if (unsubPct === 100) {
            optoutBest.push(c.shortName || c.name);
        }
    });

    document.getElementById('best-overall').textContent = bestOverall.name;
    document.getElementById('best-overall-score').textContent = `Score: ${bestOverall.score.toFixed(0)}/100`;
    document.getElementById('spam-champion').textContent = spamChampion.name;
    document.getElementById('spam-champion-detail').textContent = `${spamChampion.passRate.toFixed(0)}% pass rate, ${formatSpamScore(spamChampion.avgScore)} avg`;
    document.getElementById('critical-gap').textContent = criticalGap.name;
    document.getElementById('critical-gap-detail').textContent = `No unsubscribe links (${criticalGap.unsubPct}% miss)`;
    document.getElementById('optout-best').textContent = optoutBest.length > 0 ? optoutBest.join(', ') : '-';
    document.getElementById('optout-best-detail').textContent = optoutBest.length > 0 ? `${optoutBest.length} have unsubscribe links` : 'No unsubscribe links found';
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    renderTable();
});
</script>
@endpush
@endsection
