<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'VoteSnap') }}</title>

        <!-- Google Fonts: Plus Jakarta Sans for LTR & Cairo for Arabic RTL -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <style>
            :root {
                --font-sans: 'Plus Jakarta Sans', system-ui, sans-serif;
            }
            [dir="rtl"] {
                --font-sans: 'Cairo', system-ui, sans-serif;
                font-family: var(--font-sans);
            }
            body {
                font-family: var(--font-sans);
            }
            [x-cloak] { display: none !important; }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @livewireScripts
    </head>
    <body class="font-sans text-slate-800 dark:text-slate-100 antialiased bg-slate-50 dark:bg-slate-950 min-h-screen flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8 selection:bg-indigo-500 selection:text-white">
        <!-- Floating Header with Language & Dark Mode -->
        <div class="fixed top-4 end-4 flex items-center gap-2 z-50">
            <a href="{{ request()->fullUrlWithQuery(['lang' => app()->getLocale() === 'ar' ? 'en' : 'ar']) }}"
               class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-white dark:hover:bg-slate-800 transition-colors text-xs font-bold uppercase tracking-wider flex items-center gap-1 shadow-sm">
                <x-icon name="globe" class="w-4 h-4" />
                <span>{{ app()->getLocale() === 'ar' ? 'EN' : 'عربي' }}</span>
            </a>

            <button type="button"
                    x-data="{ darkMode: localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) }"
                    @click="
                        darkMode = !darkMode;
                        if (darkMode) {
                            document.documentElement.classList.add('dark');
                            localStorage.theme = 'dark';
                        } else {
                            document.documentElement.classList.remove('dark');
                            localStorage.theme = 'light';
                        }
                    "
                    class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:text-amber-500 dark:hover:text-amber-400 hover:bg-white dark:hover:bg-slate-800 transition-colors shadow-sm"
                    aria-label="Toggle theme">
                <span x-show="!darkMode">
                    <x-icon name="moon" class="w-5 h-5" />
                </span>
                <span x-show="darkMode" x-cloak>
                    <x-icon name="sun" class="w-5 h-5 text-amber-400" />
                </span>
            </button>
        </div>

        <div class="w-full sm:max-w-md flex flex-col items-center">
            <a href="{{ route('home') }}" class="mb-6 group transition-transform active:scale-95">
                <x-application-logo class="text-2xl" />
            </a>

            <div class="w-full bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-200/80 dark:border-slate-800/80 rounded-3xl p-6 sm:p-8">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
