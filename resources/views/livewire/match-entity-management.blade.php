<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Управление матчами</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Управление матчами и их параметрами</p>
        </div>
        @if($canCreate)
        <button wire:click="$set('showCreateModal', true)"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-150">
            <i class="fas fa-plus mr-2"></i>
            Создать матч
        </button>
        @endif
    </div>

    <!-- Success Messages -->
    @if(session()->has('message'))
    <div class="mb-4 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 dark:border-green-400 p-4 rounded">
        <div class="flex">
            <i class="fas fa-check-circle text-green-500 dark:text-green-400 mt-0.5"></i>
            <p class="ml-3 text-green-700 dark:text-green-300">{{ session('message') }}</p>
        </div>
    </div>
    @endif

    <!-- Поиск и фильтры -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-search mr-1 text-gray-400 dark:text-gray-500"></i>
                    Поиск
                </label>
                <input type="text" wire:model.live.debounce.500ms="search" placeholder="Лига, стадион, город..."
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-trophy mr-1 text-gray-400 dark:text-gray-500"></i>
                    Лига
                </label>
                <select wire:model.live="filterLeague" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Все лиги</option>
                    @foreach($leagues as $league)
                    <option value="{{ $league->id }}">{{ $league->title_ru }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-calendar mr-1 text-gray-400 dark:text-gray-500"></i>
                    Сезон
                </label>
                <select wire:model.live="filterSeason" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Все сезоны</option>
                    @foreach($seasons as $season)
                    <option value="{{ $season->id }}">{{ $season->title_ru }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-toggle-on mr-1 text-gray-400 dark:text-gray-500"></i>
                    Статус
                </label>
                <select wire:model.live="filterStatus" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Все</option>
                    <option value="active">Активные</option>
                    <option value="finished">Завершенные</option>
                    <option value="canceled">Отмененные</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Matches Table -->
    @if($matches->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-futbol mr-1 text-gray-400 dark:text-gray-500"></i>
                                Матч
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-trophy mr-1 text-gray-400 dark:text-gray-500"></i>
                                Лига / Сезон
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt mr-1 text-gray-400 dark:text-gray-500"></i>
                                Место
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-1 text-gray-400 dark:text-gray-500"></i>
                                Время
                            </div>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-toggle-on mr-1 text-gray-400 dark:text-gray-500"></i>
                                Статус
                            </div>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-cogs mr-1 text-gray-400 dark:text-gray-500"></i>
                                Действия
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($matches as $match)
                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 dark:hover:from-blue-900/20 dark:hover:to-indigo-900/20 transition-all duration-150 border-l-4 border-transparent hover:border-blue-400 dark:hover:border-blue-500">
                        <td class="px-4 py-4">
                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                <div class="flex items-center gap-2">
                                    <span class="text-blue-600 dark:text-blue-400">
                                        {{ optional(App\Models\Club::find($match->owner_club_id))->short_name_ru ?? 'N/A' }}
                                    </span>
                                    <span class="text-gray-400 dark:text-gray-500">vs</span>
                                    <span class="text-red-600 dark:text-red-400">
                                        {{ optional(App\Models\Club::find($match->guest_club_id))->short_name_ru ?? 'N/A' }}
                                    </span>
                                </div>
                                @if($match->is_finished && $match->owner_point !== null && $match->guest_point !== null)
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Счет: {{ $match->owner_point }} - {{ $match->guest_point }}
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="space-y-1">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    <i class="fas fa-trophy text-yellow-500 mr-1 text-xs"></i>
                                    {{ $match->league->title_ru }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-calendar text-gray-400 dark:text-gray-500 mr-1"></i>
                                    {{ $match->season->title_ru }}
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="space-y-1">
                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                    <i class="fas fa-building text-gray-400 dark:text-gray-500 mr-1 text-xs"></i>
                                    {{ $match->stadium->title_ru }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-city text-gray-400 dark:text-gray-500 mr-1"></i>
                                    {{ $match->city->title_ru }}
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="space-y-1">
                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                    <i class="fas fa-calendar-day mr-1 text-gray-400 dark:text-gray-500 text-xs"></i>
                                    {{ $match->start_at->format('d.m.Y') }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-clock mr-1 text-gray-400 dark:text-gray-500"></i>
                                    {{ $match->start_at->format('H:i') }} - {{ $match->end_at->format('H:i') }}
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            <div class="flex flex-col items-center gap-1">
                                @if($match->is_canceled)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-red-100 to-pink-100 text-red-800 dark:from-red-900/30 dark:to-pink-900/30 dark:text-red-300 border border-red-200 dark:border-red-700">
                                    <i class="fas fa-ban mr-1 text-red-600 dark:text-red-400"></i>
                                    Отменен
                                </span>
                                @elseif($match->is_finished)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 dark:from-gray-700 dark:to-slate-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                    <i class="fas fa-flag-checkered mr-1 text-gray-600 dark:text-gray-400"></i>
                                    Завершен
                                </span>
                                @elseif($match->is_active)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 dark:from-green-900/30 dark:to-emerald-900/30 dark:text-green-300 border border-green-200 dark:border-green-700">
                                    <i class="fas fa-check-circle mr-1 text-green-600 dark:text-green-400"></i>
                                    Активен
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 dark:from-yellow-900/30 dark:to-amber-900/30 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-700">
                                    <i class="fas fa-pause-circle mr-1 text-yellow-600 dark:text-yellow-400"></i>
                                    Неактивен
                                </span>
                                @endif
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $match->operation->title_ru }}
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('match-entity-view', $match->id) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-150"
                                   title="Просмотр">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                @if($canEdit)
                                <button wire:click="editMatch({{ $match->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/30 dark:hover:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors duration-150"
                                        title="Редактировать">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                @endif
                                @if($canEdit && !$match->is_finished)
                                <button wire:click="toggleMatchStatus({{ $match->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $match->is_active ? 'bg-yellow-50 hover:bg-yellow-100 dark:bg-yellow-900/30 dark:hover:bg-yellow-900/50 text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-300' : 'bg-green-50 hover:bg-green-100 dark:bg-green-900/30 dark:hover:bg-green-900/50 text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300' }} transition-colors duration-150"
                                        title="{{ $match->is_active ? 'Деактивировать' : 'Активировать' }}">
                                    <i class="fas fa-{{ $match->is_active ? 'ban' : 'check' }} text-sm"></i>
                                </button>
                                @endif
                                @if($canDelete)
                                <button wire:click="deleteMatch({{ $match->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition-colors duration-150"
                                        title="Удалить"
                                        onclick="return confirm('Вы уверены, что хотите удалить этот матч?')">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Пагинация -->
    @if($matches->hasPages())
    <div class="mt-8">
        {{ $matches->links('pagination::tailwind') }}
    </div>
    @endif
    @else
    <div class="text-center py-12">
        <div class="flex flex-col items-center">
            <i class="fas fa-futbol text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Матчи не найдены</p>
            <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Попробуйте изменить параметры фильтрации</p>
            @if($canCreate)
            <p class="mt-4">
                <button wire:click="$set('showCreateModal', true)"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-150">
                    <i class="fas fa-plus mr-2"></i>
                    Создать матч
                </button>
            </p>
            @endif
        </div>
    </div>
    @endif

    <!-- Модальное окно создания матча -->
    @if($showCreateModal)
    <div wire:ignore.self class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" wire:click="$set('showCreateModal', false)">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <form wire:submit.prevent="createMatch">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Создание нового матча</h3>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Лига*</label>
                                <select wire:model="leagueId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Выберите лигу</option>
                                    @foreach($leagues as $league)
                                    <option value="{{ $league->id }}">{{ $league->title_ru }}</option>
                                    @endforeach
                                </select>
                                @error('leagueId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Сезон*</label>
                                <select wire:model="seasonId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Выберите сезон</option>
                                    @foreach($seasons as $season)
                                    <option value="{{ $season->id }}">{{ $season->title_ru }}</option>
                                    @endforeach
                                </select>
                                @error('seasonId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Город*</label>
                                <select wire:model="cityId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Выберите город</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->title_ru }}</option>
                                    @endforeach
                                </select>
                                @error('cityId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Стадион*</label>
                                <select wire:model="stadiumId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Выберите стадион</option>
                                    @foreach($stadiums as $stadium)
                                    <option value="{{ $stadium->id }}">{{ $stadium->title_ru }}</option>
                                    @endforeach
                                </select>
                                @error('stadiumId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Команда хозяев*</label>
                                <select wire:model="ownerClubId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Выберите команду</option>
                                    @foreach($clubs as $club)
                                    <option value="{{ $club->id }}">{{ $club->short_name_ru }}</option>
                                    @endforeach
                                </select>
                                @error('ownerClubId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Команда гостей*</label>
                                <select wire:model="guestClubId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Выберите команду</option>
                                    @foreach($clubs as $club)
                                    <option value="{{ $club->id }}">{{ $club->short_name_ru }}</option>
                                    @endforeach
                                </select>
                                @error('guestClubId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Время начала*</label>
                                <input type="datetime-local" wire:model="startAt" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('startAt') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Время окончания*</label>
                                <input type="datetime-local" wire:model="endAt" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('endAt') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Текущая операция*</label>
                            <select wire:model="currentOperationId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Выберите операцию</option>
                                @foreach($operations as $operation)
                                <option value="{{ $operation->id }}">{{ $operation->title_ru }}</option>
                                @endforeach
                            </select>
                            @error('currentOperationId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-3 gap-4 mt-4">
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="isActive" id="isActive" class="w-4 h-4 text-blue-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500">
                                <label for="isActive" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Активен</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="isFinished" id="isFinished" class="w-4 h-4 text-blue-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500">
                                <label for="isFinished" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Завершен</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="isCanceled" id="isCanceled" class="w-4 h-4 text-blue-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500">
                                <label for="isCanceled" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Отменен</label>
                            </div>
                        </div>

                        <!-- Требования к судьям (ОБЯЗАТЕЛЬНО) -->
                        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100">
                                    <i class="fas fa-users mr-2 text-blue-500"></i>
                                    Требования к судьям <span class="text-red-500">*</span>
                                </h4>
                                <button type="button" wire:click="addJudgeRequirement"
                                        class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded-md transition-colors">
                                    <i class="fas fa-plus mr-1"></i>
                                    Добавить
                                </button>
                            </div>
                            @error('judgeRequirements') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                            <div class="space-y-3 max-h-60 overflow-y-auto">
                                @foreach($judgeRequirements as $index => $requirement)
                                <div class="grid grid-cols-12 gap-2 items-start p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="col-span-6">
                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Тип судьи*</label>
                                        <select wire:model="judgeRequirements.{{ $index }}.judge_type_id"
                                                class="w-full px-2 py-1.5 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Выберите тип</option>
                                            @foreach($judgeTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->title_ru }}</option>
                                            @endforeach
                                        </select>
                                        @error("judgeRequirements.{$index}.judge_type_id") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-span-3">
                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Количество*</label>
                                        <input type="number" wire:model="judgeRequirements.{{ $index }}.qty" min="1"
                                               class="w-full px-2 py-1.5 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @error("judgeRequirements.{$index}.qty") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-span-2 flex items-end">
                                        <label class="flex items-center text-xs">
                                            <input type="checkbox" wire:model="judgeRequirements.{{ $index }}.is_required"
                                                   class="w-4 h-4 text-blue-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500">
                                            <span class="ml-1 text-gray-700 dark:text-gray-300">Обяз.</span>
                                        </label>
                                    </div>
                                    <div class="col-span-1 flex items-end">
                                        @if(count($judgeRequirements) > 1)
                                        <button type="button" wire:click="removeJudgeRequirement({{ $index }})"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 rounded-md transition-colors"
                                                title="Удалить">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Логисты матча (ОБЯЗАТЕЛЬНО) -->
                        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100">
                                    <i class="fas fa-truck mr-2 text-purple-500"></i>
                                    Логисты матча <span class="text-red-500">*</span>
                                </h4>
                                <button type="button" wire:click="addMatchLogist"
                                        class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded-md transition-colors">
                                    <i class="fas fa-plus mr-1"></i>
                                    Добавить
                                </button>
                            </div>
                            @error('matchLogists') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                            <div class="space-y-3 max-h-40 overflow-y-auto">
                                @foreach($matchLogists as $index => $logist)
                                <div class="grid grid-cols-12 gap-2 items-start p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="col-span-11">
                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Логист*</label>
                                        <select wire:model="matchLogists.{{ $index }}.logist_id"
                                                class="w-full px-2 py-1.5 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Выберите логиста</option>
                                            @foreach($logists as $logistUser)
                                            <option value="{{ $logistUser->id }}">{{ $logistUser->last_name }} {{ $logistUser->first_name }}</option>
                                            @endforeach
                                        </select>
                                        @error("matchLogists.{$index}.logist_id") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-span-1 flex items-end">
                                        @if(count($matchLogists) > 1)
                                        <button type="button" wire:click="removeMatchLogist({{ $index }})"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 rounded-md transition-colors"
                                                title="Удалить">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Дедлайны матча (ОПЦИОНАЛЬНО) -->
                        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100">
                                    <i class="fas fa-clock mr-2 text-orange-500"></i>
                                    Дедлайны матча
                                </h4>
                                <button type="button" wire:click="addMatchDeadline"
                                        class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded-md transition-colors">
                                    <i class="fas fa-plus mr-1"></i>
                                    Добавить
                                </button>
                            </div>

                            @if(count($matchDeadlines) > 0)
                            <div class="space-y-3 max-h-60 overflow-y-auto">
                                @foreach($matchDeadlines as $index => $deadline)
                                <div class="grid grid-cols-12 gap-2 items-start p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="col-span-5">
                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Операция</label>
                                        <select wire:model="matchDeadlines.{{ $index }}.operation_id"
                                                class="w-full px-2 py-1.5 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Выберите операцию</option>
                                            @foreach($operations as $operation)
                                            <option value="{{ $operation->id }}">{{ $operation->title_ru }}</option>
                                            @endforeach
                                        </select>
                                        @error("matchDeadlines.{$index}.operation_id") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-span-3">
                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Начало</label>
                                        <input type="datetime-local" wire:model="matchDeadlines.{{ $index }}.start_at"
                                               class="w-full px-2 py-1.5 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @error("matchDeadlines.{$index}.start_at") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-span-3">
                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Окончание</label>
                                        <input type="datetime-local" wire:model="matchDeadlines.{{ $index }}.end_at"
                                               class="w-full px-2 py-1.5 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @error("matchDeadlines.{$index}.end_at") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-span-1 flex items-end">
                                        <button type="button" wire:click="removeMatchDeadline({{ $index }})"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 rounded-md transition-colors"
                                                title="Удалить">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">Дедлайны не добавлены (опционально)</p>
                            @endif
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Создать
                        </button>
                        <button type="button" wire:click="$set('showCreateModal', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Отмена
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Модальное окно редактирования матча -->
    @if($showEditModal)
    <div wire:ignore.self class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" wire:click="$set('showEditModal', false)">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <form wire:submit.prevent="updateMatch">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Редактирование матча</h3>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Лига*</label>
                                <select wire:model="leagueId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Выберите лигу</option>
                                    @foreach($leagues as $league)
                                    <option value="{{ $league->id }}">{{ $league->title_ru }}</option>
                                    @endforeach
                                </select>
                                @error('leagueId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Сезон*</label>
                                <select wire:model="seasonId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Выберите сезон</option>
                                    @foreach($seasons as $season)
                                    <option value="{{ $season->id }}">{{ $season->title_ru }}</option>
                                    @endforeach
                                </select>
                                @error('seasonId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Город*</label>
                                <select wire:model="cityId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Выберите город</option>
                                    @foreach($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->title_ru }}</option>
                                    @endforeach
                                </select>
                                @error('cityId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Стадион*</label>
                                <select wire:model="stadiumId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Выберите стадион</option>
                                    @foreach($stadiums as $stadium)
                                    <option value="{{ $stadium->id }}">{{ $stadium->title_ru }}</option>
                                    @endforeach
                                </select>
                                @error('stadiumId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Команда хозяев*</label>
                                <select wire:model="ownerClubId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Выберите команду</option>
                                    @foreach($clubs as $club)
                                    <option value="{{ $club->id }}">{{ $club->title_ru }}</option>
                                    @endforeach
                                </select>
                                @error('ownerClubId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Команда гостей*</label>
                                <select wire:model="guestClubId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Выберите команду</option>
                                    @foreach($clubs as $club)
                                    <option value="{{ $club->id }}">{{ $club->title_ru }}</option>
                                    @endforeach
                                </select>
                                @error('guestClubId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Время начала*</label>
                                <input type="datetime-local" wire:model="startAt" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('startAt') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Время окончания*</label>
                                <input type="datetime-local" wire:model="endAt" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('endAt') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Текущая операция*</label>
                            <select wire:model="currentOperationId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Выберите операцию</option>
                                @foreach($operations as $operation)
                                <option value="{{ $operation->id }}">{{ $operation->title_ru }}</option>
                                @endforeach
                            </select>
                            @error('currentOperationId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Голы хозяев</label>
                                <input type="number" wire:model="ownerPoint" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('ownerPoint') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Голы гостей</label>
                                <input type="number" wire:model="guestPoint" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('guestPoint') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 mt-4">
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="isActive" id="editIsActive" class="w-4 h-4 text-blue-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500">
                                <label for="editIsActive" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Активен</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="isFinished" id="editIsFinished" class="w-4 h-4 text-blue-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500">
                                <label for="editIsFinished" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Завершен</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="isCanceled" id="editIsCanceled" class="w-4 h-4 text-blue-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500">
                                <label for="editIsCanceled" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Отменен</label>
                            </div>
                        </div>

                        @if($isCanceled)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Причина отмены</label>
                            <textarea wire:model="cancelReason" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            @error('cancelReason') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        @endif

                        <!-- Требования к судьям (ОБЯЗАТЕЛЬНО) - EDIT -->
                        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100">
                                    <i class="fas fa-users mr-2 text-blue-500"></i>
                                    Требования к судьям <span class="text-red-500">*</span>
                                </h4>
                                <button type="button" wire:click="addJudgeRequirement"
                                        class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded-md transition-colors">
                                    <i class="fas fa-plus mr-1"></i>
                                    Добавить
                                </button>
                            </div>
                            @error('judgeRequirements') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                            <div class="space-y-3 max-h-60 overflow-y-auto">
                                @foreach($judgeRequirements as $index => $requirement)
                                <div class="grid grid-cols-12 gap-2 items-start p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="col-span-6">
                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Тип судьи*</label>
                                        <select wire:model="judgeRequirements.{{ $index }}.judge_type_id"
                                                class="w-full px-2 py-1.5 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Выберите тип</option>
                                            @foreach($judgeTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->title_ru }}</option>
                                            @endforeach
                                        </select>
                                        @error("judgeRequirements.{$index}.judge_type_id") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-span-3">
                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Количество*</label>
                                        <input type="number" wire:model="judgeRequirements.{{ $index }}.qty" min="1"
                                               class="w-full px-2 py-1.5 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @error("judgeRequirements.{$index}.qty") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-span-2 flex items-end">
                                        <label class="flex items-center text-xs">
                                            <input type="checkbox" wire:model="judgeRequirements.{{ $index }}.is_required"
                                                   class="w-4 h-4 text-blue-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500">
                                            <span class="ml-1 text-gray-700 dark:text-gray-300">Обяз.</span>
                                        </label>
                                    </div>
                                    <div class="col-span-1 flex items-end">
                                        @if(count($judgeRequirements) > 1)
                                        <button type="button" wire:click="removeJudgeRequirement({{ $index }})"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 rounded-md transition-colors"
                                                title="Удалить">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Логисты матча (ОБЯЗАТЕЛЬНО) - EDIT -->
                        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100">
                                    <i class="fas fa-truck mr-2 text-purple-500"></i>
                                    Логисты матча <span class="text-red-500">*</span>
                                </h4>
                                <button type="button" wire:click="addMatchLogist"
                                        class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded-md transition-colors">
                                    <i class="fas fa-plus mr-1"></i>
                                    Добавить
                                </button>
                            </div>
                            @error('matchLogists') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                            <div class="space-y-3 max-h-40 overflow-y-auto">
                                @foreach($matchLogists as $index => $logist)
                                <div class="grid grid-cols-12 gap-2 items-start p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="col-span-11">
                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Логист*</label>
                                        <select wire:model="matchLogists.{{ $index }}.logist_id"
                                                class="w-full px-2 py-1.5 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Выберите логиста</option>
                                            @foreach($logists as $logistUser)
                                            <option value="{{ $logistUser->id }}">{{ $logistUser->last_name }} {{ $logistUser->first_name }}</option>
                                            @endforeach
                                        </select>
                                        @error("matchLogists.{$index}.logist_id") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-span-1 flex items-end">
                                        @if(count($matchLogists) > 1)
                                        <button type="button" wire:click="removeMatchLogist({{ $index }})"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 rounded-md transition-colors"
                                                title="Удалить">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Дедлайны матча (ОПЦИОНАЛЬНО) - EDIT -->
                        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100">
                                    <i class="fas fa-clock mr-2 text-orange-500"></i>
                                    Дедлайны матча
                                </h4>
                                <button type="button" wire:click="addMatchDeadline"
                                        class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded-md transition-colors">
                                    <i class="fas fa-plus mr-1"></i>
                                    Добавить
                                </button>
                            </div>

                            @if(count($matchDeadlines) > 0)
                            <div class="space-y-3 max-h-60 overflow-y-auto">
                                @foreach($matchDeadlines as $index => $deadline)
                                <div class="grid grid-cols-12 gap-2 items-start p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="col-span-5">
                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Операция</label>
                                        <select wire:model="matchDeadlines.{{ $index }}.operation_id"
                                                class="w-full px-2 py-1.5 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Выберите операцию</option>
                                            @foreach($operations as $operation)
                                            <option value="{{ $operation->id }}">{{ $operation->title_ru }}</option>
                                            @endforeach
                                        </select>
                                        @error("matchDeadlines.{$index}.operation_id") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-span-3">
                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Начало</label>
                                        <input type="datetime-local" wire:model="matchDeadlines.{{ $index }}.start_at"
                                               class="w-full px-2 py-1.5 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @error("matchDeadlines.{$index}.start_at") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-span-3">
                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Окончание</label>
                                        <input type="datetime-local" wire:model="matchDeadlines.{{ $index }}.end_at"
                                               class="w-full px-2 py-1.5 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @error("matchDeadlines.{$index}.end_at") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-span-1 flex items-end">
                                        <button type="button" wire:click="removeMatchDeadline({{ $index }})"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 rounded-md transition-colors"
                                                title="Удалить">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">Дедлайны не добавлены (опционально)</p>
                            @endif
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Обновить
                        </button>
                        <button type="button" wire:click="$set('showEditModal', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Отмена
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
