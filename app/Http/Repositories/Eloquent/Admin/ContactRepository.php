<?php

namespace App\Http\Repositories\Eloquent\Admin;

use App\Models\Contact;
use DevxPackage\AbstractRepository;

class ContactRepository extends AbstractRepository
{

    protected $model;

    public function __construct(Contact $model)
    {
        $this->model = $model;
    }

    public function crudName(): string
    {
        return 'contacts';
    }

    public function index($offset, $limit)
    {
        $contacts = $this->pagination($offset, $limit);
        return view('admin.contacts.index', compact('contacts'));
    }

    public function create()
    {
        return view('admin.contacts.create');
    }

    public function edit($id)
    {
        $contact = $this->findOne($id);
        return view('admin.contacts.update', compact('contact'));
    }

    public function archivesPage($offset, $limit)
    {
        $contacts = $this->archives($offset, $limit);
        return view('admin.contacts.archives', compact('contacts'));
    }

}