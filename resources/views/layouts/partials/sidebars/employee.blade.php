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

            @can('manage-stadiums')
                <a href="{{ route('stadiums') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('stadiums*') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-building w-5"></i>
                    <span class="ml-3">Стадионы</span>
                </a>
            @endcan

            @can('manage-club-stadiums')
                <a href="{{ route('club-stadiums') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('club-stadiums*') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-link w-5"></i>
                    <span class="ml-3">Связи клубов и стадионов</span>
                </a>
            @endcan

            <!-- Match Management -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Матчи
                </p>
            </div>

            @can('manage-category-operations')
                <a href="{{ route('category-operations') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('category-operations*') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-layer-group w-5"></i>
                    <span class="ml-3">Категории операций</span>
                </a>
            @endcan

            @can('manage-operations')
                <a href="{{ route('operations') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('operations*') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-tasks w-5"></i>
                    <span class="ml-3">Операции</span>
                </a>
            @endcan

            @can('manage-protocol-requirements')
                <a href="{{ route('protocol-requirements') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('protocol-requirements*') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-file-alt w-5"></i>
                    <span class="ml-3">Требования протоколов</span>
                </a>
            @endcan

            @can('manage-matches')
                <a href="{{ route('match-entity-management') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('match-entity-management*') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-futbol w-5"></i>
                    <span class="ml-3">Управление матчами</span>
                </a>
            @endcan

            @can('assign-referees')
                <a href="{{ route('match-assignment-cards') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('match-assignment-cards*') || request()->routeIs('match-assignment-detail*') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-user-tie w-5"></i>
                    <span class="ml-3">Назначение судей на матч</span>
                </a>
            @endcan

            @can('manage-common-services')
                <a href="{{ route('common-services') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('common-services*') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-briefcase w-5"></i>
                    <span class="ml-3">Типы работ (АВР)</span>
                </a>
            @endcan

            <!-- Business Trips -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Командировки
                </p>
            </div>

            @can('manage-referees')
                <a href="{{ route('primary-business-trip-confirmation') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('primary-business-trip-confirmation') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-clipboard-check w-5"></i>
                    <span class="ml-3">Первичная проверка</span>
                </a>
            @endcan

            <!-- Protocols -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Протоколы
                </p>
            </div>

            <!-- Geography Management -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    География
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

            @can('manage-judge-cities')
                <a href="{{ route('judge-cities') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('judge-cities*') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-link w-5"></i>
                    <span class="ml-3">Города судей</span>
                </a>
            @endcan

            <!-- League Management -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Лиги и соревнования
                </p>
            </div>

            @can('manage-leagues')
                <a href="{{ route('leagues') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('leagues*') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-trophy w-5"></i>
                    <span class="ml-3">Лиги</span>
                </a>
            @endcan

            @can('manage-seasons')
                <a href="{{ route('seasons') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('seasons*') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-calendar w-5"></i>
                    <span class="ml-3">Сезоны</span>
                </a>
            @endcan

            <!-- Hotels and Logistics -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Отели и логистика
                </p>
            </div>

            @can('manage-hotels')
                <a href="{{ route('hotels') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('hotels*') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-hotel w-5"></i>
                    <span class="ml-3">Отели</span>
                </a>
            @endcan

            @can('manage-hotel-rooms')
                <a href="{{ route('hotel-rooms') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('hotel-rooms*') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-bed w-5"></i>
                    <span class="ml-3">Номера отелей</span>
                </a>
            @endcan

            @can('manage-facilities')
                <a href="{{ route('facilities') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('facilities*') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-wifi w-5"></i>
                    <span class="ml-3">Удобства номеров</span>
                </a>
            @endcan

            @can('manage-room-facilities')
                <a href="{{ route('room-facilities') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('room-facilities*') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-link w-5"></i>
                    <span class="ml-3">Связи удобств</span>
                </a>
            @endcan

            @can('manage-transport-types')
                <a href="{{ route('transport-types') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('transport-types*') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-bus w-5"></i>
                    <span class="ml-3">Тип транспорта</span>
                </a>
            @endcan
        </nav>
    </div>
</aside>
