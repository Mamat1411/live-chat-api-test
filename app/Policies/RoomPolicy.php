<?php

namespace App\Policies;

use App\Models\Room;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RoomPolicy
{
    /**
     * Determine whether the only admin can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the admin or room owner can view the model.
     */
    public function view(User $user, Room $room): bool
    {
        return $user->isAdmin() || $this->isOwner($user, $room);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether only owner can update the model.
     */
    public function update(User $user, Room $room): bool
    {
        return $this->isOwner($user, $room);
    }

    /**
     * Determine whether only owner can delete the model.
     */
    public function delete(User $user, Room $room): bool
    {
        return $this->isOwner($user, $room);
    }

    private function isOwner(User $user, Room $room): bool
    {
        return $room->created_by === $user->id;
    }
}
