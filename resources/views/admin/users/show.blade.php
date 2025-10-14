@extends(get_user_layout())

@section('title', 'Просмотр пользователя')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-start">
            <div class="flex items-center">
                <div class="h-16 w-16 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-2xl mr-4">
                    {{ mb_substr($user->first_name, 0, 1) }}{{ mb_substr($user->last_name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $user->last_name }} {{ $user->first_name }}</h1>
                    @if($user->patronymic)
                    <p class="text-lg text-gray-600">{{ $user->patronymic }}</p>
                    @endif
                    <p class="mt-1 text-sm text-gray-500">ID: {{ $user->id }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                @can('manage-users')
                <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-edit mr-1"></i> Редактировать
                </a>
                @endcan
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Назад
                </a>
            </div>
        </div>

        <!-- Status Badges -->
        <div class="mb-6 flex gap-2">
            @if($user->is_active)
            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                <i class="fas fa-check-circle"></i> Активен
            </span>
            @else
            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                <i class="fas fa-times-circle"></i> Неактивен
            </span>
            @endif

            @if($user->email_verified_at)
            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                <i class="fas fa-envelope-circle-check"></i> Email подтвержден
            </span>
            @else
            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                <i class="fas fa-envelope"></i> Email не подтвержден
            </span>
            @endif

            @if($user->role)
            <span class="px-3 py-1 text-sm font-semibold rounded-full
                @if($user->role->is_administrative) bg-purple-100 text-purple-800
                @else bg-gray-100 text-gray-800
                @endif">
                <i class="fas fa-user-shield"></i> {{ $user->role->title_ru }}
            </span>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Personal Information -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-3">
                    <h2 class="text-lg font-semibold text-gray-900">Персональная информация</h2>
                </div>
                <div class="px-6 py-4">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Фамилия</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->last_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Имя</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->first_name }}</dd>
                        </div>
                        @if($user->patronymic)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Отчество</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->patronymic }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Дата рождения</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($user->birth_date)
                                {{ $user->birth_date->format('d.m.Y') }}
                                @if($user->birth_date)
                                <span class="text-gray-500">({{ \Carbon\Carbon::parse($user->birth_date)->age }} лет)</span>
                                @endif
                                @else
                                —
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-3">
                    <h2 class="text-lg font-semibold text-gray-900">Контактная информация</h2>
                </div>
                <div class="px-6 py-4">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="mailto:{{ $user->email }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $user->email }}
                                </a>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Телефон</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($user->phone)
                                <a href="tel:{{ $user->phone }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $user->phone }}
                                </a>
                                @else
                                —
                                @endif
                            </dd>
                        </div>
                        @if($user->email_verified_at)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email подтвержден</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->email_verified_at->format('d.m.Y H:i') }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Account Information -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-3">
                    <h2 class="text-lg font-semibold text-gray-900">Учетная запись</h2>
                </div>
                <div class="px-6 py-4">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Роль</dt>
                            <dd class="mt-1">
                                @if($user->role)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if($user->role->is_administrative) bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $user->role->title_ru }}
                                </span>
                                <div class="mt-1 text-xs text-gray-500">{{ $user->role->description_ru }}</div>
                                @else
                                <span class="text-sm text-gray-400">—</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Статус</dt>
                            <dd class="mt-1">
                                @if($user->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Активен
                                </span>
                                @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Неактивен
                                </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Дата регистрации</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d.m.Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Последнее обновление</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('d.m.Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-3">
                    <h2 class="text-lg font-semibold text-gray-900">Дополнительная информация</h2>
                </div>
                <div class="px-6 py-4">
                    <dl class="space-y-4">
                        @if($user->role && $user->role->is_administrative)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Административные права</dt>
                            <dd class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                    Да
                                </span>
                            </dd>
                        </div>
                        @endif

                        @if($user->id === auth()->id())
                        <div class="p-3 bg-blue-50 rounded-md">
                            <p class="text-sm text-blue-800">
                                <i class="fas fa-info-circle"></i> Это ваш профиль
                            </p>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        @if($user->id !== auth()->id())
        <!-- Danger Zone -->
        <div class="mt-6 bg-white shadow-md rounded-lg overflow-hidden border-2 border-red-200">
            <div class="bg-red-50 px-6 py-3 border-b border-red-200">
                <h2 class="text-lg font-semibold text-red-900">Опасная зона</h2>
            </div>
            <div class="px-6 py-4">
                <p class="text-sm text-gray-600 mb-4">Удаление пользователя необратимо. Все данные, связанные с этим пользователем, будут потеряны.</p>
                @can('manage-users')
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этого пользователя? Это действие необратимо!')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                        <i class="fas fa-trash mr-1"></i> Удалить пользователя
                    </button>
                </form>
                @endcan
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
