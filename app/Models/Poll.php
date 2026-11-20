<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Poll extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'slug',
        'title',
        'question',
        'description',
        'image_path',
        'poll_type',
        'max_choices',
        'start_at',
        'end_at',
        'visibility',
        'show_results',
        'results_visibility',
        'allow_vote_change',
        'randomize_options',
        'is_closed_manually',
        'closed_at',
        'votes_count',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'closed_at' => 'datetime',
        'show_results' => 'boolean',
        'allow_vote_change' => 'boolean',
        'randomize_options' => 'boolean',
        'is_closed_manually' => 'boolean',
        'max_choices' => 'integer',
        'votes_count' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Poll $poll) {
            if (empty($poll->slug)) {
                $poll->slug = Str::slug(Str::limit($poll->title, 40, '')) . '-' . Str::lower(Str::random(8));
                // Ensure slug uniqueness
                while (static::where('slug', $poll->slug)->exists()) {
                    $poll->slug = Str::slug(Str::limit($poll->title, 40, '')) . '-' . Str::lower(Str::random(8));
                }
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(PollOption::class)->orderBy('order', 'asc');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Compute current lifecycle status: scheduled, active, closed, or expired.
     */
    public function getStatusAttribute(): string
    {
        if ($this->is_closed_manually) {
            return 'closed';
        }

        $now = now();

        if ($this->end_at && $now->greaterThanOrEqualTo($this->end_at)) {
            return 'expired';
        }

        if ($this->start_at && $now->lessThan($this->start_at)) {
            return 'scheduled';
        }

        return 'active';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    public function canAcceptVotes(): bool
    {
        return $this->isActive();
    }

    public function canViewResults(?Vote $userVote = null, ?User $viewer = null): bool
    {
        // Owner can always view results
        if ($viewer && $this->user_id && $viewer->id === $this->user_id) {
            return true;
        }

        if (! $this->show_results) {
            return false;
        }

        if ($this->results_visibility === 'always') {
            return true;
        }

        if ($this->results_visibility === 'after_end') {
            return $this->isExpired() || $this->isClosed();
        }

        if ($this->results_visibility === 'after_vote') {
            return $userVote !== null || $this->isExpired() || $this->isClosed();
        }

        return true;
    }

    public function isMultipleChoice(): bool
    {
        return $this->poll_type === 'multiple';
    }
}
