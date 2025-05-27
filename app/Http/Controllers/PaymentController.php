<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Branch;
use App\Models\Package;
use Illuminate\Http\Request;
use App\Models\PackageHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PackageHistoryResource;

class PaymentController extends Controller
{
    
    public $publicKey;
    public $secretKey;
    public $apiKey;
    public $iframeURL;
    public $integrationID;
    public $firebaseService;
    public $walletService;
    public $currency;
    
    public function __construct()
    {
        // $this->publicKey = env('PAYMOB_PUBLIC_KEY_LIVE');
        // $this->secretKey = env('PAYMOB_SECRET_KEY_LIVE');
        // $this->integrationID = env('PAYMOB_INTEGRATION_ID_LIVE');
        $this->publicKey = env('PAYMOB_PUBLIC_KEY_TEST');
        $this->secretKey = env('PAYMOB_SECRET_KEY_TEST');
        $this->integrationID = env('PAYMOB_INTEGRATION_ID_TEST');
        $this->apiKey = env('PAYMOB_API_KEY');
        $this->iframeURL = env('PAYMOB_IFRAME_ID');
        $this->currency = env('PAYMOB_CURRENCY');
    }
    public function generatePaymentUrl(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_id' => 'required|exists:packages,id',
            'branch_id' => 'required|exists:branches,id',
        ]);
        if ($validator->fails()) {
            return responseJson(400, "Bad Request", $validator->errors()->first());
        }
        $package = Package::find($request->package_id);
        $total = ($package->price - $package->discount) * 100;
        $user = auth()->user();
        $authResponse = Http::post('https://accept.paymob.com/api/auth/tokens', [
            'api_key' => $this->apiKey
        ]);
        $authToken = $authResponse['token'];
        $merchant_order_id = $request->branch_id . '_'. $request->package_id. '_' . $user->id . '_'. time();
        $orderResponse = Http::post('https://accept.paymob.com/api/ecommerce/orders', [
            'auth_token' => $authToken,
            'delivery_needed' => false,
            'amount_cents' => $total,
            'currency' => 'EGP',
            'merchant_order_id' => $merchant_order_id,
            'items' => [],
        ]);
        $orderId = $orderResponse['id'];
        $billingData = [
            "first_name" => $user->name ?? 'egypin',
            "last_name" => $user->name ?? 'egypin',
            "email" => $user->email ?? 'egypin@egypin.com',
            "phone_number" => $user->mobile ?? '01122222222',
            "apartment" => "NA",
            "floor" => "NA",
            "street" => "NA",
            "building" => "NA",
            "city" => "NA",
            "state" => "NA",
            "country" => "EG",
            "postal_code" => "NA",
        ];
        $paymentKeyResponse = Http::post('https://accept.paymob.com/api/acceptance/payment_keys', [
            'auth_token' => $authToken,
            'amount_cents' => $total,
            'expiration' => 3600,
            'order_id' => $orderId,
            'billing_data' => $billingData,
            'currency' => 'EGP',
            'integration_id' => (int)$this->integrationID,
        ]);
    
        $paymentToken = $paymentKeyResponse['token'];
        $url =  "https://accept.paymob.com/api/acceptance/iframes/{$this->iframeURL}?payment_token={$paymentToken}";
        return responseJson(200, 'Payment URL generated successfully',$url);
    }
    
    public function state(Request $request){
        try{
            DB::beginTransaction(); 
            $branch_id = explode('_',$request->merchant_order_id)[0];
            $package_id = explode('_',$request->merchant_order_id)[1];
            $user_id = explode('_',$request->merchant_order_id)[2];
            if($request->success == 'false'){
                $branch = Branch::find($branch_id);
                $package = Package::find($package_id);

                $new_expire_at = $branch->expire_at && $branch->expire_at > now()
                ? Carbon::parse($branch->expire_at)->addDays($package->duration)
                : now()->addDays($package->duration);
            
                $branch->update([
                    'expire_at' => $new_expire_at,
                    'three_month_email_sent_at' => $new_expire_at->copy()->subDays(90),
                    'one_month_email_sent_at' => $new_expire_at->copy()->subDays(30),
                ]);
                PackageHistory::create([
                    'user_id' => $user_id,
                    'package_id' => $package_id,
                    'branch_id' => $branch_id,
                    'price' => $request->amount_cents / 100,
                ]);
                DB::commit();
                return  responseJson(200, 'success','payment success');
            }elseif($request->success == 'true'){
                return  responseJson(400, 'bad request','invalid amount');
            }
        }catch(\Exception $e){
            DB::rollBack();
            return  responseJson(400, 'bad request',$e->getMessage());
        }
    }

    public function history($limt = 10, $page = 0)
    {
        $user = auth()->user();
        $history = PackageHistory::where('user_id', $user->id)
            ->select('id', 'package_id', 'branch_id', 'price', 'created_at')
            ->with(['package:id,title,description', 'branch:id,name'])
            ->paginate($limt, ['*'], 'page', $page);
    
        return responseJson(200, 'success', PackageHistoryResource::collection($history));
    }
}
