<?php

namespace App\Policies;

use App\Models\Poll;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PollPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Poll $poll): bool
    {
        // Public and unlisted polls can be viewed by anyone who has the link
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Poll $poll): bool
    {
        return $user->id === $poll->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Poll $poll): bool
    {
        return $user->id === $poll->user_id;
    }

    /**
     * Determine whether the user can close or toggle status of the poll.
     */
    public function close(User $user, Poll $poll): bool
    {
        return $user->id === $poll->user_id;
    }

    /**
     * Determine whether the user can duplicate the poll.
     */
    public function duplicate(User $user, Poll $poll): bool
    {
        return $user->id === $poll->user_id;
    }
}
