<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Eloquent\Admin\CategoryRepository;
use App\Http\Repositories\Eloquent\Admin\HomeRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public $home;
    public $categoryRepository;

    public function __construct(HomeRepository $home, CategoryRepository $categoryRepository)
    {
        $this->home = $home;
        $this->categoryRepository = $categoryRepository;
    }

    public function home()
    {
        return $this->home->home();
    }

    public function categories(Request $request)
    {
        return $this->categoryRepository->search($request);
    }

}
