<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Course;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Order;
use App\Models\Plan;
use App\Models\PlanOrder;
use App\Models\Product;
use App\Models\ProductVariantOption;
use App\Models\PurchasedCourse;
use App\Models\Shipping;
use App\Models\Store;
use App\Models\UserCoupon;
use App\Models\Student;
use App\Models\UserDetail;
use App\Models\UserStore;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;

use Srmklive\PayPal\Services\PayPal as PayPalClient;
// use PayPal\Api\PaymentExecution;
// use PayPal\Api\RedirectUrls;
// use PayPal\Api\Transaction;
// use PayPal\Auth\OAuthTokenCredential;
// use PayPal\Rest\ApiContext;

class PaypalController extends Controller
{
    // private $_api_context;
    protected $invoiceData;

    // public function setApiContext($slug = '')
    // {
    //     if(Auth::check() && Auth::guard('students')->check() == false)
    //     {
    //         $admin_payment_setting           = Utility::getAdminPaymentSetting();
    //         $paypal_conf['settings']['mode'] = $admin_payment_setting['paypal_mode'];
    //         $paypal_conf['client_id']        = $admin_payment_setting['paypal_client_id'];
    //         $paypal_conf['secret_key']       = $admin_payment_setting['paypal_secret_key'];
    //     }
    //     else
    //     {     
    //         $store                           = Store::where('slug', $slug)->first();
    // $store_payment_setting           = Utility::getPaymentSetting($store->id);
    //         $paypal_conf['settings']['mode'] = $store_payment_setting['paypal_mode'];
    //         $paypal_conf['client_id']        = $store_payment_setting['paypal_client_id'];
    //         $paypal_conf['secret_key']       = $store_payment_setting['paypal_secret_key'];
    //     }

    //     $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret_key']));
    //     $this->_api_context->setConfig($paypal_conf['settings']);       
    // }

    public function paymentConfig($slug = '')
    {
        if (\Auth::check() && Auth::guard('students')->check() == false) {
            $payment_setting = Utility::getAdminPaymentSetting();
        } else {
            // $store           = Store::where('slug', $slug)->first();
            // $payment_setting = Utility::getPaymentSetting($store->id);
            $payment_setting = Utility::getPaymentSetting(\Auth::user()->creator_id());
        }
        // dd($payment_setting);
        config(
            [
                'paypal.sandbox.client_id' => isset($payment_setting['paypal_client_id']) ? $payment_setting['paypal_client_id'] : '',
                'paypal.sandbox.client_secret' => isset($payment_setting['paypal_secret_key']) ? $payment_setting['paypal_secret_key'] : '',
                'paypal.mode' => isset($payment_setting['paypal_mode']) ? $payment_setting['paypal_mode'] : '',
            ]
        );
    }

