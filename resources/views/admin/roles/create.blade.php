@extends(get_user_layout())

@section('title', 'Создать роль')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Создать новую роль</h1>
            <p class="mt-1 text-sm text-gray-600">Заполните форму для создания новой роли</p>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.roles.store') }}" method="POST" class="bg-white shadow-md rounded-lg p-6">
            @csrf

            <!-- Titles -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="title_ru" class="block text-sm font-medium text-gray-700 mb-1">Название (RU) *</label>
                    <input type="text" name="title_ru" id="title_ru" value="{{ old('title_ru') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title_ru') border-red-500 @enderror">
                    @error('title_ru')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="title_kk" class="block text-sm font-medium text-gray-700 mb-1">Название (KK) *</label>
                    <input type="text" name="title_kk" id="title_kk" value="{{ old('title_kk') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title_kk') border-red-500 @enderror">
                    @error('title_kk')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="title_en" class="block text-sm font-medium text-gray-700 mb-1">Название (EN) *</label>
                    <input type="text" name="title_en" id="title_en" value="{{ old('title_en') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title_en') border-red-500 @enderror">
                    @error('title_en')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Value -->
            <div class="mb-4">
                <label for="value" class="block text-sm font-medium text-gray-700 mb-1">Значение (slug) *</label>
                <input type="text" name="value" id="value" value="{{ old('value') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('value') border-red-500 @enderror">
                @error('value')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Например: administrator, employee, etc.</p>
            </div>

            <!-- Descriptions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="description_ru" class="block text-sm font-medium text-gray-700 mb-1">Описание (RU)</label>
                    <textarea name="description_ru" id="description_ru" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description_ru') }}</textarea>
                </div>

                <div>
                    <label for="description_kk" class="block text-sm font-medium text-gray-700 mb-1">Описание (KK)</label>
                    <textarea name="description_kk" id="description_kk" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description_kk') }}</textarea>
                </div>

                <div>
                    <label for="description_en" class="block text-sm font-medium text-gray-700 mb-1">Описание (EN)</label>
                    <textarea name="description_en" id="description_en" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description_en') }}</textarea>
                </div>
            </div>

            <!-- Checkboxes -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="flex items-center">
                    <input type="checkbox" name="is_administrative" id="is_administrative" value="1" {{ old('is_administrative') ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                    <label for="is_administrative" class="ml-2 text-sm text-gray-700">Административная</label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                    <label for="is_active" class="ml-2 text-sm text-gray-700">Активна</label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="can_register" id="can_register" value="1" {{ old('can_register') ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                    <label for="can_register" class="ml-2 text-sm text-gray-700">Доступна при регистрации</label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_system" id="is_system" value="1" {{ old('is_system') ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                    <label for="is_system" class="ml-2 text-sm text-gray-700">Системная</label>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
                    Отмена
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    Создать роль
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
