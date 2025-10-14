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

            <!-- Trips -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Командировки
                </p>
            </div>

            <a href="{{ route('trips') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('trips*') && !request()->routeIs('trips.pending') && !request()->routeIs('trips.active') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-plane w-5"></i>
                <span class="ml-3">Все командировки</span>
            </a>

            <a href="{{ route('trips.pending') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('trips.pending*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-clock w-5"></i>
                <span class="ml-3">Планируемые</span>
                @if($pendingTrips = \App\Models\Trip::where('judge_status', 0)->count())
                <span class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    {{ $pendingTrips }}
                </span>
                @endif
            </a>

            <a href="{{ route('trips.active') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('trips.active*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-running w-5"></i>
                <span class="ml-3">Активные</span>
                @if($activeTrips = \App\Models\Trip::where('judge_status', 1)->where('first_status', 1)->count())
                <span class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $activeTrips }}
                </span>
                @endif
            </a>

            <!-- Hotels -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Размещение
                </p>
            </div>

            <a href="{{ route('hotels') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('hotels*') && !request()->routeIs('hotels.bookings') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-hotel w-5"></i>
                <span class="ml-3">Отели</span>
            </a>

            <a href="{{ route('hotels.bookings') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('hotels.bookings*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-bed w-5"></i>
                <span class="ml-3">Бронирования</span>
            </a>

            <!-- Transport -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Транспорт
                </p>
            </div>

            <a href="{{ route('transport') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('transport*') && !request()->routeIs('transport.tickets') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-bus w-5"></i>
                <span class="ml-3">Транспорт</span>
            </a>

            <a href="{{ route('transport.tickets') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('transport.tickets*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-ticket-alt w-5"></i>
                <span class="ml-3">Билеты</span>
            </a>

            <!-- Documents -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Документы
                </p>
            </div>

            <a href="{{ route('trip-documents') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('trip-documents*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-file-alt w-5"></i>
                <span class="ml-3">Документы командировок</span>
            </a>

            <!-- Schedule -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Планирование
                </p>
            </div>

            <a href="{{ route('matches') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('matches*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-calendar-alt w-5"></i>
                <span class="ml-3">Матчи</span>
            </a>

            <a href="{{ route('calendar') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('calendar*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-calendar w-5"></i>
                <span class="ml-3">Календарь</span>
            </a>

            <!-- Referees -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Справочники
                </p>
            </div>

            <a href="{{ route('referees') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('referees*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-user-tie w-5"></i>
                <span class="ml-3">Судьи</span>
            </a>

            <a href="{{ route('cities') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('cities*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-map-marker-alt w-5"></i>
                <span class="ml-3">Города</span>
            </a>
        </nav>
    </div>
</aside>
