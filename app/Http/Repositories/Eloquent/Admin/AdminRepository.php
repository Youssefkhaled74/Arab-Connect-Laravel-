<?php

namespace App\Http\Repositories\Eloquent\Admin;

use App\Models\Admin;
use DevxPackage\AbstractRepository;

class AdminRepository extends AbstractRepository
{

    protected $model;

    public function __construct(Admin $model)
    {
        $this->model = $model;
    }

    public function crudName(): string
    {
        return 'admins';
    }

    public function index($offset, $limit)
    {
        $admins = $this->pagination($offset, $limit);
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function archivesPage($offset, $limit)
    {
        $admins = $this->archives($offset, $limit);
        return view('admin.admins.archives', compact('admins'));
    }

    public function info()
    {
        return auth()->guard('admin')->user();
    }

    public function changePassword($request)
    {
        $admin = $this->info();
        // check old password
        if(!Hash::check($request->input('old_password'), $admin->password)){
            flash()->error("There IS Something Wrong");
            return back();
        }
        // update password
        $admin->password = bcrypt($request->input('password'));
        return $admin->save();
    }


}