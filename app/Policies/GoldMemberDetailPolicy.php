<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\GoldMemberDetail;
use App\Models\User;

class GoldMemberDetailPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any GoldMemberDetail');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, GoldMemberDetail $goldmemberdetail): bool
    {
        return $user->checkPermissionTo('view GoldMemberDetail');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create GoldMemberDetail');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GoldMemberDetail $goldmemberdetail): bool
    {
        return $user->checkPermissionTo('update GoldMemberDetail');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GoldMemberDetail $goldmemberdetail): bool
    {
        return $user->checkPermissionTo('delete GoldMemberDetail');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any GoldMemberDetail');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, GoldMemberDetail $goldmemberdetail): bool
    {
        return $user->checkPermissionTo('restore GoldMemberDetail');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any GoldMemberDetail');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, GoldMemberDetail $goldmemberdetail): bool
    {
        return $user->checkPermissionTo('replicate GoldMemberDetail');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder GoldMemberDetail');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, GoldMemberDetail $goldmemberdetail): bool
    {
        return $user->checkPermissionTo('force-delete GoldMemberDetail');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any GoldMemberDetail');
    }
}
