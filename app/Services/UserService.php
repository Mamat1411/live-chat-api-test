<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    public function __construct(
        private User $user
    ) {}

    public function getAllUsers() : LengthAwarePaginator {
        return $this->user->latest()->paginate(10, ["id", "name", "username", "email", "role", "created_at", "updated_at"]);
    }

    public function getUserById(string $id) : User {
        return $this->user->findOrFail($id);
    }

    public function createNewUser(array $data): User {
        $hashedPassword = bcrypt($data['password']);
        $data['password'] = $hashedPassword;
        $emailVerifiedAt = now();
        $data['email_verified_at'] = $emailVerifiedAt;
        return $this->user->create($data);
    }

    public function updateUser(User $user, array $data) : User {
        if (isset($data['password'])) {
            $hashedPassword = bcrypt($data['password']);
            $data['password'] = $hashedPassword;
        }
        $user->update($data);
        return $user;
    }

    public function deleteUser(User $user) : User {
        $user->delete();
        return $user;
    }
}
