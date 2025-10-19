<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                <i class="fas fa-file-invoice text-indigo-600 dark:text-indigo-400 mr-3"></i>
                Управление АВР
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Создание и управление Актами выполненных работ
            </p>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg relative">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('message') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg relative">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Tabs Navigation -->
        <div class="mb-6 overflow-x-auto">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-4" aria-label="Tabs">
                    <button
                        wire:click="setActiveTab('processing')"
                        class="@if($activeTab === 'processing') border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                    >
                        <i class="fas fa-edit mr-2"></i>
                        Оформление АВР
                    </button>
                    <button
                        wire:click="setActiveTab('rework')"
                        class="@if($activeTab === 'rework') border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                    >
                        <i class="fas fa-redo mr-2"></i>
                        Доработка
                    </button>
                    <button
                        wire:click="setActiveTab('judge_confirmation')"
                        class="@if($activeTab === 'judge_confirmation') border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                    >
                        <i class="fas fa-user-check mr-2"></i>
                        Подтверждение судьи
                    </button>
                    <button
                        wire:click="setActiveTab('committee_approval')"
                        class="@if($activeTab === 'committee_approval') border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                    >
                        <i class="fas fa-gavel mr-2"></i>
                        Комитет
                    </button>
                    <button
                        wire:click="setActiveTab('primary_financial')"
                        class="@if($activeTab === 'primary_financial') border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                    >
                        <i class="fas fa-dollar-sign mr-2"></i>
                        Первичная фин.
                    </button>
                    <button
                        wire:click="setActiveTab('control_financial')"
                        class="@if($activeTab === 'control_financial') border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                    >
                        <i class="fas fa-coins mr-2"></i>
                        Контрольная фин.
                    </button>
                    <button
                        wire:click="setActiveTab('completed')"
                        class="@if($activeTab === 'completed') border-indigo-500 text-indigo-600 dark:text-indigo-400 @else border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                    >
                        <i class="fas fa-check-circle mr-2"></i>
                        Завершенные
                    </button>
                </nav>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Поиск по ID матча или клубу..."
                    class="block w-full pl-12 pr-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                >
            </div>
        </div>

        <!-- Tab Content -->
        <div>
            @if($activeTab === 'processing')
                <!-- Tab 1: Оформление АВР -->
                @if(isset($matches) && $matches->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                        @foreach($matches as $match)
                            @php
                                $createdWaitingOp = \App\Models\Operation::where('value', 'avr_created_waiting_processing')->first();
                                $processingOp = \App\Models\Operation::where('value', 'avr_processing')->first();

                                $existingAvrs = \App\Models\ActOfWork::with(['user', 'operation'])
                                    ->where('match_id', $match->id)
                                    ->whereIn('operation_id', [$createdWaitingOp?->id, $processingOp?->id])
                                    ->get();

                                $approvedJudges = \App\Models\MatchJudge::where('match_id', $match->id)
                                    ->where('judge_response', 1)
                                    ->where('final_status', 1)
                                    ->count();
                                $avrCount = $existingAvrs->count();
                            @endphp
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden border border-gray-200 dark:border-gray-700">
                                <!-- Match Header -->
                                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-4 text-white">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold uppercase tracking-wide opacity-90">
                                            {{ $match->league->title_ru ?? 'N/A' }}
                                        </span>
                                        <span class="text-xs bg-white/20 px-2 py-1 rounded">
                                            ID: {{ $match->id }}
                                        </span>
                                    </div>
                                    <div class="text-sm font-medium">
                                        {{ \Carbon\Carbon::parse($match->start_at)->format('d.m.Y H:i') }}
                                    </div>
                                </div>

                                <!-- Match Teams -->
                                <div class="p-4 bg-gray-50 dark:bg-gray-700/50">
                                    <div class="flex items-center justify-between text-sm mb-3">
                                        <div class="flex-1">
                                            <div class="font-semibold text-gray-900 dark:text-white">
                                                {{ $match->ownerClub->short_name_ru ?? 'N/A' }}
                                            </div>
                                        </div>
                                        <div class="px-3 text-gray-500 dark:text-gray-400 font-bold">VS</div>
                                        <div class="flex-1 text-right">
                                            <div class="font-semibold text-gray-900 dark:text-white">
                                                {{ $match->guestClub->short_name_ru ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400 flex items-center">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        {{ $match->stadium->title_ru ?? 'N/A' }}
                                    </div>
                                </div>

                                <!-- AVR Info -->
                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">АВР создано:</span>
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $avrCount }} из {{ $approvedJudges }}</span>
                                    </div>

                                    @if($existingAvrs->isNotEmpty())
                                        <div class="mb-3 space-y-2">
                                            @foreach($existingAvrs as $avr)
                                                <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg border border-blue-200 dark:border-blue-800">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <div class="flex-1">
                                                            <span class="text-sm font-medium text-gray-900 dark:text-white block">
                                                                {{ $avr->user->last_name ?? 'N/A' }} {{ $avr->user->first_name ?? 'N/A' }}
                                                            </span>
                                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                                Акт №{{ $avr->act_number }}
                                                            </span>
                                                        </div>
                                                        @if($avr->operation->value === 'avr_created_waiting_processing')
                                                            <span class="text-xs bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 px-2 py-1 rounded font-medium">
                                                                Черновик
                                                            </span>
                                                        @elseif($avr->operation->value === 'avr_processing')
                                                            <span class="text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 px-2 py-1 rounded font-medium">
                                                                В процессе
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="flex gap-2 mb-2">
                                                        <button
                                                            wire:click="openViewModal({{ $avr->id }})"
                                                            class="flex-1 bg-gray-600 hover:bg-gray-700 text-white text-xs font-semibold py-2 px-3 rounded transition-colors"
                                                        >
                                                            <i class="fas fa-eye mr-1"></i>
                                                            Просмотр
                                                        </button>
                                                        <button
                                                            wire:click="openEditModal({{ $avr->id }})"
                                                            class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold py-2 px-3 rounded transition-colors"
                                                        >
                                                            <i class="fas fa-edit mr-1"></i>
                                                            Редактировать
                                                        </button>
                                                    </div>
                                                    <button
                                                        wire:click="sendForReview({{ $avr->id }})"
                                                        wire:confirm="Отправить АВР на рассмотрение судье?"
                                                        class="w-full bg-green-600 hover:bg-green-700 text-white text-xs font-semibold py-2 px-3 rounded transition-colors"
                                                    >
                                                        <i class="fas fa-paper-plane mr-1"></i>
                                                        Отправить
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <button
                                        wire:click="openCreateModal({{ $match->id }})"
                                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold py-2.5 px-4 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg"
                                    >
                                        <i class="fas fa-plus mr-2"></i>
                                        Создать АВР
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $matches->links() }}
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                        <i class="fas fa-inbox text-5xl text-gray-400 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                            Нет матчей для оформления
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            В данный момент нет матчей, ожидающих оформления АВР
                        </p>
                    </div>
                @endif

            @elseif($activeTab === 'rework')
                <!-- Tab 2: Возвращенные на доработку -->
                @if(isset($avrs) && $avrs->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                        @foreach($avrs as $avr)
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                                <div class="bg-gradient-to-r from-orange-500 to-red-600 p-4 text-white">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold">Матч #{{ $avr->match->id }}</span>
                                        <span class="text-xs bg-white/20 px-2 py-1 rounded">Доработка</span>
                                    </div>
                                </div>

                                <div class="p-4">
                                    <p class="text-sm text-gray-900 dark:text-white mb-2">
                                        <strong>Судья:</strong> {{ $avr->user->last_name ?? 'N/A' }} {{ $avr->user->first_name ?? 'N/A' }}
                                    </p>
                                    <p class="text-sm text-gray-900 dark:text-white mb-2">
                                        <strong>Матч:</strong> {{ $avr->match->ownerClub->short_name_ru ?? 'N/A' }} vs {{ $avr->match->guestClub->short_name_ru ?? 'N/A' }}
                                    </p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-4">
                                        Акт №{{ $avr->act_number }} от {{ \Carbon\Carbon::parse($avr->act_date)->format('d.m.Y') }}
                                    </p>

                                    <div class="flex gap-2 mb-2">
                                        <button
                                            wire:click="openViewModal({{ $avr->id }})"
                                            class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors"
                                        >
                                            <i class="fas fa-eye mr-2"></i>
                                            Просмотр
                                        </button>
                                        <button
                                            wire:click="openEditModal({{ $avr->id }})"
                                            class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors"
                                        >
                                            <i class="fas fa-edit mr-2"></i>
                                            Редактировать
                                        </button>
                                    </div>
                                    <button
                                        wire:click="resendForReview({{ $avr->id }})"
                                        wire:confirm="Отправить АВР на повторное рассмотрение?"
                                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors"
                                    >
                                        <i class="fas fa-paper-plane mr-2"></i>
                                        Переотправить
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $avrs->links() }}
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                        <i class="fas fa-check-circle text-5xl text-green-400 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                            Нет АВР на доработке
                        </h3>
                    </div>
                @endif

            @elseif($activeTab === 'judge_confirmation')
                <!-- Tab 3: Ожидание подтверждения судьи -->
                @if(isset($avrs) && $avrs->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                        @foreach($avrs as $avr)
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                                <div class="bg-gradient-to-r from-blue-500 to-cyan-600 p-4 text-white">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold">Матч #{{ $avr->match->id }}</span>
                                        <span class="text-xs bg-white/20 px-2 py-1 rounded">Ожидание</span>
                                    </div>
                                </div>

                                <div class="p-4">
                                    <p class="text-sm text-gray-900 dark:text-white mb-2">
                                        <strong>Судья:</strong> {{ $avr->user->last_name ?? 'N/A' }} {{ $avr->user->first_name ?? 'N/A' }}
                                    </p>
                                    <p class="text-sm text-gray-900 dark:text-white mb-2">
                                        <strong>Матч:</strong> {{ $avr->match->ownerClub->short_name_ru ?? 'N/A' }} vs {{ $avr->match->guestClub->short_name_ru ?? 'N/A' }}
                                    </p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-4">
                                        Акт №{{ $avr->act_number }} от {{ \Carbon\Carbon::parse($avr->act_date)->format('d.m.Y') }}
                                    </p>

                                    <button
                                        wire:click="openViewModal({{ $avr->id }})"
                                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors"
                                    >
                                        <i class="fas fa-eye mr-2"></i>
                                        Просмотр
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $avrs->links() }}
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                        <i class="fas fa-hourglass-half text-5xl text-gray-400 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                            Нет АВР в ожидании подтверждения
                        </h3>
                    </div>
                @endif

            @elseif($activeTab === 'committee_approval')
                <!-- Tab 4: Утверждение комитетом -->
                @if(isset($avrs) && $avrs->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                        @foreach($avrs as $avr)
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                                <div class="bg-gradient-to-r from-purple-500 to-pink-600 p-4 text-white">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold">Матч #{{ $avr->match->id }}</span>
                                        <span class="text-xs bg-white/20 px-2 py-1 rounded">Комитет</span>
                                    </div>
                                </div>

                                <div class="p-4">
                                    <p class="text-sm text-gray-900 dark:text-white mb-2">
                                        <strong>Судья:</strong> {{ $avr->user->last_name ?? 'N/A' }} {{ $avr->user->first_name ?? 'N/A' }}
                                    </p>
                                    <p class="text-sm text-gray-900 dark:text-white mb-2">
                                        <strong>Матч:</strong> {{ $avr->match->ownerClub->short_name_ru ?? 'N/A' }} vs {{ $avr->match->guestClub->short_name_ru ?? 'N/A' }}
                                    </p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-4">
                                        Акт №{{ $avr->act_number }} от {{ \Carbon\Carbon::parse($avr->act_date)->format('d.m.Y') }}
                                    </p>

                                    <button
                                        wire:click="openViewModal({{ $avr->id }})"
                                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors"
                                    >
                                        <i class="fas fa-eye mr-2"></i>
                                        Просмотр
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $avrs->links() }}
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                        <i class="fas fa-gavel text-5xl text-gray-400 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                            Нет АВР на утверждении комитета
                        </h3>
                    </div>
                @endif

            @elseif($activeTab === 'primary_financial')
                <!-- Tab 5: Первичная финансовая проверка -->
                @if(isset($avrs) && $avrs->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                        @foreach($avrs as $avr)
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                                <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-4 text-white">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold">Матч #{{ $avr->match->id }}</span>
                                        <span class="text-xs bg-white/20 px-2 py-1 rounded">Первичная</span>
                                    </div>
                                </div>

                                <div class="p-4">
                                    <p class="text-sm text-gray-900 dark:text-white mb-2">
                                        <strong>Судья:</strong> {{ $avr->user->last_name ?? 'N/A' }} {{ $avr->user->first_name ?? 'N/A' }}
                                    </p>
                                    <p class="text-sm text-gray-900 dark:text-white mb-2">
                                        <strong>Матч:</strong> {{ $avr->match->ownerClub->short_name_ru ?? 'N/A' }} vs {{ $avr->match->guestClub->short_name_ru ?? 'N/A' }}
                                    </p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-4">
                                        Акт №{{ $avr->act_number }} от {{ \Carbon\Carbon::parse($avr->act_date)->format('d.m.Y') }}
                                    </p>

                                    <button
                                        wire:click="openViewModal({{ $avr->id }})"
                                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors"
                                    >
                                        <i class="fas fa-eye mr-2"></i>
                                        Просмотр
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $avrs->links() }}
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                        <i class="fas fa-dollar-sign text-5xl text-gray-400 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                            Нет АВР на первичной проверке
                        </h3>
                    </div>
                @endif

            @elseif($activeTab === 'control_financial')
                <!-- Tab 6: Контрольная финансовая проверка -->
                @if(isset($avrs) && $avrs->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                        @foreach($avrs as $avr)
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                                <div class="bg-gradient-to-r from-yellow-500 to-orange-600 p-4 text-white">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold">Матч #{{ $avr->match->id }}</span>
                                        <span class="text-xs bg-white/20 px-2 py-1 rounded">Контрольная</span>
                                    </div>
                                </div>

                                <div class="p-4">
                                    <p class="text-sm text-gray-900 dark:text-white mb-2">
                                        <strong>Судья:</strong> {{ $avr->user->last_name ?? 'N/A' }} {{ $avr->user->first_name ?? 'N/A' }}
                                    </p>
                                    <p class="text-sm text-gray-900 dark:text-white mb-2">
                                        <strong>Матч:</strong> {{ $avr->match->ownerClub->short_name_ru ?? 'N/A' }} vs {{ $avr->match->guestClub->short_name_ru ?? 'N/A' }}
                                    </p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-4">
                                        Акт №{{ $avr->act_number }} от {{ \Carbon\Carbon::parse($avr->act_date)->format('d.m.Y') }}
                                    </p>

                                    <button
                                        wire:click="openViewModal({{ $avr->id }})"
                                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors"
                                    >
                                        <i class="fas fa-eye mr-2"></i>
                                        Просмотр
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $avrs->links() }}
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                        <i class="fas fa-coins text-5xl text-gray-400 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                            Нет АВР на контрольной проверке
                        </h3>
                    </div>
                @endif

            @elseif($activeTab === 'completed')
                <!-- Tab 7: Завершенные АВР -->
                @if(isset($avrs) && $avrs->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                        @foreach($avrs as $avr)
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                                <div class="bg-gradient-to-r from-green-500 to-teal-600 p-4 text-white">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold">Матч #{{ $avr->match->id }}</span>
                                        <span class="text-xs bg-white/20 px-2 py-1 rounded">
                                            <i class="fas fa-check-circle mr-1"></i>Завершен
                                        </span>
                                    </div>
                                </div>

                                <div class="p-4">
                                    <p class="text-sm text-gray-900 dark:text-white mb-2">
                                        <strong>Судья:</strong> {{ $avr->user->last_name ?? 'N/A' }} {{ $avr->user->first_name ?? 'N/A' }}
                                    </p>
                                    <p class="text-sm text-gray-900 dark:text-white mb-2">
                                        <strong>Матч:</strong> {{ $avr->match->ownerClub->short_name_ru ?? 'N/A' }} vs {{ $avr->match->guestClub->short_name_ru ?? 'N/A' }}
                                    </p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">
                                        Акт №{{ $avr->act_number }} от {{ \Carbon\Carbon::parse($avr->act_date)->format('d.m.Y') }}
                                    </p>
                                    <p class="text-xs text-green-600 dark:text-green-400 mb-4">
                                        <i class="fas fa-check-double mr-1"></i>
                                        Утвержден {{ \Carbon\Carbon::parse($avr->updated_at)->format('d.m.Y') }}
                                    </p>

                                    <button
                                        wire:click="openViewModal({{ $avr->id }})"
                                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors"
                                    >
                                        <i class="fas fa-eye mr-2"></i>
                                        Просмотр
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $avrs->links() }}
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                        <i class="fas fa-check-circle text-5xl text-gray-400 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                            Нет завершенных АВР
                        </h3>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- AVR Modal -->
    @if($showAvrModal && $selectedMatch)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4" wire:click="closeAvrModal">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-6xl w-full max-h-[95vh] overflow-y-auto" wire:click.stop>
                <!-- Modal Header -->
                <div class="sticky top-0 bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6 rounded-t-2xl z-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold mb-1">
                                <i class="fas fa-file-invoice mr-2"></i>
                                {{ $isEditing ? 'Редактирование АВР' : 'Создание АВР' }}
                            </h2>
                            <p class="text-indigo-100 text-sm">
                                Матч #{{ $selectedMatch->id }}: {{ $selectedMatch->ownerClub->short_name_ru ?? 'N/A' }} vs {{ $selectedMatch->guestClub->short_name_ru ?? 'N/A' }}
                            </p>
                        </div>
                        <button
                            wire:click="closeAvrModal"
                            class="text-white hover:bg-white/20 rounded-lg p-2 transition-colors"
                        >
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="p-6 space-y-6">
                    <!-- Judge Selection -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Судья <span class="text-red-500">*</span>
                        </label>
                        <select
                            wire:model="avrForm.judge_id"
                            class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 text-gray-900 dark:text-white"
                        >
                            <option value="">Выберите судью</option>
                            @foreach($availableJudges as $judge)
                                <option value="{{ $judge->judge_id }}">
                                    {{ $judge->user->last_name ?? '' }} {{ $judge->user->first_name ?? '' }} - {{ $judge->judge_type->title_ru ?? '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('avrForm.judge_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Customer Info -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Информация о заказчике <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            wire:model="avrForm.customer_info"
                            rows="3"
                            placeholder="Юридические данные заказчика..."
                            class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 text-gray-900 dark:text-white resize-none"
                        ></textarea>
                        @error('avrForm.customer_info') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Contract Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Номер договора <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model="avrForm.dogovor_number"
                                class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 text-gray-900 dark:text-white"
                            >
                            @error('avrForm.dogovor_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Дата договора <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                wire:model="avrForm.dogovor_date"
                                class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 text-gray-900 dark:text-white"
                            >
                            @error('avrForm.dogovor_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Номер акта <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model="avrForm.act_number"
                                class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 text-gray-900 dark:text-white"
                            >
                            @error('avrForm.act_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Дата акта <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                wire:model="avrForm.act_date"
                                class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 text-gray-900 dark:text-white"
                            >
                            @error('avrForm.act_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Services Section -->
                    <div class="border-t border-gray-200 dark:border-gray-600 pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                <i class="fas fa-list mr-2 text-indigo-600 dark:text-indigo-400"></i>
                                Список работ/услуг
                            </h3>
                            <button
                                type="button"
                                wire:click="addService"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors"
                            >
                                <i class="fas fa-plus mr-2"></i>
                                Добавить услугу
                            </button>
                        </div>

                        @if(count($services) > 0)
                            <div class="space-y-4">
                                @foreach($services as $index => $service)
                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                                        <div class="flex items-start justify-between mb-3">
                                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Услуга #{{ $index + 1 }}</h4>
                                            <button
                                                type="button"
                                                wire:click="removeService({{ $index }})"
                                                class="text-red-600 hover:text-red-700 dark:text-red-400"
                                            >
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                            <div class="lg:col-span-2">
                                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Услуга <span class="text-red-500">*</span></label>
                                                <select
                                                    wire:model="services.{{ $index }}.service_id"
                                                    class="w-full px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 text-gray-900 dark:text-white"
                                                >
                                                    <option value="">Выберите услугу</option>
                                                    @foreach($availableServices as $availService)
                                                        <option value="{{ $availService->id }}">{{ $availService->title_ru }}</option>
                                                    @endforeach
                                                </select>
                                                @error("services.{$index}.service_id") <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                            </div>

                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Валюта <span class="text-red-500">*</span></label>
                                                <select
                                                    wire:model="services.{{ $index }}.price_per"
                                                    class="w-full px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 text-gray-900 dark:text-white"
                                                >
                                                    <option value="KZT">KZT</option>
                                                    <option value="USD">USD</option>
                                                    <option value="EUR">EUR</option>
                                                    <option value="RUB">RUB</option>
                                                </select>
                                                @error("services.{$index}.price_per") <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                            </div>

                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Количество <span class="text-red-500">*</span></label>
                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    wire:model="services.{{ $index }}.qty"
                                                    wire:change="updateServiceTotal({{ $index }})"
                                                    class="w-full px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 text-gray-900 dark:text-white"
                                                >
                                                @error("services.{$index}.qty") <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                            </div>

                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Цена за ед. <span class="text-red-500">*</span></label>
                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    wire:model="services.{{ $index }}.price"
                                                    wire:change="updateServiceTotal({{ $index }})"
                                                    class="w-full px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 text-gray-900 dark:text-white"
                                                >
                                                @error("services.{$index}.price") <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                            </div>

                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Итого</label>
                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    wire:model="services.{{ $index }}.total_price"
                                                    readonly
                                                    class="w-full px-3 py-2 text-sm bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white"
                                                >
                                            </div>

                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Дата выполнения <span class="text-red-500">*</span></label>
                                                <input
                                                    type="date"
                                                    wire:model="services.{{ $index }}.executed_date"
                                                    class="w-full px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 text-gray-900 dark:text-white"
                                                >
                                                @error("services.{$index}.executed_date") <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 p-4 rounded-lg text-center">
                                <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 mr-2"></i>
                                <span class="text-yellow-800 dark:text-yellow-300">Необходимо добавить хотя бы одну услугу</span>
                            </div>
                        @endif
                        @error('services') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="sticky bottom-0 bg-gray-50 dark:bg-gray-700/50 p-6 border-t border-gray-200 dark:border-gray-600 rounded-b-2xl">
                    <div class="flex gap-3">
                        <button
                            wire:click="saveAvr"
                            wire:loading.attr="disabled"
                            class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 disabled:from-gray-400 disabled:to-gray-500 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg disabled:cursor-not-allowed"
                        >
                            <i class="fas fa-save mr-2"></i>
                            <span wire:loading.remove wire:target="saveAvr">{{ $isEditing ? 'Обновить АВР' : 'Создать АВР' }}</span>
                            <span wire:loading wire:target="saveAvr">
                                <i class="fas fa-spinner fa-spin mr-2"></i>Сохранение...
                            </span>
                        </button>
                        <button
                            wire:click="closeAvrModal"
                            class="bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-800 dark:text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200"
                        >
                            <i class="fas fa-times mr-2"></i>
                            Отмена
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
                <div class="sticky top-0 bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6 rounded-t-2xl z-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold mb-1">
                                <i class="fas fa-file-invoice mr-2"></i>
                                Просмотр АВР
                            </h2>
                            <p class="text-indigo-100 text-sm">
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
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $selectedMatch->id }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Дата и время</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($selectedMatch->start_at)->format('d.m.Y H:i') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Лига</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $selectedMatch->league->title_ru ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Сезон</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $selectedMatch->season->title_ru ?? 'N/A' }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Команды</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $selectedMatch->ownerClub->short_name_ru ?? 'N/A' }}
                                    <span class="text-gray-500 mx-2">vs</span>
                                    {{ $selectedMatch->guestClub->short_name_ru ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Стадион</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $selectedMatch->stadium->title_ru ?? 'N/A' }}</p>
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
                            <div class="md:col-span-2">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Текущий статус</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $selectedAvr->operation->title_ru ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Services List -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-5 border border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-list-ul text-indigo-600 dark:text-indigo-400 mr-2"></i>
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
                                    <tr class="bg-indigo-50 dark:bg-indigo-900/30 font-bold">
                                        <td colspan="5" class="py-3 px-2 text-right text-gray-900 dark:text-white">Общая сумма:</td>
                                        <td class="py-3 px-2 text-right text-indigo-700 dark:text-indigo-300 text-lg">
                                            {{ number_format($totalSum, 2) }}
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Status Information -->
                    <div class="bg-gradient-to-br from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 rounded-xl p-5 border border-yellow-200 dark:border-yellow-800">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-info-circle text-yellow-600 dark:text-yellow-400 mr-2"></i>
                            Статусы
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full {{ $selectedAvr->first_status == 1 ? 'bg-green-500' : ($selectedAvr->first_status == -1 ? 'bg-red-500' : 'bg-gray-400') }}"></div>
                                <span class="text-sm text-gray-900 dark:text-white">Первичный</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full {{ $selectedAvr->judge_status == 1 ? 'bg-green-500' : ($selectedAvr->judge_status == -1 ? 'bg-red-500' : 'bg-gray-400') }}"></div>
                                <span class="text-sm text-gray-900 dark:text-white">Судья</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full {{ $selectedAvr->control_status == 1 ? 'bg-green-500' : ($selectedAvr->control_status == -1 ? 'bg-red-500' : 'bg-gray-400') }}"></div>
                                <span class="text-sm text-gray-900 dark:text-white">Комитет</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full {{ $selectedAvr->first_financial_status == 1 ? 'bg-green-500' : ($selectedAvr->first_financial_status == -1 ? 'bg-red-500' : 'bg-gray-400') }}"></div>
                                <span class="text-sm text-gray-900 dark:text-white">Первичная фин.</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full {{ $selectedAvr->last_financial_status == 1 ? 'bg-green-500' : ($selectedAvr->last_financial_status == -1 ? 'bg-red-500' : 'bg-gray-400') }}"></div>
                                <span class="text-sm text-gray-900 dark:text-white">Контрольная фин.</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full {{ $selectedAvr->final_status == 1 ? 'bg-green-500' : ($selectedAvr->final_status == -1 ? 'bg-red-500' : 'bg-gray-400') }}"></div>
                                <span class="text-sm text-gray-900 dark:text-white">Финальный</span>
                            </div>
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
