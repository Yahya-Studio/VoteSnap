<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">
            {{ __('إنشاء تصويت جديد') }}
        </h1>
        <p class="mt-2 text-slate-500 dark:text-slate-400 text-sm">
            {{ __('حدد سؤالك وخياراتك وخصص قواعد التصويت وشاركه خلال لحظات.') }}
        </p>
    </div>

    <!-- 2 Column Layout: Form & Live Preview -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- ================= FORM COLUMN ================= -->
        <div class="lg:col-span-7 space-y-6">

            <form wire:submit.prevent="save" class="space-y-6">

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
                               wire:model.live.debounce.300ms="title"
                               placeholder="{{ __('مثال: استطلاع أفضل موعد للاجتماع السنوي') }}"
                               class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-all" />
                        @error('title') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Question -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                            {{ __('سؤال التصويت') }} <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               wire:model.live.debounce.300ms="question"
                               placeholder="{{ __('ما هو الخيار الأفضل بالنسبة لك؟') }}"
                               class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-all" />
                        @error('question') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Description (Optional) -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                            {{ __('وصف إضافي (اختياري)') }}
                        </label>
                        <textarea wire:model.live.debounce.300ms="description"
                                  rows="2"
                                  placeholder="{{ __('أضف أي تفاصيل توضيحية للمصوتين...') }}"
                                  class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-all"></textarea>
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
                        <span class="text-xs text-slate-400">{{ __('خياران على الأقل') }}</span>
                    </div>

                    <div class="space-y-3">
                        @foreach ($options as $index => $option)
                            <div class="flex items-center gap-2">
                                <span class="w-6 text-center text-xs font-bold text-slate-400 shrink-0">
                                    {{ $index + 1 }}
                                </span>
                                <input type="text"
                                       wire:model.live.debounce.200ms="options.{{ $index }}"
                                       placeholder="{{ __('الخيار رقم :num', ['num' => $index + 1]) }}"
                                       class="flex-1 px-4 py-2.5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-all" />

                                @if (count($options) > 2)
                                    <button type="button"
                                            wire:click="removeOption({{ $index }})"
                                            class="p-2.5 text-slate-400 hover:text-rose-500 rounded-xl hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-colors">
                                        <x-icon name="trash" class="w-4 h-4" />
                                    </button>
                                @endif
                            </div>
                            @error('options.' . $index) <span class="text-xs text-rose-500 mt-0.5 ms-8 block">{{ $message }}</span> @enderror
                        @endforeach
                    </div>

                    @if (count($options) < 20)
                        <button type="button"
                                wire:click="addOption"
                                class="inline-flex items-center gap-2 mt-2 px-4 py-2 rounded-xl text-sm font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50 hover:bg-indigo-100 transition-colors">
                            <x-icon name="plus" class="w-4 h-4" />
                            <span>{{ __('إضافة خيار آخر') }}</span>
                        </button>
                    @endif
                </div>

                <!-- Poll Type & Rules Settings -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 dark:border-slate-800/80 space-y-6">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800">
                        <span class="p-2 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400">
                            <x-icon name="sparkles" class="w-5 h-5" />
                        </span>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('نوع وقواعد التصويت') }}</h2>
                    </div>

                    <!-- Type: Single or Multiple Choice -->
                    <div class="grid grid-cols-2 gap-3">
                        <label class="p-4 rounded-2xl border-2 cursor-pointer transition-all flex flex-col gap-1 {{ $poll_type === 'single' ? 'border-indigo-600 bg-indigo-50/50 dark:bg-indigo-950/30' : 'border-slate-200 dark:border-slate-800' }}">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-slate-900 dark:text-white">{{ __('اختيار فردي') }}</span>
                                <input type="radio" wire:model.live="poll_type" value="single" class="text-indigo-600 focus:ring-indigo-500" />
                            </div>
                            <span class="text-xs text-slate-500">{{ __('إجابة واحدة فقط لكل مصوت') }}</span>
                        </label>

                        <label class="p-4 rounded-2xl border-2 cursor-pointer transition-all flex flex-col gap-1 {{ $poll_type === 'multiple' ? 'border-indigo-600 bg-indigo-50/50 dark:bg-indigo-950/30' : 'border-slate-200 dark:border-slate-800' }}">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-slate-900 dark:text-white">{{ __('خيارات متعددة') }}</span>
                                <input type="radio" wire:model.live="poll_type" value="multiple" class="text-indigo-600 focus:ring-indigo-500" />
                            </div>
                            <span class="text-xs text-slate-500">{{ __('السماح باختيار أكثر من إجابة') }}</span>
                        </label>
                    </div>

                    @if ($poll_type === 'multiple')
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                {{ __('الحد الأقصى للخيارات المسموح باختيارها') }}
                            </label>
                            <input type="number"
                                   wire:model.live="max_choices"
                                   min="1"
                                   max="20"
                                   class="w-32 px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-sm" />
                        </div>
                    @endif

                    <!-- Scheduling: Start & End Times -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                {{ __('تاريخ ووقت البدء (اختياري)') }}
                            </label>
                            <input type="datetime-local"
                                   wire:model.live="start_at"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-xs sm:text-sm text-slate-700 dark:text-slate-200" />
                            <span class="text-[11px] text-slate-400">{{ __('اتركه فارغاً للبدء فوراً') }}</span>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                {{ __('تاريخ ووقت الانتهاء (اختياري)') }}
                            </label>
                            <input type="datetime-local"
                                   wire:model.live="end_at"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-xs sm:text-sm text-slate-700 dark:text-slate-200" />
                            <span class="text-[11px] text-slate-400">{{ __('اتركه فارغاً ليستمر بدون انتهاء') }}</span>
                        </div>
                    </div>

                    <!-- Toggles: Visibility & Results -->
                    <div class="space-y-4 pt-2 border-t border-slate-100 dark:border-slate-800">
                        <!-- Show Results Timing -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                {{ __('موعد عرض النتائج للمشاركين') }}
                            </label>
                            <select wire:model.live="results_visibility"
                                    class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-sm">
                                <option value="always">{{ __('متاحة للجميع دائماً') }}</option>
                                <option value="after_vote">{{ __('تظهر فقط بعد أن يقوم المستخدم بالتصويت') }}</option>
                                <option value="after_end">{{ __('تظهر فقط بعد انتهاء مدة التصويت أو إغلاقه') }}</option>
                            </select>
                        </div>

                        <!-- Checkbox: Allow Vote Change -->
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox"
                                   wire:model.live="allow_vote_change"
                                   class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500" />
                            <div>
                                <span class="text-sm font-semibold text-slate-800 dark:text-slate-200 block">
                                    {{ __('السماح للمستخدم بتغيير صوته لاحقاً') }}
                                </span>
                                <span class="text-xs text-slate-400">
                                    {{ __('يستطيع المصوت العودة إلى الرابط وتعديل خياره طالما التصويت نشط.') }}
                                </span>
                            </div>
                        </label>

                        <!-- Checkbox: Randomize Options -->
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox"
                                   wire:model.live="randomize_options"
                                   class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500" />
                            <div>
                                <span class="text-sm font-semibold text-slate-800 dark:text-slate-200 block">
                                    {{ __('ترتيب الخيارات عشوائياً عند فتح التصويت') }}
                                </span>
                                <span class="text-xs text-slate-400">
                                    {{ __('لتقليل انحياز المصوتين للخيار الأول.') }}
                                </span>
                            </div>
                        </label>

                        <!-- Visibility: Public vs Unlisted -->
                        <div class="pt-2">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                {{ __('مستوى الخصوصية') }}
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="p-3 rounded-2xl border cursor-pointer text-xs flex items-center justify-between {{ $visibility === 'public' ? 'border-indigo-600 bg-indigo-50/50 dark:bg-indigo-950/30 font-bold' : 'border-slate-200 dark:border-slate-800' }}">
                                    <span>{{ __('تصويت عام') }}</span>
                                    <input type="radio" wire:model.live="visibility" value="public" class="text-indigo-600" />
                                </label>
                                <label class="p-3 rounded-2xl border cursor-pointer text-xs flex items-center justify-between {{ $visibility === 'unlisted' ? 'border-indigo-600 bg-indigo-50/50 dark:bg-indigo-950/30 font-bold' : 'border-slate-200 dark:border-slate-800' }}">
                                    <span>{{ __('غير مدرج (فقط عبر الرابط)') }}</span>
                                    <input type="radio" wire:model.live="visibility" value="unlisted" class="text-indigo-600" />
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="w-full py-4 rounded-2xl font-black text-lg text-white bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 hover:from-indigo-500 hover:to-purple-500 shadow-xl shadow-indigo-500/25 transition-all transform active:scale-98 flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="save">
                            {{ __('نشر التصويت الآن') }}
                        </span>
                        <span wire:loading wire:target="save" class="flex items-center gap-2">
                            <x-icon name="refresh" class="w-5 h-5 animate-spin" />
                            <span>{{ __('جارٍ النشر...') }}</span>
                        </span>
                    </button>
                </div>

            </form>

        </div>

        <!-- ================= LIVE PREVIEW COLUMN ================= -->
        <div class="lg:col-span-5 sticky top-24">
            <div class="bg-slate-100/70 dark:bg-slate-900/40 p-4 rounded-3xl border border-dashed border-slate-300 dark:border-slate-700/60">
                <div class="flex items-center justify-between mb-3 px-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                        <x-icon name="eye" class="w-4 h-4 text-indigo-500" />
                        {{ __('معاينة مباشرة لشكل التصويت') }}
                    </span>
                    <span class="text-[11px] px-2 py-0.5 rounded-full bg-indigo-100 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 font-semibold">
                        {{ __('تفاعلي') }}
                    </span>
                </div>

                <!-- Mock Poll Card -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-lg border border-slate-200/80 dark:border-slate-800 p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            {{ __('نشط') }}
                        </span>
                        <span class="text-[11px] text-slate-400">
                            {{ $poll_type === 'multiple' ? __('خيارات متعددة') : __('اختيار فردي') }}
                        </span>
                    </div>

                    <h3 class="text-xl font-black text-slate-900 dark:text-white leading-snug">
                        {{ !empty($title) ? $title : __('عنوان التصويت سيظهر هنا...') }}
                    </h3>

                    @if (!empty($question) && $question !== $title)
                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ $question }}
                        </p>
                    @endif

                    @if (!empty($description))
                        <p class="text-xs text-slate-400 line-clamp-3">
                            {{ $description }}
                        </p>
                    @endif

                    <!-- Preview Options -->
                    <div class="space-y-2 pt-2">
                        @foreach ($options as $idx => $opt)
                            <div class="p-3 rounded-2xl border border-slate-200 dark:border-slate-800 flex items-center gap-3 bg-slate-50/50 dark:bg-slate-950/40">
                                <div class="w-4 h-4 rounded-{{ $poll_type === 'multiple' ? 'sm' : 'full' }} border border-slate-300 dark:border-slate-600"></div>
                                <span class="text-sm text-slate-700 dark:text-slate-200 {{ empty($opt) ? 'italic text-slate-400' : 'font-medium' }}">
                                    {{ !empty($opt) ? $opt : __('الخيار رقم :num', ['num' => $idx + 1]) }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-2">
                        <div class="w-full py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs text-center shadow-md shadow-indigo-500/20">
                            {{ __('إرسال الصوت') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
