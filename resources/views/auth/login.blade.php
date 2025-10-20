<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в систему - Судейская система</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        @keyframes pulse-scale {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes slide-up {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-shadow {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .floating {
            animation: float 6s ease-in-out infinite;
        }

        .pulse-hover:hover {
            animation: pulse-scale 0.3s ease-in-out;
        }

        .slide-up {
            animation: slide-up 0.6s ease-out;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .input-focus {
            transition: all 0.3s ease;
        }

        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.3);
        }

        .btn-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.4);
        }

        .soccer-ball {
            position: absolute;
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            opacity: 0.1;
            animation: float 8s ease-in-out infinite;
        }

        .soccer-ball::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 30% 30%, #ffffff 0%, #e5e7eb 100%);
            border-radius: 50%;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="soccer-ball" style="top: 10%; left: 10%; animation-delay: 0s;"></div>
    <div class="soccer-ball" style="top: 20%; right: 15%; animation-delay: 2s;"></div>
    <div class="soccer-ball" style="bottom: 20%; left: 20%; animation-delay: 4s;"></div>
    <div class="soccer-ball" style="bottom: 30%; right: 10%; animation-delay: 6s;"></div>

    <div class="max-w-md w-full relative z-10">
        <div class="glass-effect rounded-2xl card-shadow p-8 slide-up">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl mb-4 floating pulse-hover">
                    <i class="fas fa-futbol text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    Добро пожаловать
                </h1>
                <p class="text-gray-600 text-sm">
                    Система управления футбольным судейством
                </p>
            </div>
            <form class="space-y-6" method="POST" action="{{ route('login') }}">
                @csrf
                <input type="hidden" name="remember" value="true">

                @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl slide-up">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400 mt-0.5"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-medium text-red-800">Ошибка входа</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="space-y-5">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope text-gray-400 mr-2"></i>Email
                        </label>
                        <div class="relative">
                            <input id="email" name="email" type="email" autocomplete="email" required
                                   value="{{ old('email') }}"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 input-focus focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white bg-opacity-80"
                                   placeholder="your@email.com">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock text-gray-400 mr-2"></i>Пароль
                        </label>
                        <div class="relative">
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 input-focus focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white bg-opacity-80"
                                   placeholder="••••••••">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between py-2">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember" type="checkbox"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-colors">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-700 cursor-pointer select-none">
                            Запомнить меня
                        </label>
                    </div>
                    <div class="text-sm">
                        <a href="#" class="text-blue-600 hover:text-blue-500 transition-colors">
                            Забыли пароль?
                        </a>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full flex justify-center items-center px-4 py-3 border border-transparent text-sm font-semibold rounded-xl text-white btn-gradient focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Войти в систему
                    </button>
                </div>

                @if(Route::has('register'))
                <div class="text-center pt-4 border-t border-gray-200">
                    <span class="text-sm text-gray-600">
                        Новые в системе?
                    </span>
                    <a href="{{ route('register') }}" class="ml-1 text-sm font-medium text-blue-600 hover:text-blue-500 transition-colors">
                        Создать аккаунт
                    </a>
                </div>
                @endif
            </form>
        </div>
    </div>

    <script>
        // Add interactive features
        document.addEventListener('DOMContentLoaded', function() {
            // Password visibility toggle
            const passwordInput = document.getElementById('password');
            const passwordIcon = passwordInput.nextElementSibling.querySelector('i');

            // Create toggle button
            const toggleButton = document.createElement('button');
            toggleButton.type = 'button';
            toggleButton.className = 'absolute inset-y-0 right-0 pr-3 flex items-center focus:outline-none';
            toggleButton.innerHTML = '<i class="fas fa-eye text-gray-400 hover:text-gray-600 transition-colors"></i>';

            passwordInput.parentElement.appendChild(toggleButton);

            toggleButton.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                const icon = this.querySelector('i');
                if (type === 'text') {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });

            // Input focus effects
            const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.parentElement.classList.add('scale-[1.02]');
                });

                input.addEventListener('blur', function() {
                    this.parentElement.parentElement.classList.remove('scale-[1.02]');
                });
            });

            // Remember checkbox animation
            const checkbox = document.getElementById('remember-me');
            checkbox.addEventListener('change', function() {
                const label = this.nextElementSibling;
                if (this.checked) {
                    label.classList.add('text-blue-600');
                    label.classList.remove('text-gray-700');
                } else {
                    label.classList.remove('text-blue-600');
                    label.classList.add('text-gray-700');
                }
            });

            // Form submission animation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Вход...';
            });

            // Add floating animation to background elements
            const soccerBalls = document.querySelectorAll('.soccer-ball');
            soccerBalls.forEach((ball, index) => {
                ball.style.animationDelay = `${index * 2}s`;

                // Add random movement
                setInterval(() => {
                    const currentTop = parseInt(ball.style.top);
                    const newTop = currentTop + (Math.random() - 0.5) * 20;
                    ball.style.top = `${Math.max(5, Math.min(90, newTop))}%`;
                }, 3000 + index * 1000);
            });

            // Form validation enhancement
            const emailInput = document.getElementById('email');
            emailInput.addEventListener('input', function() {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (this.value && !emailRegex.test(this.value)) {
                    this.classList.add('border-red-300');
                    this.classList.remove('border-gray-200');
                } else {
                    this.classList.remove('border-red-300');
                    this.classList.add('border-gray-200');
                }
            });

            // Loading state for page
            document.body.style.opacity = '0';
            setTimeout(() => {
                document.body.style.transition = 'opacity 0.5s ease-in-out';
                document.body.style.opacity = '1';
            }, 100);
        });
    </script>
</body>
</html>