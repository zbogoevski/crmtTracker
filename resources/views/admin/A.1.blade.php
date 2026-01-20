@extends('layouts.dashboard')


@section('title', 'CRMTracker - Admin Settings')

@push('styles')
<style>
body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .tab-btn.active {
            border-bottom-color: #6366f1;
            color: #4f46e5;
            font-weight: 600;
        }

        input[type="range"] {
            -webkit-appearance: none;
            height: 8px;
            border-radius: 4px;
            background: #e2e8f0;
        }

        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #6366f1;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        input[type="range"]::-webkit-slider-thumb:hover {
            background: #4f46e5;
        }
</style>
@endpush

@section('content')
<main class="flex-1 ml-20 p-8 max-w-[1400px] mx-auto">
<!-- Header -->
<header class="flex justify-between items-center mb-8">
<div class="flex items-center gap-3">
<span
class="text-xs font-medium bg-red-50 text-red-700 px-2 py-1 rounded border border-red-200">Admin</span>
<span
class="text-xs font-medium bg-amber-50 text-amber-700 px-2 py-1 rounded border border-amber-200">Beta</span>
</div>
<div class="flex items-center gap-3">
<button onclick="exportSettings()"
class="px-4 py-2 rounded-lg font-medium shadow-sm flex items-center gap-2 transition-colors bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300">
<i class="fa-solid fa-download"></i>
Export
</button>
<label
class="px-4 py-2 rounded-lg font-medium shadow-sm flex items-center gap-2 transition-colors bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 cursor-pointer">
<i class="fa-solid fa-upload"></i>
Import
<input type="file" accept=".json" onchange="importSettings(event)" class="hidden">
</label>
</div>
</header>
<!-- Tabs -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
<!-- Tab Navigation - dynamically generated -->
<div id="tab-navigation" class="flex flex-wrap border-b border-slate-200">
<button
class="tab-btn active px-4 py-3 text-sm border-b-2 border-transparent hover:bg-slate-50 transition-colors"
data-tab="dashboard">
<i class="fa-solid fa-gauge mr-2"></i>Dashboard
</button>
<!-- Report tabs will be injected here by JavaScript -->
</div>
<!-- Tab Content -->
<div class="p-6">
<!-- Dashboard Tab -->
<div id="tab-dashboard" class="tab-content">
<h3 class="font-bold text-lg text-slate-800 mb-2">Dashboard Module Weights</h3>
<p class="text-sm text-slate-500 mb-6">Configure how each module contributes to the overall
CRMTracker Score.</p>
<div id="sliders-dashboard" class="space-y-4"></div>
</div>
<!-- Dynamic report tabs - container for JS generation -->
<div id="dynamic-report-tabs"></div>
<!-- C3 Lifecycle Tab -->
<div id="tab-C3" class="tab-content hidden">
<h3 class="font-bold text-lg text-slate-800 mb-2">
<i class="fa-solid fa-leaf text-green-500 mr-2"></i>C3 Customer Lifecycle
</h3>
<p class="text-sm text-slate-500 mb-6">Configure lifecycle stage thresholds and tracking
behavior.</p>
<div class="grid grid-cols-3 gap-6">
<!-- Acquisition -->
<div class="bg-green-50 rounded-xl border border-green-200 p-5">
<div class="flex items-center gap-2 mb-4">
<span class="text-lg">🟢</span>
<h4 class="font-semibold text-green-800">Acquisition (ACQ)</h4>
</div>
<p class="text-xs text-green-700 mb-4">New player onboarding period</p>
<div class="space-y-3">
<div>
<label class="text-sm font-medium text-green-700">Duration (days after
registration)</label>
<input type="number" id="c3-acq-duration" value="30" min="7" max="90"
class="w-full mt-1 px-3 py-2 border border-green-300 rounded-lg text-center font-bold text-lg"
onchange="saveC3Config()">
</div>
<p class="text-xs text-green-600">After this, transitions to Retention</p>
</div>
</div>
<!-- Retention -->
<div class="bg-blue-50 rounded-xl border border-blue-200 p-5">
<div class="flex items-center gap-2 mb-4">
<span class="text-lg">🔵</span>
<h4 class="font-semibold text-blue-800">Retention (RET)</h4>
</div>
<p class="text-xs text-blue-700 mb-4">Active player engagement</p>
<div class="space-y-3">
<div>
<label class="text-sm font-medium text-blue-700">Inactivity threshold
(days)</label>
<input type="number" id="c3-ret-inactivity" value="14" min="7" max="60"
class="w-full mt-1 px-3 py-2 border border-blue-300 rounded-lg text-center font-bold text-lg"
onchange="saveC3Config()">
</div>
<p class="text-xs text-blue-600">No activity after X days → Reactivation</p>
</div>
</div>
<!-- Reactivation -->
<div class="bg-amber-50 rounded-xl border border-amber-200 p-5">
<div class="flex items-center gap-2 mb-4">
<span class="text-lg">🟡</span>
<h4 class="font-semibold text-amber-800">Reactivation (REA)</h4>
</div>
<p class="text-xs text-amber-700 mb-4">Win-back campaigns</p>
<div class="space-y-3">
<div>
<label class="text-sm font-medium text-amber-700">Max reactivation window
(days)</label>
<input type="number" id="c3-rea-window" value="90" min="30" max="365"
class="w-full mt-1 px-3 py-2 border border-amber-300 rounded-lg text-center font-bold text-lg"
onchange="saveC3Config()">
</div>
<p class="text-xs text-amber-600">Beyond this = Churned (excluded from reports)</p>
</div>
</div>
</div>
<!-- C3 Save Status -->
<div class="mt-6 p-4 bg-slate-50 rounded-lg border border-slate-200">
<div class="flex items-center justify-between">
<div>
<span class="text-sm font-medium text-slate-600">C3 Config Status:</span>
<span id="c3-config-status" class="ml-2 text-sm text-green-600 font-medium">Saved to
localStorage</span>
<div id="date-range-container" class="flex items-center"></div>
<button onclick="resetC3Defaults()"
class="text-sm text-slate-500 hover:text-slate-700">
<i class="fa-solid fa-rotate-left mr-1"></i>Reset to Defaults
</button>
</div>
</div>
</div>
<!-- Total & Actions -->
<div class="mt-8 pt-6 border-t border-slate-200">
<div class="flex items-center justify-between">
<div id="total-indicator" class="flex items-center gap-3">
<span class="text-sm font-semibold text-slate-600">Total:</span>
<span id="total-value" class="text-lg font-bold text-green-600">100%</span>
<i id="total-check" class="fa-solid fa-check-circle text-green-500"></i>
</div>
<div class="flex items-center gap-3">
<button onclick="resetToDefaults()"
class="px-4 py-2 rounded-lg font-medium shadow-sm flex items-center gap-2 transition-colors bg-slate-100 hover:bg-slate-200 text-slate-700">
<i class="fa-solid fa-rotate-left"></i>
Reset to Defaults
</button>
<button onclick="applyChanges()"
class="px-6 py-2.5 rounded-lg font-medium shadow-sm flex items-center gap-2 transition-colors bg-indigo-600 hover:bg-indigo-700 text-white">
<i class="fa-solid fa-check"></i>
Apply Changes
</button>
</div>
</div>
</div>
</div>
</div>
<!-- Preview Panel -->
<div class="mt-6 bg-white rounded-xl shadow-sm border border-slate-100 p-6">
<h3 class="font-bold text-lg text-slate-800 mb-4">
<i class="fa-solid fa-eye text-indigo-500 mr-2"></i>
Live Preview
</h3>
<div id="preview-panel" class="text-center py-8">
<p class="text-slate-500">
<i class="fa-solid fa-chart-line text-indigo-400 mr-2"></i>
Weight changes will affect scores on the dashboard.
</p>
<p class="text-sm text-slate-400 mt-2">
Select a competitor group on the Dashboard to see score impacts.
</p>
</div>
</div>
</main>
</div>
<script>
// Default weight configuration (embedded to avoid fetch issues with file:// protocol)
const DEFAULT_WEIGHTS = {
version: "2.0",
dashboard: {
module_weights: {
"1.3": 0.18, "2.1": 0.12, "2.2": 0.08, "2.3": 0.08, "2.4": 0.08,
"3.1": 0.14, "3.2": 0.05, "3.3": 0.05, "4.1": 0.10, "5.1": 0.12
}
},
modules: {
"1.3": {
name: "CRM Scorecard", sections: {
personalization: { weight: 0.30, subs: { name_usage: 0.50, dynamic_content: 0.50 } },
frequency: { weight: 0.20, subs: { emails_per_week: 0.50, timing_consistency: 0.50 } },
channel: { weight: 0.20, subs: { email_share: 0.34, sms_share: 0.33, push_share: 0.33 } },
journey: { weight: 0.30, subs: { welcome: 0.25, activation: 0.25, retention: 0.25, winback: 0.25 } }
}
},
"2.1": {
name: "Content Analysis", sections: {
headers: { weight: 0.20, subs: { subject_length: 0.30, preview_text: 0.30, personalized: 0.40 } },
body_copy: { weight: 0.40, subs: { tone: 0.25, urgency_score: 0.25, cta_count: 0.25, word_count: 0.25 } },
promotions: { weight: 0.30, subs: { offer_value: 0.40, wagering_req: 0.30, fairness_score: 0.30 } },
screenshots: { weight: 0.10, subs: {} }
}
},
"2.4": {
name: "Jackpots/Offers/Games", sections: {
jackpots: { weight: 0.30, subs: { progressive_count: 0.40, largest_jackpot: 0.40, daily_drops: 0.20 } },
offers: { weight: 0.40, subs: { avg_offer_value: 0.35, best_match: 0.25, free_spins: 0.20, wagering: 0.20 } },
games: { weight: 0.30, subs: { total_games: 0.40, new_games: 0.30, providers: 0.30 } }
}
},
"3.1": {
name: "Compliance", sections: {
completeness: { weight: 0.25, subs: { legal_disclaimer: 0.30, footer_present: 0.25, physical_address: 0.20, ownership_disclosure: 0.25 } },
header_footer: { weight: 0.25, subs: { marketing_standards: 0.40, age_verification: 0.30, rg_links: 0.30 } },
opt_in_unsubscribe: { weight: 0.25, subs: { unsubscribe_link: 0.40, clear_cta: 0.30, one_click_optout: 0.30 } },
traceability: { weight: 0.25, subs: { sender_transparency: 0.40, domain_consistency: 0.30, audit_trail: 0.30 } }
}
},
"4.1": {
name: "License", sections: {
license_info: { weight: 0.40, subs: { jurisdiction: 0.25, license_number: 0.25, license_owner: 0.25, issue_date: 0.25 } },
entity_ownership: { weight: 0.10, subs: {} },
rg_links: { weight: 0.25, subs: { begambleaware: 0.25, gamstop: 0.25, rg_messaging: 0.25, age_verification: 0.25 } },
social_media: { weight: 0.25, subs: { twitter: 0.25, instagram: 0.25, facebook: 0.25, youtube: 0.25 } }
}
},
"5.1": {
name: "Valuation", sections: {
ghost_costs: { weight: 0.25, subs: { unused_licenses: 0.50, duplicate_tools: 0.50 } },
journey_gaps: { weight: 0.35, subs: { lifecycle_coverage: 0.50, automation_opportunity: 0.50 } },
tech_consolidation: { weight: 0.15, subs: { integration_score: 0.50, migration_readiness: 0.50 } },
risk_exposure: { weight: 0.25, subs: { compliance_risk: 0.50, regulatory_risk: 0.50 } }
}
},
"5.2": {
name: "Compliance Exposure", sections: {
risk_exposure: { weight: 0.30, subs: { footer_compliance: 0.40, unsubscribe_present: 0.30, tc_present: 0.30 } },
clv_forecast: { weight: 0.25, subs: { promo_rate: 0.40, dominant_offer: 0.30, weekly_freq: 0.30 } },
churn_breakdown: { weight: 0.25, subs: { journey_coverage: 0.50, reactivation: 0.25, winback: 0.25 } },
operational_efficiency: { weight: 0.10, subs: {} },
brand_equity: { weight: 0.10, subs: {} }
}
},
"D.6": { name: "CRMT", weights: { transparency: 0.40, risk: 0.30, completeness: 0.30 } }
}
};
DEFAULT_WEIGHTS.defaults = JSON.parse(JSON.stringify({ dashboard: DEFAULT_WEIGHTS.dashboard, modules: {} }));
for (const [k, v] of Object.entries(DEFAULT_WEIGHTS.modules)) {
DEFAULT_WEIGHTS.defaults.modules[k] = { ...v.weights };
}
let weightsConfig = null;
let currentTab = 'dashboard';
// Label mappings
const labelMap = {
'personalization': 'Personalization',
'frequency': 'Frequency',
'channel': 'Channel Mix',
'journey': 'Journey Coverage',
'footer': 'Legal Footer',
'tc_content': 'T&C Content',
'age_notice': 'Age Notice',
'rg_message': 'RG Messaging',
'unsubscribe': 'Unsubscribe Link',
'rg_links': 'RG Links',
'social_media': 'Social Media',
'license_entity': 'License & Entity',
'ghost_costs': 'Ghost Costs',
'journey_gaps': 'Journey Gaps',
'tech_consolidation': 'Tech Consolidation',
'risk_exposure': 'Risk Exposure',
'transparency': 'Transparency',
'risk': 'Risk',
'completeness': 'Completeness',
'1.3': 'Module 1.3 - CRM',
'2.1': 'Module 2.1 - Content',
'2.2': 'Module 2.2 - Quality',
'2.3': 'Module 2.3 - Risk',
'3.1': 'Module 3.1 - Compliance',
'3.2': 'Module 3.2 - Alignment',
'3.3': 'Module 3.3 - Audit',
'4.1': 'Module 4.1 - License',
'5.1': 'Module 5.1 - Valuation',
'5.2': 'Module 5.2 - Compliance Exposure',
'clv_forecast': 'CLV Forecast',
'churn_breakdown': 'Churn Breakdown',
'operational_efficiency': 'Operational Efficiency',
'brand_equity': 'Brand Equity',
// Section labels
'headers': 'Headers',
'body_copy': 'Body Copy',
'promotions': 'Promotions',
'screenshots': 'Screenshots',
'completeness': 'Completeness',
'header_footer': 'Header/Footer',
'opt_in_unsubscribe': 'Opt-in/Unsubscribe',
'traceability': 'Traceability',
'license_info': 'License Info',
'entity_ownership': 'Entity Ownership',
// Sub-weight labels
'subject_length': 'Subject Length',
'preview_text': 'Preview Text',
'personalized': 'Personalized',
'tone': 'Tone',
'urgency_score': 'Urgency Score',
'cta_count': 'CTA Count',
'word_count': 'Word Count',
'offer_value': 'Offer Value',
'wagering_req': 'Wagering Req',
'fairness_score': 'Fairness Score',
'legal_disclaimer': 'Legal Disclaimer',
'footer_present': 'Footer Present',
'physical_address': 'Physical Address',
'ownership_disclosure': 'Ownership Disclosure',
'marketing_standards': 'Marketing Standards',
'age_verification': 'Age Verification',
'unsubscribe_link': 'Unsubscribe Link',
'clear_cta': 'Clear CTA',
'one_click_optout': 'One-Click Opt-out',
'sender_transparency': 'Sender Transparency',
'domain_consistency': 'Domain Consistency',
'audit_trail': 'Audit Trail',
'jurisdiction': 'Jurisdiction',
'license_number': 'License Number',
'license_owner': 'License Owner',
'issue_date': 'Issue Date',
'begambleaware': 'BeGambleAware',
'gamstop': 'GamStop',
'rg_messaging': 'RG Messaging',
'twitter': 'Twitter/X',
'instagram': 'Instagram',
'facebook': 'Facebook',
'youtube': 'YouTube',
'unused_licenses': 'Unused Licenses',
'duplicate_tools': 'Duplicate Tools',
'lifecycle_coverage': 'Lifecycle Coverage',
'automation_opportunity': 'Automation Opportunity',
'integration_score': 'Integration Score',
'migration_readiness': 'Migration Readiness',
'compliance_risk': 'Compliance Risk',
'regulatory_risk': 'Regulatory Risk',
'footer_compliance': 'Footer Compliance',
'unsubscribe_present': 'Unsubscribe Present',
'tc_present': 'T&C Present',
'promo_rate': 'Promo Rate',
'dominant_offer': 'Dominant Offer',
'weekly_freq': 'Weekly Frequency',
'journey_coverage': 'Journey Coverage',
'reactivation': 'Reactivation',
'winback': 'Winback',
// 1.3 sub-weights
'name_usage': 'Name Usage',
'dynamic_content': 'Dynamic Content',
'emails_per_week': 'Emails/Week',
'timing_consistency': 'Timing Consistency',
'email_share': 'Email Share',
'sms_share': 'SMS Share',
'push_share': 'Push Share',
'welcome': 'Welcome',
'activation': 'Activation',
'retention': 'Retention',
// 2.4 sub-weights
'2.4': 'Module 2.4 - Jackpots/Offers',
'jackpots': 'Jackpots',
'offers': 'Offers',
'games': 'Games',
'progressive_count': 'Progressive Count',
'largest_jackpot': 'Largest Jackpot',
'daily_drops': 'Daily Drops',
'avg_offer_value': 'Avg Offer Value',
'best_match': 'Best Match %',
'free_spins': 'Free Spins',
'wagering': 'Wagering',
'total_games': 'Total Games',
'new_games': 'New Games',
'providers': 'Providers',
'retention': 'Retention'
};
// Load weights - uses embedded defaults, tries fetch for any saved changes
async function loadWeights() {
// Start with embedded defaults
weightsConfig = JSON.parse(JSON.stringify(DEFAULT_WEIGHTS));
// Check localStorage for saved changes
const saved = localStorage.getItem('crmt_weights');
if (saved) {
try {
weightsConfig = JSON.parse(saved);
console.log('[Admin] Loaded from localStorage');
} catch (e) {
console.warn('[Admin] Invalid localStorage data, using defaults');
}
}
// Try to fetch from file (works when served via HTTP)
try {
const response = await fetch('../data/weights.json');
if (response.ok) {
const fetched = await response.json();
weightsConfig = fetched;
console.log('[Admin] Loaded from weights.json');
}
} catch (e) {
console.log('[Admin] Using embedded defaults (fetch unavailable)');
}
renderAllTabs();
}
// Render sliders for a tab
function renderSliders(containerId, weights) {
const container = document.getElementById(containerId);
console.log('[Admin] Rendering sliders for', containerId, 'weights:', weights);
if (!container) {
console.error('[Admin] Container not found:', containerId);
return;
}
if (!weights || typeof weights !== 'object') {
console.error('[Admin] Invalid weights data for', containerId);
container.innerHTML = '<p class="text-amber-500">No weights data available</p>';
return;
}
container.innerHTML = '';
const entries = Object.entries(weights);
console.log('[Admin] Rendering', entries.length, 'sliders');
for (const [key, value] of entries) {
const pct = Math.round(value * 100);
const label = labelMap[key] || key;
container.innerHTML += `
<div class="flex items-center gap-4">
<span class="w-40 text-sm font-medium text-slate-700">${label}</span>
<input type="range" 
class="flex-1 weight-slider" 
data-key="${key}" 
min="0" max="100" 
value="${pct}"
oninput="updateWeight(this)">
<div class="w-20 flex items-center gap-1">
<input type="number" 
class="w-14 px-2 py-1 text-sm border border-slate-300 rounded text-center weight-input"
data-key="${key}"
min="0" max="100"
value="${pct}"
onchange="updateWeightFromInput(this)">
<span class="text-sm text-slate-500">%</span>
</div>
</div>
`;
}
}
// Render hierarchical sections with collapsible sub-weights
function renderSections(containerId, sections, moduleId) {
const container = document.getElementById(containerId);
if (!container || !sections) return;
container.innerHTML = '';
for (const [sectionKey, sectionData] of Object.entries(sections)) {
const sectionPct = Math.round(sectionData.weight * 100);
const sectionLabel = labelMap[sectionKey] || sectionKey.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
const hasSubs = sectionData.subs && Object.keys(sectionData.subs).length > 0;
const sectionId = `section-${moduleId}-${sectionKey}`;
let subsHtml = '';
if (hasSubs) {
subsHtml = `<div id="${sectionId}-subs" class="hidden pl-6 pt-3 border-l-2 border-slate-200 ml-4 space-y-3">`;
for (const [subKey, subValue] of Object.entries(sectionData.subs)) {
const subPct = Math.round(subValue * 100);
const subLabel = labelMap[subKey] || subKey.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
subsHtml += `
<div class="flex items-center gap-4">
<span class="w-36 text-sm text-slate-600">↳ ${subLabel}</span>
<input type="range" class="flex-1 sub-slider" 
data-module="${moduleId}" data-section="${sectionKey}" data-sub="${subKey}"
min="0" max="100" value="${subPct}"
oninput="updateSubWeight(this)">
<div class="w-16 flex items-center gap-1">
<input type="number" class="w-12 px-1 py-0.5 text-xs border border-slate-300 rounded text-center sub-input"
data-module="${moduleId}" data-section="${sectionKey}" data-sub="${subKey}"
min="0" max="100" value="${subPct}"
onchange="updateSubWeightFromInput(this)">
<span class="text-xs text-slate-500">%</span>
</div>
</div>`;
}
subsHtml += `<div class="text-xs text-slate-400 mt-2">Sub-total: <span id="${sectionId}-subtotal" class="font-medium">100%</span></div></div>`;
}
container.innerHTML += `
<div class="border border-slate-200 rounded-lg overflow-hidden mb-3">
<div class="flex items-center gap-4 px-4 py-3 bg-slate-50 ${hasSubs ? 'cursor-pointer' : ''}" 
${hasSubs ? `onclick="toggleSection('${sectionId}')"` : ''}>
${hasSubs ? `<i id="${sectionId}-icon" class="fa-solid fa-chevron-right text-xs text-slate-400 transition-transform"></i>` : '<span class="w-3"></span>'}
<span class="w-40 text-sm font-medium text-slate-700">${sectionLabel}</span>
<input type="range" class="flex-1 section-slider" 
data-module="${moduleId}" data-section="${sectionKey}"
min="0" max="100" value="${sectionPct}"
oninput="updateSectionWeight(this)" onclick="event.stopPropagation()">
<div class="w-20 flex items-center gap-1">
<input type="number" class="w-14 px-2 py-1 text-sm border border-slate-300 rounded text-center section-input"
data-module="${moduleId}" data-section="${sectionKey}"
min="0" max="100" value="${sectionPct}"
onchange="updateSectionWeightFromInput(this)" onclick="event.stopPropagation()">
<span class="text-sm text-slate-500">%</span>
</div>
${!hasSubs ? '<span class="text-xs text-slate-400 italic">stub</span>' : ''}
</div>
${subsHtml}
</div>`;
}
// Add section total indicator
container.innerHTML += `<div class="text-sm text-slate-500 mt-4">Section Total: <span id="${containerId}-total" class="font-medium">100%</span></div>`;
updateSectionTotals(moduleId);
}
function toggleSection(sectionId) {
const subs = document.getElementById(`${sectionId}-subs`);
const icon = document.getElementById(`${sectionId}-icon`);
if (subs) subs.classList.toggle('hidden');
if (icon) icon.classList.toggle('rotate-90');
}
function updateSectionWeight(slider) {
const moduleId = slider.dataset.module;
const sectionKey = slider.dataset.section;
const value = parseInt(slider.value);
const input = slider.parentElement.parentElement.querySelector('.section-input');
if (input) input.value = value;
if (weightsConfig?.modules?.[moduleId]?.sections?.[sectionKey]) {
weightsConfig.modules[moduleId].sections[sectionKey].weight = value / 100;
}
updateSectionTotals(moduleId);
}
function updateSectionWeightFromInput(input) {
const moduleId = input.dataset.module;
const sectionKey = input.dataset.section;
let value = Math.max(0, Math.min(100, parseInt(input.value) || 0));
input.value = value;
const slider = input.parentElement.parentElement.querySelector('.section-slider');
if (slider) slider.value = value;
if (weightsConfig?.modules?.[moduleId]?.sections?.[sectionKey]) {
weightsConfig.modules[moduleId].sections[sectionKey].weight = value / 100;
}
updateSectionTotals(moduleId);
}
function updateSubWeight(slider) {
const { module: moduleId, section: sectionKey, sub: subKey } = slider.dataset;
const value = parseInt(slider.value);
const input = slider.parentElement.querySelector('.sub-input');
if (input) input.value = value;
if (weightsConfig?.modules?.[moduleId]?.sections?.[sectionKey]?.subs?.[subKey] !== undefined) {
weightsConfig.modules[moduleId].sections[sectionKey].subs[subKey] = value / 100;
}
updateSubTotals(moduleId, sectionKey);
}
function updateSubWeightFromInput(input) {
const { module: moduleId, section: sectionKey, sub: subKey } = input.dataset;
let value = Math.max(0, Math.min(100, parseInt(input.value) || 0));
input.value = value;
const slider = input.parentElement.parentElement.querySelector('.sub-slider');
if (slider) slider.value = value;
if (weightsConfig?.modules?.[moduleId]?.sections?.[sectionKey]?.subs?.[subKey] !== undefined) {
weightsConfig.modules[moduleId].sections[sectionKey].subs[subKey] = value / 100;
}
updateSubTotals(moduleId, sectionKey);
}
function updateSectionTotals(moduleId) {
const sections = weightsConfig?.modules?.[moduleId]?.sections;
if (!sections) return;
const total = Object.values(sections).reduce((sum, s) => sum + (s.weight || 0), 0);
const totalPct = Math.round(total * 100);
const el = document.getElementById(`sliders-${moduleId}-total`);
if (el) {
el.textContent = `${totalPct}%`;
el.className = totalPct === 100 ? 'font-medium text-green-600' : 'font-medium text-red-500';
}
}
function updateSubTotals(moduleId, sectionKey) {
const subs = weightsConfig?.modules?.[moduleId]?.sections?.[sectionKey]?.subs;
if (!subs) return;
const total = Object.values(subs).reduce((sum, v) => sum + v, 0);
const totalPct = Math.round(total * 100);
const el = document.getElementById(`section-${moduleId}-${sectionKey}-subtotal`);
if (el) {
el.textContent = `${totalPct}%`;
el.className = totalPct === 100 ? 'font-medium text-green-600' : 'font-medium text-red-500';
}
}
// Render dashboard module weights grouped by parent module
function renderDashboardGrouped(containerId, moduleWeights) {
const container = document.getElementById(containerId);
if (!container || !moduleWeights) return;
container.innerHTML = '';
// Group modules by parent (1.x → Module 1, 2.x → Module 2, etc.)
const groups = {};
for (const [modId, weight] of Object.entries(moduleWeights)) {
const parent = modId.split('.')[0];
if (!groups[parent]) groups[parent] = [];
groups[parent].push({ id: modId, weight });
}
const parentNames = {
'1': 'Module 1 - CRM',
'2': 'Module 2 - Content',
'3': 'Module 3 - Compliance',
'4': 'Module 4 - License',
'5': 'Module 5 - Valuation',
'D': 'Module D - CRMT'
};
for (const [parent, modules] of Object.entries(groups)) {
const groupId = `dashboard-group-${parent}`;
const groupTotal = modules.reduce((sum, m) => sum + m.weight, 0);
const groupPct = Math.round(groupTotal * 100);
let modulesHtml = '';
for (const mod of modules) {
const pct = Math.round(mod.weight * 100);
const label = labelMap[mod.id] || `Module ${mod.id}`;
modulesHtml += `
<div class="flex items-center gap-4 py-2">
<span class="w-40 text-sm text-slate-600">↳ ${label}</span>
<input type="range" class="flex-1 weight-slider" 
data-key="${mod.id}" min="0" max="100" value="${pct}"
oninput="updateWeight(this)">
<div class="w-20 flex items-center gap-1">
<input type="number" class="w-14 px-2 py-1 text-sm border border-slate-300 rounded text-center weight-input"
data-key="${mod.id}" min="0" max="100" value="${pct}"
onchange="updateWeightFromInput(this)">
<span class="text-sm text-slate-500">%</span>
</div>
</div>`;
}
container.innerHTML += `
<div class="border border-slate-200 rounded-lg overflow-hidden mb-3">
<div class="flex items-center gap-4 px-4 py-3 bg-slate-50 cursor-pointer" 
onclick="toggleDashboardGroup('${groupId}')">
<i id="${groupId}-icon" class="fa-solid fa-chevron-right text-xs text-slate-400 transition-transform"></i>
<span class="flex-1 text-sm font-medium text-slate-700">${parentNames[parent] || 'Module ' + parent}</span>
<span class="text-sm font-bold text-slate-600">${groupPct}%</span>
</div>
<div id="${groupId}-subs" class="hidden pl-6 pr-4 pb-3 border-l-2 border-slate-200 ml-4 space-y-1">
${modulesHtml}
</div>
</div>`;
}
container.innerHTML += `<div class="text-sm text-slate-500 mt-4">Total: <span id="sliders-dashboard-total" class="font-medium">100%</span></div>`;
}
function toggleDashboardGroup(groupId) {
const subs = document.getElementById(`${groupId}-subs`);
const icon = document.getElementById(`${groupId}-icon`);
if (subs) subs.classList.toggle('hidden');
if (icon) icon.classList.toggle('rotate-90');
}
// Generate dynamic tabs from Report Registry
function generateDynamicTabs() {
if (!CRMT?.reportRegistry?.getAll) {
console.warn('[Admin] Report Registry not available');
return;
}
const reports = CRMT.reportRegistry.getAll();
const tabNav = document.getElementById('tab-navigation');
const tabContainer = document.getElementById('dynamic-report-tabs');
if (!tabNav || !tabContainer) {
console.warn('[Admin] Tab containers not found');
return;
}
// Group reports by module
const modules = {
'M.1': { name: 'Marketer\'s Must-Haves', icon: 'envelope', reports: [] },
'M.2': { name: 'Content & Creator', icon: 'file-alt', reports: [] },
'M.3': { name: 'Compliance & Social', icon: 'shield-halved', reports: [] },
'M.4': { name: 'License & Entity', icon: 'building', reports: [] },
'M.5': { name: 'M&A & Valuation', icon: 'chart-line', reports: [] },
'M.6': { name: 'Brand Intelligence', icon: 'brain', reports: [] },
'M.7': { name: 'Brand Profiles', icon: 'id-card', reports: [] }
};
reports.forEach(r => {
if (modules[r.module]) {
modules[r.module].reports.push(r);
}
});
// Generate tab buttons (grouped by module)
let tabButtons = '';
Object.entries(modules).forEach(([modId, mod]) => {
if (mod.reports.length > 0) {
tabButtons += `
<div class="relative group">
<button class="tab-btn px-3 py-3 text-xs border-b-2 border-transparent hover:bg-slate-50 transition-colors"
onclick="toggleModuleDropdown('${modId}')" data-module="${modId}">
<i class="fa-solid fa-${mod.icon} mr-1"></i>${modId.replace('M.', '')}
</button>
<div id="dropdown-${modId}" class="hidden absolute z-50 left-0 mt-1 bg-white shadow-lg rounded-lg border border-slate-200 min-w-[200px]">
<div class="py-2">
${mod.reports.map(r => `
<button class="block w-full text-left px-4 py-2 text-sm hover:bg-slate-50 ${r.available ? 'text-slate-700' : 'text-slate-400'}"
onclick="selectReportTab('${r.id}')" data-tab="${r.id}">
<span class="${r.color ? 'text-' + r.color + '-600' : ''}">${r.id}</span>
<span class="ml-2">${r.name}</span>
${!r.available ? '<i class="fa-solid fa-clock text-amber-400 ml-2" title="Pending"></i>' : ''}
</button>
`).join('')}
</div>
</div>
</div>
`;
}
});
// Add C3 Lifecycle tab button
tabButtons += `
<button class="tab-btn px-4 py-3 text-sm border-b-2 border-transparent hover:bg-slate-50 transition-colors" data-tab="C3">
<i class="fa-solid fa-leaf mr-2 text-green-500"></i>C3
</button>
`;
// Insert tab buttons after Dashboard button
const existingTabButtons = tabNav.querySelectorAll('.tab-btn, .relative');
existingTabButtons.forEach((btn, i) => {
if (i > 0) btn.remove(); // Keep only Dashboard button
});
tabNav.insertAdjacentHTML('beforeend', tabButtons);
// Generate tab content for all reports
let tabContent = reports.map(r => {
const sectionLabels = r.sections ? Object.values(r.sections).map(s => s.label).join(', ') : '';
return `
<div id="tab-${r.id}" class="tab-content hidden">
<div class="flex items-center gap-3 mb-2">
<span class="text-xs font-medium text-${r.color}-600 bg-${r.color}-100 px-2 py-0.5 rounded">${r.id}</span>
<h3 class="font-bold text-lg text-slate-800">${r.name}</h3>
${!r.available ? '<span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded">Pending Data</span>' : ''}
</div>
<p class="text-sm text-slate-500 mb-6">Configure section weights: ${sectionLabels || 'No sections defined'}</p>
<div id="sliders-${r.id}" class="space-y-4"></div>
</div>
`;
}).join('');
tabContainer.innerHTML = tabContent;
console.log('[Admin] Generated', reports.length, 'report tabs from registry');
}
// Toggle module dropdown
function toggleModuleDropdown(modId) {
const dropdown = document.getElementById(`dropdown-${modId}`);
const allDropdowns = document.querySelectorAll('[id^="dropdown-M."]');
allDropdowns.forEach(d => {
if (d.id !== `dropdown-${modId}`) d.classList.add('hidden');
});
dropdown?.classList.toggle('hidden');
}
// Select report tab
function selectReportTab(reportId) {
// Hide all dropdowns
document.querySelectorAll('[id^="dropdown-M."]').forEach(d => d.classList.add('hidden'));
// Switch to the tab
const tabBtn = document.querySelector(`[data-tab="${reportId}"]`) || document.createElement('button');
tabBtn.dataset.tab = reportId;
switchTab({ target: tabBtn });
}
// Close dropdowns when clicking outside
document.addEventListener('click', (e) => {
if (!e.target.closest('[data-module]') && !e.target.closest('[id^="dropdown-M."]')) {
document.querySelectorAll('[id^="dropdown-M."]').forEach(d => d.classList.add('hidden'));
}
});
// Render all tabs
function renderAllTabs() {
console.log('[Admin] renderAllTabs called, weightsConfig:', weightsConfig);
// Generate dynamic tabs from registry first
generateDynamicTabs();
// Dashboard - grouped by parent module
if (weightsConfig?.dashboard?.module_weights) {
renderDashboardGrouped('sliders-dashboard', weightsConfig.dashboard.module_weights);
}
// Module tabs - now includes all 29 reports from registry
if (CRMT?.reportRegistry?.getAll) {
const reports = CRMT.reportRegistry.getAll();
for (const report of reports) {
// Use registry sections to create default weights if not in weightsConfig
let sections = weightsConfig?.modules?.[report.id]?.sections;
if (!sections && report.sections) {
// Build weights from registry defaults
sections = {};
Object.entries(report.sections).forEach(([key, sec]) => {
sections[key] = { weight: sec.defaultWeight || 0.25 };
});
}
if (sections) {
renderSections(`sliders-${report.id}`, sections, report.id);
}
}
}
// Legacy support: also render any weightsConfig modules
if (weightsConfig?.modules) {
for (const [moduleId, moduleData] of Object.entries(weightsConfig.modules)) {
if (moduleData?.sections) {
renderSections(`sliders-${moduleId}`, moduleData.sections, moduleId);
} else if (moduleData?.weights) {
renderSliders(`sliders-${moduleId}`, moduleData.weights);
}
}
}
updateTotal();
}
// Update weight from slider
function updateWeight(slider) {
const key = slider.dataset.key;
const value = parseInt(slider.value);
// Update corresponding input
const input = slider.parentElement.querySelector('.weight-input');
if (input) input.value = value;
// Update config
updateConfigValue(key, value / 100);
updateTotal();
}
// Update weight from input
function updateWeightFromInput(input) {
const key = input.dataset.key;
let value = parseInt(input.value) || 0;
value = Math.max(0, Math.min(100, value));
input.value = value;
// Update corresponding slider
const slider = input.parentElement.parentElement.querySelector('.weight-slider');
if (slider) slider.value = value;
updateConfigValue(key, value / 100);
updateTotal();
}
// Update config value
function updateConfigValue(key, value) {
if (currentTab === 'dashboard') {
weightsConfig.dashboard.module_weights[key] = value;
} else {
weightsConfig.modules[currentTab].weights[key] = value;
}
}
// Update total indicator
function updateTotal() {
let total = 0;
if (currentTab === 'dashboard') {
total = Object.values(weightsConfig.dashboard.module_weights).reduce((a, b) => a + b, 0);
} else {
total = Object.values(weightsConfig.modules[currentTab].weights).reduce((a, b) => a + b, 0);
}
const pct = Math.round(total * 100);
const totalValue = document.getElementById('total-value');
const totalCheck = document.getElementById('total-check');
totalValue.textContent = pct + '%';
if (pct === 100) {
totalValue.className = 'text-lg font-bold text-green-600';
totalCheck.className = 'fa-solid fa-check-circle text-green-500';
} else {
totalValue.className = 'text-lg font-bold text-amber-600';
totalCheck.className = 'fa-solid fa-exclamation-circle text-amber-500';
}
}
// Tab switching
document.querySelectorAll('.tab-btn').forEach(btn => {
btn.addEventListener('click', () => {
document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
btn.classList.add('active');
currentTab = btn.dataset.tab;
document.getElementById(`tab-${currentTab}`).classList.remove('hidden');
updateTotal();
});
});
// Export settings
function exportSettings() {
const dataStr = JSON.stringify(weightsConfig, null, 2);
const blob = new Blob([dataStr], { type: 'application/json' });
const url = URL.createObjectURL(blob);
const a = document.createElement('a');
a.href = url;
a.download = `weights-export-${new Date().toISOString().split('T')[0]}.json`;
a.click();
URL.revokeObjectURL(url);
}
// Import settings
function importSettings(event) {
const file = event.target.files[0];
if (!file) return;
const reader = new FileReader();
reader.onload = (e) => {
try {
const imported = JSON.parse(e.target.result);
// Validate structure
if (!imported.dashboard || !imported.modules) {
alert('Invalid weights file structure');
return;
}
weightsConfig = imported;
renderAllTabs();
alert('Settings imported successfully! Click "Apply Changes" to save.');
} catch (error) {
alert('Failed to parse JSON file');
}
};
reader.readAsText(file);
}
// Reset to defaults
function resetToDefaults() {
if (confirm('Reset all weights to defaults?')) {
weightsConfig.dashboard = JSON.parse(JSON.stringify(weightsConfig.defaults.dashboard));
for (const [key, value] of Object.entries(weightsConfig.defaults.modules)) {
weightsConfig.modules[key].weights = { ...value };
}
renderAllTabs();
}
}
// Apply changes (save to file - for now just shows alert)
function applyChanges() {
weightsConfig.last_modified = new Date().toISOString();
console.log('Saving weights:', weightsConfig);
// In a real implementation, this would POST to a backend
// For now, we'll use localStorage as a fallback
localStorage.setItem('crmt_weights', JSON.stringify(weightsConfig));
alert('Changes applied! Weights saved to browser storage.\n\nNote: To persist permanently, Export the settings and replace data/weights.json.');
}
// ========== C3 LIFECYCLE CONFIG ==========
const C3_DEFAULTS = {
acq_duration: 30,     // Days after registration to stay in ACQ
ret_inactivity: 14,   // Days of inactivity to move to REA
rea_window: 90        // Max days for REA before churned
};
function loadC3Config() {
const saved = localStorage.getItem('crmt_c3_config');
let config = { ...C3_DEFAULTS };
if (saved) {
try {
config = { ...C3_DEFAULTS, ...JSON.parse(saved) };
} catch (e) { }
}
document.getElementById('c3-acq-duration').value = config.acq_duration;
document.getElementById('c3-ret-inactivity').value = config.ret_inactivity;
document.getElementById('c3-rea-window').value = config.rea_window;
}
function saveC3Config() {
const config = {
acq_duration: parseInt(document.getElementById('c3-acq-duration').value) || 30,
ret_inactivity: parseInt(document.getElementById('c3-ret-inactivity').value) || 14,
rea_window: parseInt(document.getElementById('c3-rea-window').value) || 90
};
localStorage.setItem('crmt_c3_config', JSON.stringify(config));
document.getElementById('c3-config-status').textContent = 'Saved ✓';
setTimeout(() => {
document.getElementById('c3-config-status').textContent = 'Saved to localStorage';
}, 2000);
}
function resetC3Defaults() {
document.getElementById('c3-acq-duration').value = C3_DEFAULTS.acq_duration;
document.getElementById('c3-ret-inactivity').value = C3_DEFAULTS.ret_inactivity;
document.getElementById('c3-rea-window').value = C3_DEFAULTS.rea_window;
saveC3Config();
}
// Initialize
loadWeights();
loadC3Config();
</script>
@endsection

