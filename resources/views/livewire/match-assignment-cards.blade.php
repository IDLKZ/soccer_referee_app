<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Заголовок страницы -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                <i class="fas fa-user-tie mr-3 text-blue-600 dark:text-blue-400"></i>
                Назначение судей на матч
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Управление назначением судей на футбольные матчи
            </p>
        </div>

        <!-- Фильтры и поиск -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">

                <!-- Поиск -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-search mr-1"></i>
                        Поиск по командам
                    </label>
                    <input type="text" wire:model.live.debounce.300ms="search"
                           placeholder="Введите название команды..."
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                </div>

                <!-- Фильтр по лиге -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-trophy mr-1"></i>
                        Лига
                    </label>
                    <select wire:model.live="leagueFilter"
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
                        <i class="fas fa-calendar mr-1"></i>
                        Сезон
                    </label>
                    <select wire:model.live="seasonFilter"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                        <option value="">Все сезоны</option>
                        @foreach($seasons as $season)
                            <option value="{{ $season->id }}">{{ $season->title_ru }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Фильтр по статусу операции -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-tasks mr-1"></i>
                        Статус
                    </label>
                    <select wire:model.live="operationFilter"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                        <option value="">Все статусы</option>
                        @foreach($operations as $operation)
                            <option value="{{ $operation->id }}">{{ $operation->title_ru }}</option>
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
                    <div wire:click="viewMatchDetail({{ $match->id }})"
                         class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-xl transition-shadow cursor-pointer overflow-hidden border border-gray-200 dark:border-gray-700 transform hover:scale-105 transition-transform">

                        <!-- Заголовок карточки с цветовой индикацией статуса -->
                        <div class="p-4
                            @if($match->operation->value === 'match_created_waiting_referees')
                                bg-gradient-to-r from-yellow-500 to-orange-500
                            @elseif($match->operation->value === 'referee_reassignment')
                                bg-gradient-to-r from-red-500 to-pink-500
                            @else
                                bg-gradient-to-r from-green-500 to-teal-500
                            @endif
                            text-white">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold uppercase tracking-wide">
                                    {{ $match->operation->title_ru }}
                                </span>
                                <span class="text-xs">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    {{ \Carbon\Carbon::parse($match->match_date)->format('d.m.Y') }}
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
                                    <span class="flex-1 truncate">{{ $match->owner_club_name ?? 'Команда 1' }}</span>
                                    <span class="mx-3 text-gray-500 dark:text-gray-400">vs</span>
                                    <span class="flex-1 truncate text-right">{{ $match->guest_club_name ?? 'Команда 2' }}</span>
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
                                    <span>{{ $match->match_time ? \Carbon\Carbon::parse($match->match_time)->format('H:i') : 'Время не указано' }}</span>
                                </div>
                            </div>

                            <!-- Прогресс назначения судей -->
                            <div class="mb-3">
                                <div class="flex items-center justify-between text-xs text-gray-700 dark:text-gray-300 mb-1">
                                    <span>Прогресс назначения</span>
                                    <span class="font-semibold">{{ $match->approved_count }}/{{ $match->required_judges_count }}</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="h-2 rounded-full transition-all
                                        @if($match->assignment_progress == 100)
                                            bg-gradient-to-r from-green-500 to-emerald-500
                                        @elseif($match->assignment_progress >= 50)
                                            bg-gradient-to-r from-yellow-500 to-orange-500
                                        @else
                                            bg-gradient-to-r from-red-500 to-pink-500
                                        @endif"
                                        style="width: {{ $match->assignment_progress }}%">
                                    </div>
                                </div>
                            </div>

                            <!-- Статистика судей -->
                            <div class="grid grid-cols-4 gap-2 text-center text-xs">
                                <!-- Утверждено -->
                                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-2 border border-green-200 dark:border-green-800">
                                    <div class="font-bold text-green-700 dark:text-green-400">{{ $match->approved_count }}</div>
                                    <div class="text-green-600 dark:text-green-500 text-[10px]">Утверждено</div>
                                </div>

                                <!-- Ожидание ответа -->
                                <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-2 border border-yellow-200 dark:border-yellow-800">
                                    <div class="font-bold text-yellow-700 dark:text-yellow-400">{{ $match->waiting_response_count }}</div>
                                    <div class="text-yellow-600 dark:text-yellow-500 text-[10px]">Ожидание</div>
                                </div>

                                <!-- Ожидание директора -->
                                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-2 border border-blue-200 dark:border-blue-800">
                                    <div class="font-bold text-blue-700 dark:text-blue-400">{{ $match->waiting_director_count }}</div>
                                    <div class="text-blue-600 dark:text-blue-500 text-[10px]">Директор</div>
                                </div>

                                <!-- Отказано -->
                                <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-2 border border-red-200 dark:border-red-800">
                                    <div class="font-bold text-red-700 dark:text-red-400">{{ $match->rejected_count }}</div>
                                    <div class="text-red-600 dark:text-red-500 text-[10px]">Отказано</div>
                                </div>
                            </div>
                        </div>

                        <!-- Нижняя часть с кнопкой -->
                        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-eye mr-1"></i>
                                    Посмотреть детали
                                </span>
                                <i class="fas fa-arrow-right text-blue-600 dark:text-blue-400"></i>
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
                    <i class="fas fa-inbox text-6xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Матчи не найдены
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Попробуйте изменить параметры фильтрации или создать новый матч
                </p>
            </div>
        @endif

    </div>
</div>
