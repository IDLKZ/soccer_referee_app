<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Управление связями судей и городов</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Управление связями между судьями и городами</p>
        </div>
        @if($canCreate)
        <button wire:click="$set('showCreateModal', true)"
                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-lg transition-all duration-150 shadow-lg">
            <i class="fas fa-plus mr-2"></i>
            Создать связь
        </button>
        @endif
    </div>

    <!-- Success/Error Messages -->
    @if(session()->has('message'))
    <div class="mb-4 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 dark:border-green-400 p-4 rounded">
        <div class="flex">
            <i class="fas fa-check-circle text-green-500 dark:text-green-400 mt-0.5"></i>
            <p class="ml-3 text-green-700 dark:text-green-300">{{ session('message') }}</p>
        </div>
    </div>
    @endif

    @if(session()->has('error'))
    <div class="mb-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 dark:border-red-400 p-4 rounded">
        <div class="flex">
            <i class="fas fa-exclamation-circle text-red-500 dark:text-red-400 mt-0.5"></i>
            <p class="ml-3 text-red-700 dark:text-red-300">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Поиск и фильтры -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-search mr-1 text-gray-400 dark:text-gray-500"></i>
                    Поиск
                </label>
                <input type="text" wire:model.live.debounce.500ms="search" placeholder="Имя судьи или город..."
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-user-tie mr-1 text-gray-400 dark:text-gray-500"></i>
                    Судья
                </label>
                <select wire:model.live="filterJudge" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Все судьи</option>
                    @foreach($judges as $judge)
                    <option value="{{ $judge->id }}">{{ $judge->last_name }} {{ $judge->first_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-city mr-1 text-gray-400 dark:text-gray-500"></i>
                    Город
                </label>
                <select wire:model.live="filterCity" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Все города</option>
                    @foreach($cities as $city)
                    <option value="{{ $city->id }}">{{ $city->title_ru }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Judge Cities Table -->
    @if($judgeCities->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-hashtag mr-1 text-gray-400 dark:text-gray-500"></i>
                                ID
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-user-tie mr-1 text-gray-400 dark:text-gray-500"></i>
                                Судья
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-city mr-1 text-gray-400 dark:text-gray-500"></i>
                                Город
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-calendar mr-1 text-gray-400 dark:text-gray-500"></i>
                                Дата создания
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
                    @foreach($judgeCities as $judgeCity)
                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 dark:hover:from-blue-900/20 dark:hover:to-indigo-900/20 transition-all duration-150 border-l-4 border-transparent hover:border-blue-400 dark:hover:border-blue-500">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                #{{ $judgeCity->id }}
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($judgeCity->user && $judgeCity->user->image_url)
                                    <img class="h-10 w-10 rounded-full border-2 border-white dark:border-gray-700 shadow-sm" src="{{ $judgeCity->user->image_url }}" alt="">
                                    @else
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center border-2 border-white dark:border-gray-700 shadow-sm">
                                        <span class="text-white text-sm font-bold">
                                            @if($judgeCity->user)
                                            {{ strtoupper(substr($judgeCity->user->first_name, 0, 1)) }}{{ strtoupper(substr($judgeCity->user->last_name, 0, 1)) }}
                                            @endif
                                        </span>
                                    </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    @if($judgeCity->user)
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $judgeCity->user->last_name }} {{ $judgeCity->user->first_name }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-envelope mr-1"></i>{{ $judgeCity->user->email }}
                                    </div>
                                    @else
                                    <span class="text-sm text-red-500 dark:text-red-400">Судья удален</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @if($judgeCity->city)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gradient-to-r from-emerald-100 to-teal-100 dark:from-emerald-900/30 dark:to-teal-900/30 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-700">
                                <i class="fas fa-map-marker-alt mr-1.5 text-emerald-600 dark:text-emerald-400"></i>
                                {{ $judgeCity->city->title_ru }}
                            </span>
                            @else
                            <span class="text-sm text-red-500 dark:text-red-400">Город удален</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                {{ $judgeCity->created_at ? $judgeCity->created_at->format('d.m.Y') : '-' }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $judgeCity->created_at ? $judgeCity->created_at->format('H:i') : '' }}
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if($canEdit)
                                <button wire:click="editJudgeCity({{ $judgeCity->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors duration-150"
                                        title="Редактировать">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                @endif
                                @if($canDelete)
                                <button wire:click="deleteJudgeCity({{ $judgeCity->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition-colors duration-150"
                                        title="Удалить"
                                        onclick="return confirm('Вы уверены, что хотите удалить эту связь?')">
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
    @if($judgeCities->hasPages())
    <div class="mt-8">
        {{ $judgeCities->links('pagination::tailwind') }}
    </div>
    @endif
    @else
    <div class="text-center py-12">
        <div class="flex flex-col items-center">
            <i class="fas fa-link text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Связи не найдены</p>
            <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Попробуйте изменить параметры фильтрации</p>
            @if($canCreate)
            <p class="mt-4">
                <button wire:click="$set('showCreateModal', true)"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-lg transition-all duration-150 shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    Создать связь
                </button>
            </p>
            @endif
        </div>
    </div>
    @endif

    <!-- Модальное окно создания связи -->
    @if($showCreateModal)
    <div wire:ignore.self class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" wire:click="$set('showCreateModal', false)">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit.prevent="createJudgeCity">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Создание связи судьи и города</h3>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    <i class="fas fa-user-tie mr-1 text-gray-400 dark:text-gray-500"></i>
                                    Судья*
                                </label>
                                <select wire:model="userId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Выберите судью</option>
                                    @foreach($judges as $judge)
                                    <option value="{{ $judge->id }}">{{ $judge->last_name }} {{ $judge->first_name }} ({{ $judge->email }})</option>
                                    @endforeach
                                </select>
                                @error('userId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    <i class="fas fa-city mr-1 text-gray-400 dark:text-gray-500"></i>
                                    Город*
                                </label>
                                <select wire:model="cityId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Выберите город</option>
                                    @foreach($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->title_ru }}</option>
                                    @endforeach
                                </select>
                                @error('cityId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
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

    <!-- Модальное окно редактирования связи -->
    @if($showEditModal)
    <div wire:ignore.self class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" wire:click="$set('showEditModal', false)">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit.prevent="updateJudgeCity">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Редактирование связи судьи и города</h3>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    <i class="fas fa-user-tie mr-1 text-gray-400 dark:text-gray-500"></i>
                                    Судья*
                                </label>
                                <select wire:model="userId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Выберите судью</option>
                                    @foreach($judges as $judge)
                                    <option value="{{ $judge->id }}">{{ $judge->last_name }} {{ $judge->first_name }} ({{ $judge->email }})</option>
                                    @endforeach
                                </select>
                                @error('userId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    <i class="fas fa-city mr-1 text-gray-400 dark:text-gray-500"></i>
                                    Город*
                                </label>
                                <select wire:model="cityId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Выберите город</option>
                                    @foreach($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->title_ru }}</option>
                                    @endforeach
                                </select>
                                @error('cityId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
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
