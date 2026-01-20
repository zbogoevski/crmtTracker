@extends('layouts.dashboard')


@section('title', 'D.3 Company & Entity Verification | CRMTracker')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
            color: #334155;
        }
</style>
@endpush

@section('content')
<main class="flex-1 ml-20 p-8 max-w-[1800px] mx-auto overflow-x-hidden">
<header class="flex justify-between items-center mb-6">
<div class="flex items-center gap-3">
<span
class="text-xs font-medium bg-purple-50 text-purple-700 px-2 py-1 rounded border border-purple-200">Data
Module D.3</span>
<span
class="text-xs font-medium bg-green-50 text-green-700 px-2 py-1 rounded border border-green-200">
<i class="fa-solid fa-check text-xs mr-1"></i>12 Entities
</span>
<span
class="text-xs font-medium bg-slate-100 text-slate-600 px-2 py-1 rounded border border-slate-200">
<i class="fa-solid fa-clock text-xs mr-1"></i>Updated Dec 12, 2024
</span>
<div id="date-range-container" class="flex items-center"></div>
<button
class="px-6 py-2.5 rounded-lg font-medium shadow-sm flex items-center gap-2 transition-colors bg-purple-600 hover:bg-purple-700 text-white">
<i class="fa-solid fa-plus"></i>
Add Entity
</button>
</header>
<!-- Info Banner -->
<div class="bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-200 rounded-xl p-4 mb-6">
<div class="flex items-start gap-3">
<div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
<i class="fa-solid fa-building text-purple-600"></i>
</div>
<div>
<h3 class="font-semibold text-purple-900">Corporate Entity Verification</h3>
<p class="text-sm text-purple-700 mt-1">This module maps brands to their parent companies. When
analyzing emails, we extract footer text and match it against the expected corporate entity
to verify legitimacy.</p>
</div>
</div>
</div>
<!-- Quick Nav -->
<div class="flex gap-3 mb-6 flex-wrap">
<a href="#ca-entities"
class="px-4 py-2 bg-red-100 text-red-700 rounded-lg font-medium hover:bg-red-200 transition-colors">🇨🇦
Canada</a>
<a href="#uk-entities"
class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg font-medium hover:bg-blue-200 transition-colors">🇬🇧
UK</a>
<a href="#us-entities"
class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg font-medium hover:bg-indigo-200 transition-colors">🇺🇸
USA</a>
<a href="#ch-entities"
class="px-4 py-2 bg-red-100 text-red-700 rounded-lg font-medium hover:bg-red-200 transition-colors">🇨🇭
Switzerland</a>
<a href="#tr-entities"
class="px-4 py-2 bg-red-100 text-red-600 rounded-lg font-medium hover:bg-red-200 transition-colors">🇹🇷
Turkey</a>
</div>
<!-- Canada Table -->
<div id="ca-entities" class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm mb-6">
<div class="px-5 py-4 border-b border-slate-200 bg-red-50 flex items-center gap-3">
<span class="text-xl">🇨🇦</span>
<h3 class="font-semibold text-red-900">Canada - Ontario (AGCO)</h3>
<span class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded">4 Entities</span>
</div>
<table class="w-full text-sm">
<thead class="bg-slate-50 border-b border-slate-200">
<tr>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Brand(s)</th>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Corporate
Entity</th>
<th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Status</th>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Footer
Pattern</th>
<th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">In D.6</th>
</tr>
</thead>
<tbody>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4 font-medium">BetMGM</td>
<td class="py-3 px-4">BetMGM Canada Inc.</td>
<td class="py-3 px-4 text-center"><span
class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Authorized</span>
</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">"BetMGM Canada Inc."</code></td>
<td class="py-3 px-4 text-center text-slate-300">—</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4 font-medium">FanDuel</td>
<td class="py-3 px-4">FanDuel Canada ULC</td>
<td class="py-3 px-4 text-center"><span
class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Authorized</span>
</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">"FanDuel Canada ULC"</code></td>
<td class="py-3 px-4 text-center text-slate-300">—</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4 font-medium">DraftKings</td>
<td class="py-3 px-4">Crown DK CAN Ltd</td>
<td class="py-3 px-4 text-center"><span
class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Authorized</span>
</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">"Crown DK" | "DraftKings"</code></td>
<td class="py-3 px-4 text-center text-slate-300">—</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4 font-medium">JackpotCity, Spin Casino</td>
<td class="py-3 px-4">Cadtree Limited</td>
<td class="py-3 px-4 text-center"><span
class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Authorized</span>
</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">"Cadtree Limited"</code></td>
<td class="py-3 px-4 text-center"><i class="fa-solid fa-check text-green-500"></i></td>
</tr>
</tbody>
</table>
</div>
<!-- UK Table -->
<div id="uk-entities" class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm mb-6">
<div class="px-5 py-4 border-b border-slate-200 bg-blue-50 flex items-center gap-3">
<span class="text-xl">🇬🇧</span>
<h3 class="font-semibold text-blue-900">United Kingdom (UKGC)</h3>
<span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded">4 Entities</span>
</div>
<table class="w-full text-sm">
<thead class="bg-slate-50 border-b border-slate-200">
<tr>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Brand(s)</th>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Corporate
Entity</th>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">License #
</th>
<th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Status</th>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Footer
Pattern</th>
</tr>
</thead>
<tbody>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4 font-medium">William Hill, Mr Green</td>
<td class="py-3 px-4">WHG (International) Ltd</td>
<td class="py-3 px-4 font-mono text-xs">39198</td>
<td class="py-3 px-4 text-center"><span
class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Active</span>
</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">"WHG" | "William Hill"</code></td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4 font-medium">Ladbrokes, Coral</td>
<td class="py-3 px-4">LC International Ltd</td>
<td class="py-3 px-4 font-mono text-xs">54743</td>
<td class="py-3 px-4 text-center"><span
class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Active</span>
</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">"LC International"</code></td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4 font-medium">Betway</td>
<td class="py-3 px-4">Betway Limited</td>
<td class="py-3 px-4 font-mono text-xs">39372</td>
<td class="py-3 px-4 text-center"><span
class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Active</span>
</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">"Betway Limited"</code></td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4 font-medium">PokerStars, BetStars</td>
<td class="py-3 px-4">Stars Interactive Ltd</td>
<td class="py-3 px-4 font-mono text-xs">39108</td>
<td class="py-3 px-4 text-center"><span
class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Active</span>
</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">"Stars Interactive" | "Flutter"</code>
</td>
</tr>
</tbody>
</table>
</div>
<!-- US Table -->
<div id="us-entities" class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm mb-6">
<div class="px-5 py-4 border-b border-slate-200 bg-indigo-50 flex items-center gap-3">
<span class="text-xl">🇺🇸</span>
<h3 class="font-semibold text-indigo-900">United States (Multi-State)</h3>
<span class="text-xs bg-indigo-100 text-indigo-600 px-2 py-1 rounded">4 Entities</span>
</div>
<table class="w-full text-sm">
<thead class="bg-slate-50 border-b border-slate-200">
<tr>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Brand(s)</th>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Corporate
Entity</th>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">States</th>
<th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Status</th>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Footer
Pattern</th>
</tr>
</thead>
<tbody>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4 font-medium">DraftKings Sportsbook, DK Casino</td>
<td class="py-3 px-4">DraftKings Inc.</td>
<td class="py-3 px-4"><span class="text-xs bg-slate-100 px-1 rounded mr-1">NJ</span><span
class="text-xs bg-slate-100 px-1 rounded mr-1">PA</span><span
class="text-xs bg-slate-100 px-1 rounded mr-1">MI</span><span
class="text-xs bg-slate-100 px-1 rounded">+2</span></td>
<td class="py-3 px-4 text-center"><span
class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Authorized</span>
</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">"DraftKings Inc."</code></td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4 font-medium">FanDuel, PokerStars US</td>
<td class="py-3 px-4">Flutter Entertainment</td>
<td class="py-3 px-4"><span class="text-xs bg-slate-100 px-1 rounded mr-1">NJ</span><span
class="text-xs bg-slate-100 px-1 rounded mr-1">PA</span><span
class="text-xs bg-slate-100 px-1 rounded mr-1">MI</span><span
class="text-xs bg-slate-100 px-1 rounded">+2</span></td>
<td class="py-3 px-4 text-center"><span
class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Authorized</span>
</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">"FanDuel" | "Flutter"</code></td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4 font-medium">BetMGM, Borgata Online</td>
<td class="py-3 px-4">Entain PLC / MGM</td>
<td class="py-3 px-4"><span class="text-xs bg-slate-100 px-1 rounded mr-1">NJ</span><span
class="text-xs bg-slate-100 px-1 rounded mr-1">PA</span><span
class="text-xs bg-slate-100 px-1 rounded mr-1">MI</span><span
class="text-xs bg-slate-100 px-1 rounded">WV</span></td>
<td class="py-3 px-4 text-center"><span
class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Authorized</span>
</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">"BetMGM" | "Roar Digital"</code></td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4 font-medium">Caesars, WSOP, Harrah's</td>
<td class="py-3 px-4">Caesars Digital</td>
<td class="py-3 px-4"><span class="text-xs bg-slate-100 px-1 rounded mr-1">NJ</span><span
class="text-xs bg-slate-100 px-1 rounded mr-1">PA</span><span
class="text-xs bg-slate-100 px-1 rounded mr-1">MI</span><span
class="text-xs bg-slate-100 px-1 rounded">WV</span></td>
<td class="py-3 px-4 text-center"><span
class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Authorized</span>
</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">"Caesars" | "CIE"</code></td>
</tr>
</tbody>
</table>
</div>
<!-- Switzerland Table -->
<div id="ch-entities" class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm mb-6">
<div class="px-5 py-4 border-b border-slate-200 bg-red-50 flex items-center gap-3">
<span class="text-xl">🇨🇭</span>
<h3 class="font-semibold text-red-900">Switzerland (ESBK)</h3>
<span class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded">5 Entities</span>
</div>
<table class="w-full text-sm">
<thead class="bg-slate-50 border-b border-slate-200">
<tr>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Brand(s)</th>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Casino
Partner</th>
<th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Status</th>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Footer
Pattern</th>
</tr>
</thead>
<tbody>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4 font-medium">Swiss Casinos</td>
<td class="py-3 px-4">Swiss Casinos Holding AG</td>
<td class="py-3 px-4 text-center"><span
class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Licensed</span>
</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">"Swiss Casinos"</code></td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4 font-medium">Swiss4Win, Starvegas</td>
<td class="py-3 px-4">Casino Interlaken AG</td>
<td class="py-3 px-4 text-center"><span
class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Licensed</span>
</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">"Casino Interlaken"</code></td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4 font-medium">7 Melons</td>
<td class="py-3 px-4">Grand Casino Bern AG</td>
<td class="py-3 px-4 text-center"><span
class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Licensed</span>
</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">"Grand Casino Bern"</code></td>
</tr>
<tr class="border-b border-slate-100 hover:bg-slate-50">
<td class="py-3 px-4 font-medium">Jackpots.ch</td>
<td class="py-3 px-4">Grand Casino Luzern AG</td>
<td class="py-3 px-4 text-center"><span
class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Licensed</span>
</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">"Casino Luzern"</code></td>
</tr>
<tr class="border-b border-slate-100 hover:bg-amber-50/50">
<td class="py-3 px-4 font-medium text-amber-700">Stake, Bet365 (offshore)</td>
<td class="py-3 px-4 text-slate-500 italic">None - Offshore</td>
<td class="py-3 px-4 text-center"><span
class="bg-amber-100 text-amber-700 px-2 py-1 rounded text-xs font-medium">Grey</span>
</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">"Curaçao" | "Malta"</code></td>
</tr>
</tbody>
</table>
</div>
<!-- Turkey Table -->
<div id="tr-entities" class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm mb-6">
<div class="px-5 py-4 border-b border-slate-200 bg-red-50 flex items-center gap-3">
<span class="text-xl">🇹🇷</span>
<h3 class="font-semibold text-red-900">Turkey (Grey Market)</h3>
<span class="text-xs bg-amber-100 text-amber-600 px-2 py-1 rounded">All Offshore</span>
</div>
<div class="p-4 bg-amber-50 border-b border-amber-100 text-sm text-amber-800">
<i class="fa-solid fa-triangle-exclamation mr-2"></i>
No licensed operators exist in Turkey. All tracked operators use offshore licenses (Curaçao, Malta).
</div>
<table class="w-full text-sm">
<thead class="bg-slate-50 border-b border-slate-200">
<tr>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Brand(s)</th>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Primary
License</th>
<th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Status</th>
<th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase">Footer
Pattern</th>
</tr>
</thead>
<tbody>
<tr class="border-b border-slate-100 hover:bg-amber-50/50">
<td class="py-3 px-4 font-medium text-amber-700">Casibom</td>
<td class="py-3 px-4 text-slate-500 italic">Curaçao</td>
<td class="py-3 px-4 text-center"><span
class="bg-amber-100 text-amber-700 px-2 py-1 rounded text-xs font-medium">Grey</span>
</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">"Antillephone"</code></td>
</tr>
<tr class="border-b border-slate-100 hover:bg-amber-50/50">
<td class="py-3 px-4 font-medium text-amber-700">1xBet, Mostbet</td>
<td class="py-3 px-4 text-slate-500 italic">Curaçao</td>
<td class="py-3 px-4 text-center"><span
class="bg-amber-100 text-amber-700 px-2 py-1 rounded text-xs font-medium">Grey</span>
</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">"1X Corp N.V."</code></td>
</tr>
<tr class="border-b border-slate-100 hover:bg-amber-50/50">
<td class="py-3 px-4 font-medium text-amber-700">Bets10, Mobilbahis</td>
<td class="py-3 px-4 text-slate-500 italic">Malta</td>
<td class="py-3 px-4 text-center"><span
class="bg-amber-100 text-amber-700 px-2 py-1 rounded text-xs font-medium">Grey</span>
</td>
<td class="py-3 px-4"><code class="text-xs bg-slate-100 px-2 py-1 rounded">"Betsson"</code>
</td>
</tr>
<tr class="border-b border-slate-100 hover:bg-green-50/50">
<td class="py-3 px-4 font-medium">Tuttur, Misli</td>
<td class="py-3 px-4 text-slate-600">Spor Toto (State)</td>
<td class="py-3 px-4 text-center"><span
class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">State</span>
</td>
<td class="py-3 px-4"><code
class="text-xs bg-slate-100 px-2 py-1 rounded">"Spor Toto"</code></td>
</tr>
</tbody>
</table>
</div>
<!-- Summary Stats -->
<div class="grid grid-cols-4 gap-4">
<div class="bg-white rounded-xl border border-slate-200 p-4">
<div class="text-2xl font-bold text-slate-800">12</div>
<div class="text-sm text-slate-500">Corporate Entities</div>
</div>
<div class="bg-white rounded-xl border border-slate-200 p-4">
<div class="text-2xl font-bold text-purple-600">24+</div>
<div class="text-sm text-slate-500">Brands Mapped</div>
</div>
<div class="bg-white rounded-xl border border-slate-200 p-4">
<div class="text-2xl font-bold text-green-600">3</div>
<div class="text-sm text-slate-500">Jurisdictions</div>
</div>
<div class="bg-white rounded-xl border border-slate-200 p-4">
<div class="text-2xl font-bold text-blue-600">12</div>
<div class="text-sm text-slate-500">Footer Patterns</div>
</div>
</div>
</main>
@endsection

