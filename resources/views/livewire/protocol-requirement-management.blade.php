<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Управление требованиями протоколов</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Управление требованиями к документам для различных лиг и типов судей</p>
        </div>
        @if($canCreate)
        <button wire:click="$set('showCreateModal', true)"
                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
            <i class="fas fa-plus mr-2"></i>
            Создать требование
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
                <input type="text" wire:model.live.debounce.500ms="search" placeholder="Название, описание..."
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-trophy mr-1 text-gray-400 dark:text-gray-500"></i>
                    Лига
                </label>
                <select wire:model.live="filterLeague" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                    <option value="">Все лиги</option>
                    @foreach($leagues as $league)
                    <option value="{{ $league->id }}">{{ $league->title_ru }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-user-shield mr-1 text-gray-400 dark:text-gray-500"></i>
                    Тип судьи
                </label>
                <select wire:model.live="filterJudgeType" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                    <option value="">Все типы</option>
                    @foreach($judgeTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->title_ru }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-toggle-on mr-1 text-gray-400 dark:text-gray-500"></i>
                    Обязательность
                </label>
                <select wire:model.live="filterRequired" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                    <option value="">Все</option>
                    <option value="1">Обязательные</option>
                    <option value="0">Необязательные</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Requirements Table -->
    @if($requirements->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-file-alt mr-1 text-gray-400 dark:text-gray-500"></i>
                                Требование
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-trophy mr-1 text-gray-400 dark:text-gray-500"></i>
                                Лига
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-user-shield mr-1 text-gray-400 dark:text-gray-500"></i>
                                Тип судьи
                            </div>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-exclamation-circle mr-1 text-gray-400 dark:text-gray-500"></i>
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
                    @foreach($requirements as $requirement)
                    <tr class="hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 dark:hover:from-purple-900/20 dark:hover:to-pink-900/20 transition-all duration-150 border-l-4 border-transparent hover:border-purple-400 dark:hover:border-purple-500">
                        <td class="px-4 py-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shadow-sm">
                                        <i class="fas fa-file-alt text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $requirement->title_ru }}
                                    </div>
                                    @if($requirement->info_ru)
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ Str::limit($requirement->info_ru, 60) }}
                                    </div>
                                    @endif
                                    @if($requirement->extensions)
                                    <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                        <i class="fas fa-paperclip mr-1"></i>
                                        Форматы: {{ is_array($requirement->extensions) ? implode(', ', $requirement->extensions) : $requirement->extensions }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @if($requirement->league)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-800 dark:from-blue-900/40 dark:to-cyan-900/40 dark:text-blue-200 border border-blue-200 dark:border-blue-700">
                                <i class="fas fa-trophy mr-1.5 text-blue-600 dark:text-blue-400"></i>
                                {{ $requirement->league->title_ru }}
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-600">
                                Все лиги
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @if($requirement->judge_type)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-800 dark:from-indigo-900/40 dark:to-purple-900/40 dark:text-indigo-200 border border-indigo-200 dark:border-indigo-700">
                                <i class="fas fa-user-shield mr-1.5 text-indigo-600 dark:text-indigo-400"></i>
                                {{ $requirement->judge_type->title_ru }}
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-600">
                                Все типы
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            @if($requirement->is_required)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-red-100 to-orange-100 text-red-800 dark:from-red-900/40 dark:to-orange-900/40 dark:text-red-200 border border-red-200 dark:border-red-700">
                                <i class="fas fa-exclamation-circle mr-1 text-red-600 dark:text-red-400"></i>
                                Обязательно
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 dark:from-green-900/40 dark:to-emerald-900/40 dark:text-green-200 border border-green-200 dark:border-green-700">
                                <i class="fas fa-info-circle mr-1 text-green-600 dark:text-green-400"></i>
                                Необязательно
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if($canEdit)
                                <button wire:click="editRequirement({{ $requirement->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-purple-50 hover:bg-purple-100 dark:bg-purple-900/30 dark:hover:bg-purple-900/50 text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300 transition-colors duration-150"
                                        title="Редактировать">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                @endif
                                @if($canDelete)
                                <button wire:click="deleteRequirement({{ $requirement->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-150"
                                        title="Удалить"
                                        onclick="return confirm('Вы уверены, что хотите удалить это требование?')">
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
    @if($requirements->hasPages())
    <div class="mt-8">
        {{ $requirements->links('pagination::tailwind') }}
    </div>
    @endif
    @else
    <div class="text-center py-12">
        <div class="flex flex-col items-center">
            <div class="w-20 h-20 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-file-alt text-4xl text-purple-400 dark:text-purple-500"></i>
            </div>
            <p class="text-gray-500 dark:text-gray-400 font-medium text-lg">Требования не найдены</p>
            <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Попробуйте изменить параметры фильтрации</p>
            @if($canCreate)
            <p class="mt-4">
                <button wire:click="$set('showCreateModal', true)"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-medium rounded-lg transition-all duration-150 shadow-md hover:shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    Создать требование
                </button>
            </p>
            @endif
        </div>
    </div>
    @endif

    <!-- Модальное окно создания требования -->
    @if($showCreateModal)
    <div wire:ignore.self class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" wire:click="$set('showCreateModal', false)">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <form wire:submit.prevent="createRequirement">
                    <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Создание нового требования</h3>
                    </div>

                    <div class="bg-white dark:bg-gray-800 px-6 py-6 space-y-6">
                        <!-- Basic Information -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">Основная информация</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        <i class="fas fa-trophy mr-1"></i> Лига*
                                    </label>
                                    <select wire:model.live="leagueId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                                        <option value="">Выберите лигу</option>
                                        @foreach($leagues as $league)
                                        <option value="{{ $league->id }}">{{ $league->title_ru }}</option>
                                        @endforeach
                                    </select>
                                    @error('leagueId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        <i class="fas fa-user-shield mr-1"></i> Тип судьи*
                                    </label>
                                    <select wire:model.live="judgeTypeId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                                        <option value="">Выберите тип судьи</option>
                                        @foreach($judgeTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->title_ru }}</option>
                                        @endforeach
                                    </select>
                                    @error('judgeTypeId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    <i class="fas fa-futbol mr-1"></i> Матч (необязательно)
                                </label>
                                <select wire:model="matchId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    <option value="">Для всех матчей лиги</option>
                                    @foreach($matches as $match)
                                    <option value="{{ $match->id }}">
                                        {{ $match->ownerClub->short_name_ru ?? $match->ownerClub->title_ru }} - {{ $match->guestClub->short_name_ru ?? $match->guestClub->title_ru }}
                                        ({{ \Carbon\Carbon::parse($match->start_at)->format('d.m.Y') }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('matchId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Titles -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">Название требования</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        <i class="fas fa-flag mr-1"></i> Название (RU)*
                                    </label>
                                    <input type="text" wire:model="titleRu" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    @error('titleRu') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        <i class="fas fa-flag mr-1"></i> Название (KK)*
                                    </label>
                                    <input type="text" wire:model="titleKk" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    @error('titleKk') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        <i class="fas fa-flag mr-1"></i> Название (EN)*
                                    </label>
                                    <input type="text" wire:model="titleEn" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    @error('titleEn') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Info/Description -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">Описание</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        <i class="fas fa-flag mr-1"></i> Описание (RU)
                                    </label>
                                    <textarea wire:model="infoRu" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                                    @error('infoRu') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        <i class="fas fa-flag mr-1"></i> Описание (KK)
                                    </label>
                                    <textarea wire:model="infoKk" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                                    @error('infoKk') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        <i class="fas fa-flag mr-1"></i> Описание (EN)
                                    </label>
                                    <textarea wire:model="infoEn" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                                    @error('infoEn') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Settings -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">Настройки</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        <i class="fas fa-file-code mr-1"></i> Допустимые форматы файлов
                                    </label>
                                    <input type="text" wire:model="extensions" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="pdf,doc,docx,jpg,png">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Разделяйте форматы запятыми</p>
                                    @error('extensions') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="flex items-center mt-7">
                                        <input type="checkbox" wire:model="isRequired" class="rounded border-gray-300 dark:border-gray-600 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                            <i class="fas fa-exclamation-circle mr-1"></i> Обязательное требование
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Example Files Upload -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-paperclip mr-1"></i> Примеры файлов
                            </h4>

                            <!-- Upload Area -->
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-purple-500 dark:hover:border-purple-500 transition-colors duration-200">
                                <input type="file"
                                       wire:model="exampleFiles"
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                       multiple
                                       class="hidden"
                                       id="example-files-upload-create">
                                <label for="example-files-upload-create" class="cursor-pointer">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 dark:text-gray-600 mb-3"></i>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                            Нажмите для выбора файлов или перетащите их сюда
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-500">
                                            Поддерживаются форматы: PDF, DOC, DOCX, JPG, PNG (макс. 10MB каждый)
                                        </p>
                                    </div>
                                </label>
                            </div>

                            <!-- New Files Preview -->
                            @if($exampleFiles && count($exampleFiles) > 0)
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Выбранные файлы:</p>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    @foreach($exampleFiles as $index => $file)
                                    <div class="relative group">
                                        <div class="border-2 border-gray-200 dark:border-gray-700 rounded-lg p-3 bg-gray-50 dark:bg-gray-700/50 hover:border-purple-400 dark:hover:border-purple-500 transition-colors">
                                            <div class="flex items-center justify-center h-16 mb-2">
                                                @if(in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png']))
                                                <img src="{{ $file->temporaryUrl() }}" alt="Preview" class="h-16 w-auto object-contain rounded">
                                                @else
                                                <i class="fas fa-file-alt text-3xl text-gray-400 dark:text-gray-500"></i>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 text-center truncate">{{ $file->getClientOriginalName() }}</p>
                                        </div>
                                        <button type="button"
                                                wire:click="removeNewFile({{ $index }})"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition-colors shadow-md">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @error('exampleFiles.*') <span class="text-red-500 text-xs block mt-2">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-base font-medium text-white hover:from-purple-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm transition-all duration-200">
                            <i class="fas fa-save mr-2"></i> Создать
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

    <!-- Модальное окно редактирования требования -->
    @if($showEditModal)
    <div wire:ignore.self class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" wire:click="$set('showEditModal', false)">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <form wire:submit.prevent="updateRequirement">
                    <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Редактирование требования</h3>
                    </div>

                    <div class="bg-white dark:bg-gray-800 px-6 py-6 space-y-6">
                        <!-- Basic Information -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">Основная информация</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        <i class="fas fa-trophy mr-1"></i> Лига*
                                    </label>
                                    <select wire:model.live="leagueId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                                        <option value="">Выберите лигу</option>
                                        @foreach($leagues as $league)
                                        <option value="{{ $league->id }}">{{ $league->title_ru }}</option>
                                        @endforeach
                                    </select>
                                    @error('leagueId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        <i class="fas fa-user-shield mr-1"></i> Тип судьи*
                                    </label>
                                    <select wire:model.live="judgeTypeId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                                        <option value="">Выберите тип судьи</option>
                                        @foreach($judgeTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->title_ru }}</option>
                                        @endforeach
                                    </select>
                                    @error('judgeTypeId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    <i class="fas fa-futbol mr-1"></i> Матч (необязательно)
                                </label>
                                <select wire:model="matchId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    <option value="">Для всех матчей лиги</option>
                                    @foreach($matches as $match)
                                    <option value="{{ $match->id }}">
                                        {{ $match->ownerClub->short_name_ru ?? $match->ownerClub->title_ru }} - {{ $match->guestClub->short_name_ru ?? $match->guestClub->title_ru }}
                                        ({{ \Carbon\Carbon::parse($match->start_at)->format('d.m.Y') }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('matchId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Titles -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">Название требования</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        <i class="fas fa-flag mr-1"></i> Название (RU)*
                                    </label>
                                    <input type="text" wire:model="titleRu" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    @error('titleRu') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        <i class="fas fa-flag mr-1"></i> Название (KK)*
                                    </label>
                                    <input type="text" wire:model="titleKk" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    @error('titleKk') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        <i class="fas fa-flag mr-1"></i> Название (EN)*
                                    </label>
                                    <input type="text" wire:model="titleEn" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    @error('titleEn') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Info/Description -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">Описание</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        <i class="fas fa-flag mr-1"></i> Описание (RU)
                                    </label>
                                    <textarea wire:model="infoRu" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                                    @error('infoRu') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        <i class="fas fa-flag mr-1"></i> Описание (KK)
                                    </label>
                                    <textarea wire:model="infoKk" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                                    @error('infoKk') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        <i class="fas fa-flag mr-1"></i> Описание (EN)
                                    </label>
                                    <textarea wire:model="infoEn" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                                    @error('infoEn') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Settings -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">Настройки</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        <i class="fas fa-file-code mr-1"></i> Допустимые форматы файлов
                                    </label>
                                    <input type="text" wire:model="extensions" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="pdf,doc,docx,jpg,png">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Разделяйте форматы запятыми</p>
                                    @error('extensions') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="flex items-center mt-7">
                                        <input type="checkbox" wire:model="isRequired" class="rounded border-gray-300 dark:border-gray-600 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                            <i class="fas fa-exclamation-circle mr-1"></i> Обязательное требование
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Existing Files -->
                        @if(isset($existingFiles) && count($existingFiles) > 0)
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-folder-open mr-1"></i> Существующие файлы
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @foreach($existingFiles as $fileId => $fileName)
                                <div class="relative group">
                                    <div class="border-2 border-gray-200 dark:border-gray-700 rounded-lg p-3 bg-gray-50 dark:bg-gray-700/50 hover:border-purple-400 dark:hover:border-purple-500 transition-colors">
                                        <div class="flex items-center justify-center h-16 mb-2">
                                            @if(in_array(pathinfo($fileName, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                                            <i class="fas fa-image text-3xl text-blue-400 dark:text-blue-500"></i>
                                            @else
                                            <i class="fas fa-file-alt text-3xl text-gray-400 dark:text-gray-500"></i>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 text-center truncate" title="{{ $fileName }}">{{ $fileName }}</p>
                                    </div>
                                    <button type="button"
                                            wire:click="removeExistingFile({{ $fileId }})"
                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition-colors shadow-md">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Example Files Upload -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                <i class="fas fa-paperclip mr-1"></i> Добавить новые примеры файлов
                            </h4>

                            <!-- Upload Area -->
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-purple-500 dark:hover:border-purple-500 transition-colors duration-200">
                                <input type="file"
                                       wire:model="exampleFiles"
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                       multiple
                                       class="hidden"
                                       id="example-files-upload-edit">
                                <label for="example-files-upload-edit" class="cursor-pointer">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 dark:text-gray-600 mb-3"></i>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                            Нажмите для выбора файлов или перетащите их сюда
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-500">
                                            Поддерживаются форматы: PDF, DOC, DOCX, JPG, PNG (макс. 10MB каждый)
                                        </p>
                                    </div>
                                </label>
                            </div>

                            <!-- New Files Preview -->
                            @if($exampleFiles && count($exampleFiles) > 0)
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Новые файлы для загрузки:</p>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    @foreach($exampleFiles as $index => $file)
                                    <div class="relative group">
                                        <div class="border-2 border-gray-200 dark:border-gray-700 rounded-lg p-3 bg-gray-50 dark:bg-gray-700/50 hover:border-purple-400 dark:hover:border-purple-500 transition-colors">
                                            <div class="flex items-center justify-center h-16 mb-2">
                                                @if(in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png']))
                                                <img src="{{ $file->temporaryUrl() }}" alt="Preview" class="h-16 w-auto object-contain rounded">
                                                @else
                                                <i class="fas fa-file-alt text-3xl text-gray-400 dark:text-gray-500"></i>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 text-center truncate">{{ $file->getClientOriginalName() }}</p>
                                        </div>
                                        <button type="button"
                                                wire:click="removeNewFile({{ $index }})"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition-colors shadow-md">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @error('exampleFiles.*') <span class="text-red-500 text-xs block mt-2">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-base font-medium text-white hover:from-purple-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm transition-all duration-200">
                            <i class="fas fa-save mr-2"></i> Обновить
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
