<?php

namespace Tests\Feature;

use App\Livewire\PollVote;
use App\Models\Poll;
use App\Models\User;
use App\Services\VotingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Tests\TestCase;

class VotingTest extends TestCase
{
    use RefreshDatabase;

    public function test_voter_can_cast_single_choice_vote(): void
    {
        $poll = Poll::create([
            'title' => 'استطلاع تصويت فردي',
            'question' => 'اختر إجابة واحدة',
            'poll_type' => 'single',
            'visibility' => 'public',
            'allow_vote_change' => false,
        ]);
        $opt1 = $poll->options()->create(['option_text' => 'نعم', 'order' => 0]);
        $opt2 = $poll->options()->create(['option_text' => 'لا', 'order' => 1]);

        $service = new VotingService();
        $request = Request::create('/poll/' . $poll->slug, 'POST');

        $vote = $service->recordVote($poll, [$opt1->id], $request);

        $this->assertDatabaseHas('votes', [
            'poll_id' => $poll->id,
            'id' => $vote->id,
        ]);

        $this->assertEquals(1, $poll->fresh()->votes_count);
        $this->assertEquals(1, $opt1->fresh()->votes_count);
        $this->assertEquals(0, $opt2->fresh()->votes_count);
    }

    public function test_voter_can_cast_multiple_choice_vote(): void
    {
        $poll = Poll::create([
            'title' => 'استطلاع متعدد الخيارات',
            'question' => 'اختر حتى 2 خيارات',
            'poll_type' => 'multiple',
            'max_choices' => 2,
            'visibility' => 'public',
        ]);
        $opt1 = $poll->options()->create(['option_text' => 'خيار 1', 'order' => 0]);
        $opt2 = $poll->options()->create(['option_text' => 'خيار 2', 'order' => 1]);
        $opt3 = $poll->options()->create(['option_text' => 'خيار 3', 'order' => 2]);

        $service = new VotingService();
        $request = Request::create('/poll/' . $poll->slug, 'POST');

        $vote = $service->recordVote($poll, [$opt1->id, $opt2->id], $request);

        $this->assertEquals(1, $poll->fresh()->votes_count);
        $this->assertEquals(1, $opt1->fresh()->votes_count);
        $this->assertEquals(1, $opt2->fresh()->votes_count);
        $this->assertEquals(0, $opt3->fresh()->votes_count);
    }

    public function test_anti_duplicate_protection_blocks_repeat_vote_when_change_disabled(): void
    {
        $poll = Poll::create([
            'title' => 'استطلاع غير قابل للتعديل',
            'question' => 'السؤال',
            'poll_type' => 'single',
            'allow_vote_change' => false,
        ]);
        $opt1 = $poll->options()->create(['option_text' => 'خيار 1', 'order' => 0]);
        $opt2 = $poll->options()->create(['option_text' => 'خيار 2', 'order' => 1]);

        $service = new VotingService();
        $request = Request::create('/poll/' . $poll->slug, 'POST');
        $request->cookies->set('votesnap_token', 'sample_fixed_token_abc123');

        // First vote succeeds
        $service->recordVote($poll, [$opt1->id], $request);

        // Second vote with same token must throw ValidationException
        $this->expectException(ValidationException::class);
        $service->recordVote($poll, [$opt2->id], $request);
    }

    public function test_voter_can_update_vote_when_allow_vote_change_is_enabled(): void
    {
        $poll = Poll::create([
            'title' => 'استطلاع قابل للتعديل',
            'question' => 'السؤال',
            'poll_type' => 'single',
            'allow_vote_change' => true,
        ]);
        $opt1 = $poll->options()->create(['option_text' => 'خيار أ', 'order' => 0]);
        $opt2 = $poll->options()->create(['option_text' => 'خيار ب', 'order' => 1]);

        $service = new VotingService();
        $request = Request::create('/poll/' . $poll->slug, 'POST');
        $request->cookies->set('votesnap_token', 'sample_change_token_xyz999');

        // First vote: Option 1
        $service->recordVote($poll, [$opt1->id], $request);
        $this->assertEquals(1, $opt1->fresh()->votes_count);
        $this->assertEquals(0, $opt2->fresh()->votes_count);
        $this->assertEquals(1, $poll->fresh()->votes_count);

        // Change vote to Option 2
        $service->recordVote($poll, [$opt2->id], $request);

        // Option 1 decremented, Option 2 incremented, total votes count remains 1
        $this->assertEquals(0, $opt1->fresh()->votes_count);
        $this->assertEquals(1, $opt2->fresh()->votes_count);
        $this->assertEquals(1, $poll->fresh()->votes_count);
    }

    public function test_expired_or_closed_poll_rejects_new_votes(): void
    {
        // Expired poll
        $poll = Poll::create([
            'title' => 'استطلاع منتهي',
            'question' => 'السؤال',
            'poll_type' => 'single',
            'end_at' => now()->subMinutes(10),
        ]);
        $opt1 = $poll->options()->create(['option_text' => 'خيار 1', 'order' => 0]);

        $service = new VotingService();
        $request = Request::create('/poll/' . $poll->slug, 'POST');

        $this->expectException(ValidationException::class);
        $service->recordVote($poll, [$opt1->id], $request);
    }

    public function test_scheduled_poll_rejects_early_votes(): void
    {
        // Scheduled poll in the future
        $poll = Poll::create([
            'title' => 'استطلاع مجدول',
            'question' => 'السؤال',
            'poll_type' => 'single',
            'start_at' => now()->addDay(),
        ]);
        $opt1 = $poll->options()->create(['option_text' => 'خيار 1', 'order' => 0]);

        $service = new VotingService();
        $request = Request::create('/poll/' . $poll->slug, 'POST');

        $this->expectException(ValidationException::class);
        $service->recordVote($poll, [$opt1->id], $request);
    }

    public function test_livewire_poll_vote_component_records_vote(): void
    {
        $poll = Poll::create([
            'title' => 'استطلاع لايف واير',
            'question' => 'اختر خياراً',
            'poll_type' => 'single',
            'visibility' => 'public',
            'show_results' => true,
        ]);
        $opt = $poll->options()->create(['option_text' => 'موافق', 'order' => 0]);

        Livewire::test(PollVote::class, ['poll' => $poll])
            ->call('selectOption', $opt->id)
            ->call('submitVote')
            ->assertSet('hasVoted', true)
            ->assertDispatched('toast');

        $this->assertEquals(1, $poll->fresh()->votes_count);
        $this->assertEquals(1, $opt->fresh()->votes_count);
    }
}
