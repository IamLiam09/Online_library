<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\Utility;
use App\Models\Client;
class PaymentWallPaymentController extends Controller
{
    public $secret_key;
    public $public_key;
    public $is_enabled;

    public function paymentwall(Request $request)
    {
        $data = $request->all();
        $admin_payments_details = Utility::getAdminPaymentSetting();
        
        return view('plans.paymentwall',compact('data','admin_payments_details'));
    }

    // public function index(Request $request){
    //     $data = $request->all();
    //     $admin_payments_details = Utility::getAdminPaymentSetting();
    //     return view('plans.paymentwall',compact('data','admin_payments_details'));
    // }

    public function paymentConfig($user)
    {
        if(Auth::check()){
            $user = Auth::user();
        }
        if($user->type == 'owner')
        {
            $payment_setting = Utility::getAdminPaymentSetting();
        }
        else
        {
            $payment_setting = Utility::getCompanyPaymentSetting();
        }

        $this->secret_key = isset($payment_setting['paymentwall_private_key ']) ? $payment_setting['paymentwall_private_key  '] : '';
        $this->public_key = isset($payment_setting['paymentwall_public_key']) ? $payment_setting['paymentwall_public_key'] : '';
        $this->is_enabled = isset($payment_setting['is_paymentwall_enabled']) ? $payment_setting['is_paymentwall_enabled'] : 'off';

        return $this;
    }


    public function paymenterror(Request $request,$flag)
    {
        if($flag == 1){
            return redirect()->route("plans.index")->with('error', __('Transaction has been Successfull! '));
        }else{  
                              
            return redirect()->route("plans.index")->with('error', __('Transaction has been failed!'));            
        }       
    }

    public function PayWithPaymentwall(Request $request,$plan_id)
    {
        $planID    = \Illuminate\Support\Facades\Crypt::decrypt($plan_id);

        $plan      = Plan::find($planID);
        $authuser  = Auth::user();
        $coupon_id = '';

        if($plan)
        {
            $price = $plan->price;        
            if($price <= 0)
            {
                $authuser->plan = $plan->id;
                $authuser->save();
                $assignPlan = $authuser->assignPlan($plan->id); 
                if($assignPlan['is_success'] == true && !empty($plan))
                {
                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                    
                    Order::create(
                        [
                            'order_id' => $orderID,
                            'name' => null,
                            'email' => null,
                            'card_number' => null,
                            'card_exp_month' => null,
                            'card_exp_year' => null,
                            'plan_name' => $plan->name,
                            'plan_id' => $plan->id,
                            'price' => $price == null ? 0 : $price,
                            'price_currency' => !empty(env('CURRENCY')) ? env('CURRENCY') : 'usd',
                            'txn_id' => '',
                            'payment_type' => __('Flutterwave'),
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => $authuser->id,
                        ]
                    );
                    $res['msg']  = __("Plan successfully upgraded.");
                    $res['flag'] = 2;
                   
                    return $res;                 
                }
            } else {
                $orderID = time();
                \Paymentwall_Config::getInstance()->set(array(
                    'private_key' => 'sdrsefrszdef'
                ));
                $parameters = $request->all();
                $chargeInfo = array(
                    'email' => $parameters['email'],
                    'history[registration_date]' => '1489655092',
                    'amount' => $price,
                    'currency' => !empty($this->currancy) ? $this->currancy : 'USD',
                    'token' => $parameters['brick_token'],
                    'fingerprint' => $parameters['brick_fingerprint'],
                    'description' => 'Order #123'
                );
                $charge = new \Paymentwall_Charge();
                $charge->create($chargeInfo);
                $responseData = json_decode($charge->getRawResponseData(),true);
                $response = $charge->getPublicData();
                
                if ($charge->isSuccessful() AND empty($responseData['secure'])) {
                    if ($charge->isCaptured()) {
                        if($request->has('coupon') && $request->coupon != '')
                        {
                            $coupons = Coupon::find($request->coupon);
                            if(!empty($coupons))
                            {
                                $userCoupon            = new UserCoupon();
                                $userCoupon->user   = $authuser->id;
                                $userCoupon->coupon = $coupons->id;
                                $userCoupon->order  = $orderID;
                                $userCoupon->save();
                                $usedCoupun = $coupons->used_coupon();
                                if($coupons->limit <= $usedCoupun)
                                {
                                    $coupons->is_active = 0;
                                    $coupons->save();
                                }
                            }
                        }
                        $orderID=time();
                        $order                 = new Order();
                        $order->order_id       = $orderID;
                        $order->name           = $authuser->name;
                        $order->card_number    = '';
                        $order->card_exp_month = '';
                        $order->card_exp_year  = '';
                        $order->plan_name      = $plan->name;
                        $order->plan_id        = $plan->id;
                        $order->price          = isset($paydata['amount']) ? $paydata['amount'] : $price;
                        $order->price_currency = $this->currancy;
                        $order->txn_id         = isset($paydata['txid']) ? $paydata['txid'] : 0;
                        $order->payment_type   = __('PaymentWall');
                        $order->payment_status = 'success';
                        $order->receipt        = '';
                        $order->user_id        = $authuser->id;
                        $order->save();
                        $assignPlan = $authuser->assignPlan($plan->id);
                        if($assignPlan['is_success'])
                        {
                            $res['msg'] = __("Plan successfully upgraded.");
                                $res['flag'] = 1;
                                return $res;
                        }
                    } elseif ($charge->isUnderReview()) {
                        // decide on risk charge
                    }
                } elseif (!empty($responseData['secure'])) {
                    $response = json_encode(array('secure' => $responseData['secure']));
                } else {
                    $errors = json_decode($response, true);
                                $res['flag'] = 2;
                                return $res;
                }
                echo $response;                
            }                  
        }

    }

