<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\ServicesLayer\Api\BranchServices\BranchService;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    public $branch;
    public $branchService;

    public function __construct(Branch $branch, BranchService $branchService)
    {
        $this->branch = $branch;
        $this->branchService = $branchService;
        $this->middleware('auth:api', ['except' => ['details', 'branches','branch']]);
    }

    public function branch($id = 0)
    {

        $branch = $this->branch->unArchive()->where('id', $id)->with([
            'payments',
            'days',
            'related_branches' => function ($query) use($id) {
                $query->whereNotIn('id', [$id]);
            }
        ])
        ->where('expire_at','>=',now())
        ->first();
        return responseJson(200, "success", $branch);
    }

    public function details($id = 0)
    {
        $branch = $this->branchService->details($id);
        return responseJson(200, "success", $branch);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:255',
            'location' => 'required|string|max:1550',
            // 'map_location' => 'required|string|max:1550',
            'category_id' => 'nullable|exists:categories,id',
            'imgs' => 'required|array',
            'imgs.*' => 'file|image|max:5125',

            'payments' => 'nullable|array',
            'payments.*' => 'nullable|exists:payment_methods,id',
            'email' => 'nullable|string|max:255',
            'face' => 'nullable|string|max:1550',
            'insta' => 'nullable|string|max:1550',
            'tiktok' => 'nullable|string|max:1550',
            'website' => 'nullable|string|max:1550',
            'tax_card' => 'nullable|file|image|max:5120',
            'commercial_register' => 'nullable|file|image|max:5120',
            'lat' => 'nullable|max:100',
            'lon' => 'nullable|max:100',

            'days' => 'nullable|array',
            'days.*.off' => 'nullable|in:0,1',
            'days.*.day' => 'nullable|min:1|max:7',
            'days.*.from' => 'nullable',
            'days.*.to' => 'nullable',
            "all_days" => "nullable|in:0,1",
        ]);
        if ($validator->fails()) {
            return responseJson(400, "Bad Request", $validator->errors()->first());
        }
        if(!$request->hasFile('imgs') == null && count($request->file('imgs')) > 10){
            return responseJson(500, "not accepted more than 10 imgs");
        }
        $this->branchService->store($request, auth()->user()->id);
        return responseJson(200, "success");
    }

    public function update(Request $request, $id = 0)
    {
        if ($id > 0) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'mobile' => 'required|string|max:255',
                'location' => 'required|string|max:1550',
                // 'map_location' => 'required|string|max:1550',
                'category_id' => 'nullable|exists:categories,id',

                //'imgs' => 'required_without:new_imgs',
				'imgs' => 'nullable',
                'new_imgs' => 'nullable|array',
                'new_imgs.*' => 'file|image|max:5120',

                'payments' => 'nullable|array',
                'payments.*' => 'nullable|exists:payment_methods,id',
                'email' => 'nullable|string|max:255',
                'face' => 'nullable|string|max:1550',
                'insta' => 'nullable|string|max:1550',
                'tiktok' => 'nullable|string|max:1550',
                'website' => 'nullable|string|max:1550',
                'tax_card' => 'nullable|max:5120',
                'commercial_register' => 'nullable|max:5120',
                'lat' => 'nullable|max:100',
                'lon' => 'nullable|max:100',
                'days' => 'nullable|array',
                'days.*.off' => 'nullable|in:0,1',
                'days.*.day' => 'nullable|min:1|max:7',
                'days.*.from' => 'nullable',
                'days.*.to' => 'nullable',
                "all_days" => "nullable|in:0,1",
            ]);
            if ($validator->fails()) {
                return responseJson(400, "Bad Request", $validator->errors()->first());
            }
            // $new_imgs_count = isset($request->new_imgs) ? count($request->new_imgs) : 0;
            // if(count(explode(',', $request->imgs)) + $new_imgs_count > 10){
            //     return responseJson(500, "not accepted more than 10 imgs");
            // }
            // dd($request->all());
            $update = $this->branchService->update($request, $id);
            // if (!$update) {
            //     return responseJson(400, "Bad Request");
            // }
        }
        return responseJson(200, "success");
    }

    public function branches(Request $request, $page = 0)
    {
        $space = 5000;
        $search = $request->get('search');
        $branchID = (int)$request->get('id');
        $category_id = (int)$request->get('category_id');
    
        $query = $this->branch->unArchive()->published();
        $query->where('expire_at', '>=', now());
    
        if ($branchID > 0) {
            $query->where('id', $branchID);
        } else if (!is_null($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereAny(['branches.name', 'branches.email', 'branches.mobile', 'branches.location', 'branches.uuid'], 'LIKE', '%' . $search . '%')
                  ->orWhereHas('category', function ($q) use ($search) {
                      $q->where('name', 'LIKE', '%' . $search . '%'); 
                  });
            });
        } else {
            $lon = $request->lon ?? 0;
            $lat = $request->lat ?? 0;
            $query->select(
                'branches.*', DB::raw("6371000 * acos(cos(radians(" . $lat . "))
                * cos(radians(lat))
                * cos(radians(lon) - radians(" . $lon . "))
                + sin(radians(" . $lat . "))
                * sin(radians(lat))) AS distance")
            )
            ->having('distance', '<', $space);
        }
    
        if ($branchID == 0 && $category_id > 0) {
            $query->where('category_id', $category_id);
        }
    
        $branches['branches'] = $query->orderBy('id', 'desc')
            ->offset(PAGINATION_COUNT_FRONT * $page)->limit(PAGINATION_COUNT_FRONT)
            ->get();
    
        return responseJson(200, "success", $branches);
    }
    
}
