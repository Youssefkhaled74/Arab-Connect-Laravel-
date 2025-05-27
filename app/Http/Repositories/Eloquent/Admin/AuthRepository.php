<?php

namespace App\Http\Repositories\Eloquent\Admin;

use App\Models\Admin;
use DevxPackage\AbstractRepository;
use App\Http\ServicesLayer\Admin\AdminServices\AuthService;

class AuthRepository extends AbstractRepository
{

    protected $model;
    protected $authService;

    public function __construct(Admin $model, AuthService $authService)
    {
        $this->model = $model;
        $this->authService = $authService;
    }

    public function crudName(): string
    {
        return '';
    }

    public function login()
    {
        return $this->authService->login();
    }

    public function check_login($request)
    {
        return $this->authService->check_login($request);
    }

    public function logout()
    {
        return $this->authService->logout();
    }

}