<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Repositories\Eloquent\Admin\BranchRepository;
use App\Http\Requests\Admin\BranchRequests\BranchStoreRequest;
use App\Http\Requests\Admin\BranchRequests\BranchUpdateRequest;

class BranchController extends Controller
{

    public $branches;

    public function __construct(BranchRepository $branches)
    {
        $this->branches = $branches;
    }

    public function index($offset, $limit)
    {
        try{
            return $this->branches->index($offset, $limit);
        }catch(\Exception $e){
            flash()->error('There is something wrong , please contact technical support');
            return back();
        }
    }

    public function create()
    {
        return $this->branches->create();
    }

    public function store(BranchStoreRequest $request)
    {
        try{
            $this->branches->store($request);
            flash()->success('Success');
            return back();
        }catch(\Exception $e){
            flash()->error('There is something wrong , please contact technical support');
            return back();
        }
    }

    public function edit($id)
    {
        return $this->branches->edit($id);
    }

    public function update(BranchUpdateRequest $request, $id)
    {
        try{
            $this->branches->update($request, $id);
            flash()->success('Success');
            return back();
        }catch(\Exception $e){
            flash()->error('There is something wrong , please contact technical support');
            return back();
        }
    }

    // public function edit($id)
    // {
    //     // استدعاء دالة edit من BranchRepository
    //     try {
    //         return $this->branches->edit($id);
    //     } catch (\Exception $e) {
    //         flash()->error('There is something wrong , please contact technical support');
    //         return back();
    //     }
    // }
    
    // public function update(BranchUpdateRequest $request, $id)
    // {
    //     try {
    //         $branch = $this->branches->find($id);
    
    //         if (!$branch) {
    //             flash()->error('Branch not found');
    //             return back();
    //         }
    
    //         $branch->update([
    //             'category_id' => $request->category_id,
    //         ]);
    
    //         flash()->success('Branch updated successfully');
    //         return back();
    //     } catch (\Exception $e) {
    //         flash()->error('There is something wrong, please contact technical support');
    //         return back();
    //     }
    // }

    public function activate(Request $request)
    {
        try{
            $this->branches->activate($request->record_id);
            flash()->success('Success');
            return back();
        }catch(\Exception $e){
            flash()->error('There is something wrong , please contact technical support');
            return back();
        }
    }

    public function publish(Request $request)
    {
        try{
            $this->branches->publish($request->record_id);
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
            $this->branches->delete($request->record_id);
            flash()->success('Success');
            return back();
        }catch(\Exception $e){
            flash()->error('There is something wrong , please contact technical support');
            return back();
        }
    }

    public function search(Request $request)
    {
        return $this->branches->search($request);
    }

    public function searchByColumn(Request $request)
    {
        return $this->branches->searchByColumn($request);
    }

    public function pagination($offset, $limit)
    {
        return $this->branches->pagination($offset, $limit);
    }

    public function archives($offset, $limit)
    {
        return $this->branches->archivesPage($offset, $limit);
    }

    public function archivesPagination($offset, $limit)
    {
        return $this->branches->archives($offset, $limit);
    }

    public function archivesSearch(Request $request)
    {
        return $this->branches->archivesSearch($request);
    }

    public function archivesSearchByColumn(Request $request)
    {
        return $this->branches->archivesSearchByColumn($request);
    }


    public function back(Request $request)
    {
        try{
            $this->branches->back($request->record_id);
            flash()->success('Success');
            return back();
        }catch(\Exception $e){
            flash()->error('There is something wrong , please contact technical support');
            return back();
        }
    }

    public function verify(Request $request)
    {
        try {
            // تحقق من وجود record_id
            // if (!$request->has('record_id')) {
            //     flash()->error('Record ID is required');
            //     return back();
            // }
    
            // نفذ عملية التحقق
            $this->branches->verify($request->record_id);
            flash()->success('Branch verified successfully');
            return back();
        } catch (\Exception $e) {
            // flash()->error('There is something wrong: ' . $e->getMessage());
            flash()->error('There is something wrong , please contact technical support');
            return back();
        }
    }
    
}