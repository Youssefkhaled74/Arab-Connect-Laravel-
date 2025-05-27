<?php

namespace App\Http\Repositories\Eloquent\Admin;

use App\Models\PaymentMethod;
use DevxPackage\AbstractRepository;

class PaymentMethodRepository extends AbstractRepository
{

    protected $model;

    public function __construct(PaymentMethod $model)
    {
        $this->model = $model;
    }

    public function crudName(): string
    {
        return 'paymentMethods';
    }

    public function index($offset, $limit)
    {
        $paymentMethods = $this->pagination($offset, $limit);
        return view('admin.paymentMethods.index', compact('paymentMethods'));
    }

    public function create()
    {
        return view('admin.paymentMethods.create');
    }

    public function edit($id)
    {
        $paymentMethod = $this->findOne($id);
        return view('admin.paymentMethods.update', compact('paymentMethod'));
    }

    public function archivesPage($offset, $limit)
    {
        $paymentMethods = $this->archives($offset, $limit);
        return view('admin.paymentMethods.archives', compact('paymentMethods'));
    }

}