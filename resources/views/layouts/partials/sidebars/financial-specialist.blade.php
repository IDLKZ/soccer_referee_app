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

            <!-- Payments -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Платежи
                </p>
            </div>

            <a href="{{ route('finance.payments') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('finance.payments*') && !request()->routeIs('finance.payments.pending') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-money-bill-wave w-5"></i>
                <span class="ml-3">Все платежи</span>
            </a>

            <a href="{{ route('finance.payments.pending') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('finance.payments.pending*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-hourglass-half w-5"></i>
                <span class="ml-3">Ожидают обработки</span>
                @if($pendingPayments = \App\Models\ActOfPayment::where('status', 0)->count())
                <span class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    {{ $pendingPayments }}
                </span>
                @endif
            </a>

            <a href="{{ route('finance.payments.processed') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('finance.payments.processed*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-check-circle w-5"></i>
                <span class="ml-3">Обработанные</span>
            </a>

            <!-- Acts of Work -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Акты
                </p>
            </div>

            <a href="{{ route('finance.acts') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('finance.acts*') && !request()->routeIs('finance.acts.review') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-file-invoice w-5"></i>
                <span class="ml-3">Акты работ</span>
            </a>

            <a href="{{ route('finance.acts.review') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('finance.acts.review*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-clipboard-check w-5"></i>
                <span class="ml-3">На проверке</span>
                @if($reviewActs = \App\Models\ActOfWork::where('first_status', 0)->count())
                <span class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    {{ $reviewActs }}
                </span>
                @endif
            </a>

            <!-- Services -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Услуги
                </p>
            </div>

            <a href="{{ route('finance.services') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('finance.services*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-concierge-bell w-5"></i>
                <span class="ml-3">Услуги и тарифы</span>
            </a>

            <!-- Reports -->
            <div class="pt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Отчеты
                </p>
            </div>

            <a href="{{ route('finance.reports.summary') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('finance.reports.summary*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-chart-pie w-5"></i>
                <span class="ml-3">Сводка</span>
            </a>

            <a href="{{ route('finance.reports.detailed') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('finance.reports.detailed*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-file-alt w-5"></i>
                <span class="ml-3">Детализация</span>
            </a>

            <!-- Referees -->
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
        </nav>
    </div>
</aside>
