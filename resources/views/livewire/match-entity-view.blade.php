<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <button wire:click="goBack"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors duration-150">
                <i class="fas fa-arrow-left mr-2"></i>
                Назад
            </button>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Детали матча #{{ $match->id }}</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ $ownerClub->short_name_ru ?? $ownerClub->title_ru }} vs {{ $guestClub->short_name_ru ?? $guestClub->title_ru }}
                </p>
            </div>
        </div>
        <div class="flex gap-2">
            @can('manage-matches')
            <a href="{{ route('match-entity-management') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-150">
                <i class="fas fa-edit mr-2"></i>
                Редактировать
            </a>
            @endcan
        </div>
    </div>

    <!-- Main Match Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Match Score Card -->
        <div class="lg:col-span-2 bg-gradient-to-br from-blue-500 to-indigo-600 dark:from-blue-700 dark:to-indigo-800 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div class="flex-1 text-center">
                    <div class="mb-2">
                        @if($ownerClub->image_url)
                        <img src="{{ $ownerClub->image_url }}" alt="{{ $ownerClub->title_ru }}" class="w-20 h-20 mx-auto rounded-full bg-white p-2">
                        @else
                        <div class="w-20 h-20 mx-auto rounded-full bg-white flex items-center justify-center">
                            <i class="fas fa-shield-alt text-4xl text-blue-600"></i>
                        </div>
                        @endif
                    </div>
                    <h3 class="text-xl font-bold">{{ $ownerClub->short_name_ru ?? $ownerClub->title_ru }}</h3>
                    <p class="text-sm opacity-90">Хозяева</p>
                </div>

                <div class="px-8 text-center">
                    @if($match->is_finished && $match->owner_point !== null && $match->guest_point !== null)
                    <div class="text-6xl font-bold">
                        {{ $match->owner_point }} : {{ $match->guest_point }}
                    </div>
                    <p class="text-sm mt-2 opacity-90">Финальный счет</p>
                    @else
                    <div class="text-4xl font-bold">
                        <i class="fas fa-clock"></i>
                    </div>
                    <p class="text-sm mt-2 opacity-90">{{ $match->start_at->format('d.m.Y H:i') }}</p>
                    @endif
                </div>

                <div class="flex-1 text-center">
                    <div class="mb-2">
                        @if($guestClub->image_url)
                        <img src="{{ $guestClub->image_url }}" alt="{{ $guestClub->title_ru }}" class="w-20 h-20 mx-auto rounded-full bg-white p-2">
                        @else
                        <div class="w-20 h-20 mx-auto rounded-full bg-white flex items-center justify-center">
                            <i class="fas fa-shield-alt text-4xl text-red-600"></i>
                        </div>
                        @endif
                    </div>
                    <h3 class="text-xl font-bold">{{ $guestClub->short_name_ru ?? $guestClub->title_ru }}</h3>
                    <p class="text-sm opacity-90">Гости</p>
                </div>
            </div>

            @if($match->club && $match->is_finished)
            <div class="mt-4 text-center border-t border-white/20 pt-4">
                <p class="text-sm opacity-90">Победитель</p>
                <p class="text-lg font-bold">
                    <i class="fas fa-trophy mr-2"></i>
                    {{ $match->club->short_name_ru ?? $match->club->title_ru }}
                </p>
            </div>
            @endif
        </div>

        <!-- Match Status Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                Статус матча
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Активен:</span>
                    @if($match->is_active)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                        <i class="fas fa-check-circle mr-1"></i> Да
                    </span>
                    @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                        <i class="fas fa-times-circle mr-1"></i> Нет
                    </span>
                    @endif
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Завершен:</span>
                    @if($match->is_finished)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                        <i class="fas fa-flag-checkered mr-1"></i> Да
                    </span>
                    @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                        <i class="fas fa-hourglass-half mr-1"></i> Нет
                    </span>
                    @endif
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Отменен:</span>
                    @if($match->is_canceled)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                        <i class="fas fa-ban mr-1"></i> Да
                    </span>
                    @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                        <i class="fas fa-check mr-1"></i> Нет
                    </span>
                    @endif
                </div>

                @if($match->is_canceled && $match->cancel_reason)
                <div class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                    <p class="text-xs font-semibold text-red-800 dark:text-red-300 mb-1">Причина отмены:</p>
                    <p class="text-sm text-red-700 dark:text-red-400">{{ $match->cancel_reason }}</p>
                </div>
                @endif

                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Текущая операция:</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $match->operation->title_ru }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Match Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- League & Competition Info -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                <i class="fas fa-trophy mr-2 text-yellow-500"></i>
                Турнир и сезон
            </h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Лига</p>
                    <div class="flex items-center gap-2">
                        @if($match->league->image_url)
                        <img src="{{ $match->league->image_url }}" alt="{{ $match->league->title_ru }}" class="w-8 h-8 rounded">
                        @endif
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $match->league->title_ru }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Сезон</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $match->season->title_ru }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $match->season->start_at->format('d.m.Y') }} - {{ $match->season->end_at->format('d.m.Y') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Location Info -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                Место проведения
            </h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Стадион</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $match->stadium->title_ru }}</p>
                    @if($match->stadium->address)
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $match->stadium->address }}</p>
                    @endif
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Город</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $match->city->title_ru }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Дата и время</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                        <i class="fas fa-calendar mr-1"></i>
                        {{ $match->start_at->format('d.m.Y') }}
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <i class="fas fa-clock mr-1"></i>
                        {{ $match->start_at->format('H:i') }} - {{ $match->end_at->format('H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Judge Requirements -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            <i class="fas fa-users mr-2 text-blue-500"></i>
            Требования к судьям
        </h3>
        @if($match->judge_requirements->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($match->judge_requirements as $requirement)
            <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                        {{ $requirement->judge_type->title_ru }}
                    </p>
                    @if($requirement->is_required)
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                        Обязательно
                    </span>
                    @endif
                </div>
                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $requirement->qty }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">требуется</p>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-sm text-gray-500 dark:text-gray-400 italic">Требования к судьям не указаны</p>
        @endif
    </div>

    <!-- Match Logists -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            <i class="fas fa-truck mr-2 text-purple-500"></i>
            Логисты матча
        </h3>
        @if($match->match_logists->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($match->match_logists as $logist)
            <div class="p-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-lg border border-purple-200 dark:border-purple-800">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        @if($logist->user->image_url)
                        <img src="{{ $logist->user->image_url }}" alt="{{ $logist->user->last_name }}" class="w-12 h-12 rounded-full">
                        @else
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                            <span class="text-white text-sm font-bold">
                                {{ strtoupper(substr($logist->user->first_name, 0, 1)) }}{{ strtoupper(substr($logist->user->last_name, 0, 1)) }}
                            </span>
                        </div>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ $logist->user->last_name }} {{ $logist->user->first_name }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            <i class="fas fa-phone mr-1"></i>{{ $logist->user->phone }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-sm text-gray-500 dark:text-gray-400 italic">Логисты не назначены</p>
        @endif
    </div>

    <!-- Match Deadlines -->
    @if($match->match_deadlines->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            <i class="fas fa-clock mr-2 text-orange-500"></i>
            Дедлайны матча
        </h3>
        <div class="space-y-3">
            @foreach($match->match_deadlines as $deadline)
            <div class="p-4 bg-gradient-to-r from-orange-50 to-yellow-50 dark:from-orange-900/20 dark:to-yellow-900/20 rounded-lg border border-orange-200 dark:border-orange-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ $deadline->operation->title_ru }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            {{ $deadline->start_at->format('d.m.Y H:i') }} - {{ $deadline->end_at->format('d.m.Y H:i') }}
                        </p>
                    </div>
                    <div class="text-right">
                        @if($deadline->end_at->isPast())
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                            <i class="fas fa-exclamation-circle mr-1"></i>Просрочено
                        </span>
                        @elseif($deadline->start_at->isFuture())
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                            <i class="fas fa-hourglass-start mr-1"></i>Ожидание
                        </span>
                        @else
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                            <i class="fas fa-check-circle mr-1"></i>Активно
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Assigned Judges (if any) -->
    @if($match->match_judges->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            <i class="fas fa-user-tie mr-2 text-green-500"></i>
            Назначенные судьи
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($match->match_judges as $judge)
            <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg border border-green-200 dark:border-green-800">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        @if($judge->user->image_url)
                        <img src="{{ $judge->user->image_url }}" alt="{{ $judge->user->last_name }}" class="w-12 h-12 rounded-full">
                        @else
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                            <span class="text-white text-sm font-bold">
                                {{ strtoupper(substr($judge->user->first_name, 0, 1)) }}{{ strtoupper(substr($judge->user->last_name, 0, 1)) }}
                            </span>
                        </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ $judge->user->last_name }} {{ $judge->user->first_name }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $judge->judge_type->title_ru }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Metadata -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            <i class="fas fa-info-circle mr-2 text-gray-500"></i>
            Метаданные
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Создано</p>
                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $match->created_at->format('d.m.Y H:i') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Обновлено</p>
                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $match->updated_at->format('d.m.Y H:i') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">ID матча</p>
                <p class="font-medium text-gray-900 dark:text-gray-100">#{{ $match->id }}</p>
            </div>
        </div>
    </div>
</div>
