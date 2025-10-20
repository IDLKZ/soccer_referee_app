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

            @can('manage-act-payments')
                @php
                    // Оплатные документы АВР
                    // Матчи в операциях: avr_reprocessing, avr_confirmed_waiting_payment, payment_completed
                    $paymentDocumentsCount = \App\Models\MatchEntity::whereHas('operation', function($q) {
                        $q->whereIn('value', [
                            'avr_reprocessing',
                            'avr_confirmed_waiting_payment',
                            'payment_completed'
                        ]);
                    })->count();
                @endphp
                <a href="{{ route('act-of-payments') }}"
                   class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('act-of-payments') ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <div class="flex items-center">
                        <i class="fas fa-file-invoice-dollar w-5"></i>
                        <span class="ml-3">Оплатные документы АВР</span>
                    </div>
                    @if($paymentDocumentsCount > 0)
                        <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                            {{ $paymentDocumentsCount }}
                        </span>
                    @endif
                </a>
            @endcan
        </nav>
    </div>
</aside>
