<?php

namespace App\Policies;

use App\Models\Admin;
use Spatie\Activitylog\Models\Activity;


class ActivityPolicy
{
    public function viewAny(Admin $user): bool
    {
        return $user->can('viewAnyActivity');
    }

    public function view(Admin $user, Activity $activity): bool
    {
        return $user->can('viewActivity');
    }

    public function create(Admin $user): bool
    {
        return $user->can('createActivity');
    }

    public function update(Admin $user, Activity $activity): bool
    {
        return $user->can('updateActivity');
    }   

    public function delete(Admin $user, Activity $activity): bool
    {
        return $user->can('deleteActivity');
    }
}
