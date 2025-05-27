<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use App\Http\Resources\PackageResource;


class PackageController extends Controller
{
    public function index()
    {
        return responseJson(200, 'success', PackageResource::collection(Package::where('status', 1)->get()));
    }
}
