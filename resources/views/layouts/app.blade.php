<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Система управления судейством')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50">
    @auth
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800">
                            <i class="fas fa-futbol text-blue-600 mr-2"></i>
                            Судейская система
                        </a>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <!-- Матчи -->
                        @can('view-matches')
                        <a href="{{ route('matches') }}"
                           class="{{ request()->routeIs('matches') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}
                              inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                            <i class="fas fa-calendar-alt mr-2"></i>Матчи
                        </a>
                        @endcan

                        <!-- Судьи -->
                        @can('manage-referees')
                        <a href="{{ route('referees') }}"
                           class="{{ request()->routeIs('referees') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}
                              inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                            <i class="fas fa-user-tie mr-2"></i>Судьи
                        </a>
                        @endcan

                        <!-- Финансы -->
                        @can('manage-finance')
                        <a href="{{ route('finance') }}"
                           class="{{ request()->routeIs('finance') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}
                              inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                            <i class="fas fa-money-check-alt mr-2"></i>Финансы
                        </a>
                        @endcan

                        <!-- Администрирование -->
                        @can('access-admin-panel')
                        <a href="{{ route('dashboard') }}"
                           class="{{ request()->routeIs('admin.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}
                              inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                            <i class="fas fa-cogs mr-2"></i>Администрирование
                        </a>
                        @endcan

                        <!-- Пользователи -->
                        @can('manage-users')
                        <a href="{{ route('users') }}"
                           class="{{ request()->routeIs('users') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}
                              inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                            <i class="fas fa-users mr-2"></i>Пользователи
                        </a>
                        @endcan
                    </div>
                </div>

                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <!-- Уведомления -->
                        <button class="relative p-2 text-gray-600 hover:text-gray-900">
                            <i class="fas fa-bell"></i>
                            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400"></span>
                        </button>
                    </div>

                    <!-- Профиль пользователя -->
                    <div class="ml-3 relative">
                        <div class="flex items-center space-x-3">
                            <div class="text-right hidden md:block">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ Auth::user()->full_name }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ Auth::user()->role->title_ru ?? 'Без роли' }}
                                </div>
                            </div>
                            <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-medium">
                                {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
                            </div>
                            <div class="relative">
                                <button class="flex items-center text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700">
                                    <i class="fas fa-chevron-down ml-1"></i>
                                </button>

                                <!-- Dropdown menu -->
                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                                    <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user mr-2"></i>Профиль
                                    </a>
                                    @if(Auth::user()->can('manage-system-settings'))
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-cog mr-2"></i>Настройки
                                    </a>
                                    @endif
                                    <div class="border-t border-gray-100"></div>
                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Выход
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="sm:hidden border-t border-gray-200">
            <div class="pt-2 pb-3 space-y-1">
                @can('view-matches')
                <a href="{{ route('matches') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('matches') ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium">
                    <i class="fas fa-calendar-alt mr-2"></i>Матчи
                </a>
                @endcan

                @can('manage-referees')
                <a href="{{ route('referees') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('referees') ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium">
                    <i class="fas fa-user-tie mr-2"></i>Судьи
                </a>
                @endcan

                @can('manage-finance')
                <a href="{{ route('finance') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('finance') ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium">
                    <i class="fas fa-money-check-alt mr-2"></i>Финансы
                </a>
                @endcan

                @can('manage-users')
                <a href="{{ route('users') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('users') ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium">
                    <i class="fas fa-users mr-2"></i>Пользователи
                </a>
                @endcan

                @can('access-admin-panel')
                <a href="{{ route('dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('admin.*') ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium">
                    <i class="fas fa-cogs mr-2"></i>Администрирование
                </a>
                @endcan
            </div>
        </div>
    </nav>
    @endauth

    <main>
        @yield('content')
    </main>

    @livewireScripts
    <script>
        // Dropdown toggle
        document.querySelectorAll('.relative button').forEach(button => {
            if (button.querySelector('.fa-chevron-down')) {
                button.addEventListener('click', function() {
                    const dropdown = this.closest('.relative').querySelector('.hidden');
                    if (dropdown) {
                        dropdown.classList.toggle('hidden');
                    }
                });
            }
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.relative')) {
                document.querySelectorAll('.relative .hidden').forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            }
        });

        // Mobile menu toggle
        const mobileMenuButton = document.querySelector('[data-mobile-menu-button]');
        const mobileMenu = document.querySelector('[data-mobile-menu]');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>