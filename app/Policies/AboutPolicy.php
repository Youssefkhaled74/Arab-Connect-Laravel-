<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\About;

class AboutPolicy
{
    public function viewAny(Admin $user): bool
    {
        return $user->can('viewAnyAbout');
    }

    public function view(Admin $user, About $about): bool
    {
        return $user->can('viewAbout');
    }

    public function create(Admin $user): bool
    {
        return $user->can('createAbout');
    }

    public function update(Admin $user, About $about): bool
    {
        return $user->can('updateAbout');
    }

    public function delete(Admin $user, About $about): bool
    {
        return $user->can('deleteAbout');
    }
}
