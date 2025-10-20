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

            <!-- Business Processes -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Бизнес процессы
                </p>
            </div>

            @php
                // Контрольная финансовая проверка АВР
                // Условие: operation = 'control_financial_check' И last_financial_status = 0
                $controlFinancialCheckCount = \App\Models\ActOfWork::whereHas('operation', function($q) {
                    $q->where('value', 'control_financial_check');
                })
                ->where('last_financial_status', 0)
                ->count();
            @endphp

            <a href="{{ route('control-financial-check') }}"
               class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('control-financial-check') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <div class="flex items-center">
                    <i class="fas fa-clipboard-check w-5"></i>
                    <span class="ml-3">Контрольная финансовая проверка АВР</span>
                </div>
                @if($controlFinancialCheckCount > 0)
                    <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                        {{ $controlFinancialCheckCount }}
                    </span>
                @endif
            </a>

        </nav>
    </div>
</aside>
