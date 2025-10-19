<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                <i class="fas fa-file-invoice-dollar text-emerald-600 dark:text-emerald-400 mr-3"></i>
                Оплатные документы АВР
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Управление платежными документами актов выполненных работ
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
                    class="block w-full pl-12 pr-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                >
            </div>
        </div>

        <!-- Matches List -->
        @if($matches->count() > 0)
            <div class="space-y-6 mb-6">
                @foreach($matches as $match)
                    @php
                        $allHavePayments = $match->act_of_works->every(fn($avr) => $avr->act_of_payments->isNotEmpty());
                    @endphp

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                        <!-- Match Header -->
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 p-5 text-white">
                            <div class="flex items-center justify-between flex-wrap gap-3">
                                <div>
                                    <h2 class="text-xl font-bold mb-1">
                                        <i class="fas fa-futbol mr-2"></i>
                                        Матч #{{ $match->id }}
                                    </h2>
                                    <p class="text-emerald-100 text-sm">
                                        {{ $match->ownerClub->short_name_ru ?? 'N/A' }} vs {{ $match->guestClub->short_name_ru ?? 'N/A' }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm opacity-90">{{ \Carbon\Carbon::parse($match->start_at)->format('d.m.Y H:i') }}</p>
                                    <p class="text-xs bg-white/20 px-3 py-1 rounded mt-1">
                                        {{ $match->operation->title_ru ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Match Details -->
                        <div class="p-5 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 mb-1">Лига</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $match->league->title_ru ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 mb-1">Сезон</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $match->season->title_ru ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 mb-1">Стадион</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $match->stadium->title_ru ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- AVRs List -->
                        <div class="p-5">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fas fa-list-ul text-emerald-600 dark:text-emerald-400 mr-2"></i>
                                Акты выполненных работ ({{ $match->act_of_works->count() }})
                            </h3>

                            <div class="space-y-4">
                                @foreach($match->act_of_works as $avr)
                                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex-1">
                                                <p class="font-semibold text-gray-900 dark:text-white mb-1">
                                                    <i class="fas fa-user-tie text-emerald-500 mr-2"></i>
                                                    {{ $avr->user->last_name ?? 'N/A' }} {{ $avr->user->first_name ?? 'N/A' }}
                                                </p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    Акт №{{ $avr->act_number }} от {{ \Carbon\Carbon::parse($avr->act_date)->format('d.m.Y') }}
                                                </p>
                                            </div>
                                            <button
                                                wire:click="openViewModal({{ $avr->id }})"
                                                class="text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>

                                        <!-- Payments for this AVR -->
                                        @if($avr->act_of_payments->isNotEmpty())
                                            <div class="mt-3 space-y-2">
                                                <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                                    Платежные документы:
                                                </p>
                                                @foreach($avr->act_of_payments as $payment)
                                                    @php
                                                        // Laravel auto-decodes JSON casts
                                                        $info = is_array($payment->info) ? $payment->info : (is_string($payment->info) ? json_decode($payment->info, true) : []);
                                                    @endphp
                                                    <div class="bg-white dark:bg-gray-800 rounded p-3 text-sm flex items-center justify-between">
                                                        <div class="flex-1">
                                                            <p class="font-medium text-gray-900 dark:text-white">
                                                                №{{ $info['payment_number'] ?? 'N/A' }} от {{ isset($info['payment_date']) ? \Carbon\Carbon::parse($info['payment_date'])->format('d.m.Y') : 'N/A' }}
                                                            </p>
                                                            <p class="text-gray-600 dark:text-gray-400 text-xs mt-1">
                                                                Сумма: {{ number_format($info['payment_amount'] ?? 0, 2) }} | {{ $info['payment_method'] ?? 'N/A' }}
                                                            </p>
                                                        </div>
                                                        <button
                                                            wire:click="openEditPaymentModal({{ $payment->id }})"
                                                            class="text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 ml-2"
                                                        >
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="mt-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded p-3 text-sm text-yellow-800 dark:text-yellow-200">
                                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                                Платежные документы не добавлены
                                            </div>
                                        @endif

                                        <button
                                            wire:click="openPaymentModal({{ $avr->id }})"
                                            class="mt-3 w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors"
                                        >
                                            <i class="fas fa-plus mr-2"></i>
                                            Добавить платежный документ
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Payment Complete Button -->
                        @if($allHavePayments)
                            <div class="p-5 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-t border-gray-200 dark:border-gray-700">
                                <button
                                    wire:click="markPaymentCompleted({{ $match->id }})"
                                    wire:confirm="Подтвердить оплату по всем АВР для этого матча?"
                                    class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg"
                                >
                                    <i class="fas fa-check-double mr-2"></i>
                                    Оплата произведена
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $matches->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                <i class="fas fa-inbox text-5xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                    Нет матчей для оплаты
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    В данный момент нет матчей, ожидающих оплаты
                </p>
            </div>
        @endif
    </div>

    <!-- Payment Modal -->
    @if($showPaymentModal && $selectedAvr)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4" wire:click="closePaymentModal">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-3xl w-full max-h-[95vh] overflow-y-auto" wire:click.stop>
                <!-- Modal Header -->
                <div class="sticky top-0 bg-gradient-to-r from-emerald-600 to-teal-600 text-white p-6 rounded-t-2xl z-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold mb-1">
                                <i class="fas fa-file-invoice-dollar mr-2"></i>
                                {{ $isEditing ? 'Редактирование платежного документа' : 'Новый платежный документ' }}
                            </h2>
                            <p class="text-emerald-100 text-sm">
                                Акт №{{ $selectedAvr->act_number }} - {{ $selectedAvr->user->last_name ?? '' }} {{ $selectedAvr->user->first_name ?? '' }}
                            </p>
                        </div>
                        <button wire:click="closePaymentModal" class="text-white hover:bg-white/20 rounded-lg p-2 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="p-6 space-y-4">
                    <!-- Payment Number and Date -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Номер платежа <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="paymentForm.payment_number" class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 text-gray-900 dark:text-white">
                            @error('paymentForm.payment_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Дата платежа <span class="text-red-500">*</span>
                            </label>
                            <input type="date" wire:model="paymentForm.payment_date" class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 text-gray-900 dark:text-white">
                            @error('paymentForm.payment_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Amount and Method -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Сумма платежа <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.01" wire:model="paymentForm.payment_amount" class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 text-gray-900 dark:text-white">
                            @error('paymentForm.payment_amount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Способ оплаты <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="paymentForm.payment_method" class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 text-gray-900 dark:text-white">
                                <option value="">Выберите способ</option>
                                <option value="Банковский перевод">Банковский перевод</option>
                                <option value="Наличные">Наличные</option>
                                <option value="Электронный платеж">Электронный платеж</option>
                            </select>
                            @error('paymentForm.payment_method') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Checked By -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Проверено
                        </label>
                        <div class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white">
                            {{ auth()->user()->last_name ?? '' }} {{ auth()->user()->first_name ?? '' }}
                        </div>
                        <input type="hidden" wire:model="paymentForm.checked_by">
                        @error('paymentForm.checked_by') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Примечания</label>
                        <textarea wire:model="paymentForm.notes" rows="3" class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 text-gray-900 dark:text-white resize-none"></textarea>
                        @error('paymentForm.notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Прикрепить файлы (макс 10 МБ каждый)
                        </label>
                        <input type="file" wire:model="uploadedFiles" multiple class="block w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none">
                        @error('uploadedFiles.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

                        <div wire:loading wire:target="uploadedFiles" class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Загрузка файлов...
                        </div>
                    </div>

                    <!-- Existing Files -->
                    @if(!empty($existingFiles))
                        <div>
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Загруженные файлы:</p>
                            <div class="space-y-2">
                                @foreach($existingFiles as $index => $file)
                                    <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-file text-gray-500"></i>
                                            <a href="{{ Storage::url($file) }}" target="_blank" class="text-sm text-emerald-600 dark:text-emerald-400 hover:underline">
                                                {{ basename($file) }}
                                            </a>
                                        </div>
                                        <button wire:click="deleteFile({{ $index }})" wire:confirm="Удалить этот файл?" class="text-red-600 hover:text-red-700">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Modal Footer -->
                <div class="sticky bottom-0 bg-gray-50 dark:bg-gray-700/50 p-6 border-t border-gray-200 dark:border-gray-600 rounded-b-2xl">
                    <div class="flex gap-3">
                        <button wire:click="savePayment" wire:loading.attr="disabled" class="flex-1 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 disabled:from-gray-400 disabled:to-gray-500 text-white font-semibold py-3 px-6 rounded-lg transition-all shadow-md hover:shadow-lg">
                            <i class="fas fa-save mr-2"></i>
                            <span wire:loading.remove wire:target="savePayment">{{ $isEditing ? 'Обновить' : 'Создать' }}</span>
                            <span wire:loading wire:target="savePayment"><i class="fas fa-spinner fa-spin mr-2"></i>Сохранение...</span>
                        </button>
                        <button wire:click="closePaymentModal" class="bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-800 dark:text-white font-semibold py-3 px-6 rounded-lg transition-all">
                            <i class="fas fa-times mr-2"></i>Отмена
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- View Modal -->
    @if($showViewModal && $selectedAvr)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4" wire:click="closeViewModal">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-5xl w-full max-h-[95vh] overflow-y-auto" wire:click.stop>
                <!-- Modal Header -->
                <div class="sticky top-0 bg-gradient-to-r from-emerald-600 to-teal-600 text-white p-6 rounded-t-2xl z-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold mb-1">
                                <i class="fas fa-file-invoice mr-2"></i>
                                Просмотр АВР
                            </h2>
                            <p class="text-emerald-100 text-sm">
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
                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-xl p-5 border border-emerald-200 dark:border-emerald-800">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-user-tie text-emerald-600 dark:text-emerald-400 mr-2"></i>
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
                            Просмотр АВР
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
                            <i class="fas fa-list-ul text-emerald-600 dark:text-emerald-400 mr-2"></i>
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
                                    <tr class="bg-emerald-50 dark:bg-emerald-900/30 font-bold">
                                        <td colspan="5" class="py-3 px-2 text-right text-gray-900 dark:text-white">Общая сумма:</td>
                                        <td class="py-3 px-2 text-right text-emerald-700 dark:text-emerald-300 text-lg">
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
