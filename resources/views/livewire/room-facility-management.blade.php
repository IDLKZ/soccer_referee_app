<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Управление удобствами номеров</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Создание, редактирование и управление связями между номерами отелей и удобствами
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
        <div class="flex-1 max-w-lg space-y-2">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="searchRoom"
                    placeholder="Поиск по номеру..."
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="searchFacility"
                    placeholder="Поиск по удобству..."
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
                Добавить связь
            </button>
        @endif
    </div>

    <!-- RoomFacilities Table -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Список связей ({{ $roomFacilities->total() }})
            </h3>
        </div>
        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse ($roomFacilities as $roomFacility)
                <li class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $roomFacility->hotel_room->title_ru }}
                                        </h4>
                                        @if ($roomFacility->hotel_room->title_kk)
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                ({{ $roomFacility->hotel_room->title_kk }})
                                            </span>
                                        @endif
                                        <div class="mt-1">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                                <i class="fas fa-link mr-1"></i>
                                                {{ $roomFacility->facility->title_ru }}
                                            </span>
                                            @if ($roomFacility->facility->title_kk)
                                                <span class="text-sm text-gray-400 dark:text-gray-500">
                                                    ({{ $roomFacility->facility->title_kk }})
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2">
                                @if ($canEdit)
                                    <button
                                        wire:click="editRoomFacility({{ $roomFacility->id }})"
                                        class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600"
                                        title="Редактировать"
                                    >
                                        <i class="fas fa-edit text-blue-500"></i>
                                    </button>
                                @endif

                                @if ($canDelete)
                                    <button
                                        wire:click="deleteRoomFacility({{ $roomFacility->id }})"
                                        wire:confirm="Вы уверены, что хотите удалить эту связь?"
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
                    <p>Связи не найдены</p>
                </li>
            @endforelse
        </ul>

        <!-- Pagination -->
        @if ($roomFacilities->hasPages())
            <div class="bg-white dark:bg-gray-800 px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 sm:px-6">
                {{ $roomFacilities->links() }}
            </div>
        @endif
    </div>

    <!-- Create Modal -->
    @if ($showCreateModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex-shrink-0">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Создание новой связи</h3>
                    </div>
                    <div class="px-6 py-4 flex-1 overflow-y-auto">
                        <form wire:submit.prevent="createRoomFacility">
                            <!-- Room Selection -->
                            <div class="mb-4">
                                <label for="room_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Номер отеля *
                                </label>
                                <select id="room_id" wire:model="roomId"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">Выберите номер</option>
                                    @foreach ($rooms as $room)
                                        <option value="{{ $room->id }}">{{ $room->title_ru }}</option>
                                    @endforeach
                                </select>
                                @error('roomId')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Facility Selection -->
                            <div class="mb-4">
                                <label for="facility_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Удобство *
                                </label>
                                <select id="facility_id" wire:model="facilityId"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">Выберите удобство</option>
                                    @foreach ($facilities as $facility)
                                        <option value="{{ $facility->id }}">{{ $facility->title_ru }}</option>
                                    @endforeach
                                </select>
                                @error('facilityId')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            @error('duplicate')
                                <div class="mb-4 p-3 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-md">
                                    <span class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</span>
                                </div>
                            @enderror

                            <!-- Actions -->
                            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700 flex-shrink-0">
                                <button type="button" wire:click="$set('showCreateModal', false)"
                                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Отмена
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Создать связь
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
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Редактирование связи</h3>
                    </div>
                    <div class="px-6 py-4 flex-1 overflow-y-auto">
                        <form wire:submit.prevent="updateRoomFacility">
                            <!-- Room Selection -->
                            <div class="mb-4">
                                <label for="edit_room_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Номер отеля *
                                </label>
                                <select id="edit_room_id" wire:model="editRoomId"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">Выберите номер</option>
                                    @foreach ($rooms as $room)
                                        <option value="{{ $room->id }}">{{ $room->title_ru }}</option>
                                    @endforeach
                                </select>
                                @error('editRoomId')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Facility Selection -->
                            <div class="mb-4">
                                <label for="edit_facility_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Удобство *
                                </label>
                                <select id="edit_facility_id" wire:model="editFacilityId"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">Выберите удобство</option>
                                    @foreach ($facilities as $facility)
                                        <option value="{{ $facility->id }}">{{ $facility->title_ru }}</option>
                                    @endforeach
                                </select>
                                @error('editFacilityId')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            @error('duplicate_edit')
                                <div class="mb-4 p-3 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-md">
                                    <span class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</span>
                                </div>
                            @enderror

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