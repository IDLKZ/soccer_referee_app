<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Управление операциями</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Создание, редактирование и управление операциями системы
        </p>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 rounded-md bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                        {{ session('message') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Search and Actions -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex-1 max-w-lg">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Поиск операций..."
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>
        </div>

        @if ($canCreate)
            <button
                wire:click="$toggle('showCreateModal')"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
                <i class="fas fa-plus mr-2"></i>
                Добавить операцию
            </button>
        @endif
    </div>

    <!-- Operations Table -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Список операций ({{ $operations->total() }})
            </h3>
        </div>
        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse ($operations as $operation)
                <li class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        {{ $operation->title_ru }}
                                    </h4>
                                    @if ($operation->title_kk)
                                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                            ({{ $operation->title_kk }})
                                        </span>
                                    @endif

                                    <!-- Status Badge -->
                                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $operation->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        {{ $operation->is_active ? 'Активна' : 'Неактивна' }}
                                    </span>

                                    <!-- Category Badge -->
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $operation->category_operation->title_ru ?? 'Без категории' }}
                                    </span>

                                    <!-- Special Flags -->
                                    @if ($operation->is_first)
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                            Первая
                                        </span>
                                    @endif

                                    @if ($operation->is_last)
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                            Последняя
                                        </span>
                                    @endif

                                    @if ($operation->can_reject)
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            Может отклоняться
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-1 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <span>Value: {{ $operation->value }}</span>
                                    @if ($operation->description_ru)
                                        <span class="mx-2">•</span>
                                        <span class="truncate">{{ Str::limit($operation->description_ru, 100) }}</span>
                                    @endif
                                </div>

                                @if (!empty($operation->responsible_roles))
                                    <div class="mt-1 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-users mr-1"></i>
                                        Ответственные: {{ implode(', ', $operation->responsible_roles) }}
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center space-x-2">
                                @if ($canEdit)
                                    <button
                                        wire:click="toggleActive({{ $operation->id }})"
                                        class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600"
                                        title="{{ $operation->is_active ? 'Деактивировать' : 'Активировать' }}"
                                    >
                                        <i class="fas {{ $operation->is_active ? 'fa-eye-slash text-gray-500' : 'fa-eye text-green-500' }}"></i>
                                    </button>

                                    <button
                                        wire:click="editOperation({{ $operation->id }})"
                                        class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600"
                                        title="Редактировать"
                                    >
                                        <i class="fas fa-edit text-blue-500"></i>
                                    </button>
                                @endif

                                @if ($canDelete)
                                    <button
                                        wire:click="deleteOperation({{ $operation->id }})"
                                        wire:confirm="Вы уверены, что хотите удалить эту операцию?"
                                        class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600"
                                        title="Удалить"
                                    >
                                        <i class="fas fa-trash text-red-500"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                    <i class="fas fa-inbox text-4xl mb-2"></i>
                    <p>Операции не найдены</p>
                </li>
            @endforelse
        </ul>

        <!-- Pagination -->
        @if ($operations->hasPages())
            <div class="bg-white dark:bg-gray-800 px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 sm:px-6">
                {{ $operations->links() }}
            </div>
        @endif
    </div>

    <!-- Create Modal -->
    @if ($showCreateModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex-shrink-0">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Создание новой операции</h3>
                    </div>
                    <div class="px-6 py-4 flex-1 overflow-y-auto">
                        <form wire:submit.prevent="createOperation">
                        <!-- Category -->
                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Категория *
                            </label>
                            <select id="category_id" wire:model="categoryId"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Выберите категорию</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->title_ru }}</option>
                                @endforeach
                            </select>
                            @error('categoryId')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Title RU -->
                        <div class="mb-4">
                            <label for="title_ru" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Название (RU) *
                            </label>
                            <input type="text" id="title_ru" wire:model="titleRu"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Название операции">
                            @error('titleRu')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Title KK -->
                        <div class="mb-4">
                            <label for="title_kk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Название (KK)
                            </label>
                            <input type="text" id="title_kk" wire:model="titleKk"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Операция атауы">
                            @error('titleKk')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Title EN -->
                        <div class="mb-4">
                            <label for="title_en" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Название (EN)
                            </label>
                            <input type="text" id="title_en" wire:model="titleEn"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Operation title">
                            @error('titleEn')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description RU -->
                        <div class="mb-4">
                            <label for="description_ru" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Описание (RU)
                            </label>
                            <textarea id="description_ru" wire:model="descriptionRu" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Описание операции"></textarea>
                            @error('descriptionRu')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description KK -->
                        <div class="mb-4">
                            <label for="description_kk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Описание (KK)
                            </label>
                            <textarea id="description_kk" wire:model="descriptionKk" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Операция сипаттамасы"></textarea>
                            @error('descriptionKk')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description EN -->
                        <div class="mb-4">
                            <label for="description_en" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Описание (EN)
                            </label>
                            <textarea id="description_en" wire:model="descriptionEn" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Operation description"></textarea>
                            @error('descriptionEn')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Value -->
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

                        <!-- Result -->
                        <div class="mb-4">
                            <label for="result" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Результат *
                            </label>
                            <input type="number" id="result" wire:model="result" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="0">
                            @error('result')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Previous Operation -->
                        <div class="mb-4">
                            <label for="previous_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Предыдущая операция
                            </label>
                            <select id="previous_id" wire:model="previousId"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Нет</option>
                                @foreach ($availableOperations as $op)
                                    @if ($op->id != ($editingOperationId ?? 0))
                                        <option value="{{ $op->id }}">{{ $op->title_ru }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('previousId')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Next Operation -->
                        <div class="mb-4">
                            <label for="next_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Следующая операция
                            </label>
                            <select id="next_id" wire:model="nextId"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Нет</option>
                                @foreach ($availableOperations as $op)
                                    @if ($op->id != ($editingOperationId ?? 0))
                                        <option value="{{ $op->id }}">{{ $op->title_ru }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('nextId')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- On Reject Operation -->
                        <div class="mb-4">
                            <label for="on_reject_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Операция при отклонении
                            </label>
                            <select id="on_reject_id" wire:model="onRejectId"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Нет</option>
                                @foreach ($availableOperations as $op)
                                    @if ($op->id != ($editingOperationId ?? 0))
                                        <option value="{{ $op->id }}">{{ $op->title_ru }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('onRejectId')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Checkboxes -->
                        <div class="mb-4">
                            <label class="flex items-center mb-2">
                                <input type="checkbox" wire:model="isFirst" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                    Первая операция
                                </span>
                            </label>
                            <label class="flex items-center mb-2">
                                <input type="checkbox" wire:model="isLast" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                    Последняя операция
                                </span>
                            </label>
                            <label class="flex items-center mb-2">
                                <input type="checkbox" wire:model="canReject" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                    Может отклоняться
                                </span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="isActive" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                    Активна
                                </span>
                            </label>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700 flex-shrink-0">
                            <button type="button" wire:click="$set('showCreateModal', false)"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Отмена
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Создать операцию
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Modal -->
    @if ($showEditModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex-shrink-0">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Редактирование операции</h3>
                    </div>
                    <div class="px-6 py-4 flex-1 overflow-y-auto">
                        <form wire:submit.prevent="updateOperation">
                        <!-- Category -->
                        <div class="mb-4">
                            <label for="edit_category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Категория *
                            </label>
                            <select id="edit_category_id" wire:model="editCategoryId"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Выберите категорию</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->title_ru }}</option>
                                @endforeach
                            </select>
                            @error('editCategoryId')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Title RU -->
                        <div class="mb-4">
                            <label for="edit_title_ru" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Название (RU) *
                            </label>
                            <input type="text" id="edit_title_ru" wire:model="editTitleRu"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Название операции">
                            @error('editTitleRu')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Title KK -->
                        <div class="mb-4">
                            <label for="edit_title_kk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Название (KK)
                            </label>
                            <input type="text" id="edit_title_kk" wire:model="editTitleKk"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Операция атауы">
                            @error('editTitleKk')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Title EN -->
                        <div class="mb-4">
                            <label for="edit_title_en" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Название (EN)
                            </label>
                            <input type="text" id="edit_title_en" wire:model="editTitleEn"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Operation title">
                            @error('editTitleEn')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description RU -->
                        <div class="mb-4">
                            <label for="edit_description_ru" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Описание (RU)
                            </label>
                            <textarea id="edit_description_ru" wire:model="editDescriptionRu" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Описание операции"></textarea>
                            @error('editDescriptionRu')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description KK -->
                        <div class="mb-4">
                            <label for="edit_description_kk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Описание (KK)
                            </label>
                            <textarea id="edit_description_kk" wire:model="editDescriptionKk" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Операция сипаттамасы"></textarea>
                            @error('editDescriptionKk')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description EN -->
                        <div class="mb-4">
                            <label for="edit_description_en" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Описание (EN)
                            </label>
                            <textarea id="edit_description_en" wire:model="editDescriptionEn" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Operation description"></textarea>
                            @error('editDescriptionEn')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Value -->
                        <div class="mb-4">
                            <label for="edit_value" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Значение *
                            </label>
                            <input type="text" id="edit_value" wire:model="editValue"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Значение для идентификации">
                            @error('editValue')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Result -->
                        <div class="mb-4">
                            <label for="edit_result" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Результат *
                            </label>
                            <input type="number" id="edit_result" wire:model="editResult" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="0">
                            @error('editResult')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Previous Operation -->
                        <div class="mb-4">
                            <label for="edit_previous_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Предыдущая операция
                            </label>
                            <select id="edit_previous_id" wire:model="editPreviousId"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Нет</option>
                                @foreach ($availableOperations as $op)
                                    @if ($op->id != $editingOperationId)
                                        <option value="{{ $op->id }}">{{ $op->title_ru }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('editPreviousId')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Next Operation -->
                        <div class="mb-4">
                            <label for="edit_next_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Следующая операция
                            </label>
                            <select id="edit_next_id" wire:model="editNextId"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Нет</option>
                                @foreach ($availableOperations as $op)
                                    @if ($op->id != $editingOperationId)
                                        <option value="{{ $op->id }}">{{ $op->title_ru }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('editNextId')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- On Reject Operation -->
                        <div class="mb-4">
                            <label for="edit_on_reject_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Операция при отклонении
                            </label>
                            <select id="edit_on_reject_id" wire:model="editOnRejectId"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Нет</option>
                                @foreach ($availableOperations as $op)
                                    @if ($op->id != $editingOperationId)
                                        <option value="{{ $op->id }}">{{ $op->title_ru }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('editOnRejectId')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Checkboxes -->
                        <div class="mb-4">
                            <label class="flex items-center mb-2">
                                <input type="checkbox" wire:model="editIsFirst" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                    Первая операция
                                </span>
                            </label>
                            <label class="flex items-center mb-2">
                                <input type="checkbox" wire:model="editIsLast" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                    Последняя операция
                                </span>
                            </label>
                            <label class="flex items-center mb-2">
                                <input type="checkbox" wire:model="editCanReject" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                    Может отклоняться
                                </span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="editIsActive" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                    Активна
                                </span>
                            </label>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700 flex-shrink-0">
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