<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12"
     x-data="{
        copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                $dispatch('toast', {
                    type: 'success',
                    message: '{{ __('تم نسخ الرابط بنجاح!') }}'
                });
            });
        }
     }">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                {{ __('لوحة التحكم') }}
            </h1>
            <p class="mt-1 text-slate-500 dark:text-slate-400 text-sm">
                {{ __('تابع أداء واستجابات تصويتاتك وأدرها بكل سهولة.') }}
            </p>
        </div>

        <a href="{{ route('polls.create') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl font-bold text-sm text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all transform active:scale-95">
            <x-icon name="plus" class="w-4 h-4" />
            <span>{{ __('إنشاء تصويت جديد') }}</span>
        </a>
    </div>

    <!-- Stat Cards (4 Cards Grid) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        <!-- Total Polls -->
        <div class="bg-white dark:bg-slate-900 p-5 sm:p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-400 block mb-1">{{ __('إجمالي التصويتات') }}</span>
                <span class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ number_format($totalPolls) }}</span>
            </div>
            <div class="p-3 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400">
                <x-icon name="vote" class="w-6 h-6" />
            </div>
        </div>

        <!-- Total Votes -->
        <div class="bg-white dark:bg-slate-900 p-5 sm:p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-400 block mb-1">{{ __('إجمالي الأصوات') }}</span>
                <span class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ number_format($totalVotes) }}</span>
            </div>
            <div class="p-3 rounded-2xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400">
                <x-icon name="users" class="w-6 h-6" />
            </div>
        </div>

        <!-- Active Polls -->
        <div class="bg-white dark:bg-slate-900 p-5 sm:p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-400 block mb-1">{{ __('التصويتات النشطة') }}</span>
                <span class="text-2xl sm:text-3xl font-black text-emerald-600 dark:text-emerald-400">{{ number_format($activePolls) }}</span>
            </div>
            <div class="p-3 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400">
                <x-icon name="sparkles" class="w-6 h-6" />
            </div>
        </div>

        <!-- Closed Polls -->
        <div class="bg-white dark:bg-slate-900 p-5 sm:p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-400 block mb-1">{{ __('التصويتات المغلقة') }}</span>
                <span class="text-2xl sm:text-3xl font-black text-slate-600 dark:text-slate-300">{{ number_format($closedPolls) }}</span>
            </div>
            <div class="p-3 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-500">
                <x-icon name="lock" class="w-6 h-6" />
            </div>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-4 sm:p-5 border border-slate-200/80 dark:border-slate-800/80 shadow-sm mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <!-- Filter Tabs -->
        <div class="flex items-center gap-1.5 p-1 rounded-2xl bg-slate-100 dark:bg-slate-800/70 w-full sm:w-auto overflow-x-auto">
            <button type="button"
                    wire:click="setFilter('all')"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $filter === 'all' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900' }}">
                {{ __('الكل') }}
            </button>
            <button type="button"
                    wire:click="setFilter('active')"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $filter === 'active' ? 'bg-white dark:bg-slate-700 text-emerald-600 dark:text-emerald-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900' }}">
                {{ __('النشطة') }}
            </button>
            <button type="button"
                    wire:click="setFilter('scheduled')"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $filter === 'scheduled' ? 'bg-white dark:bg-slate-700 text-amber-600 dark:text-amber-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900' }}">
                {{ __('المجدولة') }}
            </button>
            <button type="button"
                    wire:click="setFilter('closed')"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $filter === 'closed' ? 'bg-white dark:bg-slate-700 text-rose-600 dark:text-rose-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900' }}">
                {{ __('المغلقة') }}
            </button>
        </div>

        <!-- Search Input -->
        <div class="relative w-full sm:w-72">
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   placeholder="{{ __('بحث في تصويتاتي...') }}"
                   class="w-full ps-9 pe-4 py-2 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-xs sm:text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500" />
            <div class="absolute inset-y-0 start-0 ps-3 flex items-center pointer-events-none text-slate-400">
                <x-icon name="filter" class="w-4 h-4" />
            </div>
        </div>
    </div>

    <!-- Polls Listing -->
    @if ($polls->isEmpty())
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-12 text-center border border-slate-200/80 dark:border-slate-800/80 shadow-sm">
            <div class="w-16 h-16 rounded-3xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto mb-4">
                <x-icon name="vote" class="w-8 h-8" />
            </div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">
                {{ __('لا توجد تصويتات تطابق البحث') }}
            </h3>
            <p class="text-sm text-slate-400 max-w-sm mx-auto mb-6">
                {{ __('ابدأ بإنشاء تصويت جديد وشاركه مع الآخرين لمتابعة آرائهم.') }}
            </p>
            <a href="{{ route('polls.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                <x-icon name="plus" class="w-4 h-4" />
                <span>{{ __('إنشاء تصويت جديد') }}</span>
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($polls as $poll)
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-5 sm:p-6 border border-slate-200/80 dark:border-slate-800/80 shadow-sm hover:border-indigo-300 dark:hover:border-indigo-800/60 transition-all flex flex-col md:flex-row md:items-center justify-between gap-6">

                    <!-- Left: Title, Badges, Date -->
                    <div class="space-y-2 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <!-- Status Badge -->
                            @if ($poll->isClosed())
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-400 border border-rose-200 dark:border-rose-900">
                                    {{ __('مغلق') }}
                                </span>
                            @elseif ($poll->isExpired())
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                    {{ __('انتهت المدة') }}
                                </span>
                            @elseif ($poll->isScheduled())
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-400 border border-amber-200 dark:border-amber-900">
                                    {{ __('مجدول') }}
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    {{ __('نشط') }}
                                </span>
                            @endif

                            <span class="text-xs text-slate-400">
                                {{ $poll->isMultipleChoice() ? __('خيارات متعددة') : __('اختيار فردي') }}
                            </span>

                            @if ($poll->visibility === 'unlisted')
                                <span class="text-[11px] text-slate-400 px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800">
                                    {{ __('غير مدرج') }}
                                </span>
                            @endif
                        </div>

                        <a href="{{ route('polls.show', $poll) }}" class="block group">
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                {{ $poll->title }}
                            </h2>
                        </a>

                        <div class="flex flex-wrap items-center gap-4 text-xs text-slate-400">
                            <span class="flex items-center gap-1">
                                <x-icon name="calendar" class="w-3.5 h-3.5" />
                                <span>{{ $poll->created_at->translatedFormat('Y/m/d - H:i') }}</span>
                            </span>
                            <span class="flex items-center gap-1 font-semibold text-slate-600 dark:text-slate-300">
                                <x-icon name="users" class="w-3.5 h-3.5 text-indigo-500" />
                                <span>{{ number_format($poll->votes_count) }} {{ __('مشارك') }}</span>
                            </span>
                            <span>
                                {{ $poll->options_count }} {{ __('خيارات') }}
                            </span>
                        </div>
                    </div>

                    <!-- Right: Action Buttons -->
                    <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 pt-2 md:pt-0 border-t md:border-t-0 border-slate-100 dark:border-slate-800">

                        <!-- View Public Page -->
                        <a href="{{ route('polls.show', $poll) }}"
                           target="_blank"
                           class="p-2 sm:px-3 sm:py-2 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center gap-1.5 transition-colors"
                           title="{{ __('عرض صفحة التصويت') }}">
                            <x-icon name="external-link" class="w-4 h-4" />
                            <span class="hidden sm:inline">{{ __('عرض') }}</span>
                        </a>

                        <!-- Share Button -->
                        <button type="button"
                                wire:click="openShareModal({{ $poll->id }})"
                                class="p-2 sm:px-3 sm:py-2 rounded-xl text-xs font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/60 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 flex items-center gap-1.5 transition-colors"
                                title="{{ __('مشاركة التصويت') }}">
                            <x-icon name="share" class="w-4 h-4" />
                            <span class="hidden sm:inline">{{ __('مشاركة') }}</span>
                        </button>

                        <!-- Edit Button -->
                        <a href="{{ route('polls.edit', $poll) }}"
                           class="p-2 sm:px-3 sm:py-2 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center gap-1.5 transition-colors"
                           title="{{ __('تعديل التصويت') }}">
                            <x-icon name="edit" class="w-4 h-4" />
                            <span class="hidden sm:inline">{{ __('تعديل') }}</span>
                        </a>

                        <!-- Duplicate Button -->
                        <button type="button"
                                wire:click="duplicatePoll({{ $poll->id }})"
                                class="p-2 rounded-xl text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-slate-800 transition-colors"
                                title="{{ __('نسخ كمسودة جديدة') }}">
                            <x-icon name="duplicate" class="w-4 h-4" />
                        </button>

                        <!-- Close / Reopen Toggle -->
                        <button type="button"
                                wire:click="toggleClose({{ $poll->id }})"
                                class="p-2 rounded-xl {{ $poll->is_closed_manually ? 'text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/40' : 'text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-950/40' }} transition-colors"
                                title="{{ $poll->is_closed_manually ? __('إعادة فتح التصويت') : __('إغلاق التصويت') }}">
                            @if ($poll->is_closed_manually)
                                <x-icon name="unlock" class="w-4 h-4" />
                            @else
                                <x-icon name="lock" class="w-4 h-4" />
                            @endif
                        </button>

                        <!-- Delete Button (Trigger confirmation) -->
                        <button type="button"
                                wire:click="confirmDelete({{ $poll->id }})"
                                class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors"
                                title="{{ __('حذف التصويت') }}">
                            <x-icon name="trash" class="w-4 h-4" />
                        </button>

                    </div>

                </div>
            @endforeach

            <!-- Pagination -->
            <div class="pt-4">
                {{ $polls->links() }}
            </div>
        </div>
    @endif

    <!-- ================= SHARE MODAL ================= -->
    @if ($sharePoll)
        @php $shareUrl = route('polls.show', $sharePoll); @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800 max-w-md w-full p-6 sm:p-8 space-y-6"
                 x-data="{
                    qrDataUrl: '',
                    init() {
                        if (window.QRCode) {
                            window.QRCode.toDataURL(@js($shareUrl), {
                                width: 240,
                                margin: 2,
                                color: { dark: '#4f46e5', light: '#ffffff' }
                            }, (err, dataUrl) => {
                                if (!err && dataUrl) this.qrDataUrl = dataUrl;
                            });
                        }
                    },
                    downloadQr() {
                        if (!this.qrDataUrl) return;
                        const a = document.createElement('a');
                        a.download = 'votesnap-poll-{{ $sharePoll->slug }}.png';
                        a.href = this.qrDataUrl;
                        a.click();
                    }
                 }">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="p-2 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400">
                            <x-icon name="share" class="w-5 h-5" />
                        </span>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">
                            {{ __('مشاركة التصويت') }}
                        </h3>
                    </div>
                    <button type="button" wire:click="$set('pollToShareId', null)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1">
                        <x-icon name="x" class="w-5 h-5" />
                    </button>
                </div>

                <!-- URL Field -->
                <div class="space-y-2">
                    <label class="text-xs font-semibold text-slate-500">{{ __('الرابط المباشر') }}</label>
                    <div class="flex items-center gap-2">
                        <input type="text"
                               readonly
                               value="{{ $shareUrl }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-xs sm:text-sm font-mono text-slate-600 dark:text-slate-300 focus:outline-none select-all" />
                        <button type="button"
                                @click="copyToClipboard('{{ $shareUrl }}')"
                                class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition-colors shrink-0 flex items-center gap-1.5 shadow-md shadow-indigo-500/20">
                            <x-icon name="copy" class="w-4 h-4" />
                            <span>{{ __('نسخ') }}</span>
                        </button>
                    </div>
                </div>

                <!-- App Share Buttons -->
                <div class="grid grid-cols-3 gap-2">
                    <a href="https://wa.me/?text={{ urlencode($sharePoll->title . "\n" . $shareUrl) }}"
                       target="_blank"
                       rel="noopener"
                       class="flex flex-col items-center justify-center p-3 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-100 transition-colors border border-emerald-200 dark:border-emerald-900">
                        <x-icon name="whatsapp" class="w-5 h-5 mb-1" />
                        <span class="text-xs font-semibold">{{ __('واتساب') }}</span>
                    </a>
                    <a href="https://t.me/share/url?url={{ urlencode($shareUrl) }}&text={{ urlencode($sharePoll->title) }}"
                       target="_blank"
                       rel="noopener"
                       class="flex flex-col items-center justify-center p-3 rounded-2xl bg-sky-50 dark:bg-sky-950/40 text-sky-700 dark:text-sky-300 hover:bg-sky-100 transition-colors border border-sky-200 dark:border-sky-900">
                        <x-icon name="send" class="w-5 h-5 mb-1" />
                        <span class="text-xs font-semibold">{{ __('تلغرام') }}</span>
                    </a>
                    <a href="mailto:?subject={{ urlencode($sharePoll->title) }}&body={{ urlencode($shareUrl) }}"
                       class="flex flex-col items-center justify-center p-3 rounded-2xl bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 hover:bg-purple-100 transition-colors border border-purple-200 dark:border-purple-900">
                        <x-icon name="mail" class="w-5 h-5 mb-1" />
                        <span class="text-xs font-semibold">{{ __('البريد') }}</span>
                    </a>
                </div>

                <!-- QR Code Box -->
                <div class="pt-2 border-t border-slate-100 dark:border-slate-800 space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-semibold text-slate-500">{{ __('رمز الاستجابة السريعة (QR)') }}</label>
                        <button type="button" @click="downloadQr()" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                            <x-icon name="qr-code" class="w-3.5 h-3.5" />
                            <span>{{ __('تحميل الصورة') }}</span>
                        </button>
                    </div>
                    <div class="flex justify-center p-4 bg-white dark:bg-slate-950 rounded-2xl border border-slate-200 dark:border-slate-800 min-h-[200px] items-center">
                        <template x-if="qrDataUrl">
                            <img :src="qrDataUrl" alt="QR Code" class="w-48 h-48 rounded-xl shadow-sm" />
                        </template>
                        <template x-if="!qrDataUrl">
                            <div class="flex items-center gap-2 text-xs text-slate-400">
                                <x-icon name="refresh" class="w-4 h-4 animate-spin" />
                                <span>{{ __('جارٍ توليد الرمز...') }}</span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- ================= DELETE CONFIRMATION MODAL ================= -->
    @if ($pollToDeleteId)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800 max-w-sm w-full p-6 text-center space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto">
                    <x-icon name="trash" class="w-6 h-6" />
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">
                    {{ __('تأكيد حذف التصويت') }}
                </h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    {{ __('هل أنت متأكد من رغبتك في حذف هذا التصويت نهائياً؟ سيتم حذف جميع الأصوات والخيارات التابعة له ولا يمكن التراجع عن هذا الإجراء.') }}
                </p>
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <button type="button"
                            wire:click="$set('pollToDeleteId', null)"
                            class="py-2.5 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200">
                        {{ __('إلغاء') }}
                    </button>
                    <button type="button"
                            wire:click="deletePoll"
                            class="py-2.5 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-md shadow-rose-500/20">
                        {{ __('نعم، احذف') }}
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
