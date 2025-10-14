<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MemberFeed;

class MemberFeedsPolicy
{
    /**
     * Determine whether the user can view any member feeds.
     */
    public function viewAny(User $user): bool
    {
        // Only users with a plan_id can view any feeds
        return !is_null($user->plan_id);
    }

    /**
     * Determine whether the user can view the member feed.
     */
    public function view(User $user, MemberFeed $memberFeed): bool
    {
        // Only if user has plan and owns the feed (optional ownership check)
        return !is_null($user->plan_id)
            && $memberFeed->user_id === $user->id;
    }

    /**
     * Determine whether the user can create member feeds.
     */
    public function create(User $user): bool
    {
        // Only users with a plan can create feeds
        return !is_null($user->plan_id);
    }

    /**
     * Determine whether the user can update the member feed.
     */
    public function update(User $user, MemberFeed $memberFeed): bool
    {
        return $user->checkPermissionTo('update MemberFeed');
    }

    /**
     * Determine whether the user can delete the member feed.
     */
    public function delete(User $user, MemberFeed $memberFeed): bool
    {
        return !is_null($user->plan_id)
            && $memberFeed->user_id === $user->id;
    }

    /**
     * Optionally, add a before method to block all abilities if no plan
     */
    public function before(User $user, $ability)
    {
        if (is_null($user->plan_id)) {
            return false;
        }
    }
}
