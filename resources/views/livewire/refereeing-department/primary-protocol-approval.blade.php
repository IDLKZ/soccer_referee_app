<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                <i class="fas fa-clipboard-check text-blue-600 dark:text-blue-400 mr-3"></i>
                Первичная проверка протоколов
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Проверка и одобрение протоколов от судей
            </p>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg relative">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('message') }}</span>
            </div>
        @endif

        <!-- Search Bar -->
        <div class="mb-6">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Поиск по матчу, клубу или судье..."
                    class="block w-full pl-12 pr-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                >
            </div>
        </div>

        <!-- Protocols Grid -->
        @if($protocols->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                @foreach($protocols as $protocol)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden border border-gray-200 dark:border-gray-700">
                        <!-- Match Header -->
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-4 text-white">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold uppercase tracking-wide opacity-90">
                                    {{ $protocol->match->league->name_ru ?? 'N/A' }}
                                </span>
                                <span class="text-xs bg-white/20 px-2 py-1 rounded">
                                    ID: {{ $protocol->match->id }}
                                </span>
                            </div>
                            <div class="text-sm font-medium">
                                {{ \Carbon\Carbon::parse($protocol->match->start_at)->format('d.m.Y H:i') }}
                            </div>
                        </div>

                        <!-- Match Teams -->
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50">
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-900 dark:text-white">
                                        {{ $protocol->match->ownerClub->name_ru ?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="px-3 text-gray-500 dark:text-gray-400 font-bold">VS</div>
                                <div class="flex-1 text-right">
                                    <div class="font-semibold text-gray-900 dark:text-white">
                                        {{ $protocol->match->guestClub->name_ru ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 text-xs text-gray-600 dark:text-gray-400 flex items-center">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $protocol->match->stadium->name_ru ?? 'N/A' }}
                            </div>
                        </div>

                        <!-- Protocol Info -->
                        <div class="p-4 space-y-3">
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-user-tie text-gray-400 mt-1"></i>
                                <div class="flex-1">
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Судья</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $protocol->judge->user->surname ?? '' }} {{ $protocol->judge->user->name ?? '' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $protocol->requirement->type->name_ru ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>

                            @if($protocol->file_url)
                                <div class="flex items-center space-x-2 text-sm text-green-600 dark:text-green-400">
                                    <i class="fas fa-file-check"></i>
                                    <span>Файл прикреплен</span>
                                </div>
                            @endif

                            @if($protocol->info)
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Комментарий судьи</div>
                                    <div class="text-sm text-gray-700 dark:text-gray-300 line-clamp-3">
                                        {{ $protocol->info }}
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                <i class="fas fa-clock mr-1"></i>
                                Отправлен: {{ \Carbon\Carbon::parse($protocol->created_at)->format('d.m.Y H:i') }}
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-600">
                            <button
                                wire:click="openDecisionModal({{ $protocol->id }})"
                                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-2.5 px-4 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg"
                            >
                                <i class="fas fa-gavel mr-2"></i>
                                Принять решение
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $protocols->links() }}
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                    <i class="fas fa-clipboard-list text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                    Нет протоколов для проверки
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    В данный момент нет протоколов, ожидающих первичной проверки
                </p>
            </div>
        @endif
    </div>

    <!-- Decision Modal -->
    @if($showDecisionModal && $selectedProtocol)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4" wire:click="closeDecisionModal">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto" wire:click.stop>
                <!-- Modal Header -->
                <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6 rounded-t-2xl z-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold mb-1">
                                <i class="fas fa-gavel mr-2"></i>
                                Принятие решения
                            </h2>
                            <p class="text-blue-100 text-sm">
                                Протокол #{{ $selectedProtocol->id }}
                            </p>
                        </div>
                        <button
                            wire:click="closeDecisionModal"
                            class="text-white hover:bg-white/20 rounded-lg p-2 transition-colors"
                        >
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="p-6 space-y-6">
                    <!-- Match Information -->
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-futbol text-blue-600 dark:text-blue-400 mr-2"></i>
                            Информация о матче
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Лига</label>
                                <p class="text-gray-900 dark:text-white font-medium">{{ $selectedProtocol->match->league->name_ru ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Сезон</label>
                                <p class="text-gray-900 dark:text-white font-medium">{{ $selectedProtocol->match->season->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Дата и время</label>
                                <p class="text-gray-900 dark:text-white font-medium">
                                    {{ \Carbon\Carbon::parse($selectedProtocol->match->start_at)->format('d.m.Y H:i') }}
                                </p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Стадион</label>
                                <p class="text-gray-900 dark:text-white font-medium">{{ $selectedProtocol->match->stadium->name_ru ?? 'N/A' }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Команды</label>
                                <p class="text-gray-900 dark:text-white font-medium">
                                    {{ $selectedProtocol->match->ownerClub->name_ru ?? 'N/A' }}
                                    <span class="text-gray-500 mx-2">vs</span>
                                    {{ $selectedProtocol->match->guestClub->name_ru ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Judge Information -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-user-tie text-blue-600 dark:text-blue-400 mr-2"></i>
                            Информация о судье
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">ФИО</label>
                                <p class="text-gray-900 dark:text-white font-medium">
                                    {{ $selectedProtocol->judge->user->surname ?? '' }}
                                    {{ $selectedProtocol->judge->user->name ?? '' }}
                                    {{ $selectedProtocol->judge->user->patronymic ?? '' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Тип судьи</label>
                                <p class="text-gray-900 dark:text-white font-medium">{{ $selectedProtocol->requirement->type->name_ru ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Protocol Information -->
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-6 border border-purple-200 dark:border-purple-800">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-file-alt text-purple-600 dark:text-purple-400 mr-2"></i>
                            Информация о протоколе
                        </h3>

                        @if($selectedProtocol->file_url)
                            <div class="mb-4">
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide block mb-2">Прикрепленный файл</label>
                                <a href="{{ asset('storage/' . $selectedProtocol->file_url) }}"
                                   target="_blank"
                                   class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-purple-300 dark:border-purple-600 rounded-lg hover:bg-purple-50 dark:hover:bg-gray-600 transition-colors">
                                    <i class="fas fa-file-download mr-2 text-purple-600 dark:text-purple-400"></i>
                                    <span class="text-gray-900 dark:text-white font-medium">Скачать файл</span>
                                </a>
                            </div>
                        @endif

                        @if($selectedProtocol->info)
                            <div>
                                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide block mb-2">Комментарий судьи</label>
                                <div class="bg-white dark:bg-gray-700 p-4 rounded-lg border border-purple-200 dark:border-purple-700">
                                    <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $selectedProtocol->info }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="mt-4 flex items-center text-sm text-gray-600 dark:text-gray-400">
                            <i class="fas fa-clock mr-2"></i>
                            Дата создания: {{ \Carbon\Carbon::parse($selectedProtocol->created_at)->format('d.m.Y H:i') }}
                        </div>
                    </div>

                    <!-- Decision Comment -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Комментарий к решению
                            <span class="text-red-500">*</span>
                            <span class="text-xs font-normal text-gray-500 dark:text-gray-400">(обязателен при отказе)</span>
                        </label>
                        <textarea
                            wire:model="decisionComment"
                            rows="4"
                            placeholder="Введите комментарий к вашему решению..."
                            class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 resize-none"
                        ></textarea>
                        @error('decisionComment')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="sticky bottom-0 bg-gray-50 dark:bg-gray-700/50 p-6 border-t border-gray-200 dark:border-gray-600 rounded-b-2xl">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button
                            wire:click="reject"
                            wire:loading.attr="disabled"
                            class="flex-1 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 disabled:from-gray-400 disabled:to-gray-500 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg disabled:cursor-not-allowed"
                        >
                            <i class="fas fa-times-circle mr-2"></i>
                            <span wire:loading.remove wire:target="reject">Отказать</span>
                            <span wire:loading wire:target="reject">
                                <i class="fas fa-spinner fa-spin mr-2"></i>Обработка...
                            </span>
                        </button>
                        <button
                            wire:click="approve"
                            wire:loading.attr="disabled"
                            class="flex-1 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 disabled:from-gray-400 disabled:to-gray-500 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg disabled:cursor-not-allowed"
                        >
                            <i class="fas fa-check-circle mr-2"></i>
                            <span wire:loading.remove wire:target="approve">Одобрить</span>
                            <span wire:loading wire:target="approve">
                                <i class="fas fa-spinner fa-spin mr-2"></i>Обработка...
                            </span>
                        </button>
                        <button
                            wire:click="closeDecisionModal"
                            class="flex-1 sm:flex-none bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-800 dark:text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200"
                        >
                            <i class="fas fa-arrow-left mr-2"></i>
                            Назад
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