@push('page-scripts')
<script>
        // Default weight configuration (embedded to avoid fetch issues with file:// protocol)
        const DEFAULT_WEIGHTS = {
            version: "2.0",
            dashboard: {
                module_weights: {
                    "1.3": 0.18, "2.1": 0.12, "2.2": 0.08, "2.3": 0.08, "2.4": 0.08,
                    "3.1": 0.14, "3.2": 0.05, "3.3": 0.05, "4.1": 0.10, "5.1": 0.12
                }
            },
            modules: {
                "1.3": {
                    name: "CRM Scorecard", sections: {
                        personalization: { weight: 0.30, subs: { name_usage: 0.50, dynamic_content: 0.50 } },
                        frequency: { weight: 0.20, subs: { emails_per_week: 0.50, timing_consistency: 0.50 } },
                        channel: { weight: 0.20, subs: { email_share: 0.34, sms_share: 0.33, push_share: 0.33 } },
                        journey: { weight: 0.30, subs: { welcome: 0.25, activation: 0.25, retention: 0.25, winback: 0.25 } }
                    }
                },
                "2.1": {
                    name: "Content Analysis", sections: {
                        headers: { weight: 0.20, subs: { subject_length: 0.30, preview_text: 0.30, personalized: 0.40 } },
                        body_copy: { weight: 0.40, subs: { tone: 0.25, urgency_score: 0.25, cta_count: 0.25, word_count: 0.25 } },
                        promotions: { weight: 0.30, subs: { offer_value: 0.40, wagering_req: 0.30, fairness_score: 0.30 } },
                        screenshots: { weight: 0.10, subs: {} }
                    }
                },
                "2.4": {
                    name: "Jackpots/Offers/Games", sections: {
                        jackpots: { weight: 0.30, subs: { progressive_count: 0.40, largest_jackpot: 0.40, daily_drops: 0.20 } },
                        offers: { weight: 0.40, subs: { avg_offer_value: 0.35, best_match: 0.25, free_spins: 0.20, wagering: 0.20 } },
                        games: { weight: 0.30, subs: { total_games: 0.40, new_games: 0.30, providers: 0.30 } }
                    }
                },
                "3.1": {
                    name: "Compliance", sections: {
                        completeness: { weight: 0.25, subs: { legal_disclaimer: 0.30, footer_present: 0.25, physical_address: 0.20, ownership_disclosure: 0.25 } },
                        header_footer: { weight: 0.25, subs: { marketing_standards: 0.40, age_verification: 0.30, rg_links: 0.30 } },
                        opt_in_unsubscribe: { weight: 0.25, subs: { unsubscribe_link: 0.40, clear_cta: 0.30, one_click_optout: 0.30 } },
                        traceability: { weight: 0.25, subs: { sender_transparency: 0.40, domain_consistency: 0.30, audit_trail: 0.30 } }
                    }
                },
                "4.1": {
                    name: "License", sections: {
                        license_info: { weight: 0.40, subs: { jurisdiction: 0.25, license_number: 0.25, license_owner: 0.25, issue_date: 0.25 } },
                        entity_ownership: { weight: 0.10, subs: {} },
                        rg_links: { weight: 0.25, subs: { begambleaware: 0.25, gamstop: 0.25, rg_messaging: 0.25, age_verification: 0.25 } },
                        social_media: { weight: 0.25, subs: { twitter: 0.25, instagram: 0.25, facebook: 0.25, youtube: 0.25 } }
                    }
                },
                "5.1": {
                    name: "Valuation", sections: {
                        ghost_costs: { weight: 0.25, subs: { unused_licenses: 0.50, duplicate_tools: 0.50 } },
                        journey_gaps: { weight: 0.35, subs: { lifecycle_coverage: 0.50, automation_opportunity: 0.50 } },
                        tech_consolidation: { weight: 0.15, subs: { integration_score: 0.50, migration_readiness: 0.50 } },
                        risk_exposure: { weight: 0.25, subs: { compliance_risk: 0.50, regulatory_risk: 0.50 } }
                    }
                },
                "5.2": {
                    name: "Compliance Exposure", sections: {
                        risk_exposure: { weight: 0.30, subs: { footer_compliance: 0.40, unsubscribe_present: 0.30, tc_present: 0.30 } },
                        clv_forecast: { weight: 0.25, subs: { promo_rate: 0.40, dominant_offer: 0.30, weekly_freq: 0.30 } },
                        churn_breakdown: { weight: 0.25, subs: { journey_coverage: 0.50, reactivation: 0.25, winback: 0.25 } },
                        operational_efficiency: { weight: 0.10, subs: {} },
                        brand_equity: { weight: 0.10, subs: {} }
                    }
                },
                "D.6": { name: "CRMT", weights: { transparency: 0.40, risk: 0.30, completeness: 0.30 } }
            }
        };
        DEFAULT_WEIGHTS.defaults = JSON.parse(JSON.stringify({ dashboard: DEFAULT_WEIGHTS.dashboard, modules: {} }));
        for (const [k, v] of Object.entries(DEFAULT_WEIGHTS.modules)) {
            DEFAULT_WEIGHTS.defaults.modules[k] = { ...v.weights };
        }

        let weightsConfig = null;
        let currentTab = 'dashboard';

        // Label mappings
        const labelMap = {
            'personalization': 'Personalization',
            'frequency': 'Frequency',
            'channel': 'Channel Mix',
            'journey': 'Journey Coverage',
            'footer': 'Legal Footer',
            'tc_content': 'T&C Content',
            'age_notice': 'Age Notice',
            'rg_message': 'RG Messaging',
            'unsubscribe': 'Unsubscribe Link',
            'rg_links': 'RG Links',
            'social_media': 'Social Media',
            'license_entity': 'License & Entity',
            'ghost_costs': 'Ghost Costs',
            'journey_gaps': 'Journey Gaps',
            'tech_consolidation': 'Tech Consolidation',
            'risk_exposure': 'Risk Exposure',
            'transparency': 'Transparency',
            'risk': 'Risk',
            'completeness': 'Completeness',
            '1.3': 'Module 1.3 - CRM',
            '2.1': 'Module 2.1 - Content',
            '2.2': 'Module 2.2 - Quality',
            '2.3': 'Module 2.3 - Risk',
            '3.1': 'Module 3.1 - Compliance',
            '3.2': 'Module 3.2 - Alignment',
            '3.3': 'Module 3.3 - Audit',
            '4.1': 'Module 4.1 - License',
            '5.1': 'Module 5.1 - Valuation',
            '5.2': 'Module 5.2 - Compliance Exposure',
            'clv_forecast': 'CLV Forecast',
            'churn_breakdown': 'Churn Breakdown',
            'operational_efficiency': 'Operational Efficiency',
            'brand_equity': 'Brand Equity',
            // Section labels
            'headers': 'Headers',
            'body_copy': 'Body Copy',
            'promotions': 'Promotions',
            'screenshots': 'Screenshots',
            'completeness': 'Completeness',
            'header_footer': 'Header/Footer',
            'opt_in_unsubscribe': 'Opt-in/Unsubscribe',
            'traceability': 'Traceability',
            'license_info': 'License Info',
            'entity_ownership': 'Entity Ownership',
            // Sub-weight labels
            'subject_length': 'Subject Length',
            'preview_text': 'Preview Text',
            'personalized': 'Personalized',
            'tone': 'Tone',
            'urgency_score': 'Urgency Score',
            'cta_count': 'CTA Count',
            'word_count': 'Word Count',
            'offer_value': 'Offer Value',
            'wagering_req': 'Wagering Req',
            'fairness_score': 'Fairness Score',
            'legal_disclaimer': 'Legal Disclaimer',
            'footer_present': 'Footer Present',
            'physical_address': 'Physical Address',
            'ownership_disclosure': 'Ownership Disclosure',
            'marketing_standards': 'Marketing Standards',
            'age_verification': 'Age Verification',
            'unsubscribe_link': 'Unsubscribe Link',
            'clear_cta': 'Clear CTA',
            'one_click_optout': 'One-Click Opt-out',
            'sender_transparency': 'Sender Transparency',
            'domain_consistency': 'Domain Consistency',
            'audit_trail': 'Audit Trail',
            'jurisdiction': 'Jurisdiction',
            'license_number': 'License Number',
            'license_owner': 'License Owner',
            'issue_date': 'Issue Date',
            'begambleaware': 'BeGambleAware',
            'gamstop': 'GamStop',
            'rg_messaging': 'RG Messaging',
            'twitter': 'Twitter/X',
            'instagram': 'Instagram',
            'facebook': 'Facebook',
            'youtube': 'YouTube',
            'unused_licenses': 'Unused Licenses',
            'duplicate_tools': 'Duplicate Tools',
            'lifecycle_coverage': 'Lifecycle Coverage',
            'automation_opportunity': 'Automation Opportunity',
            'integration_score': 'Integration Score',
            'migration_readiness': 'Migration Readiness',
            'compliance_risk': 'Compliance Risk',
            'regulatory_risk': 'Regulatory Risk',
            'footer_compliance': 'Footer Compliance',
            'unsubscribe_present': 'Unsubscribe Present',
            'tc_present': 'T&C Present',
            'promo_rate': 'Promo Rate',
            'dominant_offer': 'Dominant Offer',
            'weekly_freq': 'Weekly Frequency',
            'journey_coverage': 'Journey Coverage',
            'reactivation': 'Reactivation',
            'winback': 'Winback',
            // 1.3 sub-weights
            'name_usage': 'Name Usage',
            'dynamic_content': 'Dynamic Content',
            'emails_per_week': 'Emails/Week',
            'timing_consistency': 'Timing Consistency',
            'email_share': 'Email Share',
            'sms_share': 'SMS Share',
            'push_share': 'Push Share',
            'welcome': 'Welcome',
            'activation': 'Activation',
            'retention': 'Retention',
            // 2.4 sub-weights
            '2.4': 'Module 2.4 - Jackpots/Offers',
            'jackpots': 'Jackpots',
            'offers': 'Offers',
            'games': 'Games',
            'progressive_count': 'Progressive Count',
            'largest_jackpot': 'Largest Jackpot',
            'daily_drops': 'Daily Drops',
            'avg_offer_value': 'Avg Offer Value',
            'best_match': 'Best Match %',
            'free_spins': 'Free Spins',
            'wagering': 'Wagering',
            'total_games': 'Total Games',
            'new_games': 'New Games',
            'providers': 'Providers',
            'retention': 'Retention'
        };

        // Load weights - uses embedded defaults, tries fetch for any saved changes
        async function loadWeights() {
            // Start with embedded defaults
            weightsConfig = JSON.parse(JSON.stringify(DEFAULT_WEIGHTS));

            // Check localStorage for saved changes
            const saved = localStorage.getItem('crmt_weights');
            if (saved) {
                try {
                    weightsConfig = JSON.parse(saved);
                    console.log('[Admin] Loaded from localStorage');
                } catch (e) {
                    console.warn('[Admin] Invalid localStorage data, using defaults');
                }
            }

            // Try to fetch from file (works when served via HTTP)
            try {
                const response = await fetch('../data/weights.json');
                if (response.ok) {
                    const fetched = await response.json();
                    weightsConfig = fetched;
                    console.log('[Admin] Loaded from weights.json');
                }
            } catch (e) {
                console.log('[Admin] Using embedded defaults (fetch unavailable)');
            }

            renderAllTabs();
        }

        // Render sliders for a tab
        function renderSliders(containerId, weights) {
            const container = document.getElementById(containerId);
            console.log('[Admin] Rendering sliders for', containerId, 'weights:', weights);

            if (!container) {
                console.error('[Admin] Container not found:', containerId);
                return;
            }

            if (!weights || typeof weights !== 'object') {
                console.error('[Admin] Invalid weights data for', containerId);
                container.innerHTML = '<p class="text-amber-500">No weights data available</p>';
                return;
            }

            container.innerHTML = '';
            const entries = Object.entries(weights);
            console.log('[Admin] Rendering', entries.length, 'sliders');

            for (const [key, value] of entries) {
                const pct = Math.round(value * 100);
                const label = labelMap[key] || key;

                container.innerHTML += `
                    <div class="flex items-center gap-4">
                        <span class="w-40 text-sm font-medium text-slate-700">${label}</span>
                        <input type="range" 
                               class="flex-1 weight-slider" 
                               data-key="${key}" 
                               min="0" max="100" 
                               value="${pct}"
                               oninput="updateWeight(this)">
                        <div class="w-20 flex items-center gap-1">
                            <input type="number" 
                                   class="w-14 px-2 py-1 text-sm border border-slate-300 rounded text-center weight-input"
                                   data-key="${key}"
                                   min="0" max="100"
                                   value="${pct}"
                                   onchange="updateWeightFromInput(this)">
                            <span class="text-sm text-slate-500">%</span>
                        </div>
                    </div>
                `;
            }
        }

        // Render hierarchical sections with collapsible sub-weights
        function renderSections(containerId, sections, moduleId) {
            const container = document.getElementById(containerId);
            if (!container || !sections) return;

            container.innerHTML = '';

            for (const [sectionKey, sectionData] of Object.entries(sections)) {
                const sectionPct = Math.round(sectionData.weight * 100);
                const sectionLabel = labelMap[sectionKey] || sectionKey.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                const hasSubs = sectionData.subs && Object.keys(sectionData.subs).length > 0;
                const sectionId = `section-${moduleId}-${sectionKey}`;

                let subsHtml = '';
                if (hasSubs) {
                    subsHtml = `<div id="${sectionId}-subs" class="hidden pl-6 pt-3 border-l-2 border-slate-200 ml-4 space-y-3">`;
                    for (const [subKey, subValue] of Object.entries(sectionData.subs)) {
                        const subPct = Math.round(subValue * 100);
                        const subLabel = labelMap[subKey] || subKey.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        subsHtml += `
                            <div class="flex items-center gap-4">
                                <span class="w-36 text-sm text-slate-600">↳ ${subLabel}</span>
                                <input type="range" class="flex-1 sub-slider" 
                                       data-module="${moduleId}" data-section="${sectionKey}" data-sub="${subKey}"
                                       min="0" max="100" value="${subPct}"
                                       oninput="updateSubWeight(this)">
                                <div class="w-16 flex items-center gap-1">
                                    <input type="number" class="w-12 px-1 py-0.5 text-xs border border-slate-300 rounded text-center sub-input"
                                           data-module="${moduleId}" data-section="${sectionKey}" data-sub="${subKey}"
                                           min="0" max="100" value="${subPct}"
                                           onchange="updateSubWeightFromInput(this)">
                                    <span class="text-xs text-slate-500">%</span>
                                </div>
                            </div>`;
                    }
                    subsHtml += `<div class="text-xs text-slate-400 mt-2">Sub-total: <span id="${sectionId}-subtotal" class="font-medium">100%</span></div></div>`;
                }

                container.innerHTML += `
                    <div class="border border-slate-200 rounded-lg overflow-hidden mb-3">
                        <div class="flex items-center gap-4 px-4 py-3 bg-slate-50 ${hasSubs ? 'cursor-pointer' : ''}" 
                             ${hasSubs ? `onclick="toggleSection('${sectionId}')"` : ''}>
                            ${hasSubs ? `<i id="${sectionId}-icon" class="fa-solid fa-chevron-right text-xs text-slate-400 transition-transform"></i>` : '<span class="w-3"></span>'}
                            <span class="w-40 text-sm font-medium text-slate-700">${sectionLabel}</span>
                            <input type="range" class="flex-1 section-slider" 
                                   data-module="${moduleId}" data-section="${sectionKey}"
                                   min="0" max="100" value="${sectionPct}"
                                   oninput="updateSectionWeight(this)" onclick="event.stopPropagation()">
                            <div class="w-20 flex items-center gap-1">
                                <input type="number" class="w-14 px-2 py-1 text-sm border border-slate-300 rounded text-center section-input"
                                       data-module="${moduleId}" data-section="${sectionKey}"
                                       min="0" max="100" value="${sectionPct}"
                                       onchange="updateSectionWeightFromInput(this)" onclick="event.stopPropagation()">
                                <span class="text-sm text-slate-500">%</span>
                            </div>
                            ${!hasSubs ? '<span class="text-xs text-slate-400 italic">stub</span>' : ''}
                        </div>
                        ${subsHtml}
                    </div>`;
            }

            // Add section total indicator
            container.innerHTML += `<div class="text-sm text-slate-500 mt-4">Section Total: <span id="${containerId}-total" class="font-medium">100%</span></div>`;
            updateSectionTotals(moduleId);
        }

        function toggleSection(sectionId) {
            const subs = document.getElementById(`${sectionId}-subs`);
            const icon = document.getElementById(`${sectionId}-icon`);
            if (subs) subs.classList.toggle('hidden');
            if (icon) icon.classList.toggle('rotate-90');
        }

        function updateSectionWeight(slider) {
            const moduleId = slider.dataset.module;
            const sectionKey = slider.dataset.section;
            const value = parseInt(slider.value);
            const input = slider.parentElement.parentElement.querySelector('.section-input');
            if (input) input.value = value;
            if (weightsConfig?.modules?.[moduleId]?.sections?.[sectionKey]) {
                weightsConfig.modules[moduleId].sections[sectionKey].weight = value / 100;
            }
            updateSectionTotals(moduleId);
        }

        function updateSectionWeightFromInput(input) {
            const moduleId = input.dataset.module;
            const sectionKey = input.dataset.section;
            let value = Math.max(0, Math.min(100, parseInt(input.value) || 0));
            input.value = value;
            const slider = input.parentElement.parentElement.querySelector('.section-slider');
            if (slider) slider.value = value;
            if (weightsConfig?.modules?.[moduleId]?.sections?.[sectionKey]) {
                weightsConfig.modules[moduleId].sections[sectionKey].weight = value / 100;
            }
            updateSectionTotals(moduleId);
        }

        function updateSubWeight(slider) {
            const { module: moduleId, section: sectionKey, sub: subKey } = slider.dataset;
            const value = parseInt(slider.value);
            const input = slider.parentElement.querySelector('.sub-input');
            if (input) input.value = value;
            if (weightsConfig?.modules?.[moduleId]?.sections?.[sectionKey]?.subs?.[subKey] !== undefined) {
                weightsConfig.modules[moduleId].sections[sectionKey].subs[subKey] = value / 100;
            }
            updateSubTotals(moduleId, sectionKey);
        }

        function updateSubWeightFromInput(input) {
            const { module: moduleId, section: sectionKey, sub: subKey } = input.dataset;
            let value = Math.max(0, Math.min(100, parseInt(input.value) || 0));
            input.value = value;
            const slider = input.parentElement.parentElement.querySelector('.sub-slider');
            if (slider) slider.value = value;
            if (weightsConfig?.modules?.[moduleId]?.sections?.[sectionKey]?.subs?.[subKey] !== undefined) {
                weightsConfig.modules[moduleId].sections[sectionKey].subs[subKey] = value / 100;
            }
            updateSubTotals(moduleId, sectionKey);
        }

        function updateSectionTotals(moduleId) {
            const sections = weightsConfig?.modules?.[moduleId]?.sections;
            if (!sections) return;
            const total = Object.values(sections).reduce((sum, s) => sum + (s.weight || 0), 0);
            const totalPct = Math.round(total * 100);
            const el = document.getElementById(`sliders-${moduleId}-total`);
            if (el) {
                el.textContent = `${totalPct}%`;
                el.className = totalPct === 100 ? 'font-medium text-green-600' : 'font-medium text-red-500';
            }
        }

        function updateSubTotals(moduleId, sectionKey) {
            const subs = weightsConfig?.modules?.[moduleId]?.sections?.[sectionKey]?.subs;
            if (!subs) return;
            const total = Object.values(subs).reduce((sum, v) => sum + v, 0);
            const totalPct = Math.round(total * 100);
            const el = document.getElementById(`section-${moduleId}-${sectionKey}-subtotal`);
            if (el) {
                el.textContent = `${totalPct}%`;
                el.className = totalPct === 100 ? 'font-medium text-green-600' : 'font-medium text-red-500';
            }
        }

        // Render dashboard module weights grouped by parent module
        function renderDashboardGrouped(containerId, moduleWeights) {
            const container = document.getElementById(containerId);
            if (!container || !moduleWeights) return;

            container.innerHTML = '';

            // Group modules by parent (1.x → Module 1, 2.x → Module 2, etc.)
            const groups = {};
            for (const [modId, weight] of Object.entries(moduleWeights)) {
                const parent = modId.split('.')[0];
                if (!groups[parent]) groups[parent] = [];
                groups[parent].push({ id: modId, weight });
            }

            const parentNames = {
                '1': 'Module 1 - CRM',
                '2': 'Module 2 - Content',
                '3': 'Module 3 - Compliance',
                '4': 'Module 4 - License',
                '5': 'Module 5 - Valuation',
                'D': 'Module D - CRMT'
            };

            for (const [parent, modules] of Object.entries(groups)) {
                const groupId = `dashboard-group-${parent}`;
                const groupTotal = modules.reduce((sum, m) => sum + m.weight, 0);
                const groupPct = Math.round(groupTotal * 100);

                let modulesHtml = '';
                for (const mod of modules) {
                    const pct = Math.round(mod.weight * 100);
                    const label = labelMap[mod.id] || `Module ${mod.id}`;
                    modulesHtml += `
                        <div class="flex items-center gap-4 py-2">
                            <span class="w-40 text-sm text-slate-600">↳ ${label}</span>
                            <input type="range" class="flex-1 weight-slider" 
                                   data-key="${mod.id}" min="0" max="100" value="${pct}"
                                   oninput="updateWeight(this)">
                            <div class="w-20 flex items-center gap-1">
                                <input type="number" class="w-14 px-2 py-1 text-sm border border-slate-300 rounded text-center weight-input"
                                       data-key="${mod.id}" min="0" max="100" value="${pct}"
                                       onchange="updateWeightFromInput(this)">
                                <span class="text-sm text-slate-500">%</span>
                            </div>
                        </div>`;
                }

                container.innerHTML += `
                    <div class="border border-slate-200 rounded-lg overflow-hidden mb-3">
                        <div class="flex items-center gap-4 px-4 py-3 bg-slate-50 cursor-pointer" 
                             onclick="toggleDashboardGroup('${groupId}')">
                            <i id="${groupId}-icon" class="fa-solid fa-chevron-right text-xs text-slate-400 transition-transform"></i>
                            <span class="flex-1 text-sm font-medium text-slate-700">${parentNames[parent] || 'Module ' + parent}</span>
                            <span class="text-sm font-bold text-slate-600">${groupPct}%</span>
                        </div>
                        <div id="${groupId}-subs" class="hidden pl-6 pr-4 pb-3 border-l-2 border-slate-200 ml-4 space-y-1">
                            ${modulesHtml}
                        </div>
                    </div>`;
            }

            container.innerHTML += `<div class="text-sm text-slate-500 mt-4">Total: <span id="sliders-dashboard-total" class="font-medium">100%</span></div>`;
        }

        function toggleDashboardGroup(groupId) {
            const subs = document.getElementById(`${groupId}-subs`);
            const icon = document.getElementById(`${groupId}-icon`);
            if (subs) subs.classList.toggle('hidden');
            if (icon) icon.classList.toggle('rotate-90');
        }

        // Generate dynamic tabs from Report Registry
        function generateDynamicTabs() {
            if (!CRMT?.reportRegistry?.getAll) {
                console.warn('[Admin] Report Registry not available');
                return;
            }

            const reports = CRMT.reportRegistry.getAll();
            const tabNav = document.getElementById('tab-navigation');
            const tabContainer = document.getElementById('dynamic-report-tabs');

            if (!tabNav || !tabContainer) {
                console.warn('[Admin] Tab containers not found');
                return;
            }

            // Group reports by module
            const modules = {
                'M.1': { name: 'Marketer\'s Must-Haves', icon: 'envelope', reports: [] },
                'M.2': { name: 'Content & Creator', icon: 'file-alt', reports: [] },
                'M.3': { name: 'Compliance & Social', icon: 'shield-halved', reports: [] },
                'M.4': { name: 'License & Entity', icon: 'building', reports: [] },
                'M.5': { name: 'M&A & Valuation', icon: 'chart-line', reports: [] },
                'M.6': { name: 'Brand Intelligence', icon: 'brain', reports: [] },
                'M.7': { name: 'Brand Profiles', icon: 'id-card', reports: [] }
            };

            reports.forEach(r => {
                if (modules[r.module]) {
                    modules[r.module].reports.push(r);
                }
            });

            // Generate tab buttons (grouped by module)
            let tabButtons = '';
            Object.entries(modules).forEach(([modId, mod]) => {
                if (mod.reports.length > 0) {
                    tabButtons += `
                        <div class="relative group">
                            <button class="tab-btn px-3 py-3 text-xs border-b-2 border-transparent hover:bg-slate-50 transition-colors"
                                    onclick="toggleModuleDropdown('${modId}')" data-module="${modId}">
                                <i class="fa-solid fa-${mod.icon} mr-1"></i>${modId.replace('M.', '')}
                            </button>
                            <div id="dropdown-${modId}" class="hidden absolute z-50 left-0 mt-1 bg-white shadow-lg rounded-lg border border-slate-200 min-w-[200px]">
                                <div class="py-2">
                                    ${mod.reports.map(r => `
                                        <button class="block w-full text-left px-4 py-2 text-sm hover:bg-slate-50 ${r.available ? 'text-slate-700' : 'text-slate-400'}"
                                                onclick="selectReportTab('${r.id}')" data-tab="${r.id}">
                                            <span class="${r.color ? 'text-' + r.color + '-600' : ''}">${r.id}</span>
                                            <span class="ml-2">${r.name}</span>
                                            ${!r.available ? '<i class="fa-solid fa-clock text-amber-400 ml-2" title="Pending"></i>' : ''}
                                        </button>
                                    `).join('')}
                                </div>
                            </div>
                        </div>
                    `;
                }
            });

            // Add C3 Lifecycle tab button
            tabButtons += `
                <button class="tab-btn px-4 py-3 text-sm border-b-2 border-transparent hover:bg-slate-50 transition-colors" data-tab="C3">
                    <i class="fa-solid fa-leaf mr-2 text-green-500"></i>C3
                </button>
            `;

            // Insert tab buttons after Dashboard button
            const existingTabButtons = tabNav.querySelectorAll('.tab-btn, .relative');
            existingTabButtons.forEach((btn, i) => {
                if (i > 0) btn.remove(); // Keep only Dashboard button
            });
            tabNav.insertAdjacentHTML('beforeend', tabButtons);

            // Generate tab content for all reports
            let tabContent = reports.map(r => {
                const sectionLabels = r.sections ? Object.values(r.sections).map(s => s.label).join(', ') : '';
                return `
                    <div id="tab-${r.id}" class="tab-content hidden">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-xs font-medium text-${r.color}-600 bg-${r.color}-100 px-2 py-0.5 rounded">${r.id}</span>
                            <h3 class="font-bold text-lg text-slate-800">${r.name}</h3>
                            ${!r.available ? '<span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded">Pending Data</span>' : ''}
                        </div>
                        <p class="text-sm text-slate-500 mb-6">Configure section weights: ${sectionLabels || 'No sections defined'}</p>
                        <div id="sliders-${r.id}" class="space-y-4"></div>
                    </div>
                `;
            }).join('');

            tabContainer.innerHTML = tabContent;
            console.log('[Admin] Generated', reports.length, 'report tabs from registry');
        }

        // Toggle module dropdown
        function toggleModuleDropdown(modId) {
            const dropdown = document.getElementById(`dropdown-${modId}`);
            const allDropdowns = document.querySelectorAll('[id^="dropdown-M."]');
            allDropdowns.forEach(d => {
                if (d.id !== `dropdown-${modId}`) d.classList.add('hidden');
            });
            dropdown?.classList.toggle('hidden');
        }

        // Select report tab
        function selectReportTab(reportId) {
            // Hide all dropdowns
            document.querySelectorAll('[id^="dropdown-M."]').forEach(d => d.classList.add('hidden'));

            // Switch to the tab
            const tabBtn = document.querySelector(`[data-tab="${reportId}"]`) || document.createElement('button');
            tabBtn.dataset.tab = reportId;
            switchTab({ target: tabBtn });
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('[data-module]') && !e.target.closest('[id^="dropdown-M."]')) {
                document.querySelectorAll('[id^="dropdown-M."]').forEach(d => d.classList.add('hidden'));
            }
        });

        // Render all tabs
        function renderAllTabs() {
            console.log('[Admin] renderAllTabs called, weightsConfig:', weightsConfig);

            // Generate dynamic tabs from registry first
            generateDynamicTabs();

            // Dashboard - grouped by parent module
            if (weightsConfig?.dashboard?.module_weights) {
                renderDashboardGrouped('sliders-dashboard', weightsConfig.dashboard.module_weights);
            }

            // Module tabs - now includes all 29 reports from registry
            if (CRMT?.reportRegistry?.getAll) {
                const reports = CRMT.reportRegistry.getAll();
                for (const report of reports) {
                    // Use registry sections to create default weights if not in weightsConfig
                    let sections = weightsConfig?.modules?.[report.id]?.sections;

                    if (!sections && report.sections) {
                        // Build weights from registry defaults
                        sections = {};
                        Object.entries(report.sections).forEach(([key, sec]) => {
                            sections[key] = { weight: sec.defaultWeight || 0.25 };
                        });
                    }

                    if (sections) {
                        renderSections(`sliders-${report.id}`, sections, report.id);
                    }
                }
            }

            // Legacy support: also render any weightsConfig modules
            if (weightsConfig?.modules) {
                for (const [moduleId, moduleData] of Object.entries(weightsConfig.modules)) {
                    if (moduleData?.sections) {
                        renderSections(`sliders-${moduleId}`, moduleData.sections, moduleId);
                    } else if (moduleData?.weights) {
                        renderSliders(`sliders-${moduleId}`, moduleData.weights);
                    }
                }
            }

            updateTotal();
        }

        // Update weight from slider
        function updateWeight(slider) {
            const key = slider.dataset.key;
            const value = parseInt(slider.value);

            // Update corresponding input
            const input = slider.parentElement.querySelector('.weight-input');
            if (input) input.value = value;

            // Update config
            updateConfigValue(key, value / 100);
            updateTotal();
        }

        // Update weight from input
        function updateWeightFromInput(input) {
            const key = input.dataset.key;
            let value = parseInt(input.value) || 0;
            value = Math.max(0, Math.min(100, value));
            input.value = value;

            // Update corresponding slider
            const slider = input.parentElement.parentElement.querySelector('.weight-slider');
            if (slider) slider.value = value;

            updateConfigValue(key, value / 100);
            updateTotal();
        }

        // Update config value
        function updateConfigValue(key, value) {
            if (currentTab === 'dashboard') {
                weightsConfig.dashboard.module_weights[key] = value;
            } else {
                weightsConfig.modules[currentTab].weights[key] = value;
            }
        }

        // Update total indicator
        function updateTotal() {
            let total = 0;
            if (currentTab === 'dashboard') {
                total = Object.values(weightsConfig.dashboard.module_weights).reduce((a, b) => a + b, 0);
            } else {
                total = Object.values(weightsConfig.modules[currentTab].weights).reduce((a, b) => a + b, 0);
            }

            const pct = Math.round(total * 100);
            const totalValue = document.getElementById('total-value');
            const totalCheck = document.getElementById('total-check');

            totalValue.textContent = pct + '%';

            if (pct === 100) {
                totalValue.className = 'text-lg font-bold text-green-600';
                totalCheck.className = 'fa-solid fa-check-circle text-green-500';
            } else {
                totalValue.className = 'text-lg font-bold text-amber-600';
                totalCheck.className = 'fa-solid fa-exclamation-circle text-amber-500';
            }
        }

        // Tab switching
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));

                btn.classList.add('active');
                currentTab = btn.dataset.tab;
                document.getElementById(`tab-${currentTab}`).classList.remove('hidden');

                updateTotal();
            });
        });

        // Export settings
        function exportSettings() {
            const dataStr = JSON.stringify(weightsConfig, null, 2);
            const blob = new Blob([dataStr], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `weights-export-${new Date().toISOString().split('T')[0]}.json`;
            a.click();
            URL.revokeObjectURL(url);
        }

        // Import settings
        function importSettings(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                try {
                    const imported = JSON.parse(e.target.result);

                    // Validate structure
                    if (!imported.dashboard || !imported.modules) {
                        alert('Invalid weights file structure');
                        return;
                    }

                    weightsConfig = imported;
                    renderAllTabs();
                    alert('Settings imported successfully! Click "Apply Changes" to save.');
                } catch (error) {
                    alert('Failed to parse JSON file');
                }
            };
            reader.readAsText(file);
        }

        // Reset to defaults
        function resetToDefaults() {
            if (confirm('Reset all weights to defaults?')) {
                weightsConfig.dashboard = JSON.parse(JSON.stringify(weightsConfig.defaults.dashboard));
                for (const [key, value] of Object.entries(weightsConfig.defaults.modules)) {
                    weightsConfig.modules[key].weights = { ...value };
                }
                renderAllTabs();
            }
        }

        // Apply changes (save to file - for now just shows alert)
        function applyChanges() {
            weightsConfig.last_modified = new Date().toISOString();
            console.log('Saving weights:', weightsConfig);

            // In a real implementation, this would POST to a backend
            // For now, we'll use localStorage as a fallback
            localStorage.setItem('crmt_weights', JSON.stringify(weightsConfig));

            alert('Changes applied! Weights saved to browser storage.\n\nNote: To persist permanently, Export the settings and replace data/weights.json.');
        }

        // ========== C3 LIFECYCLE CONFIG ==========
        const C3_DEFAULTS = {
            acq_duration: 30,     // Days after registration to stay in ACQ
            ret_inactivity: 14,   // Days of inactivity to move to REA
            rea_window: 90        // Max days for REA before churned
        };

        function loadC3Config() {
            const saved = localStorage.getItem('crmt_c3_config');
            let config = { ...C3_DEFAULTS };
            if (saved) {
                try {
                    config = { ...C3_DEFAULTS, ...JSON.parse(saved) };
                } catch (e) { }
            }

            document.getElementById('c3-acq-duration').value = config.acq_duration;
            document.getElementById('c3-ret-inactivity').value = config.ret_inactivity;
            document.getElementById('c3-rea-window').value = config.rea_window;
        }

        function saveC3Config() {
            const config = {
                acq_duration: parseInt(document.getElementById('c3-acq-duration').value) || 30,
                ret_inactivity: parseInt(document.getElementById('c3-ret-inactivity').value) || 14,
                rea_window: parseInt(document.getElementById('c3-rea-window').value) || 90
            };
            localStorage.setItem('crmt_c3_config', JSON.stringify(config));
            document.getElementById('c3-config-status').textContent = 'Saved ✓';
            setTimeout(() => {
                document.getElementById('c3-config-status').textContent = 'Saved to localStorage';
            }, 2000);
        }

        function resetC3Defaults() {
            document.getElementById('c3-acq-duration').value = C3_DEFAULTS.acq_duration;
            document.getElementById('c3-ret-inactivity').value = C3_DEFAULTS.ret_inactivity;
            document.getElementById('c3-rea-window').value = C3_DEFAULTS.rea_window;
            saveC3Config();
        }

        // Initialize
        loadWeights();
        loadC3Config();
    </script>
@endpush
