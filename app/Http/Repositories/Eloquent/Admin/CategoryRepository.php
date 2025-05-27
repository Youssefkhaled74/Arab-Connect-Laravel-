<?php

namespace App\Http\Repositories\Eloquent\Admin;

use App\Models\Category;
use DevxPackage\AbstractRepository;

class CategoryRepository extends AbstractRepository
{

    protected $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    public function crudName(): string
    {
        return 'categories';
    }

    public function index($offset, $limit)
    {
        $categories = $this->pagination($offset, $limit);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function edit($id)
    {
        $category = $this->findOne($id);
        return view('admin.categories.update', compact('category'));
    }

    public function archivesPage($offset, $limit)
    {
        $categories = $this->archives($offset, $limit);
        return view('admin.categories.archives', compact('categories'));
    }

}