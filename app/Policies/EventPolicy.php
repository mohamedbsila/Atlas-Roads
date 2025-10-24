<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    public function before(?User $user, $ability = null)
    {
        if ($user && ($user->is_admin ?? false)) {
            return true;
        }
    }

    public function viewAny(?User $_user)
    {
        // any authenticated user may view the events listing
        return true;
    }

    public function view(?User $user, Event $event)
    {
        // allow admins, the event organizer, or if the event is public
    return ($user && ($user->is_admin ?? false)) || ($user && (string)$user->id === (string)$event->organizer_id) || $event->is_public;
    }

    public function create(?User $_user)
    {
        // allow only authenticated users to create events
        return $_user !== null;
    }

    public function update(?User $user, Event $event)
    {
        // allow admins, the organizer, or (for legacy events with no organizer) any authenticated user
        if ($user && ($user->is_admin ?? false)) {
            return true;
        }

        if ($event->organizer_id === null) {
            return $user !== null;
        }

        return $user && (string)$user->id === (string)$event->organizer_id;
    }

    public function delete(?User $user, Event $event)
    {
        // same rules as update
        if ($user && ($user->is_admin ?? false)) {
            return true;
        }

        if ($event->organizer_id === null) {
            return $user !== null;
        }

        return $user && (string)$user->id === (string)$event->organizer_id;
    }
}
