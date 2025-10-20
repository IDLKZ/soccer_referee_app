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
                // 1. Мои приглашения - непринятые приглашения судьи
                $myInvitationsCount = \App\Models\MatchJudge::where('judge_id', auth()->id())
                    ->where('judge_response', 0)
                    ->count();

                // 2. Мои командировки - сложная логика
                // Сначала ищем поездки судьи со статусом 0 в операции referee_team_confirmation
                $myTripsCount = \App\Models\Trip::where('judge_id', auth()->id())
                    ->where('judge_status', 0)
                    ->whereHas('operation', function($q) {
                        $q->where('value', 'referee_team_confirmation');
                    })
                    ->count();

                // Если поездок нет (0), ищем матчи где судья принял и утверждён, но матч ожидает протокол
                if ($myTripsCount == 0) {
                    $myTripsCount = \App\Models\MatchEntity::whereHas('match_judges', function($q) {
                        $q->where('judge_id', auth()->id())
                          ->where('judge_response', 1)
                          ->where('final_status', 1);
                    })
                    ->whereHas('operation', function($q) {
                        $q->where('value', 'waiting_for_protocol');
                    })
                    ->count();
                }

                // 3. Мои протоколы - очень сложная логика
                // Сначала ищем протоколы судьи в нужных операциях
                $myProtocolsCount = \App\Models\Protocol::where('judge_id', auth()->id())
                    ->whereHas('operation', function($q) {
                        $q->whereIn('value', ['protocol_reprocessing']);
                    })
                    ->count();

                // Если протоколов нет, ищем матчи где нужно создать протокол
                if ($myProtocolsCount == 0) {
                    $myProtocolsCount = \App\Models\MatchEntity::whereHas('operation', function($q) {
                        $q->whereIn('value', ['waiting_for_protocol', 'protocol_reprocessing']);
                    })
                    ->whereHas('match_judges', function($q) {
                        $q->where('judge_id', auth()->id())
                          ->where('judge_response', 1)
                          ->where('final_status', 1);
                    })
                    ->where(function($query) {
                        $query->whereExists(function($subQuery) {
                            $subQuery->select(\DB::raw(1))
                                ->from('protocol_requirements')
                                ->join('match_judges', function($join) {
                                    $join->on('protocol_requirements.judge_type_id', '=', 'match_judges.type_id')
                                        ->where('match_judges.judge_id', auth()->id())
                                        ->where('match_judges.judge_response', 1)
                                        ->where('match_judges.final_status', 1);
                                })
                                ->whereColumn('match_judges.match_id', 'matches.id')
                                ->where(function($req) {
                                    $req->whereColumn('protocol_requirements.match_id', 'matches.id')
                                        ->orWhere(function($leagueReq) {
                                            $leagueReq->whereColumn('protocol_requirements.league_id', 'matches.league_id')
                                                ->whereNull('protocol_requirements.match_id');
                                        });
                                })
                                ->whereNull('protocol_requirements.deleted_at');
                        });
                    })
                    ->count();
                }
            @endphp

            <a href="{{ route('referee.my-invitations') }}"
               class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('referee.my-invitations') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <div class="flex items-center">
                    <i class="fas fa-envelope w-5"></i>
                    <span class="ml-3">Мои приглашения</span>
                </div>
                @if($myInvitationsCount > 0)
                    <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                        {{ $myInvitationsCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('referee.my-business-trips') }}"
               class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('referee.my-business-trips') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <div class="flex items-center">
                    <i class="fas fa-suitcase w-5"></i>
                    <span class="ml-3">Мои командировки</span>
                </div>
                @if($myTripsCount > 0)
                    <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                        {{ $myTripsCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('referee.my-protocols') }}"
               class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('referee.my-protocols') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <div class="flex items-center">
                    <i class="fas fa-file-alt w-5"></i>
                    <span class="ml-3">Мои протоколы</span>
                </div>
                @if($myProtocolsCount > 0)
                    <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                        {{ $myProtocolsCount }}
                    </span>
                @endif
            </a>

            <!-- AVR (Act of Works) -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    АВР
                </p>
            </div>

            <a href="{{ route('referee.my-act-of-works') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('referee.my-act-of-works') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <i class="fas fa-file-invoice w-5"></i>
                <span class="ml-3">Мои АВР</span>
            </a>
        </nav>
    </div>
</aside>
