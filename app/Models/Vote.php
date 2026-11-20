<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'poll_id',
        'user_id',
        'voter_identifier',
        'ip_address',
        'user_agent',
        'session_id',
    ];

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(VoteAnswer::class);
    }

    public function options(): BelongsToMany
    {
        return $this->belongsToMany(PollOption::class, 'vote_answers', 'vote_id', 'poll_option_id')
            ->withTimestamps();
    }
}
