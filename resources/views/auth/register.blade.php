<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация - Судейская система</title>
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

        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
        }

        .step-indicator {
            transition: all 0.3s ease;
        }

        .step-indicator.active {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
        }

        .step-indicator.completed {
            background: #10b981;
            color: white;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen py-8 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="soccer-ball" style="top: 10%; left: 10%; animation-delay: 0s;"></div>
    <div class="soccer-ball" style="top: 20%; right: 15%; animation-delay: 2s;"></div>
    <div class="soccer-ball" style="bottom: 20%; left: 20%; animation-delay: 4s;"></div>
    <div class="soccer-ball" style="bottom: 30%; right: 10%; animation-delay: 6s;"></div>

    <div class="max-w-4xl mx-auto relative z-10">
        <div class="glass-effect rounded-2xl card-shadow p-8 slide-up">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl mb-4 floating pulse-hover">
                    <i class="fas fa-user-plus text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    Создание аккаунта
                </h1>
                <p class="text-gray-600 text-sm">
                    Присоединяйтесь к системе управления футбольным судейством
                </p>
            </div>
            <form class="space-y-6" method="POST" action="{{ route('register') }}">
                @csrf

                @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl slide-up">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400 mt-0.5"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-medium text-red-800">Ошибка регистрации</h3>
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

                <!-- Personal Information Section -->
                <div class="bg-white bg-opacity-60 rounded-xl p-6 backdrop-blur-sm">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user text-blue-500 mr-2"></i>
                        Личная информация
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Фамилия <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input id="last_name" name="last_name" type="text" required
                                       value="{{ old('last_name') }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 input-focus focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white bg-opacity-80"
                                       placeholder="Иванов">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Имя <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input id="first_name" name="first_name" type="text" required
                                       value="{{ old('first_name') }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 input-focus focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white bg-opacity-80"
                                       placeholder="Иван">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="patronomic" class="block text-sm font-medium text-gray-700 mb-2">
                                Отчество
                            </label>
                            <div class="relative">
                                <input id="patronomic" name="patronomic" type="text"
                                       value="{{ old('patronomic') }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 input-focus focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white bg-opacity-80"
                                       placeholder="Иванович">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="bg-white bg-opacity-60 rounded-xl p-6 backdrop-blur-sm">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-address-book text-blue-500 mr-2"></i>
                        Контактная информация
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope text-gray-400 mr-2"></i>Email <span class="text-red-500">*</span>
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
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-phone text-gray-400 mr-2"></i>Телефон <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input id="phone" name="phone" type="tel" required
                                       value="{{ old('phone') }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 input-focus focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white bg-opacity-80"
                                       placeholder="+7 (701) 234-56-78">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-phone text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information Section -->
                <div class="bg-white bg-opacity-60 rounded-xl p-6 backdrop-blur-sm">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        Дополнительная информация
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="iin" class="block text-sm font-medium text-gray-700 mb-2">
                                ИИН
                            </label>
                            <div class="relative">
                                <input id="iin" name="iin" type="text" maxlength="12"
                                       value="{{ old('iin') }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 input-focus focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white bg-opacity-80"
                                       placeholder="123456789012">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-id-card text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Дата рождения
                            </label>
                            <div class="relative">
                                <input id="birth_date" name="birth_date" type="date"
                                       value="{{ old('birth_date') }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 input-focus focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white bg-opacity-80">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="sex" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-venus-mars text-gray-400 mr-2"></i>Пол <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="sex" name="sex" required
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white bg-opacity-80 appearance-none">
                                    <option value="">Выберите пол</option>
                                    <option value="1" {{ old('sex') == '1' ? 'selected' : '' }}>Мужской</option>
                                    <option value="2" {{ old('sex') == '2' ? 'selected' : '' }}>Женский</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Role Section -->
                <div class="bg-white bg-opacity-60 rounded-xl p-6 backdrop-blur-sm">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user-tag text-blue-500 mr-2"></i>
                        Роль в системе
                    </h3>

                    <div>
                        <label for="role_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Роль <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="role_id" name="role_id" required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white bg-opacity-80 appearance-none">
                                <option value="">Выберите роль</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->title_ru }}
                                </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Password Section -->
                <div class="bg-white bg-opacity-60 rounded-xl p-6 backdrop-blur-sm">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-lock text-blue-500 mr-2"></i>
                        Безопасность
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Пароль <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input id="password" name="password" type="password" autocomplete="new-password" required
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 input-focus focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white bg-opacity-80"
                                       placeholder="Минимум 8 символов">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Подтверждение пароля <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 input-focus focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white bg-opacity-80"
                                       placeholder="Повторите пароль">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                            class="w-full flex justify-center items-center px-4 py-3 border border-transparent text-sm font-semibold rounded-xl text-white btn-gradient focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-user-plus mr-2"></i>
                        Зарегистрироваться
                    </button>
                </div>

                @if(Route::has('login'))
                <div class="text-center pt-4 border-t border-gray-200">
                    <span class="text-sm text-gray-600">
                        Уже есть аккаунт?
                    </span>
                    <a href="{{ route('login') }}" class="ml-1 text-sm font-medium text-blue-600 hover:text-blue-500 transition-colors">
                        Войти в систему
                    </a>
                </div>
                @endif
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password visibility toggles
            const passwordInput = document.getElementById('password');
            const passwordConfirmationInput = document.getElementById('password_confirmation');

            function createPasswordToggle(input) {
                const toggleButton = document.createElement('button');
                toggleButton.type = 'button';
                toggleButton.className = 'absolute inset-y-0 right-0 pr-3 flex items-center focus:outline-none';
                toggleButton.innerHTML = '<i class="fas fa-eye text-gray-400 hover:text-gray-600 transition-colors"></i>';

                input.parentElement.appendChild(toggleButton);

                toggleButton.addEventListener('click', function() {
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);

                    const icon = this.querySelector('i');
                    if (type === 'text') {
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            }

            createPasswordToggle(passwordInput);
            createPasswordToggle(passwordConfirmationInput);

            // Password strength checker
            const passwordStrengthIndicator = document.createElement('div');
            passwordStrengthIndicator.className = 'mt-2 h-2 bg-gray-200 rounded-full overflow-hidden';
            passwordStrengthIndicator.innerHTML = '<div id="strength-bar" class="h-full transition-all duration-300" style="width: 0%"></div>';
            passwordInput.parentElement.parentElement.appendChild(passwordStrengthIndicator);

            const strengthText = document.createElement('p');
            strengthText.className = 'mt-1 text-xs text-gray-600';
            strengthText.textContent = '';
            passwordInput.parentElement.parentElement.appendChild(strengthText);

            function checkPasswordStrength(password) {
                let strength = 0;
                const strengthBar = document.getElementById('strength-bar');
                const strengthTextElement = strengthText;

                if (password.length >= 8) strength++;
                if (password.length >= 12) strength++;
                if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^a-zA-Z0-9]/.test(password)) strength++;

                const strengthLevels = [
                    { width: '0%', color: 'bg-gray-300', text: '' },
                    { width: '20%', color: 'bg-red-500', text: 'Слабый пароль' },
                    { width: '40%', color: 'bg-orange-500', text: 'Нормальный пароль' },
                    { width: '60%', color: 'bg-yellow-500', text: 'Хороший пароль' },
                    { width: '80%', color: 'bg-blue-500', text: 'Сильный пароль' },
                    { width: '100%', color: 'bg-green-500', text: 'Очень сильный пароль' }
                ];

                const level = strengthLevels[strength];
                strengthBar.style.width = level.width;
                strengthBar.className = `h-full transition-all duration-300 ${level.color}`;
                strengthTextElement.textContent = level.text;
                strengthTextElement.className = `mt-1 text-xs ${level.color.replace('bg-', 'text-')}`;
            }

            passwordInput.addEventListener('input', function() {
                checkPasswordStrength(this.value);
            });

            // Password confirmation matching
            function checkPasswordMatch() {
                const password = passwordInput.value;
                const confirmation = passwordConfirmationInput.value;

                if (confirmation === '') return;

                const confirmationGroup = passwordConfirmationInput.parentElement.parentElement;
                const matchIndicator = confirmationGroup.querySelector('.match-indicator');

                if (!matchIndicator) {
                    const indicator = document.createElement('div');
                    indicator.className = 'match-indicator mt-1 text-xs flex items-center';
                    confirmationGroup.appendChild(indicator);
                }

                const indicator = confirmationGroup.querySelector('.match-indicator');

                if (password === confirmation) {
                    indicator.innerHTML = '<i class="fas fa-check-circle text-green-500 mr-1"></i><span class="text-green-600">Пароли совпадают</span>';
                    passwordConfirmationInput.classList.remove('border-red-300');
                    passwordConfirmationInput.classList.add('border-green-300');
                } else {
                    indicator.innerHTML = '<i class="fas fa-times-circle text-red-500 mr-1"></i><span class="text-red-600">Пароли не совпадают</span>';
                    passwordConfirmationInput.classList.remove('border-green-300');
                    passwordConfirmationInput.classList.add('border-red-300');
                }
            }

            passwordConfirmationInput.addEventListener('input', checkPasswordMatch);
            passwordInput.addEventListener('input', checkPasswordMatch);

            // Form validation enhancement
            const emailInput = document.getElementById('email');
            const phoneInput = document.getElementById('phone');
            const iinInput = document.getElementById('iin');

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

            phoneInput.addEventListener('input', function() {
                // Format phone number as user types
                let value = this.value.replace(/\D/g, '');
                if (value.startsWith('7')) value = value.substring(1);

                if (value.length > 0) {
                    if (value.length <= 3) {
                        value = `+7 (${value}`;
                    } else if (value.length <= 6) {
                        value = `+7 (${value.slice(0, 3)}) ${value.slice(3)}`;
                    } else if (value.length <= 8) {
                        value = `+7 (${value.slice(0, 3)}) ${value.slice(3, 6)}-${value.slice(6)}`;
                    } else {
                        value = `+7 (${value.slice(0, 3)}) ${value.slice(3, 6)}-${value.slice(6, 8)}-${value.slice(8, 10)}`;
                    }
                }

                this.value = value;
            });

            iinInput.addEventListener('input', function() {
                // Only allow numbers, max 12 digits
                this.value = this.value.replace(/\D/g, '').slice(0, 12);
            });

            // Focus effects
            const inputs = document.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.parentElement.classList.add('scale-[1.02]');
                });

                input.addEventListener('blur', function() {
                    this.parentElement.parentElement.classList.remove('scale-[1.02]');
                });
            });

            // Form submission with loading state
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Регистрация...';
            });

            // Animated background elements
            const soccerBalls = document.querySelectorAll('.soccer-ball');
            soccerBalls.forEach((ball, index) => {
                ball.style.animationDelay = `${index * 2}s`;

                setInterval(() => {
                    const currentTop = parseInt(ball.style.top);
                    const newTop = currentTop + (Math.random() - 0.5) * 20;
                    ball.style.top = `${Math.max(5, Math.min(90, newTop))}%`;
                }, 3000 + index * 1000);
            });

            // Smooth page load
            document.body.style.opacity = '0';
            setTimeout(() => {
                document.body.style.transition = 'opacity 0.5s ease-in-out';
                document.body.style.opacity = '1';
            }, 100);

            // Section hover effects
            const sections = document.querySelectorAll('.bg-white.bg-opacity-60');
            sections.forEach(section => {
                section.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 10px 25px -5px rgba(0, 0, 0, 0.1)';
                });

                section.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '';
                });
            });
        });
    </script>
</body>
</html>