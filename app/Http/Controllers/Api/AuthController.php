<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\ServicesLayer\Api\BranchServices\BranchService;
use App\Http\ServicesLayer\Api\WhatsAppServices\WhatsAppService;
use App\Models\Branch;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash as FacadesHash;
use Illuminate\Support\Facades\Notification as FacadesNotification;
use App\Notifications\UpdateEmail;


class AuthController extends Controller
{
    public $user;
    public $branch;
    public $branchService;
    public $whatsAppService;

    public function __construct(User $user, Branch $branch, BranchService $branchService, WhatsAppService $whatsAppService)
    {
        $this->user = $user;
        $this->branch = $branch;
        $this->branchService = $branchService;
        $this->whatsAppService = $whatsAppService;
        $this->middleware('auth:api', ['except' => ['emailCheck', 'login', 'register', 'mobileCheck', 'regenerateCode', 'sendResetCode', 'verifyResetCode', 'resetPassword', 'changePassword', 'registerUser', 'registerVendor']]);
    }

    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|unique:users,email|max:50',
    //         'mobile' => 'required|unique:users,mobile|max:50',
    //         'password' => 'required|confirmed|max:30',
    //         'name' => 'required|string|max:60',
    //         'user_type' => 'required|in:1,2',
    //         'country.name' => 'required|string|max:100',
    //         'country.flag' => 'required|file|image|max:5120',

    //         'branch.name' => 'required_if:user_type,1|string|max:255',
    //         'branch.mobile' => 'required_if:user_type,1|string|max:255',
    //         'branch.location' => 'required_if:user_type,1|string|max:1550',
    //         'branch.lat' => 'required_if:user_type,1|string|max:255',
    //         'branch.lon' => 'required_if:user_type,1|string|max:255',
    //         'branch.img' => 'required_if:user_type,1|file|image|max:5120',
    //         'branch.category_id' => 'nullable|integer|exists:categories,id',
    //         'branch.payments' => 'nullable|array',
    //         'branch.payments.*' => 'nullable|exists:payment_methods,id',
    //         'branch.email' => 'nullable|string|max:255',
    //         'branch.face' => 'nullable|string|max:1550',
    //         'branch.insta' => 'nullable|string|max:1550',
    //         'branch.tiktok' => 'nullable|string|max:1550',
    //         'branch.website' => 'nullable|string|max:1550',
    //         'branch.tax_card' => 'nullable|file|image|max:5120',
    //         'branch.commercial_register' => 'nullable|file|image|max:5120',
    //     ]);

    //     if ($validator->fails()) {
    //         return responseJson(400, "Bad Request", $validator->errors()->first());
    //     }

    //     try {
    //         DB::beginTransaction();

    //         $active = $request->user_type == 1 ? 0 : 1;

    //         // Upload country flag
    //         $country_flag = uploadIamge($request->file('country.flag'), 'countries');

    //         $user = $this->user->create([
    //             'email' => $request->email ?? null,
    //             'mobile' => $request->mobile ?? null,
    //             'name' => $request->name ?? null,
    //             'password' => bcrypt($request->password) ?? null,
    //             'user_type' => $request->user_type ?? null,
    //             'code' => 1111, 
    //             'is_activate' => $active,
    //             'country_name' => $request->country['name'] ?? null,
    //             'country_flag' => $country_flag ?? null,
    //         ]);

    //         if (isset($request->branch) && !is_null($request->branch)) {
    //             // Upload branch image and documents
    //             $branch_img = uploadIamge($request->file('branch.img'), 'branches');
    //             $tax_card = $request->hasFile('branch.tax_card') ? uploadIamge($request->file('branch.tax_card'), 'branches') : null;
    //             $commercial_register = $request->hasFile('branch.commercial_register') ? uploadIamge($request->file('branch.commercial_register'), 'branches') : null;

    //             $map_location = generateGoogleMapsLink($request->branch['lat'], $request->branch['lon']);

