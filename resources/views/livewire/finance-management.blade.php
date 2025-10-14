<div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow-md">
        <div class="border-b border-gray-200 px-6 py-4">
            <h1 class="text-2xl font-bold text-gray-900">Финансовое управление</h1>
        </div>

        @if(session()->has('message'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 m-4 rounded">
            {{ session('message') }}
        </div>
        @endif

        <!-- Вкладки -->
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6">
                <button wire:click="$set('activeTab', 'trips')"
                        class="{{ $activeTab === 'trips' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}
                               whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-route mr-2"></i>Поездки
                </button>
                <button wire:click="$set('activeTab', 'work-acts')"
                        class="{{ $activeTab === 'work-acts' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}
                               whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-file-contract mr-2"></i>Акты работ
                </button>
                <button wire:click="$set('activeTab', 'payments')"
                        class="{{ $activeTab === 'payments' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}
                               whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-money-check-alt mr-2"></i>Платежи
                </button>
            </nav>
        </div>

        <div class="p-6">
            <!-- Вкладка "Поездки" -->
            @if($activeTab === 'trips')
            <div>
                @if($trips->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Матч
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Направление
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Даты
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Статус
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Действия
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($trips as $trip)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $trip->match->title_ru ?? 'Матч ' . $trip->match->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $trip->city->title_ru ?? '' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $trip->start_date ? $trip->start_date->format('d.m.Y') : '' }} -
                                    {{ $trip->end_date ? $trip->end_date->format('d.m.Y') : '' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                           {{ $trip->status === 'approved' ? 'bg-green-100 text-green-800' :
                                              ($trip->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ $trip->status == 'approved' ? 'Согласована' :
                                           ($trip->status == 'pending' ? 'Ожидает' : 'Черновик') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        @if(auth()->user()->can('manage-logistics', $trip))
                                        <button class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @endif
                                        @if(auth()->user()->can('create-work-act', $trip))
                                        <button class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-file-plus"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-route text-4xl mb-4"></i>
                    <p>Поездки не найдены</p>
                </div>
                @endif
            </div>
            @endif

            <!-- Вкладка "Акты работ" -->
            @if($activeTab === 'work-acts')
            <div>
                @if($workActs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Судья
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Матч
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Сумма
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Статус
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Действия
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($workActs as $workAct)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $workAct->judge->full_name ?? 'Судья ' . $workAct->judge_id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $workAct->match->title_ru ?? 'Матч ' . $workAct->match->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($workAct->amount ?? 0, 2, ',', ' ') }} ₸
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                           {{ $workAct->status === 'approved' ? 'bg-green-100 text-green-800' :
                                              ($workAct->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ $workAct->status == 'approved' ? 'Подписан' :
                                           ($workAct->status == 'pending' ? 'Ожидает' : 'Черновик') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        @if($canCreateWorkActs)
                                        <button class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @endif
                                        @if($canApprovePayments)
                                        <button class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-file-contract text-4xl mb-4"></i>
                    <p>Акты работ не найдены</p>
                </div>
                @endif
            </div>
            @endif

            <!-- Вкладка "Платежи" -->
            @if($activeTab === 'payments')
            <div>
                @if($payments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Акт работы
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Судья
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Сумма
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Статус
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Проверил
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Действия
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($payments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    #{{ $payment->workAct->id ?? $payment->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $payment->workAct->judge->full_name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($payment->amount ?? 0, 2, ',', ' ') }} ₸
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                           {{ $payment->status === 'approved' ? 'bg-green-100 text-green-800' :
                                              ($payment->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ $payment->status == 'approved' ? 'Одобрен' :
                                           ($payment->status == 'rejected' ? 'Отклонен' : 'Ожидает') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $payment->checkedBy->full_name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($canApprovePayments && $payment->status === 'pending')
                                    <div class="flex space-x-2">
                                        <button wire:click="approvePayment({{ $payment->id }})"
                                                class="text-green-600 hover:text-green-900"
                                                onclick="return confirm('Одобрить этот платеж?')">
                                            <i class="fas fa-check"></i> Одобрить
                                        </button>
                                        <button wire:click="rejectPayment({{ $payment->id }})"
                                                class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('Отклонить этот платеж?')">
                                            <i class="fas fa-times"></i> Отклонить
                                        </button>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-money-check-alt text-4xl mb-4"></i>
                    <p>Платежи не найдены</p>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>