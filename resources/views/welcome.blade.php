<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Система управления судейством</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animate-fadeInUp { animation: fadeInUp 0.8s ease-out; }
        .animate-float { animation: float 3s ease-in-out infinite; }
        .gradient-animate {
            background: linear-gradient(-45deg, #1e3a8a, #3b82f6, #06b6d4, #0891b2);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen gradient-animate">
        <!-- Navigation -->
        <nav class="relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3 animate-fadeInUp">
                        <img src="{{ asset('images/logo_white.png') }}" alt="Logo" class="h-12 w-auto">
                        <span class="text-white text-xl font-bold hidden sm:block">Система управления судейством</span>
                    </div>
                    @if (Route::has('login'))
                        <div class="flex items-center space-x-4 animate-fadeInUp" style="animation-delay: 0.2s;">
                            @auth
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-white text-blue-600 rounded-lg font-semibold hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
                                    <i class="fas fa-home mr-2"></i>Панель управления
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 glass-effect text-white rounded-lg font-semibold hover:bg-white hover:bg-opacity-20 transition-all transform hover:scale-105">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Войти
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-white text-blue-600 rounded-lg font-semibold hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
                                        <i class="fas fa-user-plus mr-2"></i>Регистрация
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 animate-fadeInUp" style="animation-delay: 0.3s;">
                    Профессиональное управление
                    <span class="block text-transparent bg-clip-text bg-gradient-to-r from-cyan-200 to-blue-200">
                        судейством футбольных матчей
                    </span>
                </h1>
                <p class="text-xl md:text-2xl text-blue-100 mb-12 max-w-3xl mx-auto animate-fadeInUp" style="animation-delay: 0.4s;">
                    Комплексная система для назначения судей, управления логистикой, финансами и документооборотом
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-20 animate-fadeInUp" style="animation-delay: 0.5s;">
                    @guest
                        <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-4 bg-white text-blue-600 rounded-lg font-bold text-lg hover:bg-gray-100 transition-all transform hover:scale-105 shadow-2xl">
                            <i class="fas fa-sign-in-alt mr-3"></i>Войти в систему
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 glass-effect text-white rounded-lg font-bold text-lg hover:bg-white hover:bg-opacity-20 transition-all transform hover:scale-105">
                            <i class="fas fa-user-plus mr-3"></i>Создать аккаунт
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-8 py-4 bg-white text-blue-600 rounded-lg font-bold text-lg hover:bg-gray-100 transition-all transform hover:scale-105 shadow-2xl">
                            <i class="fas fa-home mr-3"></i>Перейти к панели управления
                        </a>
                    @endguest
                </div>

                <!-- Features -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate-fadeInUp" style="animation-delay: 0.6s;">
                    <div class="glass-effect rounded-xl p-6 card-hover">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-cyan-400 rounded-full flex items-center justify-center mx-auto mb-4 animate-float">
                            <i class="fas fa-futbol text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">Управление матчами</h3>
                        <p class="text-blue-100 text-sm">Создание, редактирование и отслеживание футбольных матчей с полным контролем всех этапов</p>
                    </div>

                    <div class="glass-effect rounded-xl p-6 card-hover">
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-teal-400 rounded-full flex items-center justify-center mx-auto mb-4 animate-float" style="animation-delay: 0.5s;">
                            <i class="fas fa-user-tie text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">Назначение судей</h3>
                        <p class="text-blue-100 text-sm">Автоматизированная система назначения судей на матчи с учетом квалификации и доступности</p>
                    </div>

                    <div class="glass-effect rounded-xl p-6 card-hover">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full flex items-center justify-center mx-auto mb-4 animate-float" style="animation-delay: 1s;">
                            <i class="fas fa-map-marked-alt text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">Логистика</h3>
                        <p class="text-blue-100 text-sm">Планирование командировок, бронирование отелей и организация транспорта для судей</p>
                    </div>

                    <div class="glass-effect rounded-xl p-6 card-hover">
                        <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-red-400 rounded-full flex items-center justify-center mx-auto mb-4 animate-float" style="animation-delay: 1.5s;">
                            <i class="fas fa-money-bill-wave text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">Финансы</h3>
                        <p class="text-blue-100 text-sm">Учет выплат, создание актов выполненных работ и контроль финансовых операций</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="glass-effect rounded-2xl p-8 animate-fadeInUp" style="animation-delay: 0.7s;">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div>
                        <div class="text-4xl md:text-5xl font-bold text-white mb-2">
                            <i class="fas fa-users mr-2 text-cyan-300"></i>8
                        </div>
                        <div class="text-blue-200 font-medium">Ролей в системе</div>
                    </div>
                    <div>
                        <div class="text-4xl md:text-5xl font-bold text-white mb-2">
                            <i class="fas fa-trophy mr-2 text-yellow-300"></i>∞
                        </div>
                        <div class="text-blue-200 font-medium">Лиг и турниров</div>
                    </div>
                    <div>
                        <div class="text-4xl md:text-5xl font-bold text-white mb-2">
                            <i class="fas fa-language mr-2 text-green-300"></i>3
                        </div>
                        <div class="text-blue-200 font-medium">Языка интерфейса</div>
                    </div>
                    <div>
                        <div class="text-4xl md:text-5xl font-bold text-white mb-2">
                            <i class="fas fa-shield-alt mr-2 text-blue-300"></i>100%
                        </div>
                        <div class="text-blue-200 font-medium">Безопасность</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roles -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center mb-12 animate-fadeInUp" style="animation-delay: 0.8s;">
                <h2 class="text-4xl font-bold text-white mb-4">Роли в системе</h2>
                <p class="text-xl text-blue-100">Каждая роль имеет свои права доступа и функциональные возможности</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate-fadeInUp" style="animation-delay: 0.9s;">
                <div class="glass-effect rounded-xl p-6 text-center card-hover">
                    <div class="w-20 h-20 bg-gradient-to-br from-red-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-crown text-white text-3xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-white mb-2">Администратор</h4>
                    <p class="text-blue-100 text-sm">Полный доступ ко всем функциям системы</p>
                </div>

                <div class="glass-effect rounded-xl p-6 text-center card-hover">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-shield text-white text-3xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-white mb-2">Директор департамента</h4>
                    <p class="text-blue-100 text-sm">Управление департаментом судейства</p>
                </div>

                <div class="glass-effect rounded-xl p-6 text-center card-hover">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-cog text-white text-3xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-white mb-2">Сотрудник департамента</h4>
                    <p class="text-blue-100 text-sm">Назначение судей и управление матчами</p>
                </div>

                <div class="glass-effect rounded-xl p-6 text-center card-hover">
                    <div class="w-20 h-20 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-whistle text-white text-3xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-white mb-2">Судья</h4>
                    <p class="text-blue-100 text-sm">Просмотр назначений и ответ на приглашения</p>
                </div>

                <div class="glass-effect rounded-xl p-6 text-center card-hover">
                    <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-violet-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-route text-white text-3xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-white mb-2">Логист</h4>
                    <p class="text-blue-100 text-sm">Организация командировок и транспорта</p>
                </div>

                <div class="glass-effect rounded-xl p-6 text-center card-hover">
                    <div class="w-20 h-20 bg-gradient-to-br from-teal-500 to-cyan-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-hand-holding-usd text-white text-3xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-white mb-2">Финансовый директор</h4>
                    <p class="text-blue-100 text-sm">Контроль финансовых операций</p>
                </div>

                <div class="glass-effect rounded-xl p-6 text-center card-hover">
                    <div class="w-20 h-20 bg-gradient-to-br from-cyan-500 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calculator text-white text-3xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-white mb-2">Финансовый специалист</h4>
                    <p class="text-blue-100 text-sm">Обработка выплат и документов</p>
                </div>

                <div class="glass-effect rounded-xl p-6 text-center card-hover">
                    <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-invoice-dollar text-white text-3xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-white mb-2">Бухгалтер</h4>
                    <p class="text-blue-100 text-sm">Ведение бухгалтерского учета</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="relative z-10 mt-20 border-t border-white border-opacity-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="flex items-center space-x-3 mb-4 md:mb-0">
                        <img src="{{ asset('images/logo_white.png') }}" alt="Logo" class="h-10 w-auto">
                        <span class="text-white font-semibold">© {{ date('Y') }} Система управления судейством</span>
                    </div>
                    <div class="flex items-center space-x-6 text-blue-200">
                        <a href="#" class="hover:text-white transition-colors">
                            <i class="fas fa-question-circle mr-1"></i>Помощь
                        </a>
                        <a href="#" class="hover:text-white transition-colors">
                            <i class="fas fa-book mr-1"></i>Документация
                        </a>
                        <a href="#" class="hover:text-white transition-colors">
                            <i class="fas fa-envelope mr-1"></i>Контакты
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
