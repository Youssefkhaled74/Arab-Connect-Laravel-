<?php

namespace App\Http\Controllers\Api;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class HomePageController extends Controller
{
    public function nearestBranches(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return responseJson(400, "Bad Request", $validator->errors()->first());
        }

        $lat = $request->lat;
        $lon = $request->lon;

        // Only select branches with valid lat/lon
        $branches = Branch::whereNotNull('lat')
            ->whereNotNull('lon')
            ->where('lat', '!=', '')
            ->where('lon', '!=', '')
            ->whereRaw('lat REGEXP "^-?[0-9]+(\.[0-9]+)?$"')
            ->whereRaw('lon REGEXP "^-?[0-9]+(\.[0-9]+)?$"')
            ->select('*', DB::raw(
                "(6371 * acos(
                cos(radians($lat)) * cos(radians(lat)) * cos(radians(lon) - radians($lon)) +
                sin(radians($lat)) * sin(radians(lat))
            )) AS distance"
            ))
            ->orderBy('distance')
            ->limit(20)
            ->get();

        // Add 'how_far' key to each branch
        $branches->transform(function ($branch) {
            $branch->how_far = isset($branch->distance) ? round($branch->distance, 2) : null; // in km
            unset($branch->distance);
            return $branch;
        });

        return responseJson(200, "success", $branches);
    }


    //checkToken
    public function checkToken(Request $request)
    {
        if ($request->user()) {
            return responseJson(200, 'Token valid', true);
        } else {
            return responseJson(404, 'Token invalid',false);
        }
    }
}
