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

            <!-- System Management -->
            @can('manage-users')
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Система
                </p>
            </div>

            <a href="{{ route('users') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('users*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-users w-5"></i>
                <span class="ml-3">Пользователи</span>
            </a>
            @endcan

            @can('manage-roles')
            <a href="{{ route('admin.roles') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.roles*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-user-shield w-5"></i>
                <span class="ml-3">Роли и права</span>
            </a>
            @endcan

            @can('manage-system-settings')
            <a href="{{ route('admin.settings') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.settings*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-cog w-5"></i>
                <span class="ml-3">Настройки</span>
            </a>
            @endcan

            <!-- Match Management -->
            @can('manage-matches')
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Матчи
                </p>
            </div>

            <a href="{{ route('matches') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('matches*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-calendar-alt w-5"></i>
                <span class="ml-3">Матчи</span>
            </a>
            @endcan

            @can('manage-leagues')
            <a href="{{ route('admin.leagues') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.leagues*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-trophy w-5"></i>
                <span class="ml-3">Лиги и сезоны</span>
            </a>
            @endcan

            @can('manage-clubs')
            <a href="{{ route('admin.clubs') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.clubs*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-shield-alt w-5"></i>
                <span class="ml-3">Клубы</span>
            </a>
            @endcan

            @can('manage-stadiums')
            <a href="{{ route('admin.stadiums') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.stadiums*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-building w-5"></i>
                <span class="ml-3">Стадионы</span>
            </a>
            @endcan

            <!-- Referees -->
            @can('manage-referees')
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Судьи
                </p>
            </div>

            <a href="{{ route('referees') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('referees*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-user-tie w-5"></i>
                <span class="ml-3">Судьи</span>
            </a>
            @endcan

            @can('manage-judge-types')
            <a href="{{ route('admin.judge-types') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.judge-types*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-list w-5"></i>
                <span class="ml-3">Типы судей</span>
            </a>
            @endcan

            <!-- Finance -->
            @can('manage-finance')
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Финансы
                </p>
            </div>

            <a href="{{ route('finance') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('finance*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-money-check-alt w-5"></i>
                <span class="ml-3">Финансы</span>
            </a>
            @endcan

            @can('manage-services')
            <a href="{{ route('admin.services') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.services*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-concierge-bell w-5"></i>
                <span class="ml-3">Услуги</span>
            </a>
            @endcan

            <!-- Logistics -->
            @can('manage-logistics')
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Логистика
                </p>
            </div>

            <a href="{{ route('admin.trips') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.trips*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-plane w-5"></i>
                <span class="ml-3">Командировки</span>
            </a>
            @endcan

            @can('manage-hotels')
            <a href="{{ route('admin.hotels') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.hotels*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-hotel w-5"></i>
                <span class="ml-3">Отели</span>
            </a>
            @endcan

            <!-- Reports -->
            @can('view-reports')
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Отчеты
                </p>
            </div>

            <a href="{{ route('admin.reports') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.reports*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-chart-bar w-5"></i>
                <span class="ml-3">Отчеты</span>
            </a>
            @endcan

            @can('view-logs')
            <a href="{{ route('admin.logs') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.logs*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-history w-5"></i>
                <span class="ml-3">Логи</span>
            </a>
            @endcan
        </nav>
    </div>
</aside>
