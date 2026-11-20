<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PollController extends Controller
{
    /**
     * Show the public poll page.
     */
    public function show(Poll $poll): View
    {
        // Load options with vote answers if needed
        $poll->load(['options']);

        return view('polls.show', compact('poll'));
    }

    /**
     * Show the poll creation page.
     */
    public function create(): View
    {
        return view('polls.create');
    }

    /**
     * Show the poll edit page.
     */
    public function edit(Poll $poll): View
    {
        Gate::authorize('update', $poll);

        $poll->load('options');

        return view('polls.edit', compact('poll'));
    }

    /**
     * Toggle manual close status of a poll.
     */
    public function toggleClose(Poll $poll): RedirectResponse
    {
        Gate::authorize('close', $poll);

        $poll->is_closed_manually = ! $poll->is_closed_manually;
        $poll->closed_at = $poll->is_closed_manually ? now() : null;
        $poll->save();

        $message = $poll->is_closed_manually
            ? __('تم إغلاق التصويت بنجاح.')
            : __('تم إعادة فتح التصويت بنجاح.');

        return back()->with('status', $message);
    }

    /**
     * Duplicate a poll into a new draft.
     */
    public function duplicate(Poll $poll): RedirectResponse
    {
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
        $newPoll->is_closed_manually = false;
        $newPoll->closed_at = null;
        $newPoll->votes_count = 0;
        $newPoll->save();

        foreach ($poll->options as $option) {
            $newPoll->options()->create([
                'option_text' => $option->option_text,
                'order' => $option->order,
                'votes_count' => 0,
            ]);
        }

        return redirect()->route('polls.edit', $newPoll)
            ->with('status', __('تم نسخ التصويت بنجاح! يمكنك الآن تعديله ونشره.'));
    }

    /**
     * Delete a poll.
     */
    public function destroy(Poll $poll): RedirectResponse
    {
        Gate::authorize('delete', $poll);

        $poll->delete();

        return redirect()->route('dashboard')
            ->with('status', __('تم حذف التصويت بنجاح.'));
    }
}
