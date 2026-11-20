<?php

namespace App\Livewire;

use App\Models\Poll;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class PollDashboard extends Component
{
    use WithPagination;

    public string $filter = 'all'; // 'all', 'active', 'scheduled', 'closed'
    public string $search = '';

    public ?int $pollToDeleteId = null;
    public ?int $pollToShareId = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function setFilter(string $filter): void
    {
        $this->filter = in_array($filter, ['all', 'active', 'scheduled', 'closed']) ? $filter : 'all';
        $this->resetPage();
    }

    public function toggleClose(int $pollId): void
    {
        $poll = Poll::where('user_id', auth()->id())->findOrFail($pollId);
        Gate::authorize('close', $poll);

        $poll->is_closed_manually = ! $poll->is_closed_manually;
        $poll->closed_at = $poll->is_closed_manually ? now() : null;
        $poll->save();

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => $poll->is_closed_manually
                ? __('تم إغلاق التصويت بنجاح.')
                : __('تم إعادة فتح التصويت بنجاح.'),
        ]);
    }

    public function confirmDelete(int $pollId): void
    {
        $this->pollToDeleteId = $pollId;
    }

    public function deletePoll(): void
    {
        if (! $this->pollToDeleteId) {
            return;
        }

        $poll = Poll::where('user_id', auth()->id())->findOrFail($this->pollToDeleteId);
        Gate::authorize('delete', $poll);

        $poll->delete();
        $this->pollToDeleteId = null;

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => __('تم حذف التصويت نهائياً.'),
        ]);
    }

    public function duplicatePoll(int $pollId): void
    {
        $poll = Poll::with('options')->where('user_id', auth()->id())->findOrFail($pollId);
        Gate::authorize('duplicate', $poll);

        $newPoll = $poll->replicate([
            'slug',
            'votes_count',
            'is_closed_manually',
            'closed_at',
            'created_at',
            'updated_at',
        ]);

        $newPoll->title = $poll->title . ' (' . __('نسخة') . ')';
        $newPoll->user_id = auth()->id();
        $newPoll->votes_count = 0;
        $newPoll->is_closed_manually = false;
        $newPoll->closed_at = null;
        $newPoll->save();

        foreach ($poll->options as $option) {
            $newPoll->options()->create([
                'option_text' => $option->option_text,
                'order' => $option->order,
                'votes_count' => 0,
            ]);
        }

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => __('تم نسخ التصويت بنجاح! يمكنك الآن تعديله.'),
        ]);
    }

    public function openShareModal(int $pollId): void
    {
        $this->pollToShareId = $pollId;
    }

    public function render()
    {
        $user = auth()->user();

        // Calculate statistics
        $userPollsQuery = Poll::where('user_id', $user->id);

        $totalPolls = (clone $userPollsQuery)->count();
        $totalVotes = (clone $userPollsQuery)->sum('votes_count');

        $now = now();
        $activePolls = (clone $userPollsQuery)
            ->where('is_closed_manually', false)
            ->where(function ($q) use ($now) {
                $q->whereNull('end_at')->orWhere('end_at', '>', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('start_at')->orWhere('start_at', '<=', $now);
            })
            ->count();

        $closedPolls = (clone $userPollsQuery)
            ->where(function ($q) use ($now) {
                $q->where('is_closed_manually', true)
                  ->orWhere(function ($sub) use ($now) {
                      $sub->whereNotNull('end_at')->where('end_at', '<=', $now);
                  });
            })
            ->count();

        // Filtered listing
        $listingQuery = Poll::where('user_id', $user->id)
            ->withCount('options')
            ->orderBy('created_at', 'desc');

        if (! empty($this->search)) {
            $listingQuery->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('question', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filter === 'active') {
            $listingQuery->where('is_closed_manually', false)
                ->where(function ($q) use ($now) {
                    $q->whereNull('end_at')->orWhere('end_at', '>', $now);
                })
                ->where(function ($q) use ($now) {
                    $q->whereNull('start_at')->orWhere('start_at', '<=', $now);
                });
        } elseif ($this->filter === 'scheduled') {
            $listingQuery->where('is_closed_manually', false)
                ->whereNotNull('start_at')
                ->where('start_at', '>', $now);
        } elseif ($this->filter === 'closed') {
            $listingQuery->where(function ($q) use ($now) {
                $q->where('is_closed_manually', true)
                  ->orWhere(function ($sub) use ($now) {
                      $sub->whereNotNull('end_at')->where('end_at', '<=', $now);
                  });
            });
        }

        $polls = $listingQuery->paginate(10);
        $sharePoll = $this->pollToShareId ? Poll::find($this->pollToShareId) : null;

        return view('livewire.poll-dashboard', [
            'totalPolls' => $totalPolls,
            'totalVotes' => $totalVotes,
            'activePolls' => $activePolls,
            'closedPolls' => $closedPolls,
            'polls' => $polls,
            'sharePoll' => $sharePoll,
        ]);
    }
}
