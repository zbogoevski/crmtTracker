@extends('layouts.dashboard')


@section('title', 'CRMTracker - Content Quality Hub')

@push('scripts')
    <script src="{{ asset('js/vendor/chart.js') }}"></script>
@endpush


@section('content')
<div class="space-y-6">
<header class="flex justify-between items-center mb-6">
<div class="flex items-center gap-3">
<span
class="text-xs font-medium bg-purple-50 text-purple-700 px-2 py-1 rounded border border-purple-200">Module
2.1</span>
<span
class="text-xs font-medium bg-indigo-50 text-indigo-700 px-2 py-1 rounded border border-indigo-200">Content</span>
</div>
<div id="date-range-container" class="flex items-center"></div>
<button
class="px-6 py-2.5 rounded-lg font-medium shadow-sm flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white">
<i class="fa-solid fa-download"></i> Export
</button>
</header>
<!-- Tab Navigation -->
<div class="bg-white rounded-t-xl border-b border-slate-200">
<div class="flex">
<button class="tab-btn active px-6 py-4 text-sm" onclick="switchTab('analysis')">
<i class="fa-solid fa-chart-bar mr-2 text-purple-500"></i>Content Analysis
</button>
<button class="tab-btn px-6 py-4 text-sm text-slate-500" onclick="switchTab('quality')">
<i class="fa-solid fa-check-double mr-2 text-green-500"></i>Quality Metrics
</button>
<button class="tab-btn px-6 py-4 text-sm text-slate-500" onclick="switchTab('subjects')">
<i class="fa-solid fa-microscope mr-2 text-blue-500"></i>Email Analyzer
</button>
</div>
</div>
<!-- Tab Content -->
<div class="bg-white rounded-b-xl shadow-sm border border-t-0 border-slate-100 p-6">
<div class="bg-white rounded-b-xl shadow-sm border border-t-0 border-slate-100 p-6 relative">
<!-- Loading Overlay -->
<div id="loading-overlay"
class="absolute inset-0 bg-white/90 flex items-center justify-center z-10 rounded-b-xl">
<div class="text-center">
<i class="fa-solid fa-spinner fa-spin text-4xl text-purple-500 mb-3"></i>
<p class="text-slate-600 font-medium">Loading content data...</p>
</div>
</div>
<!-- Analysis Tab -->
<div id="tab-analysis" class="tab-content active">
<div class="flex justify-between items-center mb-6">
<div>
<h3 class="font-bold text-lg text-slate-800">Content Volume by Competitor</h3>
<p class="text-sm text-slate-500">Compare communication counts across competitors -
<span class="font-semibold" id="analysis-total">0</span> total
</p>
</div>
</div>
<!-- Competitor Content Cards Side by Side -->
<div id="analysis-cards" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
</div>
<div class="grid grid-cols-2 gap-6 mb-6">
<div class="bg-slate-50 rounded-lg p-4">
<h4 class="font-semibold text-slate-700 mb-1">Content Type Distribution</h4>
<p class="text-xs text-slate-500 mb-3">Promotional vs Transactional vs Newsletter</p>
<div style="height: 280px;"><canvas id="chart-content-type-11"></canvas></div>
</div>
<div class="bg-slate-50 rounded-lg p-4">
<h4 class="font-semibold text-slate-700 mb-1">Promotion Categories</h4>
<p class="text-xs text-slate-500 mb-3">Bonus, Spins, Cashback breakdown</p>
<div style="height: 280px;"><canvas id="chart-promo-11"></canvas></div>
</div>
</div>
<div class="grid grid-cols-2 gap-6">
<div class="bg-slate-50 rounded-lg p-4">
<h4 class="font-semibold text-slate-700 mb-3">Content by Type (Legacy)</h4>
<div style="height: 280px;"><canvas id="chart-content-type"></canvas></div>
</div>
<div class="bg-slate-50 rounded-lg p-4">
<h4 class="font-semibold text-slate-700 mb-3">Volume by Competitor</h4>
<div style="height: 280px;"><canvas id="chart-competitor-volume"></canvas></div>
</div>
</div>
</div>
<!-- Quality Tab -->
<div id="tab-quality" class="tab-content">
<div class="flex justify-between items-center mb-6">
<div>
<h3 class="font-bold text-lg text-slate-800">Tone Distribution Analysis</h3>
<p class="text-sm text-slate-500">What communication tones does each competitor use?</p>
</div>
</div>
<!-- Competitor Tone Cards Side by Side -->
<div id="quality-cards" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6"></div>
<!-- Tone Distribution Chart -->
<div class="bg-slate-50 rounded-lg p-4 mb-6">
<h4 class="font-semibold text-slate-700 mb-3">Tone Usage by Competitor (%)</h4>
<div style="height: 280px;"><canvas id="chart-tone-distribution"></canvas></div>
</div>
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead>
<tr class="border-b-2 border-slate-200">
<th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs">
Competitor</th>
<th class="text-center py-3 px-3 font-bold text-purple-600 uppercase text-xs">
Total
</th>
<th class="text-center py-3 px-3 font-bold text-emerald-600 uppercase text-xs">
Welcoming</th>
<th class="text-center py-3 px-3 font-bold text-blue-600 uppercase text-xs">
Encouraging</th>
<th class="text-center py-3 px-3 font-bold text-red-600 uppercase text-xs">
Urgent
</th>
<th class="text-center py-3 px-3 font-bold text-amber-600 uppercase text-xs">
Informative</th>
<th class="text-center py-3 px-3 font-bold text-indigo-600 uppercase text-xs">
Dominant</th>
</tr>
</thead>
<tbody id="quality-tbody" class="divide-y divide-slate-100"></tbody>
</table>
</div>
</div>
<!-- Subject Lines Tab -->
<div id="tab-subjects" class="tab-content">
<div class="flex justify-between items-center mb-4">
<div>
<h3 class="font-bold text-lg text-slate-800">Email Analyzer</h3>
<p class="text-sm text-slate-500">Recent subject lines by competitor</p>
</div>
</div>
<!-- Brand Filter Toggle Buttons -->
<div class="flex flex-wrap gap-2 mb-4" id="subject-filter-buttons"></div>
<!-- Subject Stats by Competitor -->
<div id="subjects-cards" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
</div>
<!-- Emoji Analysis Section -->
<div class="grid grid-cols-2 gap-6 mb-6">
<div
class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-lg p-4 border border-amber-200">
<h4 class="font-semibold text-amber-700 mb-3">
<i class="fa-solid fa-face-smile mr-2"></i>Emoji Frequency by Competitor
</h4>
<div style="height: 240px;"><canvas id="chart-emoji-frequency"></canvas></div>
</div>
<div
class="bg-gradient-to-br from-pink-50 to-purple-50 rounded-lg p-4 border border-pink-200">
<h4 class="font-semibold text-pink-700 mb-3">
<i class="fa-solid fa-ranking-star mr-2"></i>Top Emojis Used
</h4>
<div id="top-emojis-grid" class="grid grid-cols-5 gap-3"></div>
<p class="text-xs text-slate-500 mt-3">Across all competitors</p>
</div>
</div>
<div class="overflow-x-auto max-h-96 overflow-y-auto">
<table class="w-full text-sm">
<thead class="sticky top-0 bg-white">
<tr class="border-b-2 border-slate-200">
<th class="text-left py-3 px-4 font-bold text-slate-600 uppercase text-xs w-32">
Competitor</th>
<th class="text-left py-3 px-4 font-bold text-purple-600 uppercase text-xs">
Subject
Line</th>
<th class="text-left py-3 px-4 font-bold text-green-600 uppercase text-xs">
Translated</th>
<th
class="text-center py-3 px-4 font-bold text-blue-600 uppercase text-xs w-20">
Tone</th>
<th
class="text-center py-3 px-4 font-bold text-amber-600 uppercase text-xs w-24">
Date</th>
</tr>
</thead>
<tbody id="subjects-tbody" class="divide-y divide-slate-100"></tbody>
</table>
</div>
</div>
</div>
</div>
</div>
<!-- Email Viewer Modal -->
<div id="email-modal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center hidden"
onclick="closeEmailModal(event)">
<div class="bg-white rounded-xl shadow-2xl w-11/12 max-w-6xl h-5/6 flex flex-col"
onclick="event.stopPropagation()">
<!-- Header -->
<div
class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-t-xl">
<div class="flex-1 min-w-0">
<h3 id="modal-subject" class="font-bold text-white text-lg truncate">Email Preview</h3>
<p id="modal-meta" class="text-purple-200 text-sm truncate">From: Competitor • Date</p>
</div>
<button onclick="closeEmailModal()" class="ml-4 text-white hover:text-purple-200 transition">
<i class="fa-solid fa-times text-2xl"></i>
</button>
</div>
<!-- Section Tabs -->
<div class="flex gap-1 px-4 pt-3 bg-slate-50 border-b border-slate-200">
<button id="modal-tab-full" onclick="switchEmailSection('full')"
class="email-section-tab px-4 py-2 text-sm font-medium rounded-t-lg bg-white border border-b-0 border-purple-300 text-purple-700">
<i class="fa-solid fa-envelope-open mr-1"></i> Full Email
</button>
<button id="modal-tab-links" onclick="switchEmailSection('links')"
class="email-section-tab px-4 py-2 text-sm font-medium rounded-t-lg bg-slate-100 text-slate-600 hover:bg-slate-200">
<i class="fa-solid fa-link mr-1"></i> Links
</button>
<button id="modal-tab-images" onclick="switchEmailSection('images')"
class="email-section-tab px-4 py-2 text-sm font-medium rounded-t-lg bg-slate-100 text-slate-600 hover:bg-slate-200">
<i class="fa-solid fa-image mr-1"></i> Images
</button>
<button id="modal-tab-ctas" onclick="switchEmailSection('ctas')"
class="email-section-tab px-4 py-2 text-sm font-medium rounded-t-lg bg-slate-100 text-slate-600 hover:bg-slate-200">
<i class="fa-solid fa-hand-pointer mr-1"></i> CTAs
</button>
</div>
<!-- Main Content Area -->
<div class="flex-1 overflow-hidden flex bg-slate-100">
<!-- Stats Sidebar -->
<div class="w-48 bg-white border-r border-slate-200 p-4 overflow-y-auto">
<h4 class="text-xs font-bold text-slate-500 uppercase mb-3">Email Stats</h4>
<div id="section-stats" class="space-y-3">
<div class="flex items-center gap-2">
<i class="fa-solid fa-font text-purple-500 w-4"></i>
<span class="text-xs text-slate-600">Words:</span>
<span id="stat-words" class="text-sm font-bold text-slate-800">0</span>
</div>
<div class="flex items-center gap-2">
<i class="fa-solid fa-link text-blue-500 w-4"></i>
<span class="text-xs text-slate-600">Links:</span>
<span id="stat-links" class="text-sm font-bold text-slate-800">0</span>
</div>
<div class="flex items-center gap-2">
<i class="fa-solid fa-image text-green-500 w-4"></i>
<span class="text-xs text-slate-600">Images:</span>
<span id="stat-images" class="text-sm font-bold text-slate-800">0</span>
</div>
<div class="flex items-center gap-2">
<i class="fa-solid fa-hand-pointer text-amber-500 w-4"></i>
<span class="text-xs text-slate-600">CTAs:</span>
<span id="stat-ctas" class="text-sm font-bold text-slate-800">0</span>
</div>
<hr class="border-slate-200">
<div id="stat-flags" class="space-y-2 text-xs">
<!-- Dynamic flags will go here -->
</div>
</div>
</div>
<!-- Email Content (Full Email View) -->
<div id="modal-content-full" class="flex-1 p-4 overflow-hidden">
<iframe id="email-iframe" class="w-full h-full bg-white rounded-lg border border-slate-200"
sandbox="allow-same-origin"></iframe>
</div>
<!-- Links Table View -->
<div id="modal-content-links" class="flex-1 p-4 overflow-auto hidden">
<div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
<table class="w-full text-sm">
<thead class="bg-slate-50 sticky top-0">
<tr>
<th class="text-left py-3 px-4 font-bold text-slate-600 text-xs uppercase">Text</th>
<th class="text-left py-3 px-4 font-bold text-slate-600 text-xs uppercase">
Description</th>
<th class="text-center py-3 px-4 font-bold text-slate-600 text-xs uppercase w-24">
Link</th>
</tr>
</thead>
<tbody id="links-tbody" class="divide-y divide-slate-100"></tbody>
</table>
<div id="links-empty" class="hidden py-12 text-center text-slate-400">
<i class="fa-solid fa-link-slash text-4xl mb-3"></i>
<p>No links found in this email</p>
</div>
</div>
</div>
<!-- CTAs Table View -->
<div id="modal-content-ctas" class="flex-1 p-4 overflow-auto hidden">
<div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
<table class="w-full text-sm">
<thead class="bg-slate-50 sticky top-0">
<tr>
<th class="text-left py-3 px-4 font-bold text-slate-600 text-xs uppercase">Text</th>
<th class="text-left py-3 px-4 font-bold text-slate-600 text-xs uppercase">
Description</th>
<th class="text-center py-3 px-4 font-bold text-slate-600 text-xs uppercase w-24">
Link</th>
</tr>
</thead>
<tbody id="ctas-tbody" class="divide-y divide-slate-100"></tbody>
</table>
<div id="ctas-empty" class="hidden py-12 text-center text-slate-400">
<i class="fa-solid fa-hand-pointer text-4xl mb-3"></i>
<p>No CTAs found in this email</p>
</div>
</div>
</div>
<!-- Images Table View -->
<div id="modal-content-images" class="flex-1 p-4 overflow-auto hidden">
<div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
<table class="w-full text-sm">
<thead class="bg-slate-50 sticky top-0">
<tr>
<th class="text-center py-3 px-4 font-bold text-slate-600 text-xs uppercase w-24">
Preview</th>
<th class="text-left py-3 px-4 font-bold text-slate-600 text-xs uppercase">Alt Text
</th>
<th class="text-left py-3 px-4 font-bold text-slate-600 text-xs uppercase">Purpose
</th>
<th class="text-center py-3 px-4 font-bold text-slate-600 text-xs uppercase w-24">
Link</th>
</tr>
</thead>
<tbody id="images-tbody" class="divide-y divide-slate-100"></tbody>
</table>
<div id="images-empty" class="hidden py-12 text-center text-slate-400">
<i class="fa-solid fa-image text-4xl mb-3"></i>
<p>No images found in this email</p>
</div>
</div>
</div>
</div>
<!-- Footer -->
<div class="px-6 py-3 border-t border-slate-200 flex justify-between items-center bg-white rounded-b-xl">
<span id="modal-extra" class="text-sm text-slate-500"></span>
<button onclick="closeEmailModal()"
class="px-4 py-2 bg-slate-200 hover:bg-slate-300 rounded-lg text-sm font-medium transition">Close</button>
</div>
</div>
</div>
<script>
// Use Laravel data if available
const laravelData = @json($data ?? []);
console.log('[2.1] Laravel data loaded:', laravelData.length, 'competitors');