    //             $branch = $this->branch->create([
    //                 'name' => $request->branch['name'] ?? null,
    //                 'mobile' => $request->branch['mobile'] ?? null,
    //                 'location' => $request->branch['location'] ?? null,
    //                 'map_location' => $map_location ?? null,
    //                 'category_id' => $request->branch['category_id'] ?? null,
    //                 'email' => $request->branch['email'] ?? null,
    //                 'face' => $request->branch['face'] ?? null,
    //                 'insta' => $request->branch['insta'] ?? null,
    //                 'tiktok' => $request->branch['tiktok'] ?? null,
    //                 'website' => $request->branch['website'] ?? null,
    //                 'img' => $branch_img ?? null,
    //                 'tax_card' => $tax_card ?? null,
    //                 'commercial_register' => $commercial_register ?? null,
    //                 'uuid' => generateCustomUUID(),
    //                 'owner_id' => $user->id,
    //                 'expire_at' => now()->addMonths(6),
    //                 'lat' => $request->branch['lat'] ?? null,
    //                 'lon' => $request->branch['lon'] ?? null,
    //             ]);

    //             if (isset($request->branch['payments']) && count($request->branch['payments']) > 0) {
    //                 $branch->payments()->sync(array_values($request->branch['payments']));
    //             }
    //         }

