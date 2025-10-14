@extends(get_user_layout())

@section('title', 'Просмотр роли')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $role->title_ru }}</h1>
                <p class="mt-1 text-sm text-gray-600">Детальная информация о роли</p>
            </div>
            <div class="flex gap-2">
                @can('manage-roles')
                <a href="{{ route('admin.roles.edit', $role) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-edit mr-1"></i> Редактировать
                </a>
                @endcan
                <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Назад
                </a>
            </div>
        </div>

        <!-- Role Details -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <!-- Basic Information -->
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-3">
                <h2 class="text-lg font-semibold text-gray-900">Основная информация</h2>
            </div>
            <div class="px-6 py-4">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">ID</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $role->id }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Значение (slug)</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <code class="px-2 py-1 bg-gray-100 rounded">{{ $role->value }}</code>
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Titles -->
            <div class="border-t border-b border-gray-200 bg-gray-50 px-6 py-3">
                <h2 class="text-lg font-semibold text-gray-900">Названия</h2>
            </div>
            <div class="px-6 py-4">
                <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Русский</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $role->title_ru }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Казахский</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $role->title_kk }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Английский</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $role->title_en }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Descriptions -->
            <div class="border-t border-b border-gray-200 bg-gray-50 px-6 py-3">
                <h2 class="text-lg font-semibold text-gray-900">Описания</h2>
            </div>
            <div class="px-6 py-4">
                <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Русский</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $role->description_ru ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Казахский</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $role->description_kk ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Английский</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $role->description_en ?: '—' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Properties -->
            <div class="border-t border-b border-gray-200 bg-gray-50 px-6 py-3">
                <h2 class="text-lg font-semibold text-gray-900">Свойства</h2>
            </div>
            <div class="px-6 py-4">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Статус</dt>
                        <dd class="mt-1">
                            @if($role->is_active)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Активна
                            </span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Неактивна
                            </span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Тип</dt>
                        <dd class="mt-1">
                            @if($role->is_system)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                Системная
                            </span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Обычная
                            </span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Административная</dt>
                        <dd class="mt-1">
                            @if($role->is_administrative)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                Да
                            </span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Нет
                            </span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Доступна при регистрации</dt>
                        <dd class="mt-1">
                            @if($role->can_register)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Да
                            </span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Нет
                            </span>
                            @endif
                        </dd>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="border-t border-b border-gray-200 bg-gray-50 px-6 py-3">
                <h2 class="text-lg font-semibold text-gray-900">Статистика</h2>
            </div>
            <div class="px-6 py-4">
                <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Пользователей с этой ролью</dt>
                        <dd class="mt-1 text-2xl font-bold text-gray-900">{{ $role->users_count }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Дата создания</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $role->created_at->format('d.m.Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Дата обновления</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $role->updated_at->format('d.m.Y H:i') }}</dd>
                    </div>
                </dl>
            </div>

            @if(!$role->is_system && $role->users_count === 0)
            <!-- Delete Action -->
            <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
                @can('manage-roles')
                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить эту роль?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                        <i class="fas fa-trash mr-1"></i> Удалить роль
                    </button>
                </form>
                @endcan
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
