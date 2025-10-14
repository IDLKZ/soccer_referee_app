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

            <!-- Analytics -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Аналитика
                </p>
            </div>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-chart-line w-5"></i>
                <span class="ml-3">Обзор</span>
            </a>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-wallet w-5"></i>
                <span class="ml-3">Бюджет</span>
            </a>

            <!-- Approval -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Утверждение
                </p>
            </div>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-check-double w-5"></i>
                <span class="ml-3">Платежи</span>
            </a>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-file-signature w-5"></i>
                <span class="ml-3">Акты работ</span>
            </a>

            <!-- Payments -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Платежи
                </p>
            </div>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-money-bill-wave w-5"></i>
                <span class="ml-3">Все платежи</span>
            </a>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-history w-5"></i>
                <span class="ml-3">История</span>
            </a>

            <!-- Acts -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Акты
                </p>
            </div>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-file-invoice w-5"></i>
                <span class="ml-3">Акты работ</span>
            </a>

            <!-- Reports -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Отчеты
                </p>
            </div>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-chart-pie w-5"></i>
                <span class="ml-3">Сводка</span>
            </a>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-file-invoice-dollar w-5"></i>
                <span class="ml-3">Финансовые отчеты</span>
            </a>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-file-export w-5"></i>
                <span class="ml-3">Экспорт данных</span>
            </a>

            <!-- Settings -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Настройки
                </p>
            </div>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-concierge-bell w-5"></i>
                <span class="ml-3">Услуги и тарифы</span>
            </a>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <i class="fas fa-users w-5"></i>
                <span class="ml-3">Пользователи</span>
            </a>
        </nav>
    </div>
</aside>
