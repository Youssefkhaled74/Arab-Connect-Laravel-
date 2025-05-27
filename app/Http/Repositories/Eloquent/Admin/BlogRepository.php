<?php

namespace App\Http\Repositories\Eloquent\Admin;

use App\Models\Blog;
use DevxPackage\AbstractRepository;

class BlogRepository extends AbstractRepository
{

    protected $model;

    public function __construct(Blog $model)
    {
        $this->model = $model;
    }

    public function crudName(): string
    {
        return 'blogs';
    }

    public function index($offset, $limit)
    {
        $blogs = $this->pagination($offset, $limit);
        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function edit($id)
    {
        $blog = $this->findOne($id);
        return view('admin.blogs.update', compact('blog'));
    }

    public function archivesPage($offset, $limit)
    {
        $blogs = $this->archives($offset, $limit);
        return view('admin.blogs.archives', compact('blogs'));
    }

}