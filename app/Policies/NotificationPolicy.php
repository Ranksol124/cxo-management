<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Notifications;

class NotificationPolicy
{
    /**
     * Determine whether the user can view any notifications.
     */
    public function viewAny(User $user): bool
    {
        return !is_null($user->plan_id);
    }

    /**
     * Determine whether the user can view a specific notification.
     */
    public function view(User $user, Notifications $notification): bool
    {
        return !is_null($user->plan_id)
            && $notification->user_id === $user->id;  // optional ownership check
    }

    /**
     * Determine whether the user can create notifications.
     */
    public function create(User $user): bool
    {
        return !is_null($user->plan_id);
    }

    /**
     * Determine whether the user can update the notification.
     */
    public function update(User $user, Notifications $notification): bool
    {
        return !is_null($user->plan_id)
            && $notification->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the notification.
     */
    public function delete(User $user, Notifications $notification): bool
    {
        return !is_null($user->plan_id)
            && $notification->user_id === $user->id;
    }

    /**
     * Optional before method to block all access if no plan.
     */
  public function before(User $user, $ability)
{
    if ($user->role === 'super_admin') {
        return true; 
    }

    return false;
}


}
