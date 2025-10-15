<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Управление удобствами</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Создание, редактирование и управление удобствами номеров
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
                    placeholder="Поиск удобств..."
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
                Добавить удобство
            </button>
        @endif
    </div>

    <!-- Facilities Table -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Список удобств ({{ $facilities->total() }})
            </h3>
        </div>
        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse ($facilities as $facility)
                <li class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        {{ $facility->title_ru }}
                                    </h4>
                                    @if ($facility->title_kk)
                                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                            ({{ $facility->title_kk }})
                                        </span>
                                    @endif
                                    @if ($facility->title_en)
                                        <span class="ml-2 text-sm text-gray-400 dark:text-gray-500">
                                            / {{ $facility->title_en }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center space-x-2">
                                @if ($canEdit)
                                    <button
                                        wire:click="editFacility({{ $facility->id }})"
                                        class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600"
                                        title="Редактировать"
                                    >
                                        <i class="fas fa-edit text-blue-500"></i>
                                    </button>
                                @endif

                                @if ($canDelete)
                                    <button
                                        wire:click="deleteFacility({{ $facility->id }})"
                                        wire:confirm="Вы уверены, что хотите удалить это удобство?"
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
                    <p>Удобства не найдены</p>
                </li>
            @endforelse
        </ul>

        <!-- Pagination -->
        @if ($facilities->hasPages())
            <div class="bg-white dark:bg-gray-800 px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 sm:px-6">
                {{ $facilities->links() }}
            </div>
        @endif
    </div>

    <!-- Create Modal -->
    @if ($showCreateModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex-shrink-0">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Создание нового удобства</h3>
                    </div>
                    <div class="px-6 py-4 flex-1 overflow-y-auto">
                        <form wire:submit.prevent="createFacility">
                            <!-- Title RU -->
                            <div class="mb-4">
                                <label for="title_ru" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Название (RU) *
                                </label>
                                <input type="text" id="title_ru" wire:model="titleRu"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="Название удобства">
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
                                       placeholder="Ыңтыс атауы">
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
                                       placeholder="Facility name">
                                @error('titleEn')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700 flex-shrink-0">
                                <button type="button" wire:click="$set('showCreateModal', false)"
                                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Отмена
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Создать удобство
                                </button>
                            </div>
                        </form>
                    </div>
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
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Редактирование удобства</h3>
                    </div>
                    <div class="px-6 py-4 flex-1 overflow-y-auto">
                        <form wire:submit.prevent="updateFacility">
                            <!-- Title RU -->
                            <div class="mb-4">
                                <label for="edit_title_ru" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Название (RU) *
                                </label>
                                <input type="text" id="edit_title_ru" wire:model="editTitleRu"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="Название удобства">
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
                                       placeholder="Ыңтыс атауы">
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
                                       placeholder="Facility name">
                                @error('editTitleEn')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
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
        </div>
    @endif
</div>