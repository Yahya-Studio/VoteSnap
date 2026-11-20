<?php

namespace Tests\Feature;

use App\Livewire\CreatePoll;
use App\Models\Poll;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PollTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_landing_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('VoteSnap');
    }

    public function test_guest_can_view_public_poll(): void
    {
        $user = User::factory()->create();
        $poll = Poll::create([
            'user_id' => $user->id,
            'title' => 'اختبار استطلاع تجريبي',
            'question' => 'سؤال الاستطلاع التجريبي',
            'poll_type' => 'single',
            'visibility' => 'public',
        ]);
        $poll->options()->create(['option_text' => 'الخيار 1', 'order' => 0]);
        $poll->options()->create(['option_text' => 'الخيار 2', 'order' => 1]);

        $response = $this->get(route('polls.show', $poll));

        $response->assertStatus(200);
        $response->assertSee('اختبار استطلاع تجريبي');
        $response->assertSee('الخيار 1');
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
    }

    public function test_user_can_create_poll_via_livewire(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(CreatePoll::class)
            ->set('title', 'ما هو لونك المفضل؟')
            ->set('question', 'اختر لونك المفضل من بين الخيارات التالية:')
            ->set('options', ['الأزرق', 'الأخضر', 'الأرجواني'])
            ->set('poll_type', 'single')
            ->set('visibility', 'public')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('polls', [
            'user_id' => $user->id,
            'title' => 'ما هو لونك المفضل؟',
            'poll_type' => 'single',
        ]);

        $poll = Poll::where('title', 'ما هو لونك المفضل؟')->first();
        $this->assertCount(3, $poll->options);
    }

    public function test_only_owner_can_edit_poll(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $poll = Poll::create([
            'user_id' => $owner->id,
            'title' => 'استطلاع للمالك فقط',
            'question' => 'السؤال',
            'poll_type' => 'single',
        ]);
        $poll->options()->create(['option_text' => 'خيار 1', 'order' => 0]);
        $poll->options()->create(['option_text' => 'خيار 2', 'order' => 1]);

        // Non-owner gets 403 Forbidden
        $this->actingAs($otherUser)
            ->get(route('polls.edit', $poll))
            ->assertStatus(403);

        // Owner can access edit
        $this->actingAs($owner)
            ->get(route('polls.edit', $poll))
            ->assertStatus(200);
    }

    public function test_owner_can_delete_poll(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $poll = Poll::create([
            'user_id' => $owner->id,
            'title' => 'استطلاع سيتم حذفه',
            'question' => 'السؤال',
            'poll_type' => 'single',
        ]);

        // Unauthorized user cannot delete
        $this->actingAs($otherUser)
            ->delete(route('polls.destroy', $poll))
            ->assertStatus(403);

        // Owner can delete
        $this->actingAs($owner)
            ->delete(route('polls.destroy', $poll))
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseMissing('polls', ['id' => $poll->id]);
    }

    public function test_owner_can_toggle_close_poll(): void
    {
        $owner = User::factory()->create();
        $poll = Poll::create([
            'user_id' => $owner->id,
            'title' => 'استطلاع قابل للإغلاق',
            'question' => 'السؤال',
            'poll_type' => 'single',
            'is_closed_manually' => false,
        ]);

        $this->actingAs($owner)
            ->post(route('polls.toggle-close', $poll));

        $this->assertTrue($poll->fresh()->is_closed_manually);
        $this->assertTrue($poll->fresh()->isClosed());
    }

    public function test_owner_can_duplicate_poll(): void
    {
        $owner = User::factory()->create();
        $poll = Poll::create([
            'user_id' => $owner->id,
            'title' => 'استطلاع أصلي',
            'question' => 'السؤال',
            'poll_type' => 'single',
        ]);
        $poll->options()->create(['option_text' => 'خيار أ', 'order' => 0]);
        $poll->options()->create(['option_text' => 'خيار ب', 'order' => 1]);

        $this->actingAs($owner)
            ->post(route('polls.duplicate', $poll));

        $this->assertDatabaseHas('polls', [
            'user_id' => $owner->id,
            'title' => 'استطلاع أصلي (نسخة)',
        ]);
    }
}
