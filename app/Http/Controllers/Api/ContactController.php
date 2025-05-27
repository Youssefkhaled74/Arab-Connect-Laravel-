<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\ServicesLayer\Api\BranchServices\BranchService;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{

    public $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
        $this->middleware('auth:api', ['except' => ['store']]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'mobile' => 'required|string|max:255',
            'message' => 'required|string|max:12000',
        ]);
        if ($validator->fails()) {
            return responseJson(400, "Bad Request", $validator->errors()->first());
        }
        $request = $this->handle_request($request);
        $this->contact->create($request);
        return responseJson(200, "success");
    }

    public function handle_request($request)
    {
        $request = array_filter(array_intersect_key($request->all(), $this->contact->fildes()));
        return $request;
    }
}