    public function PayWithPaypal(Request $request, $slug)
    {
        // dd('vbnvfdrt');
        $cart     = session()->get($slug);
        $products = $cart['products'];

        $store = Store::where('slug', $slug)->first();

        $admin_payments_details = Utility::getPaymentSetting($store->id);

        config(
            [
                'paypal.sandbox.client_id' => isset($admin_payments_details['paypal_client_id']) ? $admin_payments_details['paypal_client_id'] : '',
                'paypal.sandbox.client_secret' => isset($admin_payments_details['paypal_secret_key']) ? $admin_payments_details['paypal_secret_key'] : '',
                'paypal.mode' => isset($admin_payments_details['paypal_mode']) ? $admin_payments_details['paypal_mode'] : '',
            ]
        );

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        Session::put('paypal_payment_id', $paypalToken['access_token']);
        $objUser = \Auth::user();

        $total_price    = 0;
        $sub_tax        = 0;
        $sub_totalprice = 0;
        $total_tax      = 0;
        $product_name   = [];
        $product_id     = [];

        foreach ($products as $key => $product) {
            $product_name[] = $product['product_name'];
            $product_id[]   = $product['id'];
            $sub_totalprice += $product['price'];
            $total_price    += $product['price'];
        }

        if ($products) {
            try {
                $coupon_id = null;
                if (isset($cart['coupon']) && isset($cart['coupon'])) {
                    if ($cart['coupon']['coupon']['enable_flat'] == 'off') {
                        $discount_value = ($sub_totalprice / 100) * $cart['coupon']['coupon']['discount'];
                        $total_price    = $sub_totalprice - $discount_value;
                    } else {
                        $discount_value = $cart['coupon']['coupon']['flat_discount'];
                        $total_price    = $sub_totalprice - $discount_value;
                    }
                }

                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    "application_context" => [
                        "return_url" => route('get.payment.status', $store->slug),
                        "cancel_url" => route('get.payment.status', $store->slug),
                    ],
                    "purchase_units" => [
                        0 => [
                            "amount" => [
                                "currency_code" => Utility::getValByName('site_currency'),
                                "value" => $total_price,
                            ],
                        ],
                    ],
                ]);
                if (isset($response['id']) && $response['id'] != null) {
                    // redirect to approve href
                    foreach ($response['links'] as $links) {
                        if ($links['rel'] == 'approve') {
                            return redirect()->away($links['href']);
                        }
                    }
                    return redirect()
                        ->route('plans.index')
                        ->with('error', 'Something went wrong.');
                } else {
                    return redirect()
                        ->route('plans.index')
                        ->with('error', $response['message'] ?? 'Something went wrong.');
                }
                // dd($paypalToken->id,$response,$provider);
                Session::put('paypal_payment_id', $paypalToken->id);
            } catch (\Exception $e) {
                // dd($e);
                return redirect()->back()->with('error', __('Unknown error occurred'));
            }
        } else {
            return redirect()->back()->with('error', __('is deleted.'));
        }
    }

    public function GetPaymentStatus(Request $request, $slug)
    {
        $store = Store::where('slug', $slug)->first();
        $admin_payments_details = Utility::getPaymentSetting($store->id);

        config(
            [
                'paypal.sandbox.client_id' => isset($admin_payments_details['paypal_client_id']) ? $admin_payments_details['paypal_client_id'] : '',
                'paypal.sandbox.client_secret' => isset($admin_payments_details['paypal_secret_key']) ? $admin_payments_details['paypal_secret_key'] : '',
                'paypal.mode' => isset($admin_payments_details['paypal_mode']) ? $admin_payments_details['paypal_mode'] : '',
            ]
        );

        $cart = session()->get($slug);
        if (isset($cart['coupon'])) {
            $coupon = $cart['coupon']['coupon'];
        }
        $products       = $cart['products'];
        $store          = Store::where('slug', $slug)->first();
        $sub_totalprice = 0;
        $product_name   = [];
        $product_id     = [];

        foreach ($products as $key => $product) {
            $course = Course::where('id', $product['product_id'])->where('store_id', $store->id)->first();
            $product_name[] = $product['product_name'];
            $product_id[]   = $product['id'];
            $sub_totalprice += $product['price'];
        }
        if (!empty($coupon)) {
            if ($coupon['enable_flat'] == 'off') {
                $discount_value = ($sub_totalprice / 100) * $coupon['discount'];
                $totalprice     = $sub_totalprice - $discount_value;
            } else {
                $discount_value = $coupon['flat_discount'];
                $totalprice     = $sub_totalprice - $discount_value;
            }
        }
        if ($product) {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request['token']);
            $payment_id = Session::get('paypal_payment_id');
            $order_id = strtoupper(str_replace('.', '', uniqid('', true)));
            // dd($response, $response['purchase_units'][0]['payments']['captures'][0]['amount']['value']);
            // $this->setApiContext($slug);
            // $payment_id = Session::get('paypal_payment_id');
            // Session::forget('paypal_payment_id');
            // if(empty($request->PayerID || empty($request->token)))
            // {
            //     return redirect()->route('store-payment.payment', $slug)->with('error', __('Payment failed'));
            // }
            // $payment   = Payment::get($payment_id, $this->_api_context);
            // $execution = new PaymentExecution();
            // $execution->setPayerId($request->PayerID);
            try {
                // $result = $payment->execute($execution, $this->_api_context)->toArray();
                $order       = new Order();
                $latestOrder = Order::orderBy('created_at', 'DESC')->first();
                if (!empty($latestOrder)) {
                    $order->order_nr = '#' . str_pad($latestOrder->id + 1, 4, "100", STR_PAD_LEFT);
                } else {
                    $order->order_nr = '#' . str_pad(1, 4, "100", STR_PAD_LEFT);
                }
                $orderID = $order->order_nr;

                // $status = ucwords(str_replace('_', ' ', $result['state']));
                // if($result['state'] == 'approved')
                // {
                $statuses = '';
                if (isset($response['status']) && $response['status'] == 'COMPLETED') 
                {
                    if ($response['status'] == 'COMPLETED') {
                        $statuses = __('successful');
                    }

                    $student               = Auth::guard('students')->user();
                    $order                 = new Order();
                    $order->order_id       = $orderID;
                    $order->name           = $student->name;
                    $order->card_number    = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year  = '';
                    $order->student_id     = $student->id;
                    $order->course         = json_encode($products);
                    // $order->price          = $result['transactions'][0]['amount']['total'];
                    $order->price          = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
                    $order->coupon         = !empty($cart['coupon']['coupon']['id']) ? $cart['coupon']['coupon']['id'] : '';
                    $order->coupon_json    = json_encode(!empty($coupon) ? $coupon : '');
                    $order->discount_price = !empty($cart['coupon']['discount_price']) ? $cart['coupon']['discount_price'] : '';
                    $order->price_currency = $store->currency_code;
                    $order->txn_id         = $payment_id;
                    $order->payment_type   = __('PAYPAL');
                    // $order->payment_status = $result['state'];
                    $order->payment_status = $statuses;
                    $order->receipt        = '';
                    $order->store_id       = $store['id'];
                    $order->save();

                    $purchased_course = new PurchasedCourse();
                    foreach ($course as $course_id) {
                        $purchased_course->course_id  = $course_id;
                        $purchased_course->student_id = $student->id;
                        $purchased_course->order_id   = $order->id;
                        $purchased_course->save();

                        $student = Student::where('id', $purchased_course->student_id)->first();
                        $student->courses_id = $purchased_course->course_id;
                        $student->save();
                    }


                    // slack // 
                    $settings  = Utility::notifications();
                    if (isset($settings['order_notification']) && $settings['order_notification'] == 1) {
                        $msg = 'New Order ' . $orderID . ' is created by ' . $store['name'] . '.';
                        Utility::send_slack_msg($msg);
                    }

                    // telegram // 
                    $settings  = Utility::notifications();
                    if (isset($settings['telegram_order_notification']) && $settings['telegram_order_notification'] == 1) {
                        $msg = 'New Order ' . $orderID . ' is created by ' . $store['name'] . '.';
                        Utility::send_telegram_msg($msg);
                    }

                    session()->forget($slug);

                    return redirect()->route(
                        'store-complete.complete',
                        [
                            $store->slug,
                            Crypt::encrypt($order->id),
                        ]
                    )->with('success', __('Transaction has been') .' '. $statuses);
                } else {
                    return redirect()->back()->with('error', __('Transaction has been') .' '.$statuses);
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', __('Transaction has been failed.'));
            }
        } else {
            return redirect()->back()->with('error', __(' is deleted.'));
        }
    }

    public function planPayWithPaypal(Request $request)
    {
        $this->paymentConfig();

        $store = Store::where('id', Auth::user()->current_store)->first();
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan   = Plan::find($planID);


        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $get_amount = $plan->price;

        if ($plan) {
            try {
                $coupon_id = null;
                $price     = $plan->price;
                if (!empty($request->coupon)) {
                    $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                    if (!empty($coupons)) {
                        $usedCoupun     = $coupons->used_coupon();
                        $discount_value = ($plan->price / 100) * $coupons->discount;
                        $price          = $plan->price - $discount_value;
                        if ($coupons->limit == $usedCoupun) {
                            return redirect()->back()->with('error', __('This coupon code has expired.'));
                        }
                        $coupon_id = $coupons->id;
                    } else {
                        return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                    }
                }

                $paypalToken = $provider->getAccessToken();
                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    "application_context" => [
                        "return_url" => route('plan.get.payment.status', [$plan->id, $get_amount]),
                        "cancel_url" =>  route('plan.get.payment.status', [$plan->id, $get_amount]),
                    ],
                    "purchase_units" => [
                        0 => [
                            "amount" => [
                                "currency_code" => Utility::getValByName('site_currency'),
                                "value" => $get_amount
                            ]
                        ]
                    ]
                ]);
                if (isset($response['id']) && $response['id'] != null) {
                    // redirect to approve href
                    foreach ($response['links'] as $links) {
                        if ($links['rel'] == 'approve') {
                            return redirect()->away($links['href']);
                        }
                    }
                    return redirect()
                        ->route('payment', \Illuminate\Support\Facades\Crypt::encrypt($plan->id))
                        ->with('error', 'Something went wrong. OR Unknown error occurred');
                } else {
                    return redirect()
                        ->route('payment', \Illuminate\Support\Facades\Crypt::encrypt($plan->id))
                        ->with('error', $response['message'] ?? 'Something went wrong.');
                }

                // $this->setApiContext($store->slug);
                // $name  = $plan->name;
                // $payer = new Payer();
                // $payer->setPaymentMethod('paypal');
                // $item_1 = new Item();
                // $item_1->setName($name)->setCurrency(env('CURRENCY'))->setQuantity(1)->setPrice($price);
                // $item_list = new ItemList();
                // $item_list->setItems([$item_1]);
                // $amount = new Amount();
                // $amount->setCurrency(env('CURRENCY'))->setTotal($price);
                // $transaction = new Transaction();
                // $transaction->setAmount($amount)->setItemList($item_list)->setDescription($name);
                // $redirect_urls = new RedirectUrls();
                // $redirect_urls->setReturnUrl(
                //     route(
                //         'get.store.payment.status', [
                //                                       $plan->id,
                //                                       'coupon_id' => $coupon_id,
                //                                   ]
                //     )
                // )->setCancelUrl(
                //     route(
                //         'get.store.payment.status', [
                //                                       $plan->id,
                //                                       'coupon_id' => $coupon_id,
                //                                   ]
                //     )
                // );
                // $payment = new Payment();
                // $payment->setIntent('Sale')->setPayer($payer)->setRedirectUrls($redirect_urls)->setTransactions([$transaction]);
                // try
                // {
                //     $payment->create($this->_api_context);
                // }
                // catch(\PayPal\Exception\PayPalConnectionException $ex) //PPConnectionException
                // {
                //     if(config('app.debug'))
                //     {
                //         return redirect()->route('stripe', \Illuminate\Support\Facades\Crypt::encrypt($plan->id))->with('error', __('Connection timeout'));
                //     }
                //     else
                //     {
                //         return redirect()->route('stripe', \Illuminate\Support\Facades\Crypt::encrypt($plan->id))->with('error', __('Some error occur, sorry for inconvenient'));
                //     }
                // }
                // foreach($payment->getLinks() as $link)
                // {
                //     if($link->getRel() == 'approval_url')
                //     {
                //         $redirect_url = $link->getHref();
                //         break;
                //     }
                // }
                // Session::put('paypal_payment_id', $payment->getId());
                // if(isset($redirect_url))
                // {
                //     return Redirect::away($redirect_url);
                // }

                // return redirect()->route('payment', \Illuminate\Support\Facades\Crypt::encrypt($plan->id))->with('error', __('Unknown error occurred'));
            } catch (\Exception $e) {
                return redirect()->route('plans.index')->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }

    public function planGetPaymentStatus(Request $request, $plan_id, $amount)
    {
        $this->paymentconfig();
        $user = Auth::user();
        $plan = Plan::find($plan_id);

        if ($plan) {
            // $this->paymentConfig();
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request['token']);
            // dd($response);
            $payment_id = Session::get('paypal_payment_id');
            $order_id = strtoupper(str_replace('.', '', uniqid('', true)));

            // $status  = ucwords(str_replace('_', ' ', $result['state']));
            if ($request->has('coupon_id') && $request->coupon_id != '') {
                $coupons = Coupon::find($request->coupon_id);
                if (!empty($coupons)) {
                    $userCoupon = new UserCoupon();
                    $userCoupon->user = $user->id;
                    $userCoupon->coupon = $coupons->id;
                    $userCoupon->order = $order_id;
                    $userCoupon->save();
                    $usedCoupun = $coupons->used_coupon();
                    if ($coupons->limit <= $usedCoupun) {
                        $coupons->is_active = 0;
                        $coupons->save();
                    }
                }
            }
            if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                if ($response['status'] == 'COMPLETED') {
                    $statuses = 'success';
                }
                // dd($response);
                $order = new Order();
                $order->order_id = $order_id;
                $order->name = $user->name;
                $order->card_number = '';
                $order->card_exp_month = '';
                $order->card_exp_year = '';
                $order->plan_name = $plan->name;
                $order->plan_id = $plan->id;
                $order->price = $amount;
                $order->price_currency = env('CURRENCY');
                $order->txn_id = $payment_id;
                $order->payment_type = __('PAYPAL');
                $order->payment_status = $statuses;
                $order->txn_id = '';
                $order->receipt = '';
                $order->user_id = $user->id;
                $order->save();
                $assignPlan = $user->assignPlan($plan->id);
                if ($assignPlan['is_success']) {
                    return redirect()->route('plans.index')->with('success', __('Plan activated Successfully.'));
                } else {
                    return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                }

                return redirect()
                    ->route('plans.index')
                    ->with('success', 'Transaction complete.');
            } else {
                return redirect()
                    ->route('plans.index')
                    ->with('error', $response['message'] ?? 'Something went wrong.');
            }
        } else {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }

    // public function storeGetPaymentStatus(Request $request, $plan_id)
    // {
    //     $user     = Auth::user();
    //     $store_id = Auth::user()->current_store;
    //     $store = Store::where('id',Auth::user()->current_store)->first();


    //     $plan = Plan::find($plan_id);

    //     if($plan)
    //     {
    //         $this->setApiContext($store->slug);
    //         $payment_id = Session::get('paypal_payment_id');
    //         Session::forget('paypal_payment_id');
    //         if(empty($request->PayerID || empty($request->token)))
    //         {
    //             return redirect()->route('payment', \Illuminate\Support\Facades\Crypt::encrypt($plan->id))->with('error', __('Payment failed'));
    //         }
    //         $payment   = Payment::get($payment_id, $this->_api_context);
    //         $execution = new PaymentExecution();
    //         $execution->setPayerId($request->PayerID);
    //         try
    //         {
    //             $result  = $payment->execute($execution, $this->_api_context)->toArray();
    //             $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
    //             $status  = ucwords(str_replace('_', ' ', $result['state']));
    //             if($request->has('coupon_id') && $request->coupon_id != '')
    //             {
    //                 $coupons = Coupon::find($request->coupon_id);
    //                 if(!empty($coupons))
    //                 {
    //                     $userCoupon         = new UserCoupon();
    //                     $userCoupon->user   = $user->id;
    //                     $userCoupon->coupon = $coupons->id;
    //                     $userCoupon->order  = $orderID;
    //                     $userCoupon->save();
    //                     $usedCoupun = $coupons->used_coupon();
    //                     if($coupons->limit <= $usedCoupun)
    //                     {
    //                         $coupons->is_active = 0;
    //                         $coupons->save();
    //                     }
    //                 }
    //             }

    //             if($result['state'] == 'approved')
    //             {

    //                 $planorder                 = new PlanOrder();
    //                 $planorder->order_id       = $orderID;
    //                 $planorder->name           = $user->name;
    //                 $planorder->card_number    = '';
    //                 $planorder->card_exp_month = '';
    //                 $planorder->card_exp_year  = '';
    //                 $planorder->plan_name      = $plan->name;
    //                 $planorder->plan_id        = $plan->id;
    //                 $planorder->price          = $result['transactions'][0]['amount']['total'];
    //                 $planorder->price_currency = env('CURRENCY');
    //                 $planorder->txn_id         = $payment_id;
    //                 $planorder->payment_type   = __('PAYPAL');
    //                 $planorder->payment_status = $result['state'];
    //                 $planorder->receipt        = '';
    //                 $planorder->user_id        = $user->id;
    //                 $planorder->store_id       = $store_id;
    //                 $planorder->save();

    //                 $assignPlan = $user->assignPlan($plan->id);

    //                 if($assignPlan['is_success'])
    //                 {


    //                     return redirect()->route('plans.index')->with('success', __('Plan activated Successfully.'));
    //                 }
    //                 else
    //                 {


    //                     return redirect()->route('plans.index')->with('error', $assignPlan['error']);
    //                 }
    //             }
    //             else
    //             {
    //                 return redirect()->route('plans.index')->with('error', __('Transaction has been') . $status);
    //             }
    //         }
    //         catch(\Exception $e)
    //         {
    //             return redirect()->route('plans.index')->with('error', __('Transaction has been failed.'));
    //         }
    //     }
    //     else
    //     {
    //         return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
    //     }
    // }
}
