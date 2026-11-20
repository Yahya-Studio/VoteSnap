<div wire:poll.5s="refreshResults"
     x-data="{
        showShareModal: false,
        qrGenerated: false,
        initQr() {
            if (this.qrGenerated) return;
            this.$nextTick(() => {
                const canvas = document.getElementById('poll-qr-canvas');
                if (canvas && window.QRCode) {
                    window.QRCode.toCanvas(canvas, window.location.href, {
                        width: 200,
                        margin: 2,
                        color: {
                            dark: '#4f46e5',
                            light: '#ffffff'
                        }
                    }, (err) => {
                        if (!err) this.qrGenerated = true;
                    });
                }
            });
        },
        downloadQr() {
            const canvas = document.getElementById('poll-qr-canvas');
            if (!canvas) return;
            const a = document.createElement('a');
            a.download = 'votesnap-poll-{{ $poll->slug }}.png';
            a.href = canvas.toDataURL('image/png');
            a.click();
        },
        copyLink() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                $dispatch('toast', {
                    type: 'success',
                    message: '{{ __('تم نسخ رابط التصويت إلى الحافظة بنجاح!') }}'
                });
            });
        }
     }"
     class="max-w-3xl mx-auto px-4 sm:px-6 py-8 sm:py-12">

    <!-- Poll Container Card -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-200/80 dark:border-slate-800/80 overflow-hidden transition-all">

        <!-- Top Header & Badges -->
        <div class="p-6 sm:p-8 border-b border-slate-100 dark:border-slate-800/80 bg-gradient-to-b from-slate-50/50 dark:from-slate-800/30 to-transparent">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <!-- Status Badge -->
                <div class="flex items-center gap-2">
                    @if ($poll->isClosed())
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400 border border-rose-200 dark:border-rose-900">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            {{ __('مغلق') }}
                        </span>
                    @elseif ($poll->isExpired())
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                            <x-icon name="clock" class="w-3.5 h-3.5" />
                            {{ __('انتهت المدة') }}
                        </span>
                    @elseif ($poll->isScheduled())
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400 border border-amber-200 dark:border-amber-900">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            {{ __('مجدول') }}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                            {{ __('نشط ومتاح للتصويت') }}
                        </span>
                    @endif

                    <!-- Choice Type Badge -->
                    <span class="text-xs px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-medium">
                        {{ $poll->isMultipleChoice() ? __('خيارات متعددة') : __('اختيار فردي') }}
                    </span>

                    @if ($poll->visibility === 'unlisted')
                        <span class="text-xs px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center gap-1">
                            <x-icon name="lock" class="w-3 h-3" />
                            {{ __('غير مدرج') }}
                        </span>
                    @endif
                </div>

                <!-- Action Buttons: Share & View Results -->
                <div class="flex items-center gap-2">
                    <button type="button"
                            @click="showShareModal = true; initQr();"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 transition-colors">
                        <x-icon name="share" class="w-3.5 h-3.5" />
                        <span>{{ __('مشاركة') }}</span>
                    </button>

                    @if ($poll->canViewResults(null, auth()->user()) && ! $showResultsView)
                        <button type="button"
                                wire:click="toggleResultsView"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                            <x-icon name="chart" class="w-3.5 h-3.5" />
                            <span>{{ __('النتائج') }}</span>
                        </button>
                    @elseif ($showResultsView && $hasVoted && $poll->allow_vote_change && $poll->canAcceptVotes())
                        <button type="button"
                                wire:click="changeVote"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 transition-colors">
                            <x-icon name="edit" class="w-3.5 h-3.5" />
                            <span>{{ __('تعديل صوتي') }}</span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Title & Question -->
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight leading-snug">
                {{ $poll->title }}
            </h1>

            @if ($poll->question && $poll->question !== $poll->title)
                <p class="mt-2 text-base sm:text-lg font-medium text-slate-700 dark:text-slate-300">
                    {{ $poll->question }}
                </p>
            @endif

            @if ($poll->description)
                <p class="mt-3 text-sm text-slate-500 dark:text-slate-400 leading-relaxed whitespace-pre-line">
                    {{ $poll->description }}
                </p>
            @endif

            <!-- Optional Image -->
            @if ($poll->image_path)
                <div class="mt-4 rounded-2xl overflow-hidden max-h-72 border border-slate-200 dark:border-slate-800">
                    <img src="{{ Str::startsWith($poll->image_path, 'http') ? $poll->image_path : asset('storage/' . $poll->image_path) }}"
                         alt="{{ $poll->title }}"
                         class="w-full h-full object-cover">
                </div>
            @endif

            <!-- Live Countdown Timer Component -->
            @if (($poll->isActive() && $poll->end_at) || $poll->isScheduled())
                <div x-data="{
                        targetTime: new Date('{{ ($poll->isScheduled() ? $poll->start_at : $poll->end_at)->toIso8601String() }}').getTime(),
                        days: 0, hours: 0, minutes: 0, seconds: 0,
                        isDone: false,
                        updateCountdown() {
                            const now = new Date().getTime();
                            const diff = this.targetTime - now;
                            if (diff <= 0) {
                                this.isDone = true;
                                $wire.refreshResults();
                                return;
                            }
                            this.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                            this.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            this.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                            this.seconds = Math.floor((diff % (1000 * 60)) / 1000);
                        }
                     }"
                     x-init="updateCountdown(); setInterval(() => updateCountdown(), 1000);"
                     x-show="!isDone"
                     class="mt-4 p-3 rounded-2xl bg-indigo-50/70 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-2 text-xs sm:text-sm font-semibold text-indigo-900 dark:text-indigo-200">
                        <x-icon name="clock" class="w-4 h-4 text-indigo-600 dark:text-indigo-400 shrink-0" />
                        <span>{{ $poll->isScheduled() ? __('يبدأ التصويت خلال:') : __('ينتهي التصويت خلال:') }}</span>
                    </div>

                    <div class="flex items-center gap-1.5 font-mono font-bold text-xs sm:text-sm text-indigo-700 dark:text-indigo-300" dir="ltr">
                        <span x-show="days > 0" class="bg-white dark:bg-slate-900 px-2 py-1 rounded-lg border border-indigo-200 dark:border-indigo-800">
                            <span x-text="days"></span>d
                        </span>
                        <span class="bg-white dark:bg-slate-900 px-2 py-1 rounded-lg border border-indigo-200 dark:border-indigo-800">
                            <span x-text="String(hours).padStart(2, '0')"></span>h
                        </span>
                        <span>:</span>
                        <span class="bg-white dark:bg-slate-900 px-2 py-1 rounded-lg border border-indigo-200 dark:border-indigo-800">
                            <span x-text="String(minutes).padStart(2, '0')"></span>m
                        </span>
                        <span>:</span>
                        <span class="bg-white dark:bg-slate-900 px-2 py-1 rounded-lg border border-indigo-200 dark:border-indigo-800 text-indigo-600 dark:text-indigo-400">
                            <span x-text="String(seconds).padStart(2, '0')"></span>s
                        </span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Card Body: Voting Mode OR Results Mode -->
        <div class="p-6 sm:p-8">

            @if (! $showResultsView)
                <!-- ================= VOTING INTERFACE ================= -->
                <div class="space-y-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                        @if ($poll->isMultipleChoice())
                            {{ __('اختر إجابة أو أكثر') }} @if($poll->max_choices) ({{ __('بحد أقصى :max', ['max' => $poll->max_choices]) }}) @endif
                        @else
                            {{ __('اختر إجابة واحدة') }}
                        @endif
                    </p>

                    @foreach ($displayOptions as $option)
                        @php
                            $isSelected = in_array($option->id, $selectedOptions);
                        @endphp
                        <button type="button"
                                wire:click="selectOption({{ $option->id }})"
                                @disabled(! $poll->canAcceptVotes())
                                class="w-full text-start p-4 sm:p-5 rounded-2xl border-2 transition-all flex items-center justify-between gap-4 group cursor-pointer {{ $isSelected ? 'border-indigo-600 bg-indigo-50/60 dark:bg-indigo-950/40 text-indigo-950 dark:text-white shadow-md shadow-indigo-500/10' : 'border-slate-200 dark:border-slate-800 hover:border-indigo-300 dark:hover:border-indigo-800 hover:bg-slate-50/50 dark:hover:bg-slate-800/40 text-slate-800 dark:text-slate-200' }}">
                            <div class="flex items-center gap-3.5">
                                <!-- Selection Indicator Circle/Box -->
                                <div class="w-5 h-5 rounded-{{ $poll->isMultipleChoice() ? 'md' : 'full' }} border-2 flex items-center justify-center transition-colors {{ $isSelected ? 'border-indigo-600 bg-indigo-600 text-white' : 'border-slate-300 dark:border-slate-600 group-hover:border-indigo-400' }}">
                                    @if ($isSelected)
                                        <x-icon name="check" class="w-3.5 h-3.5" />
                                    @endif
                                </div>
                                <span class="font-semibold text-base sm:text-lg leading-snug">
                                    {{ $option->option_text }}
                                </span>
                            </div>

                            @if ($hasVoted && in_array($option->id, $selectedOptions))
                                <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-indigo-200 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 shrink-0">
                                    {{ __('صوتك الحالي') }}
                                </span>
                            @endif
                        </button>
                    @endforeach

                    <!-- Submit Button & Action Bar -->
                    <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                        @if ($poll->canAcceptVotes())
                            <button type="button"
                                    wire:click="submitVote"
                                    wire:loading.attr="disabled"
                                    @disabled(empty($selectedOptions))
                                    class="w-full sm:w-auto px-8 py-3.5 rounded-2xl font-bold text-base text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all transform active:scale-95 disabled:opacity-50 disabled:pointer-events-none flex items-center justify-center gap-2">
                                <span wire:loading.remove wire:target="submitVote">
                                    {{ $hasVoted ? __('تحديث صوتي') : __('إرسال الصوت') }}
                                </span>
                                <span wire:loading wire:target="submitVote" class="flex items-center gap-2">
                                    <x-icon name="refresh" class="w-4 h-4 animate-spin" />
                                    <span>{{ __('جارٍ الحفظ...') }}</span>
                                </span>
                            </button>
                        @else
                            <div class="p-3 w-full rounded-2xl bg-slate-100 dark:bg-slate-800 text-center text-sm font-semibold text-slate-500">
                                {{ __('لا يمكن إرسال أصوات لهذا التصويت حالياً.') }}
                            </div>
                        @endif

                        @if ($poll->canViewResults(null, auth()->user()))
                            <button type="button"
                                    wire:click="toggleResultsView"
                                    class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                                <x-icon name="chart" class="w-4 h-4" />
                                <span>{{ __('عرض النتائج المباشرة') }}</span>
                            </button>
                        @endif
                    </div>
                </div>

            @else
                <!-- ================= LIVE RESULTS INTERFACE ================= -->
                <div class="space-y-6"
                     x-data="{
                        showChartClient: false,
                        chartType: 'bar',
                        labels: @js($poll->options->pluck('option_text')),
                        counts: @js($poll->options->pluck('votes_count')),
                        renderChart() {
                            this.$nextTick(() => {
                                const canvas = document.getElementById('pollChartCanvas');
                                if (!canvas || !window.Chart) return;
                                const existing = window.Chart.getChart(canvas);
                                if (existing) {
                                    existing.destroy();
                                }
                                const ctx = canvas.getContext('2d');
                                const isDark = document.documentElement.classList.contains('dark');
                                const textColor = isDark ? '#94a3b8' : '#64748b';
                                const gridColor = isDark ? '#334155' : '#e2e8f0';

                                new window.Chart(ctx, {
                                    type: this.chartType,
                                    data: {
                                        labels: this.labels,
                                        datasets: [{
                                            label: '{{ __('الأصوات') }}',
                                            data: this.counts,
                                            backgroundColor: [
                                                '#6366f1', '#8b5cf6', '#ec4899', '#06b6d4',
                                                '#10b981', '#f59e0b', '#3b82f6', '#14b8a6'
                                            ],
                                            borderRadius: 8
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                display: this.chartType === 'doughnut',
                                                labels: { color: textColor }
                                            }
                                        },
                                        scales: this.chartType === 'bar' ? {
                                            x: { ticks: { color: textColor }, grid: { display: false } },
                                            y: { ticks: { color: textColor, precision: 0 }, grid: { color: gridColor }, beginAtZero: true }
                                        } : {}
                                    }
                                });
                            });
                        },
                        switchType(type) {
                            this.chartType = type;
                            this.renderChart();
                        }
                     }"
                     x-init="
                        $wire.on('chart-data-updated', (event) => {
                            const data = Array.isArray(event) ? event[0] : event;
                            labels = data.labels;
                            counts = data.data;
                            if (showChartClient) renderChart();
                        });
                     ">

                    <!-- Results Overview Stats -->
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-2">
                            <span class="p-2 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400">
                                <x-icon name="users" class="w-5 h-5" />
                            </span>
                            <div>
                                <span class="text-xs text-slate-400 font-medium block">{{ __('إجمالي المشاركين') }}</span>
                                <span class="text-lg font-black text-slate-900 dark:text-white">{{ number_format($poll->votes_count) }}</span>
                            </div>
                        </div>

                        <!-- Toggle Chart Button -->
                        <div class="flex items-center gap-2">
                            <button type="button"
                                    @click="showChartClient = !showChartClient; if (showChartClient) renderChart();"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold transition-colors"
                                    :class="showChartClient ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300'">
                                <x-icon name="chart" class="w-3.5 h-3.5" />
                                <span x-text="showChartClient ? '{{ __('إخفاء المخطط') }}' : '{{ __('عرض المخطط البياني') }}'"></span>
                            </button>

                            <div x-show="showChartClient" x-cloak class="inline-flex rounded-xl p-0.5 bg-slate-100 dark:bg-slate-800 text-xs">
                                <button type="button"
                                        @click="switchType('bar')"
                                        class="px-2 py-1 rounded-lg transition-all"
                                        :class="chartType === 'bar' ? 'bg-white dark:bg-slate-700 shadow font-bold text-indigo-600 dark:text-indigo-400' : 'text-slate-500'">
                                    {{ __('أعمدة') }}
                                </button>
                                <button type="button"
                                        @click="switchType('doughnut')"
                                        class="px-2 py-1 rounded-lg transition-all"
                                        :class="chartType === 'doughnut' ? 'bg-white dark:bg-slate-700 shadow font-bold text-indigo-600 dark:text-indigo-400' : 'text-slate-500'">
                                    {{ __('دائري') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Chart Canvas (Controlled cleanly with x-show and wire:ignore) -->
                    <div x-show="showChartClient"
                         x-cloak
                         wire:ignore
                         class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-800 h-64">
                        <canvas id="pollChartCanvas"></canvas>
                    </div>

                    <!-- Ranked Progress Bars List -->
                    <div class="space-y-4">
                        @foreach ($sortedResults as $option)
                            @php
                                $pct = $option->percentage;
                                $isMyVote = in_array($option->id, $selectedOptions);
                            @endphp
                            <div class="p-4 rounded-2xl border {{ $isMyVote ? 'border-indigo-500/50 bg-indigo-50/30 dark:bg-indigo-950/20' : 'border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900' }} transition-all">
                                <div class="flex items-center justify-between gap-3 mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-slate-800 dark:text-slate-100 text-base">
                                            {{ $option->option_text }}
                                        </span>
                                        @if ($isMyVote)
                                            <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-100 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 font-bold">
                                                {{ __('صوتك') }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex items-baseline gap-2 shrink-0">
                                        <span class="text-sm font-semibold text-slate-500 dark:text-slate-400 font-mono">
                                            {{ $option->votes_count }} {{ __('صوت') }}
                                        </span>
                                        <span class="text-base font-black text-indigo-600 dark:text-indigo-400 font-mono">
                                            {{ $pct }}%
                                        </span>
                                    </div>
                                </div>

                                <!-- Animated Progress Bar -->
                                <div class="w-full h-3 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                    <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600 transition-all duration-700 ease-out"
                                         style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Bottom Controls in Results View -->
                    <div class="pt-4 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 dark:border-slate-800">
                        @if ($poll->canAcceptVotes())
                            @if ($hasVoted && $poll->allow_vote_change)
                                <button type="button"
                                        wire:click="changeVote"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/60 hover:bg-indigo-100 transition-colors">
                                    <x-icon name="edit" class="w-4 h-4" />
                                    <span>{{ __('تعديل خياري') }}</span>
                                </button>
                            @elseif (! $hasVoted)
                                <button type="button"
                                        wire:click="toggleResultsView"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                                    <x-icon name="vote" class="w-4 h-4" />
                                    <span>{{ __('العودة إلى خيارات التصويت') }}</span>
                                </button>
                            @endif
                        @endif

                        <div class="flex items-center gap-2 text-xs text-slate-400 font-medium">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>{{ __('تحديث مباشر وتلقائي كل 5 ثوانٍ') }}</span>
                        </div>
                    </div>
                </div>
            @endif

        </div>

    </div>

    <!-- ================= SHARE MODAL ================= -->
    <div x-show="showShareModal"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">

        <div @click.outside="showShareModal = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800 max-w-md w-full p-6 sm:p-8 space-y-6">

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="p-2 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400">
                        <x-icon name="share" class="w-5 h-5" />
                    </span>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">
                        {{ __('مشاركة التصويت') }}
                    </h3>
                </div>
                <button type="button" @click="showShareModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1 rounded-lg">
                    <x-icon name="x" class="w-5 h-5" />
                </button>
            </div>

            <!-- Direct Link Copy Field -->
            <div class="space-y-2">
                <label class="text-xs font-semibold text-slate-500">{{ __('رابط التصويت المباشر') }}</label>
                <div class="flex items-center gap-2">
                    <input type="text"
                           readonly
                           :value="window.location.href"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-xs sm:text-sm font-mono text-slate-600 dark:text-slate-300 focus:outline-none select-all" />
                    <button type="button"
                            @click="copyLink()"
                            class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition-colors shrink-0 flex items-center gap-1.5 shadow-md shadow-indigo-500/20">
                        <x-icon name="copy" class="w-4 h-4" />
                        <span>{{ __('نسخ') }}</span>
                    </button>
                </div>
            </div>

            <!-- Social Share Buttons -->
            <div class="space-y-2">
                <label class="text-xs font-semibold text-slate-500">{{ __('المشاركة عبر التطبيقات') }}</label>
                <div class="grid grid-cols-3 gap-2">
                    <!-- WhatsApp -->
                    <a :href="'https://wa.me/?text=' + encodeURIComponent('{{ $poll->title }}' + '\n' + window.location.href)"
                       target="_blank"
                       rel="noopener"
                       class="flex flex-col items-center justify-center p-3 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-100 transition-colors border border-emerald-200 dark:border-emerald-900">
                        <x-icon name="whatsapp" class="w-5 h-5 mb-1" />
                        <span class="text-xs font-semibold">{{ __('واتساب') }}</span>
                    </a>

                    <!-- Telegram -->
                    <a :href="'https://t.me/share/url?url=' + encodeURIComponent(window.location.href) + '&text=' + encodeURIComponent('{{ $poll->title }}')"
                       target="_blank"
                       rel="noopener"
                       class="flex flex-col items-center justify-center p-3 rounded-2xl bg-sky-50 dark:bg-sky-950/40 text-sky-700 dark:text-sky-300 hover:bg-sky-100 transition-colors border border-sky-200 dark:border-sky-900">
                        <x-icon name="send" class="w-5 h-5 mb-1" />
                        <span class="text-xs font-semibold">{{ __('تلغرام') }}</span>
                    </a>

                    <!-- Email -->
                    <a :href="'mailto:?subject=' + encodeURIComponent('{{ $poll->title }}') + '&body=' + encodeURIComponent('صوّت الآن على: ' + window.location.href)"
                       class="flex flex-col items-center justify-center p-3 rounded-2xl bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 hover:bg-purple-100 transition-colors border border-purple-200 dark:border-purple-900">
                        <x-icon name="mail" class="w-5 h-5 mb-1" />
                        <span class="text-xs font-semibold">{{ __('البريد') }}</span>
                    </a>
                </div>
            </div>

            <!-- Instant QR Code Section -->
            <div class="space-y-3 pt-2 border-t border-slate-100 dark:border-slate-800">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-semibold text-slate-500">{{ __('رمز الاستجابة السريعة (QR Code)') }}</label>
                    <button type="button"
                            @click="downloadQr()"
                            class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                        <x-icon name="qr-code" class="w-3.5 h-3.5" />
                        <span>{{ __('تحميل الصورة') }}</span>
                    </button>
                </div>

                <div class="flex justify-center p-4 bg-white dark:bg-slate-950 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-inner">
                    <canvas id="poll-qr-canvas" class="rounded-xl"></canvas>
                </div>
            </div>

        </div>
    </div>

</div>
