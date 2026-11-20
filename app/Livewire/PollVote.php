<?php

namespace App\Livewire;

use App\Models\Poll;
use App\Models\Vote;
use App\Services\VotingService;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class PollVote extends Component
{
    public Poll $poll;

    /** @var array<int> */
    public array $selectedOptions = [];

    public bool $hasVoted = false;
    public ?int $userVoteId = null;
    public bool $showResultsView = false;
    public bool $showChart = false;
    public string $chartType = 'bar'; // 'bar' or 'doughnut'
    public bool $showShareModal = false;

    public function mount(Poll $poll, VotingService $votingService): void
    {
        $this->poll = $poll->load(['options']);

        $existingVote = $votingService->findExistingVote($this->poll, request(), auth()->user());

        if ($existingVote) {
            $this->hasVoted = true;
            $this->userVoteId = $existingVote->id;
            $this->selectedOptions = $existingVote->answers()->pluck('poll_option_id')->toArray();
        }

        // Auto display results if already voted or if poll is ended or if allowed
        if ($this->hasVoted || ! $this->poll->canAcceptVotes()) {
            if ($this->poll->canViewResults($existingVote, auth()->user())) {
                $this->showResultsView = true;
            }
        }
    }

    public function selectOption(int $optionId): void
    {
        if (! $this->poll->canAcceptVotes()) {
            return;
        }

        if (! $this->poll->isMultipleChoice()) {
            $this->selectedOptions = [$optionId];
            return;
        }

        if (in_array($optionId, $this->selectedOptions)) {
            $this->selectedOptions = array_values(array_diff($this->selectedOptions, [$optionId]));
        } else {
            if ($this->poll->max_choices && count($this->selectedOptions) >= $this->poll->max_choices) {
                $this->dispatch('toast', [
                    'type' => 'error',
                    'message' => __('الحد الأقصى للخيارات المسموح باختيارها هو :max', ['max' => $this->poll->max_choices]),
                ]);
                return;
            }
            $this->selectedOptions[] = $optionId;
        }
    }

    public function submitVote(VotingService $votingService): void
    {
        try {
            $vote = $votingService->recordVote(
                $this->poll,
                $this->selectedOptions,
                request(),
                auth()->user()
            );

            $this->hasVoted = true;
            $this->userVoteId = $vote->id;
            $this->poll->refresh();
            $this->poll->load('options');

            $message = $this->hasVoted && $this->poll->allow_vote_change
                ? __('تم حفظ وتحديث صوتك بنجاح!')
                : __('تم تسجيل صوتك بنجاح! شكراً لمشاركتك.');

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => $message,
            ]);

            if ($this->poll->canViewResults($vote, auth()->user())) {
                $this->showResultsView = true;
                $this->dispatchResultsEvent();
            }
        } catch (ValidationException $e) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => collect($e->errors())->flatten()->first() ?? __('تعذر تسجيل الصوت.'),
            ]);
        }
    }

    public function changeVote(): void
    {
        if (! $this->poll->allow_vote_change || ! $this->poll->canAcceptVotes()) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => __('تغيير الصوت غير متاح لهذا التصويت.'),
            ]);
            return;
        }

        $this->showResultsView = false;
    }

    public function toggleResultsView(): void
    {
        $existingVote = $this->userVoteId ? Vote::find($this->userVoteId) : null;

        if (! $this->poll->canViewResults($existingVote, auth()->user())) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => __('إعدادات هذا التصويت لا تتيح عرض النتائج حالياً.'),
            ]);
            return;
        }

        $this->showResultsView = ! $this->showResultsView;

        if ($this->showResultsView) {
            $this->dispatchResultsEvent();
        }
    }

    public function toggleChart(): void
    {
        $this->showChart = ! $this->showChart;
        if ($this->showChart) {
            $this->dispatchResultsEvent();
        }
    }

    public function switchChartType(string $type): void
    {
        $this->chartType = in_array($type, ['bar', 'doughnut']) ? $type : 'bar';
        $this->dispatchResultsEvent();
    }

    public function refreshResults(): void
    {
        $this->poll->refresh();
        $this->poll->load('options');

        if ($this->showResultsView) {
            $this->dispatchResultsEvent();
        }
    }

    private function dispatchResultsEvent(): void
    {
        $labels = [];
        $data = [];
        $percentages = [];

        foreach ($this->poll->options as $option) {
            $labels[] = $option->option_text;
            $data[] = $option->votes_count;
            $percentages[] = $option->percentage;
        }

        $this->dispatch('chart-data-updated', [
            'labels' => $labels,
            'data' => $data,
            'percentages' => $percentages,
            'chartType' => $this->chartType,
        ]);
    }

    public function render()
    {
        $options = $this->poll->options;

        if ($this->poll->randomize_options && ! $this->showResultsView) {
            // Keep deterministic seed per voter session/cookie so options don't jump around on re-render
            $seed = crc32(request()->ip() . '_' . $this->poll->id);
            $options = $options->sortBy(fn ($opt) => crc32($opt->id . '_' . $seed));
        }

        $sortedOptionsForResults = $this->poll->options->sortByDesc('votes_count');

        return view('livewire.poll-vote', [
            'displayOptions' => $options,
            'sortedResults' => $sortedOptionsForResults,
        ]);
    }
}