    public function planPayWithPaymentwall(Request $request,$plan_id)
    {
        // dd($request->all());
        // $res['msg'] = __("Plan successfully upgraded.");
        // return $res;
        $planID    = \Illuminate\Support\Facades\Crypt::decrypt($plan_id);
        $plan    = Plan::find($planID);
        $user    = \Auth::user();
        $coupon_id = '';
        $result  = array();
        $setting = Utility::settings();
        // dd($planID,$plan,$result,$setting );

        if(\Auth::user()->type == 'company')
        {
            $payment_setting = Utility::getAdminPaymentSetting();
        }
        else
        {
            $payment_setting = Utility::getCompanyPaymentSetting();
        }
        if($plan)
        {
            try
            {
                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                \Paymentwall_Config::getInstance()->set(array(
                    'private_key' => '43a32d4d8c9253e7cd25b79d7f727f94'
                ));
                $parameters = $_POST;
                $chargeInfo = array(
                    'email' => $parameters['email'],
                    'history[registration_date]' => '1489655092',
                    'amount' => $plan->price,
                    // 'currency' => 'USD',
                    'currency' => $setting['site_currency'],
                    'token' => $parameters['brick_token'],
                    'fingerprint' => $parameters['brick_fingerprint'],
                    'description' => 'Order #123'
                );
                $charge = new \Paymentwall_Charge();
                $charge->create($chargeInfo);
                $setting  = Utility::settings();

                
                if(isset($result['status']) && $result['status'] == true)
                {
                    $status = $result['data']['status'];
                    if($request->has('coupon_id') && $request->coupon_id != '')
                    {
                        $coupons = Coupon::find($request->coupon_id);
                        if(!empty($coupons))
                        {
                            $userCoupon         = new UserCoupon();
                            $userCoupon->user   = $user->id;
                            $userCoupon->coupon = $coupons->id;
                            $userCoupon->order  = $orderID;
                            $userCoupon->save();
                            $usedCoupun = $coupons->used_coupon();
                            if($coupons->limit <= $usedCoupun)
                            {
                                $coupons->is_active = 0;
                                $coupons->save();
                            }
                        }
                    }
                    $order                 = new Order();
                    $order->order_id       = $orderID;
                    $order->name           = $user->name;
                    $order->card_number    = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year  = '';
                    $order->plan_name      = $plan->name;
                    $order->plan_id        = $plan->id;
                    $order->price          = $result['data']['amount'] / 100;
                    // $order->price_currency = env('CURRENCY');
                    $order->price_currency  = $setting['site_currency'];
                    $order->txn_id         = $plan_id;
                    $order->payment_type   = __('Paystack');
                    $order->payment_status = $result['data']['status'];
                    $order->receipt        = '';
                    $order->user_id        = $user->id;
                    $order->save();
                    $assignPlan = $user->assignPlan($plan->id);
                    if($assignPlan['is_success'])
                    {
                        return redirect()->route('plans.index')->with('success', __('Plan activated Successfully.'));
                    }
                    else
                    {
                        return redirect()->route('plans.index')->with('error', $assignPlan['error']);
                    }
                }
                else
                {
                    return redirect()->back()->with('error', __('Transaction Unsuccesfull'));
                }
            }
            catch(\Exception $e)
            {
                return redirect()->route('plans.index')->with('error', __('Transaction has been failed.'));
            }
        }
        else
        {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }
}