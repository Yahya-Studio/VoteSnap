<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
      class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title . ' — ' . config('app.name', 'VoteSnap') : config('app.name', 'VoteSnap') . ' — ' . __('Create. Share. Vote.') }}</title>

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

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="antialiased bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen flex flex-col selection:bg-indigo-500 selection:text-white transition-colors duration-200">
        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Page Heading (Optional) -->
        @isset($header)
            <header class="bg-white/70 dark:bg-slate-900/70 border-b border-slate-200/80 dark:border-slate-800/80 backdrop-blur-sm">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Main Content -->
        <main class="flex-1">
            {{ $slot }}
        </main>

        <!-- Global Toast Notifications -->
        <div x-data="{
                toasts: [],
                add(toast) {
                    toast.id = Date.now() + Math.random();
                    this.toasts.push(toast);
                    setTimeout(() => this.remove(toast.id), toast.duration || 4000);
                },
                remove(id) {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }
             }"
             x-on:toast.window="add($event.detail)"
             class="fixed bottom-5 end-5 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none p-4">
            <template x-for="toast in toasts" :key="toast.id">
                <div x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-3 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="pointer-events-auto p-4 rounded-2xl shadow-xl flex items-center gap-3 border text-sm font-medium backdrop-blur-md"
                     :class="{
                        'bg-emerald-50 dark:bg-emerald-950/90 text-emerald-900 dark:text-emerald-100 border-emerald-200 dark:border-emerald-800': toast.type === 'success',
                        'bg-rose-50 dark:bg-rose-950/90 text-rose-900 dark:text-rose-100 border-rose-200 dark:border-rose-800': toast.type === 'error',
                        'bg-indigo-50 dark:bg-indigo-950/90 text-indigo-900 dark:text-indigo-100 border-indigo-200 dark:border-indigo-800': toast.type === 'info' || !toast.type
                     }">
                    <span class="shrink-0">
                        <template x-if="toast.type === 'success'">
                            <x-icon name="check-circle" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                        </template>
                        <template x-if="toast.type === 'error'">
                            <x-icon name="alert-circle" class="w-5 h-5 text-rose-600 dark:text-rose-400" />
                        </template>
                        <template x-if="toast.type !== 'success' && toast.type !== 'error'">
                            <x-icon name="sparkles" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
                        </template>
                    </span>
                    <span class="flex-1" x-text="toast.message"></span>
                    <button @click="remove(toast.id)" class="shrink-0 p-1 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <x-icon name="x" class="w-4 h-4" />
                    </button>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <footer class="mt-auto border-t border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-slate-900/60 transition-colors">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <x-application-logo />
                    <span class="text-xs text-slate-400 dark:text-slate-500 border-s border-slate-200 dark:border-slate-800 ps-3">
                        {{ __('Create. Share. Vote.') }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 text-center sm:text-end">
                    &copy; {{ date('Y') }} VoteSnap. {{ __('All rights reserved.') }}
                </p>
            </div>
        </footer>

        @livewireScripts
        @stack('scripts')
    </body>
</html>
