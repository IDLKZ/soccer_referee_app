<aside class="w-64 bg-white border-r border-gray-200 min-h-screen">
    <div class="px-4 py-6">
        <!-- Sidebar Navigation -->
        <nav class="space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-home w-5"></i>
                <span class="ml-3">Главная</span>
            </a>

            <!-- Accounting -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Бухгалтерия
                </p>
            </div>

            <a href="{{ route('accounting.overview') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('accounting.overview*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-chart-line w-5"></i>
                <span class="ml-3">Обзор</span>
            </a>

            <a href="{{ route('accounting.transactions') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('accounting.transactions*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-exchange-alt w-5"></i>
                <span class="ml-3">Транзакции</span>
            </a>

            <!-- Payments -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Платежи
                </p>
            </div>

            <a href="{{ route('accounting.payments') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('accounting.payments*') && !request()->routeIs('accounting.payments.pending') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-money-bill-wave w-5"></i>
                <span class="ml-3">Все платежи</span>
            </a>

            <a href="{{ route('accounting.payments.pending') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('accounting.payments.pending*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-hourglass-half w-5"></i>
                <span class="ml-3">Ожидают оплаты</span>
                @if($pendingPayments = \App\Models\ActOfPayment::where('status', 0)->count())
                <span class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    {{ $pendingPayments }}
                </span>
                @endif
            </a>

            <a href="{{ route('accounting.payments.completed') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('accounting.payments.completed*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-check-circle w-5"></i>
                <span class="ml-3">Выполненные</span>
            </a>

            <!-- Acts of Work -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Акты
                </p>
            </div>

            <a href="{{ route('accounting.acts') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('accounting.acts*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-file-invoice w-5"></i>
                <span class="ml-3">Акты работ</span>
            </a>

            <!-- Reconciliation -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Сверка
                </p>
            </div>

            <a href="{{ route('accounting.reconciliation') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('accounting.reconciliation*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-balance-scale w-5"></i>
                <span class="ml-3">Сверка платежей</span>
            </a>

            <!-- Reports -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Отчеты
                </p>
            </div>

            <a href="{{ route('accounting.reports.financial') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('accounting.reports.financial*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-file-invoice-dollar w-5"></i>
                <span class="ml-3">Финансовые отчеты</span>
            </a>

            <a href="{{ route('accounting.reports.tax') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('accounting.reports.tax*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-percent w-5"></i>
                <span class="ml-3">Налоговые отчеты</span>
            </a>

            <a href="{{ route('accounting.reports.export') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('accounting.reports.export*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-file-export w-5"></i>
                <span class="ml-3">Экспорт данных</span>
            </a>

            <!-- Reference Books -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Справочники
                </p>
            </div>

            <a href="{{ route('referees') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('referees*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-user-tie w-5"></i>
                <span class="ml-3">Судьи</span>
            </a>

            <a href="{{ route('accounting.services') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('accounting.services*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-concierge-bell w-5"></i>
                <span class="ml-3">Услуги</span>
            </a>
        </nav>
    </div>
</aside>
