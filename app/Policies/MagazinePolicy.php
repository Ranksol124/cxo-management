<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Magazine;
use App\Models\User;

class MagazinePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->view($user, new Magazine);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Magazine $magazine): bool
    {
        return $user->checkPermissionTo('view Magazine');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Magazine');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Magazine $magazine): bool
    {
        return $user->checkPermissionTo('update Magazine');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Magazine $magazine): bool
    {
        return $user->checkPermissionTo('delete Magazine');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $this->delete($user, new Magazine);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Magazine $magazine): bool
    {
        return $user->checkPermissionTo('restore Magazine');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Magazine');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Magazine $magazine): bool
    {
        return $user->checkPermissionTo('replicate Magazine');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Magazine');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Magazine $magazine): bool
    {
        return $user->checkPermissionTo('force-delete Magazine');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Magazine');
    }
 
}
