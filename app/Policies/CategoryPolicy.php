<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Category;

class CategoryPolicy
{
    public function viewAny(Admin $user): bool
    {
        return $user->can('viewAnyCategory');
    }

    public function view(Admin $user, Category $category): bool
    {
        return $user->can('viewCategory');
    }

    public function create(Admin $user): bool
    {
        return $user->can('createCategory');
    }

    public function update(Admin $user, Category $category): bool
    {
        return $user->can('updateCategory');
    }

    public function delete(Admin $user, Category $category): bool
    {
        return $user->can('deleteCategory');
    }
}
