<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PollOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'poll_id',
        'option_text',
        'order',
        'votes_count',
    ];

    protected $casts = [
        'order' => 'integer',
        'votes_count' => 'integer',
    ];

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(VoteAnswer::class, 'poll_option_id');
    }

    public function getPercentageAttribute(): float
    {
        $total = $this->poll ? $this->poll->votes_count : 0;
        if ($total <= 0) {
            return 0.0;
        }

        return round(($this->votes_count / $total) * 100, 1);
    }
}
