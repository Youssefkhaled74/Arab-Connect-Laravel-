<?php

namespace App\Http\Repositories\Eloquent\Admin;

use App\Models\User;
use DevxPackage\AbstractRepository;

class UserRepository extends AbstractRepository
{

    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function crudName(): string
    {
        return 'users';
    }

    public function index($offset, $limit)
    {
        $users = $this->pagination($offset, $limit);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function edit($id)
    {
        $user = $this->findOne($id);
        return view('admin.users.update', compact('user'));
    }

    public function archivesPage($offset, $limit)
    {
        $users = $this->archives($offset, $limit);
        return view('admin.users.archives', compact('users'));
    }

}