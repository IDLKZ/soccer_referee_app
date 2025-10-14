<aside class="w-64 bg-white border-r border-gray-200 min-h-screen">
    <div class="px-4 py-6">
        <!-- Sidebar Navigation -->
        <nav class="space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-home w-5"></i>
                <span class="ml-3">Главная</span>
            </a>

            <!-- My Matches -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Мои матчи
                </p>
            </div>

            <a href="{{ route('referee.matches') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('referee.matches*') && !request()->routeIs('referee.matches.upcoming') && !request()->routeIs('referee.matches.history') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-calendar-alt w-5"></i>
                <span class="ml-3">Все матчи</span>
            </a>

            <a href="{{ route('referee.matches.upcoming') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('referee.matches.upcoming*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-clock w-5"></i>
                <span class="ml-3">Предстоящие</span>
                @if($upcomingMatches = \App\Models\MatchJudge::where('judge_id', Auth::id())->whereHas('match', function($q) {
                    $q->where('start_at', '>', now());
                })->count())
                <span class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $upcomingMatches }}
                </span>
                @endif
            </a>

            <a href="{{ route('referee.matches.history') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('referee.matches.history*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-history w-5"></i>
                <span class="ml-3">История</span>
            </a>

            <!-- Assignments -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Назначения
                </p>
            </div>

            <a href="{{ route('referee.assignments') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('referee.assignments*') && !request()->routeIs('referee.assignments.pending') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-tasks w-5"></i>
                <span class="ml-3">Мои назначения</span>
            </a>

            <a href="{{ route('referee.assignments.pending') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('referee.assignments.pending*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-bell w-5"></i>
                <span class="ml-3">Требуют ответа</span>
                @if($pendingAssignments = \App\Models\MatchJudge::where('judge_id', Auth::id())->where('judge_response', 0)->count())
                <span class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    {{ $pendingAssignments }}
                </span>
                @endif
            </a>

            <!-- Protocols -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Протоколы
                </p>
            </div>

            <a href="{{ route('referee.protocols') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('referee.protocols*') && !request()->routeIs('referee.protocols.pending') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-file-alt w-5"></i>
                <span class="ml-3">Мои протоколы</span>
            </a>

            <a href="{{ route('referee.protocols.pending') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('referee.protocols.pending*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-exclamation-triangle w-5"></i>
                <span class="ml-3">Требуют заполнения</span>
                @if($pendingProtocols = \App\Models\Protocol::where('judge_id', Auth::id())->where('is_ready', false)->count())
                <span class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    {{ $pendingProtocols }}
                </span>
                @endif
            </a>

            <!-- Trips -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Командировки
                </p>
            </div>

            <a href="{{ route('referee.trips') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('referee.trips*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-plane w-5"></i>
                <span class="ml-3">Мои командировки</span>
            </a>

            <!-- Finance -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Финансы
                </p>
            </div>

            <a href="{{ route('referee.payments') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('referee.payments*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-money-bill-wave w-5"></i>
                <span class="ml-3">Мои выплаты</span>
            </a>

            <a href="{{ route('referee.acts') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('referee.acts*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-file-invoice w-5"></i>
                <span class="ml-3">Акты работ</span>
            </a>

            <!-- My Profile -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Профиль
                </p>
            </div>

            <a href="{{ route('profile') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('profile*') && !request()->routeIs('profile.statistics') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-user w-5"></i>
                <span class="ml-3">Мой профиль</span>
            </a>

            <a href="{{ route('profile.statistics') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('profile.statistics*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-chart-bar w-5"></i>
                <span class="ml-3">Моя статистика</span>
            </a>

            <!-- Calendar -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Календарь
                </p>
            </div>

            <a href="{{ route('calendar') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('calendar*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-calendar w-5"></i>
                <span class="ml-3">Мой календарь</span>
            </a>
        </nav>
    </div>
</aside>
