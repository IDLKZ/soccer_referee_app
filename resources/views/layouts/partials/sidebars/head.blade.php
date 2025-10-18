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

            <!-- Referee Team Approval -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Судейские бригады
                </p>
            </div>

            <a href="{{ route('referee-team-approval-cards') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('referee-team-approval-cards') || request()->routeIs('referee-team-approval-detail') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <i class="fas fa-clipboard-check w-5"></i>
                <span class="ml-3">Утверждение судейской бригады</span>
            </a>

            <!-- Business Trips -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Командировки
                </p>
            </div>

            <a href="{{ route('final-business-trip-confirmation') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('final-business-trip-confirmation') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <i class="fas fa-clipboard-check w-5"></i>
                <span class="ml-3">Финальная проверка</span>
            </a>

            <!-- Protocols -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Протоколы
                </p>
            </div>

            <a href="{{ route('control-protocol-approval') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('control-protocol-approval') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <i class="fas fa-shield-check w-5"></i>
                <span class="ml-3">Финальная проверка протоколов</span>
            </a>
        </nav>
    </div>
</aside>
