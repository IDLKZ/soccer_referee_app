<aside class="w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 min-h-screen">
    <div class="px-4 py-6">
        <!-- Sidebar Navigation -->
        <nav class="space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <i class="fas fa-home w-5"></i>
                <span class="ml-3">Главная</span>
            </a>

            <!-- Invitations -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Приглашения
                </p>
            </div>

            <a href="{{ route('referee.my-invitations') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('referee.my-invitations') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <i class="fas fa-envelope w-5"></i>
                <span class="ml-3">Мои приглашения</span>
            </a>

            <!-- Business Trips -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Командировки
                </p>
            </div>

            <a href="{{ route('referee.my-business-trips') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('referee.my-business-trips') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <i class="fas fa-suitcase w-5"></i>
                <span class="ml-3">Мои командировки</span>
            </a>
        </nav>
    </div>
</aside>
