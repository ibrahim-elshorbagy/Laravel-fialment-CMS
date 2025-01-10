<?php

namespace App\Policies\Classification;

use App\Models\User;
use App\Models\Classification\Category;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_classification::category');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Category $category): bool
    {
        return $user->can('view_classification::category');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_classification::category');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Category $category): bool
    {
        return $user->can('update_classification::category');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Category $category): bool
    {
        return $user->can('delete_classification::category');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_classification::category');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Category $category): bool
    {
        return $user->can('force_delete_classification::category');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_classification::category');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Category $category): bool
    {
        return $user->can('restore_classification::category');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_classification::category');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Category $category): bool
    {
        return $user->can('replicate_classification::category');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_classification::category');
    }
}
