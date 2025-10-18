<div>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Заголовок страницы -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                    <i class="fas fa-clipboard-check mr-3 text-blue-500"></i>
                    Утверждение судейской бригады
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Утверждение состава судейских бригад для назначенных матчей
                </p>
            </div>

            <!-- Фильтры и поиск -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Поиск -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-search mr-2"></i>Поиск
                        </label>
                        <input type="text"
                               wire:model.live.debounce.300ms="search"
                               placeholder="Поиск по лиге, стадиону, клубу..."
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                    </div>

                    <!-- Фильтр по лиге -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-trophy mr-2"></i>Лига
                        </label>
                        <select wire:model.live="filterLeague"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                            <option value="">Все лиги</option>
                            @foreach($leagues as $league)
                                <option value="{{ $league->id }}">{{ $league->title_ru }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Фильтр по сезону -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-calendar mr-2"></i>Сезон
                        </label>
                        <select wire:model.live="filterSeason"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                            <option value="">Все сезоны</option>
                            @foreach($seasons as $season)
                                <option value="{{ $season->id }}">{{ $season->title_ru }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Карточки матчей -->
            @if($matches->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    @foreach($matches as $match)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 cursor-pointer transform hover:scale-105 transition-transform"
                             wire:click="viewMatch({{ $match->id }})">

                            <!-- Заголовок карточки -->
                            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-700 dark:to-indigo-800 p-4 text-white">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-semibold uppercase tracking-wider opacity-90">
                                        {{ $match->league->title_ru }}
                                    </span>
                                    <span class="text-xs opacity-90">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        {{ \Carbon\Carbon::parse($match->start_at)->format('d.m.Y') }}
                                    </span>
                                </div>
                                <div class="text-center">
                                    <p class="font-bold text-lg truncate">
                                        {{ $match->ownerClub ? ($match->ownerClub->short_name_ru ?? $match->ownerClub->title_ru) : 'Клуб хозяев' }}
                                    </p>
                                    <p class="text-2xl font-bold my-2">VS</p>
                                    <p class="font-bold text-lg truncate">
                                        {{ $match->guestClub ? ($match->guestClub->short_name_ru ?? $match->guestClub->title_ru) : 'Клуб гостей' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Информация о матче -->
                            <div class="p-4">
                                <div class="space-y-3">
                                    <!-- Стадион -->
                                    <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                        <i class="fas fa-map-marker-alt w-5 text-blue-500"></i>
                                        <span class="ml-2 truncate">{{ $match->stadium->title_ru ?? 'Н/Д' }}</span>
                                    </div>

                                    <!-- Время -->
                                    @if($match->start_at)
                                        <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                            <i class="fas fa-clock w-5 text-blue-500"></i>
                                            <span class="ml-2">{{ \Carbon\Carbon::parse($match->start_at)->format('H:i') }}</span>
                                        </div>
                                    @endif

                                    <!-- Сезон -->
                                    <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                        <i class="fas fa-calendar w-5 text-blue-500"></i>
                                        <span class="ml-2 truncate">{{ $match->season->title_ru }}</span>
                                    </div>

                                    <!-- Количество судей -->
                                    <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                        @php
                                            $waitingCount = $match->match_judges->where('final_status', 0)->count();
                                            $approvedCount = $match->match_judges->where('final_status', 1)->count();
                                            $rejectedCount = $match->match_judges->where('final_status', -1)->count();
                                        @endphp
                                        <div class="flex items-center justify-between text-xs">
                                            <div class="flex items-center">
                                                <span class="w-3 h-3 rounded-full bg-yellow-400 mr-1"></span>
                                                <span class="text-gray-600 dark:text-gray-400">Ожидают: {{ $waitingCount }}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <span class="w-3 h-3 rounded-full bg-green-400 mr-1"></span>
                                                <span class="text-gray-600 dark:text-gray-400">Утверждено: {{ $approvedCount }}</span>
                                            </div>
                                            @if($rejectedCount > 0)
                                                <div class="flex items-center">
                                                    <span class="w-3 h-3 rounded-full bg-red-400 mr-1"></span>
                                                    <span class="text-gray-600 dark:text-gray-400">Отказано: {{ $rejectedCount }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Кнопка просмотра -->
                            <div class="px-4 pb-4">
                                <button class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition-all">
                                    <i class="fas fa-eye mr-2"></i>
                                    Просмотр и утверждение
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Пагинация -->
                <div class="mt-6">
                    {{ $matches->links() }}
                </div>
            @else
                <!-- Пустое состояние -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
                    <div class="text-gray-400 dark:text-gray-500 mb-4">
                        <i class="fas fa-clipboard-list text-6xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Нет матчей для утверждения
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        В настоящее время нет матчей, требующих утверждения судейской бригады
                    </p>
                </div>
            @endif

        </div>
    </div>

    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .grid > div {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>
</div>
