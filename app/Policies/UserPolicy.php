<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\User;

class UserPolicy
{
    public function viewAny(Admin $user): bool
    {
        return $user->can('viewAnyUser');
    }

    public function view(Admin $user, User $targetUser): bool
    {
        return $user->can('viewUser');
    }

    public function create(Admin $user): bool
    {
        return $user->can('createUser');
    }

    public function update(Admin $user, User $targetUser): bool
    {
        return $user->can('updateUser');
    }

    public function delete(Admin $user, User $targetUser): bool
    {
        return $user->can('deleteUser');
    }
}
