<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Repositories\Eloquent\Admin\PaymentMethodRepository;
use App\Http\Requests\Admin\PaymentMethodRequests\PaymentMethodStoreRequest;
use App\Http\Requests\Admin\PaymentMethodRequests\PaymentMethodUpdateRequest;

class PaymentMethodController extends Controller
{

    public $paymentMethods;

    public function __construct(PaymentMethodRepository $paymentMethods)
    {
        $this->paymentMethods = $paymentMethods;
    }

    public function index($offset, $limit)
    {
        try{
            return $this->paymentMethods->index($offset, $limit);
        }catch(\Exception $e){
            flash()->error('There is something wrong , please contact technical support');
            return back();
        }
    }

    public function create()
    {
        return $this->paymentMethods->create();
    }

    public function store(PaymentMethodStoreRequest $request)
    {
        try{
            $this->paymentMethods->store($request);
            flash()->success('Success');
            return back();
        }catch(\Exception $e){
            flash()->error('There is something wrong , please contact technical support');
            return back();
        }
    }

    public function edit($id)
    {
        return $this->paymentMethods->edit($id);
    }

    public function update(PaymentMethodUpdateRequest $request, $id)
    {
        try{
            $this->paymentMethods->update($request, $id);
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
            $this->paymentMethods->activate($request->record_id);
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
            $this->paymentMethods->delete($request->record_id);
            flash()->success('Success');
            return back();
        }catch(\Exception $e){
            flash()->error('There is something wrong , please contact technical support');
            return back();
        }
    }

    public function search(Request $request)
    {
        return $this->paymentMethods->search($request);
    }

    public function searchByColumn(Request $request)
    {
        return $this->paymentMethods->searchByColumn($request);
    }

    public function pagination($offset, $limit)
    {
        return $this->paymentMethods->pagination($offset, $limit);
    }

    public function archives($offset, $limit)
    {
        return $this->paymentMethods->archivesPage($offset, $limit);
    }

    public function archivesPagination($offset, $limit)
    {
        return $this->paymentMethods->archives($offset, $limit);
    }

    public function archivesSearch(Request $request)
    {
        return $this->paymentMethods->archivesSearch($request);
    }

    public function archivesSearchByColumn(Request $request)
    {
        return $this->paymentMethods->archivesSearchByColumn($request);
    }


    public function back(Request $request)
    {
        try{
            $this->paymentMethods->back($request->record_id);
            flash()->success('Success');
            return back();
        }catch(\Exception $e){
            flash()->error('There is something wrong , please contact technical support');
            return back();
        }
    }

}