    //         DB::commit();
    //         return responseJson(200, "Success");
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         return responseJson(500, "Internal Server Error", $e->getMessage());
    //     }
    // }
    public function register(Request $request)
    {
        $rules = [
            'email' => 'required|unique:users,email|max:50',
            'mobile' => 'required|unique:users,mobile|max:50',
            'country_code' => 'required|string|max:10',
            'password' => 'required|confirmed|max:30',
            'name' => 'required|string|max:60',
            'user_type' => 'required|in:1,2', // 1 = vendor, 2 = user
            'country_id' => 'required|integer|exists:countries,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return responseJson(400, "Bad Request", $validator->errors()->first());
        }

        try {
            DB::beginTransaction();

            $is_activate = $request->user_type == 1 ? 0 : 1;

            $this->user->create([
                'email' => $request->email ?? null,
                'mobile' => $request->mobile ?? null,
                'country_code' => $request->country_code ?? null,
                'country_id' => $request->country_id ?? null,
                'name' => $request->name ?? null,
                'password' => bcrypt($request->password) ?? null,
                'user_type' => $request->user_type,
                'code' => 1111,
                'is_activate' => $is_activate,
            ]);

            DB::commit();

            return responseJson(200, "Success");
        } catch (\Exception $e) {
            DB::rollback();
            return responseJson(500, "Internal Server Error", $e->getMessage());
        }
    }
    public function addBranch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:255',
            'country_code' => 'nullable|string|max:10',
            'country_id' => 'required|integer|exists:countries,id',
            'location' => 'required|string|max:1550',
            'lat' => 'required|string|max:255',
            'lon' => 'required|string|max:255',
            'img' => 'required|file|image|max:5120',
            'category_id' => 'nullable|integer|exists:categories,id',
            'payments' => 'nullable|array',
            'payments.*' => 'nullable|exists:payment_methods,id',
            'email' => 'nullable|string|max:255',
            'face' => 'nullable|string|max:1550',
            'insta' => 'nullable|string|max:1550',
            'tiktok' => 'nullable|string|max:1550',
            'website' => 'nullable|string|max:1550',
            'tax_card' => 'nullable|file|image|max:5120',
            'commercial_register' => 'nullable|file|image|max:5120',
        ]);

        if ($validator->fails()) {
            return responseJson(400, "Bad Request", $validator->errors()->first());
        }

        try {
            DB::beginTransaction();

            $user = auth()->user();

            $branch_img = uploadIamge($request->file('img'), 'branches');
            $tax_card = $request->hasFile('tax_card') ? uploadIamge($request->file('tax_card'), 'branches') : null;
            $commercial_register = $request->hasFile('commercial_register') ? uploadIamge($request->file('commercial_register'), 'branches') : null;
            $map_location = generateGoogleMapsLink($request->lat, $request->lon);

            $branch = $this->branch->create([
                'name' => $request->name ?? null,
                'mobile' => $request->mobile ?? null,
                'country_code' => $request->country_code ?? null,
                'country_id' => $request->country_id ?? null,
                'location' => $request->location ?? null,
                'map_location' => $map_location ?? null,
                'category_id' => $request->category_id ?? null,
                'email' => $request->email ?? null,
                'face' => $request->face ?? null,
                'insta' => $request->insta ?? null,
                'tiktok' => $request->tiktok ?? null,
                'website' => $request->website ?? null,
                'img' => $branch_img ?? null,
                'tax_card' => $tax_card ?? null,
                'commercial_register' => $commercial_register ?? null,
                'uuid' => generateCustomUUID(),
                'owner_id' => $user->id,
                'expire_at' => now()->addMonths(6),
                'lat' => $request->lat ?? null,
                'lon' => $request->lon ?? null,
            ]);

            if ($request->has('payments') && is_array($request->payments)) {
                $branch->payments()->sync(array_values($request->payments));
            }

            DB::commit();
            return responseJson(200, "Branch created successfully", $branch);
        } catch (\Exception $e) {
            DB::rollback();
            return responseJson(500, "Internal Server Error", $e->getMessage());
        }
    }

    public function emailCheck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|exists:users,code|max:4',
            'email' => 'required|exists:users,email|max:255',
        ]);
        if ($validator->fails()) {
            return responseJson(400, "Bad Request", $validator->errors()->first());
        }
        try {
            $user = $this->user->where('email', $request->email)->where('code', $request->code)->first();
            if (!$user || !is_null($user->deleted_at)) {
                return responseJson(401, "This Account Not Activate , Please Contact Technical Support");
            }
            DB::beginTransaction();
            $user->update([
                'code' => null,
                'email_verified_at' => now(),
            ]);
            $user->token = JWTAuth::customClaims(['exp' => Carbon::now()->addYears(20)->timestamp])->fromUser($user);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return responseJson(500, "Internal Server Error");
        }
        return responseJson(200, "success", $user->only(['id', 'name', 'mobile', 'email', 'user_type', 'token']));
    }

    public function regenerateCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email|max:255',
        ]);
        if ($validator->fails()) {
            return responseJson(400, "Bad Request", $validator->errors()->first());
        }

        $user = $this->user->where('email', $request->email)->first();
        if (!is_null($user->deleted_at)) {
            return responseJson(401, "This Account Not Activate , Please Contact Technical Support");
        }
        try {
            DB::beginTransaction();
            $user->update([
                'code' => 1111, // or rand(1000, 9999)
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return responseJson(500, "Internal Server Error");
        }
        return responseJson(200, "success");
    }

    public function login(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|exists:users,mobile|max:60',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return responseJson(400, "Bad Request", $validator->errors()->first());
        }
        $user = $this->user->where('mobile', $request->mobile)->first();
        if (!is_null($user->deleted_at)) {
            return responseJson(401, "This Account Not Activate , Please Contact Technical Support");
        }
        if (!$user->is_activate) {
            return responseJson(401, "This Account Not Verified , Please Contact Technical Support");
        }
        try {
            if (!FacadesHash::check($request->password, $user->password)) {
                return response()->json(['error' => 'Invalid Credentials'], 401);
            }
            $user->token = JWTAuth::customClaims(['exp' => Carbon::now()->addYears(20)->timestamp])->fromUser($user);
        } catch (\Exception $e) {
            return responseJson(500, "Internal Server Error");
        }
        return responseJson(200, "success", $user->only(['id', 'name', 'mobile', 'email', 'user_type', 'token']));
    }

    public function me()
    {
        return responseJson(200, "success", auth()->user()->only(['id', 'name', 'mobile', 'email', 'user_type']));
    }

    public function userBranches()
    {
        $branches['branches'] = $this->branchService->userBranches();
        return responseJson(200, "success", $branches);
    }

    public function getFavorites()
    {
        try {
            $branches['branches'] = $this->branchService->getFavorites() ?? [];
            return responseJson(200, "success", $branches);
        } catch (\Exception $e) {
            return responseJson(500, "Internal Server Error", $e->getMessage());
        }
    }

    public function addFavorites($id = 0)
    {
        try {
            if ($id > 0) {
                return $this->branchService->favorites($id);
            }
            return responseJson(200, "success");
        } catch (\Exception $e) {
            return responseJson(500, "Internal Server Error");
        }
    }

    public function logout()
    {
        auth()->logout();
        $data['token'] = null;
        return responseJson(200, "successfully logged out", $data);
    }

    public function refresh()
    {
        return responseJson(200, "success", auth()->refresh());
    }

    public function userUpdate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:254',
            // 'email' => 'required|string|max:254|unique:users,email,' . auth()->user()->id,
        ]);
        if ($validator->fails()) {
            return responseJson(400, "Bad Request", $validator->errors()->first());
        }

        $user = auth()->user();
        $request->name ? $user->name = $request->name : '';
        $request->email ? $user->email = $request->email : '';
        $user->save();
        return responseJson(200, "success");
    }

    public function changeMobileNum(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'mobile' => 'required|unique:users,mobile|max:60'
        ]);
        if ($validator->fails()) {
            return responseJson(400, "Bad Request", $validator->errors()->first());
        }

        $user = auth()->user();
        try {

            DB::beginTransaction();
            $user->update([
                'mobile' => $request->mobile,
                'mobile_verified_at' =>  null,
                'code' => 1111,
                // 'code' => rand(1000, 9999),
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return responseJson(500, "Internal Server Error");
        }
        return responseJson(200, "success");
    }

    public function deleteAccount()
    {
        $user = auth()->guard('api')->user();
        $user->deleted_at = date("Y-m-d h:m:s");
        $user->save();
        return responseJson(200, "success");
    }



    ///////////////

    public function sendResetCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email|max:255',
        ]);
        if ($validator->fails()) {
            return responseJson(400, "Bad Request", $validator->errors()->first());
        }

        $user = $this->user->where('email', $request->email)->first();
        if (!$user) {
            return responseJson(404, "User not found");
        }

        try {
            $user->update(['code' => 1111]); // or rand(1000, 9999)
        } catch (\Exception $e) {
            return responseJson(500, "Internal Server Error");
        }

        return responseJson(200, "Reset code sent successfully.");
    }

    public function verifyResetCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email|max:255',
            'code' => 'required|exists:users,code|max:4',
        ]);
        if ($validator->fails()) {
            return responseJson(400, "Bad Request", $validator->errors()->first());
        }

        $user = $this->user->where('email', $request->email)->first();
        if (!$user || !is_null($user->deleted_at) || $user->code != $request->code) {
            return responseJson(401, "There Is Something Wrong, Please Contact Technical Support");
        }

        return responseJson(200, "Code verified successfully.");
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|exists:users,mobile|max:60',
            'code' => 'required|exists:users,code|max:4',
            'password' => 'required|confirmed|max:30',
        ]);
        if ($validator->fails()) {
            return responseJson(400, "Bad Request", $validator->errors()->first());
        }

        $user = $this->user->where('mobile', $request->mobile)->first();
        if (!$user || !is_null($user->deleted_at) || $user->code != $request->code) {
            return responseJson(401, "This Account Not Activated, Please Contact Technical Support");
        }

        try {
            $user->update([
                'password' => bcrypt($request->password),
                'code' => null,
            ]);
        } catch (\Exception $e) {
            return responseJson(500, "Internal Server Error");
        }
        return responseJson(200, "Password reset successfully.");
    }

    public function changePassword(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|max:30|confirmed',
        ]);

        if ($validator->fails()) {
            return responseJson(400, "Bad Request", $validator->errors()->first());
        }

        $user = auth()->user();

        if (!$user) {
            return responseJson(401, "Unauthorized: User not logged in.");
        }

        if (!Hash::check($request->old_password, $user->password)) {
            return responseJson(400, "Old password is incorrect.");
        }

        try {
            $user->update([
                'password' => bcrypt($request->new_password),
            ]);
        } catch (\Exception $e) {
            return responseJson(500, "Internal Server Error", $e->getMessage());
        }

        return responseJson(200, "Password changed successfully.");
    }

    public function updateEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255|unique:users,email,' . auth()->user()->id,
        ]);
        if ($validator->fails()) {
            return responseJson(400, "Bad Request", $validator->errors()->first());
        }

        $user = User::find(auth()->user()->id);
        try {
            $code = rand(1000, 9999);
            $user->update([
                'code' => $code,
            ]);
            FacadesNotification::route('mail', $request->email)->notify(new UpdateEmail($code));
            return responseJson(200, "check your mail");
        } catch (\Exception $e) {
            return responseJson(500, "Internal Server Error", $e->getMessage());
        }
        return responseJson(200, "success");
    }
    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'code' => 'required|max:4',
        ]);
        if ($validator->fails()) {
            return responseJson(400, "Bad Request", $validator->errors()->first());
        }

        $user = $this->user->where('id', auth()->user()->id)
            ->where('code', $request->code)
            ->first();
        if ($user->code != $request->code) {
            return responseJson(401, "Invaild OTP");
        }

        try {
            $user->update([
                'code' => null,
                'email' => $request->email,
                'email_verified_at' => now(),
            ]);
        } catch (\Exception $e) {
            return responseJson(500, "Internal Server Error");
        }
        return responseJson(200, "Email updated successfully.");
    }
}
