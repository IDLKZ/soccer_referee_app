<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                <i class="fas fa-gavel text-purple-600 dark:text-purple-400 mr-3"></i>
                Утверждение АВР судейским комитетом
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Рассмотрение и утверждение актов выполненных работ
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
                    placeholder="Поиск по номеру акта, ID матча, ФИО судьи..."
                    class="block w-full pl-12 pr-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                >
            </div>
        </div>

        <!-- AVR Cards -->
        @if($avrs->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                @foreach($avrs as $avr)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden border border-gray-200 dark:border-gray-700">
                        <!-- Card Header -->
                        <div class="bg-gradient-to-r from-purple-500 to-pink-600 p-4 text-white">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold uppercase tracking-wide opacity-90">
                                    Акт №{{ $avr->act_number }}
                                </span>
                                <span class="text-xs bg-white/20 px-2 py-1 rounded">
                                    Матч #{{ $avr->match->id }}
                                </span>
                            </div>
                            <div class="text-sm font-medium">
                                {{ \Carbon\Carbon::parse($avr->act_date)->format('d.m.Y') }}
                            </div>
                        </div>

                        <!-- Match Info -->
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50">
                            <div class="mb-3">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Матч</p>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="font-semibold text-gray-900 dark:text-white">
                                        {{ $avr->match->ownerClub->short_name_ru ?? 'N/A' }}
                                    </span>
                                    <span class="text-gray-500 dark:text-gray-400 font-bold px-2">VS</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">
                                        {{ $avr->match->guestClub->short_name_ru ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Дата матча</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($avr->match->start_at)->format('d.m.Y H:i') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Стадион</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    <i class="fas fa-map-marker-alt text-purple-500 mr-1"></i>
                                    {{ $avr->match->stadium->title_ru ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <!-- Judge Info -->
                        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Судья</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                <i class="fas fa-user-tie text-purple-500 mr-1"></i>
                                {{ $avr->user->last_name ?? 'N/A' }} {{ $avr->user->first_name ?? 'N/A' }}
                            </p>
                        </div>

                        <!-- Contract Info -->
                        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">Договор №</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $avr->dogovor_number }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">Дата договора</p>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($avr->dogovor_date)->format('d.m.Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 space-y-2">
                            <button
                                wire:click="openViewModal({{ $avr->id }})"
                                class="w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors"
                            >
                                <i class="fas fa-eye mr-2"></i>
                                Просмотр деталей
                            </button>
                            <div class="grid grid-cols-2 gap-2">
                                <button
                                    wire:click="approve({{ $avr->id }})"
                                    wire:confirm="Утвердить этот АВР?"
                                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors"
                                >
                                    <i class="fas fa-check mr-1"></i>
                                    Принять
                                </button>
                                <button
                                    wire:click="reject({{ $avr->id }})"
                                    wire:confirm="Отклонить этот АВР? Он будет отправлен на переоформление."
                                    class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors"
                                >
                                    <i class="fas fa-times mr-1"></i>
                                    Отклонить
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $avrs->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                <i class="fas fa-inbox text-5xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                    Нет АВР на утверждении
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    В данный момент нет актов, ожидающих утверждения судейским комитетом
                </p>
            </div>
        @endif
    </div>

    <!-- View Modal -->
    @if($showViewModal && $selectedAvr)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4" wire:click="closeViewModal">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-5xl w-full max-h-[95vh] overflow-y-auto" wire:click.stop>
                <!-- Modal Header -->
                <div class="sticky top-0 bg-gradient-to-r from-purple-600 to-pink-600 text-white p-6 rounded-t-2xl z-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold mb-1">
                                <i class="fas fa-file-invoice mr-2"></i>
                                Детали АВР
                            </h2>
                            <p class="text-purple-100 text-sm">
                                Акт №{{ $selectedAvr->act_number }} от {{ \Carbon\Carbon::parse($selectedAvr->act_date)->format('d.m.Y') }}
                            </p>
                        </div>
                        <button
                            wire:click="closeViewModal"
                            class="text-white hover:bg-white/20 rounded-lg p-2 transition-colors"
                        >
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="p-6 space-y-6">
                    <!-- Match Information -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-5 border border-blue-200 dark:border-blue-800">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-futbol text-blue-600 dark:text-blue-400 mr-2"></i>
                            Информация о матче
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">ID матча</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $selectedAvr->match->id }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Дата и время</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($selectedAvr->match->start_at)->format('d.m.Y H:i') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Лига</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $selectedAvr->match->league->title_ru ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Сезон</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $selectedAvr->match->season->title_ru ?? 'N/A' }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Команды</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $selectedAvr->match->ownerClub->short_name_ru ?? 'N/A' }}
                                    <span class="text-gray-500 mx-2">vs</span>
                                    {{ $selectedAvr->match->guestClub->short_name_ru ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Стадион</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $selectedAvr->match->stadium->title_ru ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Judge Information -->
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl p-5 border border-purple-200 dark:border-purple-800">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-user-tie text-purple-600 dark:text-purple-400 mr-2"></i>
                            Информация о судье
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">ФИО судьи</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $selectedAvr->user->last_name ?? 'N/A' }} {{ $selectedAvr->user->first_name ?? 'N/A' }} {{ $selectedAvr->user->patronymic ?? '' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Email</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $selectedAvr->user->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- AVR Details -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-5 border border-green-200 dark:border-green-800">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-file-contract text-green-600 dark:text-green-400 mr-2"></i>
                            Детали АВР
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Номер акта</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $selectedAvr->act_number }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Дата акта</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($selectedAvr->act_date)->format('d.m.Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Номер договора</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $selectedAvr->dogovor_number }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Дата договора</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($selectedAvr->dogovor_date)->format('d.m.Y') }}
                                </p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Информация о заказчике</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white whitespace-pre-line">{{ $selectedAvr->customer_info }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Services List -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-5 border border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-list-ul text-purple-600 dark:text-purple-400 mr-2"></i>
                            Список работ/услуг
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-300 dark:border-gray-600">
                                        <th class="text-left py-3 px-2 text-gray-700 dark:text-gray-300 font-semibold">№</th>
                                        <th class="text-left py-3 px-2 text-gray-700 dark:text-gray-300 font-semibold">Наименование</th>
                                        <th class="text-center py-3 px-2 text-gray-700 dark:text-gray-300 font-semibold">Кол-во</th>
                                        <th class="text-right py-3 px-2 text-gray-700 dark:text-gray-300 font-semibold">Цена за ед.</th>
                                        <th class="text-center py-3 px-2 text-gray-700 dark:text-gray-300 font-semibold">Валюта</th>
                                        <th class="text-right py-3 px-2 text-gray-700 dark:text-gray-300 font-semibold">Итого</th>
                                        <th class="text-center py-3 px-2 text-gray-700 dark:text-gray-300 font-semibold">Дата выполнения</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalSum = 0;
                                    @endphp
                                    @foreach($selectedAvr->act_of_work_services as $index => $service)
                                        @php
                                            $totalSum += $service->total_price;
                                        @endphp
                                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700/70">
                                            <td class="py-3 px-2 text-gray-900 dark:text-white">{{ $index + 1 }}</td>
                                            <td class="py-3 px-2 text-gray-900 dark:text-white">{{ $service->common_service->title_ru ?? 'N/A' }}</td>
                                            <td class="py-3 px-2 text-center text-gray-900 dark:text-white">{{ $service->qty }}</td>
                                            <td class="py-3 px-2 text-right text-gray-900 dark:text-white">{{ number_format($service->price, 2) }}</td>
                                            <td class="py-3 px-2 text-center text-gray-900 dark:text-white">{{ $service->price_per }}</td>
                                            <td class="py-3 px-2 text-right font-semibold text-gray-900 dark:text-white">{{ number_format($service->total_price, 2) }}</td>
                                            <td class="py-3 px-2 text-center text-gray-900 dark:text-white">
                                                {{ \Carbon\Carbon::parse($service->executed_date)->format('d.m.Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-purple-50 dark:bg-purple-900/30 font-bold">
                                        <td colspan="5" class="py-3 px-2 text-right text-gray-900 dark:text-white">Общая сумма:</td>
                                        <td class="py-3 px-2 text-right text-purple-700 dark:text-purple-300 text-lg">
                                            {{ number_format($totalSum, 2) }}
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="sticky bottom-0 bg-gray-50 dark:bg-gray-700/50 p-6 border-t border-gray-200 dark:border-gray-600 rounded-b-2xl">
                    <button
                        wire:click="closeViewModal"
                        class="w-full bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-800 dark:text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200"
                    >
                        <i class="fas fa-times mr-2"></i>
                        Закрыть
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
