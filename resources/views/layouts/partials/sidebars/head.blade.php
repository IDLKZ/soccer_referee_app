<aside class="w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 min-h-screen">
    <div class="px-4 py-6">
        <!-- Sidebar Navigation -->
        <nav class="space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <i class="fas fa-home w-5"></i>
                <span class="ml-3">Главная</span>
            </a>

            <!-- Business Processes -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Бизнес процессы
                </p>
            </div>

            @php
                // 1. Утверждение судейской бригады
                // Count matches where operation is 'referee_team_approval' and all judges responded (judge_response = 1) but final_status = 0
                $refereeTeamApprovalCount = \App\Models\MatchEntity::whereHas('operation', function($q) {
                    $q->where('value', 'referee_team_approval');
                })
                ->whereHas('match_judges', function($q) {
                    $q->where('judge_response', 1)
                      ->where('final_status', 0);
                })
                ->count();

                // 2. Финальная проверка командировок
                $finalTripCount = \App\Models\Trip::whereHas('operation', function($q) {
                    $q->where('value', 'final_business_trip_confirmation');
                })->count();

                // 3. Финальная проверка протоколов
                $finalProtocolCount = \App\Models\Protocol::whereHas('operation', function($q) {
                    $q->where('value', 'control_protocol_approval');
                })
                ->where('final_status', 0)
                ->count();

                // 4. Проверка АВР
                $avrCommitteeCount = \App\Models\ActOfWork::whereHas('operation', function($q) {
                    $q->where('value', 'avr_committee_approval');
                })->count();
            @endphp

            <a href="{{ route('referee-team-approval-cards') }}"
               class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('referee-team-approval-cards') || request()->routeIs('referee-team-approval-detail') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <div class="flex items-center">
                    <i class="fas fa-users-cog w-5"></i>
                    <span class="ml-3">Утверждение судейской бригады</span>
                </div>
                @if($refereeTeamApprovalCount > 0)
                    <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                        {{ $refereeTeamApprovalCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('final-business-trip-confirmation') }}"
               class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('final-business-trip-confirmation') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <div class="flex items-center">
                    <i class="fas fa-plane-departure w-5"></i>
                    <span class="ml-3">Финальная проверка командировок</span>
                </div>
                @if($finalTripCount > 0)
                    <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                        {{ $finalTripCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('control-protocol-approval') }}"
               class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('control-protocol-approval') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <div class="flex items-center">
                    <i class="fas fa-file-signature w-5"></i>
                    <span class="ml-3">Финальная проверка протоколов</span>
                </div>
                @if($finalProtocolCount > 0)
                    <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                        {{ $finalProtocolCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('avr-approval-by-committee') }}"
               class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('avr-approval-by-committee') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <div class="flex items-center">
                    <i class="fas fa-gavel w-5"></i>
                    <span class="ml-3">Проверка АВР</span>
                </div>
                @if($avrCommitteeCount > 0)
                    <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                        {{ $avrCommitteeCount }}
                    </span>
                @endif
            </a>
        </nav>
    </div>
</aside>
