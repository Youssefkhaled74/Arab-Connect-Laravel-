<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Branch;

class BranchPolicy
{
    public function viewAny(Admin $user): bool
    {
        return $user->can('viewAnyBranch');
    }

    public function view(Admin $user, Branch $branch): bool
    {
        return $user->can('viewBranch');
    }

    public function create(Admin $user): bool
    {
        return $user->can('createBranch');
    }

    public function update(Admin $user, Branch $branch): bool
    {
        return $user->can('updateBranch');
    }

    public function delete(Admin $user, Branch $branch): bool
    {
        return $user->can('deleteBranch');
    }
}
