<div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow-md">
        <div class="border-b border-gray-200 px-6 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">Управление матчами</h1>
                @if($canCreate)
                <button wire:click="$set('showCreateModal', true)"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-plus mr-2"></i>Создать матч
                </button>
                @endif
            </div>
        </div>

        @if(session()->has('message'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 m-4 rounded">
            {{ session('message') }}
        </div>
        @endif

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Список матчей -->
                <div class="space-y-4">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Список матчей</h2>
                    @if($matches->count() > 0)
                        @foreach($matches as $match)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer"
                             wire:click="selectMatch({{ $match->id }})">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $match->title_ru ?? 'Матч ' . $match->id }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $match->league->title_ru ?? '' }} • {{ $match->season->title_ru ?? '' }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        {{ $match->stadium->title_ru ?? '' }}, {{ $match->city->title_ru ?? '' }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $match->match_date ? $match->match_date->format('d.m.Y H:i') : 'Не указано' }}
                                    </p>
                                </div>
                                <div class="flex space-x-2">
                                    @if($canEdit)
                                    <button wire:click="editMatch({{ $match->id }})"
                                            class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @endif
                                    @if($canDelete)
                                    <button wire:click="deleteMatch({{ $match->id }})"
                                            class="text-red-600 hover:text-red-800"
                                            onclick="return confirm('Вы уверены, что хотите удалить этот матч?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-futbol text-4xl mb-4"></i>
                        <p>Матчи не найдены</p>
                    </div>
                    @endif
                </div>

                <!-- Детали выбранного матча -->
                @if($selectedMatch)
                <div class="border border-gray-200 rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Детали матча</h2>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Матч</label>
                            <p class="text-gray-900">{{ $selectedMatch->title_ru ?? 'Матч ' . $selectedMatch->id }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Лига</label>
                            <p class="text-gray-900">{{ $selectedMatch->league->title_ru ?? 'Не указано' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Сезон</label>
                            <p class="text-gray-900">{{ $selectedMatch->season->title_ru ?? 'Не указано' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Стадион</label>
                            <p class="text-gray-900">{{ $selectedMatch->stadium->title_ru ?? 'Не указано' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Дата и время</label>
                            <p class="text-gray-900">{{ $selectedMatch->match_date ? $selectedMatch->match_date->format('d.m.Y H:i') : 'Не указано' }}</p>
                        </div>

                        <!-- Действия с матчем -->
                        <div class="pt-4 border-t border-gray-200">
                            <div class="grid grid-cols-2 gap-2">
                                @if($canAssignReferees)
                                <button class="bg-blue-100 hover:bg-blue-200 text-blue-800 font-medium py-2 px-3 rounded text-sm">
                                    <i class="fas fa-user-plus mr-1"></i>Назначить судей
                                </button>
                                @endif
                                @if($canManageLogistics)
                                <button class="bg-green-100 hover:bg-green-200 text-green-800 font-medium py-2 px-3 rounded text-sm">
                                    <i class="fas fa-route mr-1"></i>Логистика
                                </button>
                                @endif
                                @if(auth()->user()->can('create-protocols', $selectedMatch))
                                <button class="bg-purple-100 hover:bg-purple-200 text-purple-800 font-medium py-2 px-3 rounded text-sm">
                                    <i class="fas fa-file-alt mr-1"></i>Протокол
                                </button>
                                @endif
                                @if(auth()->user()->can('approve-operations', $selectedMatch))
                                <button class="bg-yellow-100 hover:bg-yellow-200 text-yellow-800 font-medium py-2 px-3 rounded text-sm">
                                    <i class="fas fa-check-circle mr-1"></i>Согласовать
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>