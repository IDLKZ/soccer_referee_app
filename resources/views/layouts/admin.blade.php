<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ theme: localStorage.getItem('theme') || 'light' }" :class="{ 'dark': theme === 'dark' }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Администратор') - Судейская система</title>

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js будет загружен через Livewire -->

    <!-- Livewire Styles -->
    @livewireStyles

    <style>
        [x-cloak] { display: none !important; }

        /* Dark mode styles */
        .dark {
            color-scheme: dark;
        }

        .dark body {
            background-color: #1a202c;
            color: #e2e8f0;
        }

        .dark aside {
            background-color: #2d3748;
            border-color: #4a5568;
        }

        .dark header {
            background-color: #2d3748;
            border-color: #4a5568;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    @include('layouts.partials.header')

    <div class="flex">
        <!-- Sidebar -->
        @include('layouts.partials.sidebars.admin')

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto">
            <div class="container mx-auto px-6 py-8">
                @yield('content')
                {{ $slot ?? '' }}
            </div>
        </main>
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts

    @stack('scripts')

    <!-- Initialize theme -->
    <script>
        document.addEventListener('alpine:init', () => {
            const theme = localStorage.getItem('theme') || 'light';
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            }
        });
    </script>
</body>
</html>
