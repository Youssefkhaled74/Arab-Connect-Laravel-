<?php

namespace App\Http\Repositories\Eloquent\Admin;

use App\Http\ServicesLayer\Admin\HomeServices\HomeService;
use DevxPackage\AbstractRepository;

class HomeRepository extends AbstractRepository
{
    protected $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }

    public function crudName(): string
    {
        return '';
    }

    public function home(){
        return $this->homeService->home();
    }

}