<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                <i class="fas fa-file-invoice-dollar mr-3 text-green-600 dark:text-green-400"></i>
                Первичная финансовая проверка АВР
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Проверка актов выполненных работ специалистом финансового департамента
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

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Ожидают проверки</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $pendingAvrs->count() }}</p>
                    </div>
                    <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-lg">
                        <i class="fas fa-hourglass-half text-2xl text-yellow-600 dark:text-yellow-400"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Общая сумма</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                            {{ number_format($pendingAvrs->sum(function($avr) { return $avr->act_of_work_services->sum('total_price'); }), 0, '.', ' ') }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">KZT</p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900 p-3 rounded-lg">
                        <i class="fas fa-money-bill-wave text-2xl text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Судей</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $pendingAvrs->unique('judge_id')->count() }}</p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-lg">
                        <i class="fas fa-users text-2xl text-blue-600 dark:text-blue-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- AVR List -->
        <div class="space-y-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                АВР, ожидающие первичной финансовой проверки
            </h2>

            @if($pendingAvrs->count() > 0)
                @foreach($pendingAvrs as $avr)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition">
                        <!-- AVR Header -->
                        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start mb-4 gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        АВР #{{ $avr->act_number }}
                                    </h3>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-400">
                                        Ожидает проверки
                                    </span>
                                </div>
                                <div class="flex flex-wrap gap-4 text-sm text-gray-600 dark:text-gray-400">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar mr-2"></i>
                                        Дата АВР: {{ \Carbon\Carbon::parse($avr->act_date)->format('d.m.Y') }}
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-user mr-2"></i>
                                        Судья: {{ $avr->user->last_name }} {{ $avr->user->first_name }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="text-right">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Сумма</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                        {{ number_format($avr->act_of_work_services->sum('total_price'), 0, '.', ' ') }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">KZT</p>
                                </div>
                            </div>
                        </div>

                        <!-- Match Info -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-4">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3">
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Лига</div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $avr->match->league->title_ru ?? 'Н/Д' }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Дата матча</div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($avr->match->start_at)->format('d.m.Y H:i') }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Стадион</div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $avr->match->stadium->title_ru ?? 'Н/Д' }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Договор</div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">
                                        № {{ $avr->dogovor_number }}
                                    </div>
                                </div>
                            </div>

                            <!-- Teams -->
                            <div class="flex items-center justify-center py-2 border-t border-gray-200 dark:border-gray-600">
                                <span class="text-base font-bold text-gray-900 dark:text-gray-100">
                                    {{ $avr->match->ownerClub->short_name_ru ?? $avr->match->ownerClub->title_ru ?? 'Команда 1' }}
                                </span>
                                <span class="mx-3 text-gray-400">vs</span>
                                <span class="text-base font-bold text-gray-900 dark:text-gray-100">
                                    {{ $avr->match->guestClub->short_name_ru ?? $avr->match->guestClub->title_ru ?? 'Команда 2' }}
                                </span>
                            </div>
                        </div>

                        <!-- Services Summary -->
                        <div class="mb-4">
                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-2 font-medium">Услуги ({{ $avr->act_of_work_services->count() }})</div>
                            <div class="bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-600">
                                @foreach($avr->act_of_work_services->take(3) as $service)
                                    <div class="px-3 py-2 border-b border-gray-200 dark:border-gray-600 last:border-b-0 flex justify-between">
                                        <span class="text-sm text-gray-900 dark:text-gray-100">
                                            {{ $service->common_service->title_ru ?? 'Услуга' }}
                                        </span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ number_format($service->total_price, 2) }} {{ $service->price_per }}
                                        </span>
                                    </div>
                                @endforeach
                                @if($avr->act_of_work_services->count() > 3)
                                    <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700/50 text-center text-xs text-gray-500 dark:text-gray-400">
                                        И еще {{ $avr->act_of_work_services->count() - 3 }} услуг(и)
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Previous Comments -->
                        @if($avr->judge_comment)
                            <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                <div class="text-xs text-blue-600 dark:text-blue-400 mb-1 font-medium">
                                    <i class="fas fa-comment mr-1"></i>Комментарий судьи:
                                </div>
                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $avr->judge_comment }}
                                </div>
                            </div>
                        @endif

                        @if($avr->control_comment)
                            <div class="mb-4 p-3 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg">
                                <div class="text-xs text-purple-600 dark:text-purple-400 mb-1 font-medium">
                                    <i class="fas fa-comment mr-1"></i>Комментарий судейского комитета:
                                </div>
                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $avr->control_comment }}
                                </div>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex flex-wrap gap-3">
                            <button wire:click="openViewModal({{ $avr->id }})"
                                    class="flex-1 sm:flex-none px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                <i class="fas fa-eye mr-2"></i>
                                Подробнее
                            </button>
                            <button wire:click="openConfirmationModal({{ $avr->id }}, 'accept')"
                                    class="flex-1 sm:flex-none px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                <i class="fas fa-check mr-2"></i>
                                Утвердить
                            </button>
                            <button wire:click="openConfirmationModal({{ $avr->id }}, 'reject')"
                                    class="flex-1 sm:flex-none px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                <i class="fas fa-times mr-2"></i>
                                Отклонить
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center border border-gray-200 dark:border-gray-700">
                    <i class="fas fa-inbox text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <p class="text-gray-500 dark:text-gray-400 text-lg">
                        Нет АВР, ожидающих первичной финансовой проверки
                    </p>
                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">
                        Все акты выполненных работ проверены
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- View Modal -->
    @if($showViewModal && $selectedAvr)
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:click.self="closeViewModal">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75"></div>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-green-600 dark:bg-green-700 px-6 py-4 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-white">Детали АВР #{{ $selectedAvr->act_number }}</h3>
                        <button wire:click="closeViewModal" class="text-white hover:text-gray-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div class="px-6 py-4 max-h-[70vh] overflow-y-auto">
                        <div class="mb-6">
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-3">Информация о судье</h4>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">ФИО</div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $selectedAvr->user->last_name }} {{ $selectedAvr->user->first_name }} {{ $selectedAvr->user->middle_name ?? '' }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Email</div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $selectedAvr->user->email }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-6">
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-3">Информация о матче</h4>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <div class="grid grid-cols-2 gap-4 mb-3">
                                    <div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Лига</div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $selectedAvr->match->league->title_ru ?? 'Н/Д' }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Сезон</div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $selectedAvr->match->season->title_ru ?? 'Н/Д' }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Дата матча</div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($selectedAvr->match->start_at)->format('d.m.Y H:i') }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Стадион</div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $selectedAvr->match->stadium->title_ru ?? 'Н/Д' }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-center py-3 border-t border-gray-200 dark:border-gray-600">
                                    <span class="text-base font-bold text-gray-900 dark:text-gray-100">{{ $selectedAvr->match->ownerClub->short_name_ru ?? $selectedAvr->match->ownerClub->title_ru }}</span>
                                    <span class="mx-4 text-gray-400">vs</span>
                                    <span class="text-base font-bold text-gray-900 dark:text-gray-100">{{ $selectedAvr->match->guestClub->short_name_ru ?? $selectedAvr->match->guestClub->title_ru }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-6">
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-3">Детали АВР</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Номер договора</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $selectedAvr->dogovor_number }}</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Дата договора</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($selectedAvr->dogovor_date)->format('d.m.Y') }}</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Номер акта</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $selectedAvr->act_number }}</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Дата акта</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($selectedAvr->act_date)->format('d.m.Y') }}</div>
                                </div>
                                <div class="col-span-2">
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Заказчик</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $selectedAvr->customer_info }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-6">
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-3">Услуги</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Услуга</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Кол-во</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Цена</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Сумма</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Дата выполнения</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($selectedAvr->act_of_work_services as $service)
                                            <tr>
                                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $service->common_service->title_ru ?? 'Н/Д' }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $service->qty }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ number_format($service->price, 2) }} {{ $service->price_per }}</td>
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ number_format($service->total_price, 2) }} {{ $service->price_per }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($service->executed_date)->format('d.m.Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <td colspan="3" class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-gray-100 text-right">Итого:</td>
                                            <td colspan="2" class="px-4 py-3 text-sm font-bold text-gray-900 dark:text-gray-100">{{ number_format($selectedAvr->act_of_work_services->sum('total_price'), 2) }} KZT</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        @if($selectedAvr->judge_comment || $selectedAvr->control_comment)
                            <div class="mb-4">
                                <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-3">Комментарии</h4>
                                @if($selectedAvr->judge_comment)
                                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded mb-2">
                                        <p class="text-xs text-blue-600 dark:text-blue-400 font-medium mb-1">Судья:</p>
                                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ $selectedAvr->judge_comment }}</p>
                                    </div>
                                @endif
                                @if($selectedAvr->control_comment)
                                    <div class="p-3 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded">
                                        <p class="text-xs text-purple-600 dark:text-purple-400 font-medium mb-1">Судейский комитет:</p>
                                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ $selectedAvr->control_comment }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 flex justify-end">
                        <button wire:click="closeViewModal" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Confirmation Modal -->
    @if($showConfirmationModal && $selectedAvr)
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:click.self="closeConfirmationModal">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75"></div>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-6 py-4 {{ $confirmationAction === 'accept' ? 'bg-green-600' : 'bg-red-600' }}">
                        <h3 class="text-lg font-semibold text-white">{{ $confirmationAction === 'accept' ? 'Утвердить АВР' : 'Отклонить АВР' }}</h3>
                    </div>
                    <div class="px-6 py-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            АВР #{{ $selectedAvr->act_number }} от судьи
                            <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $selectedAvr->user->last_name }} {{ $selectedAvr->user->first_name }}</span>
                            на сумму
                            <span class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($selectedAvr->act_of_work_services->sum('total_price'), 2) }} KZT</span>
                        </p>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Комментарий специалиста {{ $confirmationAction === 'reject' ? '(обязательно)' : '(опционально)' }}
                            </label>
                            <textarea wire:model="first_financial_comment" rows="4" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-gray-100" placeholder="{{ $confirmationAction === 'reject' ? 'Укажите причину отказа...' : 'Введите комментарий (опционально)...' }}"></textarea>
                            @error('first_financial_comment')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="p-3 {{ $confirmationAction === 'accept' ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800' : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800' }} border rounded-lg">
                            <p class="text-sm {{ $confirmationAction === 'accept' ? 'text-green-800 dark:text-green-400' : 'text-red-800 dark:text-red-400' }}">
                                @if($confirmationAction === 'accept')
                                    После утверждения АВР будет отправлен на контрольную финансовую проверку.
                                @else
                                    После отклонения АВР будет возвращен на переоформление.
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 flex justify-end gap-3">
                        <button wire:click="closeConfirmationModal" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">Отмена</button>
                        <button wire:click="confirmAction" class="px-4 py-2 {{ $confirmationAction === 'accept' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }} text-white rounded-lg transition">
                            {{ $confirmationAction === 'accept' ? 'Утвердить' : 'Отклонить' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
