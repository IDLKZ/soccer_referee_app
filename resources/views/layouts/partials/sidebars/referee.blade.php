<aside class="w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 min-h-screen">
    <div class="px-4 py-6">
        <!-- Sidebar Navigation -->
        <nav class="space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-home w-5"></i>
                <span class="ml-3">Главная</span>
            </a>

            <!-- My Matches -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Мои матчи
                </p>
            </div>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-calendar-alt w-5"></i>
                <span class="ml-3">Все матчи</span>
            </a>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-clock w-5"></i>
                <span class="ml-3">Предстоящие</span>
            </a>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-history w-5"></i>
                <span class="ml-3">История</span>
            </a>

            <!-- Assignments -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Назначения
                </p>
            </div>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-tasks w-5"></i>
                <span class="ml-3">Мои назначения</span>
            </a>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-bell w-5"></i>
                <span class="ml-3">Требуют ответа</span>
            </a>

            <!-- Protocols -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Протоколы
                </p>
            </div>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-file-alt w-5"></i>
                <span class="ml-3">Мои протоколы</span>
            </a>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-exclamation-triangle w-5"></i>
                <span class="ml-3">Требуют заполнения</span>
            </a>

            <!-- Trips -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Командировки
                </p>
            </div>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-plane w-5"></i>
                <span class="ml-3">Мои командировки</span>
            </a>

            <!-- Finance -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Финансы
                </p>
            </div>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-money-bill-wave w-5"></i>
                <span class="ml-3">Мои выплаты</span>
            </a>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-file-invoice w-5"></i>
                <span class="ml-3">Акты работ</span>
            </a>

            <!-- My Profile -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Профиль
                </p>
            </div>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-user w-5"></i>
                <span class="ml-3">Мой профиль</span>
            </a>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-chart-bar w-5"></i>
                <span class="ml-3">Моя статистика</span>
            </a>

            <!-- Calendar -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Календарь
                </p>
            </div>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-calendar w-5"></i>
                <span class="ml-3">Мой календарь</span>
            </a>
        </nav>
    </div>
</aside>
