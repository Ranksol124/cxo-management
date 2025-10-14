<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\EnterpriseMemberDetail;
use App\Models\User;

class EnterpriseMemberDetailPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any EnterpriseMemberDetail');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EnterpriseMemberDetail $enterprisememberdetail): bool
    {
        return $user->checkPermissionTo('view EnterpriseMemberDetail');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create EnterpriseMemberDetail');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EnterpriseMemberDetail $enterprisememberdetail): bool
    {
        return $user->checkPermissionTo('update EnterpriseMemberDetail');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EnterpriseMemberDetail $enterprisememberdetail): bool
    {
        return $user->checkPermissionTo('delete EnterpriseMemberDetail');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any EnterpriseMemberDetail');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, EnterpriseMemberDetail $enterprisememberdetail): bool
    {
        return $user->checkPermissionTo('restore EnterpriseMemberDetail');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any EnterpriseMemberDetail');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, EnterpriseMemberDetail $enterprisememberdetail): bool
    {
        return $user->checkPermissionTo('replicate EnterpriseMemberDetail');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder EnterpriseMemberDetail');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, EnterpriseMemberDetail $enterprisememberdetail): bool
    {
        return $user->checkPermissionTo('force-delete EnterpriseMemberDetail');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any EnterpriseMemberDetail');
    }
}
