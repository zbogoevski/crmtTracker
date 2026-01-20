@extends('layouts.dashboard')


@section('title', 'D.1 Jurisdiction & Regulatory | CRMTracker')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
            color: #334155;
        }

        .jurisdiction-card {
            transition: all 0.2s;
        }

        .jurisdiction-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1);
        }
</style>
@endpush

@section('content')
<main class="flex-1 ml-20 p-8 max-w-[1800px] mx-auto overflow-x-hidden">
<header class="flex justify-between items-center mb-6">
<div class="flex items-center gap-3">
<span
class="text-xs font-medium bg-purple-50 text-purple-700 px-2 py-1 rounded border border-purple-200">Data
Module D.1</span>
<span id="jurisdiction-count-badge"
class="text-xs font-medium bg-green-50 text-green-700 px-2 py-1 rounded border border-green-200">
<i class="fa-solid fa-check text-xs mr-1"></i><span id="jurisdiction-count">4</span>
Jurisdictions
</span>
<span
class="text-xs font-medium bg-slate-100 text-slate-600 px-2 py-1 rounded border border-slate-200">
<i class="fa-solid fa-clock text-xs mr-1"></i>Updated <span id="last-updated-date">Dec 16,
2024</span>
</span>
<div id="date-range-container" class="flex items-center"></div>
<button
class="px-6 py-2.5 rounded-lg font-medium shadow-sm flex items-center gap-2 transition-colors bg-purple-600 hover:bg-purple-700 text-white">
<i class="fa-solid fa-plus"></i>
Add Jurisdiction
</button>
</header>
<!-- Quick Nav -->
<div class="flex gap-3 mb-6 flex-wrap">
<a href="#ca-on"
class="px-4 py-2 bg-red-100 text-red-700 rounded-lg font-medium hover:bg-red-200 transition-colors">🇨🇦
Canada</a>
<a href="#uk-gb"
class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg font-medium hover:bg-blue-200 transition-colors">🇬🇧
UK</a>
<a href="#us-multi"
class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg font-medium hover:bg-indigo-200 transition-colors">🇺🇸
USA</a>
<a href="#ar-caba"
class="px-4 py-2 bg-sky-100 text-sky-700 rounded-lg font-medium hover:bg-sky-200 transition-colors">🇦🇷
Argentina</a>
<a href="#ch-all"
class="px-4 py-2 bg-red-100 text-red-700 rounded-lg font-medium hover:bg-red-200 transition-colors">🇨🇭
Switzerland</a>
<a href="#tr-all"
class="px-4 py-2 bg-red-100 text-red-600 rounded-lg font-medium hover:bg-red-200 transition-colors">🇹🇷
Turkey</a>
</div>
<div class="grid grid-cols-1 gap-6">
<!-- ==================== CANADA - ONTARIO ==================== -->
<div id="ca-on"
class="jurisdiction-card bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
<div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4 flex items-center justify-between">
<div class="flex items-center gap-4">
<span class="text-4xl">🇨🇦</span>
<div>
<h2 class="text-xl font-bold text-white">Canada - Ontario</h2>
<p class="text-red-100 text-sm">AGCO (Alcohol and Gaming Commission of Ontario)</p>
</div>
</div>
<div class="flex items-center gap-3">
<span class="bg-white/20 text-white px-3 py-1 rounded-full text-sm font-medium">12
Rules</span>
<span
class="bg-green-400/30 text-white px-3 py-1 rounded-full text-sm font-medium">Active</span>
</div>
</div>
<div class="p-6 overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-50 border-b border-slate-200">
<tr>
<th class="text-left py-3 px-3 text-xs font-semibold text-slate-600 uppercase">
Category</th>
<th class="text-left py-3 px-3 text-xs font-semibold text-slate-600 uppercase">
Requirement</th>
<th class="text-left py-3 px-3 text-xs font-semibold text-slate-600 uppercase">
Description</th>
<th class="text-center py-3 px-3 text-xs font-semibold text-slate-600 uppercase">
Severity</th>
<th class="text-right py-3 px-3 text-xs font-semibold text-slate-600 uppercase">Fine
Range</th>
</tr>
</thead>
<tbody>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium">RG
Messaging</span></td>
<td class="py-3 px-3 font-medium">Helpline Required</td>
<td class="py-3 px-3 text-slate-600">Must include ConnexOntario helpline</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">$50K-$100K</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium">RG
Messaging</span></td>
<td class="py-3 px-3 font-medium">19+ Age Gate</td>
<td class="py-3 px-3 text-slate-600">Ontario requires 19+ (not 18+)</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">$100K-$250K</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium">RG
Messaging</span></td>
<td class="py-3 px-3 font-medium">Problem Gambling Warning</td>
<td class="py-3 px-3 text-slate-600">Standard warning message required</td>
<td class="py-3 px-3 text-center"><span
class="bg-amber-100 text-amber-700 px-2 py-1 rounded text-xs font-bold">High</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-amber-600">$25K-$50K</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-medium">Bonus
Terms</span></td>
<td class="py-3 px-3 font-medium">T&C Link Required</td>
<td class="py-3 px-3 text-slate-600">All promos must link to full terms</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">$50K-$100K</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-medium">Bonus
Terms</span></td>
<td class="py-3 px-3 font-medium">Wagering Disclosure</td>
<td class="py-3 px-3 text-slate-600">Wagering requirements stated clearly</td>
<td class="py-3 px-3 text-center"><span
class="bg-amber-100 text-amber-700 px-2 py-1 rounded text-xs font-bold">High</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-amber-600">$25K-$75K</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Advertising</span>
</td>
<td class="py-3 px-3 font-medium">No Minors</td>
<td class="py-3 px-3 text-slate-600">No imagery targeting minors</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">$100K-$500K</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs font-medium">Communications</span>
</td>
<td class="py-3 px-3 font-medium">CASL Compliance</td>
<td class="py-3 px-3 text-slate-600">Canadian Anti-Spam Law compliance</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">$1M-$10M</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs font-medium">Communications</span>
</td>
<td class="py-3 px-3 font-medium">Opt-out Required</td>
<td class="py-3 px-3 text-slate-600">Clear unsubscribe mechanism</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">$50K-$100K</td>
</tr>
</tbody>
</table>
</div>
<div
class="bg-slate-50 border-t border-slate-200 px-6 py-3 flex items-center justify-between text-sm">
<div class="text-slate-500"><i class="fa-solid fa-link mr-1"></i><a href="https://www.agco.ca"
target="_blank" class="text-purple-600 hover:underline">AGCO</a> • <a
href="https://igamingontario.ca" target="_blank"
class="text-purple-600 hover:underline">iGaming Ontario</a></div>
<span class="text-xs text-slate-400">5 competitors mapped</span>
</div>
</div>
<!-- ==================== UK - UKGC ==================== -->
<div id="uk-gb"
class="jurisdiction-card bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
<div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 flex items-center justify-between">
<div class="flex items-center gap-4">
<span class="text-4xl">🇬🇧</span>
<div>
<h2 class="text-xl font-bold text-white">United Kingdom</h2>
<p class="text-blue-100 text-sm">UKGC (UK Gambling Commission)</p>
</div>
</div>
<div class="flex items-center gap-3">
<span class="bg-white/20 text-white px-3 py-1 rounded-full text-sm font-medium">15
Rules</span>
<span
class="bg-green-400/30 text-white px-3 py-1 rounded-full text-sm font-medium">Active</span>
</div>
</div>
<div class="p-6 overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-slate-50 border-b border-slate-200">
<tr>
<th class="text-left py-3 px-3 text-xs font-semibold text-slate-600 uppercase">
Category</th>
<th class="text-left py-3 px-3 text-xs font-semibold text-slate-600 uppercase">
Requirement</th>
<th class="text-left py-3 px-3 text-xs font-semibold text-slate-600 uppercase">
Description</th>
<th class="text-center py-3 px-3 text-xs font-semibold text-slate-600 uppercase">
Severity</th>
<th class="text-right py-3 px-3 text-xs font-semibold text-slate-600 uppercase">Fine
Range</th>
</tr>
</thead>
<tbody>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium">RG
Messaging</span></td>
<td class="py-3 px-3 font-medium">BeGambleAware Link</td>
<td class="py-3 px-3 text-slate-600">Must include BeGambleAware.org</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">£100K-£500K</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium">RG
Messaging</span></td>
<td class="py-3 px-3 font-medium">18+ Age Gate</td>
<td class="py-3 px-3 text-slate-600">18+ symbol must be visible</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">£250K-£1M</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium">RG
Messaging</span></td>
<td class="py-3 px-3 font-medium">GamStop Reference</td>
<td class="py-3 px-3 text-slate-600">Reference self-exclusion service</td>
<td class="py-3 px-3 text-center"><span
class="bg-amber-100 text-amber-700 px-2 py-1 rounded text-xs font-bold">High</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-amber-600">£50K-£250K</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-medium">Bonus
Terms</span></td>
<td class="py-3 px-3 font-medium">Significant Terms Visible</td>
<td class="py-3 px-3 text-slate-600">Key T&C visible, not just linked</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">£100K-£500K</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-medium">Bonus
Terms</span></td>
<td class="py-3 px-3 font-medium">No "Free" Misuse</td>
<td class="py-3 px-3 text-slate-600">Can't use "free" if wagering applies</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">£500K-£2M</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Advertising</span>
</td>
<td class="py-3 px-3 font-medium">No Urgency</td>
<td class="py-3 px-3 text-slate-600">Cannot create urgency to gamble</td>
<td class="py-3 px-3 text-center"><span
class="bg-amber-100 text-amber-700 px-2 py-1 rounded text-xs font-bold">High</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-amber-600">£100K-£500K</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Advertising</span>
</td>
<td class="py-3 px-3 font-medium">No Athletes/Celebrities</td>
<td class="py-3 px-3 text-slate-600">Cannot use sports stars in ads</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">£1M-£5M</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs font-medium">Communications</span>
</td>
<td class="py-3 px-3 font-medium">PECR Compliance</td>
<td class="py-3 px-3 text-slate-600">Electronic marketing requires consent</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">£500K-£17.5M</td>
</tr>
</tbody>
</table>
</div>
<div
class="bg-slate-50 border-t border-slate-200 px-6 py-3 flex items-center justify-between text-sm">
<div class="text-slate-500"><i class="fa-solid fa-link mr-1"></i><a
href="https://www.gamblingcommission.gov.uk" target="_blank"
class="text-purple-600 hover:underline">UKGC</a> • <a
href="https://www.begambleaware.org" target="_blank"
class="text-purple-600 hover:underline">BeGambleAware</a></div>
<span class="text-xs text-slate-400">0 competitors mapped</span>
</div>
</div>
<!-- ==================== USA - MULTI-STATE ==================== -->
<div id="us-multi"
class="jurisdiction-card bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
<div
class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4 flex items-center justify-between">
<div class="flex items-center gap-4">
<span class="text-4xl">🇺🇸</span>
<div>
<h2 class="text-xl font-bold text-white">United States (Multi-State)</h2>
<p class="text-indigo-100 text-sm">State Gaming Commissions (NJ, PA, MI, etc.)</p>
</div>
</div>
<div class="flex items-center gap-3">
<span class="bg-white/20 text-white px-3 py-1 rounded-full text-sm font-medium">10
Rules</span>
<span
class="bg-green-400/30 text-white px-3 py-1 rounded-full text-sm font-medium">Active</span>
</div>
</div>
<div class="p-6 overflow-x-auto">
<div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-4 text-sm text-amber-800">
<i class="fa-solid fa-info-circle mr-2"></i>
<strong>Note:</strong> US regulations vary by state. Fines shown are typical ranges across
NJ, PA, MI, WV, CT.
</div>
<table class="w-full text-sm">
<thead class="bg-slate-50 border-b border-slate-200">
<tr>
<th class="text-left py-3 px-3 text-xs font-semibold text-slate-600 uppercase">
Category</th>
<th class="text-left py-3 px-3 text-xs font-semibold text-slate-600 uppercase">
Requirement</th>
<th class="text-left py-3 px-3 text-xs font-semibold text-slate-600 uppercase">
Description</th>
<th class="text-center py-3 px-3 text-xs font-semibold text-slate-600 uppercase">
Severity</th>
<th class="text-right py-3 px-3 text-xs font-semibold text-slate-600 uppercase">Fine
Range</th>
</tr>
</thead>
<tbody>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium">RG
Messaging</span></td>
<td class="py-3 px-3 font-medium">State Helpline</td>
<td class="py-3 px-3 text-slate-600">State-specific problem gambling helpline</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">$50K-$250K</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium">RG
Messaging</span></td>
<td class="py-3 px-3 font-medium">21+ Age Gate</td>
<td class="py-3 px-3 text-slate-600">21+ requirement in most states</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">$100K-$500K</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-medium">Bonus
Terms</span></td>
<td class="py-3 px-3 font-medium">State-Specific T&C</td>
<td class="py-3 px-3 text-slate-600">Terms customized per state</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">$75K-$300K</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-medium">Bonus
Terms</span></td>
<td class="py-3 px-3 font-medium">Geolocation Lock</td>
<td class="py-3 px-3 text-slate-600">Offers geo-restricted to licensed states</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">$500K-$5M</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Advertising</span>
</td>
<td class="py-3 px-3 font-medium">College Sports Ban</td>
<td class="py-3 px-3 text-slate-600">No ads on college sports (some states)</td>
<td class="py-3 px-3 text-center"><span
class="bg-amber-100 text-amber-700 px-2 py-1 rounded text-xs font-bold">High</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-amber-600">$50K-$200K</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Advertising</span>
</td>
<td class="py-3 px-3 font-medium">No False Claims</td>
<td class="py-3 px-3 text-slate-600">Claims must be accurate & verifiable</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">$100K-$1M</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs font-medium">Communications</span>
</td>
<td class="py-3 px-3 font-medium">CAN-SPAM Compliance</td>
<td class="py-3 px-3 text-slate-600">Federal CAN-SPAM Act compliance</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">$46K/email</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs font-medium">Communications</span>
</td>
<td class="py-3 px-3 font-medium">State DNC Lists</td>
<td class="py-3 px-3 text-slate-600">Honor state Do-Not-Call lists</td>
<td class="py-3 px-3 text-center"><span
class="bg-amber-100 text-amber-700 px-2 py-1 rounded text-xs font-bold">High</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-amber-600">$10K-$50K</td>
</tr>
</tbody>
</table>
</div>
<div
class="bg-slate-50 border-t border-slate-200 px-6 py-3 flex items-center justify-between text-sm">
<div class="text-slate-500"><i class="fa-solid fa-link mr-1"></i><a href="https://www.njdge.org"
target="_blank" class="text-purple-600 hover:underline">NJ DGE</a> • <a
href="https://gamingcontrolboard.pa.gov" target="_blank"
class="text-purple-600 hover:underline">PA GCB</a> • <a
href="https://www.michigan.gov/mgcb" target="_blank"
class="text-purple-600 hover:underline">MI GCB</a></div>
<span class="text-xs text-slate-400">0 competitors mapped</span>
</div>
</div>
<!-- ==================== ARGENTINA - CABA ==================== -->
<div id="ar-caba"
class="jurisdiction-card bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
<div class="bg-gradient-to-r from-sky-500 to-sky-600 px-6 py-4 flex items-center justify-between">
<div class="flex items-center gap-4">
<span class="text-4xl">🇦🇷</span>
<div>
<h2 class="text-xl font-bold text-white">Argentina - CABA</h2>
<p class="text-sky-100 text-sm">LOTBA (Lotería de la Ciudad de Buenos Aires)</p>
</div>
</div>
<div class="flex items-center gap-3">
<span class="bg-white/20 text-white px-3 py-1 rounded-full text-sm font-medium">8
Rules</span>
<span
class="bg-green-400/30 text-white px-3 py-1 rounded-full text-sm font-medium">Active</span>
</div>
</div>
<div class="p-6 overflow-x-auto">
<div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4 text-sm text-red-800">
<i class="fa-solid fa-triangle-exclamation mr-2"></i>
<strong>Warning:</strong> Operating without .bet.ar domain is a Criminal Offense under
Argentina's Penal Code - triggers ISP blocking and potential prison sentences.
</div>
<table class="w-full text-sm">
<thead class="bg-slate-50 border-b border-slate-200">
<tr>
<th class="text-left py-3 px-3 text-xs font-semibold text-slate-600 uppercase">
Category</th>
<th class="text-left py-3 px-3 text-xs font-semibold text-slate-600 uppercase">
Requirement</th>
<th class="text-left py-3 px-3 text-xs font-semibold text-slate-600 uppercase">
Description</th>
<th class="text-center py-3 px-3 text-xs font-semibold text-slate-600 uppercase">
Severity</th>
<th class="text-right py-3 px-3 text-xs font-semibold text-slate-600 uppercase">Fine
Range</th>
</tr>
</thead>
<tbody>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-medium">Criminal</span>
</td>
<td class="py-3 px-3 font-medium">.bet.ar Domain Required</td>
<td class="py-3 px-3 text-slate-600">Must operate under licensed .bet.ar domain
</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Criminal</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">ISP Block + Prison
</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium">RG
Messaging</span></td>
<td class="py-3 px-3 font-medium">LOTBA Helpline</td>
<td class="py-3 px-3 text-slate-600">Must include LOTBA-approved helpline</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">ARS 5M-25M</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium">RG
Messaging</span></td>
<td class="py-3 px-3 font-medium">18+ Age Gate</td>
<td class="py-3 px-3 text-slate-600">18+ symbol must be prominently displayed</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">ARS 2M-10M</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-medium">Bonus
Terms</span></td>
<td class="py-3 px-3 font-medium">Spanish Language</td>
<td class="py-3 px-3 text-slate-600">All terms in Spanish (no English-only)</td>
<td class="py-3 px-3 text-center"><span
class="bg-amber-100 text-amber-700 px-2 py-1 rounded text-xs font-bold">High</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-amber-600">ARS 1M-5M</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-medium">Bonus
Terms</span></td>
<td class="py-3 px-3 font-medium">Peso-Only Pricing</td>
<td class="py-3 px-3 text-slate-600">Promotions in ARS, no USD display</td>
<td class="py-3 px-3 text-center"><span
class="bg-amber-100 text-amber-700 px-2 py-1 rounded text-xs font-bold">High</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-amber-600">ARS 500K-2M</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Advertising</span>
</td>
<td class="py-3 px-3 font-medium">No Minors in Ads</td>
<td class="py-3 px-3 text-slate-600">No imagery or references to minors</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">ARS 10M-50M</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs font-medium">Communications</span>
</td>
<td class="py-3 px-3 font-medium">Express Consent</td>
<td class="py-3 px-3 text-slate-600">CASL-like express consent required</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">ARS 5M-20M</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs font-medium">Communications</span>
</td>
<td class="py-3 px-3 font-medium">Unsubscribe Mechanism</td>
<td class="py-3 px-3 text-slate-600">Easy 1-click unsubscribe required</td>
<td class="py-3 px-3 text-center"><span
class="bg-amber-100 text-amber-700 px-2 py-1 rounded text-xs font-bold">High</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-amber-600">ARS 1M-5M</td>
</tr>
</tbody>
</table>
</div>
<div
class="bg-slate-50 border-t border-slate-200 px-6 py-3 flex items-center justify-between text-sm">
<div class="text-slate-500"><i class="fa-solid fa-link mr-1"></i><a
href="https://www.loteriadelaciudad.gob.ar" target="_blank"
class="text-purple-600 hover:underline">LOTBA</a></div>
<span class="text-xs text-slate-400">0 competitors mapped</span>
</div>
</div>
<!-- ==================== SWITZERLAND ==================== -->
<div id="ch-all"
class="jurisdiction-card bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
<div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4 flex items-center justify-between">
<div class="flex items-center gap-4">
<span class="text-4xl">🇨🇭</span>
<div>
<h2 class="text-xl font-bold text-white">Switzerland</h2>
<p class="text-red-100 text-sm">ESBK/CFMJ (Federal Gaming Board)</p>
</div>
</div>
<div class="flex items-center gap-3">
<span class="bg-white/20 text-white px-3 py-1 rounded-full text-sm font-medium">6
Rules</span>
<span
class="bg-green-400/30 text-white px-3 py-1 rounded-full text-sm font-medium">Active</span>
</div>
</div>
<div class="p-6 overflow-x-auto">
<div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4 text-sm text-blue-800">
<i class="fa-solid fa-info-circle mr-2"></i>
<strong>Note:</strong> Switzerland has a licensed online gambling market since 2019. Only
land-based casino license holders can operate online.
</div>
<table class="w-full text-sm">
<thead class="bg-slate-50 border-b border-slate-200">
<tr>
<th class="text-left py-3 px-3 text-xs font-semibold text-slate-600 uppercase">
Category</th>
<th class="text-left py-3 px-3 text-xs font-semibold text-slate-600 uppercase">
Requirement</th>
<th class="text-left py-3 px-3 text-xs font-semibold text-slate-600 uppercase">
Description</th>
<th class="text-center py-3 px-3 text-xs font-semibold text-slate-600 uppercase">
Severity</th>
<th class="text-right py-3 px-3 text-xs font-semibold text-slate-600 uppercase">Fine
Range</th>
</tr>
</thead>
<tbody>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-medium">Licensing</span>
</td>
<td class="py-3 px-3 font-medium">Swiss Casino License</td>
<td class="py-3 px-3 text-slate-600">Must hold land-based casino concession to
operate online</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">ISP Block</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium">RG
Messaging</span></td>
<td class="py-3 px-3 font-medium">Swiss RG Helpline</td>
<td class="py-3 px-3 text-slate-600">Must include sos-spielsucht.ch or equivalent
</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">CHF 50K-200K</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium">RG
Messaging</span></td>
<td class="py-3 px-3 font-medium">18+ Age Gate</td>
<td class="py-3 px-3 text-slate-600">18+ symbol must be prominently displayed</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">CHF 20K-100K</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Advertising</span>
</td>
<td class="py-3 px-3 font-medium">No Minors</td>
<td class="py-3 px-3 text-slate-600">No ads targeting or featuring minors</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">CHF 100K-500K</td>
</tr>
</tbody>
</table>
</div>
<div
class="bg-slate-50 border-t border-slate-200 px-6 py-3 flex items-center justify-between text-sm">
<div class="text-slate-500"><i class="fa-solid fa-link mr-1"></i><a
href="https://www.esbk.admin.ch" target="_blank"
class="text-purple-600 hover:underline">ESBK</a> • <a
href="https://www.sos-spielsucht.ch" target="_blank"
class="text-purple-600 hover:underline">SOS Spielsucht</a></div>
<span class="text-xs text-slate-400">32 competitors tracked</span>
</div>
</div>
<!-- ==================== TURKEY ==================== -->
<div id="tr-all"
class="jurisdiction-card bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
<div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 flex items-center justify-between">
<div class="flex items-center gap-4">
<span class="text-4xl">🇹🇷</span>
<div>
<h2 class="text-xl font-bold text-white">Turkey</h2>
<p class="text-red-100 text-sm">Spor Toto / BTK (Telecommunications Authority)</p>
</div>
</div>
<div class="flex items-center gap-3">
<span class="bg-white/20 text-white px-3 py-1 rounded-full text-sm font-medium">5
Rules</span>
<span class="bg-amber-400/30 text-white px-3 py-1 rounded-full text-sm font-medium">Grey
Market</span>
</div>
</div>
<div class="p-6 overflow-x-auto">
<div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4 text-sm text-red-800">
<i class="fa-solid fa-triangle-exclamation mr-2"></i>
<strong>Warning:</strong> Online gambling is prohibited in Turkey. All operators are
unlicensed. BTK actively blocks gambling sites.
</div>
<table class="w-full text-sm">
<thead class="bg-slate-50 border-b border-slate-200">
<tr>
<th class="text-left py-3 px-3 text-xs font-semibold text-slate-600 uppercase">
Category</th>
<th class="text-left py-3 px-3 text-xs font-semibold text-slate-600 uppercase">
Requirement</th>
<th class="text-left py-3 px-3 text-xs font-semibold text-slate-600 uppercase">
Description</th>
<th class="text-center py-3 px-3 text-xs font-semibold text-slate-600 uppercase">
Severity</th>
<th class="text-right py-3 px-3 text-xs font-semibold text-slate-600 uppercase">
Penalty</th>
</tr>
</thead>
<tbody>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-medium">Criminal</span>
</td>
<td class="py-3 px-3 font-medium">No Legal License Available</td>
<td class="py-3 px-3 text-slate-600">Online gambling prohibited under Law 7258</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Criminal</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">ISP Block + Criminal
</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs font-medium">Enforcement</span>
</td>
<td class="py-3 px-3 font-medium">BTK Domain Blocking</td>
<td class="py-3 px-3 text-slate-600">Domains blocked within 24 hours of detection
</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">Immediate Block</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs font-medium">Enforcement</span>
</td>
<td class="py-3 px-3 font-medium">Payment Blocking</td>
<td class="py-3 px-3 text-slate-600">Banks required to block gambling transactions
</td>
<td class="py-3 px-3 text-center"><span
class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Critical</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-red-600">Account Freeze</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-3"><span
class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-medium">Grey
Market</span></td>
<td class="py-3 px-3 font-medium">Crypto Payments Common</td>
<td class="py-3 px-3 text-slate-600">Operators use crypto to bypass payment blocks
</td>
<td class="py-3 px-3 text-center"><span
class="bg-amber-100 text-amber-700 px-2 py-1 rounded text-xs font-bold">Note</span>
</td>
<td class="py-3 px-3 text-right font-mono text-xs text-slate-500">-</td>
</tr>
</tbody>
</table>
</div>
<div
class="bg-slate-50 border-t border-slate-200 px-6 py-3 flex items-center justify-between text-sm">
<div class="text-slate-500"><i class="fa-solid fa-link mr-1"></i><a
href="https://www.btk.gov.tr" target="_blank"
class="text-purple-600 hover:underline">BTK</a></div>
<span class="text-xs text-slate-400">41 competitors tracked</span>
</div>
</div>
</div>
<!-- Summary Stats -->
<div class="grid grid-cols-4 gap-4 mt-6">
<div class="bg-white rounded-xl border border-slate-200 p-4">
<div class="text-2xl font-bold text-slate-800">4</div>
<div class="text-sm text-slate-500">Jurisdictions</div>
</div>
<div class="bg-white rounded-xl border border-slate-200 p-4">
<div class="text-2xl font-bold text-purple-600">45</div>
<div class="text-sm text-slate-500">Total Rules</div>
</div>
<div class="bg-white rounded-xl border border-slate-200 p-4">
<div class="text-2xl font-bold text-red-600">22</div>
<div class="text-sm text-slate-500">Critical Rules</div>
</div>
<div class="bg-white rounded-xl border border-slate-200 p-4">
<div class="text-2xl font-bold text-amber-600">$47M+</div>
<div class="text-sm text-slate-500">Max Fine Exposure</div>
</div>
</div>
</main>
</div>
<script>
// Dynamically count jurisdictions and update badges
document.addEventListener('DOMContentLoaded', function () {
// Count jurisdiction cards (cards with jurisdiction-card class inside the grid)
const jurisdictionCards = document.querySelectorAll('.jurisdiction-card');
const count = jurisdictionCards.length;
// Update the count badge
const countEl = document.getElementById('jurisdiction-count');
if (countEl) {
countEl.textContent = count;
}
// Update the summary stats card as well
const summaryCountEl = document.querySelector('.grid.grid-cols-4 .text-2xl.font-bold.text-slate-800');
if (summaryCountEl) {
summaryCountEl.textContent = count;
}
});
</script>
@endsection

@push('page-scripts')
<script>
        // Dynamically count jurisdictions and update badges
        document.addEventListener('DOMContentLoaded', function () {
            // Count jurisdiction cards (cards with jurisdiction-card class inside the grid)
            const jurisdictionCards = document.querySelectorAll('.jurisdiction-card');
            const count = jurisdictionCards.length;

            // Update the count badge
            const countEl = document.getElementById('jurisdiction-count');
            if (countEl) {
                countEl.textContent = count;
            }

            // Update the summary stats card as well
            const summaryCountEl = document.querySelector('.grid.grid-cols-4 .text-2xl.font-bold.text-slate-800');
            if (summaryCountEl) {
                summaryCountEl.textContent = count;
            }
        });
    </script>
@endpush