let charts = {};
let allHits = [];
let competitors = [];
let selectedSubjectFilter = 'all'; // 'all' or competitor_id

// Populate competitors and hits from Laravel data
if (laravelData.length > 0) {
    competitors = laravelData.map(c => ({
        id: c.competitor_id,
        name: c.competitor_name,
        shortName: c.short_name,
    }));
    
    // Flatten all hits from all competitors
    laravelData.forEach(comp => {
        if (comp.recent_hits && comp.recent_hits.length > 0) {
            allHits = allHits.concat(comp.recent_hits.map(hit => ({
                ...hit,
                competitor_id: comp.competitor_id,
                competitor_name: comp.competitor_name,
            })));
        }
    });
    
    console.log('[2.1] Using Laravel data - Competitors:', competitors.length, 'Hits:', allHits.length);
}
// Color palette for competitors
const COLORS = [
{ bg: 'bg-amber-100', border: 'border-amber-300', text: 'text-amber-700', accent: '#F59E0B' },
{ bg: 'bg-blue-100', border: 'border-blue-300', text: 'text-blue-700', accent: '#3B82F6' },
{ bg: 'bg-purple-100', border: 'border-purple-300', text: 'text-purple-700', accent: '#8B5CF6' },
{ bg: 'bg-emerald-100', border: 'border-emerald-300', text: 'text-emerald-700', accent: '#10B981' },
{ bg: 'bg-rose-100', border: 'border-rose-300', text: 'text-rose-700', accent: '#F43F5E' },
{ bg: 'bg-cyan-100', border: 'border-cyan-300', text: 'text-cyan-700', accent: '#06B6D4' },
{ bg: 'bg-indigo-100', border: 'border-indigo-300', text: 'text-indigo-700', accent: '#6366F1' },
{ bg: 'bg-orange-100', border: 'border-orange-300', text: 'text-orange-700', accent: '#F97316' }
];
function switchTab(tabId) {
document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
event.currentTarget.classList.add('active');
document.getElementById('tab-' + tabId).classList.add('active');
window.location.hash = tabId;
}
function checkHash() {
const hash = window.location.hash.slice(1);
if (hash && ['analysis', 'quality', 'subjects'].includes(hash)) {
const btn = document.querySelector(`.tab-btn[onclick*="${hash}"]`);
if (btn) btn.click();
}
}
// Email modal state
let currentEmailHtml = '';
let currentEmailLinks = [];
let currentEmailCTAs = [];
let currentEmailImages = [];
// Switch email modal section tabs
function switchEmailSection(section) {
// Update tab buttons
document.querySelectorAll('.email-section-tab').forEach(btn => {
btn.classList.remove('bg-white', 'border', 'border-b-0', 'border-purple-300', 'text-purple-700');
btn.classList.add('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
});
const activeTab = document.getElementById('modal-tab-' + section);
if (activeTab) {
activeTab.classList.remove('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
activeTab.classList.add('bg-white', 'border', 'border-b-0', 'border-purple-300', 'text-purple-700');
}
// Show/hide content areas
document.getElementById('modal-content-full').classList.add('hidden');
document.getElementById('modal-content-links').classList.add('hidden');
document.getElementById('modal-content-images').classList.add('hidden');
document.getElementById('modal-content-ctas').classList.add('hidden');
document.getElementById('modal-content-' + section).classList.remove('hidden');
}
// Categorize image based on src, alt, and context
function categorizeImage(src, alt, width, height) {
const srcLower = (src || '').toLowerCase();
const altLower = (alt || '').toLowerCase();
// Logo detection
if (altLower.includes('logo') || srcLower.includes('logo') ||
altLower.includes('brand') || srcLower.includes('brand')) {
return { label: 'Logo', color: 'purple' };
}
// Banner/Hero
if (altLower.includes('banner') || altLower.includes('hero') ||
srcLower.includes('banner') || srcLower.includes('hero') ||
(width > 400 && height > 150)) {
return { label: 'Banner', color: 'blue' };
}
// Social icons
if (altLower.includes('facebook') || altLower.includes('twitter') || altLower.includes('instagram') ||
altLower.includes('linkedin') || altLower.includes('youtube') || altLower.includes('social') ||
srcLower.includes('facebook') || srcLower.includes('twitter') || srcLower.includes('instagram')) {
return { label: 'Social Icon', color: 'cyan' };
}
// App store badges
if (altLower.includes('app store') || altLower.includes('google play') || altLower.includes('download') ||
srcLower.includes('appstore') || srcLower.includes('googleplay')) {
return { label: 'App Badge', color: 'green' };
}
// Spacer/tracking pixel (tiny images)
if ((width && width < 10) || (height && height < 10) ||
srcLower.includes('spacer') || srcLower.includes('pixel') || srcLower.includes('track')) {
return { label: 'Tracking Pixel', color: 'slate' };
}
// Icon (small square-ish images)
if ((width && width < 50 && height && height < 50)) {
return { label: 'Icon', color: 'amber' };
}
// Product/promo image
if (altLower.includes('promo') || altLower.includes('offer') || altLower.includes('bonus') ||
altLower.includes('game') || altLower.includes('casino') || altLower.includes('sport')) {
return { label: 'Promo', color: 'purple' };
}
// Default
return { label: 'Content', color: 'slate' };
}
// Categorize links based on URL patterns and text
function categorizeLink(url, text) {
const urlLower = (url || '').toLowerCase();
const textLower = (text || '').toLowerCase();
// First check TEXT content (more reliable for redirected links)
// Unsubscribe (check first - important)
if (textLower.includes('unsubscribe') || textLower.includes('opt out') || textLower.includes('opt-out') ||
urlLower.includes('unsubscribe') || urlLower.includes('optout')) {
return { label: 'Unsubscribe', color: 'red' };
}
// CTA patterns (action words in text)
if (textLower.includes('bet') || textLower.includes('play') || textLower.includes('deposit') ||
textLower.includes('claim') || textLower.includes('join') || textLower.includes('register') ||
textLower.includes('bonus') || textLower.includes('offer') || textLower.includes('spin') ||
textLower.includes('win') || textLower.includes('get') || textLower.includes('start') ||
textLower.includes('sign up') || textLower.includes('try') || textLower.includes('casino') ||
textLower.includes('free') || textLower.includes('now')) {
return { label: 'CTA', color: 'purple' };
}
// Social Media (check text and URL)
if (textLower.includes('facebook') || textLower.includes('twitter') || textLower.includes('instagram') ||
textLower.includes('linkedin') || textLower.includes('youtube') || textLower.includes('tiktok') ||
textLower.includes('follow') || textLower.includes('share') ||
urlLower.includes('facebook.com') || urlLower.includes('twitter.com') || urlLower.includes('instagram.com') ||
urlLower.includes('linkedin.com') || urlLower.includes('youtube.com') || urlLower.includes('tiktok.com') ||
urlLower.includes('t.co')) {
return { label: 'Social', color: 'blue' };
}
// App Store
if (textLower.includes('app store') || textLower.includes('google play') || textLower.includes('download') ||
urlLower.includes('apps.apple') || urlLower.includes('play.google') || urlLower.includes('app.adjust')) {
return { label: 'App Store', color: 'green' };
}
// Terms/Legal
if (textLower.includes('terms') || textLower.includes('privacy') || textLower.includes('legal') ||
textLower.includes('policy') || textLower.includes('conditions') || textLower.includes('responsible') ||
urlLower.includes('/terms') || urlLower.includes('/privacy') || urlLower.includes('/legal')) {
return { label: 'Legal', color: 'amber' };
}
// Support
if (textLower.includes('support') || textLower.includes('help') || textLower.includes('faq') ||
textLower.includes('contact') || textLower.includes('customer service')) {
return { label: 'Support', color: 'cyan' };
}
// Logo/Image link (no text, likely an image)
if (!text || text === '[Image]' || text.trim().length === 0) {
return { label: 'Logo/Image', color: 'slate' };
}
// Tracking (ONLY if URL is from known ESP domains - be specific)
const espDomains = ['sendgrid', 'mailchimp', 'mailgun', 'mandrill', 'sparkpost', 'amazonses',
'email.mg', 'mandrillapp', 'list-manage', 'campaign-archive'];
if (espDomains.some(esp => urlLower.includes(esp))) {
return { label: 'Tracking', color: 'slate' };
}
return { label: 'Other', color: 'slate' };
}
// Detect CTAs (buttons/prominent links)
function isCTA(el, text) {
const textLower = (text || '').toLowerCase();
const ctaWords = ['bet', 'play', 'deposit', 'claim', 'join', 'register', 'sign up', 'get', 'start', 'try', 'win', 'spin', 'bonus'];
const hasCtaText = ctaWords.some(word => textLower.includes(word));
// Check if styled as button (has background, padding, etc)
const style = el.getAttribute('style') || '';
const hasButtonStyle = style.includes('background') || style.includes('padding') ||
el.closest('table[bgcolor]') || el.closest('[style*="background"]');
return hasCtaText || hasButtonStyle;
}
// Parse email HTML for links and CTAs
function parseEmailContent(html) {
currentEmailHtml = html;
currentEmailLinks = [];
currentEmailCTAs = [];
// Create temp DOM to parse
const parser = new DOMParser();
const doc = parser.parseFromString(html, 'text/html');
// Find all links
const anchors = doc.querySelectorAll('a[href]');
const seenUrls = new Set();
anchors.forEach(a => {
const href = a.getAttribute('href') || '';
const text = (a.textContent || '').trim();
const hasImage = a.querySelector('img');
const displayText = hasImage ? '[Image]' : (text || href.substring(0, 50) + '...');
if (!href || href.startsWith('#') || href.startsWith('mailto:')) return;
const category = categorizeLink(href, text);
const linkData = { url: href, text: displayText, category };
// Check if CTA
if (isCTA(a, text)) {
currentEmailCTAs.push({
text: text || '[Button]',
url: href,
type: category.label === 'CTA' ? 'Primary' : 'Secondary'
});
}
// Dedupe links
if (!seenUrls.has(href)) {
seenUrls.add(href);
currentEmailLinks.push(linkData);
}
});
// Calculate stats
const textContent = doc.body?.textContent || '';
const words = textContent.split(/\s+/).filter(w => w.length > 0).length;
const images = doc.querySelectorAll('img').length;
// Update stats sidebar
document.getElementById('stat-words').textContent = words;
document.getElementById('stat-links').textContent = currentEmailLinks.length;
document.getElementById('stat-images').textContent = images;
document.getElementById('stat-ctas').textContent = currentEmailCTAs.length;
// Populate links table
const linksTbody = document.getElementById('links-tbody');
const linksEmpty = document.getElementById('links-empty');
if (currentEmailLinks.length === 0) {
linksTbody.innerHTML = '';
linksEmpty.classList.remove('hidden');
} else {
linksEmpty.classList.add('hidden');
linksTbody.innerHTML = currentEmailLinks.map(link => `
<tr class="hover:bg-slate-50">
<td class="py-2 px-4 text-sm text-slate-800">${link.text}</td>
<td class="py-2 px-4 text-sm">
<span class="px-2 py-0.5 rounded-full text-xs bg-${link.category.color}-100 text-${link.category.color}-700">${link.category.label}</span>
</td>
<td class="py-2 px-4 text-center">
<a href="${link.url}" target="_blank" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs hover:bg-blue-200 transition">
<i class="fa-solid fa-external-link mr-1"></i>Link
</a>
</td>
</tr>
`).join('');
}
// Populate CTAs table
const ctasTbody = document.getElementById('ctas-tbody');
const ctasEmpty = document.getElementById('ctas-empty');
if (currentEmailCTAs.length === 0) {
ctasTbody.innerHTML = '';
ctasEmpty.classList.remove('hidden');
} else {
ctasEmpty.classList.add('hidden');
ctasTbody.innerHTML = currentEmailCTAs.map(cta => `
<tr class="hover:bg-slate-50">
<td class="py-2 px-4 font-medium text-slate-800">${cta.text}</td>
<td class="py-2 px-4 text-sm">
<span class="px-2 py-0.5 rounded-full text-xs ${cta.type === 'Primary' ? 'bg-purple-100 text-purple-700' : 'bg-slate-100 text-slate-600'}">${cta.type}</span>
</td>
<td class="py-2 px-4 text-center">
<a href="${cta.url}" target="_blank" class="px-3 py-1 bg-purple-100 text-purple-700 rounded-lg text-xs hover:bg-purple-200 transition">
<i class="fa-solid fa-external-link mr-1"></i>Link
</a>
</td>
</tr>
`).join('');
}
// Parse and populate images table
currentEmailImages = [];
const imgElements = doc.querySelectorAll('img');
imgElements.forEach(img => {
const src = img.getAttribute('src') || '';
const alt = img.getAttribute('alt') || '';
const width = parseInt(img.getAttribute('width') || '0');
const height = parseInt(img.getAttribute('height') || '0');
if (!src) return;
const purpose = categorizeImage(src, alt, width, height);
currentEmailImages.push({ src, alt, width, height, purpose });
});
const imagesTbody = document.getElementById('images-tbody');
const imagesEmpty = document.getElementById('images-empty');
if (currentEmailImages.length === 0) {
imagesTbody.innerHTML = '';
imagesEmpty.classList.remove('hidden');
} else {
imagesEmpty.classList.add('hidden');
imagesTbody.innerHTML = currentEmailImages.map(img => `
<tr class="hover:bg-slate-50">
<td class="py-2 px-4 text-center">
<img src="${img.src}" alt="${img.alt}" class="max-w-16 max-h-12 object-contain rounded border border-slate-200" onerror="this.style.display='none'">
</td>
<td class="py-2 px-4 text-sm text-slate-600">${img.alt || '<em class="text-slate-400">No alt text</em>'}</td>
<td class="py-2 px-4 text-sm">
<span class="px-2 py-0.5 rounded-full text-xs bg-${img.purpose.color}-100 text-${img.purpose.color}-700">${img.purpose.label}</span>
</td>
<td class="py-2 px-4 text-center">
<a href="${img.src}" target="_blank" class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs hover:bg-green-200 transition">
<i class="fa-solid fa-external-link mr-1"></i>Link
</a>
</td>
</tr>
`).join('');
}
}
async function fetchContentData() {
    // Use Laravel data if available
    if (laravelData.length > 0 && allHits.length > 0) {
        console.log('[2.1] Using Laravel hits data:', allHits.length);
        return;
    }
    
    // Fallback to old API if no Laravel data
    try {
        const competitorIds = competitors.map(c => c.id);
        if (competitorIds.length === 0) return;
        const result = await window.CRMT?.dal?.getTimelineHits(competitorIds);
        allHits = result?.data || [];
        console.log('[2.1] Loaded', allHits.length, 'hits for content analysis');
    } catch (e) {
        console.error('[2.1] Failed to fetch content data:', e);
    }
}
function renderAnalysis() {
const cardsContainer = document.getElementById('analysis-cards');
document.getElementById('analysis-total').textContent = allHits.length;
// Group by competitor
const byCompetitor = {};
allHits.forEach(h => {
const cid = h.competitor_id;
if (!byCompetitor[cid]) byCompetitor[cid] = [];
byCompetitor[cid].push(h);
});
// Count by hit_type
const typeCount = {};
allHits.forEach(h => {
const type = h.hit_type || 'Other';
typeCount[type] = (typeCount[type] || 0) + 1;
});
// Render competitor cards
cardsContainer.innerHTML = '';
competitors.forEach((c, idx) => {
const color = COLORS[idx % COLORS.length];
const compHits = byCompetitor[c.id] || [];
const count = compHits.length;
const types = {};
compHits.forEach(h => { types[h.hit_type || 'Other'] = (types[h.hit_type || 'Other'] || 0) + 1; });
const topType = Object.entries(types).sort((a, b) => b[1] - a[1])[0];
cardsContainer.innerHTML += `
<div class="competitor-card ${color.bg} ${color.border} border-2 rounded-xl p-4 text-center">
<div class="font-bold ${color.text} text-sm mb-2 truncate" title="${c.name}">${c.shortName || c.name}</div>
<div class="text-3xl font-black ${color.text}">${count}</div>
<div class="text-xs text-slate-500 mt-1">Emails</div>
<div class="mt-3 pt-2 border-t border-slate-200">
<div class="text-xs text-slate-500">Top Type</div>
<div class="font-semibold ${color.text} truncate">${topType ? topType[0] : 'N/A'}</div>
</div>
</div>
`;
});
// Type chart - grouped bar by competitor for comparison
if (charts['type']) charts['type'].destroy();
const typeCtx = document.getElementById('chart-content-type').getContext('2d');
// Fixed email types from column Z in Excel data
const EMAIL_TYPES = ['Promotional', 'Transactional', 'Informational', 'Welcome', 'System'];
const TYPE_COLORS = {
'Promotional': '#F59E0B',  // amber
'Transactional': '#3B82F6', // blue
'Informational': '#10B981', // emerald
'Welcome': '#8B5CF6',       // purple
'System': '#6B7280'         // gray
};
// Build datasets per competitor
const typeDatasets = competitors.slice(0, 5).map((c, idx) => {
const compHits = byCompetitor[c.id] || [];
const compTotal = compHits.length;
// Calculate percentage for each type
const data = EMAIL_TYPES.map(type => {
const count = compHits.filter(h => (h.hit_type || 'Promotional') === type).length;
return compTotal > 0 ? Math.round(count / compTotal * 100) : 0;
});
return {
label: `${c.shortName || c.name} (n=${compTotal})`,
data,
backgroundColor: COLORS[idx % COLORS.length].accent
};
});
charts['type'] = new Chart(typeCtx, {
type: 'bar',
data: {
labels: EMAIL_TYPES,
datasets: typeDatasets
},
options: {
responsive: true,
maintainAspectRatio: false,
indexAxis: 'y',
plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } } },
scales: {
x: { max: 100, title: { display: true, text: '% of Total' } },
y: { stacked: false }
}
}
});
// Competitor volume chart
if (charts['volume']) charts['volume'].destroy();
const volCtx = document.getElementById('chart-competitor-volume').getContext('2d');
charts['volume'] = new Chart(volCtx, {
type: 'bar',
data: {
labels: competitors.map(c => c.shortName || c.name),
datasets: [{
label: 'Emails',
data: competitors.map(c => (byCompetitor[c.id] || []).length),
backgroundColor: competitors.map((_, idx) => COLORS[idx % COLORS.length].accent)
}]
},
options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
});
// --- MOVED FROM 1.1 ---
// Content Type Distribution (1.1 style)
if (charts['content-type-11']) charts['content-type-11'].destroy();
const contentCtx11 = document.getElementById('chart-content-type-11')?.getContext('2d');
if (contentCtx11) {
const contentLabels = ['Promotional', 'Transactional', 'Newsletter', 'Other'];
const contentDatasets = competitors.slice(0, 5).map((c, idx) => {
const compHits = byCompetitor[c.id] || [];
const total = compHits.length || 1;
const counts = { Promotional: 0, Transactional: 0, Newsletter: 0, Other: 0 };
compHits.forEach(h => {
const txt = (h.hit_type || h.subject || '').toLowerCase();
if (txt.includes('promo') || txt.includes('bonus') || txt.includes('offer') || txt.includes('free') || txt.includes('spin')) counts.Promotional++;
else if (txt.includes('trans') || txt.includes('confirm') || txt.includes('receipt') || txt.includes('verify')) counts.Transactional++;
else if (txt.includes('news') || txt.includes('update') || txt.includes('digest')) counts.Newsletter++;
else counts.Other++;
});
return {
label: `${c.shortName || c.name} (n=${compHits.length})`,
data: contentLabels.map(l => Math.round(counts[l] / total * 100)),
backgroundColor: COLORS[idx % COLORS.length].accent
};
});
charts['content-type-11'] = new Chart(contentCtx11, {
type: 'bar',
data: { labels: contentLabels, datasets: contentDatasets },
options: {
responsive: true, maintainAspectRatio: false, indexAxis: 'y',
plugins: {
legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } },
tooltip: { callbacks: { label: ctx => `${ctx.dataset.label}: ${ctx.raw}%` } }
},
scales: { x: { max: 100, title: { display: true, text: '% of Total' } }, y: { stacked: false } }
}
});
}
// Promotion Categories (1.1 style)
if (charts['promo-11']) charts['promo-11'].destroy();
const promoCtx11 = document.getElementById('chart-promo-11')?.getContext('2d');
if (promoCtx11) {
const promoLabels = ['Bonus', 'Spins', 'Cashback', 'Other'];
const promoDatasets = competitors.slice(0, 5).map((c, idx) => {
const compHits = byCompetitor[c.id] || [];
const total = compHits.length || 1;
const counts = { Bonus: 0, Spins: 0, Cashback: 0, Other: 0 };
compHits.forEach(h => {
const s = (h.subject || '').toLowerCase();
if (s.includes('bonus')) counts.Bonus++;
else if (s.includes('spin')) counts.Spins++;
else if (s.includes('cash')) counts.Cashback++;
else counts.Other++;
});
return {
label: `${c.shortName || c.name} (n=${compHits.length})`,
data: promoLabels.map(l => Math.round(counts[l] / total * 100)),
backgroundColor: COLORS[idx % COLORS.length].accent
};
});
charts['promo-11'] = new Chart(promoCtx11, {
type: 'bar',
data: { labels: promoLabels, datasets: promoDatasets },
options: {
responsive: true, maintainAspectRatio: false, indexAxis: 'y',
plugins: {
legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } },
tooltip: { callbacks: { label: ctx => `${ctx.dataset.label}: ${ctx.raw}%` } }
},
scales: { x: { max: 100, title: { display: true, text: '% of Total' } }, y: { stacked: false } }
}
});
}
}
function renderQuality() {
const cardsContainer = document.getElementById('quality-cards');
const tbody = document.getElementById('quality-tbody');
const byCompetitor = {};
allHits.forEach(h => {
const cid = h.competitor_id;
if (!byCompetitor[cid]) byCompetitor[cid] = [];
byCompetitor[cid].push(h);
});
// Tone types from the data (column AD in Excel)
const TONE_TYPES = ['Welcoming', 'Encouraging', 'Urgent', 'Informative'];
const TONE_COLORS = {
'Welcoming': '#10B981',    // emerald
'Encouraging': '#3B82F6',   // blue
'Urgent': '#EF4444',        // red
'Informative': '#F59E0B'    // amber
};
// Calculate tone counts per competitor (exact match on tone value)
const tonesByCompetitor = {};
competitors.forEach(c => {
const compHits = byCompetitor[c.id] || [];
const counts = { Welcoming: 0, Encouraging: 0, Urgent: 0, Informative: 0 };
compHits.forEach(h => {
const tone = (h.tone || '').toLowerCase();
if (tone.includes('welcom')) counts.Welcoming++;
else if (tone.includes('encourag')) counts.Encouraging++;
else if (tone.includes('urgent')) counts.Urgent++;
else if (tone.includes('inform')) counts.Informative++;
});
tonesByCompetitor[c.id] = counts;
});
// Render tone cards
cardsContainer.innerHTML = '';
tbody.innerHTML = '';
competitors.forEach((c, idx) => {
const color = COLORS[idx % COLORS.length];
const compHits = byCompetitor[c.id] || [];
const tones = tonesByCompetitor[c.id];
const total = compHits.length;
// Find dominant tone
const dominantTone = Object.entries(tones).sort((a, b) => b[1] - a[1])[0];
const dominantPct = total > 0 ? Math.round(dominantTone[1] / total * 100) : 0;
cardsContainer.innerHTML += `
<div class="competitor-card ${color.bg} ${color.border} border-2 rounded-xl p-4 text-center">
<div class="font-bold ${color.text} text-sm mb-2 truncate" title="${c.name}">${c.shortName || c.name}</div>
<div class="text-2xl font-black ${color.text}">${dominantTone[0]}</div>
<div class="text-xs text-slate-500 mt-1">${dominantPct}% of ${total}</div>
<div class="grid grid-cols-2 gap-1 mt-3 pt-2 border-t border-slate-200 text-xs">
<div>
<div class="font-bold text-emerald-600">${tones.Welcoming}</div>
<div class="text-slate-400">Welcome</div>
</div>
<div>
<div class="font-bold text-red-600">${tones.Urgent}</div>
<div class="text-slate-400">Urgent</div>
</div>
</div>
</div>
`;
// Table row
const welcomePct = total > 0 ? Math.round(tones.Welcoming / total * 100) : 0;
const encouragePct = total > 0 ? Math.round(tones.Encouraging / total * 100) : 0;
const urgentPct = total > 0 ? Math.round(tones.Urgent / total * 100) : 0;
const informPct = total > 0 ? Math.round(tones.Informative / total * 100) : 0;
tbody.innerHTML += `
<tr class="hover:bg-slate-50">
<td class="py-3 px-4 font-medium ${color.text}">${c.shortName || c.name}</td>
<td class="py-3 px-3 text-center font-bold text-purple-600">${total}</td>
<td class="py-3 px-3 text-center"><span class="px-2 py-0.5 rounded text-xs ${tones.Welcoming > 0 ? 'bg-emerald-100 text-emerald-700' : 'text-slate-400'}">${tones.Welcoming} (${welcomePct}%)</span></td>
<td class="py-3 px-3 text-center"><span class="px-2 py-0.5 rounded text-xs ${tones.Encouraging > 0 ? 'bg-blue-100 text-blue-700' : 'text-slate-400'}">${tones.Encouraging} (${encouragePct}%)</span></td>
<td class="py-3 px-3 text-center"><span class="px-2 py-0.5 rounded text-xs ${tones.Urgent > 0 ? 'bg-red-100 text-red-700' : 'text-slate-400'}">${tones.Urgent} (${urgentPct}%)</span></td>
<td class="py-3 px-3 text-center"><span class="px-2 py-0.5 rounded text-xs ${tones.Informative > 0 ? 'bg-amber-100 text-amber-700' : 'text-slate-400'}">${tones.Informative} (${informPct}%)</span></td>
<td class="py-3 px-3 text-center"><span class="px-2 py-1 rounded text-xs font-bold bg-indigo-100 text-indigo-700">${dominantTone[0]}</span></td>
</tr>
`;
});
// Tone distribution chart
if (charts['tone']) charts['tone'].destroy();
const toneCtx = document.getElementById('chart-tone-distribution').getContext('2d');
const toneDatasets = TONE_TYPES.map(tone => ({
label: tone,
data: competitors.map(c => {
const total = (byCompetitor[c.id] || []).length;
const count = tonesByCompetitor[c.id][tone];
return total > 0 ? Math.round(count / total * 100) : 0;
}),
backgroundColor: TONE_COLORS[tone]
}));
charts['tone'] = new Chart(toneCtx, {
type: 'bar',
data: {
labels: competitors.map(c => c.shortName || c.name),
datasets: toneDatasets
},
options: {
responsive: true,
maintainAspectRatio: false,
plugins: { legend: { position: 'bottom' } },
scales: {
y: { max: 100, title: { display: true, text: '% of Emails' } },
x: { stacked: false }
}
}
});
}
function selectSubjectFilter(filter) {
selectedSubjectFilter = filter;
renderSubjectFilterButtons();
renderSubjectTable();
}
function renderSubjectFilterButtons() {
const container = document.getElementById('subject-filter-buttons');
const isAllActive = selectedSubjectFilter === 'all';
let html = `<button onclick="selectSubjectFilter('all')" 
class="px-3 py-1.5 rounded-full text-xs font-medium transition-all ${isAllActive ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'}">
<i class="fa-solid fa-list mr-1"></i>All
</button>`;
competitors.forEach((c, idx) => {
const color = COLORS[idx % COLORS.length];
const isActive = selectedSubjectFilter === c.id;
const activeClass = isActive ? `${color.bg} ${color.text} ${color.border} border` : 'bg-slate-100 text-slate-600 hover:bg-slate-200';
html += `<button onclick="selectSubjectFilter('${c.id}')" 
class="px-3 py-1.5 rounded-full text-xs font-medium transition-all ${activeClass}">
${c.shortName || c.name}
</button>`;
});
container.innerHTML = html;
}
function renderSubjectTable() {
const tbody = document.getElementById('subjects-tbody');
tbody.innerHTML = '';
let filteredHits = allHits.filter(h => h.subject && h.subject.length > 0);
if (selectedSubjectFilter !== 'all') {
filteredHits = filteredHits.filter(h => h.competitor_id === selectedSubjectFilter);
}
filteredHits.slice(-100).reverse().forEach((h, hitIdx) => {
const comp = competitors.find(c => c.id === h.competitor_id);
const idx = competitors.findIndex(c => c.id === h.competitor_id);
const color = COLORS[idx % COLORS.length];
const date = new Date(h.received_at);
// Always show view button - we'll check for content when fetching
const viewBtn = h.id
? `<button onclick="openEmailModal(${hitIdx})" class="ml-2 text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded hover:bg-purple-200 whitespace-nowrap"><i class="fa-solid fa-eye mr-1"></i>View</button>`
: '';
tbody.innerHTML += `
<tr class="hover:bg-slate-50">
<td class="py-2 px-4 text-xs font-medium ${color.text}">${comp?.shortName || comp?.name || 'Unknown'}</td>
<td class="py-2 px-4 text-sm">
<span class="${h.id ? 'cursor-pointer hover:text-purple-600' : ''}" ${h.id ? `onclick="openEmailModal(${hitIdx})"` : ''}>${h.subject}</span>
${viewBtn}
</td>
<td class="py-2 px-4 text-sm text-slate-600">${h.translated_content || '-'}</td>
<td class="py-2 px-4 text-center text-xs"><span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">${h.tone || 'N/A'}</span></td>
<td class="py-2 px-4 text-center text-xs text-slate-500">${date.toLocaleDateString()}</td>
</tr>
`;
});
// Store filtered hits for modal access
window.currentFilteredHits = filteredHits.slice(-100).reverse();
}
async function openEmailModal(hitIdx) {
const hit = window.currentFilteredHits?.[hitIdx];
if (!hit || !hit.id) return;
const comp = competitors.find(c => c.id === hit.competitor_id);
const date = new Date(hit.received_at);
document.getElementById('modal-subject').textContent = hit.subject || 'No Subject';
document.getElementById('modal-meta').textContent = `From: ${comp?.name || 'Unknown'} • ${date.toLocaleDateString()} ${date.toLocaleTimeString()}`;
document.getElementById('modal-extra').textContent = `Type: ${hit.hit_type || 'N/A'} • Tone: ${hit.tone || 'N/A'}`;
// Reset to Full Email tab
switchEmailSection('full');
const iframe = document.getElementById('email-iframe');
iframe.srcdoc = '<div style="text-align:center;padding:40px;"><i class="fa-solid fa-spinner fa-spin" style="font-size:24px;color:#8B5CF6;"></i><p style="margin-top:16px;color:#64748B;">Loading email...</p></div>';
// Reset stats while loading
document.getElementById('stat-words').textContent = '...';
document.getElementById('stat-links').textContent = '...';
document.getElementById('stat-images').textContent = '...';
document.getElementById('stat-ctas').textContent = '...';
document.getElementById('email-modal').classList.remove('hidden');
document.body.style.overflow = 'hidden';
// Fetch full content on demand
try {
const res = await fetch(`${window.CRMT?.dal?.baseUrl || '/.netlify/functions'}/hits/${hit.id}`);
const json = await res.json();
const fullHit = json.data;
// Handle raw_content - it may be a string or a JSON object
let htmlContent = '';
if (fullHit?.raw_content) {
if (typeof fullHit.raw_content === 'string') {
htmlContent = fullHit.raw_content;
} else if (typeof fullHit.raw_content === 'object') {
// Check for common content keys in the object
htmlContent = fullHit.raw_content.html ||
fullHit.raw_content.content ||
fullHit.raw_content.body ||
JSON.stringify(fullHit.raw_content, null, 2);
}
} else if (fullHit?.extracted_data?.content) {
htmlContent = typeof fullHit.extracted_data.content === 'string'
? fullHit.extracted_data.content
: JSON.stringify(fullHit.extracted_data.content, null, 2);
}
if (!htmlContent || htmlContent === '{}') {
htmlContent = '<p style="text-align:center;padding:40px;color:#64748B;">No HTML content available</p>';
}
iframe.srcdoc = htmlContent;
// Parse email content for links/CTAs and stats
parseEmailContent(htmlContent);
} catch (e) {
console.error('[2.1] Failed to load email content:', e);
iframe.srcdoc = '<p style="text-align:center;padding:40px;color:#EF4444;">Failed to load email content</p>';
// Reset stats on error
document.getElementById('stat-words').textContent = '0';
document.getElementById('stat-links').textContent = '0';
document.getElementById('stat-images').textContent = '0';
document.getElementById('stat-ctas').textContent = '0';
}
}
function closeEmailModal(event) {
if (event && event.target !== event.currentTarget) return;
document.getElementById('email-modal').classList.add('hidden');
document.body.style.overflow = '';
}
function renderSubjects() {
const cardsContainer = document.getElementById('subjects-cards');
const byCompetitor = {};
allHits.forEach(h => {
const cid = h.competitor_id;
if (!byCompetitor[cid]) byCompetitor[cid] = [];
byCompetitor[cid].push(h);
});
// Subject stats cards
cardsContainer.innerHTML = '';
competitors.forEach((c, idx) => {
const color = COLORS[idx % COLORS.length];
const compHits = byCompetitor[c.id] || [];
const withSubject = compHits.filter(h => h.subject && h.subject.length > 0);
const avgLen = withSubject.length > 0
? Math.round(withSubject.reduce((a, h) => a + h.subject.length, 0) / withSubject.length)
: 0;
cardsContainer.innerHTML += `
<div class="competitor-card ${color.bg} ${color.border} border-2 rounded-xl p-4 text-center cursor-pointer" onclick="selectSubjectFilter('${c.id}')">
<div class="font-bold ${color.text} text-sm mb-2 truncate" title="${c.name}">${c.shortName || c.name}</div>
<div class="text-3xl font-black ${color.text}">${withSubject.length}</div>
<div class="text-xs text-slate-500 mt-1">Subjects</div>
<div class="mt-3 pt-2 border-t border-slate-200">
<div class="text-xs text-slate-500">Avg Length</div>
<div class="font-semibold ${color.text}">${avgLen} chars</div>
</div>
</div>
`;
});
// Render filter buttons and table
renderSubjectFilterButtons();
renderSubjectTable();
renderEmojiAnalysis();
}
// Extract emojis from text using regex
function extractEmojis(text) {
if (!text) return [];
const emojiRegex = /(\p{Emoji_Presentation}|\p{Extended_Pictographic})/gu;
return (text.match(emojiRegex) || []);
}
function renderEmojiAnalysis() {
const byCompetitor = {};
allHits.forEach(h => {
const cid = h.competitor_id;
if (!byCompetitor[cid]) byCompetitor[cid] = [];
byCompetitor[cid].push(h);
});
// Count emojis per competitor and globally
const emojiByCompetitor = {};
const globalEmojiCount = {};
competitors.forEach(c => {
const compHits = byCompetitor[c.id] || [];
let emojiCount = 0;
compHits.forEach(h => {
const emojis = extractEmojis(h.subject);
emojiCount += emojis.length;
emojis.forEach(e => {
globalEmojiCount[e] = (globalEmojiCount[e] || 0) + 1;
});
});
emojiByCompetitor[c.id] = emojiCount;
});
// Emoji frequency chart (horizontal bar)
if (charts['emoji']) charts['emoji'].destroy();
const emojiCtx = document.getElementById('chart-emoji-frequency')?.getContext('2d');
if (emojiCtx) {
const sortedComps = competitors
.map((c, idx) => ({ name: c.shortName || c.name, count: emojiByCompetitor[c.id] || 0, color: COLORS[idx % COLORS.length].accent }))
.sort((a, b) => b.count - a.count);
charts['emoji'] = new Chart(emojiCtx, {
type: 'bar',
data: {
labels: sortedComps.map(c => c.name),
datasets: [{
label: 'Emojis in Subjects',
data: sortedComps.map(c => c.count),
backgroundColor: sortedComps.map(c => c.color),
borderRadius: 4
}]
},
options: {
indexAxis: 'y',
responsive: true,
maintainAspectRatio: false,
plugins: { legend: { display: false } },
scales: { x: { title: { display: true, text: 'Total Emojis' } } }
}
});
}
// Top emojis grid
const topEmojisGrid = document.getElementById('top-emojis-grid');
if (topEmojisGrid) {
const sortedEmojis = Object.entries(globalEmojiCount)
.sort((a, b) => b[1] - a[1])
.slice(0, 10);
if (sortedEmojis.length === 0) {
topEmojisGrid.innerHTML = '<p class="col-span-5 text-center text-slate-400 py-4">No emojis found in subject lines</p>';
} else {
topEmojisGrid.innerHTML = sortedEmojis.map(([emoji, count]) => `
<div class="bg-white rounded-lg p-3 text-center shadow-sm border">
<div class="text-3xl mb-1">${emoji}</div>
<div class="text-xs font-bold text-slate-600">${count}x</div>
</div>
`).join('');
}
}
}
async function initDashboard() {
    // If Laravel data is available, use it immediately
    if (laravelData.length > 0) {
        console.log('[2.1] Initializing with Laravel data...');
        await fetchContentData();
        renderAnalysis();
        renderQuality();
        renderSubjects();
        checkHash();
        document.getElementById('loading-overlay')?.classList.add('hidden');
        return;
    }
    
    // If no Laravel data, show message and hide loading overlay
    console.warn('[2.1] No Laravel data available');
    document.getElementById('loading-overlay')?.classList.add('hidden');
    // Show "No data available" message in cards container
    const cardsContainer = document.getElementById('analysis-cards');
    if (cardsContainer) {
        cardsContainer.innerHTML = '<div class="col-span-full text-center py-8 text-slate-500">No data available. Please run seeders to populate the database.</div>';
    }
    
    // Otherwise, wait for CRMT dataLoader
    if (!window.CRMT?.dataLoader || !window.getActiveCompetitorsForReport) {
        setTimeout(initDashboard, 200);
        return;
    }
    competitors = window.getActiveCompetitorsForReport?.() || [];
    console.log('[2.1] Active competitors:', competitors.length);
    await fetchContentData();
    renderAnalysis();
    renderQuality();
    renderSubjects();
    checkHash();
    // Hide loading overlay
    document.getElementById('loading-overlay')?.classList.add('hidden');
    window.addEventListener('dataLoaderChange', async () => {
        competitors = window.getActiveCompetitorsForReport?.() || [];
        await fetchContentData();
        renderAnalysis();
        renderQuality();
        renderSubjects();
    });
}

// Initialize immediately
document.addEventListener('DOMContentLoaded', function() {
    console.log('[2.1] DOMContentLoaded - Initializing...');
    initDashboard();
});
</script>
@endsection

