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

            <!-- Analytics -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Аналитика
                </p>
            </div>

            <a href="{{ route('analytics.overview') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('analytics.overview*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-chart-line w-5"></i>
                <span class="ml-3">Обзор</span>
            </a>

            <a href="{{ route('analytics.referees') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('analytics.referees*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-user-chart w-5"></i>
                <span class="ml-3">Анализ судей</span>
            </a>

            <!-- Matches -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Матчи
                </p>
            </div>

            <a href="{{ route('matches') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('matches*') && !request()->routeIs('matches.protocols*') && !request()->routeIs('matches.approval*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-calendar-alt w-5"></i>
                <span class="ml-3">Матчи</span>
            </a>

            <a href="{{ route('matches.approval') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('matches.approval*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-check-circle w-5"></i>
                <span class="ml-3">Согласование</span>
                @if($pendingMatches = \App\Models\Match::where('status', 'pending')->count())
                <span class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    {{ $pendingMatches }}
                </span>
                @endif
            </a>

            <a href="{{ route('matches.protocols') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('matches.protocols*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-file-alt w-5"></i>
                <span class="ml-3">Протоколы</span>
            </a>

            <!-- Referees -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Судьи
                </p>
            </div>

            <a href="{{ route('referees') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('referees*') && !request()->routeIs('referees.performance*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-user-tie w-5"></i>
                <span class="ml-3">Судьи</span>
            </a>

            <a href="{{ route('referees.performance') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('referees.performance*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-star w-5"></i>
                <span class="ml-3">Производительность</span>
            </a>

            <!-- Finance -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Финансы
                </p>
            </div>

            <a href="{{ route('finance') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('finance*') && !request()->routeIs('finance.approval*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-money-check-alt w-5"></i>
                <span class="ml-3">Финансы</span>
            </a>

            <a href="{{ route('finance.approval') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('finance.approval*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-check-double w-5"></i>
                <span class="ml-3">Утверждение платежей</span>
                @if($pendingPayments = \App\Models\ActOfPayment::where('first_status', 0)->count())
                <span class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    {{ $pendingPayments }}
                </span>
                @endif
            </a>

            <!-- Reports -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Отчеты
                </p>
            </div>

            <a href="{{ route('reports.general') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('reports.general*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-chart-bar w-5"></i>
                <span class="ml-3">Общие отчеты</span>
            </a>

            <a href="{{ route('reports.custom') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('reports.custom*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-file-export w-5"></i>
                <span class="ml-3">Настраиваемые отчеты</span>
            </a>

            <!-- Settings -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Управление
                </p>
            </div>

            <a href="{{ route('users') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('users*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-users w-5"></i>
                <span class="ml-3">Пользователи</span>
            </a>
        </nav>
    </div>
</aside>
