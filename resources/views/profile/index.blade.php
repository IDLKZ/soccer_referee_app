@extends(get_user_layout())

@section('title', 'Мой профиль')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Мой профиль</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Управление личной информацией и настройками</p>
        </div>

        <!-- Success Messages -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 dark:border-green-400 p-4 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 dark:text-green-400 mr-3"></i>
                <p class="text-green-700 dark:text-green-300">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Profile Overview Card -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden mb-8">
            <!-- Profile Header -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-8">
                <div class="flex items-center space-x-6">
                    <!-- Avatar -->
                    <div class="flex-shrink-0">
                        @if($user->image_url)
                            <img src="{{ asset('storage/' . $user->image_url) }}"
                                 alt="{{ $user->full_name }}"
                                 class="h-20 w-20 rounded-full border-4 border-white shadow-lg object-cover">
                        @else
                            <div class="h-20 w-20 bg-white bg-opacity-20 rounded-full border-4 border-white shadow-lg flex items-center justify-center">
                                <i class="fas fa-user text-3xl text-white"></i>
                            </div>
                        @endif
                    </div>

                    <!-- User Info -->
                    <div class="flex-1 text-white">
                        <h2 class="text-2xl font-bold">{{ $user->full_name }}</h2>
                        <p class="text-blue-100 mt-1">{{ $user->role->title_ru ?? 'Нет роли' }}</p>
                        <div class="flex items-center mt-3 space-x-4 text-sm text-blue-100">
                            <span class="flex items-center">
                                <i class="fas fa-envelope mr-2"></i>
                                {{ $user->email }}
                            </span>
                            @if($user->phone)
                            <span class="flex items-center">
                                <i class="fas fa-phone mr-2"></i>
                                {{ $user->phone }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col space-y-2">
                        <a href="{{ route('profile.edit') }}"
                           class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-edit mr-2"></i>
                            Редактировать профиль
                        </a>
                        <button onclick="document.getElementById('avatarModal').classList.remove('hidden')"
                                class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-camera mr-2"></i>
                            Сменить фото
                        </button>
                    </div>
                </div>
            </div>

            <!-- Profile Details -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Personal Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Личная информация</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ФИО</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $user->full_name }}</dd>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</dd>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Телефон</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $user->phone ?: 'Не указан' }}</dd>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Дата рождения</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $user->birth_date ? $user->birth_date->format('d.m.Y') : 'Не указана' }}
                                </dd>
                            </div>
                            <div class="flex justify-between py-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Адрес</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $user->address_ru ?: 'Не указан' }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Account Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Учетная запись</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Имя пользователя</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $user->username }}</dd>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Роль</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $user->role->title_ru ?? 'Не назначена' }}</dd>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Статус</dt>
                                <dd class="text-sm">
                                    @if($user->is_active)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300">
                                            Активен
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300">
                                            Неактивен
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email подтвержден</dt>
                                <dd class="text-sm">
                                    @if($user->is_verified)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300">
                                            Подтвержден
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300">
                                            Не подтвержден
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            <div class="flex justify-between py-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Дата регистрации</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $user->created_at->format('d.m.Y H:i') }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('profile.edit') }}"
               class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition-shadow block">
                <div class="flex items-center text-blue-600 dark:text-blue-400 mb-2">
                    <i class="fas fa-user-edit text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Редактировать профиль</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Изменить личную информацию</p>
            </a>

            <button onclick="document.getElementById('passwordModal').classList.remove('hidden')"
                    class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition-shadow text-left w-full">
                <div class="flex items-center text-orange-600 dark:text-orange-400 mb-2">
                    <i class="fas fa-key text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Изменить пароль</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Обновить пароль доступа</p>
            </button>

            <button onclick="document.getElementById('avatarModal').classList.remove('hidden')"
                    class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition-shadow text-left w-full">
                <div class="flex items-center text-purple-600 dark:text-purple-400 mb-2">
                    <i class="fas fa-camera text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Сменить фото</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Обновить аватар</p>
            </button>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Последняя активность</h3>
            <div class="text-center py-8">
                <i class="fas fa-clock text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                <p class="text-gray-500 dark:text-gray-400">История активности будет доступна в ближайшее время</p>
            </div>
        </div>
    </div>

    <!-- Avatar Upload Modal -->
    <div id="avatarModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" onclick="document.getElementById('avatarModal').classList.add('hidden')">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-gray-800">
                    @csrf
                    @method('PUT')
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Сменить аватар</h3>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="avatar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Выберите фото</label>
                                <input type="file"
                                       name="avatar"
                                       id="avatar"
                                       accept="image/*"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Загрузить
                        </button>
                        <button type="button"
                                onclick="document.getElementById('avatarModal').classList.add('hidden')"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Отмена
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Password Change Modal -->
    <div id="passwordModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" onclick="document.getElementById('passwordModal').classList.add('hidden')">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('profile.password') }}" method="POST" class="bg-white dark:bg-gray-800">
                    @csrf
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Изменить пароль</h3>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Текущий пароль *</label>
                                <input type="password"
                                       name="current_password"
                                       id="current_password"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Новый пароль *</label>
                                <input type="password"
                                       name="password"
                                       id="password"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Подтвердите пароль *</label>
                                <input type="password"
                                       name="password_confirmation"
                                       id="password_confirmation"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Изменить пароль
                        </button>
                        <button type="button"
                                onclick="document.getElementById('passwordModal').classList.add('hidden')"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Отмена
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection