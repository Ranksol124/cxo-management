<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\PlanFeature;
use App\Models\User;

class PlanFeaturePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->view($user, new PlanFeature);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PlanFeature $planfeature): bool
    {
        return $user->checkPermissionTo('view PlanFeature');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create PlanFeature');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PlanFeature $planfeature): bool
    {
        return $user->checkPermissionTo('update PlanFeature');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PlanFeature $planfeature): bool
    {
        return $user->checkPermissionTo('delete PlanFeature');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any PlanFeature');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PlanFeature $planfeature): bool
    {
        return $user->checkPermissionTo('restore PlanFeature');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any PlanFeature');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, PlanFeature $planfeature): bool
    {
        return $user->checkPermissionTo('replicate PlanFeature');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder PlanFeature');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PlanFeature $planfeature): bool
    {
        return $user->checkPermissionTo('force-delete PlanFeature');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any PlanFeature');
    }
}
