<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoteAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'vote_id',
        'poll_option_id',
    ];

    public function vote(): BelongsTo
    {
        return $this->belongsTo(Vote::class);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(PollOption::class, 'poll_option_id');
    }
}
