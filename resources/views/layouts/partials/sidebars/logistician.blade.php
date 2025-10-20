<aside class="w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 min-h-screen">
    <div class="px-4 py-6">
        <!-- Sidebar Navigation -->
        <nav class="space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-home w-5"></i>
                <span class="ml-3">Главная</span>
            </a>

            <!-- Business Processes -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Бизнес процессы
                </p>
            </div>

            @can('manage-logistics')
                @php
                    // Сначала пытаемся найти матчи с нужными операциями
                    $matchCount = \App\Models\MatchEntity::whereHas('operation', function($q) {
                        $q->whereIn('value', [
                            'select_transport_departure',
                            'business_trip_plan_preparation',
                            'business_trip_registration',
                            'business_trip_plan_reprocessing'
                        ]);
                    })->count();

                    // Если матчей нет (count = 0), ищем поездки с теми же операциями
                    if ($matchCount == 0) {
                        $tripCount = \App\Models\Trip::whereHas('operation', function($q) {
                            $q->whereIn('value', [
                                'select_transport_departure',
                                'business_trip_plan_preparation',
                                'business_trip_plan_reprocessing'
                            ]);
                        })->count();
                        $businessTripCount = $tripCount;
                    } else {
                        $businessTripCount = $matchCount;
                    }
                @endphp
                <a href="{{ route('business-trip-cards') }}"
                   class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('business-trip*') ? 'bg-purple-50 dark:bg-purple-900 text-purple-700 dark:text-purple-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <div class="flex items-center">
                        <i class="fas fa-route w-5"></i>
                        <span class="ml-3">Управление командировками</span>
                    </div>
                    @if($businessTripCount > 0)
                        <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                            {{ $businessTripCount }}
                        </span>
                    @endif
                </a>
            @endcan

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
