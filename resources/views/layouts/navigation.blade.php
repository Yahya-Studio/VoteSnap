<nav x-data="{ open: false, darkMode: localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) }"
     class="sticky top-0 z-40 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800/80 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Left Side: Brand & Links -->
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="group transition-transform active:scale-95">
                    <x-application-logo />
                </a>

                <div class="hidden md:flex items-center gap-1">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="px-3.5 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                            {{ __('Dashboard') }}
                        </a>
                        <a href="{{ route('polls.create') }}"
                           class="px-3.5 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('polls.create') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                            {{ __('New Poll') }}
                        </a>
                    @else
                        <a href="{{ route('home') }}#features"
                           class="px-3.5 py-2 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            {{ __('Features') }}
                        </a>
                        <a href="{{ route('home') }}#how-it-works"
                           class="px-3.5 py-2 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            {{ __('How It Works') }}
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Right Side: Dark Mode, Language, Auth Actions -->
            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Create Poll Quick CTA Button (Visible on medium+ screens) -->
                <a href="{{ route('polls.create') }}"
                   class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 shadow-md shadow-indigo-500/20 hover:shadow-indigo-500/35 transition-all transform active:scale-95">
                    <x-icon name="plus" class="w-4 h-4" />
                    <span>{{ __('Create a Poll') }}</span>
                </a>

                <!-- Language Switcher -->
                <a href="{{ request()->fullUrlWithQuery(['lang' => app()->getLocale() === 'ar' ? 'en' : 'ar']) }}"
                   class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors text-xs font-bold uppercase tracking-wider flex items-center gap-1"
                   title="{{ app()->getLocale() === 'ar' ? 'Switch to English' : 'التحويل إلى العربية' }}">
                    <x-icon name="globe" class="w-4 h-4" />
                    <span>{{ app()->getLocale() === 'ar' ? 'EN' : 'عربي' }}</span>
                </a>

                <!-- Dark Mode Toggle Button -->
                <button type="button"
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
                        class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:text-amber-500 dark:hover:text-amber-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                        aria-label="Toggle theme">
                    <span x-show="!darkMode">
                        <x-icon name="moon" class="w-5 h-5" />
                    </span>
                    <span x-show="darkMode" x-cloak>
                        <x-icon name="sun" class="w-5 h-5 text-amber-400" />
                    </span>
                </button>

                <!-- Auth Buttons or User Dropdown -->
                @auth
                    <div class="relative ms-1" x-data="{ userMenuOpen: false }">
                        <button @click="userMenuOpen = !userMenuOpen"
                                @click.outside="userMenuOpen = false"
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-200 bg-slate-100/80 dark:bg-slate-800/80 hover:bg-slate-200/80 dark:hover:bg-slate-700/80 transition-colors">
                            <div class="w-7 h-7 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold text-xs uppercase">
                                {{ mb_substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="max-w-[120px] truncate hidden sm:inline">{{ Auth::user()->name }}</span>
                            <x-icon name="chevron-down" class="w-4 h-4 text-slate-400" />
                        </button>

                        <div x-show="userMenuOpen"
                             x-cloak
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute end-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-2xl shadow-xl ring-1 ring-slate-900/5 dark:ring-white/10 py-1.5 z-50">
                            <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-700/50">
                                <p class="text-xs text-slate-400">{{ __('Signed in as') }}</p>
                                <p class="text-sm font-semibold text-slate-800 dark:text-slate-200 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <x-icon name="chart" class="w-4 h-4 text-slate-400" />
                                <span>{{ __('Dashboard') }}</span>
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <x-icon name="user" class="w-4 h-4 text-slate-400" />
                                <span>{{ __('Profile') }}</span>
                            </a>

                            <div class="border-t border-slate-100 dark:border-slate-700/50 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-start flex items-center gap-2 px-4 py-2 text-sm text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-colors">
                                    <x-icon name="lock" class="w-4 h-4" />
                                    <span>{{ __('Logout') }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="px-3 py-2 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            {{ __('Login') }}
                        </a>
                        <a href="{{ route('register') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 transition-colors">
                            {{ __('Register') }}
                        </a>
                    </div>
                @endauth

                <!-- Mobile Hamburger Button -->
                <button @click="open = !open" class="md:hidden p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    <x-icon name="filter" class="w-5 h-5" />
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Dropdown Menu -->
    <div x-show="open" x-cloak class="md:hidden border-t border-slate-200 dark:border-slate-800 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md px-4 pt-3 pb-4 space-y-2">
        <a href="{{ route('home') }}" class="block px-3 py-2 rounded-xl text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">
            {{ __('Home') }}
        </a>
        <a href="{{ route('polls.create') }}" class="block px-3 py-2 rounded-xl text-base font-semibold text-white bg-indigo-600 hover:bg-indigo-700 text-center">
            {{ __('Create a Poll') }}
        </a>
        @auth
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-xl text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">
                {{ __('Dashboard') }}
            </a>
            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-xl text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">
                {{ __('Profile') }}
            </a>
            <form method="POST" action="{{ route('logout') }}" class="pt-2">
                @csrf
                <button type="submit" class="w-full text-start px-3 py-2 rounded-xl text-base font-medium text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30">
                    {{ __('Logout') }}
                </button>
            </form>
        @else
            <div class="pt-2 flex gap-2">
                <a href="{{ route('login') }}" class="flex-1 text-center py-2 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800">
                    {{ __('Login') }}
                </a>
                <a href="{{ route('register') }}" class="flex-1 text-center py-2 rounded-xl text-sm font-semibold text-white bg-indigo-600">
                    {{ __('Register') }}
                </a>
            </div>
        @endauth
    </div>
</nav>
