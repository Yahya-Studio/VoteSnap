<x-app-layout>
    <x-slot name="title">{{ __('VoteSnap — أنشئ استطلاعاتك وشاركها فوراً') }}</x-slot>

    <!-- ================= HERO SECTION ================= -->
    <section class="relative overflow-hidden pt-12 pb-20 lg:pt-20 lg:pb-28">
        <!-- Background Gradient Glows -->
        <div class="absolute top-1/4 start-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[400px] bg-gradient-to-tr from-indigo-500/20 via-purple-500/15 to-transparent blur-3xl pointer-events-none -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">

                <!-- Hero Content (Text & Call to actions) -->
                <div class="lg:col-span-7 space-y-6 text-center lg:text-start">
                    <!-- Brand Pill -->
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200/80 dark:border-indigo-800/80 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                        <span>VoteSnap — {{ __('الجيل القادم من الاستطلاعات السريعة') }}</span>
                    </div>

                    <!-- Main Headline -->
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 dark:text-white tracking-tight leading-[1.15]">
                        {{ __('Create polls. Get answers instantly.') }}
                    </h1>

                    <!-- Subtitle -->
                    <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 max-w-2xl leading-relaxed mx-auto lg:mx-0">
                        {{ __('The modern, lightning-fast polling platform for creators, communities, and teams. Create interactive polls in seconds, share with a single link, and track live results in real time.') }}
                    </p>

                    <!-- CTAs -->
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3.5 pt-2">
                        <a href="{{ route('polls.create') }}"
                           class="w-full sm:w-auto px-8 py-4 rounded-2xl font-black text-base text-white bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 hover:from-indigo-500 hover:to-purple-500 shadow-xl shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all transform active:scale-95 flex items-center justify-center gap-2.5">
                            <x-icon name="plus" class="w-5 h-5" />
                            <span>{{ __('Create a Poll') }}</span>
                        </a>

                        @guest
                            <a href="{{ route('register') }}"
                               class="w-full sm:w-auto px-6 py-4 rounded-2xl font-bold text-base text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200/80 dark:border-slate-800 shadow-sm transition-colors text-center">
                                {{ __('إنشاء حساب مجاني') }}
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}"
                               class="w-full sm:w-auto px-6 py-4 rounded-2xl font-bold text-base text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200/80 dark:border-slate-800 shadow-sm transition-colors text-center flex items-center justify-center gap-2">
                                <x-icon name="chart" class="w-5 h-5 text-indigo-500" />
                                <span>{{ __('لوحة تحكمي') }}</span>
                            </a>
                        @endguest
                    </div>

                    <!-- Trust Metrics / Badges -->
                    <div class="pt-4 flex flex-wrap items-center justify-center lg:justify-start gap-6 text-xs text-slate-500 font-medium">
                        <div class="flex items-center gap-2">
                            <x-icon name="check-circle" class="w-4 h-4 text-emerald-500" />
                            <span>{{ __('مشاركة وتصويت فوري بدون تسجيل') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-icon name="check-circle" class="w-4 h-4 text-emerald-500" />
                            <span>{{ __('حماية ضد التلاعب وتكرار الأصوات') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-icon name="check-circle" class="w-4 h-4 text-emerald-500" />
                            <span>{{ __('تحديث مباشر للنتائج') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Hero Interactive Demo Poll (Live Reactive Preview) -->
                <div class="lg:col-span-5"
                     x-data="{
                        selected: 1,
                        voted: false,
                        votes: [
                            { id: 1, text: '{{ __('واجهات مستخدم نظيفة وبسيطة (Minimal SaaS)') }}', count: 142 },
                            { id: 2, text: '{{ __('سرعة فائقة ونتائج حية في نفس اللحظة') }}', count: 189 },
                            { id: 3, text: '{{ __('تخصيص كامل للقواعد والخيارات والمواعيد') }}', count: 96 }
                        ],
                        get total() {
                            return this.votes.reduce((acc, curr) => acc + curr.count, 0);
                        },
                        vote(id) {
                            if (this.voted) return;
                            this.selected = id;
                            const item = this.votes.find(v => v.id === id);
                            if (item) item.count++;
                            this.voted = true;
                        }
                     }">
                    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 shadow-2xl shadow-indigo-500/10 border border-slate-200/80 dark:border-slate-800/80 relative transition-all">

                        <!-- Floating Live Indicator Badge -->
                        <div class="flex items-center justify-between gap-2 mb-4">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    {{ __('معاينة تفاعلية حية') }}
                                </span>
                                <span class="text-xs text-slate-400 font-medium">
                                    {{ __('جرّب بنفسك!') }}
                                </span>
                            </div>

                            <span class="text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400">
                                <span x-text="total"></span> {{ __('مشارك') }}
                            </span>
                        </div>

                        <!-- Demo Poll Question -->
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white leading-snug mb-5">
                            {{ __('ما هي أهم ميزة تبحث عنها عند إجراء استطلاع رأي؟') }}
                        </h2>

                        <!-- Options List -->
                        <div class="space-y-3">
                            <template x-for="item in votes" :key="item.id">
                                <div @click="vote(item.id)"
                                     class="p-4 rounded-2xl border-2 transition-all cursor-pointer relative overflow-hidden"
                                     :class="{
                                        'border-indigo-600 bg-indigo-50/40 dark:bg-indigo-950/30': selected === item.id,
                                        'border-slate-200 dark:border-slate-800 hover:border-indigo-300 dark:hover:border-indigo-800': selected !== item.id
                                     }">
                                    <!-- Background Bar for Percentage when voted -->
                                    <div x-show="voted"
                                         class="absolute inset-y-0 start-0 bg-indigo-100/60 dark:bg-indigo-900/40 transition-all duration-700 -z-0"
                                         :style="'width: ' + Math.round((item.count / total) * 100) + '%'"></div>

                                    <div class="relative z-10 flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center shrink-0"
                                                 :class="selected === item.id ? 'border-indigo-600 bg-indigo-600 text-white' : 'border-slate-300 dark:border-slate-600'">
                                                <template x-if="selected === item.id">
                                                    <x-icon name="check" class="w-3 h-3" />
                                                </template>
                                            </div>
                                            <span class="text-sm font-semibold text-slate-800 dark:text-slate-100 leading-snug" x-text="item.text"></span>
                                        </div>

                                        <div x-show="voted" class="flex items-center gap-2 shrink-0 font-mono text-xs font-bold text-indigo-600 dark:text-indigo-400">
                                            <span x-text="Math.round((item.count / total) * 100) + '%'"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs text-slate-400">
                            <span x-text="voted ? '{{ __('تم التصويت بنجاح!') }}' : '{{ __('انقر فوق أي خيار للتصويت ومشاهدة النتيجة') }}'"></span>
                            <span class="flex items-center gap-1 text-indigo-600 font-semibold">
                                <x-icon name="clock" class="w-3.5 h-3.5" />
                                <span>{{ __('مباشر') }}</span>
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= HOW IT WORKS (3 STEPS) ================= -->
    <section id="how-it-works" class="py-16 sm:py-24 bg-white dark:bg-slate-900/50 border-y border-slate-200/80 dark:border-slate-800/80 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 block mb-2">
                    {{ __('How It Works') }}
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight">
                    {{ __('Three simple steps to gather opinions and make decisions.') }}
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="bg-slate-50 dark:bg-slate-900 p-8 rounded-3xl border border-slate-200/80 dark:border-slate-800 relative group hover:border-indigo-400 dark:hover:border-indigo-700 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white font-black text-xl flex items-center justify-center mb-6 shadow-md shadow-indigo-500/25">
                        1
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">
                        {{ __('1. Create your poll') }}
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        {{ __('Craft your question and options in seconds with full customization.') }}
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="bg-slate-50 dark:bg-slate-900 p-8 rounded-3xl border border-slate-200/80 dark:border-slate-800 relative group hover:border-indigo-400 dark:hover:border-indigo-700 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-purple-600 text-white font-black text-xl flex items-center justify-center mb-6 shadow-md shadow-purple-500/25">
                        2
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">
                        {{ __('2. Share your link') }}
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        {{ __('Distribute your unique poll link via WhatsApp, Telegram, social media, or QR code.') }}
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="bg-slate-50 dark:bg-slate-900 p-8 rounded-3xl border border-slate-200/80 dark:border-slate-800 relative group hover:border-indigo-400 dark:hover:border-indigo-700 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white font-black text-xl flex items-center justify-center mb-6 shadow-md shadow-emerald-500/25">
                        3
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">
                        {{ __('3. See the results') }}
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        {{ __('Watch votes roll in live with dynamic progress bars and instant charts.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= FEATURES SECTION ================= -->
    <section id="features" class="py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 block mb-2">
                    {{ __('Features') }}
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight">
                    {{ __('Everything you need for interactive polling') }}
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">

                <!-- Feature 1 -->
                <div class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 shadow-sm hover:shadow-md transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-5">
                        <x-icon name="chart" class="w-6 h-6" />
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                        {{ __('Instant Live Results') }}
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        {{ __('Real-time updates without refreshing the page using reactive polling.') }}
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 shadow-sm hover:shadow-md transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center mb-5">
                        <x-icon name="lock" class="w-6 h-6" />
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                        {{ __('Anti-Duplicate Protection') }}
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        {{ __('Intelligent fraud and ballot stuffing prevention to keep your data trustworthy.') }}
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 shadow-sm hover:shadow-md transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-5">
                        <x-icon name="clock" class="w-6 h-6" />
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                        {{ __('Flexible Scheduling') }}
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        {{ __('Schedule start and auto-close times with live dynamic countdown clocks.') }}
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 shadow-sm hover:shadow-md transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-5">
                        <x-icon name="sparkles" class="w-6 h-6" />
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                        {{ __('Multi-Choice & Custom Rules') }}
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        {{ __('Support for single or multiple choices, voting edits, and randomized choices.') }}
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 shadow-sm hover:shadow-md transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 flex items-center justify-center mb-5">
                        <x-icon name="vote" class="w-6 h-6" />
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                        {{ __('Dashboard & Analytics') }}
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        {{ __('Monitor total votes, active polls, participation metrics, and export data.') }}
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 shadow-sm hover:shadow-md transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center mb-5">
                        <x-icon name="share" class="w-6 h-6" />
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                        {{ __('Seamless Sharing') }}
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        {{ __('Direct copy, WhatsApp, Telegram, email, and one-tap QR Code generation.') }}
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= BOTTOM CTA BANNER ================= -->
    <section class="py-16 sm:py-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 rounded-3xl p-8 sm:p-14 text-center text-white shadow-2xl shadow-indigo-500/25 relative overflow-hidden">
                <h2 class="text-3xl sm:text-4xl font-black tracking-tight mb-4">
                    {{ __('Ready to start your first poll?') }}
                </h2>
                <p class="text-indigo-100 max-w-xl mx-auto text-base sm:text-lg mb-8">
                    {{ __('Create your poll now in seconds and share with your audience directly.') }}
                </p>
                <a href="{{ route('polls.create') }}"
                   class="inline-flex items-center gap-2 px-8 py-4 rounded-2xl font-black text-base text-indigo-900 bg-white hover:bg-indigo-50 shadow-lg transition-all transform active:scale-95">
                    <x-icon name="plus" class="w-5 h-5 text-indigo-600" />
                    <span>{{ __('Create a Poll') }}</span>
                </a>
            </div>
        </div>
    </section>

</x-app-layout>
