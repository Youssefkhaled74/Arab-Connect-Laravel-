<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Eloquent\Admin\AdminRepository;
use App\Http\Requests\Admin\AdminRequests\AdminStoreRequest;
use App\Http\Requests\Admin\AdminRequests\AdminUpdateRequest;

class AdminController extends Controller
{

    public $admins;

    public function __construct(AdminRepository $admins)
    {
        $this->admins = $admins;
    }

    public function index($offset, $limit)
    {
        try{
            return $this->admins->index($offset, $limit);
        }catch(\Exception $e){
            flash()->error('There is something wrong , please contact technical support');
            return back();
        }
    }

    public function create()
    {
        return $this->admins->create();
    }

    public function store(AdminStoreRequest $request)
    {
        try{
            $this->admins->store($request);
            flash()->success('Success');
            return back();
        }catch(\Exception $e){
            flash()->error('There is something wrong , please contact technical support');
            return back();
        }
    }
    public function activate(Request $request)
    {
        try{
            $this->admins->activate($request->record_id);
            flash()->success('Success');
            return back();
        }catch(\Exception $e){
            flash()->error('There is something wrong , please contact technical support');
            return back();
        }
    }

    public function delete(Request $request)
    {
        try{
            $this->admins->delete($request->record_id);
            flash()->success('Success');
            return back();
        }catch(\Exception $e){
            flash()->error('There is something wrong , please contact technical support');
            return back();
        }
    }

    public function search(Request $request)
    {
        return $this->admins->search($request);
    }

    public function searchByColumn(Request $request)
    {
        return $this->admins->searchByColumn($request);
    }

    public function pagination($offset, $limit)
    {
        return $this->admins->pagination($offset, $limit);
    }

    public function archives($offset, $limit)
    {
        return $this->admins->archivesPage($offset, $limit);
    }

    public function archivesPagination($offset, $limit)
    {
        return $this->admins->archives($offset, $limit);
    }

    public function archivesSearch(Request $request)
    {
        return $this->admins->archivesSearch($request);
    }

    public function archivesSearchByColumn(Request $request)
    {
        return $this->admins->archivesSearchByColumn($request);
    }

    public function back(Request $request)
    {
        try{
            $this->admins->back($request->record_id);
            flash()->success('Success');
            return back();
        }catch(\Exception $e){
            flash()->error('There is something wrong , please contact technical support');
            return back();
        }
    }

    public function info()
    {
        $admin = $this->admins->info();
        return view('admin.admins.info', compact('admin'));
    }
    
    public function info_update(AdminUpdateRequest $request)
    {
        try{
            $user = $this->admins->info();
            $this->admins->update($request, $user->id);
            flash()->success('Success');
            return back();
        }catch(\Exception $e){
            flash()->error('There is something wrong , please contact technical support');
            return back();
        }
    }
    
    public function change_password(AdminUpdateRequest $request)
    {
        try{
            $this->admins->changePassword($request);
            flash()->success('Success');
            return back();
        }catch(\Exception $e){
            flash()->error('There is something wrong , please contact technical support');
            return back();
        }
    }

}