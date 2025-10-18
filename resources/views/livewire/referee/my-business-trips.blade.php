<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                <i class="fas fa-suitcase mr-3 text-blue-600 dark:text-blue-400"></i>
                Мои командировки
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Управление вашими командировками на футбольные матчи
            </p>
        </div>

        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-400 px-4 py-3 rounded-lg flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('message') }}
            </div>
        @endif

        <!-- Error Message -->
        @if (session()->has('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-400 px-4 py-3 rounded-lg flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Tabs -->
        <div class="mb-6">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-8">
                    <!-- Tab 1: Transport Selection -->
                    <button wire:click="switchTab('transport-selection')"
                            class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'transport-selection' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        <i class="fas fa-bus mr-2"></i>
                        Выбор транспорта
                        @if($transportMatches->count() > 0)
                            <span class="ml-2 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 py-0.5 px-2.5 rounded-full text-xs font-medium">
                                {{ $transportMatches->count() }}
                            </span>
                        @endif
                    </button>

                    <!-- Tab 2: Awaiting Confirmation -->
                    <button wire:click="switchTab('awaiting-confirmation')"
                            class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'awaiting-confirmation' ? 'border-yellow-500 text-yellow-600 dark:text-yellow-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        <i class="fas fa-clock mr-2"></i>
                        Ожидают подтверждения
                        @if($awaitingConfirmation->count() > 0)
                            <span class="ml-2 bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400 py-0.5 px-2.5 rounded-full text-xs font-medium">
                                {{ $awaitingConfirmation->count() }}
                            </span>
                        @endif
                    </button>

                    <!-- Tab 3: Completed Trips -->
                    <button wire:click="switchTab('completed-trips')"
                            class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'completed-trips' ? 'border-green-500 text-green-600 dark:text-green-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        <i class="fas fa-check-circle mr-2"></i>
                        Готовые командировки
                        @if($completedTrips->count() > 0)
                            <span class="ml-2 bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 py-0.5 px-2.5 rounded-full text-xs font-medium">
                                {{ $completedTrips->count() }}
                            </span>
                        @endif
                    </button>
                </nav>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="mt-6">
            <!-- Tab 1: Transport Selection -->
            @if($activeTab === 'transport-selection')
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        Матчи, требующие выбора транспорта
                    </h2>

                    @if($transportMatches->count() > 0)
                        @foreach($transportMatches as $match)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                                <!-- Match Info -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Лига</div>
                                        <div class="font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $match->league->title_ru ?? 'Н/Д' }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Дата</div>
                                        <div class="font-semibold text-gray-900 dark:text-gray-100">
                                            {{ \Carbon\Carbon::parse($match->start_at)->format('d.m.Y H:i') }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Стадион</div>
                                        <div class="font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $match->stadium->title_ru ?? 'Н/Д' }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Teams -->
                                <div class="flex items-center justify-center mb-4 py-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <span class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                        {{ $match->ownerClub->short_name_ru ?? $match->ownerClub->title_ru ?? 'Команда 1' }}
                                    </span>
                                    <span class="mx-4 text-gray-400">vs</span>
                                    <span class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                        {{ $match->guestClub->short_name_ru ?? $match->guestClub->title_ru ?? 'Команда 2' }}
                                    </span>
                                </div>

                                <!-- Transport Selection Form -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Город отправления *
                                        </label>
                                        <select wire:model="transportForm.departure_city_id"
                                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                                            <option value="">Выберите город</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}">{{ $city->title_ru }}</option>
                                            @endforeach
                                        </select>
                                        @error('transportForm.departure_city_id')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Тип транспорта *
                                        </label>
                                        <select wire:model="transportForm.transport_type_id"
                                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                                            <option value="">Выберите тип транспорта</option>
                                            @foreach($transportTypes as $transportType)
                                                <option value="{{ $transportType->id }}">{{ $transportType->title_ru }}</option>
                                            @endforeach
                                        </select>
                                        @error('transportForm.transport_type_id')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <button wire:click="createTrip({{ $match->id }})"
                                        class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                    <i class="fas fa-plus mr-2"></i>
                                    Создать командировку
                                </button>
                            </div>
                        @endforeach
                    @else
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
                            <div class="text-gray-400 dark:text-gray-500 mb-4">
                                <i class="fas fa-check-circle text-6xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Нет доступных матчей
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                В данный момент нет матчей, требующих выбора транспорта
                            </p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Tab 2: Awaiting Confirmation -->
            @if($activeTab === 'awaiting-confirmation')
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        Командировки, ожидающие вашего подтверждения
                    </h2>

                    @if($awaitingConfirmation->count() > 0)
                        @foreach($awaitingConfirmation as $trip)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow">
                                <!-- Trip Header -->
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">
                                            {{ $trip->name }}
                                        </h3>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm text-gray-600 dark:text-gray-400">
                                            <div>
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ \Carbon\Carbon::parse($trip->match->start_at)->format('d.m.Y H:i') }}
                                            </div>
                                            <div>
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                {{ $trip->match->stadium->title_ru ?? 'Н/Д' }}
                                            </div>
                                            <div>
                                                <i class="fas fa-trophy mr-1"></i>
                                                {{ $trip->match->league->title_ru ?? 'Н/Д' }}
                                            </div>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400">
                                        <i class="fas fa-clock mr-1"></i>
                                        Ожидает подтверждения
                                    </span>
                                </div>

                                <!-- Actions -->
                                <div class="flex gap-2">
                                    <button wire:click="openConfirmationModal({{ $trip->id }})"
                                            class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                                        <i class="fas fa-check mr-2"></i>
                                        Подтвердить
                                    </button>
                                    <button wire:click="openTripDetail({{ $trip->id }})"
                                            class="inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                        <i class="fas fa-eye mr-2"></i>
                                        Детали
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
                            <div class="text-gray-400 dark:text-gray-500 mb-4">
                                <i class="fas fa-inbox text-6xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Нет командировок на подтверждение
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                У вас нет командировок, ожидающих подтверждения
                            </p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Tab 3: Completed Trips -->
            @if($activeTab === 'completed-trips')
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        Мои завершенные командировки
                    </h2>

                    @if($completedTrips->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($completedTrips as $trip)
                                <div wire:click="openTripDetail({{ $trip->id }})"
                                     class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700 cursor-pointer hover:shadow-xl transition-all transform hover:scale-105">
                                    <div class="mb-3">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Завершена
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-3">
                                        {{ $trip->name }}
                                    </h3>
                                    <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar w-5 mr-2"></i>
                                            {{ \Carbon\Carbon::parse($trip->match->start_at)->format('d.m.Y H:i') }}
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-map-marker-alt w-5 mr-2"></i>
                                            {{ $trip->match->stadium->title_ru ?? 'Н/Д' }}
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-trophy w-5 mr-2"></i>
                                            {{ $trip->match->league->title_ru ?? 'Н/Д' }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
                            <div class="text-gray-400 dark:text-gray-500 mb-4">
                                <i class="fas fa-plane text-6xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Нет завершенных командировок
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                У вас пока нет завершенных командировок
                            </p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

    </div>

    <!-- Confirmation Modal -->
    @if($showConfirmationModal && $confirmationTrip)
        <div class="fixed inset-0 bg-gray-900/75 dark:bg-gray-900/90 flex items-center justify-center z-50 p-4"
             wire:click.self="closeConfirmationModal">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden"
                 @click.stop>

                <!-- Modal Header -->
                <div class="px-6 py-4 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold flex items-center">
                            <i class="fas fa-clipboard-check mr-3"></i>
                            Подтверждение командировки
                        </h3>
                        <p class="text-sm text-yellow-100 mt-1">{{ $confirmationTrip->name }}</p>
                    </div>
                    <button wire:click="closeConfirmationModal"
                            class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 180px);">

                    <!-- Match Information -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-6">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Информация о матче</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Дата:</span>
                                <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($confirmationTrip->match->start_at)->format('d.m.Y H:i') }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Стадион:</span>
                                <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">
                                    {{ $confirmationTrip->match->stadium->title_ru ?? 'Н/Д' }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Лига:</span>
                                <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">
                                    {{ $confirmationTrip->match->league->title_ru ?? 'Н/Д' }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Сезон:</span>
                                <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">
                                    {{ $confirmationTrip->match->season->title_ru ?? 'Н/Д' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Hotels -->
                    @if($confirmationTrip->trip_hotels->count() > 0)
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center">
                                <i class="fas fa-hotel mr-2 text-blue-500"></i>
                                Отели ({{ $confirmationTrip->trip_hotels->count() }})
                            </h4>
                            <div class="space-y-2">
                                @foreach($confirmationTrip->trip_hotels as $hotel)
                                    <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg text-sm">
                                        <div class="font-medium text-gray-900 dark:text-gray-100">
                                            {{ $hotel->hotel_room->hotel->title_ru ?? 'Н/Д' }}
                                        </div>
                                        <div class="text-gray-600 dark:text-gray-400">
                                            Номер: {{ $hotel->hotel_room->number ?? 'Н/Д' }} |
                                            {{ \Carbon\Carbon::parse($hotel->check_in_at)->format('d.m.Y') }} -
                                            {{ \Carbon\Carbon::parse($hotel->check_out_at)->format('d.m.Y') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Migrations -->
                    @if($confirmationTrip->trip_migrations->count() > 0)
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center">
                                <i class="fas fa-route mr-2 text-green-500"></i>
                                Маршруты ({{ $confirmationTrip->trip_migrations->count() }})
                            </h4>
                            <div class="space-y-2">
                                @foreach($confirmationTrip->trip_migrations as $migration)
                                    <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg text-sm">
                                        <div class="font-medium text-gray-900 dark:text-gray-100">
                                            {{ $migration->transport_type->title_ru ?? 'Н/Д' }}
                                        </div>
                                        <div class="text-gray-600 dark:text-gray-400">
                                            {{ $migration->departure_city->title_ru ?? 'Н/Д' }} →
                                            {{ $migration->arrival_city->title_ru ?? 'Н/Д' }} |
                                            {{ \Carbon\Carbon::parse($migration->from_date)->format('d.m.Y H:i') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Documents -->
                    @if($confirmationTrip->trip_documents->count() > 0)
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center">
                                <i class="fas fa-file-alt mr-2 text-purple-500"></i>
                                Документы ({{ $confirmationTrip->trip_documents->count() }})
                            </h4>
                            <div class="space-y-2">
                                @foreach($confirmationTrip->trip_documents as $document)
                                    <div class="bg-purple-50 dark:bg-purple-900/20 p-3 rounded-lg text-sm">
                                        <div class="font-medium text-gray-900 dark:text-gray-100">
                                            {{ $document->title }}
                                        </div>
                                        <div class="text-gray-600 dark:text-gray-400">
                                            Сумма: {{ number_format($document->total_price, 2) }} ₸
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Comment Field -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Комментарий (обязательно при отказе)
                        </label>
                        <textarea wire:model="judge_comment"
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 dark:bg-gray-700 dark:text-gray-100"
                                  placeholder="Укажите комментарий или причину отказа..."></textarea>
                        @error('judge_comment')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                    <button wire:click="rejectTrip"
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Отклонить
                    </button>
                    <button wire:click="acceptTrip"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-check mr-2"></i>
                        Принять
                    </button>
                </div>

            </div>
        </div>
    @endif

    <!-- Trip Detail Modal -->
    @if($showTripDetailModal && $selectedTrip)
        <div class="fixed inset-0 bg-gray-900/75 dark:bg-gray-900/90 flex items-center justify-center z-50 p-4"
             wire:click.self="closeTripDetail">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden"
                 @click.stop>

                <!-- Modal Header -->
                <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold flex items-center">
                            <i class="fas fa-info-circle mr-3"></i>
                            Детали командировки
                        </h3>
                        <p class="text-sm text-blue-100 mt-1">{{ $selectedTrip->name }}</p>
                    </div>
                    <button wire:click="closeTripDetail"
                            class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 180px);">

                    <!-- Match Information -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg p-6 mb-6">
                        <h4 class="font-bold text-lg text-gray-900 dark:text-gray-100 mb-4">Информация о матче</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center">
                                <i class="fas fa-calendar w-6 text-blue-500 mr-3"></i>
                                <div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400">Дата и время</div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($selectedTrip->match->start_at)->format('d.m.Y H:i') }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt w-6 text-red-500 mr-3"></i>
                                <div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400">Стадион</div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $selectedTrip->match->stadium->title_ru ?? 'Н/Д' }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-trophy w-6 text-yellow-500 mr-3"></i>
                                <div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400">Лига</div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $selectedTrip->match->league->title_ru ?? 'Н/Д' }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar-check w-6 text-green-500 mr-3"></i>
                                <div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400">Сезон</div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $selectedTrip->match->season->title_ru ?? 'Н/Д' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hotels -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center">
                            <i class="fas fa-hotel mr-2 text-blue-500"></i>
                            Отели
                            <span class="ml-2 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 px-2 py-0.5 rounded-full text-xs">
                                {{ $selectedTrip->trip_hotels->count() }}
                            </span>
                        </h4>
                        @if($selectedTrip->trip_hotels->count() > 0)
                            <div class="space-y-3">
                                @foreach($selectedTrip->trip_hotels as $hotel)
                                    <div class="bg-white dark:bg-gray-700 border border-blue-200 dark:border-blue-800 p-4 rounded-lg">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                                    {{ $hotel->hotel_room->hotel->title_ru ?? 'Н/Д' }}
                                                </div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                                    Номер: {{ $hotel->hotel_room->number ?? 'Н/Д' }}
                                                    ({{ $hotel->hotel_room->type_ru ?? 'Тип не указан' }})
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                                    <i class="fas fa-calendar-check mr-1"></i>
                                                    Заезд: {{ \Carbon\Carbon::parse($hotel->check_in_at)->format('d.m.Y H:i') }}
                                                    <i class="fas fa-calendar-times ml-3 mr-1"></i>
                                                    Выезд: {{ \Carbon\Carbon::parse($hotel->check_out_at)->format('d.m.Y H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Отели не добавлены</p>
                        @endif
                    </div>

                    <!-- Migrations/Routes -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center">
                            <i class="fas fa-route mr-2 text-green-500"></i>
                            Маршруты
                            <span class="ml-2 bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 px-2 py-0.5 rounded-full text-xs">
                                {{ $selectedTrip->trip_migrations->count() }}
                            </span>
                        </h4>
                        @if($selectedTrip->trip_migrations->count() > 0)
                            <div class="space-y-3">
                                @foreach($selectedTrip->trip_migrations as $migration)
                                    <div class="bg-white dark:bg-gray-700 border border-green-200 dark:border-green-800 p-4 rounded-lg">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $migration->transport_type->title_ru ?? 'Н/Д' }}
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 text-sm mb-2">
                                            <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-full">
                                                {{ $migration->departure_city->title_ru ?? 'Н/Д' }}
                                            </span>
                                            <i class="fas fa-arrow-right text-gray-400"></i>
                                            <span class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 rounded-full">
                                                {{ $migration->arrival_city->title_ru ?? 'Н/Д' }}
                                            </span>
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-500">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            Отправление: {{ \Carbon\Carbon::parse($migration->from_date)->format('d.m.Y H:i') }}
                                            <i class="fas fa-calendar-check ml-3 mr-1"></i>
                                            Прибытие: {{ \Carbon\Carbon::parse($migration->to_date)->format('d.m.Y H:i') }}
                                        </div>
                                        @if($migration->info)
                                            <div class="text-xs text-gray-600 dark:text-gray-400 mt-2 italic">
                                                {{ $migration->info }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Маршруты не добавлены</p>
                        @endif
                    </div>

                    <!-- Documents -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center">
                            <i class="fas fa-file-alt mr-2 text-purple-500"></i>
                            Документы
                            <span class="ml-2 bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400 px-2 py-0.5 rounded-full text-xs">
                                {{ $selectedTrip->trip_documents->count() }}
                            </span>
                        </h4>
                        @if($selectedTrip->trip_documents->count() > 0)
                            <div class="space-y-3">
                                @foreach($selectedTrip->trip_documents as $document)
                                    <div class="bg-white dark:bg-gray-700 border border-purple-200 dark:border-purple-800 p-4 rounded-lg">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                                    {{ $document->title }}
                                                </div>
                                                <div class="grid grid-cols-3 gap-2 text-sm text-gray-600 dark:text-gray-400">
                                                    <div>Цена: {{ number_format($document->price, 2) }} ₸</div>
                                                    <div>Кол-во: {{ $document->qty }}</div>
                                                    <div class="font-semibold text-purple-600 dark:text-purple-400">
                                                        Итого: {{ number_format($document->total_price, 2) }} ₸
                                                    </div>
                                                </div>
                                                @if($document->file_url)
                                                    <a href="{{ asset('storage/' . $document->file_url) }}"
                                                       target="_blank"
                                                       class="text-xs text-blue-600 dark:text-blue-400 hover:underline mt-2 inline-block">
                                                        <i class="fas fa-paperclip mr-1"></i>
                                                        Просмотреть файл
                                                    </a>
                                                @endif
                                            </div>
                                            @if($document->is_active)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                                                    Активен
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold text-purple-700 dark:text-purple-400">
                                        Общая сумма активных документов:
                                    </span>
                                    <span class="text-xl font-bold text-purple-800 dark:text-purple-300">
                                        {{ number_format($selectedTrip->trip_documents->where('is_active', true)->sum('total_price'), 2) }} ₸
                                    </span>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Документы не добавлены</p>
                        @endif
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                    <button wire:click="closeTripDetail"
                            class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Закрыть
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>

