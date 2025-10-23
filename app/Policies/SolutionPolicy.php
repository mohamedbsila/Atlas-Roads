<?php

namespace App\Policies;

use App\Models\Solution;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SolutionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Solution $solution): bool
    {
        // L'utilisateur qui a créé la réclamation ou un administrateur peut voir la solution
        return $user->id === $solution->reclamation->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Seuls les administrateurs peuvent créer des solutions
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Solution $solution): bool
    {
        // Seul l'administrateur qui a créé la solution peut la modifier
        return $user->isAdmin() && $user->id === $solution->admin_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Solution $solution): bool
    {
        // Seul l'administrateur qui a créé la solution peut la supprimer
        return $user->isAdmin() && $user->id === $solution->admin_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Solution $solution): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Solution $solution): bool
    {
        return false;
    }
}
