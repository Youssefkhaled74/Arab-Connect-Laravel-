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
    public function register(Request $request)
    {
        $rules = [
            'email' => 'required|unique:users,email|max:50|email',
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

            $is_activate = 1;

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
                'all_days' => $request->all_days ?? 0,
            ]);

            // Add working days if provided
            if ($request->has('days') && is_array($request->days)) {
                foreach ($request->days as $day) {
                    $branch->days()->create([
                        'from' => $day['from'] ?? null,
                        'to' => $day['to'] ?? null,
                        'off' => $day['off'] ?? 0,
                        'day' => $day['day'] ?? null,
                    ]);
                }
            }

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
            'email' => 'required|exists:users,email|max:255|email',
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
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email|max:255',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return responseJson(400, "Bad Request", $validator->errors()->first());
        }
        $user = $this->user->where('email', $request->email)->first();
        if (!is_null($user->deleted_at)) {
            return responseJson(401, "This Account Not Activate , Please Contact Technical Support");
        }
        if (!$user->is_activate) {
            return responseJson(401, "This Account Not Verified , Please Contact Technical Support");
        }
        if (is_null($user->email_verified_at)) {
            return response()->json([
                'status' => false,
                'msg' => 'Please verify your email'
            ], 401);
        }
        try {
            if (!FacadesHash::check($request->password, $user->password)) {
                return response()->json(['error' => 'Invalid Credentials'], 401);
            }
            $user->token = JWTAuth::customClaims(['exp' => Carbon::now()->addYears(20)->timestamp])->fromUser($user);

            $imgUrl = $user->img
                ? env('APP_URL') . '/public/' . $user->img
                : null;
        } catch (\Exception $e) {
            return responseJson(500, "Internal Server Error");
        }
        return responseJson(200, "success", [
            'id' => $user->id,
            'name' => $user->name,
            'mobile' => $user->mobile,
            'email' => $user->email,
            'user_type' => $user->user_type,
            'token' => $user->token,
            'img' => $imgUrl,
        ]);
    }
    public function me()
    {
        $user = auth()->user();
        $imgUrl = $user->img
            ? env('APP_URL') . '/public/' . $user->img
            : null;

        return responseJson(200, "success", [
            'id' => $user->id,
            'name' => $user->name,
            'mobile' => $user->mobile,
            'country_code' => $user->country_code,
            'email' => $user->email,
            'user_type' => $user->user_type,
            'country_id' => (int) $user->country_id,
            'img' => $imgUrl,
        ]);
    }
    public function userBranches()
    {
        $branches['branches'] = $this->branchService->userBranches();
        return responseJson(200, "success", $branches);
    }
    public function getFavorites(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);

            $branches = $this->branchService->getFavorites($perPage, $page);

            return responseJson(200, "success", [
                'branches' => $branches->items(),
                'page' => $branches->currentPage(),
                'per_page' => $branches->perPage(),
                'last_page' => $branches->lastPage(),
                'total' => $branches->total(),
            ]);
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
        $user->email_verified_at = now();
        try {
            $user->save();
        } catch (\Exception $e) {
            return responseJson(500, "Internal Server Error", $e->getMessage());
        }

        return responseJson(200, "Code verified successfully.");
    }
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email|max:255',
            'code' => 'required|exists:users,code|max:4',
            'password' => 'required|confirmed|max:30',
        ]);
        if ($validator->fails()) {
            return responseJson(400, "Bad Request", $validator->errors()->first());
        }

        $user = $this->user->where('email', $request->email)->first();
        if (!$user || !is_null($user->deleted_at) || $user->code != $request->code) {
            return responseJson(401, "This Account Not Activated, Please Contact Technical Support");
        }

        try {
            $user->update([
                'password' => bcrypt($request->password),
                'code' => null,
                'email_verified_at' => now(),
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
    public function uploadProfilePhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'img' => 'required|file|image|max:5120',
        ]);
        if ($validator->fails()) {
            return responseJson(400, "Bad Request", $validator->errors()->first());
        }

        $user = auth()->user();
        try {
            $imgPath = uploadIamge($request->file('img'), 'users');
            $user->img = $imgPath;
            $user->save();
            $imgUrl = $user->img ? asset('uploads/' . $user->img) : null;
            return responseJson(200, "Profile photo updated successfully.", ['img' => $imgUrl]);
        } catch (\Exception $e) {
            return responseJson(500, "Internal Server Error", $e->getMessage());
        }
    }
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:60|unique:users,mobile,' . $user->id,
            'country_code' => 'nullable|string|max:10',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'img' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'country_id' => 'nullable|integer|exists:countries,id',
        ]);
        if ($validator->fails()) {
            return responseJson(400, "Bad Request", $validator->errors()->first());
        }

        // Update profile photo
        if ($request->hasFile('img')) {
            $imgPath = uploadIamge($request->file('img'), 'users');
            $user->img = $imgPath;
        }

        // Update name, mobile, country_code
        if ($request->filled('name')) $user->name = $request->name;
        if ($request->filled('mobile')) $user->mobile = $request->mobile;
        if ($request->filled('country_code')) $user->country_code = $request->country_code;
        if ($request->filled('country_id')) $user->country_id = $request->country_id;

        // Handle email change
        $emailChanged = false;
        if ($request->filled('email') && $request->email !== $user->email) {
            $user->new_email = $request->email;
            $user->code = 1111; // or rand(1000,9999)
            $emailChanged = true;
            // Optionally: send email with code here
        }

        $user->save();

        $imgUrl = $user->img
            ? env('APP_URL') . '/public/' . $user->img
            : null;

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'mobile' => $user->mobile,
            'country_code' => $user->country_code,
            'email' => $user->email,
            'user_type' => $user->user_type,
            'country_id' => (int) $user->country_id,
            'img' => $imgUrl,
        ];

        if ($emailChanged) {
            return response()->json([
                'status' => 200,
                'msg' => 'Please verify your new email to complete the update.',
                'user' => $userData
            ], 200);
        }

        return responseJson(200, "Profile updated successfully.", [
            'user' => $userData
        ]);
    }
    public function verifyNewEmail(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'code' => 'required|exists:users,code|max:4',
        ]);
        if ($validator->fails()) {
            return responseJson(400, "Bad Request", $validator->errors()->first());
        }

        if ($user->code != $request->code || !$user->new_email) {
            return responseJson(401, "Invalid code or no pending email change.");
        }

        try {
            $user->email = $user->new_email;
            $user->email_verified_at = now();
            $user->new_email = null;
            $user->code = null;
            $user->save();
        } catch (\Exception $e) {
            return responseJson(500, "Internal Server Error");
        }

        return responseJson(200, "Email updated and verified successfully.");
    }
}
