<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    /**
     * Determine whether the user view event or view page.
     */
    public function viewAny(User $user): bool
    {
        return $this->view($user, new Event);
    }

    public function view(User $user, Event $event): bool
    {
        return $user->checkPermissionTo('view Event');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Event');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {
        return $user->checkPermissionTo('update Event');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        return $user->checkPermissionTo('delete Event');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Event');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Event $event): bool
    {
        return $user->checkPermissionTo('restore Event');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Event');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Event $event): bool
    {
        return $user->checkPermissionTo('replicate Event');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Event');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Event $event): bool
    {
        return $user->checkPermissionTo('force-delete Event');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Event');
    }
    public function before(User $user, $ability)
    {
        if ($user->plan_id === 3) {
            return true;
        }

        return false;
    }
}
