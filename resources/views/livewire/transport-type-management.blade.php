<div>
    <!-- Success Message -->
    @if(session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 border border-green-400 text-green-700 dark:text-green-200 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 01-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                        {{ session('message') }}
                    </p>
                </div>
            </div>
        </div>
        {{ session()->forget('message') }}
    @endif

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Управление типами транспорта</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Создавайте и управляйте информацией о типах транспорта</p>
        </div>
        @if($canCreate)
            <button wire:click="$set('showCreateModal', true)"
                    class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Добавить тип
            </button>
        @endif
    </div>

    <!-- Search -->
    <div class="mb-6 bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text"
                   wire:model.live="search"
                   placeholder="Название, описание..."
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Тип транспорта
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Дата создания
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Действия
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($transportTypes as $transportType)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($transportType->hasMedia('image'))
                                    <img class="h-12 w-12 rounded-lg object-cover"
                                         src="{{ asset($transportType->getFirstMediaUrl('image')) }}"
                                         alt="{{ $transportType->title_ru }}">
                                @else
                                    <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center">
                                        <i class="fas fa-truck text-white text-lg"></i>
                                    </div>
                                @endif
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $transportType->title_ru }}
                                    </div>
                                    @if($transportType->description_ru)
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ Str::limit($transportType->description_ru, 100) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $transportType->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if($canEdit)
                                <button wire:click="editTransportType({{ $transportType->id }})"
                                        class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 mr-3">
                                    <i class="fas fa-edit"></i>
                                </button>
                            @endif
                            @if($canDelete)
                                <button wire:click="deleteTransportType({{ $transportType->id }})"
                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300"
                                        onclick="return confirm('Вы уверены, что хотите удалить этот тип транспорта?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-truck text-gray-400 text-4xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Типы транспорта не найдены</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Попробуйте изменить параметры поиска или создайте новый тип</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $transportTypes->links() }}
    </div>

    <!-- Create Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" style="display: flex; align-items: center; justify-content: center;">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full mx-4">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Добавление нового типа транспорта</h3>
                </div>
                <div class="px-6 py-4">
                    <form wire:submit.prevent="createTransportType">
                        <!-- Фото типа транспорта -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Фото типа транспорта
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l16.5-16.5M4 20l4 4m0-12l8 8m0 0l8 8m-8-8v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                        <label for="image-upload-create" class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-blue-500">
                                            <span>Загрузить файл</span>
                                            <input type="file" id="image-upload-create" wire:model="image" accept="image/*" class="sr-only">
                                        </label>
                                        <p class="pl-1">или перетащите</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF до 5MB</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Название (RU) -->
                        <div class="mb-4">
                            <label for="title_ru" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Название (RU) *
                            </label>
                            <input type="text" id="title_ru" wire:model="titleRu"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Название типа транспорта">
                            @error('titleRu')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Название (KK) -->
                        <div class="mb-4">
                            <label for="title_kk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Название (KK)
                            </label>
                            <input type="text" id="title_kk" wire:model="titleKk"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Тасымы атауы">
                            @error('titleKk')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Название (EN) -->
                        <div class="mb-4">
                            <label for="title_en" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Название (EN)
                            </label>
                            <input type="text" id="title_en" wire:model="titleEn"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Transport type name">
                            @error('titleEn')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Описание (RU) -->
                        <div class="mb-4">
                            <label for="description_ru" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Описание (RU)
                            </label>
                            <textarea id="description_ru" wire:model="descriptionRu" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Описание типа транспорта"></textarea>
                            @error('descriptionRu')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Описание (KK) -->
                        <div class="mb-4">
                            <label for="description_kk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Описание (KK)
                            </label>
                            <textarea id="description_kk" wire:model="descriptionKk" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Тасымы сипаттамасы"></textarea>
                            @error('descriptionKk')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Описание (EN) -->
                        <div class="mb-4">
                            <label for="description_en" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Описание (EN)
                            </label>
                            <textarea id="description_en" wire:model="descriptionEn" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Transport type description"></textarea>
                            @error('descriptionEn')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" wire:click="$set('showCreateModal', false)"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Отмена
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Создать тип
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Modal -->
    @if($showEditModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" style="display: flex; align-items: center; justify-content: center;">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full mx-4">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Редактирование типа транспорта</h3>
                </div>
                <div class="px-6 py-4">
                    <form wire:submit.prevent="updateTransportType">
                        <!-- Фото типа транспорта -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Фото типа транспорта
                            </label>
                            @if($editingTransportTypeId && $transportType = \App\Models\TransportType::find($editingTransportTypeId))
                                @if($transportType->hasMedia('image'))
                                    <div class="mt-1 mb-4">
                                        <img src="{{ asset($transportType->getFirstMediaUrl('image')) }}"
                                             alt="{{ $transportType->title_ru }}"
                                             class="h-32 w-full object-cover rounded-lg">
                                        <button type="button" wire:click="removeCurrentImage"
                                                class="mt-2 text-red-600 hover:text-red-800 text-sm">
                                            <i class="fas fa-trash mr-1"></i>
                                            Удалить изображение
                                        </button>
                                    </div>
                                @endif
                            @endif
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l16.5-16.5M4 20l4 4m0-12l8 8m0 0l8 8m-8-8v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                        <label for="image-upload-edit" class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-blue-500">
                                            <span>Загрузить файл</span>
                                            <input type="file" id="image-upload-edit" wire:model="editImage" accept="image/*" class="sr-only">
                                        </label>
                                        <p class="pl-1">или перетащите</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF до 5MB</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Название (RU) -->
                        <div class="mb-4">
                            <label for="edit_title_ru" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Название (RU) *
                            </label>
                            <input type="text" id="edit_title_ru" wire:model="editTitleRu"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Название типа транспорта"
                                   value="{{ $editTitleRu }}">
                            @error('editTitleRu')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Название (KK) -->
                        <div class="mb-4">
                            <label for="edit_title_kk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Название (KK)
                            </label>
                            <input type="text" id="edit_title_kk" wire:model="editTitleKk"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Тасымы атауы"
                                   value="{{ $editTitleKk }}">
                            @error('editTitleKk')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Название (EN) -->
                        <div class="mb-4">
                            <label for="edit_title_en" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Название (EN)
                            </label>
                            <input type="text" id="edit_title_en" wire:model="editTitleEn"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Transport type name"
                                   value="{{ $editTitleEn }}">
                            @error('editTitleEn')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Описание (RU) -->
                        <div class="mb-4">
                            <label for="edit_description_ru" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Описание (RU)
                            </label>
                            <textarea id="edit_description_ru" wire:model="editDescriptionRu" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Описание типа транспорта">{{ $editDescriptionRu }}</textarea>
                            @error('editDescriptionRu')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Описание (KK) -->
                        <div class="mb-4">
                            <label for="edit_description_kk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Описание (KK)
                            </label>
                            <textarea id="edit_description_kk" wire:model="editDescriptionKk" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Тасымы сипаттамасы">{{ $editDescriptionKk }}</textarea>
                            @error('editDescriptionKk')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Описание (EN) -->
                        <div class="mb-4">
                            <label for="edit_description_en" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Описание (EN)
                            </label>
                            <textarea id="edit_description_en" wire:model="editDescriptionEn" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Transport type description">{{ $editDescriptionEn }}</textarea>
                            @error('editDescriptionEn')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" wire:click="$set('showEditModal', false)"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Отмена
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Сохранить изменения
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>