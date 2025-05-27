<?php

namespace App\Http\Repositories\Eloquent\Admin;

use App\Models\About;
use DevxPackage\AbstractRepository;

class AboutRepository extends AbstractRepository
{

    protected $model;

    public function __construct(About $model)
    {
        $this->model = $model;
    }

    public function crudName(): string
    {
        return 'abouts';
    }

    public function index($offset, $limit)
    {
        $abouts = $this->pagination($offset, $limit);
        return view('admin.abouts.index', compact('abouts'));
    }

    public function create()
    {
        return view('admin.abouts.create');
    }

    public function edit($id)
    {
        $about = $this->findOne($id);
        return view('admin.abouts.update', compact('about'));
    }

    public function archivesPage($offset, $limit)
    {
        $abouts = $this->archives($offset, $limit);
        return view('admin.abouts.archives', compact('abouts'));
    }

}