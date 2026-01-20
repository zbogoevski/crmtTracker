@extends('layouts.dashboard')

@section('title', 'CRMTracker - Report Dashboard')

@section('content')
    <header
        class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 flex-shrink-0 z-20">
        <div class="flex items-center gap-4">
            <h1 class="text-lg font-semibold text-slate-800">CRMTracker Report Dashboard</h1>
        </div>
        <div class="flex items-center gap-4">
            <!-- Group Selector -->
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-500">Group:</span>
                <select id="group-select"
                    class="bg-purple-50 border border-purple-200 text-purple-700 text-sm rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 min-w-[180px]"
                    onchange="window.location.href='?group=' + this.value">
                    <option value="">All Competitors</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}" {{ isset($activeGroup) && $activeGroup->id === $group->id ? 'selected' : '' }}>
                            {{ $group->flag ?? '📁' }} {{ $group->name }} ({{ $group->competitor_count }})
                        </option>
                    @endforeach
                </select>
            </div>
            <!-- Country Flag -->
            <div id="group-country-flag" class="text-xl" title="Group Country">
                📁
            </div>
            <!-- Benchmark Segment Selector -->
            <div class="flex items-center gap-2 border-l border-slate-200 pl-4">
                <span class="text-xs text-slate-500">Benchmark:</span>
                <select id="dashboard-benchmark-tier"
                    class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-lg px-2 py-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="top20" selected>Top 20</option>
                    <option value="top50">Top 50</option>
                    <option value="top100">Top 100</option>
                    <option value="all">All Market</option>
                </select>
            </div>
            <!-- Data Status -->
            <div id="dashboard-data-status">
                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">
                    <i class="fa-solid fa-check text-[10px] mr-1"></i>Data Available
                </span>
            </div>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-8">
        <div class="w-full">

            <!-- Hero Section -->
            <div class="gradient-hero rounded-2xl p-8 mb-8 text-white shadow-xl">
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-3xl font-bold mb-2">Welcome to CRMTracker®</h2>
                        <p class="text-slate-300 max-w-xl">Navigate through the comprehensive CRM and Transparency
                            analysis reports. Select a module from the sidebar to begin your audit journey.</p>
                    </div>
                    <div id="date-range-container" class="flex items-center"></div>

                    <div class="bg-white/10 rounded-xl p-4 backdrop-blur border border-white/20">
                        <div class="flex items-center justify-between mb-1">
                            <p class="text-xs font-bold text-slate-300 uppercase">CRMTracker® Score</p>
                            <button id="recalc-btn"
                                class="text-xs bg-white/20 hover:bg-white/30 px-2 py-1 rounded transition-colors"
                                title="Recalculate scores from latest data">
                                <i class="fa-solid fa-refresh"></i>
                            </button>
                        </div>
                        <p class="text-4xl font-bold"><span id="crmt-overall-score">{{ $overallAverage }}</span><span
                                class="text-lg text-slate-400">/100</span></p>
                        <p class="text-xs text-slate-400 mt-1" id="crmt-score-trend">Avg. of {{ $competitorCount }} competitors</p>
                        <p class="text-[10px] text-slate-500 mt-1" id="score-computed-at"></p>
                    </div>
                </div>
            </div>

            <!-- Alerts Widget -->
            <div id="alerts-widget"
                class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-8 overflow-hidden">
                <div
                    class="bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-bell text-white text-lg"></i>
                        <h3 class="text-lg font-bold text-white">Alerts & Notifications</h3>
                            <span id="alerts-count"
                                class="bg-white/20 text-white text-xs px-2 py-0.5 rounded-full">{{ $alertsCount }}</span>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="dismissAllAlerts()"
                            class="text-white/80 hover:text-white transition-colors text-sm" title="Dismiss All">
                            <i class="fa-solid fa-check-double"></i>
                        </button>
                        <button onclick="refreshAlerts()" class="text-white/80 hover:text-white transition-colors"
                            title="Refresh">
                            <i class="fa-solid fa-refresh"></i>
                        </button>
                    </div>
                </div>

                    <div id="alerts-container" class="max-h-48 overflow-y-auto">
                        @if($alerts->isEmpty())
                            <div class="p-4 text-center text-slate-400 text-sm">
                                <i class="fa-solid fa-check-circle text-green-500 mr-2"></i>No active alerts
                            </div>
                        @else
                            @foreach($alerts as $alert)
                                <div class="flex items-start gap-3 p-3 border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                    <i class="fa-solid @if($alert->severity === 'critical') fa-circle-exclamation text-red-500 @elseif($alert->severity === 'warning') fa-triangle-exclamation text-amber-500 @elseif($alert->severity === 'info') fa-circle-info text-blue-500 @else fa-check-circle text-green-500 @endif mt-0.5"></i>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-slate-700 truncate">{{ $alert->title }}</p>
                                        <p class="text-xs text-slate-500 truncate">{{ $alert->message }}</p>
                                        <p class="text-[10px] text-slate-400 mt-1">{{ $alert->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                    <form method="POST" action="{{ route('alerts.dismiss', $alert->id) }}" class="inline" onsubmit="return confirm('Dismiss this alert?');">
                                        @csrf
                                        <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors" title="Dismiss">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        @endif
                    </div>
            </div>

            <!-- Dynamic Scorecard Container -->
            <div id="scorecard-container"
                class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-8 overflow-hidden">
                <div
                    class="bg-gradient-to-r from-purple-600 to-blue-600 px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-star text-yellow-300 text-xl"></i>
                        <h3 class="text-xl font-bold text-white">CRMTracker Scorecard Overview</h3>
                        <span id="scorecard-report-count"
                            class="text-xs bg-white/20 text-white px-2 py-0.5 rounded-full">--</span>
                    </div>
                    <label class="flex items-center gap-2 text-white/80 text-sm cursor-pointer">
                        <input type="checkbox" id="show-unavailable-toggle"
                            class="rounded border-white/30 bg-white/10 text-purple-400 focus:ring-purple-400">
                        <span>Show Unavailable</span>
                    </label>
                </div>
                <div id="scorecard-body" class="p-8">
                    @php
                        $competitors = \App\Modules\Competitor\Infrastructure\Models\Competitor::where('is_active', true)->get(['id', 'name']);
                        // Extract unique report IDs from scores
                        $reportIds = [];
                        if (!empty($scores)) {
                            foreach ($scores as $competitorScores) {
                                if (is_array($competitorScores)) {
                                    $reportIds = array_merge($reportIds, array_keys($competitorScores));
                                }
                            }
                            $reportIds = array_unique($reportIds);
                            sort($reportIds);
                        }
                        $reportNames = [
                            '1.1' => 'Performance Dashboard',
                            '1.2' => 'Journey Mapping',
                            '1.3' => 'Customer Lifecycle',
                            '2.1' => 'Content Quality Hub',
                            '2.2' => 'Offers & Risk',
                            '3.1' => 'Compliance Scorecard',
                            '3.2' => 'Compliance Alignment',
                            '3.3' => 'Audit Preparedness',
                            '4.1' => 'License & Entity',
                            '4.2' => 'Compliance Risk Score',
                        ];
                    @endphp
                    @if(empty($scores) || $competitors->isEmpty())
                        <div class="text-center text-slate-400">
                            <i class="fa-solid fa-exclamation-triangle text-3xl mb-3"></i>
                            <p class="font-medium">No scorecard data available</p>
                            <p class="text-sm mt-1">Scores will appear here once data is available</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-slate-200">
                                        <th class="text-left py-3 px-4 font-semibold text-slate-700">Report</th>
                                        @foreach($competitors as $competitor)
                                            <th class="text-center py-3 px-3 font-semibold text-slate-700">{{ $competitor->name }}</th>
                                        @endforeach
                                        <th class="text-center py-3 px-3 font-semibold text-slate-700">Avg</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reportIds as $reportId)
                                        @php
                                            $reportName = $reportNames[$reportId] ?? $reportId;
                                            $reportScores = [];
                                            $totalScore = 0;
                                            $scoreCount = 0;
                                            foreach($competitors as $competitor) {
                                                $score = $scores[$competitor->id][$reportId] ?? null;
                                                if($score !== null) {
                                                    $reportScores[$competitor->id] = $score;
                                                    $totalScore += $score;
                                                    $scoreCount++;
                                                }
                                            }
                                            $avgScore = $scoreCount > 0 ? round($totalScore / $scoreCount, 1) : null;
                                        @endphp
                                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                            <td class="py-3 px-4">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm font-medium text-slate-800">{{ $reportName }}</span>
                                                    <span class="text-xs text-slate-500">({{ $reportId }})</span>
                                                </div>
                                            </td>
                                            @foreach($competitors as $competitor)
                                                @php
                                                    $score = $reportScores[$competitor->id] ?? null;
                                                    $colorClass = $score !== null 
                                                        ? ($score >= 80 ? 'text-green-600' : ($score >= 60 ? 'text-yellow-600' : 'text-red-600'))
                                                        : 'text-slate-400';
                                                @endphp
                                                <td class="py-3 px-3 text-center">
                                                    <span class="text-lg font-bold {{ $colorClass }}">
                                                        {{ $score !== null ? $score : '—' }}
                                                    </span>
                                                </td>
                                            @endforeach
                                            <td class="py-3 px-3 text-center">
                                                <span class="text-lg font-bold {{ $avgScore !== null ? ($avgScore >= 80 ? 'text-green-600' : ($avgScore >= 60 ? 'text-yellow-600' : 'text-red-600')) : 'text-slate-400' }}">
                                                    {{ $avgScore !== null ? $avgScore : '—' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 text-center">
                            <span id="scorecard-report-count" class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded-full">
                                {{ count($reportIds) }} reports
                            </span>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Placeholder functions for dashboard functionality
        function dismissAllAlerts() {
            console.log('Dismiss all alerts');
        }

        function refreshAlerts() {
            console.log('Refresh alerts');
        }

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Dashboard loaded');
        });
    </script>
@endpush
