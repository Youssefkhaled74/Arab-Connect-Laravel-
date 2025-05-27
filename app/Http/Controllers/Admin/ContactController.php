<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Repositories\Eloquent\Admin\ContactRepository;
use App\Http\Requests\Admin\ContactRequests\ContactStoreRequest;
use App\Http\Requests\Admin\ContactRequests\ContactUpdateRequest;

class ContactController extends Controller
{

    public $contacts;

    public function __construct(ContactRepository $contacts)
    {
        $this->contacts = $contacts;
    }

    public function index($offset, $limit)
    {
        try{
            return $this->contacts->index($offset, $limit);
        }catch(\Exception $e){
            flash()->error('There is something wrong , please contact technical support');
            return back();
        }
    }

    public function create()
    {
        return $this->contacts->create();
    }

    public function store(ContactStoreRequest $request)
    {
        try{
            $this->contacts->store($request);
            flash()->success('Success');
            return back();
        }catch(\Exception $e){
            flash()->error('There is something wrong , please contact technical support');
            return back();
        }
    }

    public function edit($id)
    {
        return $this->contacts->edit($id);
    }

    public function update(ContactUpdateRequest $request, $id)
    {
        try{
            $this->contacts->update($request, $id);
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
            $this->contacts->activate($request->record_id);
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
            $this->contacts->delete($request->record_id);
            flash()->success('Success');
            return back();
        }catch(\Exception $e){
            flash()->error('There is something wrong , please contact technical support');
            return back();
        }
    }

    public function search(Request $request)
    {
        return $this->contacts->search($request);
    }

    public function searchByColumn(Request $request)
    {
        return $this->contacts->searchByColumn($request);
    }

    public function pagination($offset, $limit)
    {
        return $this->contacts->pagination($offset, $limit);
    }

    public function archives($offset, $limit)
    {
        return $this->contacts->archivesPage($offset, $limit);
    }

    public function archivesPagination($offset, $limit)
    {
        return $this->contacts->archives($offset, $limit);
    }

    public function archivesSearch(Request $request)
    {
        return $this->contacts->archivesSearch($request);
    }

    public function archivesSearchByColumn(Request $request)
    {
        return $this->contacts->archivesSearchByColumn($request);
    }


    public function back(Request $request)
    {
        try{
            $this->contacts->back($request->record_id);
            flash()->success('Success');
            return back();
        }catch(\Exception $e){
            flash()->error('There is something wrong , please contact technical support');
            return back();
        }
    }

}