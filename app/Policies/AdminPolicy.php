<?php

namespace App\Policies;

use App\Models\Admin;

class AdminPolicy
{
    public function viewAny(Admin $user): bool
    {
        return $user->can('viewAnyAdmin');
    }

    public function view(Admin $user, Admin $admin): bool
    {
        return $user->can('viewAdmin');
    }

    public function create(Admin $user): bool
    {
        return $user->can('createAdmin');
    }

    public function update(Admin $user, Admin $admin): bool
    {
        return $user->can('updateAdmin');
    }

    public function delete(Admin $user, Admin $admin): bool
    {
        return $user->can('deleteAdmin');
    }
}
