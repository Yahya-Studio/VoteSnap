<?php

namespace App\Livewire;

use App\Models\Poll;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class EditPoll extends Component
{
    public Poll $poll;

    public string $title = '';
    public string $question = '';
    public string $description = '';

    /** @var array<int, array{id?: int, option_text: string, votes_count: int}> */
    public array $options = [];

    public string $poll_type = 'single';
    public ?int $max_choices = 1;

    public ?string $start_at = null;
    public ?string $end_at = null;

    public bool $show_results = true;
    public string $results_visibility = 'always';
    public bool $allow_vote_change = false;
    public bool $randomize_options = false;
    public string $visibility = 'public';

    public bool $hasVotes = false;

    public function mount(Poll $poll): void
    {
        Gate::authorize('update', $poll);

        $this->poll = $poll->load('options');
        $this->hasVotes = $this->poll->votes_count > 0;

        $this->title = $poll->title;
        $this->question = $poll->question;
        $this->description = $poll->description ?? '';
        $this->poll_type = $poll->poll_type;
        $this->max_choices = $poll->max_choices;
        $this->start_at = $poll->start_at?->format('Y-m-d\TH:i');
        $this->end_at = $poll->end_at?->format('Y-m-d\TH:i');
        $this->show_results = $poll->show_results;
        $this->results_visibility = $poll->results_visibility;
        $this->allow_vote_change = $poll->allow_vote_change;
        $this->randomize_options = $poll->randomize_options;
        $this->visibility = $poll->visibility;

        $this->options = $poll->options->map(fn ($opt) => [
            'id' => $opt->id,
            'option_text' => $opt->option_text,
            'votes_count' => $opt->votes_count,
        ])->toArray();
    }

    public function addOption(): void
    {
        if ($this->hasVotes) {
            return;
        }

        if (count($this->options) < 20) {
            $this->options[] = [
                'option_text' => '',
                'votes_count' => 0,
            ];
        }
    }

    public function removeOption(int $index): void
    {
        if ($this->hasVotes) {
            return;
        }

        if (count($this->options) > 2) {
            unset($this->options[$index]);
            $this->options = array_values($this->options);
        }
    }

    public function update(): void
    {
        Gate::authorize('update', $this->poll);

        $this->validate([
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'question' => ['required', 'string', 'min:3', 'max:1000'],
            'description' => ['nullable', 'string', 'max:2000'],
            'options' => ['required', 'array', 'min:2', 'max:20'],
            'options.*.option_text' => ['required', 'string', 'min:1', 'max:255'],
            'poll_type' => ['required', 'in:single,multiple'],
            'max_choices' => ['nullable', 'integer', 'min:1', 'max:20'],
            'start_at' => ['nullable', 'date'],
            'end_at' => ['nullable', 'date'],
            'show_results' => ['boolean'],
            'results_visibility' => ['required', 'in:always,after_vote,after_end'],
            'allow_vote_change' => ['boolean'],
            'randomize_options' => ['boolean'],
            'visibility' => ['required', 'in:public,unlisted'],
        ]);

        $this->poll->update([
            'title' => $this->title,
            'question' => $this->question,
            'description' => ! empty($this->description) ? $this->description : null,
            'poll_type' => $this->poll_type,
            'max_choices' => $this->poll_type === 'multiple' ? ($this->max_choices ?? 2) : 1,
            'start_at' => ! empty($this->start_at) ? $this->start_at : null,
            'end_at' => ! empty($this->end_at) ? $this->end_at : null,
            'show_results' => $this->show_results,
            'results_visibility' => $this->results_visibility,
            'allow_vote_change' => $this->allow_vote_change,
            'randomize_options' => $this->randomize_options,
            'visibility' => $this->visibility,
        ]);

        // If no votes have been cast yet, we safely update/sync the options
        if (! $this->hasVotes) {
            $existingOptionIds = [];
            foreach ($this->options as $index => $opt) {
                if (! empty(trim($opt['option_text']))) {
                    if (isset($opt['id'])) {
                        $this->poll->options()->where('id', $opt['id'])->update([
                            'option_text' => trim($opt['option_text']),
                            'order' => $index,
                        ]);
                        $existingOptionIds[] = $opt['id'];
                    } else {
                        $newOption = $this->poll->options()->create([
                            'option_text' => trim($opt['option_text']),
                            'order' => $index,
                        ]);
                        $existingOptionIds[] = $newOption->id;
                    }
                }
            }
            // Delete removed options
            $this->poll->options()->whereNotIn('id', $existingOptionIds)->delete();
        }

        session()->flash('status', __('تم تحديث بيانات التصويت بنجاح!'));
        $this->redirect(route('polls.show', $this->poll), navigate: true);
    }

    public function render()
    {
        return view('livewire.edit-poll');
    }
}
