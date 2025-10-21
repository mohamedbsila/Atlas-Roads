<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * Global before hook: allow admins to do anything.
     */
    public function before(?User $user, $ability = null)
    {
        if ($user && ($user->is_admin ?? false)) {
            return true;
        }
    }

    public function viewAny(?User $_user)
    {
        return true;
    }

    public function view(?User $user, Event $event)
    {
        return ($user && ($user->is_admin ?? false)) || ($user && $user->id === $event->organizer_id) || $event->is_public;
    }

    public function create(?User $user)
    {
        return $user !== null;
    }

    public function update(?User $user, Event $event)
    {
        if ($user && $user->id === $event->organizer_id) {
            return true;
        }

        // If no organizer is set, allow any authenticated user to update (legacy behavior)
        if ($event->organizer_id === null) {
            return $user !== null;
        }

        return false;
    }

    public function delete(?User $user, Event $event)
    {
        if ($user && $user->id === $event->organizer_id) {
            return true;
        }

        if ($event->organizer_id === null) {
            return $user !== null;
        }

        return false;
    }
}
