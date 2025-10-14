<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="flex items-center justify-between px-6 py-4">
        <!-- Logo and Title -->
        <div class="flex items-center">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg flex items-center justify-center">
                    <i class="fas fa-futbol text-white text-xl"></i>
                </div>
                <div class="hidden md:block">
                    <h1 class="text-xl font-bold text-gray-900">Судейская система</h1>
                    <p class="text-xs text-gray-500">Управление судейством</p>
                </div>
            </a>
        </div>

        <!-- Right Side: Language, Theme, Notifications, Profile -->
        <div class="flex items-center space-x-4">
            <!-- Language Switcher -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="flex items-center space-x-2 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-globe"></i>
                    <span class="hidden sm:inline">{{ strtoupper(app()->getLocale()) }}</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>

                <div x-show="open"
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50"
                     style="display: none;">
                    <a href="{{ route('locale.switch', 'ru') }}"
                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() === 'ru' ? 'bg-blue-50 text-blue-700' : '' }}">
                        <span class="mr-3">🇷🇺</span>
                        <span>Русский</span>
                        @if(app()->getLocale() === 'ru')
                        <i class="fas fa-check ml-auto text-blue-600"></i>
                        @endif
                    </a>
                    <a href="{{ route('locale.switch', 'kk') }}"
                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() === 'kk' ? 'bg-blue-50 text-blue-700' : '' }}">
                        <span class="mr-3">🇰🇿</span>
                        <span>Қазақша</span>
                        @if(app()->getLocale() === 'kk')
                        <i class="fas fa-check ml-auto text-blue-600"></i>
                        @endif
                    </a>
                    <a href="{{ route('locale.switch', 'en') }}"
                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() === 'en' ? 'bg-blue-50 text-blue-700' : '' }}">
                        <span class="mr-3">🇬🇧</span>
                        <span>English</span>
                        @if(app()->getLocale() === 'en')
                        <i class="fas fa-check ml-auto text-blue-600"></i>
                        @endif
                    </a>
                </div>
            </div>

            <!-- Theme Switcher -->
            <div class="relative" x-data="{ theme: localStorage.getItem('theme') || 'light' }">
                <button @click="
                    theme = theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', theme);
                    if (theme === 'dark') {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                "
                class="p-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas" :class="theme === 'dark' ? 'fa-sun' : 'fa-moon'"></i>
                </button>
            </div>

            <!-- Notifications -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="relative p-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-bell"></i>
                    <span class="absolute top-1 right-1 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                </button>

                <div x-show="open"
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg py-2 z-50"
                     style="display: none;">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900">Уведомления</h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <div class="px-4 py-3 hover:bg-gray-50 cursor-pointer">
                            <p class="text-sm text-gray-900">Новый матч назначен</p>
                            <p class="text-xs text-gray-500 mt-1">5 минут назад</p>
                        </div>
                        <div class="px-4 py-3 hover:bg-gray-50 cursor-pointer">
                            <p class="text-sm text-gray-900">Платеж одобрен</p>
                            <p class="text-xs text-gray-500 mt-1">1 час назад</p>
                        </div>
                    </div>
                    <div class="px-4 py-2 border-t border-gray-200">
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-800">Посмотреть все</a>
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="flex items-center space-x-3 px-3 py-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <div class="hidden md:block text-right">
                        <div class="text-sm font-medium text-gray-900">
                            {{ Auth::user()->full_name }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ Auth::user()->role->title_ru ?? 'Без роли' }}
                        </div>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold">
                        {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
                    </div>
                    <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                </button>

                <div x-show="open"
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-2 z-50"
                     style="display: none;">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-900">{{ Auth::user()->full_name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                    <a href="{{ route('profile') }}"
                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user w-5"></i>
                        <span class="ml-2">Профиль</span>
                    </a>
                    <a href="#"
                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-cog w-5"></i>
                        <span class="ml-2">Настройки</span>
                    </a>
                    <div class="border-t border-gray-200 my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="flex items-center w-full px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                            <i class="fas fa-sign-out-alt w-5"></i>
                            <span class="ml-2">Выход</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
