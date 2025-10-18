<div>
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                <i class="fas fa-envelope-open-text mr-3 text-blue-500"></i>
                Мои приглашения
            </h1>
            <p class="text-gray-600 dark:text-gray-400">Управляйте приглашениями на матчи в качестве судьи</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session()->has('message'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 dark:border-green-400 p-4 rounded-lg shadow-md animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 dark:text-green-400 text-xl mr-3"></i>
                <p class="text-green-700 dark:text-green-300 font-medium">{{ session('message') }}</p>
            </div>
        </div>
        @endif

        @if(session()->has('error'))
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 dark:border-red-400 p-4 rounded-lg shadow-md animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 dark:text-red-400 text-xl mr-3"></i>
                <p class="text-red-700 dark:text-red-300 font-medium">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-t-4 border-blue-500 dark:border-blue-400 transform hover:scale-105 transition-transform duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Всего</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $statistics['total'] }}</p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900/30 p-4 rounded-full">
                        <i class="fas fa-inbox text-2xl text-blue-500 dark:text-blue-400"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-t-4 border-yellow-500 dark:border-yellow-400 transform hover:scale-105 transition-transform duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Ожидание</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $statistics['waiting'] }}</p>
                    </div>
                    <div class="bg-yellow-100 dark:bg-yellow-900/30 p-4 rounded-full">
                        <i class="fas fa-clock text-2xl text-yellow-500 dark:text-yellow-400"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-t-4 border-green-500 dark:border-green-400 transform hover:scale-105 transition-transform duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Принято</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $statistics['accepted'] }}</p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900/30 p-4 rounded-full">
                        <i class="fas fa-check-circle text-2xl text-green-500 dark:text-green-400"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-t-4 border-red-500 dark:border-red-400 transform hover:scale-105 transition-transform duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Отклонено</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $statistics['rejected'] }}</p>
                    </div>
                    <div class="bg-red-100 dark:bg-red-900/30 p-4 rounded-full">
                        <i class="fas fa-times-circle text-2xl text-red-500 dark:text-red-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-search mr-2 text-gray-400 dark:text-gray-500"></i>
                        Поиск
                    </label>
                    <input type="text" wire:model.live.debounce.500ms="search"
                           placeholder="Лига, стадион..."
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-filter mr-2 text-gray-400 dark:text-gray-500"></i>
                        Статус
                    </label>
                    <select wire:model.live="filterStatus"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="all">Все приглашения</option>
                        <option value="waiting">Ожидание ответа</option>
                        <option value="accepted">Принятые</option>
                        <option value="rejected">Отклоненные</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Invitations Grid -->
        @if($invitations->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($invitations as $invitation)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-200 border-l-4
                @if($invitation->judge_response == 0)
                    border-yellow-500 dark:border-yellow-400
                @elseif($invitation->judge_response == 1)
                    border-green-500 dark:border-green-400
                @else
                    border-red-500 dark:border-red-400
                @endif
            ">
                <!-- Card Header -->
                <div class="bg-gradient-to-r
                    @if($invitation->judge_response == 0)
                        from-yellow-500 to-orange-500
                    @elseif($invitation->judge_response == 1)
                        from-green-500 to-emerald-500
                    @else
                        from-red-500 to-pink-500
                    @endif
                    p-4 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold uppercase tracking-wide opacity-90">
                            {{ $invitation->judge_type->title_ru }}
                        </span>
                        <span class="text-xs bg-white/20 px-2 py-1 rounded-full">
                            @if($invitation->judge_response == 0)
                                <i class="fas fa-clock mr-1"></i>Ожидание
                            @elseif($invitation->judge_response == 1)
                                <i class="fas fa-check mr-1"></i>Принято
                            @else
                                <i class="fas fa-times mr-1"></i>Отклонено
                            @endif
                        </span>
                    </div>
                    <h3 class="text-lg font-bold">{{ $invitation->match->league->title_ru }}</h3>
                </div>

                <!-- Card Body -->
                <div class="p-6">
                    <div class="space-y-3 mb-4">
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                            <i class="fas fa-calendar-alt w-5 text-blue-500 dark:text-blue-400"></i>
                            <span class="ml-2">{{ \Carbon\Carbon::parse($invitation->match->match_date)->format('d.m.Y') }}</span>
                            @if($invitation->match->match_time)
                            <span class="ml-2 text-gray-400">•</span>
                            <span class="ml-2">{{ \Carbon\Carbon::parse($invitation->match->match_time)->format('H:i') }}</span>
                            @endif
                        </div>

                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                            <i class="fas fa-building w-5 text-purple-500 dark:text-purple-400"></i>
                            <span class="ml-2">{{ $invitation->match->stadium->title_ru }}</span>
                        </div>

                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                            <i class="fas fa-map-marker-alt w-5 text-red-500 dark:text-red-400"></i>
                            <span class="ml-2">{{ $invitation->match->stadium->city->title_ru }}</span>
                        </div>

                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                            <i class="fas fa-trophy w-5 text-yellow-500 dark:text-yellow-400"></i>
                            <span class="ml-2">{{ $invitation->match->season->title_ru }}</span>
                        </div>
                    </div>

                    @if($invitation->cancel_reason)
                    <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <p class="text-xs font-semibold text-red-700 dark:text-red-400 mb-1">Причина отказа:</p>
                        <p class="text-sm text-red-600 dark:text-red-300">{{ $invitation->cancel_reason }}</p>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    @if($invitation->judge_response == 0)
                    <div class="flex gap-2">
                        <button wire:click="openAcceptModal({{ $invitation->id }})"
                                class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-4 py-2 rounded-lg font-medium transition-all transform hover:scale-105 shadow-md">
                            <i class="fas fa-check mr-2"></i>
                            Принять
                        </button>
                        <button wire:click="openRejectModal({{ $invitation->id }})"
                                class="flex-1 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white px-4 py-2 rounded-lg font-medium transition-all transform hover:scale-105 shadow-md">
                            <i class="fas fa-times mr-2"></i>
                            Отклонить
                        </button>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($invitations->hasPages())
        <div class="mt-8">
            {{ $invitations->links('pagination::tailwind') }}
        </div>
        @endif
        @else
        <!-- Empty State -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
            <div class="text-gray-400 dark:text-gray-500 mb-4">
                <i class="fas fa-inbox text-6xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Нет приглашений</h3>
            <p class="text-gray-600 dark:text-gray-400">У вас пока нет приглашений на матчи</p>
        </div>
        @endif
    </div>

    <!-- Accept Modal -->
    @if($showAcceptModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 p-4 animate-fade-in">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full transform animate-scale-in">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-6 rounded-t-2xl">
                <div class="flex items-center justify-between text-white">
                    <h3 class="text-xl font-bold flex items-center">
                        <i class="fas fa-check-circle mr-3 text-2xl"></i>
                        Принять приглашение
                    </h3>
                    <button wire:click="closeModals" class="text-white/80 hover:text-white transition-colors">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <p class="text-gray-700 dark:text-gray-300 mb-6 text-lg">
                    Вы уверены, что хотите <strong>принять</strong> это приглашение на матч?
                </p>

                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6">
                    <p class="text-sm text-green-700 dark:text-green-300">
                        <i class="fas fa-info-circle mr-2"></i>
                        После принятия приглашения информация о матче будет отправлена директору департамента для финального утверждения.
                    </p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-b-2xl flex gap-3">
                <button wire:click="closeModals"
                        class="flex-1 px-4 py-3 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors font-medium">
                    Отмена
                </button>
                <button wire:click="acceptInvitation"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-lg transition-all font-medium shadow-lg">
                    <i class="fas fa-check mr-2"></i>
                    Подтвердить
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Reject Modal -->
    @if($showRejectModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 p-4 animate-fade-in">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full transform animate-scale-in">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-red-600 to-pink-600 p-6 rounded-t-2xl">
                <div class="flex items-center justify-between text-white">
                    <h3 class="text-xl font-bold flex items-center">
                        <i class="fas fa-times-circle mr-3 text-2xl"></i>
                        Отклонить приглашение
                    </h3>
                    <button wire:click="closeModals" class="text-white/80 hover:text-white transition-colors">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    Укажите причину отказа от приглашения:
                </p>

                <div class="mb-4">
                    <textarea wire:model="rejectReason"
                              rows="4"
                              placeholder="Опишите причину отказа (минимум 10 символов)..."
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none"></textarea>
                    @error('rejectReason')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                    <p class="text-sm text-yellow-700 dark:text-yellow-300">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        После отклонения статус матча изменится на "Переназначение судей".
                    </p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-b-2xl flex gap-3">
                <button wire:click="closeModals"
                        class="flex-1 px-4 py-3 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors font-medium">
                    Отмена
                </button>
                <button wire:click="rejectInvitation"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white rounded-lg transition-all font-medium shadow-lg">
                    <i class="fas fa-times mr-2"></i>
                    Отклонить
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes scale-in {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.2s ease-out;
    }

    .animate-scale-in {
        animation: scale-in 0.2s ease-out;
    }
</style>
</div>
