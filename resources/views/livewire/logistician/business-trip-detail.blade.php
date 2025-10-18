<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Навигация назад -->
        <div class="mb-6">
            <a href="{{ route('business-trip-cards') }}"
               class="inline-flex items-center text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Вернуться к списку командировок
            </a>
        </div>

        <!-- Заголовок с информацией о матче -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-6">
            <!-- Цветной заголовок -->
            <div class="p-6 bg-gradient-to-r from-purple-500 to-indigo-500 text-white">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-2xl font-bold flex items-center">
                        <i class="fas fa-plane-departure mr-3"></i>
                        Детали командировки
                    </h1>
                    <span class="text-sm bg-white/20 px-4 py-2 rounded-full backdrop-blur-sm">
                        {{ $match->operation->title_ru }}
                    </span>
                </div>

    
                <!-- Информация о матче -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-white/90">
                    <div>
                        <div class="text-xs uppercase tracking-wide mb-1 text-white/70">Дата и время</div>
                        <div class="font-semibold flex items-center">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            {{ \Carbon\Carbon::parse($match->start_at)->format('d.m.Y H:i') }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs uppercase tracking-wide mb-1 text-white/70">Стадион</div>
                        <div class="font-semibold flex items-center">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            {{ $match->stadium->title_ru ?? 'Не указан' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Команды -->
            <div class="p-6 bg-gray-50 dark:bg-gray-700/50">
                <div class="flex items-center justify-between">
                    <div class="flex-1 text-center">
                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">Хозяева</div>
                        <div class="text-xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $match->ownerClub->short_name_ru ?? $match->ownerClub->title_ru ?? 'Команда 1' }}
                        </div>
                    </div>
                    <div class="px-6 text-2xl font-bold text-gray-400">vs</div>
                    <div class="flex-1 text-center">
                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">Гости</div>
                        <div class="text-xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $match->guestClub->short_name_ru ?? $match->guestClub->title_ru ?? 'Команда 2' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Лига и сезон -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                    <i class="fas fa-trophy mr-2 text-yellow-500"></i>
                    <span class="font-semibold">{{ $match->league->title_ru ?? 'Лига не указана' }}</span>
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <i class="fas fa-calendar mr-2"></i>
                    <span>{{ $match->season->title_ru ?? 'Сезон не указан' }}</span>
                </div>
            </div>
        </div>

        <!-- Success and Error Messages -->
        @if (session()->has('message'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-400 px-6 py-4 rounded-lg flex items-center shadow-md">
                <i class="fas fa-check-circle text-2xl mr-3"></i>
                <span class="font-medium">{{ session('message') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-400 px-6 py-4 rounded-lg flex items-center shadow-md">
                <i class="fas fa-exclamation-circle text-2xl mr-3"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Список командировок -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                    <i class="fas fa-suitcase mr-3 text-purple-600 dark:text-purple-400"></i>
                    Командировки судей ({{ $trips->count() }})
                </h2>
            </div>

            @if($trips->count() > 0)
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($trips as $trip)
                        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <div class="flex items-start justify-between">
                                <!-- Информация о судье -->
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-purple-600 dark:text-purple-400"></i>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $trip->user->surname_ru ?? '' }} {{ $trip->user->name_ru ?? '' }} {{ $trip->user->patronymic_ru ?? '' }}
                                            </div>
                                            <div class="text-xs text-gray-600 dark:text-gray-400">
                                                {{ $trip->user->role->title_ru ?? 'Роль не указана' }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Статус операции -->
                                    <div class="mb-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                            {{ $trip->operation->value === 'final_business_trip_confirmation'
                                                ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400'
                                                : 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400' }}">
                                            <i class="fas fa-tasks mr-1"></i>
                                            {{ $trip->operation->title_ru ?? 'Операция не указана' }}
                                        </span>
                                        @if($trip->is_confirmed)
                                            <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Подтверждено
                                            </span>
                                        @else
                                            <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400">
                                                <i class="fas fa-clock mr-1"></i>
                                                Ожидает подтверждения
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Статистика командировки -->
                                    <div class="flex items-center gap-4 text-sm">
                                        <!-- Отели -->
                                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                                            <i class="fas fa-hotel mr-2 text-blue-500"></i>
                                            <span class="font-semibold">{{ $trip->trip_hotels->count() }}</span>
                                            <span class="ml-1">{{ \Illuminate\Support\Str::plural('отель', $trip->trip_hotels->count()) }}</span>
                                        </div>

                                        <!-- Миграции (транспорт) -->
                                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                                            <i class="fas fa-route mr-2 text-green-500"></i>
                                            <span class="font-semibold">{{ $trip->trip_migrations->count() }}</span>
                                            <span class="ml-1">{{ \Illuminate\Support\Str::plural('маршрут', $trip->trip_migrations->count()) }}</span>
                                        </div>

                                        <!-- Документы -->
                                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                                            <i class="fas fa-file-alt mr-2 text-purple-500"></i>
                                            <span class="font-semibold">{{ $trip->trip_documents->count() }}</span>
                                            <span class="ml-1">{{ \Illuminate\Support\Str::plural('документ', $trip->trip_documents->count()) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Кнопки управления -->
                                <div class="ml-4 flex flex-col gap-2">
                                    <!-- Submit/Resubmit buttons -->
                                    <div class="flex gap-2">
                                        @if($trip->operation->value === 'business_trip_plan_preparation')
                                            <button wire:click="submitForConfirmation({{ $trip->id }})"
                                                    wire:confirm="Вы уверены, что хотите отправить эту командировку на подтверждение?"
                                                    class="inline-flex items-center px-3 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors text-sm shadow-md">
                                                <i class="fas fa-paper-plane mr-2"></i>
                                                Отправить на подтверждение
                                            </button>
                                        @endif

                                        @if($trip->operation->value === 'business_trip_plan_reprocessing')
                                            <button wire:click="resubmitForReview({{ $trip->id }})"
                                                    wire:confirm="Вы уверены, что хотите отправить эту командировку на новую проверку?"
                                                    class="inline-flex items-center px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors text-sm shadow-md">
                                                <i class="fas fa-redo mr-2"></i>
                                                Отправить на проверку
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Edit/View buttons -->
                                    <div class="flex gap-2">
                                        @if(in_array($trip->operation->value, ['business_trip_plan_preparation', 'business_trip_registration', 'business_trip_plan_reprocessing']))
                                            <!-- Редактирование отелей -->
                                            <button wire:click="openHotelsModal({{ $trip->id }})"
                                                    class="inline-flex items-center px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors text-sm">
                                                <i class="fas fa-hotel mr-2"></i>
                                                Отели
                                            </button>

                                            <!-- Редактирование маршрутов -->
                                            <button wire:click="openMigrationsModal({{ $trip->id }})"
                                                    class="inline-flex items-center px-3 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors text-sm">
                                                <i class="fas fa-route mr-2"></i>
                                                Маршруты
                                            </button>

                                            <!-- Редактирование документов -->
                                            <button wire:click="openDocumentsModal({{ $trip->id }})"
                                                    class="inline-flex items-center px-3 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-colors text-sm">
                                                <i class="fas fa-file-alt mr-2"></i>
                                                Документы
                                            </button>
                                        @else
                                            <!-- Просмотр (только чтение) -->
                                            <button wire:click="openHotelsModal({{ $trip->id }})"
                                                    class="inline-flex items-center px-3 py-2 bg-blue-500/70 hover:bg-blue-600/70 text-white rounded-lg transition-colors text-sm">
                                                <i class="fas fa-hotel mr-2"></i>
                                                Отели
                                            </button>

                                            <button wire:click="openMigrationsModal({{ $trip->id }})"
                                                    class="inline-flex items-center px-3 py-2 bg-green-500/70 hover:bg-green-600/70 text-white rounded-lg transition-colors text-sm">
                                                <i class="fas fa-route mr-2"></i>
                                                Маршруты
                                            </button>

                                            <button wire:click="openDocumentsModal({{ $trip->id }})"
                                                    class="inline-flex items-center px-3 py-2 bg-purple-500/70 hover:bg-purple-600/70 text-white rounded-lg transition-colors text-sm">
                                                <i class="fas fa-file-alt mr-2"></i>
                                                Документы
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Пустое состояние -->
                <div class="p-12 text-center">
                    <div class="text-gray-400 dark:text-gray-500 mb-4">
                        <i class="fas fa-suitcase text-6xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Командировки не найдены
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Для этого матча еще не созданы командировки
                    </p>
                </div>
            @endif
        </div>

    </div>

    <!-- Hotels Modal -->
    @if($showHotelsModal)
        <div class="fixed inset-0 bg-gray-900/75 dark:bg-gray-900/90 flex items-center justify-center z-50 p-4"
             wire:click.self="closeHotelsModal">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden"
                 @click.stop>

                <!-- Modal Header -->
                <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold flex items-center">
                            <i class="fas fa-hotel mr-3"></i>
                            Управление отелями
                        </h3>
                        @if($currentTrip)
                            <p class="text-sm text-blue-100 mt-1">
                                {{ $currentTrip->user->surname_ru ?? '' }} {{ $currentTrip->user->name_ru ?? '' }}
                            </p>
                        @endif
                    </div>
                    <button wire:click="closeHotelsModal"
                            class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 180px);">

                    <!-- Success Message -->
                    @if (session()->has('message'))
                        <div class="mb-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-400 px-4 py-3 rounded-lg flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('message') }}
                        </div>
                    @endif

                    @php
                        $canEdit = $currentTrip && in_array($currentTrip->operation->value, ['business_trip_plan_preparation', 'business_trip_registration', 'business_trip_plan_reprocessing']);
                    @endphp

                    @if(!$canEdit)
                        <div class="mb-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-400 px-4 py-3 rounded-lg flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span>Режим просмотра. Редактирование недоступно.</span>
                        </div>
                    @endif

                    <!-- Hotel Form -->
                    @if($canEdit)
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6 mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            {{ $hotelForm['id'] ? 'Редактировать отель' : 'Добавить отель' }}
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Hotel Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-hotel mr-1"></i>
                                    Отель *
                                </label>
                                <select wire:model.live="hotelForm.hotel_id"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                                    <option value="">Выберите отель</option>
                                    @foreach($hotels as $hotel)
                                        <option value="{{ $hotel->id }}">{{ $hotel->title_ru }}</option>
                                    @endforeach
                                </select>
                                @error('hotelForm.hotel_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Room Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-door-open mr-1"></i>
                                    Номер *
                                </label>
                                <select wire:model="hotelForm.hotel_room_id"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100"
                                        {{ !$hotelForm['hotel_id'] ? 'disabled' : '' }}>
                                    <option value="">Выберите номер</option>
                                    @foreach($hotelRooms as $room)
                                        <option value="{{ $room->id }}">
                                            Номер {{ $room->number }} ({{ $room->type_ru ?? 'Тип не указан' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('hotelForm.hotel_room_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Check-in Date -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-calendar-check mr-1"></i>
                                    Дата заезда *
                                </label>
                                <input type="datetime-local"
                                       wire:model="hotelForm.check_in_at"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                                @error('hotelForm.check_in_at')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Check-out Date -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-calendar-times mr-1"></i>
                                    Дата выезда *
                                </label>
                                <input type="datetime-local"
                                       wire:model="hotelForm.check_out_at"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                                @error('hotelForm.check_out_at')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="mt-4 flex gap-2">
                            <button wire:click="saveHotel"
                                    class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                                <i class="fas fa-save mr-2"></i>
                                {{ $hotelForm['id'] ? 'Обновить' : 'Добавить' }}
                            </button>
                            @if($hotelForm['id'])
                                <button wire:click="resetHotelForm"
                                        class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                                    <i class="fas fa-times mr-2"></i>
                                    Отмена
                                </button>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Hotels List -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            Список отелей ({{ $tripHotels->count() }})
                        </h4>

                        @if($tripHotels->count() > 0)
                            <div class="space-y-3">
                                @foreach($tripHotels as $tripHotel)
                                    <div class="bg-white dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600 flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                                <i class="fas fa-hotel mr-2 text-blue-500"></i>
                                                {{ $tripHotel->hotel_room->hotel->title_ru ?? 'Отель не указан' }}
                                            </div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                                <i class="fas fa-door-open mr-2"></i>
                                                Номер: {{ $tripHotel->hotel_room->number ?? 'Н/Д' }}
                                                ({{ $tripHotel->hotel_room->type_ru ?? 'Тип не указан' }})
                                            </div>
                                            <div class="flex gap-4 text-xs text-gray-600 dark:text-gray-400">
                                                <div>
                                                    <i class="fas fa-calendar-check mr-1 text-green-500"></i>
                                                    Заезд: {{ \Carbon\Carbon::parse($tripHotel->from_date)->format('d.m.Y H:i') }}
                                                </div>
                                                <div>
                                                    <i class="fas fa-calendar-times mr-1 text-red-500"></i>
                                                    Выезд: {{ \Carbon\Carbon::parse($tripHotel->to_date)->format('d.m.Y H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                        @if($canEdit)
                                        <div class="ml-4 flex gap-2">
                                            <button wire:click="editHotel({{ $tripHotel->id }})"
                                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click="deleteHotel({{ $tripHotel->id }})"
                                                    onclick="return confirm('Вы уверены, что хотите удалить этот отель?')"
                                                    class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-8 text-center">
                                <i class="fas fa-hotel text-4xl text-gray-400 dark:text-gray-500 mb-2"></i>
                                <p class="text-gray-600 dark:text-gray-400">Отели не добавлены</p>
                            </div>
                        @endif
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                    <button wire:click="closeHotelsModal"
                            class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Закрыть
                    </button>
                </div>

            </div>
        </div>
    @endif

    <!-- Migrations Modal -->
    @if($showMigrationsModal)
        <div class="fixed inset-0 bg-gray-900/75 dark:bg-gray-900/90 flex items-center justify-center z-50 p-4"
             wire:click.self="closeMigrationsModal">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden"
                 @click.stop>

                <!-- Modal Header -->
                <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-green-600 text-white flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold flex items-center">
                            <i class="fas fa-route mr-3"></i>
                            Управление маршрутами
                        </h3>
                        @if($currentTrip)
                            <p class="text-sm text-green-100 mt-1">
                                {{ $currentTrip->user->surname_ru ?? '' }} {{ $currentTrip->user->name_ru ?? '' }}
                            </p>
                        @endif
                    </div>
                    <button wire:click="closeMigrationsModal"
                            class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 180px);">

                    <!-- Success Message -->
                    @if (session()->has('message'))
                        <div class="mb-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-400 px-4 py-3 rounded-lg flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('message') }}
                        </div>
                    @endif

                    @php
                        $canEdit = $currentTrip && in_array($currentTrip->operation->value, ['business_trip_plan_preparation', 'business_trip_registration', 'business_trip_plan_reprocessing']);
                    @endphp

                    @if(!$canEdit)
                        <div class="mb-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-400 px-4 py-3 rounded-lg flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span>Режим просмотра. Редактирование недоступно.</span>
                        </div>
                    @endif

                    <!-- Migration Form -->
                    @if($canEdit)
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6 mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            {{ $migrationForm['id'] ? 'Редактировать маршрут' : 'Добавить маршрут' }}
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Transport Type -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-bus mr-1"></i>
                                    Тип транспорта *
                                </label>
                                <select wire:model="migrationForm.transport_type_id"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-gray-100">
                                    <option value="">Выберите тип транспорта</option>
                                    @foreach($transportTypes as $transportType)
                                        <option value="{{ $transportType->id }}">{{ $transportType->title_ru }}</option>
                                    @endforeach
                                </select>
                                @error('migrationForm.transport_type_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Departure City -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-map-marker-alt mr-1 text-blue-500"></i>
                                    Город отправления *
                                </label>
                                <select wire:model="migrationForm.departure_city_id"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-gray-100">
                                    <option value="">Выберите город</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->title_ru }}</option>
                                    @endforeach
                                </select>
                                @error('migrationForm.departure_city_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Arrival City -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-map-marker-alt mr-1 text-red-500"></i>
                                    Город прибытия *
                                </label>
                                <select wire:model="migrationForm.arrival_city_id"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-gray-100">
                                    <option value="">Выберите город</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->title_ru }}</option>
                                    @endforeach
                                </select>
                                @error('migrationForm.arrival_city_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Departure Date -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Дата отправления *
                                </label>
                                <input type="datetime-local"
                                       wire:model="migrationForm.from_date"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-gray-100">
                                @error('migrationForm.from_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Arrival Date -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-calendar-check mr-1"></i>
                                    Дата прибытия *
                                </label>
                                <input type="datetime-local"
                                       wire:model="migrationForm.to_date"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-gray-100">
                                @error('migrationForm.to_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Info -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Дополнительная информация
                                </label>
                                <textarea wire:model="migrationForm.info"
                                          rows="3"
                                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-gray-100"
                                          placeholder="Номер рейса, номер места и т.д."></textarea>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="mt-4 flex gap-2">
                            <button wire:click="saveMigration"
                                    class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors">
                                <i class="fas fa-save mr-2"></i>
                                {{ $migrationForm['id'] ? 'Обновить' : 'Добавить' }}
                            </button>
                            @if($migrationForm['id'])
                                <button wire:click="resetMigrationForm"
                                        class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                                    <i class="fas fa-times mr-2"></i>
                                    Отмена
                                </button>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Migrations List -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            Список маршрутов ({{ $tripMigrations->count() }})
                        </h4>

                        @if($tripMigrations->count() > 0)
                            <div class="space-y-3">
                                @foreach($tripMigrations as $tripMigration)
                                    <div class="bg-white dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <!-- Transport Type -->
                                                <div class="font-semibold text-gray-900 dark:text-gray-100 mb-2 flex items-center">
                                                    <i class="fas fa-bus mr-2 text-green-500"></i>
                                                    {{ $tripMigration->transport_type->title_ru ?? 'Транспорт не указан' }}
                                                </div>

                                                <!-- Route -->
                                                <div class="mb-2 flex items-center gap-2 text-sm">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400">
                                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                                        {{ $tripMigration->departure_city->title_ru ?? 'Н/Д' }}
                                                    </span>
                                                    <i class="fas fa-arrow-right text-gray-400"></i>
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400">
                                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                                        {{ $tripMigration->arrival_city->title_ru ?? 'Н/Д' }}
                                                    </span>
                                                </div>

                                                <!-- Dates -->
                                                <div class="flex gap-4 text-xs text-gray-600 dark:text-gray-400">
                                                    <div>
                                                        <i class="fas fa-calendar-alt mr-1 text-blue-500"></i>
                                                        Отправление: {{ \Carbon\Carbon::parse($tripMigration->from_date)->format('d.m.Y H:i') }}
                                                    </div>
                                                    <div>
                                                        <i class="fas fa-calendar-check mr-1 text-green-500"></i>
                                                        Прибытие: {{ \Carbon\Carbon::parse($tripMigration->to_date)->format('d.m.Y H:i') }}
                                                    </div>
                                                </div>

                                                <!-- Info -->
                                                @if($tripMigration->info)
                                                    <div class="mt-2 text-xs text-gray-600 dark:text-gray-400 italic">
                                                        <i class="fas fa-info-circle mr-1"></i>
                                                        {{ $tripMigration->info }}
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Actions -->
                                            @if($canEdit)
                                            <div class="ml-4 flex gap-2">
                                                <button wire:click="editMigration({{ $tripMigration->id }})"
                                                        class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button wire:click="deleteMigration({{ $tripMigration->id }})"
                                                        onclick="return confirm('Вы уверены, что хотите удалить этот маршрут?')"
                                                        class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-8 text-center">
                                <i class="fas fa-route text-4xl text-gray-400 dark:text-gray-500 mb-2"></i>
                                <p class="text-gray-600 dark:text-gray-400">Маршруты не добавлены</p>
                            </div>
                        @endif
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                    <button wire:click="closeMigrationsModal"
                            class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Закрыть
                    </button>
                </div>

            </div>
        </div>
    @endif

    <!-- Documents Modal -->
    @if($showDocumentsModal)
        <div class="fixed inset-0 bg-gray-900/75 dark:bg-gray-900/90 flex items-center justify-center z-50 p-4"
             wire:click.self="closeDocumentsModal">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden"
                 @click.stop>

                <!-- Modal Header -->
                <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-purple-600 text-white flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold flex items-center">
                            <i class="fas fa-file-alt mr-3"></i>
                            Управление документами
                        </h3>
                        @if($currentTrip)
                            <p class="text-sm text-purple-100 mt-1">
                                {{ $currentTrip->user->surname_ru ?? '' }} {{ $currentTrip->user->name_ru ?? '' }}
                            </p>
                        @endif
                    </div>
                    <button wire:click="closeDocumentsModal"
                            class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 180px);">

                    <!-- Success Message -->
                    @if (session()->has('message'))
                        <div class="mb-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-400 px-4 py-3 rounded-lg flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('message') }}
                        </div>
                    @endif

                    @php
                        $canEdit = $currentTrip && in_array($currentTrip->operation->value, ['business_trip_plan_preparation', 'business_trip_registration', 'business_trip_plan_reprocessing']);
                    @endphp

                    @if(!$canEdit)
                        <div class="mb-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-400 px-4 py-3 rounded-lg flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span>Режим просмотра. Редактирование недоступно.</span>
                        </div>
                    @endif

                    <!-- Document Form -->
                    @if($canEdit)
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6 mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            {{ $documentForm['id'] ? 'Редактировать документ' : 'Добавить документ' }}
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Title -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-heading mr-1"></i>
                                    Название документа *
                                </label>
                                <input type="text"
                                       wire:model="documentForm.title"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-gray-100"
                                       placeholder="Например: Билет на самолет, Бронь отеля">
                                @error('documentForm.title')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-dollar-sign mr-1"></i>
                                    Цена за единицу *
                                </label>
                                <input type="number"
                                       step="0.01"
                                       wire:model="documentForm.price"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-gray-100">
                                @error('documentForm.price')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Quantity -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-sort-numeric-up mr-1"></i>
                                    Количество *
                                </label>
                                <input type="number"
                                       step="0.01"
                                       wire:model="documentForm.qty"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-gray-100">
                                @error('documentForm.qty')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Total Price (calculated) -->
                            @if($documentForm['price'] && $documentForm['qty'])
                                <div class="md:col-span-2">
                                    <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-3 text-center">
                                        <span class="text-sm text-purple-700 dark:text-purple-400">
                                            <i class="fas fa-calculator mr-1"></i>
                                            Итоговая сумма:
                                        </span>
                                        <span class="ml-2 text-xl font-bold text-purple-800 dark:text-purple-300">
                                            {{ number_format($documentForm['price'] * $documentForm['qty'], 2) }} ₸
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <!-- File Upload -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-file-upload mr-1"></i>
                                    Файл документа (макс. 10 МБ)
                                </label>
                                <input type="file"
                                       wire:model="documentFile"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-gray-100">
                                @error('documentFile')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                @if($documentFile)
                                    <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                                        <i class="fas fa-check mr-1"></i>
                                        Файл выбран: {{ $documentFile->getClientOriginalName() }}
                                    </p>
                                @endif
                            </div>

                            <!-- Info -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Дополнительная информация
                                </label>
                                <textarea wire:model="documentForm.info"
                                          rows="3"
                                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-gray-100"
                                          placeholder="Дополнительные заметки о документе"></textarea>
                            </div>

                            <!-- Active Status -->
                            <div class="md:col-span-2">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox"
                                           wire:model="documentForm.is_active"
                                           class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Документ активен
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="mt-4 flex gap-2">
                            <button wire:click="saveDocument"
                                    class="inline-flex items-center px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-colors">
                                <i class="fas fa-save mr-2"></i>
                                {{ $documentForm['id'] ? 'Обновить' : 'Добавить' }}
                            </button>
                            @if($documentForm['id'])
                                <button wire:click="resetDocumentForm"
                                        class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                                    <i class="fas fa-times mr-2"></i>
                                    Отмена
                                </button>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Documents List -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            Список документов ({{ $tripDocuments->count() }})
                        </h4>

                        @if($tripDocuments->count() > 0)
                            <div class="space-y-3">
                                @foreach($tripDocuments as $tripDocument)
                                    <div class="bg-white dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600 {{ !$tripDocument->is_active ? 'opacity-50' : '' }}">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <!-- Title and Status -->
                                                <div class="flex items-center gap-2 mb-2">
                                                    <div class="font-semibold text-gray-900 dark:text-gray-100">
                                                        <i class="fas fa-file-alt mr-2 text-purple-500"></i>
                                                        {{ $tripDocument->title }}
                                                    </div>
                                                    @if($tripDocument->is_active)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                                                            <i class="fas fa-check-circle mr-1"></i>
                                                            Активен
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-400">
                                                            <i class="fas fa-times-circle mr-1"></i>
                                                            Неактивен
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- Price Info -->
                                                <div class="grid grid-cols-3 gap-4 text-sm mb-2">
                                                    <div class="text-gray-600 dark:text-gray-400">
                                                        <i class="fas fa-dollar-sign mr-1 text-blue-500"></i>
                                                        Цена: {{ number_format($tripDocument->price, 2) }} ₸
                                                    </div>
                                                    <div class="text-gray-600 dark:text-gray-400">
                                                        <i class="fas fa-sort-numeric-up mr-1 text-green-500"></i>
                                                        Кол-во: {{ $tripDocument->qty }}
                                                    </div>
                                                    <div class="font-semibold text-purple-700 dark:text-purple-400">
                                                        <i class="fas fa-calculator mr-1"></i>
                                                        Итого: {{ number_format($tripDocument->total_price, 2) }} ₸
                                                    </div>
                                                </div>

                                                <!-- File -->
                                                @if($tripDocument->file_url)
                                                    <div class="text-xs text-blue-600 dark:text-blue-400 mb-1">
                                                        <a href="{{ asset('storage/' . $tripDocument->file_url) }}"
                                                           target="_blank"
                                                           class="inline-flex items-center hover:underline">
                                                            <i class="fas fa-paperclip mr-1"></i>
                                                            Просмотреть файл
                                                        </a>
                                                    </div>
                                                @endif

                                                <!-- Info -->
                                                @if($tripDocument->info)
                                                    <div class="text-xs text-gray-600 dark:text-gray-400 italic">
                                                        <i class="fas fa-info-circle mr-1"></i>
                                                        {{ $tripDocument->info }}
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Actions -->
                                            @if($canEdit)
                                            <div class="ml-4 flex gap-2">
                                                <button wire:click="toggleDocumentStatus({{ $tripDocument->id }})"
                                                        class="text-{{ $tripDocument->is_active ? 'yellow' : 'green' }}-600 dark:text-{{ $tripDocument->is_active ? 'yellow' : 'green' }}-400 hover:text-{{ $tripDocument->is_active ? 'yellow' : 'green' }}-800"
                                                        title="{{ $tripDocument->is_active ? 'Деактивировать' : 'Активировать' }}">
                                                    <i class="fas fa-{{ $tripDocument->is_active ? 'toggle-on' : 'toggle-off' }}"></i>
                                                </button>
                                                <button wire:click="editDocument({{ $tripDocument->id }})"
                                                        class="text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button wire:click="deleteDocument({{ $tripDocument->id }})"
                                                        onclick="return confirm('Вы уверены, что хотите удалить этот документ?')"
                                                        class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Total Summary -->
                            <div class="mt-4 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-semibold text-purple-700 dark:text-purple-400">
                                        <i class="fas fa-coins mr-2"></i>
                                        Общая сумма всех активных документов:
                                    </span>
                                    <span class="text-xl font-bold text-purple-800 dark:text-purple-300">
                                        {{ number_format($tripDocuments->where('is_active', true)->sum('total_price'), 2) }} ₸
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-8 text-center">
                                <i class="fas fa-file-alt text-4xl text-gray-400 dark:text-gray-500 mb-2"></i>
                                <p class="text-gray-600 dark:text-gray-400">Документы не добавлены</p>
                            </div>
                        @endif
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                    <button wire:click="closeDocumentsModal"
                            class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Закрыть
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>
