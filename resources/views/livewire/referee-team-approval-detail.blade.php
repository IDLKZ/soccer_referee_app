<div>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Кнопка назад -->
            <div class="mb-4">
                <button wire:click="goBack"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Назад к списку
                </button>
            </div>

            <!-- Уведомления -->
            @if (session()->has('message'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Информация о матче -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- Карточка счета матча -->
                    <div class="lg:col-span-2 bg-gradient-to-br from-blue-500 to-indigo-600 dark:from-blue-700 dark:to-indigo-800 rounded-lg shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex-1 text-center">
                                <h3 class="text-2xl font-bold">{{ $ownerClub->short_name_ru ?? $ownerClub->title_ru }}</h3>
                            </div>
                            <div class="px-8 text-center">
                                <div class="text-5xl font-bold">VS</div>
                            </div>
                            <div class="flex-1 text-center">
                                <h3 class="text-2xl font-bold">{{ $guestClub->short_name_ru ?? $guestClub->title_ru }}</h3>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 text-sm border-t border-white/20 pt-4">
                            <div class="text-center">
                                <p class="opacity-80">Дата</p>
                                <p class="font-semibold">{{ \Carbon\Carbon::parse($match->start_at)->format('d.m.Y') }}</p>
                            </div>
                            <div class="text-center">
                                <p class="opacity-80">Время</p>
                                <p class="font-semibold">{{ $match->start_at ? \Carbon\Carbon::parse($match->start_at)->format('H:i') : 'Н/Д' }}</p>
                            </div>
                            <div class="text-center">
                                <p class="opacity-80">Стадион</p>
                                <p class="font-semibold truncate">{{ $match->stadium->short_name_ru ?? $match->stadium->title_ru }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Статус и информация -->
                    <div class="space-y-4">
                        <!-- Статус операции -->
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-lg p-4">
                            <p class="text-xs uppercase tracking-wide opacity-80 mb-1">Статус</p>
                            <p class="font-bold text-lg">{{ $match->operation->title_ru }}</p>
                        </div>

                        <!-- Лига и сезон -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">
                                <i class="fas fa-trophy mr-1"></i>
                                Лига
                            </p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $match->league->title_ru }}</p>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">
                                <i class="fas fa-calendar mr-1"></i>
                                Сезон
                            </p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $match->season->title_ru }}</p>
                        </div>
                    </div>
                </div>

                <!-- Требования к судьям -->
                <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-clipboard-list mr-2 text-blue-500"></i>
                        Требования к судьям
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($match->judge_requirements as $requirement)
                            <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $requirement->judge_type->title_ru }}
                                    </span>
                                    @if($requirement->is_required)
                                        <span class="text-xs bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 px-2 py-1 rounded">
                                            Обязательно
                                        </span>
                                    @endif
                                </div>
                                @php
                                    $approvedForType = \App\Models\MatchJudge::where('match_id', $matchId)
                                        ->where('type_id', $requirement->judge_type_id)
                                        ->where('judge_response', 1)
                                        ->where('final_status', 1)
                                        ->count();
                                @endphp
                                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                    {{ $approvedForType }} / {{ $requirement->qty }}
                                </p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Утверждено / Требуется</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Кнопки действий -->
                <div class="mt-6 flex flex-wrap gap-3">
                    @if($this->hasRejectedRequiredJudge())
                        <button wire:click="sendForRevision"
                                wire:confirm="Вы уверены, что хотите отправить на доработку?"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white rounded-lg shadow-md transition-all transform hover:scale-105">
                            <i class="fas fa-redo mr-2"></i>
                            Отправить на доработку
                        </button>
                    @endif

                    @if($this->allRequiredJudgesApproved())
                        @php
                            $logistsCount = \App\Models\MatchLogist::where('match_id', $matchId)->count();
                        @endphp
                        <button wire:click="sendToNextStage"
                                wire:confirm="Вы уверены, что хотите отправить на следующий этап?"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-lg shadow-md transition-all transform hover:scale-105">
                            <i class="fas fa-check-circle mr-2"></i>
                            @if($logistsCount == 0)
                                Отправить на выбор логистов
                            @else
                                Отправить на выбор транспорта
                            @endif
                        </button>
                    @endif
                </div>
            </div>

            <!-- Вкладки с судьями -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <!-- Заголовки вкладок -->
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex flex-wrap -mb-px">
                        <!-- Вкладка: Ожидают -->
                        <button wire:click="setActiveTab('waiting')"
                                class="px-6 py-4 text-sm font-medium border-b-2 transition-colors {{ $activeTab === 'waiting' ? 'border-yellow-500 text-yellow-600 dark:text-yellow-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                            <i class="fas fa-clock mr-2"></i>
                            Ожидают
                            <span class="ml-2 px-2 py-1 text-xs rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400">
                                {{ $waitingCount }}
                            </span>
                        </button>

                        <!-- Вкладка: Утверждено -->
                        <button wire:click="setActiveTab('approved')"
                                class="px-6 py-4 text-sm font-medium border-b-2 transition-colors {{ $activeTab === 'approved' ? 'border-green-500 text-green-600 dark:text-green-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                            <i class="fas fa-check-circle mr-2"></i>
                            Утверждено
                            <span class="ml-2 px-2 py-1 text-xs rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                {{ $approvedCount }}
                            </span>
                        </button>

                        <!-- Вкладка: Отказано -->
                        <button wire:click="setActiveTab('rejected')"
                                class="px-6 py-4 text-sm font-medium border-b-2 transition-colors {{ $activeTab === 'rejected' ? 'border-red-500 text-red-600 dark:text-red-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                            <i class="fas fa-times-circle mr-2"></i>
                            Отказано
                            <span class="ml-2 px-2 py-1 text-xs rounded-full bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                                {{ $rejectedCount }}
                            </span>
                        </button>
                    </nav>
                </div>

                <!-- Содержимое вкладок -->
                <div class="p-6">
                    @if($judgesForTab->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($judgesForTab as $judge)
                                <div class="p-4 rounded-lg border-2
                                    @if($activeTab === 'waiting')
                                        bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800
                                    @elseif($activeTab === 'approved')
                                        bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800
                                    @else
                                        bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800
                                    @endif
                                    ">

                                    <!-- Информация о судье -->
                                    <div class="flex items-center mb-3">
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg mr-3">
                                            {{ substr($judge->user->first_name, 0, 1) }}{{ substr($judge->user->last_name, 0, 1) }}
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $judge->user->last_name }} {{ $judge->user->first_name }}
                                            </p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                                {{ $judge->judge_type->title_ru }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Кнопки действий -->
                                    <div class="flex gap-2 mt-3">
                                        <button wire:click="openJudgeInfo({{ $judge->id }})"
                                                class="flex-1 px-3 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors text-sm">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Инфо
                                        </button>

                                        @if($activeTab === 'waiting')
                                            <button wire:click="openApproveModal({{ $judge->id }})"
                                                    class="flex-1 px-3 py-2 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg hover:bg-green-200 dark:hover:bg-green-900/50 transition-colors text-sm">
                                                <i class="fas fa-check mr-1"></i>
                                                Принять
                                            </button>
                                            <button wire:click="openRejectModal({{ $judge->id }})"
                                                    class="flex-1 px-3 py-2 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors text-sm">
                                                <i class="fas fa-times mr-1"></i>
                                                Отклонить
                                            </button>
                                        @endif
                                    </div>

                                    @if($judge->cancel_reason)
                                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Причина отказа:</p>
                                            <p class="text-xs text-gray-900 dark:text-gray-100">{{ $judge->cancel_reason }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Пустое состояние -->
                        <div class="text-center py-12">
                            <div class="text-gray-400 dark:text-gray-500 mb-4">
                                <i class="fas fa-inbox text-5xl"></i>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400">
                                Нет судей в этой категории
                            </p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Модальное окно информации о судье -->
    @if($showJudgeInfoModal && $selectedJudge)
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Заголовок -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fas fa-user mr-2 text-blue-500"></i>
                        Информация о судье
                    </h3>
                    <button wire:click="closeModals"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Содержимое -->
                <div class="p-6 space-y-4">
                    <!-- Фото и имя -->
                    <div class="flex items-center space-x-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-3xl">
                            {{ substr($selectedJudge->user->first_name, 0, 1) }}{{ substr($selectedJudge->user->last_name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                {{ $selectedJudge->user->last_name }} {{ $selectedJudge->user->first_name }} {{ $selectedJudge->user->patronomic }}
                            </h4>
                            <p class="text-gray-600 dark:text-gray-400">{{ $selectedJudge->judge_type->title_ru }}</p>
                        </div>
                    </div>

                    <!-- Контактная информация -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Email</label>
                            <p class="text-gray-900 dark:text-gray-100">{{ $selectedJudge->user->email }}</p>
                        </div>
                        @if($selectedJudge->user->phone)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Телефон</label>
                                <p class="text-gray-900 dark:text-gray-100">
                                    @if(is_array(json_decode($selectedJudge->user->phone, true)))
                                        {{ implode(', ', json_decode($selectedJudge->user->phone, true)) }}
                                    @else
                                        {{ $selectedJudge->user->phone }}
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Статус ответа -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Статус</h5>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Ответ судьи:</span>
                                <span class="font-semibold text-green-600 dark:text-green-400">
                                    <i class="fas fa-check mr-1"></i>Согласен
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Решение директора:</span>
                                <span class="font-semibold
                                    @if($selectedJudge->final_status == 1) text-green-600 dark:text-green-400
                                    @elseif($selectedJudge->final_status == -1) text-red-600 dark:text-red-400
                                    @else text-yellow-600 dark:text-yellow-400
                                    @endif">
                                    @if($selectedJudge->final_status == 1)
                                        <i class="fas fa-check mr-1"></i>Утверждено
                                    @elseif($selectedJudge->final_status == -1)
                                        <i class="fas fa-times mr-1"></i>Отклонено
                                    @else
                                        <i class="fas fa-clock mr-1"></i>Ожидание
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Футер -->
                <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700">
                    <button wire:click="closeModals"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Закрыть
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Модальное окно утверждения -->
    @if($showApproveModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="text-center mb-4">
                        <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check text-3xl text-green-600 dark:text-green-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                            Утвердить судью?
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Вы уверены, что хотите утвердить данного судью для этого матча?
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <button wire:click="closeModals"
                                class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                            Отмена
                        </button>
                        <button wire:click="approveJudge"
                                class="flex-1 px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-lg transition-all">
                            Утвердить
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Модальное окно отклонения -->
    @if($showRejectModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
                <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fas fa-times-circle mr-2 text-red-500"></i>
                        Отклонить судью
                    </h3>
                    <button wire:click="closeModals"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <p class="text-gray-600 dark:text-gray-400">
                        Укажите причину отклонения судьи (минимум 10 символов)
                    </p>

                    <div>
                        <textarea wire:model="rejectReason"
                                  rows="4"
                                  placeholder="Опишите причину отклонения..."
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-gray-100"></textarea>
                        @error('rejectReason')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700">
                    <button wire:click="closeModals"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Отмена
                    </button>
                    <button wire:click="rejectJudge"
                            class="px-6 py-2 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white rounded-lg transition-all">
                        <i class="fas fa-times mr-2"></i>
                        Отклонить
                    </button>
                </div>
            </div>
        </div>
    @endif

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .fixed {
            animation: fadeIn 0.2s ease-out;
        }
    </style>
</div>
