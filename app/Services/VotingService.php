<?php

namespace App\Services;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\User;
use App\Models\Vote;
use App\Models\VoteAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class VotingService
{
    /**
     * Compute or retrieve a privacy-respecting unique voter identifier.
     */
    public function getVoterIdentifier(Request $request, ?User $user = null): string
    {
        if ($user) {
            return 'uid_' . $user->id;
        }

        $voterCookie = $request->cookie('votesnap_token');
        if (! $voterCookie) {
            $voterCookie = Str::random(40);
            Cookie::queue('votesnap_token', $voterCookie, 60 * 24 * 365); // 1 year
        }

        // Salted hash combining voter token and client IP
        return 'gst_' . hash('sha256', $voterCookie . '|' . ($request->ip() ?? '127.0.0.1'));
    }

    /**
     * Check if voter has already voted on this poll.
     */
    public function findExistingVote(Poll $poll, Request $request, ?User $user = null): ?Vote
    {
        $query = Vote::where('poll_id', $poll->id);

        if ($user) {
            $existing = (clone $query)->where('user_id', $user->id)->first();
            if ($existing) {
                return $existing;
            }
        }

        $identifier = $this->getVoterIdentifier($request, $user);
        return $query->where('voter_identifier', $identifier)->first();
    }

    /**
     * Record or update a vote inside a strict database transaction.
     */
    public function recordVote(Poll $poll, array $optionIds, Request $request, ?User $user = null): Vote
    {
        // 1. Check poll status
        if (! $poll->canAcceptVotes()) {
            $statusText = match ($poll->status) {
                'scheduled' => __('هذا التصويت مجدول ولم يبدأ بعد.'),
                'expired' => __('انتهت فترة هذا التصويت ولا يستقبل أصواتاً جديدة.'),
                'closed' => __('تم إغلاق هذا التصويت.'),
                default => __('هذا التصويت غير متاح حالياً.'),
            };
            throw ValidationException::withMessages(['vote' => $statusText]);
        }

        // 2. Validate selected options count
        $optionIds = array_unique(array_filter($optionIds));
        if (empty($optionIds)) {
            throw ValidationException::withMessages(['options' => __('يرجى اختيار إجابة واحدة على الأقل.')]);
        }

        if ($poll->poll_type === 'single' && count($optionIds) !== 1) {
            throw ValidationException::withMessages(['options' => __('يمكنك اختيار إجابة واحدة فقط في هذا التصويت.')]);
        }

        if ($poll->poll_type === 'multiple' && $poll->max_choices && count($optionIds) > $poll->max_choices) {
            throw ValidationException::withMessages([
                'options' => __('الحد الأقصى للخيارات المسموح باختيارها هو :max خيارات.', ['max' => $poll->max_choices]),
            ]);
        }

        // 3. Validate options belong to this poll
        $validOptions = PollOption::where('poll_id', $poll->id)
            ->whereIn('id', $optionIds)
            ->get();

        if ($validOptions->count() !== count($optionIds)) {
            throw ValidationException::withMessages(['options' => __('بعض الخيارات المختارة غير صالحة.')]);
        }

        $identifier = $this->getVoterIdentifier($request, $user);
        $existingVote = $this->findExistingVote($poll, $request, $user);

        // 4. Duplicate check / Vote change check
        if ($existingVote) {
            if (! $poll->allow_vote_change) {
                throw ValidationException::withMessages([
                    'vote' => __('لقد قمت بالتصويت مسبقاً، وإعدادات هذا التصويت لا تسمح بتغيير الصوت.'),
                ]);
            }
        }

        return DB::transaction(function () use ($poll, $optionIds, $existingVote, $identifier, $request, $user) {
            if ($existingVote) {
                // Revert previous answers counts
                $previousAnswerOptionIds = $existingVote->answers()->pluck('poll_option_id')->toArray();

                foreach ($previousAnswerOptionIds as $oldOptId) {
                    PollOption::where('id', $oldOptId)->decrement('votes_count');
                }

                $existingVote->answers()->delete();

                // Create new answers
                foreach ($optionIds as $newOptId) {
                    VoteAnswer::create([
                        'vote_id' => $existingVote->id,
                        'poll_option_id' => $newOptId,
                    ]);
                    PollOption::where('id', $newOptId)->increment('votes_count');
                }

                $existingVote->touch();

                return $existingVote->load(['answers.option', 'poll']);
            }

            // Fresh Vote
            $vote = Vote::create([
                'poll_id' => $poll->id,
                'user_id' => $user?->id,
                'voter_identifier' => $identifier,
                'ip_address' => $request->ip(),
                'user_agent' => Str::limit($request->userAgent() ?? '', 255),
                'session_id' => $request->hasSession() ? $request->session()->getId() : null,
            ]);

            foreach ($optionIds as $newOptId) {
                VoteAnswer::create([
                    'vote_id' => $vote->id,
                    'poll_option_id' => $newOptId,
                ]);
                PollOption::where('id', $newOptId)->increment('votes_count');
            }

            $poll->increment('votes_count');

            return $vote->load(['answers.option', 'poll']);
        });
    }
}
