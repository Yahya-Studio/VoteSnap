<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <!-- Header with Back & View Actions -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-slate-400 hover:text-indigo-600 flex items-center gap-1">
                    <x-icon name="arrow-right" class="w-3.5 h-3.5" />
                    <span>{{ __('العودة إلى لوحة التحكم') }}</span>
                </a>
            </div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                {{ __('تعديل التصويت') }}
            </h1>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('polls.show', $poll) }}"
               target="_blank"
               class="px-4 py-2 rounded-xl text-sm font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50 hover:bg-indigo-100 flex items-center gap-1.5 transition-colors">
                <x-icon name="external-link" class="w-4 h-4" />
                <span>{{ __('معاينة الصفحة العامة') }}</span>
            </a>
        </div>
    </div>

    @if ($hasVotes)
        <!-- Safety Alert Banner -->
        <div class="mb-6 p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/60 flex items-start gap-3">
            <x-icon name="alert-circle" class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" />
            <div class="text-xs sm:text-sm text-amber-900 dark:text-amber-200 leading-relaxed">
                <span class="font-bold block mb-1">
                    {{ __('تنبيه حماية الأصوات: استقبل هذا التصويت :count صوت.', ['count' => $poll->votes_count]) }}
                </span>
                {{ __('تم قفل تعديل خيارات الإجابة للحفاظ على نزاهة الأصوات المسجلة سابقاً وعدم إفسادها، ولكن يمكنك تعديل العنوان، السؤال، الوصف، مواعيد الإغلاق، وتفضيلات الخصوصية.') }}
            </div>
        </div>
    @endif

    <form wire:submit.prevent="update" class="space-y-6">

        <!-- Basic Information Card -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 dark:border-slate-800/80 space-y-5">
            <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800">
                <span class="p-2 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400">
                    <x-icon name="edit" class="w-5 h-5" />
                </span>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('المعلومات الأساسية') }}</h2>
            </div>

            <!-- Title -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                    {{ __('عنوان التصويت') }} <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       wire:model="title"
                       class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 text-sm transition-all" />
                @error('title') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Question -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                    {{ __('سؤال التصويت') }} <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       wire:model="question"
                       class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 text-sm transition-all" />
                @error('question') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                    {{ __('الوصف') }}
                </label>
                <textarea wire:model="description"
                          rows="3"
                          class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 text-sm transition-all"></textarea>
                @error('description') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Answer Options Card -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 dark:border-slate-800/80 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <span class="p-2 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400">
                        <x-icon name="vote" class="w-5 h-5" />
                    </span>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('خيارات الإجابة') }}</h2>
                </div>
                @if ($hasVotes)
                    <span class="text-xs font-bold text-amber-600 dark:text-amber-400 px-2.5 py-0.5 rounded-full bg-amber-50 dark:bg-amber-950/40">
                        {{ __('مقفل لوجود أصوات') }}
                    </span>
                @endif
            </div>

            <div class="space-y-3">
                @foreach ($options as $index => $opt)
                    <div class="flex items-center gap-2">
                        <span class="w-6 text-center text-xs font-bold text-slate-400 shrink-0">
                            {{ $index + 1 }}
                        </span>

                        @if ($hasVotes)
                            <div class="flex-1 px-4 py-2.5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-sm flex items-center justify-between">
                                <span>{{ $opt['option_text'] }}</span>
                                <span class="text-xs font-mono font-bold text-slate-400">
                                    {{ $opt['votes_count'] }} {{ __('صوت') }}
                                </span>
                            </div>
                        @else
                            <input type="text"
                                   wire:model="options.{{ $index }}.option_text"
                                   class="flex-1 px-4 py-2.5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white text-sm" />

                            @if (count($options) > 2)
                                <button type="button"
                                        wire:click="removeOption({{ $index }})"
                                        class="p-2.5 text-slate-400 hover:text-rose-500 rounded-xl hover:bg-rose-50 transition-colors">
                                    <x-icon name="trash" class="w-4 h-4" />
                                </button>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>

            @if (! $hasVotes && count($options) < 20)
                <button type="button"
                        wire:click="addOption"
                        class="inline-flex items-center gap-2 mt-2 px-4 py-2 rounded-xl text-sm font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50 hover:bg-indigo-100 transition-colors">
                    <x-icon name="plus" class="w-4 h-4" />
                    <span>{{ __('إضافة خيار آخر') }}</span>
                </button>
            @endif
        </div>

        <!-- Rules & Timings Card -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 dark:border-slate-800/80 space-y-6">
            <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800">
                <span class="p-2 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400">
                    <x-icon name="sparkles" class="w-5 h-5" />
                </span>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('القواعد والمواعيد') }}</h2>
            </div>

            <!-- Scheduling -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                        {{ __('تاريخ ووقت البدء') }}
                    </label>
                    <input type="datetime-local"
                           wire:model="start_at"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-sm" />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                        {{ __('تاريخ ووقت الانتهاء') }}
                    </label>
                    <input type="datetime-local"
                           wire:model="end_at"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-sm" />
                </div>
            </div>

            <!-- Results & Vote Change Toggles -->
            <div class="space-y-4 pt-2 border-t border-slate-100 dark:border-slate-800">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        {{ __('موعد عرض النتائج للمشاركين') }}
                    </label>
                    <select wire:model="results_visibility"
                            class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-sm">
                        <option value="always">{{ __('متاحة للجميع دائماً') }}</option>
                        <option value="after_vote">{{ __('تظهر فقط بعد أن يقوم المستخدم بالتصويت') }}</option>
                        <option value="after_end">{{ __('تظهر فقط بعد انتهاء مدة التصويت أو إغلاقه') }}</option>
                    </select>
                </div>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" wire:model="allow_vote_change" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500" />
                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                        {{ __('السماح للمستخدم بتغيير صوته لاحقاً') }}
                    </span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" wire:model="randomize_options" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500" />
                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                        {{ __('ترتيب الخيارات عشوائياً عند فتح التصويت') }}
                    </span>
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('dashboard') }}" class="px-6 py-3 rounded-2xl text-sm font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 transition-colors">
                {{ __('إلغاء') }}
            </a>

            <button type="submit"
                    wire:loading.attr="disabled"
                    class="px-8 py-3.5 rounded-2xl font-bold text-base text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-500/25 transition-all transform active:scale-95 flex items-center gap-2">
                <span wire:loading.remove wire:target="update">{{ __('حفظ التعديلات') }}</span>
                <span wire:loading wire:target="update" class="flex items-center gap-2">
                    <x-icon name="refresh" class="w-4 h-4 animate-spin" />
                    <span>{{ __('جارٍ الحفظ...') }}</span>
                </span>
            </button>
        </div>

    </form>

</div>
