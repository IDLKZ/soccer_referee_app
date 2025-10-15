<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Управление клубами</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Управление футбольными клубами и их профилями</p>
        </div>
        @if($canCreate)
        <button wire:click="$set('showCreateModal', true)"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-semibold rounded-lg transition-colors duration-150">
            <i class="fas fa-plus mr-2"></i>
            Создать клуб
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

    <!-- Error Messages -->
    @if(session()->has('error'))
    <div class="mb-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 dark:border-red-400 p-4 rounded">
        <div class="flex">
            <i class="fas fa-exclamation-circle text-red-500 dark:text-red-400 mt-0.5"></i>
            <p class="ml-3 text-red-700 dark:text-red-300">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Поиск и фильтры -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-search mr-1 text-gray-400 dark:text-gray-500"></i>
                    Поиск
                </label>
                <input type="text" wire:model.live.debounce.500ms="search" placeholder="Название, БИН, адрес..."
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-globe mr-1 text-gray-400 dark:text-gray-500"></i>
                    Город
                </label>
                <select wire:model.live="filterCity" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
                    <option value="">Все города</option>
                    @foreach($cities as $city)
                    <option value="{{ $city->id }}">{{ $city->title_ru }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-tag mr-1 text-gray-400 dark:text-gray-500"></i>
                    Тип клуба
                </label>
                <select wire:model.live="filterType" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
                    <option value="">Все типы</option>
                    @foreach($clubTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->title_ru }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-sitemap mr-1 text-gray-400 dark:text-gray-500"></i>
                    Родительский клуб
                </label>
                <select wire:model.live="filterParent" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
                    <option value="">Все</option>
                    <option value="null">Без родителя</option>
                    @foreach($parentClubs as $club)
                    <option value="{{ $club->id }}">{{ $club->full_name_ru }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-4">
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                <i class="fas fa-toggle-on mr-1 text-gray-400 dark:text-gray-500"></i>
                Статус
            </label>
            <select wire:model.live="filterStatus" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
                <option value="">Все</option>
                <option value="1">Активные</option>
                <option value="0">Неактивные</option>
            </select>
        </div>
    </div>

    <!-- Clubs Table -->
    @if($clubs->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg dark:shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-shield-alt mr-1 text-gray-400 dark:text-gray-500"></i>
                                Клуб
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-globe mr-1 text-gray-400 dark:text-gray-500"></i>
                                Город
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-tag mr-1 text-gray-400 dark:text-gray-500"></i>
                                Тип
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
                    @foreach($clubs as $club)
                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 dark:hover:from-blue-900/20 dark:hover:to-indigo-900/30 transition-all duration-150 border-l-4 border-transparent hover:border-blue-400 dark:hover:border-blue-500">
                        <td class="px-4 py-4">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 h-12 w-12">
                                    @if($club->image_url)
                                        @if(str($club->image_url)->startsWith('http'))
                                        <img src="{{ $club->image_url }}" alt="{{ $club->short_name_ru }}" class="h-12 w-12 rounded-lg object-cover border border-gray-200 dark:border-gray-600">
                                        @else
                                        <img src="{{ Storage::url($club->image_url) }}" alt="{{ $club->short_name_ru }}" class="h-12 w-12 rounded-lg object-cover border border-gray-200 dark:border-gray-600">
                                        @endif
                                    @else
                                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center border-2 border-white dark:border-gray-700 shadow-sm">
                                            <span class="text-white text-sm font-bold">
                                                {{ strtoupper(substr($club->short_name_ru, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $club->short_name_ru }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $club->short_name_kk }} / {{ $club->short_name_en }}
                                    </div>
                                    @if($club->club)
                                    <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                        <i class="fas fa-sitemap mr-1"></i>Дочерний клуб: {{ $club->club->short_name_ru }}
                                    </div>
                                    @endif
                                    @if($club->description_ru)
                                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                        {{ Str::limit(strip_tags($club->description_ru), 80) }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @if($club->city)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gradient-to-r from-cyan-100 to-blue-100 dark:from-cyan-900/30 dark:to-blue-900/30 text-cyan-800 dark:text-cyan-300 border border-cyan-200 dark:border-cyan-700">
                                <i class="fas fa-map-marker-alt mr-1.5 text-cyan-600 dark:text-cyan-400"></i>
                                {{ $club->city->title_ru }}
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-600">
                                <i class="fas fa-question-circle mr-1.5"></i>
                                Без города
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @if($club->club_type)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gradient-to-r from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 text-indigo-800 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-700">
                                <i class="fas fa-shield-alt mr-1.5 text-indigo-600 dark:text-indigo-400"></i>
                                {{ $club->club_type->title_ru }}
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-600">
                                <i class="fas fa-question-circle mr-1.5"></i>
                                Без типа
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-1">
                                @if($club->is_active)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-700">
                                    <i class="fas fa-check-circle mr-1 text-green-600 dark:text-green-400"></i>
                                    Активен
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-red-100 to-pink-100 dark:from-red-900/30 dark:to-pink-900/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-700">
                                    <i class="fas fa-times-circle mr-1 text-red-600 dark:text-red-400"></i>
                                    Неактивен
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if($canEdit)
                                <button wire:click="editClub({{ $club->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:hover:bg-indigo-900/30 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-150"
                                        title="Редактировать">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                @endif
                                @if($canEdit)
                                <button wire:click="toggleClubStatus({{ $club->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $club->is_active ? 'bg-yellow-50 hover:bg-yellow-100 text-yellow-600 hover:text-yellow-800 dark:bg-yellow-900/20 dark:hover:bg-yellow-900/30 dark:text-yellow-400 dark:hover:text-yellow-300' : 'bg-green-50 hover:bg-green-100 text-green-600 hover:text-green-800 dark:bg-green-900/20 dark:hover:bg-green-900/30 dark:text-green-400 dark:hover:text-green-300' }} transition-colors duration-150"
                                        title="{{ $club->is_active ? 'Деактивировать' : 'Активировать' }}">
                                    <i class="fas fa-{{ $club->is_active ? 'ban' : 'check' }} text-sm"></i>
                                </button>
                                @endif
                                @if($canDelete)
                                <button wire:click="deleteClub({{ $club->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/30 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-150"
                                        title="Удалить"
                                        onclick="return confirm('Вы уверены, что хотите удалить этот клуб?')">
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
    @if($clubs->hasPages())
    <div class="mt-8">
        {{ $clubs->links('pagination::tailwind') }}
    </div>
    @endif
    @else
    <div class="text-center py-12">
        <div class="flex flex-col items-center">
            <i class="fas fa-shield-alt text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Клубы не найдены</p>
            <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Попробуйте изменить параметры фильтрации</p>
            @if($canCreate)
            <p class="mt-4">
                <button wire:click="$set('showCreateModal', true)"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-150">
                    <i class="fas fa-plus mr-2"></i>
                    Создать клуб
                </button>
            </p>
            @endif
        </div>
    </div>
    @endif

    <!-- Модальное окно создания клуба -->
    @if($showCreateModal)
    <div wire:ignore.self class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" wire:click="$set('showCreateModal', false)">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-200 dark:border-gray-700">
                <form wire:submit.prevent="createClub">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[90vh] overflow-y-auto">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Создание нового клуба</h3>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Изображение -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Логотип клуба</label>
                                <x-file-uploader
                                    name="imageUrl"
                                    label="Загрузить логотип"
                                    accept="image/*"
                                    :max-size="5120"
                                    :preview="true"
                                />
                            </div>

                            <!-- Родительский клуб -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Родительский клуб</label>
                                <select wire:model="parentId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    <option value="">Без родительского клуба</option>
                                    @foreach($parentClubs as $club)
                                    <option value="{{ $club->id }}">{{ $club->full_name_ru }}</option>
                                    @endforeach
                                </select>
                                @error('parentId') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Город*</label>
                                    <select wire:model="cityId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                        <option value="">Выберите город</option>
                                        @foreach($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->title_ru }}</option>
                                        @endforeach
                                    </select>
                                    @error('cityId') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Тип клуба*</label>
                                    <select wire:model="typeId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                        <option value="">Выберите тип</option>
                                        @foreach($clubTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->title_ru }}</option>
                                        @endforeach
                                    </select>
                                    @error('typeId') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Короткие названия -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Короткое название (RU)*</label>
                                    <input type="text" wire:model="shortNameRu" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('shortNameRu') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Короткое название (KK)*</label>
                                    <input type="text" wire:model="shortNameKk" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('shortNameKk') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Короткое название (EN)*</label>
                                    <input type="text" wire:model="shortNameEn" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('shortNameEn') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Полные названия -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Полное название (RU)*</label>
                                    <input type="text" wire:model="fullNameRu" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('fullNameRu') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Полное название (KK)*</label>
                                    <input type="text" wire:model="fullNameKk" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('fullNameKk') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Полное название (EN)*</label>
                                    <input type="text" wire:model="fullNameEn" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('fullNameEn') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Описания -->
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Описание на русском</label>
                                    <x-text-editor
                                        name="descriptionRu"
                                        placeholder="Введите описание на русском..."
                                        height="150px"
                                    />
                                    @error('descriptionRu') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Описание на казахском</label>
                                    <x-text-editor
                                        name="descriptionKk"
                                        placeholder="Введите описание на казахском..."
                                        height="150px"
                                    />
                                    @error('descriptionKk') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Описание на английском</label>
                                    <x-text-editor
                                        name="descriptionEn"
                                        placeholder="Введите описание на английском..."
                                        height="150px"
                                    />
                                    @error('descriptionEn') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">БИН</label>
                                    <input type="text" wire:model="bin" maxlength="12" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('bin') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Дата основания</label>
                                    <input type="date" wire:model="foundationDate" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('foundationDate') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Адреса -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Адрес (RU)</label>
                                    <input type="text" wire:model="addressRu" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('addressRu') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Адрес (KK)</label>
                                    <input type="text" wire:model="addressKk" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('addressKk') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Адрес (EN)</label>
                                    <input type="text" wire:model="addressEn" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('addressEn') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Телефоны</label>
                                    <textarea wire:model="phone" rows="2" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors" placeholder="+7 (XXX) XXX-XX-XX"></textarea>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Каждый номер с новой строки</p>
                                    @error('phone') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Веб-сайт</label>
                                    <input type="url" wire:model="website" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors" placeholder="https://example.com">
                                    @error('website') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Статус -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Статус*</label>
                                <select wire:model="isActive" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    <option value="1">Активен</option>
                                    <option value="0">Неактивен</option>
                                </select>
                                @error('isActive') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 dark:bg-blue-500 text-base font-medium text-white hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-blue-400 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Создать
                        </button>
                        <button type="button" wire:click="$set('showCreateModal', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Отмена
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Модальное окно редактирования клуба -->
    @if($showEditModal)
    <div wire:ignore.self class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" wire:click="$set('showEditModal', false)">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-200 dark:border-gray-700">
                <form wire:submit.prevent="updateClub">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[90vh] overflow-y-auto">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Редактирование клуба</h3>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Изображение -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Логотип клуба</label>
                                <x-file-uploader
                                    name="imageUrl"
                                    label="Загрузить логотип"
                                    accept="image/*"
                                    :max-size="5120"
                                    :preview="true"
                                    :value="$editingClubId ? \App\Models\Club::find($editingClubId)?->image_url : ''"
                                />
                            </div>

                            <!-- Родительский клуб -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Родительский клуб</label>
                                <select wire:model="parentId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    <option value="">Без родительского клуба</option>
                                    @foreach($parentClubs as $club)
                                    <option value="{{ $club->id }}" {{ $club->id == $parentId ? 'selected' : '' }}>{{ $club->full_name_ru }}</option>
                                    @endforeach
                                </select>
                                @error('parentId') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Город*</label>
                                    <select wire:model="cityId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                        <option value="">Выберите город</option>
                                        @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ $city->id == $cityId ? 'selected' : '' }}>{{ $city->title_ru }}</option>
                                        @endforeach
                                    </select>
                                    @error('cityId') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Тип клуба*</label>
                                    <select wire:model="typeId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                        <option value="">Выберите тип</option>
                                        @foreach($clubTypes as $type)
                                        <option value="{{ $type->id }}" {{ $type->id == $typeId ? 'selected' : '' }}>{{ $type->title_ru }}</option>
                                        @endforeach
                                    </select>
                                    @error('typeId') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Короткие названия -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Короткое название (RU)*</label>
                                    <input type="text" wire:model="shortNameRu" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('shortNameRu') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Короткое название (KK)*</label>
                                    <input type="text" wire:model="shortNameKk" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('shortNameKk') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Короткое название (EN)*</label>
                                    <input type="text" wire:model="shortNameEn" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('shortNameEn') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Полные названия -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Полное название (RU)*</label>
                                    <input type="text" wire:model="fullNameRu" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('fullNameRu') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Полное название (KK)*</label>
                                    <input type="text" wire:model="fullNameKk" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('fullNameKk') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Полное название (EN)*</label>
                                    <input type="text" wire:model="fullNameEn" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('fullNameEn') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Описания -->
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Описание на русском</label>
                                    <x-text-editor
                                        name="descriptionRu"
                                        placeholder="Введите описание на русском..."
                                        height="150px"
                                    />
                                    @error('descriptionRu') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Описание на казахском</label>
                                    <x-text-editor
                                        name="descriptionKk"
                                        placeholder="Введите описание на казахском..."
                                        height="150px"
                                    />
                                    @error('descriptionKk') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Описание на английском</label>
                                    <x-text-editor
                                        name="descriptionEn"
                                        placeholder="Введите описание на английском..."
                                        height="150px"
                                    />
                                    @error('descriptionEn') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">БИН</label>
                                    <input type="text" wire:model="bin" maxlength="12" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('bin') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Дата основания</label>
                                    <input type="date" wire:model="foundationDate" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('foundationDate') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Адреса -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Адрес (RU)</label>
                                    <input type="text" wire:model="addressRu" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('addressRu') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Адрес (KK)</label>
                                    <input type="text" wire:model="addressKk" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('addressKk') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Адрес (EN)</label>
                                    <input type="text" wire:model="addressEn" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('addressEn') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Телефоны</label>
                                    <textarea wire:model="phone" rows="2" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors" placeholder="+7 (XXX) XXX-XX-XX"></textarea>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Каждый номер с новой строки</p>
                                    @error('phone') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Веб-сайт</label>
                                    <input type="url" wire:model="website" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors" placeholder="https://example.com">
                                    @error('website') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Статус -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Статус*</label>
                                <select wire:model="isActive" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    <option value="1">Активен</option>
                                    <option value="0">Неактивен</option>
                                </select>
                                @error('isActive') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 dark:bg-blue-500 text-base font-medium text-white hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-blue-400 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Обновить
                        </button>
                        <button type="button" wire:click="$set('showEditModal', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Отмена
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>