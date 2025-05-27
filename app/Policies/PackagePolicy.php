<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Package;

class PackagePolicy
{
    public function viewAny(Admin $user): bool
    {
        return $user->can('viewAnyPackage');
    }

    public function view(Admin $user, Package $package): bool
    {
        return $user->can('viewPackage');
    }

    public function create(Admin $user): bool
    {
        return $user->can('createPackage');
    }

    public function update(Admin $user, Package $package): bool
    {
        return $user->can('updatePackage');
    }

    public function delete(Admin $user, Package $package): bool
    {
        return $user->can('deletePackage');
    }
}
