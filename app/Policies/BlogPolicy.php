<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Blog;

class BlogPolicy
{
    public function viewAny(Admin $user): bool
    {
        return $user->can('viewAnyBlog');
    }

    public function view(Admin $user, Blog $blog): bool
    {
        return $user->can('viewBlog');
    }

    public function create(Admin $user): bool
    {
        return $user->can('createBlog');
    }

    public function update(Admin $user, Blog $blog): bool
    {
        return $user->can('updateBlog');
    }

    public function delete(Admin $user, Blog $blog): bool
    {
        return $user->can('deleteBlog');
    }
}
