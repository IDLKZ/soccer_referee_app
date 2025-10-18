<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Заголовок страницы -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                <i class="fas fa-plane-departure mr-3 text-purple-600 dark:text-purple-400"></i>
                Управление командировками
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Организация командировок судей на футбольные матчи
            </p>
        </div>

        <!-- Фильтры и поиск -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                <!-- Поиск -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-search mr-1"></i>
                        Поиск
                    </label>
                    <input type="text" wire:model.live.debounce.300ms="search"
                           placeholder="Команда, стадион..."
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-gray-100">
                </div>

                <!-- Фильтр по операции/статусу -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-tasks mr-1"></i>
                        Статус
                    </label>
                    <select wire:model.live="operationFilter"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-gray-100">
                        <option value="">Все статусы</option>
                        @foreach($operations as $operation)
                            <option value="{{ $operation->id }}">{{ $operation->title_ru }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Фильтр по лиге -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-trophy mr-1"></i>
                        Лига
                    </label>
                    <select wire:model.live="leagueFilter"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-gray-100">
                        <option value="">Все лиги</option>
                        @foreach($leagues as $league)
                            <option value="{{ $league->id }}">{{ $league->title_ru }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Фильтр по сезону -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-calendar mr-1"></i>
                        Сезон
                    </label>
                    <select wire:model.live="seasonFilter"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-gray-100">
                        <option value="">Все сезоны</option>
                        @foreach($seasons as $season)
                            <option value="{{ $season->id }}">{{ $season->title_ru }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <!-- Кнопка сброса фильтров -->
            <div class="mt-4 flex justify-end">
                <button wire:click="resetFilters"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    <i class="fas fa-redo mr-2"></i>
                    Сбросить фильтры
                </button>
            </div>
        </div>

        <!-- Сетка карточек матчей -->
        @if($matches->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                @foreach($matches as $match)
                    <div wire:click="viewTripDetail({{ $match->id }})"
                         class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-xl transition-all cursor-pointer overflow-hidden border border-gray-200 dark:border-gray-700 transform hover:scale-105">

                        <!-- Заголовок с цветовой индикацией -->
                        <div class="p-4 bg-gradient-to-r from-purple-500 to-indigo-500 text-white">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold uppercase tracking-wide">
                                    {{ $match->operation->title_ru }}
                                </span>
                                <span class="text-xs">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    {{ \Carbon\Carbon::parse($match->start_at)->format('d.m.Y') }}
                                </span>
                            </div>
                        </div>

                        <!-- Информация о матче -->
                        <div class="p-4">
                            <!-- Лига и сезон -->
                            <div class="mb-3 flex items-center justify-between text-xs text-gray-600 dark:text-gray-400">
                                <span class="flex items-center">
                                    <i class="fas fa-trophy mr-1 text-yellow-500"></i>
                                    {{ $match->league->title_ru ?? 'Н/Д' }}
                                </span>
                                <span>{{ $match->season->title_ru ?? 'Н/Д' }}</span>
                            </div>

                            <!-- Команды -->
                            <div class="mb-4">
                                <div class="flex items-center justify-between text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    <span class="flex-1 truncate">{{ $match->ownerClub->short_name_ru ?? $match->ownerClub->title_ru ?? 'Команда 1' }}</span>
                                    <span class="mx-3 text-gray-500 dark:text-gray-400">vs</span>
                                    <span class="flex-1 truncate text-right">{{ $match->guestClub->short_name_ru ?? $match->guestClub->title_ru ?? 'Команда 2' }}</span>
                                </div>
                            </div>

                            <!-- Стадион и время -->
                            <div class="mb-4 text-xs text-gray-600 dark:text-gray-400">
                                <div class="flex items-center mb-1">
                                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                                    <span class="truncate">{{ $match->stadium->title_ru ?? 'Стадион не указан' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-clock mr-2 text-blue-500"></i>
                                    <span>{{ $match->start_at ? \Carbon\Carbon::parse($match->start_at)->format('H:i') : 'Время не указано' }}</span>
                                </div>
                            </div>

                            <!-- Статистика командировок -->
                            <div class="grid grid-cols-2 gap-2 text-center text-xs">
                                <!-- Всего командировок -->
                                <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-2 border border-purple-200 dark:border-purple-800">
                                    <div class="font-bold text-purple-700 dark:text-purple-400">{{ $match->trips_count }}</div>
                                    <div class="text-purple-600 dark:text-purple-500 text-[10px]">Командировок</div>
                                </div>

                                <!-- Подтверждено -->
                                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-2 border border-green-200 dark:border-green-800">
                                    <div class="font-bold text-green-700 dark:text-green-400">{{ $match->confirmed_trips_count }}</div>
                                    <div class="text-green-600 dark:text-green-500 text-[10px]">Подтверждено</div>
                                </div>
                            </div>
                        </div>

                        <!-- Нижняя часть с кнопкой -->
                        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-suitcase mr-1"></i>
                                    Управление командировкой
                                </span>
                                <i class="fas fa-arrow-right text-purple-600 dark:text-purple-400"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Пагинация -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
                {{ $matches->links() }}
            </div>

        @else
            <!-- Пустое состояние -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
                <div class="text-gray-400 dark:text-gray-500 mb-4">
                    <i class="fas fa-plane-slash text-6xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Командировки не найдены
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Попробуйте изменить параметры фильтрации
                </p>
            </div>
        @endif

    </div>
</div>
