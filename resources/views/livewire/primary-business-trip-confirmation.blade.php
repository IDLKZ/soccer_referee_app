<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header with Animation -->
        <div class="mb-8 animate-fade-in">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400 flex items-center">
                        <i class="fas fa-clipboard-check mr-4"></i>
                        Первичная проверка командировок
                    </h1>
                    <p class="mt-2 text-base text-gray-600 dark:text-gray-400">
                        Проверьте и подтвердите командировки судей перед финальным согласованием
                    </p>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-l-4 border-blue-500 transform hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Всего к проверке</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $totalTrips }}</p>
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-900/30 p-4 rounded-full">
                            <i class="fas fa-list-check text-2xl text-blue-600 dark:text-blue-400"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-l-4 border-green-500 transform hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Отфильтровано</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $trips->count() }}</p>
                        </div>
                        <div class="bg-green-100 dark:bg-green-900/30 p-4 rounded-full">
                            <i class="fas fa-filter text-2xl text-green-600 dark:text-green-400"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-l-4 border-purple-500 transform hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">С отелями</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $trips->where('trip_hotels', '!=', null)->count() }}</p>
                        </div>
                        <div class="bg-purple-100 dark:bg-purple-900/30 p-4 rounded-full">
                            <i class="fas fa-hotel text-2xl text-purple-600 dark:text-purple-400"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-l-4 border-orange-500 transform hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">С документами</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $trips->where('trip_documents', '!=', null)->count() }}</p>
                        </div>
                        <div class="bg-orange-100 dark:bg-orange-900/30 p-4 rounded-full">
                            <i class="fas fa-file-alt text-2xl text-orange-600 dark:text-orange-400"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 text-green-800 dark:text-green-400 px-6 py-4 rounded-lg flex items-center shadow-lg animate-fade-in">
                <i class="fas fa-check-circle text-3xl mr-4"></i>
                <span class="font-medium text-lg">{{ session('message') }}</span>
            </div>
        @endif

        <!-- Filters Panel -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-6 mb-8 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                    <i class="fas fa-filter mr-2 text-blue-600 dark:text-blue-400"></i>
                    Фильтры
                </h3>
                <button wire:click="resetFilters"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors text-sm">
                    <i class="fas fa-redo mr-2"></i>
                    Сбросить
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-search mr-1"></i>
                        Поиск
                    </label>
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           placeholder="Судья, команда..."
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 transition-all">
                </div>

                <!-- League Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-trophy mr-1"></i>
                        Лига
                    </label>
                    <select wire:model.live="filterLeague"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 transition-all">
                        <option value="">Все лиги</option>
                        @foreach($leagues as $league)
                            <option value="{{ $league->id }}">{{ $league->title_ru }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Дата от
                    </label>
                    <input type="date"
                           wire:model.live="filterDateFrom"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 transition-all">
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Дата до
                    </label>
                    <input type="date"
                           wire:model.live="filterDateTo"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 transition-all">
                </div>
            </div>

            <!-- Sort Options -->
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-sort mr-1"></i>
                    Сортировка
                </label>
                <div class="flex flex-wrap gap-2">
                    <button wire:click="sortByField('created_at')"
                            class="inline-flex items-center px-4 py-2 rounded-lg text-sm transition-all {{ $sortBy === 'created_at' ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        Дата создания
                        @if($sortBy === 'created_at')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-2"></i>
                        @endif
                    </button>
                    <button wire:click="sortByField('match_date')"
                            class="inline-flex items-center px-4 py-2 rounded-lg text-sm transition-all {{ $sortBy === 'match_date' ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        Дата матча
                        @if($sortBy === 'match_date')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-2"></i>
                        @endif
                    </button>
                    <button wire:click="sortByField('referee')"
                            class="inline-flex items-center px-4 py-2 rounded-lg text-sm transition-all {{ $sortBy === 'referee' ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        Судья
                        @if($sortBy === 'referee')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-2"></i>
                        @endif
                    </button>
                </div>
            </div>
        </div>

        <!-- Trips Grid -->
        @if($trips->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($trips as $index => $trip)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-200 dark:border-gray-700 transform hover:-translate-y-2 animate-fade-in"
                         style="animation-delay: {{ $index * 0.1 }}s">
                        <!-- Card Header with Gradient -->
                        <div class="bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 p-5 text-white relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-16 -mt-16"></div>
                            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-10 rounded-full -ml-12 -mb-12"></div>

                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs font-bold uppercase tracking-wider bg-white/30 backdrop-blur-sm px-3 py-1 rounded-full">
                                        {{ $trip->match->league->title_ru ?? 'Лига' }}
                                    </span>
                                    <span class="text-xs bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full font-semibold">
                                        #{{ $trip->id }}
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold leading-tight">
                                    {{ $trip->match->ownerClub->short_name_ru ?? $trip->match->ownerClub->title_ru ?? 'Команда 1' }}
                                    <span class="mx-2 text-white/70">vs</span>
                                    {{ $trip->match->guestClub->short_name_ru ?? $trip->match->guestClub->title_ru ?? 'Команда 2' }}
                                </h3>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 space-y-4">
                            <!-- Referee Info with Avatar -->
                            <div class="flex items-center bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700/50 dark:to-gray-700/30 rounded-lg p-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center mr-3 shadow-lg">
                                    <i class="fas fa-user text-white text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                        {{ $trip->user->surname_ru ?? '' }} {{ $trip->user->name_ru ?? '' }}
                                    </div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400 flex items-center mt-1">
                                        <i class="fas fa-id-badge mr-1"></i>
                                        {{ $trip->user->role->title_ru ?? 'Судья' }}
                                    </div>
                                </div>
                            </div>

                            <!-- Match Details -->
                            <div class="space-y-2">
                                <div class="flex items-center text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-2">
                                    <i class="fas fa-calendar-alt w-5 mr-2 text-blue-500"></i>
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($trip->match->start_at)->format('d.m.Y H:i') }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-2">
                                    <i class="fas fa-map-marker-alt w-5 mr-2 text-red-500"></i>
                                    <span class="truncate">{{ $trip->match->stadium->title_ru ?? 'Стадион' }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-2">
                                    <i class="fas fa-city w-5 mr-2 text-green-500"></i>
                                    <span>{{ $trip->match->stadium->city->title_ru ?? 'Город' }}</span>
                                </div>
                            </div>

                            <!-- Statistics Badges -->
                            <div class="flex items-center justify-around pt-3 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex flex-col items-center">
                                    <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg mb-1">
                                        <i class="fas fa-hotel text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $trip->trip_hotels->count() }}</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="bg-green-100 dark:bg-green-900/30 p-2 rounded-lg mb-1">
                                        <i class="fas fa-route text-green-600 dark:text-green-400"></i>
                                    </div>
                                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $trip->trip_migrations->count() }}</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="bg-purple-100 dark:bg-purple-900/30 p-2 rounded-lg mb-1">
                                        <i class="fas fa-file-alt text-purple-600 dark:text-purple-400"></i>
                                    </div>
                                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $trip->trip_documents->count() }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="px-5 pb-5">
                            <button wire:click="openDetailModal({{ $trip->id }})"
                                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg transition-all shadow-md hover:shadow-xl transform hover:scale-105 font-semibold">
                                <i class="fas fa-eye mr-2"></i>
                                Проверить командировку
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-16 text-center border border-gray-200 dark:border-gray-700">
                <div class="text-gray-300 dark:text-gray-600 mb-6">
                    <i class="fas fa-clipboard-check text-8xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-700 dark:text-gray-300 mb-3">
                    Нет командировок для проверки
                </h3>
                <p class="text-base text-gray-500 dark:text-gray-400 mb-6">
                    По заданным фильтрам командировки не найдены
                </p>
                @if($search || $filterLeague || $filterDateFrom || $filterDateTo)
                    <button wire:click="resetFilters"
                            class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors shadow-md">
                        <i class="fas fa-redo mr-2"></i>
                        Сбросить фильтры
                    </button>
                @endif
            </div>
        @endif

    </div>

    <!-- Detail Modal (keeping the same beautiful modal from before) -->
    @if($showDetailModal && $selectedTrip)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 animate-fade-in"
             wire:click.self="closeDetailModal">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-6xl w-full max-h-[90vh] overflow-hidden transform transition-all animate-scale-in"
                 @click.stop>

                <!-- Modal Header with Gradient -->
                <div class="px-8 py-6 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white flex items-center justify-between relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-32 -mt-32"></div>
                    <div class="relative z-10">
                        <h3 class="text-2xl font-bold flex items-center">
                            <i class="fas fa-clipboard-check mr-3"></i>
                            Проверка командировки
                        </h3>
                        <p class="text-sm text-blue-100 mt-2 flex items-center">
                            <i class="fas fa-user mr-2"></i>
                            {{ $selectedTrip->user->surname_ru ?? '' }} {{ $selectedTrip->user->name_ru ?? '' }}
                        </p>
                    </div>
                    <button wire:click="closeDetailModal"
                            class="relative z-10 text-white hover:text-gray-200 transition-colors bg-white/20 hover:bg-white/30 rounded-full p-3">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-8 overflow-y-auto bg-gray-50 dark:bg-gray-900" style="max-height: calc(90vh - 250px);">

                    <!-- Match Information -->
                    <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center border-b border-gray-200 dark:border-gray-700 pb-3">
                            <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg mr-3">
                                <i class="fas fa-futbol text-blue-600 dark:text-blue-400"></i>
                            </div>
                            Информация о матче
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Команды</label>
                                <p class="font-bold text-gray-900 dark:text-gray-100 mt-1">
                                    {{ $selectedTrip->match->ownerClub->short_name_ru ?? $selectedTrip->match->ownerClub->title_ru ?? '' }}
                                    <span class="text-blue-500 mx-2">vs</span>
                                    {{ $selectedTrip->match->guestClub->short_name_ru ?? $selectedTrip->match->guestClub->title_ru ?? '' }}
                                </p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Дата и время</label>
                                <p class="font-bold text-gray-900 dark:text-gray-100 mt-1">
                                    {{ \Carbon\Carbon::parse($selectedTrip->match->start_at)->format('d.m.Y H:i') }}
                                </p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Стадион</label>
                                <p class="font-bold text-gray-900 dark:text-gray-100 mt-1">
                                    {{ $selectedTrip->match->stadium->title_ru ?? 'Не указан' }}
                                </p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Город</label>
                                <p class="font-bold text-gray-900 dark:text-gray-100 mt-1">
                                    {{ $selectedTrip->match->stadium->city->title_ru ?? 'Не указан' }}
                                </p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Лига</label>
                                <p class="font-bold text-gray-900 dark:text-gray-100 mt-1">
                                    {{ $selectedTrip->match->league->title_ru ?? 'Не указана' }}
                                </p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Сезон</label>
                                <p class="font-bold text-gray-900 dark:text-gray-100 mt-1">
                                    {{ $selectedTrip->match->season->title_ru ?? 'Не указан' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Referee Information -->
                    <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center border-b border-gray-200 dark:border-gray-700 pb-3">
                            <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg mr-3">
                                <i class="fas fa-user text-blue-600 dark:text-blue-400"></i>
                            </div>
                            Информация о судье
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">ФИО</label>
                                <p class="font-bold text-gray-900 dark:text-gray-100 mt-1">
                                    {{ $selectedTrip->user->surname_ru ?? '' }} {{ $selectedTrip->user->name_ru ?? '' }} {{ $selectedTrip->user->patronymic_ru ?? '' }}
                                </p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Роль</label>
                                <p class="font-bold text-gray-900 dark:text-gray-100 mt-1">
                                    {{ $selectedTrip->user->role->title_ru ?? 'Не указана' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Hotels -->
                    <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center border-b border-gray-200 dark:border-gray-700 pb-3">
                            <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg mr-3">
                                <i class="fas fa-hotel text-blue-600 dark:text-blue-400"></i>
                            </div>
                            Отели ({{ $selectedTrip->trip_hotels->count() }})
                        </h4>
                        @if($selectedTrip->trip_hotels->count() > 0)
                            <div class="space-y-3">
                                @foreach($selectedTrip->trip_hotels as $tripHotel)
                                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700/50 dark:to-gray-700/30 rounded-lg p-4 border-l-4 border-blue-500">
                                        <div class="font-bold text-gray-900 dark:text-gray-100 mb-2 flex items-center">
                                            <i class="fas fa-hotel mr-2 text-blue-500"></i>
                                            {{ $tripHotel->hotel_room->hotel->title_ru ?? 'Отель' }}
                                        </div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                            <i class="fas fa-door-open mr-2"></i>
                                            Номер: {{ $tripHotel->hotel_room->number ?? 'Н/Д' }}
                                            ({{ $tripHotel->hotel_room->type_ru ?? 'Тип не указан' }})
                                        </div>
                                        <div class="flex gap-4 text-xs text-gray-600 dark:text-gray-400">
                                            <div class="bg-white dark:bg-gray-800 px-3 py-1 rounded-full">
                                                <i class="fas fa-calendar-check mr-1 text-green-500"></i>
                                                Заезд: {{ \Carbon\Carbon::parse($tripHotel->from_date)->format('d.m.Y H:i') }}
                                            </div>
                                            <div class="bg-white dark:bg-gray-800 px-3 py-1 rounded-full">
                                                <i class="fas fa-calendar-times mr-1 text-red-500"></i>
                                                Выезд: {{ \Carbon\Carbon::parse($tripHotel->to_date)->format('d.m.Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">Отели не указаны</p>
                        @endif
                    </div>

                    <!-- Migrations (Routes) -->
                    <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center border-b border-gray-200 dark:border-gray-700 pb-3">
                            <div class="bg-green-100 dark:bg-green-900/30 p-2 rounded-lg mr-3">
                                <i class="fas fa-route text-green-600 dark:text-green-400"></i>
                            </div>
                            Маршруты ({{ $selectedTrip->trip_migrations->count() }})
                        </h4>
                        @if($selectedTrip->trip_migrations->count() > 0)
                            <div class="space-y-3">
                                @foreach($selectedTrip->trip_migrations as $tripMigration)
                                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-700/50 dark:to-gray-700/30 rounded-lg p-4 border-l-4 border-green-500">
                                        <div class="font-bold text-gray-900 dark:text-gray-100 mb-2 flex items-center">
                                            <i class="fas fa-bus mr-2 text-green-500"></i>
                                            {{ $tripMigration->transport_type->title_ru ?? 'Транспорт' }}
                                        </div>
                                        <div class="mb-2 flex items-center gap-2 text-sm">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 font-semibold">
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                {{ $tripMigration->departure_city->title_ru ?? 'Н/Д' }}
                                            </span>
                                            <i class="fas fa-arrow-right text-gray-400"></i>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 font-semibold">
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                {{ $tripMigration->arrival_city->title_ru ?? 'Н/Д' }}
                                            </span>
                                        </div>
                                        <div class="flex gap-4 text-xs text-gray-600 dark:text-gray-400">
                                            <div class="bg-white dark:bg-gray-800 px-3 py-1 rounded-full">
                                                <i class="fas fa-calendar-alt mr-1 text-blue-500"></i>
                                                Отправление: {{ \Carbon\Carbon::parse($tripMigration->from_date)->format('d.m.Y H:i') }}
                                            </div>
                                            <div class="bg-white dark:bg-gray-800 px-3 py-1 rounded-full">
                                                <i class="fas fa-calendar-check mr-1 text-green-500"></i>
                                                Прибытие: {{ \Carbon\Carbon::parse($tripMigration->to_date)->format('d.m.Y H:i') }}
                                            </div>
                                        </div>
                                        @if($tripMigration->info)
                                            <div class="mt-2 text-xs text-gray-600 dark:text-gray-400 italic bg-white dark:bg-gray-800 px-3 py-2 rounded-lg">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                {{ $tripMigration->info }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">Маршруты не указаны</p>
                        @endif
                    </div>

                    <!-- Documents -->
                    <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center border-b border-gray-200 dark:border-gray-700 pb-3">
                            <div class="bg-purple-100 dark:bg-purple-900/30 p-2 rounded-lg mr-3">
                                <i class="fas fa-file-alt text-purple-600 dark:text-purple-400"></i>
                            </div>
                            Документы ({{ $selectedTrip->trip_documents->count() }})
                        </h4>
                        @if($selectedTrip->trip_documents->count() > 0)
                            <div class="space-y-3">
                                @foreach($selectedTrip->trip_documents as $tripDocument)
                                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-700/50 dark:to-gray-700/30 rounded-lg p-4 border-l-4 border-purple-500">
                                        <div class="font-bold text-gray-900 dark:text-gray-100 mb-2 flex items-center">
                                            <i class="fas fa-file-alt mr-2 text-purple-500"></i>
                                            {{ $tripDocument->title }}
                                        </div>
                                        <div class="grid grid-cols-3 gap-4 text-sm mb-2">
                                            <div class="bg-white dark:bg-gray-800 px-3 py-2 rounded-lg">
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Цена</div>
                                                <div class="font-bold text-blue-600 dark:text-blue-400">{{ number_format($tripDocument->price, 2) }} ₸</div>
                                            </div>
                                            <div class="bg-white dark:bg-gray-800 px-3 py-2 rounded-lg">
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Кол-во</div>
                                                <div class="font-bold text-green-600 dark:text-green-400">{{ $tripDocument->qty }}</div>
                                            </div>
                                            <div class="bg-white dark:bg-gray-800 px-3 py-2 rounded-lg">
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Итого</div>
                                                <div class="font-bold text-purple-600 dark:text-purple-400">{{ number_format($tripDocument->total_price, 2) }} ₸</div>
                                            </div>
                                        </div>
                                        @if($tripDocument->file_url)
                                            <div class="text-xs">
                                                <a href="{{ asset('storage/' . $tripDocument->file_url) }}"
                                                   target="_blank"
                                                   class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:underline bg-white dark:bg-gray-800 px-3 py-2 rounded-lg">
                                                    <i class="fas fa-paperclip mr-1"></i>
                                                    Просмотреть файл
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">Документы не добавлены</p>
                        @endif
                    </div>

                    <!-- Comment -->
                    <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <label class="block text-lg font-bold text-gray-900 dark:text-gray-100 mb-3 flex items-center">
                            <div class="bg-orange-100 dark:bg-orange-900/30 p-2 rounded-lg mr-3">
                                <i class="fas fa-comment text-orange-600 dark:text-orange-400"></i>
                            </div>
                            Комментарий к проверке
                        </label>
                        <textarea wire:model="first_comment"
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 transition-all"
                                  placeholder="Добавьте комментарий (обязательно при отклонении)"></textarea>
                        @error('first_comment')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="px-8 py-5 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 border-t border-gray-200 dark:border-gray-600 flex justify-end gap-3">
                    <button wire:click="closeDetailModal"
                            class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-all shadow-md hover:shadow-lg transform hover:scale-105">
                        <i class="fas fa-times mr-2"></i>
                        Отмена
                    </button>
                    <button wire:click="rejectTrip"
                            wire:confirm="Вы уверены, что хотите отклонить эту командировку?"
                            class="inline-flex items-center px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all shadow-md hover:shadow-lg transform hover:scale-105">
                        <i class="fas fa-times-circle mr-2"></i>
                        Отклонить
                    </button>
                    <button wire:click="acceptTrip"
                            wire:confirm="Вы уверены, что хотите принять эту командировку?"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white rounded-lg transition-all shadow-md hover:shadow-lg transform hover:scale-105">
                        <i class="fas fa-check-circle mr-2"></i>
                        Принять
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>

@push('styles')
<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes scale-in {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.5s ease-out forwards;
    }

    .animate-scale-in {
        animation: scale-in 0.3s ease-out forwards;
    }
</style>
@endpush
