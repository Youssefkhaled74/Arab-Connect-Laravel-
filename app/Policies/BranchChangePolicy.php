<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\BranchChange;

class BranchChangePolicy
{
    public function viewAny(Admin $user): bool
    {
        return $user->can('viewAnyBranchChange');
    }

    public function view(Admin $user, BranchChange $branchChange): bool
    {
        return $user->can('viewBranchChange');
    }

    public function create(Admin $user): bool
    {
        return $user->can('createBranchChange');
    }

    public function update(Admin $user, BranchChange $branchChange): bool
    {
        return $user->can('updateBranchChange');
    }

    public function delete(Admin $user, BranchChange $branchChange): bool
    {
        return $user->can('deleteBranchChange');
    }

}
