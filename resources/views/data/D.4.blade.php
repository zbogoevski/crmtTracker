@extends('layouts.dashboard')


@section('title', 'D.4 CRM & ESP Information | CRMTracker')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
            color: #334155;
        }

        .stub-tooltip {
            position: relative;
            cursor: help;
        }

        .stub-tooltip::after {
            content: attr(data-tooltip);
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: #1e293b;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 11px;
            white-space: pre-line;
            width: 260px;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s, visibility 0.2s;
            z-index: 100;
            margin-top: 4px;
            line-height: 1.4;
        }

        .stub-tooltip:hover::after {
            opacity: 1;
            visibility: visible;
        }
</style>
@endpush

@section('content')
<main class="flex-1 ml-20 p-8 max-w-[1800px] mx-auto overflow-x-hidden">
<header class="flex justify-between items-center mb-6">
<div class="flex items-center gap-3">
<span
class="text-xs font-medium bg-purple-50 text-purple-700 px-2 py-1 rounded border border-purple-200">Data
Module D.4</span>
<span
class="text-xs font-medium bg-green-50 text-green-700 px-2 py-1 rounded border border-green-200">
<i class="fa-solid fa-check text-xs mr-1"></i>5 Competitors
</span>
<span
class="text-xs font-medium bg-slate-100 text-slate-600 px-2 py-1 rounded border border-slate-200">
<i class="fa-solid fa-clock text-xs mr-1"></i>Updated Dec 12, 2024
</span>
<span
class="stub-tooltip text-xs font-medium bg-amber-50 text-amber-700 px-2 py-1 rounded border border-amber-200"
data-tooltip="Real data:&#10;✓ JackpotCity ESP (Brevo)&#10;✓ Stake sending domain&#10;&#10;Unknown data:&#10;• CasinoMax ESP/CRM&#10;• VegasCasino ESP/CRM&#10;• SlotsOfVegas ESP/CRM&#10;&#10;Future: Email header analysis">
<i class="fa-solid fa-flask text-xs mr-1"></i>Partial Data
</span>
<div id="date-range-container" class="flex items-center"></div>
<button
class="px-6 py-2.5 rounded-lg font-medium shadow-sm flex items-center gap-2 transition-colors bg-purple-600 hover:bg-purple-700 text-white">
<i class="fa-solid fa-plus"></i>
Add Vendor
</button>
</header>
<!-- Info Banner -->
<div class="bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-200 rounded-xl p-4 mb-6">
<div class="flex items-start gap-3">
<div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
<i class="fa-solid fa-envelope text-purple-600"></i>
</div>
<div>
<h3 class="font-semibold text-purple-900">CRM & ESP Detection</h3>
<p class="text-sm text-purple-700 mt-1">This module identifies the Email Service Provider (ESP),
CRM platform, and sending infrastructure used by each competitor. Data is extracted from
email headers, DKIM signatures, and sending patterns.</p>
</div>
</div>
</div>
<!-- Main CRM/ESP Table -->
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm mb-6">
<table class="w-full text-sm">
<thead class="bg-slate-50 border-b border-slate-200">
<tr>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Competitor
</th>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Market</th>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">ESP Provider
</th>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">CRM Platform
</th>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Sending
Domain</th>
<th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">DKIM</th>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Channels</th>
<th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Emails</th>
</tr>
</thead>
<tbody>
<!-- CasinoMax -->
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4">
<div class="flex items-center gap-2">
<div
class="w-7 h-7 bg-gradient-to-br from-purple-400 to-purple-600 rounded flex items-center justify-center text-white font-bold text-xs">
C</div>
<div>
<p class="font-medium text-slate-800">CasinoMax</p>
<span
class="text-xs bg-red-100 text-red-600 px-1.5 py-0.5 rounded">Unlicensed</span>
</div>
</div>
</td>
<td class="py-3 px-4"><span class="text-sm">🇨🇦 Ontario</span></td>
<td class="py-3 px-4"><span
class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium">SendGrid</span>
</td>
<td class="py-3 px-4 text-slate-500 italic">Unknown</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">mail.casinomax.com</code></td>
<td class="py-3 px-4 text-center"><i class="fa-solid fa-check text-green-500"></i></td>
<td class="py-3 px-4"><span
class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs">Email</span></td>
<td class="py-3 px-4 text-center font-medium">36</td>
</tr>
<!-- VegasCasinoOnline -->
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4">
<div class="flex items-center gap-2">
<div
class="w-7 h-7 bg-gradient-to-br from-amber-400 to-amber-600 rounded flex items-center justify-center text-white font-bold text-xs">
V</div>
<div>
<p class="font-medium text-slate-800">VegasCasinoOnline</p>
<span
class="text-xs bg-red-100 text-red-600 px-1.5 py-0.5 rounded">Unlicensed</span>
</div>
</div>
</td>
<td class="py-3 px-4"><span class="text-sm">🇨🇦 Ontario</span></td>
<td class="py-3 px-4"><span
class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Mailchimp</span>
</td>
<td class="py-3 px-4 text-slate-500 italic">Unknown</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">vco-mail.com</code></td>
<td class="py-3 px-4 text-center"><i class="fa-solid fa-check text-green-500"></i></td>
<td class="py-3 px-4"><span
class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs">Email</span></td>
<td class="py-3 px-4 text-center font-medium">27</td>
</tr>
<!-- Stake -->
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4">
<div class="flex items-center gap-2">
<div
class="w-7 h-7 bg-gradient-to-br from-green-400 to-green-600 rounded flex items-center justify-center text-white font-bold text-xs">
S</div>
<div>
<p class="font-medium text-slate-800">Stake</p>
<span
class="text-xs bg-green-100 text-green-600 px-1.5 py-0.5 rounded">Licensed</span>
</div>
</div>
</td>
<td class="py-3 px-4"><span class="text-sm">🇨🇦 Ontario</span></td>
<td class="py-3 px-4"><span
class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-medium">Braze</span>
</td>
<td class="py-3 px-4"><span
class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-medium">Braze</span>
</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">mail.stake.com</code></td>
<td class="py-3 px-4 text-center"><i class="fa-solid fa-check text-green-500"></i></td>
<td class="py-3 px-4">
<span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs mr-1">Email</span>
<span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs">Push</span>
</td>
<td class="py-3 px-4 text-center font-medium">26</td>
</tr>
<!-- JackpotCityCasino -->
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4">
<div class="flex items-center gap-2">
<div
class="w-7 h-7 bg-gradient-to-br from-blue-400 to-blue-600 rounded flex items-center justify-center text-white font-bold text-xs">
J</div>
<div>
<p class="font-medium text-slate-800">JackpotCityCasino</p>
<span
class="text-xs bg-green-100 text-green-600 px-1.5 py-0.5 rounded">Licensed</span>
</div>
</div>
</td>
<td class="py-3 px-4"><span class="text-sm">🇨🇦 Ontario</span></td>
<td class="py-3 px-4"><span
class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-medium">Optimove</span>
</td>
<td class="py-3 px-4"><span
class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-medium">Optimove</span>
</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">jpc-mail.com</code></td>
<td class="py-3 px-4 text-center"><i class="fa-solid fa-check text-green-500"></i></td>
<td class="py-3 px-4">
<span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs mr-1">Email</span>
<span class="bg-purple-100 text-purple-700 px-2 py-0.5 rounded text-xs">SMS</span>
</td>
<td class="py-3 px-4 text-center font-medium">10</td>
</tr>
<!-- SlotsOfVegas -->
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4">
<div class="flex items-center gap-2">
<div
class="w-7 h-7 bg-gradient-to-br from-red-400 to-red-600 rounded flex items-center justify-center text-white font-bold text-xs">
S</div>
<div>
<p class="font-medium text-slate-800">SlotsOfVegas</p>
<span
class="text-xs bg-red-100 text-red-600 px-1.5 py-0.5 rounded">Unlicensed</span>
</div>
</div>
</td>
<td class="py-3 px-4"><span class="text-sm">🇨🇦 Ontario</span></td>
<td class="py-3 px-4"><span
class="bg-amber-100 text-amber-700 px-2 py-1 rounded text-xs font-medium">Amazon
SES</span></td>
<td class="py-3 px-4 text-slate-500 italic">Unknown</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">sov-promo.com</code></td>
<td class="py-3 px-4 text-center"><i
class="fa-solid fa-exclamation-triangle text-amber-500"></i></td>
<td class="py-3 px-4"><span
class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs">Email</span></td>
<td class="py-3 px-4 text-center font-medium">4</td>
</tr>
</tbody>
</table>
</div>
<!-- ESP Summary Table -->
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm mb-6">
<div class="px-5 py-4 border-b border-slate-200 bg-slate-50">
<h3 class="font-semibold text-slate-800">ESP Provider Distribution</h3>
</div>
<table class="w-full text-sm">
<thead class="bg-slate-50 border-b border-slate-200">
<tr>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">ESP Provider
</th>
<th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Competitors
</th>
<th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Emails</th>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Type</th>
</tr>
</thead>
<tbody>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4 font-medium">Braze</td>
<td class="py-3 px-4 text-center">1</td>
<td class="py-3 px-4 text-center">26</td>
<td class="py-3 px-4"><span
class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs">Enterprise
CDP</span></td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4 font-medium">Optimove</td>
<td class="py-3 px-4 text-center">1</td>
<td class="py-3 px-4 text-center">10</td>
<td class="py-3 px-4"><span
class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs">Gaming CRM</span>
</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4 font-medium">SendGrid</td>
<td class="py-3 px-4 text-center">1</td>
<td class="py-3 px-4 text-center">36</td>
<td class="py-3 px-4"><span
class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">Transactional ESP</span>
</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4 font-medium">Mailchimp</td>
<td class="py-3 px-4 text-center">1</td>
<td class="py-3 px-4 text-center">27</td>
<td class="py-3 px-4"><span
class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Marketing ESP</span>
</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4 font-medium">Amazon SES</td>
<td class="py-3 px-4 text-center">1</td>
<td class="py-3 px-4 text-center">4</td>
<td class="py-3 px-4"><span
class="bg-amber-100 text-amber-700 px-2 py-1 rounded text-xs">Infrastructure</span>
</td>
</tr>
</tbody>
</table>
</div>
<!-- IP Infrastructure Section -->
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm mb-6">
<div class="px-5 py-4 border-b border-slate-200 bg-gradient-to-r from-indigo-50 to-blue-50">
<h3 class="font-semibold text-slate-800">
<i class="fa-solid fa-network-wired mr-2 text-indigo-600"></i>
IP Infrastructure & Sending Reputation
</h3>
<p class="text-xs text-slate-500 mt-1">Sending IP analysis and reputation scores</p>
</div>
<table class="w-full text-sm">
<thead class="bg-slate-50 border-b border-slate-200">
<tr>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Competitor
</th>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Sending IP
</th>
<th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">IP Type
</th>
<th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Reputation
</th>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Reverse DNS
</th>
</tr>
</thead>
<tbody>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4 font-medium">CasinoMax</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">149.72.xx.xxx</code></td>
<td class="py-3 px-4 text-center"><span
class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded text-xs">Shared</span></td>
<td class="py-3 px-4 text-center"><span class="text-amber-600 font-semibold">72/100</span>
</td>
<td class="py-3 px-4"><code class="text-xs text-slate-500">mail.sendgrid.net</code></td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4 font-medium">JackpotCity</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">185.12.xx.xxx</code></td>
<td class="py-3 px-4 text-center"><span
class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded text-xs">Dedicated</span>
</td>
<td class="py-3 px-4 text-center"><span class="text-emerald-600 font-semibold">94/100</span>
</td>
<td class="py-3 px-4"><code class="text-xs text-slate-500">mail.jpc-mail.com</code></td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4 font-medium">bet365</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">52.19.xx.xxx</code></td>
<td class="py-3 px-4 text-center"><span
class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded text-xs">Dedicated</span>
</td>
<td class="py-3 px-4 text-center"><span class="text-emerald-600 font-semibold">98/100</span>
</td>
<td class="py-3 px-4"><code class="text-xs text-slate-500">a52-19.smtp.bet365.com</code>
</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4 font-medium">SlotsOfVegas</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">54.240.xx.xxx</code></td>
<td class="py-3 px-4 text-center"><span
class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded text-xs">Shared</span></td>
<td class="py-3 px-4 text-center"><span class="text-red-600 font-semibold">45/100</span>
</td>
<td class="py-3 px-4"><code class="text-xs text-slate-500">a54-240.smtp.amazonses.com</code>
</td>
</tr>
</tbody>
</table>
</div>
<!-- Summary Stats -->
<div class="grid grid-cols-4 gap-4">
<div class="bg-white rounded-xl border border-slate-200 p-4">
<div class="text-2xl font-bold text-slate-800">5</div>
<div class="text-sm text-slate-500">Competitors Tracked</div>
</div>
<div class="bg-white rounded-xl border border-slate-200 p-4">
<div class="text-2xl font-bold text-purple-600">5</div>
<div class="text-sm text-slate-500">ESP Providers</div>
</div>
<div class="bg-white rounded-xl border border-slate-200 p-4">
<div class="text-2xl font-bold text-green-600">4</div>
<div class="text-sm text-slate-500">Valid DKIM</div>
</div>
<div class="bg-white rounded-xl border border-slate-200 p-4">
<div class="text-2xl font-bold text-blue-600">103</div>
<div class="text-sm text-slate-500">Total Emails</div>
</div>
</div>
</main>
@endsection

