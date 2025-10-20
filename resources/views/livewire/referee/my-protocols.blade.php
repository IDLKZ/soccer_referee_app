<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400 flex items-center">
                <i class="fas fa-file-alt mr-4"></i>
                Мои протоколы
            </h1>
            <p class="mt-2 text-base text-gray-600 dark:text-gray-400">
                Управление протоколами матчей
            </p>
        </div>

        <!-- Success/Error Messages -->
        @if (session()->has('message'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 text-green-800 dark:text-green-400 px-6 py-4 rounded-lg flex items-center shadow-lg animate-fade-in">
                <i class="fas fa-check-circle text-3xl mr-4"></i>
                <span class="font-medium text-lg">{{ session('message') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 text-red-800 dark:text-red-400 px-6 py-4 rounded-lg flex items-center shadow-lg animate-fade-in">
                <i class="fas fa-exclamation-circle text-3xl mr-4"></i>
                <span class="font-medium text-lg">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Tabs Navigation -->
        <div class="mb-8">
            <nav class="flex space-x-2 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-2">
                <button wire:click="switchTab('create')"
                        class="flex-1 px-4 py-4 rounded-lg font-semibold transition-all {{ $activeTab === 'create' ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-xl transform scale-105' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Создание
                </button>
                <button wire:click="switchTab('rework')"
                        class="flex-1 px-4 py-4 rounded-lg font-semibold transition-all {{ $activeTab === 'rework' ? 'bg-gradient-to-r from-red-500 to-orange-500 text-white shadow-xl transform scale-105' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-redo mr-2"></i>
                    Доработка
                    @if($reworkProtocols->count() > 0)
                        <span class="ml-2 bg-white/30 px-2 py-1 rounded-full text-xs">{{ $reworkProtocols->count() }}</span>
                    @endif
                </button>
                <button wire:click="switchTab('primary')"
                        class="flex-1 px-4 py-4 rounded-lg font-semibold transition-all {{ $activeTab === 'primary' ? 'bg-gradient-to-r from-yellow-500 to-orange-500 text-white shadow-xl transform scale-105' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-eye mr-2"></i>
                    Первичное
                    @if($primaryProtocols->count() > 0)
                        <span class="ml-2 bg-white/30 px-2 py-1 rounded-full text-xs">{{ $primaryProtocols->count() }}</span>
                    @endif
                </button>
                <button wire:click="switchTab('final')"
                        class="flex-1 px-4 py-4 rounded-lg font-semibold transition-all {{ $activeTab === 'final' ? 'bg-gradient-to-r from-green-500 to-emerald-500 text-white shadow-xl transform scale-105' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-check-double mr-2"></i>
                    Финальное
                    @if($finalProtocols->count() > 0)
                        <span class="ml-2 bg-white/30 px-2 py-1 rounded-full text-xs">{{ $finalProtocols->count() }}</span>
                    @endif
                </button>
                <button wire:click="switchTab('all')"
                        class="flex-1 px-4 py-4 rounded-lg font-semibold transition-all {{ $activeTab === 'all' ? 'bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-xl transform scale-105' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <i class="fas fa-archive mr-2"></i>
                    Все
                    @if($allProtocols->count() > 0)
                        <span class="ml-2 bg-white/30 px-2 py-1 rounded-full text-xs">{{ $allProtocols->count() }}</span>
                    @endif
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">

            <!-- Tab 1: Create/Edit Protocols -->
            @if($activeTab === 'create')
                <div class="space-y-6">
                    @if($createMatches->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($createMatches as $match)
                                @php
                                    $existingProtocol = $match->protocols->first();
                                @endphp
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-200 dark:border-gray-700">
                                    <!-- Card Header -->
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-5 text-white">
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-xs font-bold uppercase tracking-wider bg-white/30 backdrop-blur-sm px-3 py-1 rounded-full">
                                                {{ $match->league->title_ru ?? 'Лига' }}
                                            </span>
                                            <span class="text-xs bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full">
                                                {{ $match->operation->title_ru }}
                                            </span>
                                        </div>
                                        <h3 class="text-lg font-bold leading-tight">
                                            {{ $match->ownerClub->short_name_ru ?? $match->ownerClub->title_ru }}
                                            <span class="mx-2 text-white/70">vs</span>
                                            {{ $match->guestClub->short_name_ru ?? $match->guestClub->title_ru }}
                                        </h3>
                                    </div>

                                    <!-- Card Body -->
                                    <div class="p-5 space-y-3">
                                        <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                            <i class="fas fa-calendar-alt w-5 mr-2 text-blue-500"></i>
                                            <span>{{ \Carbon\Carbon::parse($match->start_at)->format('d.m.Y H:i') }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                            <i class="fas fa-map-marker-alt w-5 mr-2 text-red-500"></i>
                                            <span>{{ $match->stadium->title_ru ?? 'Стадион' }}</span>
                                        </div>

                                        @if($existingProtocol)
                                            <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                                <div class="flex items-center text-sm text-blue-800 dark:text-blue-400 mb-2">
                                                    <i class="fas fa-file-alt mr-2"></i>
                                                    <span class="font-semibold">Протокол создан</span>
                                                </div>
                                                @if($existingProtocol->file_url)
                                                    <a href="{{ asset('storage/' . $existingProtocol->file_url) }}" target="_blank"
                                                       class="text-xs text-blue-600 dark:text-blue-400 hover:underline flex items-center">
                                                        <i class="fas fa-paperclip mr-1"></i>
                                                        Просмотреть файл
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Card Footer -->
                                    <div class="px-5 pb-5 flex gap-2">
                                        <button wire:click="openProtocolModal({{ $match->id }})"
                                                class="flex-1 inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-lg transition-all shadow-md hover:shadow-xl font-semibold">
                                            <i class="fas fa-{{ $existingProtocol ? 'edit' : 'plus' }} mr-2"></i>
                                            {{ $existingProtocol ? 'Редактировать' : 'Создать протокол' }}
                                        </button>

                                        @if($existingProtocol && $existingProtocol->operation->value === 'protocol_reprocessing')
                                            <button wire:click="submitForApproval({{ $existingProtocol->id }})"
                                                    wire:confirm="Вы уверены, что хотите отправить протокол на проверку?"
                                                    class="px-4 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-all shadow-md hover:shadow-xl">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                            <button wire:click="deleteProtocol({{ $existingProtocol->id }})"
                                                    wire:confirm="Вы уверены, что хотите удалить протокол?"
                                                    class="px-4 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all shadow-md hover:shadow-xl">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-16 text-center">
                            <div class="text-gray-300 dark:text-gray-600 mb-6">
                                <i class="fas fa-file-alt text-8xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-700 dark:text-gray-300 mb-3">
                                Нет матчей для создания протоколов
                            </h3>
                            <p class="text-base text-gray-500 dark:text-gray-400">
                                Все протоколы созданы или нет доступных матчей
                            </p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Tab 2: Rework Protocols -->
            @if($activeTab === 'rework')
                <div class="space-y-6">
                    @if($reworkProtocols->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($reworkProtocols as $protocol)
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-200 dark:border-gray-700">
                                    <!-- Card Header -->
                                    <div class="bg-gradient-to-r from-red-500 to-orange-500 p-5 text-white">
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-xs font-bold uppercase tracking-wider bg-white/30 backdrop-blur-sm px-3 py-1 rounded-full">
                                                {{ $protocol->match->league->title_ru ?? 'Лига' }}
                                            </span>
                                            <span class="text-xs bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full">
                                                #{{ $protocol->id }}
                                            </span>
                                        </div>
                                        <h3 class="text-lg font-bold leading-tight">
                                            {{ $protocol->match->ownerClub->short_name_ru ?? $protocol->match->ownerClub->title_ru }}
                                            <span class="mx-2 text-white/70">vs</span>
                                            {{ $protocol->match->guestClub->short_name_ru ?? $protocol->match->guestClub->title_ru }}
                                        </h3>
                                    </div>

                                    <!-- Card Body -->
                                    <div class="p-5 space-y-3">
                                        <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                            <i class="fas fa-calendar-alt w-5 mr-2 text-red-500"></i>
                                            <span>{{ \Carbon\Carbon::parse($protocol->match->start_at)->format('d.m.Y H:i') }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                            <i class="fas fa-map-marker-alt w-5 mr-2 text-red-500"></i>
                                            <span>{{ $protocol->match->stadium->title_ru ?? 'Стадион' }}</span>
                                        </div>

                                        <!-- Rejection Status -->
                                        @if($protocol->first_status == -1)
                                            <div class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                                <div class="flex items-center text-sm text-red-800 dark:text-red-400 mb-2">
                                                    <i class="fas fa-times-circle mr-2"></i>
                                                    <span class="font-semibold">Отклонено на первичной проверке</span>
                                                </div>
                                                @if($protocol->comment)
                                                    <p class="text-xs text-gray-700 dark:text-gray-300 mt-2">{{ $protocol->comment }}</p>
                                                @endif
                                            </div>
                                        @elseif($protocol->final_status == -1)
                                            <div class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                                <div class="flex items-center text-sm text-red-800 dark:text-red-400 mb-2">
                                                    <i class="fas fa-times-circle mr-2"></i>
                                                    <span class="font-semibold">Отклонено на финальной проверке</span>
                                                </div>
                                                @if($protocol->final_comment)
                                                    <p class="text-xs text-gray-700 dark:text-gray-300 mt-2">{{ $protocol->final_comment }}</p>
                                                @endif
                                            </div>
                                        @endif

                                        @if($protocol->file_url)
                                            <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                                <a href="{{ asset('storage/' . $protocol->file_url) }}" target="_blank"
                                                   class="text-xs text-blue-600 dark:text-blue-400 hover:underline flex items-center">
                                                    <i class="fas fa-paperclip mr-1"></i>
                                                    Просмотреть текущий файл
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Card Footer -->
                                    <div class="px-5 pb-5 flex gap-2">
                                        <button wire:click="openProtocolModal({{ $protocol->match_id }})"
                                                class="flex-1 inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-lg transition-all shadow-md hover:shadow-xl font-semibold">
                                            <i class="fas fa-edit mr-2"></i>
                                            Редактировать
                                        </button>

                                        <button wire:click="submitForApproval({{ $protocol->id }})"
                                                wire:confirm="Вы уверены, что хотите отправить протокол на проверку?"
                                                class="px-4 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-all shadow-md hover:shadow-xl">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>

                                        <button wire:click="openViewModal({{ $protocol->id }})"
                                                class="px-4 py-3 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-all shadow-md hover:shadow-xl">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-16 text-center">
                            <div class="text-gray-300 dark:text-gray-600 mb-6">
                                <i class="fas fa-redo text-8xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-700 dark:text-gray-300 mb-3">
                                Нет протоколов на доработке
                            </h3>
                            <p class="text-base text-gray-500 dark:text-gray-400">
                                У вас нет отклонённых протоколов, требующих доработки
                            </p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Tab 3: Primary Approval (View Only) -->
            @if($activeTab === 'primary')
                <div class="space-y-6">
                    @if($primaryProtocols->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($primaryProtocols as $protocol)
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-200 dark:border-gray-700">
                                    <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-5 text-white">
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-xs font-bold uppercase bg-white/30 px-3 py-1 rounded-full">
                                                {{ $protocol->match->league->title_ru ?? 'Лига' }}
                                            </span>
                                            <span class="text-xs bg-white/20 px-3 py-1 rounded-full">
                                                #{{ $protocol->id }}
                                            </span>
                                        </div>
                                        <h3 class="text-lg font-bold">
                                            {{ $protocol->match->ownerClub->short_name_ru ?? $protocol->match->ownerClub->title_ru }}
                                            <span class="mx-2">vs</span>
                                            {{ $protocol->match->guestClub->short_name_ru ?? $protocol->match->guestClub->title_ru }}
                                        </h3>
                                    </div>

                                    <div class="p-5">
                                        <div class="flex items-center text-sm text-gray-700 dark:text-gray-300 mb-3">
                                            <i class="fas fa-calendar-alt w-5 mr-2 text-yellow-500"></i>
                                            <span>{{ \Carbon\Carbon::parse($protocol->match->start_at)->format('d.m.Y H:i') }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                            <i class="fas fa-map-marker-alt w-5 mr-2 text-red-500"></i>
                                            <span>{{ $protocol->match->stadium->title_ru ?? 'Стадион' }}</span>
                                        </div>

                                        <button wire:click="openViewModal({{ $protocol->id }})"
                                                class="w-full mt-4 px-4 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white rounded-lg transition-all shadow-md hover:shadow-xl font-semibold">
                                            <i class="fas fa-eye mr-2"></i>
                                            Просмотреть
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-16 text-center">
                            <div class="text-gray-300 dark:text-gray-600 mb-6">
                                <i class="fas fa-eye text-8xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-700 dark:text-gray-300 mb-3">
                                Нет протоколов на первичном утверждении
                            </h3>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Tab 3: Final Approval (View Only) -->
            @if($activeTab === 'final')
                <div class="space-y-6">
                    @if($finalProtocols->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($finalProtocols as $protocol)
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-200 dark:border-gray-700">
                                    <div class="bg-gradient-to-r from-green-500 to-emerald-500 p-5 text-white">
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-xs font-bold uppercase bg-white/30 px-3 py-1 rounded-full">
                                                {{ $protocol->match->league->title_ru ?? 'Лига' }}
                                            </span>
                                            <span class="text-xs bg-white/20 px-3 py-1 rounded-full">
                                                #{{ $protocol->id }}
                                            </span>
                                        </div>
                                        <h3 class="text-lg font-bold">
                                            {{ $protocol->match->ownerClub->short_name_ru ?? $protocol->match->ownerClub->title_ru }}
                                            <span class="mx-2">vs</span>
                                            {{ $protocol->match->guestClub->short_name_ru ?? $protocol->match->guestClub->title_ru }}
                                        </h3>
                                    </div>

                                    <div class="p-5">
                                        <div class="flex items-center text-sm text-gray-700 dark:text-gray-300 mb-3">
                                            <i class="fas fa-calendar-alt w-5 mr-2 text-green-500"></i>
                                            <span>{{ \Carbon\Carbon::parse($protocol->match->start_at)->format('d.m.Y H:i') }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                            <i class="fas fa-map-marker-alt w-5 mr-2 text-red-500"></i>
                                            <span>{{ $protocol->match->stadium->title_ru ?? 'Стадион' }}</span>
                                        </div>

                                        <button wire:click="openViewModal({{ $protocol->id }})"
                                                class="w-full mt-4 px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white rounded-lg transition-all shadow-md hover:shadow-xl font-semibold">
                                            <i class="fas fa-eye mr-2"></i>
                                            Просмотреть
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-16 text-center">
                            <div class="text-gray-300 dark:text-gray-600 mb-6">
                                <i class="fas fa-check-double text-8xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-700 dark:text-gray-300 mb-3">
                                Нет протоколов на финальном утверждении
                            </h3>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Tab 4: All Protocols -->
            @if($activeTab === 'all')
                <div class="space-y-6">
                    @if($allProtocols->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">ID</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Матч</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Дата</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Статус</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Действия</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($allProtocols as $protocol)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-gray-100">
                                                    #{{ $protocol->id }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                                    <div class="font-semibold">
                                                        {{ $protocol->match->ownerClub->short_name_ru ?? $protocol->match->ownerClub->title_ru }}
                                                        <span class="text-gray-400">vs</span>
                                                        {{ $protocol->match->guestClub->short_name_ru ?? $protocol->match->guestClub->title_ru }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                        {{ $protocol->match->league->title_ru ?? 'Лига' }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                    {{ \Carbon\Carbon::parse($protocol->match->start_at)->format('d.m.Y H:i') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                        @if($protocol->operation->value === 'protocol_reprocessing') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                                        @elseif($protocol->operation->value === 'primary_protocol_approval') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                                        @elseif($protocol->operation->value === 'control_protocol_approval') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                                        @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400
                                                        @endif">
                                                        {{ $protocol->operation->title_ru }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <button wire:click="openViewModal({{ $protocol->id }})"
                                                            class="text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 font-semibold">
                                                        <i class="fas fa-eye mr-1"></i>
                                                        Просмотреть
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-16 text-center">
                            <div class="text-gray-300 dark:text-gray-600 mb-6">
                                <i class="fas fa-archive text-8xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-700 dark:text-gray-300 mb-3">
                                У вас нет протоколов
                            </h3>
                        </div>
                    @endif
                </div>
            @endif

        </div>

    </div>

    <!-- Create/Edit Protocol Modal -->
    @if($showProtocolModal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4"
             wire:click.self="closeProtocolModal">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden"
                 @click.stop>

                <!-- Modal Header -->
                <div class="px-8 py-6 bg-gradient-to-r from-blue-600 to-purple-600 text-white flex items-center justify-between">
                    <h3 class="text-2xl font-bold flex items-center">
                        <i class="fas fa-{{ $protocolForm['id'] ? 'edit' : 'plus-circle' }} mr-3"></i>
                        {{ $protocolForm['id'] ? 'Редактировать протокол' : 'Создать протокол' }}
                    </h3>
                    <button wire:click="closeProtocolModal"
                            class="text-white hover:text-gray-200 transition-colors bg-white/20 hover:bg-white/30 rounded-full p-3">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-8 overflow-y-auto" style="max-height: calc(90vh - 200px);">
                    <div class="space-y-6">
                        <!-- Info Field -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                                Описание / Комментарий
                            </label>
                            <textarea wire:model="protocolForm.info"
                                      rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 transition-all"
                                      placeholder="Добавьте описание или комментарий к протоколу..."></textarea>
                            @error('protocolForm.info')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- File Upload -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-file-upload mr-2 text-purple-500"></i>
                                Файл протокола
                            </label>

                            @if($currentProtocol && $currentProtocol->file_url && !$file)
                                <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-file-alt text-blue-500 text-2xl mr-3"></i>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Текущий файл</p>
                                                <a href="{{ asset('storage/' . $currentProtocol->file_url) }}" target="_blank"
                                                   class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
                                                    Просмотреть файл
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-blue-500 dark:hover:border-blue-400 transition-all">
                                <input type="file" wire:model="file" id="file-upload" class="hidden">
                                <label for="file-upload" class="cursor-pointer">
                                    <i class="fas fa-cloud-upload-alt text-5xl text-gray-400 dark:text-gray-500 mb-3"></i>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <span class="font-semibold text-blue-600 dark:text-blue-400">Нажмите для загрузки</span>
                                        или перетащите файл сюда
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                        PDF, DOC, DOCX, JPG, PNG (макс. 10 МБ)
                                    </p>
                                </label>
                            </div>

                            @if($file)
                                <div class="mt-3 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-file text-green-500 mr-2"></i>
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $file->getClientOriginalName() }}</span>
                                    </div>
                                    <button wire:click="$set('file', null)" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endif

                            <div wire:loading wire:target="file" class="mt-3 text-sm text-blue-600 dark:text-blue-400 flex items-center">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Загрузка файла...
                            </div>

                            @error('file')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-8 py-5 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex justify-end gap-3">
                    <button wire:click="closeProtocolModal"
                            class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-all shadow-md hover:shadow-lg font-semibold">
                        <i class="fas fa-times mr-2"></i>
                        Отмена
                    </button>
                    <button wire:click="saveProtocol"
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-lg transition-all shadow-md hover:shadow-lg font-semibold">
                        <i class="fas fa-save mr-2"></i>
                        Сохранить
                    </button>
                </div>

            </div>
        </div>
    @endif

    <!-- View Protocol Modal -->
    @if($showViewModal && $viewProtocol)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4"
             wire:click.self="closeViewModal">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden"
                 @click.stop>

                <!-- Modal Header -->
                <div class="px-8 py-6 bg-gradient-to-r from-purple-600 to-pink-600 text-white flex items-center justify-between">
                    <h3 class="text-2xl font-bold flex items-center">
                        <i class="fas fa-file-alt mr-3"></i>
                        Просмотр протокола #{{ $viewProtocol->id }}
                    </h3>
                    <button wire:click="closeViewModal"
                            class="text-white hover:text-gray-200 transition-colors bg-white/20 hover:bg-white/30 rounded-full p-3">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-8 overflow-y-auto bg-gray-50 dark:bg-gray-900" style="max-height: calc(90vh - 150px);">

                    <!-- Match Info -->
                    <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center border-b border-gray-200 dark:border-gray-700 pb-3">
                            <i class="fas fa-futbol text-purple-600 dark:text-purple-400 mr-2"></i>
                            Информация о матче
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase">Команды</label>
                                <p class="font-bold text-gray-900 dark:text-gray-100">
                                    {{ $viewProtocol->match->ownerClub->short_name_ru ?? $viewProtocol->match->ownerClub->title_ru }}
                                    <span class="text-purple-500">vs</span>
                                    {{ $viewProtocol->match->guestClub->short_name_ru ?? $viewProtocol->match->guestClub->title_ru }}
                                </p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase">Дата</label>
                                <p class="font-bold text-gray-900 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($viewProtocol->match->start_at)->format('d.m.Y H:i') }}
                                </p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase">Стадион</label>
                                <p class="font-bold text-gray-900 dark:text-gray-100">{{ $viewProtocol->match->stadium->title_ru ?? 'Н/Д' }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase">Лига</label>
                                <p class="font-bold text-gray-900 dark:text-gray-100">{{ $viewProtocol->match->league->title_ru ?? 'Н/Д' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Protocol Info -->
                    <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center border-b border-gray-200 dark:border-gray-700 pb-3">
                            <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mr-2"></i>
                            Информация о протоколе
                        </h4>

                        @if($viewProtocol->info)
                            <div class="mb-4">
                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase">Описание</label>
                                <p class="text-gray-700 dark:text-gray-300">{{ $viewProtocol->info }}</p>
                            </div>
                        @endif

                        @if($viewProtocol->file_url)
                            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                <a href="{{ asset('storage/' . $viewProtocol->file_url) }}" target="_blank"
                                   class="flex items-center text-blue-600 dark:text-blue-400 hover:underline font-semibold">
                                    <i class="fas fa-file-download mr-2"></i>
                                    Скачать файл протокола
                                </a>
                            </div>
                        @endif

                        <div class="mt-4 grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase">Статус</label>
                                <p class="font-bold text-gray-900 dark:text-gray-100">{{ $viewProtocol->operation->title_ru }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase">Дата создания</label>
                                <p class="font-bold text-gray-900 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($viewProtocol->created_at)->format('d.m.Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Comments -->
                    @if($viewProtocol->comment || $viewProtocol->final_comment)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center border-b border-gray-200 dark:border-gray-700 pb-3">
                                <i class="fas fa-comment text-orange-600 dark:text-orange-400 mr-2"></i>
                                Комментарии
                            </h4>

                            @if($viewProtocol->comment)
                                <div class="mb-4">
                                    <label class="text-xs text-gray-500 dark:text-gray-400 uppercase">Комментарий первичной проверки</label>
                                    <p class="text-gray-700 dark:text-gray-300 mt-1">{{ $viewProtocol->comment }}</p>
                                </div>
                            @endif

                            @if($viewProtocol->final_comment)
                                <div>
                                    <label class="text-xs text-gray-500 dark:text-gray-400 uppercase">Комментарий финальной проверки</label>
                                    <p class="text-gray-700 dark:text-gray-300 mt-1">{{ $viewProtocol->final_comment }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                </div>

                <!-- Modal Footer -->
                <div class="px-8 py-5 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex justify-end">
                    <button wire:click="closeViewModal"
                            class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-all shadow-md hover:shadow-lg font-semibold">
                        <i class="fas fa-times mr-2"></i>
                        Закрыть
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>

@push('styles')
<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.5s ease-out forwards;
    }
</style>
@endpush
