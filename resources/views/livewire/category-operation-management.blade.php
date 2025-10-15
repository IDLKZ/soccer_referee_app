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
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Управление категориями операций</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Создавайте и управляйте категориями операций</p>
        </div>
        @if($canCreate)
            <button wire:click="$set('showCreateModal', true)"
                    class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Добавить категорию
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
                   placeholder="Название, значение..."
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Категория
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Уровень
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Статус
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Действия
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($categoryOperations as $categoryOperation)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                    <i class="fas fa-layer-group text-white text-lg"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $categoryOperation->title_ru }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $categoryOperation->value }}
                                    </div>
                                    @if($categoryOperation->title_en)
                                        <div class="text-xs text-gray-400 dark:text-gray-500">
                                            {{ $categoryOperation->title_en }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                Уровень {{ $categoryOperation->level }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($categoryOperation->is_active)
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Активна
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Неактивна
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if($canEdit)
                                <button wire:click="toggleActive({{ $categoryOperation->id }})"
                                        class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300 mr-3"
                                        title="{{ $categoryOperation->is_active ? 'Деактивировать' : 'Активировать' }}">
                                    <i class="fas fa-power-off"></i>
                                </button>
                                <button wire:click="editCategoryOperation({{ $categoryOperation->id }})"
                                        class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 mr-3">
                                    <i class="fas fa-edit"></i>
                                </button>
                            @endif
                            @if($canDelete)
                                <button wire:click="deleteCategoryOperation({{ $categoryOperation->id }})"
                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300"
                                        onclick="return confirm('Вы уверены, что хотите удалить эту категорию операции?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-layer-group text-gray-400 text-4xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Категории операций не найдены</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Попробуйте изменить параметры поиска или создайте новую категорию</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $categoryOperations->links() }}
    </div>

    <!-- Create Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" style="display: flex; align-items: center; justify-content: center;">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full mx-4">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Добавление новой категории операции</h3>
                </div>
                <div class="px-6 py-4">
                    <form wire:submit.prevent="createCategoryOperation">
                        <!-- Название (RU) -->
                        <div class="mb-4">
                            <label for="title_ru" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Название (RU) *
                            </label>
                            <input type="text" id="title_ru" wire:model="titleRu"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Название категории операции">
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
                                   placeholder="Операция санаты">
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
                                   placeholder="Operation category">
                            @error('titleEn')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Значение -->
                        <div class="mb-4">
                            <label for="value" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Значение *
                            </label>
                            <input type="text" id="value" wire:model="value"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Значение для идентификации">
                            @error('value')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Уровень -->
                        <div class="mb-4">
                            <label for="level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Уровень *
                            </label>
                            <select id="level" wire:model="level"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ $level == $i ? 'selected' : '' }}>Уровень {{ $i }}</option>
                                @endfor
                            </select>
                            @error('level')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Активность -->
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="isActive" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                    Активна
                                </span>
                            </label>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" wire:click="$set('showCreateModal', false)"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Отмена
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Создать категорию
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
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Редактирование категории операции</h3>
                </div>
                <div class="px-6 py-4">
                    <form wire:submit.prevent="updateCategoryOperation">
                        <!-- Название (RU) -->
                        <div class="mb-4">
                            <label for="edit_title_ru" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Название (RU) *
                            </label>
                            <input type="text" id="edit_title_ru" wire:model="editTitleRu"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Название категории операции"
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
                                   placeholder="Операция санаты"
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
                                   placeholder="Operation category"
                                   value="{{ $editTitleEn }}">
                            @error('editTitleEn')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Значение -->
                        <div class="mb-4">
                            <label for="edit_value" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Значение *
                            </label>
                            <input type="text" id="edit_value" wire:model="editValue"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Значение для идентификации"
                                   value="{{ $editValue }}">
                            @error('editValue')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Уровень -->
                        <div class="mb-4">
                            <label for="edit_level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Уровень *
                            </label>
                            <select id="edit_level" wire:model="editLevel"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ $editLevel == $i ? 'selected' : '' }}>Уровень {{ $i }}</option>
                                @endfor
                            </select>
                            @error('editLevel')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Активность -->
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="editIsActive" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                    Активна
                                </span>
                            </label>
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