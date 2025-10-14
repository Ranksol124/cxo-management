<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\SilverMemberDetail;
use App\Models\User;

class SilverMemberDetailPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any SilverMemberDetail');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SilverMemberDetail $silvermemberdetail): bool
    {
        return $user->checkPermissionTo('view SilverMemberDetail');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create SilverMemberDetail');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SilverMemberDetail $silvermemberdetail): bool
    {
        return $user->checkPermissionTo('update SilverMemberDetail');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SilverMemberDetail $silvermemberdetail): bool
    {
        return $user->checkPermissionTo('delete SilverMemberDetail');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any SilverMemberDetail');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SilverMemberDetail $silvermemberdetail): bool
    {
        return $user->checkPermissionTo('restore SilverMemberDetail');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any SilverMemberDetail');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, SilverMemberDetail $silvermemberdetail): bool
    {
        return $user->checkPermissionTo('replicate SilverMemberDetail');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder SilverMemberDetail');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SilverMemberDetail $silvermemberdetail): bool
    {
        return $user->checkPermissionTo('force-delete SilverMemberDetail');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any SilverMemberDetail');
    }
}
