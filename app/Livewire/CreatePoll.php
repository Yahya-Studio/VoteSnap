<?php

namespace App\Livewire;

use App\Models\Poll;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePoll extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $question = '';
    public string $description = '';
    public $image = null;

    /** @var array<int, string> */
    public array $options = ['', ''];

    public string $poll_type = 'single'; // 'single' or 'multiple'
    public ?int $max_choices = 2;

    public ?string $start_at = null;
    public ?string $end_at = null;

    public bool $show_results = true;
    public string $results_visibility = 'always'; // 'always', 'after_vote', 'after_end'
    public bool $allow_vote_change = false;
    public bool $randomize_options = false;
    public string $visibility = 'public'; // 'public', 'unlisted'

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'question' => ['required', 'string', 'min:3', 'max:1000'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'max:3072'], // 3MB max
            'options' => ['required', 'array', 'min:2', 'max:20'],
            'options.*' => ['required', 'string', 'min:1', 'max:255'],
            'poll_type' => ['required', 'in:single,multiple'],
            'max_choices' => ['nullable', 'integer', 'min:1', 'max:20'],
            'start_at' => ['nullable', 'date'],
            'end_at' => ['nullable', 'date', 'after:start_at'],
            'show_results' => ['boolean'],
            'results_visibility' => ['required', 'in:always,after_vote,after_end'],
            'allow_vote_change' => ['boolean'],
            'randomize_options' => ['boolean'],
            'visibility' => ['required', 'in:public,unlisted'],
        ];
    }

    public function updatedTitle($value): void
    {
        if (empty($this->question)) {
            $this->question = $value;
        }
    }

    public function addOption(): void
    {
        if (count($this->options) < 20) {
            $this->options[] = '';
        }
    }

    public function removeOption(int $index): void
    {
        if (count($this->options) > 2) {
            unset($this->options[$index]);
            $this->options = array_values($this->options);
        }
    }

    public function save(): void
    {
        $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('polls', 'public');
        }

        $poll = DB::transaction(function () use ($imagePath) {
            $poll = Poll::create([
                'user_id' => auth()->id(),
                'title' => $this->title,
                'question' => $this->question,
                'description' => ! empty($this->description) ? $this->description : null,
                'image_path' => $imagePath,
                'poll_type' => $this->poll_type,
                'max_choices' => $this->poll_type === 'multiple' ? ($this->max_choices ?? 2) : 1,
                'start_at' => ! empty($this->start_at) ? $this->start_at : null,
                'end_at' => ! empty($this->end_at) ? $this->end_at : null,
                'visibility' => $this->visibility,
                'show_results' => $this->show_results,
                'results_visibility' => $this->results_visibility,
                'allow_vote_change' => $this->allow_vote_change,
                'randomize_options' => $this->randomize_options,
            ]);

            foreach ($this->options as $index => $optionText) {
                if (! empty(trim($optionText))) {
                    $poll->options()->create([
                        'option_text' => trim($optionText),
                        'order' => $index,
                    ]);
                }
            }

            return $poll;
        });

        session()->flash('status', __('تم إنشاء التصويت بنجاح! شارك الرابط الآن مع جمهورك.'));

        $this->redirect(route('polls.show', $poll), navigate: true);
    }

    public function render()
    {
        return view('livewire.create-poll');
    }
}
