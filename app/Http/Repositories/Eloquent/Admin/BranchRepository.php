<?php

namespace App\Http\Repositories\Eloquent\Admin;

use App\Models\Branch;
use App\Models\Category;
use DevxPackage\AbstractRepository;



class BranchRepository extends AbstractRepository
{

    protected $model;

    public function __construct(Branch $model)
    {
        $this->model = $model;
    }

    public function crudName(): string
    {
        return 'branches';
    }

    public function index($offset, $limit)
    {
        $branches = $this->pagination($offset, $limit);
        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        return view('admin.branches.create');
    }
    public function find($id)
    {
        return Branch::findOrFail($id);  
    }
    public function edit($id)
    {
        $branch = $this->findOne($id);  
        $categories = Category::select('id', 'name')->get();  
        return view('admin.branches.update', compact('branch', 'categories'));
    }
    
    
    // public function update($request, $id)
    // {
    //     $branch = $this->model->findOrFail($id);
    
    //     $branch->update([
    //         'category_id' => $request->category_id,
    //     ]);
    
    //     return $branch;
    // }

    public function archivesPage($offset, $limit)
    {
        $branches = $this->archives($offset, $limit);
        return view('admin.branches.archives', compact('branches'));
    }

    public function publish($id)
    {
        $record = $this->model->find($id);
        $record->is_published = $record->is_published == 1 ? 0 : 1;
        return $record->save();
    }

    public function verify($id)
    {
        $branch = $this->model->find($id);
        $branch->is_verified = $branch->is_verified == 1 ? 0 : 1;
        return $branch->save();
        // if ($branch) {
        // } else {
        //     throw new \Exception('Branch not found');
        // }
    }
    

}