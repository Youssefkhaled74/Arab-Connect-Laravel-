<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Eloquent\Admin\AuthRepository;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\AdminRequests\AdminLoginRequest;

class AuthController extends Controller
{

    public $auth;

    public function __construct(AuthRepository $auth)
    {
        $this->auth = $auth;
    }

    public function login()
    {
        return $this->auth->login();
    }

    public function check_login(AdminLoginRequest $request)
    {
        return $this->auth->check_login($request);
    }

    public function logout()
    {
        return $this->auth->logout();
    }

}
