@extends('layouts.app')

@section('title', 'Панель управления')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Заголовок -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">
                Добро пожаловать, {{ Auth::user()->first_name }}!
            </h1>
            <p class="mt-1 text-sm text-gray-600">
                Ваша роль: {{ Auth::user()->role->title_ru }}
            </p>
        </div>

        <!-- Статистика -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @can('view-matches')
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-white"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Всего матчей
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ \App\Models\Match::count() }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            @endcan

            @can('manage-referees')
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <i class="fas fa-user-tie text-white"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Судьи
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ \App\Models\User::whereHas('role', function($q) {
                                        $q->where('value', \App\Constants\RoleConstants::SOCCER_REFEREE);
                                    })->count() }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            @endcan

            @can('manage-finance')
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                <i class="fas fa-money-check-alt text-white"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Ожидают оплаты
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ \App\Models\ActOfPayment::where('status', 'pending')->count() }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            @endcan

            @can('manage-users')
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <i class="fas fa-users text-white"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Пользователи
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ \App\Models\User::count() }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
        </div>

        <!-- Быстрые действия -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    Быстрые действия
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @can('manage-matches')
                    <a href="{{ route('matches') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-plus mr-2"></i>
                        Создать матч
                    </a>
                    @endcan

                    @can('manage-referees')
                    <a href="{{ route('referees') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-user-plus mr-2"></i>
                        Добавить судью
                    </a>
                    @endcan

                    @can('create-users')
                    <a href="{{ route('users') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        <i class="fas fa-user-plus mr-2"></i>
                        Создать пользователя
                    </a>
                    @endcan

                    @can('approve-payments')
                    <a href="{{ route('finance') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        <i class="fas fa-check-circle mr-2"></i>
                        Одобрить платежи
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Последние действия -->
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
            @can('view-matches')
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        Последние матчи
                    </h3>
                    @if(\App\Models\Match::count() > 0)
                    <div class="space-y-3">
                        @php
                        $recentMatches = \App\Models\Match::with(['league', 'season'])->latest()->take(5)->get();
                        @endphp
                        @foreach($recentMatches as $match)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $match->title_ru ?? 'Матч ' . $match->id }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $match->match_date ? $match->match_date->format('d.m.Y H:i') : '' }}
                                </p>
                            </div>
                            <a href="{{ route('matches') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                Подробнее
                            </a>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-sm text-gray-500">Матчей пока нет</p>
                    @endif
                </div>
            </div>
            @endcan

            @can('manage-finance')
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        Финансовые операции
                    </h3>
                    @if(\App\Models\ActOfPayment::count() > 0)
                    <div class="space-y-3">
                        @php
                        $recentPayments = \App\Models\ActOfPayment::with(['workAct.judge', 'checkedBy'])->latest()->take(5)->get();
                        @endphp
                        @foreach($recentPayments as $payment)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $payment->workAct->judge->full_name ?? '-' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ number_format($payment->amount ?? 0, 2, ',', ' ') }} ₸
                                </p>
                            </div>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                   {{ $payment->status === 'approved' ? 'bg-green-100 text-green-800' :
                                      ($payment->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ $payment->status == 'approved' ? 'Одобрен' :
                                   ($payment->status == 'rejected' ? 'Отклонен' : 'Ожидает') }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-sm text-gray-500">Финансовых операций пока нет</p>
                    @endif
                </div>
            </div>
            @endcan
        </div>
    </div>
</div>
@endsection