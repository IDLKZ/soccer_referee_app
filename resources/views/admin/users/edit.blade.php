@extends(get_user_layout())

@section('title', 'Редактировать пользователя')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Редактировать пользователя: {{ $user->last_name }} {{ $user->first_name }}</h1>
            <p class="mt-1 text-sm text-gray-600">Обновите информацию о пользователе</p>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-white shadow-md rounded-lg p-6">
            @csrf
            @method('PUT')

            <!-- Personal Information -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Персональная информация</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Фамилия *</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('last_name') border-red-500 @enderror">
                        @error('last_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">Имя *</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('first_name') border-red-500 @enderror">
                        @error('first_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="patronymic" class="block text-sm font-medium text-gray-700 mb-1">Отчество</label>
                        <input type="text" name="patronymic" id="patronymic" value="{{ old('patronymic', $user->patronymic) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('patronymic') border-red-500 @enderror">
                        @error('patronymic')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Дата рождения</label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('birth_date') border-red-500 @enderror">
                        @error('birth_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Телефон</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" placeholder="+7 (___) ___-__-__" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror">
                        @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="mb-6 pt-6 border-t border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Учетная запись</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                        @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role_id" class="block text-sm font-medium text-gray-700 mb-1">Роль *</label>
                        <select name="role_id" id="role_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('role_id') border-red-500 @enderror">
                            <option value="">Выберите роль</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                {{ $role->title_ru }}
                            </option>
                            @endforeach
                        </select>
                        @error('role_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Change Password -->
            <div class="mb-6 pt-6 border-t border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Изменить пароль</h2>
                <p class="text-sm text-gray-600 mb-4">Оставьте поля пустыми, если не хотите менять пароль</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Новый пароль</label>
                        <input type="password" name="password" id="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                        @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Минимум 8 символов</p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Подтверждение пароля</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="mb-6 pt-6 border-t border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Статус</h2>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                    <label for="is_active" class="ml-2 text-sm text-gray-700">Аккаунт активен</label>
                </div>

                @if($user->email_verified_at)
                <div class="mt-2 text-sm text-green-600">
                    <i class="fas fa-check-circle"></i> Email подтвержден {{ $user->email_verified_at->format('d.m.Y H:i') }}
                </div>
                @else
                <div class="mt-2 text-sm text-gray-500">
                    <i class="fas fa-clock"></i> Email еще не подтвержден
                </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
                    Отмена
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    Сохранить изменения
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
