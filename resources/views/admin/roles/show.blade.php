@extends(get_user_layout())

@section('title', 'Просмотр роли')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $role->title_ru }}</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Детальная информация о роли</p>
            </div>
            <div class="flex gap-2">
                @can('manage-roles')
                <a href="{{ route('admin.roles.edit', $role) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-edit mr-1"></i> Редактировать
                </a>
                @endcan
                <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Назад
                </a>
            </div>
        </div>

        <!-- Role Details -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <!-- Basic Information -->
            <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 px-6 py-3">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Основная информация</h2>
            </div>
            <div class="px-6 py-4">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $role->id }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Значение (slug)</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            <code class="px-2 py-1 bg-gray-100 dark:bg-gray-700 dark:text-gray-300 rounded">{{ $role->value }}</code>
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Titles -->
            <div class="border-t border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 px-6 py-3">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Названия</h2>
            </div>
            <div class="px-6 py-4">
                <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Русский</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $role->title_ru }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Казахский</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $role->title_kk }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Английский</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $role->title_en }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Descriptions -->
            <div class="border-t border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 px-6 py-3">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Описания</h2>
            </div>
            <div class="px-6 py-4">
                <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Русский</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $role->description_ru ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Казахский</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $role->description_kk ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Английский</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $role->description_en ?: '—' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Properties -->
            <div class="border-t border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 px-6 py-3">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Свойства</h2>
            </div>
            <div class="px-6 py-4">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Статус</dt>
                        <dd class="mt-1">
                            @if($role->is_active)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-300">
                                Активна
                            </span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-300">
                                Неактивна
                            </span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Тип</dt>
                        <dd class="mt-1">
                            @if($role->is_system)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 dark:bg-purple-900/20 text-purple-800 dark:text-purple-300">
                                Системная
                            </span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                Обычная
                            </span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Административная</dt>
                        <dd class="mt-1">
                            @if($role->is_administrative)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-300">
                                Да
                            </span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                Нет
                            </span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Доступна при регистрации</dt>
                        <dd class="mt-1">
                            @if($role->can_register)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-300">
                                Да
                            </span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                Нет
                            </span>
                            @endif
                        </dd>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="border-t border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 px-6 py-3">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Статистика</h2>
            </div>
            <div class="px-6 py-4">
                <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Пользователей с этой ролью</dt>
                        <dd class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $role->users_count }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Дата создания</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $role->created_at->format('d.m.Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Дата обновления</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $role->updated_at->format('d.m.Y H:i') }}</dd>
                    </div>
                </dl>
            </div>

            @if(!$role->is_system && $role->users_count === 0)
            <!-- Delete Action -->
            <div class="border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 px-6 py-4">
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
