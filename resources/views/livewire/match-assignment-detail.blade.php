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
                            <p class="font-semibold">{{ \Carbon\Carbon::parse($match->match_date)->format('d.m.Y') }}</p>
                        </div>
                        <div class="text-center">
                            <p class="opacity-80">Время</p>
                            <p class="font-semibold">{{ $match->match_time ? \Carbon\Carbon::parse($match->match_time)->format('H:i') : 'Н/Д' }}</p>
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
                    <div class="bg-gradient-to-r
                        @if($match->operation->value === 'match_created_waiting_referees')
                            from-yellow-500 to-orange-500
                        @elseif($match->operation->value === 'referee_reassignment')
                            from-red-500 to-pink-500
                        @else
                            from-green-500 to-teal-500
                        @endif
                        text-white rounded-lg p-4">
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
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $requirement->qty }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Требуется</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Кнопки действий -->
            <div class="mt-6 flex flex-wrap gap-3">
                @if($this->canSendInvitations())
                    <button wire:click="openInvitationModal"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg shadow-md transition-all transform hover:scale-105">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Отправить приглашение
                    </button>
                @endif

            </div>
        </div>

        <!-- Вкладки с судьями -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <!-- Заголовки вкладок -->
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex flex-wrap -mb-px">
                    <!-- Вкладка: Утверждено -->
                    <button wire:click="setActiveTab('approved')"
                            class="px-6 py-4 text-sm font-medium border-b-2 transition-colors {{ $activeTab === 'approved' ? 'border-green-500 text-green-600 dark:text-green-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        <i class="fas fa-check-circle mr-2"></i>
                        Утверждено
                        <span class="ml-2 px-2 py-1 text-xs rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                            {{ $approvedCount }}
                        </span>
                    </button>

                    <!-- Вкладка: Ожидание ответа -->
                    <button wire:click="setActiveTab('waiting_response')"
                            class="px-6 py-4 text-sm font-medium border-b-2 transition-colors {{ $activeTab === 'waiting_response' ? 'border-yellow-500 text-yellow-600 dark:text-yellow-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        <i class="fas fa-clock mr-2"></i>
                        Ожидание ответа
                        <span class="ml-2 px-2 py-1 text-xs rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400">
                            {{ $waitingResponseCount }}
                        </span>
                    </button>

                    <!-- Вкладка: Ожидание директора -->
                    <button wire:click="setActiveTab('waiting_director')"
                            class="px-6 py-4 text-sm font-medium border-b-2 transition-colors {{ $activeTab === 'waiting_director' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        <i class="fas fa-user-tie mr-2"></i>
                        Ожидание директора
                        <span class="ml-2 px-2 py-1 text-xs rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400">
                            {{ $waitingDirectorCount }}
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
                                @if($activeTab === 'approved')
                                    bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800
                                @elseif($activeTab === 'waiting_response')
                                    bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800
                                @elseif($activeTab === 'waiting_director')
                                    bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800
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

                                <!-- Статус -->
                                <div class="space-y-2 text-xs">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Ответ судьи:</span>
                                        <span class="font-semibold
                                            @if($judge->judge_response == 1) text-green-600 dark:text-green-400
                                            @elseif($judge->judge_response == -1) text-red-600 dark:text-red-400
                                            @else text-yellow-600 dark:text-yellow-400
                                            @endif">
                                            @if($judge->judge_response == 1)
                                                <i class="fas fa-check mr-1"></i>Согласен
                                            @elseif($judge->judge_response == -1)
                                                <i class="fas fa-times mr-1"></i>Отказ
                                            @else
                                                <i class="fas fa-clock mr-1"></i>Ожидание
                                            @endif
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Решение директора:</span>
                                        <span class="font-semibold
                                            @if($judge->final_status == 1) text-green-600 dark:text-green-400
                                            @elseif($judge->final_status == -1) text-red-600 dark:text-red-400
                                            @else text-yellow-600 dark:text-yellow-400
                                            @endif">
                                            @if($judge->final_status == 1)
                                                <i class="fas fa-check mr-1"></i>Утверждено
                                            @elseif($judge->final_status == -1)
                                                <i class="fas fa-times mr-1"></i>Отклонено
                                            @else
                                                <i class="fas fa-clock mr-1"></i>Ожидание
                                            @endif
                                        </span>
                                    </div>

                                    @if($judge->cancel_reason)
                                        <div class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-700">
                                            <p class="text-gray-600 dark:text-gray-400 mb-1">Причина отказа:</p>
                                            <p class="text-gray-900 dark:text-gray-100">{{ $judge->cancel_reason }}</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Кнопка отправки директору для конкретной заявки -->
                                @if($this->canSubmitToDirector($judge->id))
                                    <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                        <button wire:click="submitSingleToDirector({{ $judge->id }})"
                                                wire:confirm="Отправить заявку этого судьи на рассмотрение директору?"
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white text-sm rounded-md shadow-sm transition-all transform hover:scale-105">
                                            <i class="fas fa-paper-plane mr-2"></i>
                                            Отправить директору
                                        </button>
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

    <!-- Модальное окно отправки приглашения -->
    @if($showInvitationModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
                <!-- Заголовок модального окна -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fas fa-paper-plane mr-2 text-blue-500"></i>
                        Отправить приглашение судье
                    </h3>
                    <button wire:click="closeInvitationModal"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Содержимое модального окна -->
                <div class="p-6 space-y-4">
                    <!-- Выбор типа судьи -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Тип судьи <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="selectedJudgeTypeId"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                            <option value="">Выберите тип судьи</option>
                            @foreach($judgeTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->title_ru }}</option>
                            @endforeach
                        </select>
                        @error('selectedJudgeTypeId')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Выбор судьи -->
                    @if($selectedJudgeTypeId && count($availableJudges) > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Судья <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="selectedJudgeId"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100">
                                <option value="">Выберите судью</option>
                                @foreach($availableJudges as $availableJudge)
                                    <option value="{{ $availableJudge->id }}">
                                        {{ $availableJudge->last_name }} {{ $availableJudge->first_name }} {{ $availableJudge->patronomic }}
                                    </option>
                                @endforeach
                            </select>
                            @error('selectedJudgeId')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    @elseif($selectedJudgeTypeId && count($availableJudges) === 0)
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 text-yellow-700 dark:text-yellow-400 px-4 py-3 rounded-lg text-sm">
                            <i class="fas fa-info-circle mr-2"></i>
                            Нет доступных судей для этого типа
                        </div>
                    @endif

                    @error('general')
                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg text-sm">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Кнопки модального окна -->
                <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700">
                    <button wire:click="closeInvitationModal"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Отмена
                    </button>
                    <button wire:click="sendInvitation"
                            class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg transition-all">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Отправить
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
