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

            <!-- System Management -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Система
                </p>
            </div>

            <a href="{{ route('users') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('users') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <i class="fas fa-users w-5"></i>
                <span class="ml-3">Пользователи</span>
            </a>

            <a href="{{ route('admin.roles.index') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.roles.*') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <i class="fas fa-user-shield w-5"></i>
                <span class="ml-3">Роли и права</span>
            </a>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-cog w-5"></i>
                <span class="ml-3">Настройки</span>
            </a>

            <!-- Club Management -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Клубы
                </p>
            </div>

            @can('manage-clubs')
                <a href="{{ route('clubs') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('clubs') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-shield-alt w-5"></i>
                    <span class="ml-3">Клубы</span>
                </a>
            @endcan

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-building w-5"></i>
                <span class="ml-3">Стадионы</span>
            </a>

            <!-- Match Management -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Матчи
                </p>
            </div>

            <a href="{{ route('matches') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('matches') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <i class="fas fa-calendar-alt w-5"></i>
                <span class="ml-3">Матчи</span>
            </a>

            @can('manage-seasons')
                <a href="{{ route('seasons') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('seasons*') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-calendar w-5"></i>
                    <span class="ml-3">Сезоны</span>
                </a>
            @endcan

            @can('manage-leagues')
                <a href="{{ route('leagues') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('leagues*') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-trophy w-5"></i>
                    <span class="ml-3">Лиги</span>
                </a>
            @endcan

            @can('manage-clubs')
                <a href="{{ route('clubs') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('clubs') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-shield-alt w-5"></i>
                    <span class="ml-3">Клубы</span>
                </a>
            @endcan

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-building w-5"></i>
                <span class="ml-3">Стадионы</span>
            </a>

            <!-- Referees -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Судьи
                </p>
            </div>

            <a href="{{ route('referees') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('referees') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <i class="fas fa-user-tie w-5"></i>
                <span class="ml-3">Судьи</span>
            </a>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-list w-5"></i>
                <span class="ml-3">Типы судей</span>
            </a>

            <!-- Finance -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Финансы
                </p>
            </div>

            <a href="{{ route('finance') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('finance') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <i class="fas fa-money-check-alt w-5"></i>
                <span class="ml-3">Финансы</span>
            </a>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-concierge-bell w-5"></i>
                <span class="ml-3">Услуги</span>
            </a>

            <!-- Logistics -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Логистика
                </p>
            </div>

            @can('manage-countries')
                <a href="{{ route('countries') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('countries*') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-globe w-5"></i>
                    <span class="ml-3">Страны</span>
                </a>
            @endcan

            @can('manage-cities')
                <a href="{{ route('cities') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('cities*') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-city w-5"></i>
                    <span class="ml-3">Города</span>
                </a>
            @endcan

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-plane w-5"></i>
                <span class="ml-3">Командировки</span>
            </a>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-hotel w-5"></i>
                <span class="ml-3">Отели</span>
            </a>

            <!-- Reports -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Отчеты
                </p>
            </div>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-chart-bar w-5"></i>
                <span class="ml-3">Отчеты</span>
            </a>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-history w-5"></i>
                <span class="ml-3">Логи</span>
            </a>
        </nav>
    </div>
</aside>
