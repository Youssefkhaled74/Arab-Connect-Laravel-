<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Repositories\Eloquent\Admin\AboutRepository;
use App\Http\Requests\Admin\AboutRequests\AboutStoreRequest;
use App\Http\Requests\Admin\AboutRequests\AboutUpdateRequest;

class AboutController extends Controller
{

    public $abouts;

    public function __construct(AboutRepository $abouts)
    {
        $this->abouts = $abouts;
    }

    public function index($offset, $limit)
    {
        try{
            return $this->abouts->index($offset, $limit);
        }catch(\Exception $e){
            flash()->error('There is something wrong , please contact technical support');
            return back();
        }
    }

    public function create()
    {
        return $this->abouts->create();
    }

    public function store(AboutStoreRequest $request)
    {
        try{
            $this->abouts->store($request);
            flash()->success('Success');
            return back();
        }catch(\Exception $e){
            flash()->error('There is something wrong , please contact technical support');
            return back();
        }
    }

    public function edit($id)
    {
        return $this->abouts->edit($id);
    }

    public function update(AboutUpdateRequest $request, $id)
    {
        try{
            $this->abouts->update($request, $id);
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
            $this->abouts->activate($request->record_id);
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
            $this->abouts->delete($request->record_id);
            flash()->success('Success');
            return back();
        }catch(\Exception $e){
            flash()->error('There is something wrong , please contact technical support');
            return back();
        }
    }

    public function search(Request $request)
    {
        return $this->abouts->search($request);
    }

    public function searchByColumn(Request $request)
    {
        return $this->abouts->searchByColumn($request);
    }

    public function pagination($offset, $limit)
    {
        return $this->abouts->pagination($offset, $limit);
    }

    public function archives($offset, $limit)
    {
        return $this->abouts->archivesPage($offset, $limit);
    }

    public function archivesPagination($offset, $limit)
    {
        return $this->abouts->archives($offset, $limit);
    }

    public function archivesSearch(Request $request)
    {
        return $this->abouts->archivesSearch($request);
    }

    public function archivesSearchByColumn(Request $request)
    {
        return $this->abouts->archivesSearchByColumn($request);
    }


    public function back(Request $request)
    {
        try{
            $this->abouts->back($request->record_id);
            flash()->success('Success');
            return back();
        }catch(\Exception $e){
            flash()->error('There is something wrong , please contact technical support');
            return back();
        }
    }

}