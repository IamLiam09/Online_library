<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\PlanOrder;
use App\Models\Product;
use App\Models\ProductCoupon;
use App\Models\ProductVariantOption;
use App\Models\PurchasedCourse;
use App\Models\Shipping;
use App\Models\Store;
use App\Models\UserCoupon;
use App\Models\Utility;
use App\Models\Student;
use CoinGate\CoinGate;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use LivePixel\MercadoPago\MP;
use Mollie\Laravel\Facades\Mollie;
use PaytmWallet;
use Obydul\LaraSkrill\SkrillClient;
use Obydul\LaraSkrill\SkrillRequest;

// use PayPal\Api\Amount;
// use PayPal\Api\Item;
// use PayPal\Api\ItemList;
// use PayPal\Api\Payer;
// use PayPal\Api\Payment;
// use Srmklive\PayPal\Services\PayPal as PayPalClient;


class PaymentController extends Controller
{
    //paystackPayment
    public function paystackPayment($slug, $code, $order_id)
    {
        $store = Store::where('slug', $slug)->first();
        $cart  = session()->get($slug);
        if(\Auth::check())
        {
            $store_payment_setting = Utility::getPaymentSetting();
        }
        else
        {
            $store_payment_setting = Utility::getPaymentSetting($store->id);
        }

        if(!empty($cart))
        {
            $products = $cart['products'];
        }
        else
        {
            return redirect()->back()->with('error', __('Please add to product into cart'));
        }
        if(isset($cart['coupon']['data_id']))
        {
            $coupon = ProductCoupon::where('id', $cart['coupon']['data_id'])->first();
        }
        else
        {
            $coupon = '';
        }
        $product_name   = [];
        $product_id     = [];
        $totalprice     = 0;
        $sub_totalprice = 0;
        $discount_price = 0;
        foreach($products as $key => $product)
        {
            $product_name[] = $product['product_name'];
            $product_id[]   = $product['id'];
            $sub_totalprice += $product['price'];
            $totalprice     += $product['price'];
        }

        if(!empty($coupon))
        {
            if($coupon['enable_flat'] == 'off')
            {
                $discount_value = ($sub_totalprice / 100) * $coupon['discount'];
                $totalprice     = $sub_totalprice - $discount_value;
            }
            else
            {
                $discount_value = $coupon['flat_discount'];
                $totalprice     = $sub_totalprice - $discount_value;
            }
        }

        if($products)
        {
            $result = array();
            //The parameter after verify/ is the transaction reference to be verified
            $url = "https://api.paystack.co/transaction/verify/$code";
            $ch  = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt(
                $ch, CURLOPT_HTTPHEADER, [
                       'Authorization: Bearer ' . $store_payment_setting['paystack_secret_key'],
                   ]
            );
            $request = curl_exec($ch);
            curl_close($ch);
            if($request)
            {
                $result = json_decode($request, true);
            }

            if(array_key_exists('data', $result) && array_key_exists('status', $result['data']) && ($result['data']['status'] === 'success'))
            {
                $student               = Auth::guard('students')->user();
                $order                 = new Order();
                $order->order_id       = '#' . time();
                $order->name           = $student->name;
                $order->card_number    = '';
                $order->card_exp_month = '';
                $order->card_exp_year  = '';
                $order->student_id     = $student->id;
                $order->course         = json_encode($products);
                $order->price          = $totalprice;
                $order->coupon         = isset($cart['coupon']['data_id']) ? $cart['coupon']['data_id'] : '';
                $order->coupon_json    = json_encode($coupon);
                $order->discount_price = isset($cart['coupon']['discount_price']) ? $cart['coupon']['discount_price'] : '';
                $order->price_currency = $store->currency_code;
                $order->txn_id         = isset($result['data']['id']) ? $result['data']['id'] : '';
                $order->payment_type   = 'paystack';
                $order->payment_status = isset($result['data']['status']) ? $result['data']['status'] : 'succeeded';
                $order->receipt        = '';
                $order->store_id       = $store['id'];
                $order->save();


                $purchased_course = new PurchasedCourse();

                foreach($products as $course_id)
                {
                    $purchased_course->course_id  = $course_id['product_id'];
                    $purchased_course->student_id = $student->id;
                    $purchased_course->order_id   = $order->id;
                    $purchased_course->save();

                    $student=Student::where('id',$purchased_course->student_id)->first();
                    $student->courses_id=$purchased_course->course_id;
                    $student->save();
                }

                // slack // 
                $settings  = Utility::notifications();
                if(isset($settings['order_notification']) && $settings['order_notification'] ==1){
                    $msg = 'New Order '.$orderID.' is created by '.$store['name'].'.';
                    Utility::send_slack_msg($msg);    
                }

                // telegram // 
                $settings  = Utility::notifications();
                if(isset($settings['telegram_order_notification']) && $settings['telegram_order_notification'] ==1){
                    $msg = 'New Order '.$orderID.' is created by '.$store['name'].'.';
                    Utility::send_telegram_msg($msg);    
                }

                session()->forget($slug);

                return redirect()->route(
                    'store-complete.complete', [
                                                 $store->slug,
                                                 Crypt::encrypt($order->id),
                                             ]
                )->with('success', __('Transaction has been success'));

            }
            else
            {
                return redirect()->back()->with('error', __('Transaction Unsuccesfull'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Plan is deleted.'));
        }
    }

    //FlutterwavePayment
    public function flutterwavePayment($slug, $tran_id, $order_id)
    {
        $store = Store::where('slug', $slug)->first();
        $cart  = session()->get($slug);
        if(\Auth::check())
        {
            $store_payment_setting = Utility::getPaymentSetting();
        }
        else
        {
            $store_payment_setting = Utility::getPaymentSetting($store->id);
        }
        if(!empty($cart))
        {
            $products = $cart['products'];
        }
        else
        {
            return redirect()->back()->with('error', __('Please add to product into cart'));
        }

        if(isset($cart['coupon']['data_id']))
        {
            $coupon = ProductCoupon::where('id', $cart['coupon']['data_id'])->first();
        }
        else
        {
            $coupon = '';
        }
        $product_name   = [];
        $product_id     = [];
        $totalprice     = 0;
        $sub_totalprice = 0;

        foreach($products as $key => $product)
        {
            $product_name[] = $product['product_name'];
            $product_id[]   = $product['id'];
            $sub_totalprice += $product['price'];
            $totalprice     += $product['price'];
        }
        if(!empty($coupon))
        {
            if($coupon['enable_flat'] == 'off')
            {
                $discount_value = ($sub_totalprice / 100) * $coupon['discount'];
                $totalprice     = $sub_totalprice - $discount_value;
            }
            else
            {
                $discount_value = $coupon['flat_discount'];
                $totalprice     = $sub_totalprice - $discount_value;
            }
        }
        if($products)
        {
            $data = array(
                'txref' => $tran_id,
                'SECKEY' => $store_payment_setting['flutterwave_secret_key'],
                //secret key from pay button generated on rave dashboard
            );

            // make request to endpoint using unirest.
            $headers = array('Content-Type' => 'application/json');
            $body    = \Unirest\Request\Body::json($data);
            $url     = "https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify"; //please make sure to change this to production url when you go live

            // Make `POST` request and handle response with unirest
            $response = \Unirest\Request::post($url, $headers, $body);


            if($response->body->data->status === "successful" && $response->body->data->chargecode === "00")
            {
                $student               = Auth::guard('students')->user();
                $order                 = new Order();
                $order->order_id       = '#' . time();
                $order->name           = $student->name;
                $order->card_number    = '';
                $order->card_exp_month = '';
                $order->card_exp_year  = '';
                $order->student_id     = $student->id;
                $order->course         = json_encode($products);
                $order->price          = $totalprice;
                $order->coupon         = isset($cart['coupon']['data_id']) ? $cart['coupon']['data_id'] : '';
                $order->coupon_json    = json_encode($coupon);
                $order->discount_price = isset($cart['coupon']['discount_price']) ? $cart['coupon']['discount_price'] : '';
                $order->price_currency = $store->currency_code;
                $order->txn_id         = isset($tran_id) ? $tran_id : '';
                $order->payment_type   = 'flutterwave';
                $order->payment_status = 'success';
                $order->receipt        = '';
                $order->store_id       = $store['id'];
                $order->save();

                $purchased_course = new PurchasedCourse();
                foreach($products as $course_id)
                {
                    $purchased_course->course_id  = $course_id['product_id'];
                    $purchased_course->student_id = $student->id;
                    $purchased_course->order_id   = $order->id;
                    $purchased_course->save();

                    $student=Student::where('id',$purchased_course->student_id)->first();
                    $student->courses_id=$purchased_course->course_id;
                    $student->save();

                }

                // slack // 
                $settings  = Utility::notifications();
                if(isset($settings['order_notification']) && $settings['order_notification'] ==1){
                    $msg = 'New Order '.$orderID.' is created by '.$store['name'].'.';
                    Utility::send_slack_msg($msg);    
                }

                // telegram // 
                $settings  = Utility::notifications();
                if(isset($settings['telegram_order_notification']) && $settings['telegram_order_notification'] ==1){
                    $msg = 'New Order '.$orderID.' is created by '.$store['name'].'.';
                    Utility::send_telegram_msg($msg);    
                }

                session()->forget($slug);

                return redirect()->route(
                    'store-complete.complete', [
                                                 $store->slug,
                                                 Crypt::encrypt($order->id),
                                             ]
                )->with('success', __('Transaction has been success'));
            }
            else
            {
                return redirect()->back()->with('error', __('Transaction Unsuccesfull'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Plan is deleted.'));
        }

    }

    //RazerPayment
    public function razerpayPayment($slug, $pay_id, $order_id)
    {
        $store    = Store::where('slug', $slug)->first();
        $products = '';
        $cart     = session()->get($slug);
        if(\Auth::check())
        {
            $store_payment_setting = Utility::getPaymentSetting();
        }
        else
        {
            $store_payment_setting = Utility::getPaymentSetting($store->id);
        }
        if(!empty($cart))
        {
            $products = $cart['products'];
        }
        else
        {
            return redirect()->back()->with('error', __('Please add to product into cart'));
        }

        if(isset($cart['coupon']['data_id']))
        {
            $coupon = ProductCoupon::where('id', $cart['coupon']['data_id'])->first();
        }
        else
        {
            $coupon = '';
        }
        $product_name   = [];
        $product_id     = [];
        $totalprice     = 0;
        $sub_totalprice = 0;

        foreach($products as $key => $product)
        {
            $product_name[] = $product['product_name'];
            $product_id[]   = $product['id'];
            $sub_totalprice += $product['price'];
            $totalprice     += $product['price'];
        }
        if(!empty($coupon))
        {
            if($coupon['enable_flat'] == 'off')
            {
                $discount_value = ($sub_totalprice / 100) * $coupon['discount'];
                $totalprice     = $sub_totalprice - $discount_value;
            }
            else
            {
                $discount_value = $coupon['flat_discount'];
                $totalprice     = $sub_totalprice - $discount_value;
            }
        }

        if($products)
        {

            $ch = curl_init('https://api.razorpay.com/v1/payments/' . $pay_id . '');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_USERPWD, $store_payment_setting['razorpay_public_key'] . ':' . $store_payment_setting['razorpay_secret_key']); // Input your Razorpay Key Id and Secret Id here
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = json_decode(curl_exec($ch));
            // check that payment is authorized by razorpay or not

            if($response->status == 'authorized')
            {
                $student               = Auth::guard('students')->user();
                $order                 = new Order();
                $order->order_id       = '#' . time();
                $order->name           = $student->name;
                $order->card_number    = '';
                $order->card_exp_month = '';
                $order->card_exp_year  = '';
                $order->student_id     = $student->id;
                $order->course         = json_encode($products);
                $order->price          = $totalprice;
                $order->coupon         = isset($cart['coupon']['data_id']) ? $cart['coupon']['data_id'] : '';
                $order->coupon_json    = json_encode($coupon);
                $order->discount_price = isset($cart['coupon']['discount_price']) ? $cart['coupon']['discount_price'] : '';
                $order->price_currency = $store->currency_code;
                $order->txn_id         = isset($pay_id) ? $pay_id : '';
                $order->payment_type   = 'razerpay';
                $order->payment_status = 'success';
                $order->receipt        = '';
                $order->store_id       = $store['id'];
                $order->save();

                $purchased_course = new PurchasedCourse();
                foreach($products as $course_id)
                {
                    $purchased_course->course_id  = $course_id['product_id'];
                    $purchased_course->student_id = $student->id;
                    $purchased_course->order_id   = $order->id;
                    $purchased_course->save();

                    $student=Student::where('id',$purchased_course->student_id)->first();
                    $student->courses_id=$purchased_course->course_id;
                    $student->save();
                }

                // slack // 
                $settings  = Utility::notifications();
                if(isset($settings['order_notification']) && $settings['order_notification'] ==1){
                    $msg = 'New Order '.$orderID.' is created by '.$store['name'].'.';
                    Utility::send_slack_msg($msg);    
                }

                // telegram // 
                $settings  = Utility::notifications();
                if(isset($settings['telegram_order_notification']) && $settings['telegram_order_notification'] ==1){
                    $msg = 'New Order '.$orderID.' is created by '.$store['name'].'.';
                    Utility::send_telegram_msg($msg);    
                }

                session()->forget($slug);

                return redirect()->route(
                    'store-complete.complete', [
                                                 $store->slug,
                                                 Crypt::encrypt($order->id),
                                             ]
                )->with('success', __('Transaction has been success'));

            }
            else
            {
                return redirect()->back()->with('error', __('Transaction Unsuccesfull'));

            }

        }
        else
        {
            return redirect()->back()->with('error', __('Plan is deleted.'));
        }

    }

    //Mercado Pago Prepare Payment
    public function mercadopagoPayment($slug, Request $request)
    {
        $cart     = session()->get($slug);
        $products = $cart['products'];

        $store = Store::where('slug', $slug)->first();

        if(\Auth::check() && Utility::StudentAuthCheck($slug) == false)
        {
            $store_payment_setting = Utility::getPaymentSetting();
        }
        else
        {
            $store_payment_setting = Utility::getPaymentSetting($store->id);
        }
        if(!empty($cart))
        {
            $products = $cart['products'];
        }
        else
        {
            return redirect()->back()->with('error', __('Please add to product into cart'));
        }
        $discount_price = 0;
        if(isset($cart['coupon']['data_id']))
        {
            $coupon         = ProductCoupon::where('id', $cart['coupon']['data_id'])->first();
            $discount_price = str_replace('-' . $store->currency, '', $request->dicount_price);
        }
        else
        {
            $coupon = '';
        }
        $product_name   = [];
        $product_id     = [];
        $totalprice     = 0;
        $sub_totalprice = 0;

        foreach($products as $key => $product)
        {
            $product_name[] = $product['product_name'];
            $product_id[]   = $product['id'];
            $sub_totalprice += $product['price'];
            $totalprice     += $product['price'];
        }
        if($products)
        {
            //            try
            //            {

            if(!empty($coupon))
            {
                if($coupon['enable_flat'] == 'off')
                {
                    $discount_value = ($sub_totalprice / 100) * $coupon['discount'];
                    $totalprice     = $sub_totalprice - $discount_value;
                }
                else
                {
                    $discount_value = $coupon['flat_discount'];
                    $totalprice     = $sub_totalprice - $discount_value;
                }
            }


            $head_array = $request->headers;

            $request = $request->all();
         
            \MercadoPago\SDK::setAccessToken($store_payment_setting['mercado_access_token']);
            try
            {
                // Create a preference object
                $preference = new \MercadoPago\Preference();
                // Create an item in the preference
                $item              = new \MercadoPago\Item();
                $item->title       = $store->name . "Order";
                $item->quantity    = 1;
                $item->unit_price  = (float)$totalprice;
                $preference->items = array($item);
                //                $coupons_id = $request->input('coupon_id');
                $success_url = route(
                    'mercado.callback', [
                                          $slug,
                                          'flag' => 'success',
                                      ]
                );
                $failure_url = route(
                    'mercado.callback', [
                                          $slug,
                                          'flag' => 'failure',
                                      ]
                );
                $pending_url = route(
                    'mercado.callback', [
                                          $slug,
                                          'flag' => 'pending',
                                      ]
                );

                $preference->back_urls = array(
                    "success" => $success_url,
                    "failure" => $failure_url,
                    "pending" => $pending_url,
                );

                $preference->auto_return = "approved";
                $preference->save();

                // Create a customer object
                $payer = new \MercadoPago\Payer();
                // Create payer information
                $payer->name    = Auth::guard('students')->user()->id;
                $payer->email   = Auth::guard('students')->user()->email;
                $payer->address = array(
                    "street_name" => '',
                );
                if($store_payment_setting['mercado_mode'] == 'live')
                {
                    $redirectUrl = $preference->init_point;
                }
                else
                {
                    $redirectUrl = $preference->sandbox_init_point;
                }

                return response()->json(
                    [
                        'status' => 'success',
                        'url' => $redirectUrl,
                    ]
                );
            }
            catch(Exception $e)
            {
                return redirect()->back()->with('error', $e->getMessage());
            }

        }
        else
        {
            return redirect()->back()->with('error', __('is deleted.'));
        }
    }

    //Mercado Pago
    public function mercadopagoCallback($slug, Request $request)
    {
        if(!empty($slug))
        {
            $store    = Store::where('slug', $slug)->first();
            $products = '';
            $cart     = session()->get($slug);
            if(!empty($cart))
            {
                $products = $cart['products'];
            }
            else
            {
                return redirect()->back()->with('error', __('Please add to product into cart'));
            }
            if(isset($cart['coupon']['data_id']))
            {
                $coupon = ProductCoupon::where('id', $cart['coupon']['data_id'])->first();
            }
            else
            {
                $coupon = '';
            }
            $product_name   = [];
            $product_id     = [];
            $totalprice     = 0;
            $sub_totalprice = 0;


            foreach($products as $key => $product)
            {
                $product_name[] = $product['product_name'];
                $product_id[]   = $product['id'];
                $sub_totalprice += $product['price'];
                $totalprice     += $product['price'];
            }
            if(!empty($coupon))
            {
                if($coupon['enable_flat'] == 'off')
                {
                    $discount_value = ($sub_totalprice / 100) * $coupon['discount'];
                    $totalprice     = $sub_totalprice - $discount_value;
                }
                else
                {
                    $discount_value = $coupon['flat_discount'];
                    $totalprice     = $sub_totalprice - $discount_value;
                }
            }

            if($request->has('status'))
            {

                if($request->status == 'approved' && $request->flag == 'success')
                {
                    if($products)
                    {
                        $student               = Auth::guard('students')->user();
                        $order                 = new Order();
                        $order->order_id       = '#' . time();
                        $order->name           = $student->name;
                        $order->card_number    = '';
                        $order->card_exp_month = '';
                        $order->card_exp_year  = '';
                        $order->payment_status = 'success';
                        $order->student_id     = $student->id;
                        $order->course         = json_encode($products);
                        $order->price          = $totalprice;
                        $order->coupon         = isset($cart['coupon']['data_id']) ? $cart['coupon']['data_id'] : '';
                        $order->coupon_json    = json_encode($coupon);
                        $order->discount_price = isset($cart['coupon']['discount_price']) ? $cart['coupon']['discount_price'] : '';
                        $order->price_currency = $store->currency_code;
                        $order->txn_id         = $request->has('preference_id') ? $request->preference_id : '';
                        $order->payment_type   = 'mercadopago';
                        $order->payment_status = 'success';
                        $order->receipt        = '';
                        $order->store_id       = $store['id'];
                        $order->save();

                        $purchased_course = new PurchasedCourse();
                        foreach($products as $course_id)
                        {
                            $purchased_course->course_id  = $course_id['product_id'];
                            $purchased_course->student_id = $student->id;
                            $purchased_course->order_id   = $order->id;
                            $purchased_course->save();

                            $student=Student::where('id',$purchased_course->student_id)->first();
                            $student->courses_id=$purchased_course->course_id;
                            $student->save();

                        }

                        // slack // 
                        $settings  = Utility::notifications();
                        if(isset($settings['order_notification']) && $settings['order_notification'] ==1){
                            $msg = 'New Order '.$orderID.' is created by '.$store['name'].'.';
                            Utility::send_slack_msg($msg);    
                        }

                        // telegram // 
                        $settings  = Utility::notifications();
                        if(isset($settings['telegram_order_notification']) && $settings['telegram_order_notification'] ==1){
                            $msg = 'New Order '.$orderID.' is created by '.$store['name'].'.';
                            Utility::send_telegram_msg($msg);    
                        }

                        session()->forget($slug);

                        return redirect()->route(
                            'store-complete.complete', [
                                                         $store->slug,
                                                         Crypt::encrypt($order->id),
                                                     ]
                        )->with('success', __('Transaction has been success'));

                    }
                    else
                    {
                        return redirect()->back()->with('error', __('Transaction Unsuccessful.'));
                    }
                }
                else
                {
                    session()->flash('error', 'Transaction Unsuccessful');

                    return redirect('/');
                }
            }
            else
            {
                session()->flash('error', 'Transaction Unsuccessful');

                return redirect('/');

            }
        }
        else
        {
            session()->flash('error', 'Transaction Unsuccessful');

            return redirect('/');

        }

    }

    //Paytm Prepare payment
    public function paytmOrder($slug, Request $request)
    {
        $cart     = session()->get($slug);
        $products = $cart['products'];
        $store    = Store::where('slug', $slug)->first();
        if(\Auth::check())
        {
            $store_payment_setting = Utility::getPaymentSetting();
        }
        else
        {
            $store_payment_setting = Utility::getPaymentSetting($store->id);
        }
        $totalprice     = 0;
        $product_name   = [];
        $product_id     = [];
        $sub_totalprice = 0;
        $discount_price = 0;
        if(isset($cart['coupon']['data_id']))
        {
            $coupon         = ProductCoupon::where('id', $cart['coupon']['data_id'])->first();
            $discount_price = str_replace('-' . $store->currency, '', $request->dicount_price);
        }
        else
        {
            $coupon = '';
        }
        foreach($products as $key => $product)
        {
            $product_name[] = $product['product_name'];
            $product_id[]   = $product['id'];
            $sub_totalprice += $product['price'];
            $totalprice     += $product['price'];
        }
        if($products)
        {
            if(!empty($coupon))
            {
                if($coupon['enable_flat'] == 'off')
                {
                    $discount_value = ($sub_totalprice / 100) * $coupon['discount'];
                    $totalprice     = $sub_totalprice - $discount_value;
                }
                else
                {
                    $discount_value = $coupon['flat_discount'];
                    $totalprice     = $sub_totalprice - $discount_value;
                }
            }
            // $purchased_course->courses_id = $purchased_course->course_id;
            foreach($products as $product)
            {
                $courses_id = $product['product_id'];                
            }
            
            if(Utility::StudentAuthCheck($slug))
            {
                $student_data     = Auth::guard('students')->user();
                $pdata['phone']   = $student_data->phone_number;
                $pdata['email']   = $student_data->email;
                $pdata['user_id'] = $student_data->id;
                $pdata['courses_id'] = $student_data->$courses_id;
            }
            else
            {
                $pdata['phone']   = '';
                $pdata['email']   = '';
                $pdata['user_id'] = '';
            }

            config(
                [
                    'services.paytm-wallet.env' => $store_payment_setting['paytm_mode'],
                    'services.paytm-wallet.merchant_id' => $store_payment_setting['paytm_merchant_id'],
                    'services.paytm-wallet.merchant_key' => $store_payment_setting['paytm_merchant_key'],
                    'services.paytm-wallet.merchant_website' => 'WEBSTAGING',
                    'services.paytm-wallet.channel' => 'WEB',
                    'services.paytm-wallet.industry_type' => $store_payment_setting['paytm_industry_type'],
                ]
            );
            $payment = PaytmWallet::with('receive');

            $payment->prepare(
                [
                    'order' => date('Y-m-d') . '-' . strtotime(date('Y-m-d H:i:s')),
                    'user' => $pdata['user_id'],
                    'mobile_number' => $pdata['phone'],
                    'email' => $pdata['email'],
                    'amount' => $totalprice,
                    'callback_url' => route('paytm.callback', 'store=' . $slug),
                ]
            );

            return $payment->receive();
        }
        else
        {
            return redirect()->back()->with('error', __('is deleted.'));
        }

    }

    //Paytm Prepare payment
    public function paytmCallback(Request $request)
    {
        $slug     = $request->store;
        $store    = Store::where('slug', $slug)->first();
        $products = '';
        $cart     = session()->get($slug);
        if(\Auth::check())
        {
            $store_payment_setting = Utility::getPaymentSetting();
        }
        else
        {
            $store_payment_setting = Utility::getPaymentSetting($store->id);
        }
        if(!empty($cart))
        {
            $products = $cart['products'];
        }
        else
        {
            return redirect()->back()->with('error', __('Please add to product into cart'));
        }

        if(isset($cart['coupon']['data_id']))
        {
            $coupon         = ProductCoupon::where('id', $cart['coupon']['data_id'])->first();
            $discount_price = str_replace('-' . $store->currency, '', $request->dicount_price);
        }
        else
        {
            $coupon = '';
        }
        $product_name   = [];
        $product_id     = [];
        $sub_totalprice = 0;
        $totalprice     = 0;

        foreach($products as $key => $product)
        {
            $product_name[] = $product['product_name'];
            $product_id[]   = $product['id'];
            $sub_totalprice += $product['price'];
            $totalprice     += $product['price'];
        }
        if(!empty($coupon))
        {
            if($coupon['enable_flat'] == 'off')
            {
                $discount_value = ($sub_totalprice / 100) * $coupon['discount'];
                $totalprice     = $sub_totalprice - $discount_value;
            }
            else
            {
                $discount_value = $coupon['flat_discount'];
                $totalprice     = $sub_totalprice - $discount_value;
            }
        }
        if($products)
        {
            config(
                [
                    'services.paytm-wallet.env' => $store_payment_setting['paytm_mode'],
                    'services.paytm-wallet.merchant_id' => $store_payment_setting['paytm_merchant_id'],
                    'services.paytm-wallet.merchant_key' => $store_payment_setting['paytm_merchant_key'],
                    'services.paytm-wallet.merchant_website' => 'WEBSTAGING',
                    'services.paytm-wallet.channel' => 'WEB',
                    'services.paytm-wallet.industry_type' => $store_payment_setting['paytm_industry_type'],
                ]
            );

            $transaction = PaytmWallet::with('receive');

            $response = $transaction->response();
            if($transaction->isSuccessful())
            {
                $student_id            = Auth::guard('students')->user();
                $order                 = new Order();
                $order->order_id       = '#' . time();
                $order->name           = $student_id->name;
                $order->card_number    = '';
                $order->card_exp_month = '';
                $order->card_exp_year  = '';
                $order->student_id     = $student_id->id;
                $order->course         = json_encode($products);
                $order->price          = $totalprice;
                $order->coupon         = isset($cart['coupon']['data_id']) ? $cart['coupon']['data_id'] : '';
                $order->coupon_json    = json_encode($coupon);
                $order->discount_price = isset($cart['coupon']['discount_price']) ? $cart['coupon']['discount_price'] : '';
                $order->price_currency = $store->currency_code;
                $order->txn_id         = $response['TXNID'];
                $order->payment_type   = 'paytm';
                $order->payment_status = 'success';
                $order->receipt        = '';
                $order->store_id       = $store['id'];
                $order->save();


                $purchased_course = new PurchasedCourse();
                foreach($products as $course_id)
                {
                    $purchased_course->course_id  = $course_id['product_id'];
                    $purchased_course->student_id = $student_id->id;
                    $purchased_course->order_id   = $order->id;
                    $purchased_course->save();

                    $student=Student::where('id',$purchased_course->student_id)->first();
                    $student->courses_id=$purchased_course->course_id;
                    $student->save();                    
                }    
               
                // slack // 
                $settings  = Utility::notifications();
                if(isset($settings['order_notification']) && $settings['order_notification'] ==1){
                    $msg = 'New Order '.$orderID.' is created by '.$store['name'].'.';
                    Utility::send_slack_msg($msg);    
                }

                // telegram // 
                $settings  = Utility::notifications();
                if(isset($settings['telegram_order_notification']) && $settings['telegram_order_notification'] ==1){
                    $msg = 'New Order '.$orderID.' is created by '.$store['name'].'.';
                    Utility::send_telegram_msg($msg);    
                }

                session()->forget($slug);

                return redirect()->route(
                    'store-complete.complete', [
                                                 $store->slug,
                                                 Crypt::encrypt($order->id),
                                             ]
                )->with('success', __('Transaction has been success'));

            }
            else if($transaction->isFailed())
            {
                return redirect()->back()->with('error', __('Transaction Unsuccesfull'));
            }
            else if($transaction->isOpen())
            {
                //Transaction Open/Processing
                return redirect('/');
            }
            else
            {
                return redirect()->back()->with('error', __('Payment not made'));
            }


        }
        else
        {
            return redirect()->back()->with('error', __('Plan is deleted.'));
        }
    }

    //Mollie Prepare payment
    public function mollieOrder($slug, Request $request)
    {
        $cart           = session()->get($slug);
        $products       = $cart['products'];
        $store          = Store::where('slug', $slug)->first();
        $discount_price = 0;
        if(!empty($cart['coupon']))
        {
            $coupon         = ProductCoupon::where('id', $cart['coupon']['data_id'])->first();
            $discount_price = str_replace('-' . $store->currency, '', $cart['coupon']['discount_price']);
        }
        else
        {
            $coupon = '';
        }
        if(\Auth::check())
        {
            $store_payment_setting = Utility::getPaymentSetting();
        }
        else
        {
            $store_payment_setting = Utility::getPaymentSetting($store->id);
        }
        $product_name   = [];
        $product_id     = [];
        $sub_totalprice = 0;
        $totalprice  = 0;

        foreach($products as $key => $product)
        {
            $product_name[] = $product['product_name'];
            $product_id[]   = $product['id'];
            $sub_totalprice += $product['price'];
            $totalprice     += $product['price'];
        }
        if(!empty($coupon))
        {
            if($coupon['enable_flat'] == 'off')
            {
                $discount_value = ($sub_totalprice / 100) * $coupon['discount'];
                $totalprice     = $sub_totalprice - $discount_value;
            }
            else
            {
                $discount_value = $coupon['flat_discount'];
                $totalprice     = $sub_totalprice - $discount_value;
            }
        }


        if($products)
        {
            if(Utility::StudentAuthCheck($slug))
            {
                $student_data     = Auth::guard('students')->user();
                $pdata['phone']   = $student_data->phone_number;
                $pdata['email']   = $student_data->email;
                $pdata['user_id'] = $student_data->id;
            }
            else
            {
                $pdata['phone']   = '';
                $pdata['email']   = '';
                $pdata['user_id'] = '';
            }
            $request = $request->all();
            $mollie  = new \Mollie\Api\MollieApiClient();
            $mollie->setApiKey($store_payment_setting['mollie_api_key']);
            //var_dump(intval($request['amount']));

            $payment = $mollie->payments->create(
                [
                    "amount" => [
                        "currency" => "$store->currency_code",
                        "value" => number_format($totalprice, 2),
                    ],
                    "description" => "payment for product",
                    "redirectUrl" => route(
                        'mollie.callback', [
                                             $store->slug,
                                             $request['desc'],
                                         ]
                    ),

                ]
            );

            session()->put('mollie_payment_id', $payment->id);

            return redirect($payment->getCheckoutUrl())->with('payment_id', $payment->id);

        }
        else
        {
            return redirect()->back()->with('error', __('is deleted.'));
        }


    }

    //Mollie Callback payment
    public function mollieCallback($slug, $order_id, Request $request)
    {
        $store          = Store::where('slug', $slug)->first();
        $products       = '';
        $cart           = session()->get($slug);
        $discount_price = 0;
        if(\Auth::check())
        {
            $store_payment_setting = Utility::getPaymentSetting();
        }
        else
        {
            $store_payment_setting = Utility::getPaymentSetting($store->id);
        }
        if(!empty($cart))
        {
            $products = $cart['products'];
        }
        else
        {
            return redirect()->back()->with('error', __('Please add to product into cart'));
        }

        if(isset($cart['coupon']['data_id']))
        {
            $coupon         = ProductCoupon::where('id', $cart['coupon']['data_id'])->first();
            $discount_price = str_replace('-' . $store->currency, '', $request->dicount_price);
        }
        else
        {
            $coupon = '';
        }
        $product_name   = [];
        $product_id     = [];
        $totalprice     = 0;
        $sub_totalprice = 0;
        foreach($products as $key => $product)
        {
            $product_name[] = $product['product_name'];
            $product_id[]   = $product['id'];
            $sub_totalprice += $product['price'];
            $totalprice     += $product['price'];
        }
        if(!empty($coupon))
        {
            if($coupon['enable_flat'] == 'off')
            {
                $discount_value = ($sub_totalprice / 100) * $coupon['discount'];
                $totalprice     = $sub_totalprice - $discount_value;
            }
            else
            {
                $discount_value = $coupon['flat_discount'];
                $totalprice     = $sub_totalprice - $discount_value;
            }
        }

        if($products)
        {
            $mollie = new \Mollie\Api\MollieApiClient();
            $mollie->setApiKey($store_payment_setting['mollie_api_key']);

            if(session()->has('mollie_payment_id'))
            {
                $payment = $mollie->payments->get(session()->get('mollie_payment_id'));

                if($payment->isPaid())
                {
                    $student_id            = Auth::guard('students')->user();
                    $order                 = new Order();
                    $order->order_id       = '#' . time();
                    $order->name           = $student_id->name;
                    $order->card_number    = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year  = '';
                    $order->student_id     = $student_id->id;
                    $order->course         = json_encode($products);
                    $order->price          = $totalprice;
                    $order->coupon         = isset($cart['coupon']['data_id']) ? $cart['coupon']['data_id'] : '';
                    $order->coupon_json    = json_encode($coupon);
                    $order->discount_price = isset($cart['coupon']['discount_price']) ? $cart['coupon']['discount_price'] : '';
                    $order->price_currency = $store->currency_code;
                    $order->txn_id         = isset($pay_id) ? $pay_id : '';
                    $order->payment_type   = 'mollie';
                    $order->payment_status = 'success';
                    $order->receipt        = '';
                    $order->store_id       = $store['id'];
                    $order->save();

                    $purchased_course = new PurchasedCourse();
                    foreach($products as $course_id)
                    {
                        $purchased_course->course_id  = $course_id['product_id'];
                        $purchased_course->student_id = $student_id->id;
                        $purchased_course->order_id   = $order->id;
                        $purchased_course->save();

                        $student=Student::where('id',$purchased_course->student_id)->first();
                        $student->courses_id=$purchased_course->course_id;
                        $student->save();
                    }

                    // slack // 
                    $settings  = Utility::notifications();
                    if(isset($settings['order_notification']) && $settings['order_notification'] ==1){
                        $msg = 'New Order '.$orderID.' is created by '.$store['name'].'.';
                        Utility::send_slack_msg($msg);    
                    }

                    // telegram // 
                    $settings  = Utility::notifications();
                    if(isset($settings['telegram_order_notification']) && $settings['telegram_order_notification'] ==1){
                        $msg = 'New Order '.$orderID.' is created by '.$store['name'].'.';
                        Utility::send_telegram_msg($msg);    
                    }

                    session()->forget($slug);
                    session()->forget('mollie_payment_id');

                    return redirect()->route(
                        'store-complete.complete', [
                                                     $store->slug,
                                                     Crypt::encrypt($order->id),
                                                 ]
                    )->with('success', __('Transaction has been success'));

                }
                else
                {
                    return redirect()->back()->with('error', __('Transaction Unsuccesfull'));
                }
            }
            else
            {
                session()->flash('warning', 'Payment not made!');

                return redirect('/');
            }

        }
        else
        {
            return redirect()->back()->with('error', __('Plan is deleted.'));
        }
    }

    //skrillPayment Prepare payment
    public function skrillPayment($slug, Request $request)
    {
        $cart     = session()->get($slug);
        $products = $cart['products'];
        $store    = Store::where('slug', $slug)->first();
        if(\Auth::check())
        {
            $store_payment_setting = Utility::getPaymentSetting();
        }
        else
        {
            $store_payment_setting = Utility::getPaymentSetting($store->id);
        }

        $totalprice     = 0;
        $product_name   = [];
        $product_id     = [];
        $sub_totalprice = 0;

        foreach($products as $key => $product)
        {
            $product_name[] = $product['product_name'];
            $product_id[]   = $product['id'];
            $sub_totalprice += $product['price'];
            $totalprice     += $product['price'];
        }
        if($products)
        {
            $coupon_id = null;

            if(isset($cart['coupon']))
            {
                if($cart['coupon']['coupon']['enable_flat'] == 'off')
                {
                    $discount_value = ($sub_totalprice / 100) * $cart['coupon']['coupon']['discount'];
                    $totalprice     = $sub_totalprice - $discount_value;
                }
                else
                {
                    $discount_value = $cart['coupon']['coupon']['flat_discount'];
                    $totalprice     = $sub_totalprice - $discount_value;
                }
            }
            if(Utility::StudentAuthCheck($slug))
            {
                $student_data     = Auth::guard('students')->user();
                $pdata['phone']   = $student_data->phone_number;
                $pdata['email']   = $student_data->email;
                $pdata['user_id'] = $student_data->id;
            }
            else
            {
                $pdata['phone']   = '';
                $pdata['email']   = '';
                $pdata['user_id'] = '';
            }

            $head_array = $request->headers;

            $request = $request->all();
            if(!empty($store->logo))
            {
                $logo = asset(Storage::url('uploads/store_logo/' . $store->logo));
            }
            else
            {

                $logo = asset(Storage::url('uploads/store_logo/logo.png'));
            }

            $skill               = new SkrillRequest();
            $skill->pay_to_email = $store_payment_setting['skrill_email'];
            $skill->return_url   = route('skrill.callback') . '?transaction_id=' . MD5($request['transaction_id']);
            $skill->cancel_url   = route('skrill.callback') . '?slug=' . $slug;
            $skill->logo_url     = $logo;

            // create object instance of SkrillRequest
            $skill->transaction_id  = MD5($request['transaction_id']); // generate transaction id
            $skill->amount          = $totalprice;
            $skill->currency        = $store->currency_code;
            $skill->language        = 'EN';
            $skill->prepare_only    = '1';
            $skill->merchant_fields = 'site_name, customer_email';
            $skill->site_name       = $store->name;
            $skill->customer_email  = $pdata['email'];

            // create object instance of SkrillClient
            $client = new SkrillClient($skill);
            $sid    = $client->generateSID(); //return SESSION ID

            // handle error
            $jsonSID = json_decode($sid);
            if($jsonSID != null && $jsonSID->code == "BAD_REQUEST")
            {
                return redirect()->back()->with('error', $jsonSID->message);
            }


            // do the payment
            $redirectUrl = $client->paymentRedirectUrl($sid); //return redirect url
            if($request['transaction_id'])
            {
                $data = [
                    'amount' => $totalprice,
                    'trans_id' => MD5($request['transaction_id']),
                    'currency' => $store->currency_code,
                    'slug' => $store->slug,
                ];
                session()->put('skrill_data', $data);

            }

            return redirect($redirectUrl);


        }
        else
        {
            return redirect()->back()->with('error', __('is deleted.'));
        }


    }

    //skrillPayment Callback payment
    public function skrillCallback(Request $request)
    {
        if(session()->has('skrill_data') && !empty($request) && isset($request->transaction_id))
        {
            $get_data = session()->get('skrill_data');
            $slug     = $get_data['slug'];
            $store    = Store::where('slug', $slug)->first();
            $products = '';
            $cart     = session()->get($slug);
            if(\Auth::check())
            {
                $store_payment_setting = Utility::getPaymentSetting();
            }
            else
            {
                $store_payment_setting = Utility::getPaymentSetting($store->id);
            }

            if(!empty($cart))
            {
                $products = $cart['products'];
            }
            else
            {
                return redirect()->back()->with('error', __('Please add to product into cart'));
            }

            if(isset($cart['coupon']['data_id']))
            {
                $coupon         = ProductCoupon::where('id', $cart['coupon']['data_id'])->first();
                $discount_price = str_replace('-' . $store->currency, '', $cart['coupon']['discount_price']);
            }
            else
            {
                $coupon = '';
            }
            $product_name   = [];
            $product_id     = [];
            $totalprice     = 0;
            $sub_totalprice = 0;

            foreach($products as $key => $product)
            {
                $product_name[] = $product['product_name'];
                $product_id[]   = $product['id'];
                $sub_totalprice += $product['price'];
                $totalprice     += $product['price'];
            }

            if($products)
            {
                $student_id            = Auth::guard('students')->user();
                $order                 = new Order();
                $order->order_id       = '#' . time();
                $order->name           = $student_id->name;
                $order->card_number    = '';
                $order->card_exp_month = '';
                $order->card_exp_year  = '';
                $order->student_id     = $student_id->id;
                $order->course         = json_encode($products);
                $order->price          = $totalprice;
                $order->coupon         = isset($cart['coupon']['data_id']) ? $cart['coupon']['data_id'] : '';
                $order->coupon_json    = json_encode($coupon);
                $order->discount_price = isset($cart['coupon']['discount_price']) ? $cart['coupon']['discount_price'] : '';
                $order->price_currency = $store->currency_code;
                $order->txn_id         = $request->has('transaction_id') ? $request->transaction_id : '';
                $order->payment_type   = 'mollie';
                $order->payment_status = 'success';
                $order->receipt        = '';
                $order->store_id       = $store['id'];
                $order->save();

                $purchased_course = new PurchasedCourse();
                foreach($products as $course_id)
                {
                    $purchased_course->course_id  = $course_id['product_id'];
                    $purchased_course->student_id = $student_id->id;
                    $purchased_course->order_id   = $order->id;
                    $purchased_course->save();

                    $student=Student::where('id',$purchased_course->student_id)->first();
                    $student->courses_id=$purchased_course->course_id;
                    $student->save();
                }

                // slack // 
                $settings  = Utility::notifications();
                if(isset($settings['order_notification']) && $settings['order_notification'] ==1){
                    $msg = 'New Order '.$orderID.' is created by '.$store['name'].'.';
                    Utility::send_slack_msg($msg);    
                }

                // telegram // 
                $settings  = Utility::notifications();
                if(isset($settings['telegram_order_notification']) && $settings['telegram_order_notification'] ==1){
                    $msg = 'New Order '.$orderID.' is created by '.$store['name'].'.';
                    Utility::send_telegram_msg($msg);    
                }

                session()->forget($slug);
                session()->forget('skrill_data');

                return redirect()->route(
                    'store-complete.complete', [
                                                 $store->slug,
                                                 Crypt::encrypt($order->id),
                                             ]
                )->with('success', __('Transaction has been success'));

            }
            else
            {
                return redirect()->back()->with('error', __('Transaction Unsuccessful.'));
            }

        }
        else
        {
            session()->flash('error', 'Transaction Unsuccessful');

            return redirect()->route('store.slug', $request->slug)->with('error', __('Transaction Unsuccessful.'));

        }


    }

    //Coingate Pago Prepare Payment
    public function coingatePayment($slug, Request $request)
    {
        $store          = Store::where('slug', $slug)->first();
        $products       = '';
        $cart           = session()->get($slug);
        $discount_price = 0;
        if(\Auth::check())
        {
            $store_payment_setting = Utility::getPaymentSetting();
        }
        else
        {
            $store_payment_setting = Utility::getPaymentSetting($store->id);
        }
        if(!empty($cart))
        {
            $products = $cart['products'];
        }
        else
        {
            return redirect()->back()->with('error', __('Please add to product into cart'));
        }
        if(isset($cart['coupon']['data_id']))
        {
            $coupon         = ProductCoupon::where('id', $cart['coupon']['data_id'])->first();
            $discount_price = str_replace('-' . $store->currency, '', $cart['coupon']['discount_price']);
        }
        else
        {
            $coupon = '';
        }
        $product_name    = [];
        $product_id      = [];
        $sub_totalprice  = 0;
        $totalprice      = 0;
        $preference_data = [];
        foreach($products as $key => $product)
        {
            $product_name[] = $product['product_name'];
            $product_id[]   = $product['id'];
            $sub_totalprice += $product['price'];
            $totalprice     += $product['price'];
        }
        if(!empty($coupon))
        {
            if($coupon['enable_flat'] == 'off')
            {
                $discount_value = ($sub_totalprice / 100) * $coupon['discount'];
                $totalprice     = $sub_totalprice - $discount_value;
            }
            else
            {
                $discount_value = $coupon['flat_discount'];
                $totalprice     = $sub_totalprice - $discount_value;
            }
        }

        if($products)
        {
            $student_id            = Auth::guard('students')->user();
            $order                 = new Order();
            $order->order_id       = '#' . time();
            $order->name           = $student_id->name;
            $order->card_number    = '';
            $order->card_exp_month = '';
            $order->card_exp_year  = '';
            $order->student_id     = $student_id->id;
            $order->course         = json_encode($products);
            $order->price          = $totalprice;
            $order->coupon         = isset($cart['coupon']['data_id']) ? $cart['coupon']['data_id'] : '';
            $order->coupon_json    = json_encode($coupon);
            $order->discount_price = isset($cart['coupon']['discount_price']) ? $cart['coupon']['discount_price'] : '';
            $order->price_currency = $store->currency_code;
            $order->txn_id         = isset($pay_id) ? $pay_id : '';
            $order->payment_type   = 'coingate';
            $order->payment_status = 'pendding';
            $order->receipt        = '';
            $order->store_id       = $store['id'];
            $order->save();

            $purchased_course = new PurchasedCourse();
            foreach($products as $course_id)
            {
                $purchased_course->course_id  = $course_id['product_id'];
                $purchased_course->student_id = $student_id->id;
                $purchased_course->order_id   = $order->id;
                $purchased_course->save();

                $student=Student::where('id',$purchased_course->student_id)->first();
                $student->courses_id=$purchased_course->course_id;
                $student->save();

            }

            // slack // 
            $settings  = Utility::notifications();
            if(isset($settings['order_notification']) && $settings['order_notification'] ==1){
                $msg = 'New Order '.$orderID.' is created by '.$store['name'].'.';
                Utility::send_slack_msg($msg);    
            }
            
            // telegram // 
            $settings  = Utility::notifications();
            if(isset($settings['telegram_order_notification']) && $settings['telegram_order_notification'] ==1){
                $msg = 'New Order '.$orderID.' is created by '.$store['name'].'.';
                Utility::send_telegram_msg($msg);    
            }
            
            try
            {
                CoinGate::config(
                    array(
                        'environment' => $store_payment_setting['coingate_mode'],
                        // sandbox OR live
                        'auth_token' => $store_payment_setting['coingate_auth_token'],
                        'curlopt_ssl_verifypeer' => FALSE
                        // default is false
                    )
                );

                $post_params = array(
                    'order_id' => $order->id,
                    'price_amount' => $totalprice,
                    'price_currency' => $store['currency_code'],
                    'receive_currency' => $store['currency_code'],
                    'callback_url' => url('coingate/callback') . '?slug=' . $store->slug . '&order_id=' . $order->id,
                    'cancel_url' => url('/'),
                    'success_url' => route(
                        'store-complete.complete', [
                                                     $store->slug,
                                                     Crypt::encrypt($order->id),
                                                 ]
                    ),
                    'title' => 'Order #' . time(),
                );
                $order       = \CoinGate\Merchant\Order::create($post_params);
                if($order)
                {
                    session()->forget($slug);

                    return redirect($order->payment_url);
                }
                else
                {
                    return redirect()->back()->with('error', __('opps something wren wrong.'));
                }
            }
            catch(Exception $e)
            {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Transaction Unsuccesfull'));
        }

    }

    //Coingate Pago
    public function coingateCallback(Request $request)
    {
        if($request->has('order_id'))
        {
            $order = Order::where('id', $request->order_id)->first();
            if($order)
            {
                $order->payment_status = 'succuss';
                $order->save();
            }
        }
    }

    //PaymentWall
    public function paymentwallCallback(Request $request,$slug)
    {      
        $data=$request->all();
        // $payments = Utility::getPaymentSetting();
        $store    = Store::where('slug', $slug)->first(); 
        $payments = Utility::getPaymentSetting($store->id);     
        // $settings = Utility::settings();
        
        return view('storefront.' . $store->theme_dir . '.paymentwall',compact('payments','store','data','slug'));
    }

    public function paymentwallPayment(Request $request,$slug)
    {
        $store = Store::where('slug', $slug)->first();
        $cart  = session()->get($slug);
        if(\Auth::check())
        {
            $store_payment_setting = Utility::getPaymentSetting();
        }
        else
        {
            $store_payment_setting = Utility::getPaymentSetting($store->id);
        }
        if(!empty($cart))
        {
            $products = $cart['products'];
        }
        else
        {
            return redirect()->back()->with('error', __('Please add to product into cart'));
        }

        if(isset($cart['coupon']['data_id']))
        {
            $coupon = ProductCoupon::where('id', $cart['coupon']['data_id'])->first();
        }
        else
        {
            $coupon = '';
        }
        $product_name   = [];
        $product_id     = [];
        $totalprice     = 0;
        $sub_totalprice = 0;


        if(!empty($coupon))
        {
            if($coupon['enable_flat'] == 'off')
            {
                $discount_value = ($sub_totalprice / 100) * $coupon['discount'];
                $totalprice     = $sub_totalprice - $discount_value;
            }
            else
            {
                $discount_value = $coupon['flat_discount'];
                $totalprice     = $sub_totalprice - $discount_value;
            }
        }
       
        if($products)
        {

            $result = array();
            //The parameter after verify/ is the transaction reference to be verified
            \Paymentwall_Config::getInstance()->set(array(
                                                       'private_key' => $store_payment_setting['paymentwall_secret_key']
                                                   ));
            $parameters = $_POST;
            $chargeInfo = array(
                'email' => $parameters['email'],
                'history[registration_date]' => '1489655092',
                'amount' => $totalprice,
                'currency' => !empty($store->currency_code) ? $store->currency_code : 'USD',
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

                    $student               = Auth::guard('students')->user();
                    $order                 = new Order();
                    $order->order_id       = '#' . time();
                    $order->name           = $student->name;
                    $order->card_number    = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year  = '';
                    $order->student_id     = $student->id;
                    $order->course         = json_encode($products);
                    $order->price          = $totalprice;
                    $order->coupon         = isset($cart['coupon']['data_id']) ? $cart['coupon']['data_id'] : '';
                    $order->coupon_json    = json_encode($coupon);
                    $order->discount_price = isset($cart['coupon']['discount_price']) ? $cart['coupon']['discount_price'] : '';
                    $order->price_currency = $store->currency_code;
                    $order->txn_id         = isset($tran_id) ? $tran_id : '';
                    $order->payment_type   = 'flutterwave';
                    $order->payment_status = 'success';
                    $order->receipt        = '';
                    $order->store_id       = $store['id'];
                    $order->save();

                    $purchased_course = new PurchasedCourse();
                    foreach($products as $course_id)
                    {
                        $purchased_course->course_id  = $course_id['product_id'];
                        $purchased_course->student_id = $student->id;
                        $purchased_course->order_id   = $order->id;
                        $purchased_course->save();

                        $student=Student::where('id',$purchased_course->student_id)->first();
                        $student->courses_id=$purchased_course->course_id;
                        $student->save();
                    }

                    // slack // 
                    $settings  = Utility::notifications();
                    if(isset($settings['order_notification']) && $settings['order_notification'] ==1){
                        $msg = 'New Order '.$orderID.' is created by '.$store['name'].'.';
                        Utility::send_slack_msg($msg);    
                    }

                    // telegram // 
                    $settings  = Utility::notifications();
                    if(isset($settings['telegram_order_notification']) && $settings['telegram_order_notification'] ==1){
                        $msg = 'New Order '.$orderID.' is created by '.$store['name'].'.';
                        Utility::send_telegram_msg($msg);    
                    }

              
                  
                    $res['flag'] = 1;
                    $res['slug'] = $slug;
                    $res['order_id'] = Crypt::encrypt($order->id);
                    return $res;

                }
                elseif($charge->isUnderReview()) {
                 
                    $res['flag'] = 2;
                    $res['slug'] = $slug;
                    return $res;
                }
            }else {
                $res['flag'] = 2;
                $res['slug'] = $slug;
                return $res;
            }
        }

    }

    public function orderpaymenterror(Request $request,$flag,$slug)
    {           
        if($flag == 1){
            return redirect()->route('store.slug',$slug)->with('error', __('Transaction has been Successfull!'));
        }else{           
            return redirect()->route('store.slug',$slug)->with('error', __('Transaction has been failed!'));
        }    
    }

    //Plan

    // Plan purchase  Payments methods
    public function paystackPlanGetPayment($code, $plan_id, Request $request)
    {   
        // dd($request->all());
        $user                  = Auth::user();
        $store_id              = Auth::user()->current_store;
        $admin_payment_setting = Utility::getAdminPaymentSetting();
        $plan_id               = Plan::find(\Illuminate\Support\Facades\Crypt::decrypt($plan_id));
        $plan                  = Plan::find($plan_id)->first();
        $setting = Utility::settings();
        if($plan)
        {
            try
            {
                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                $result = array();
                //The parameter after verify/ is the transaction reference to be verified
                $url = "https://api.paystack.co/transaction/verify/$code";
                $ch  = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt(
                    $ch, CURLOPT_HTTPHEADER, [
                           'Authorization: Bearer ' . $admin_payment_setting['paystack_secret_key'],
                       ]
                );
                $responce = curl_exec($ch);
                curl_close($ch);
                if($responce)
                {
                    $result = json_decode($responce, true);
                }
                if(array_key_exists('data', $result) && array_key_exists('status', $result['data']) && ($result['data']['status'] === 'success'))
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
                    $planorder                 = new PlanOrder();
                    $planorder->order_id       = $orderID;
                    $planorder->name           = $user->name;
                    $planorder->card_number    = '';
                    $planorder->card_exp_month = '';
                    $planorder->card_exp_year  = '';
                    $planorder->plan_name      = $plan->name;
                    $planorder->plan_id        = $plan->id;
                    $planorder->price          = $result['data']['amount'] / 100;
                    $planorder->price_currency = $setting['site_currency'];
                    $planorder->txn_id         = $code;
                    $planorder->payment_type   = __('Paystack');
                    $planorder->payment_status = $result['data']['status'];
                    $planorder->receipt        = '';
                    $planorder->user_id        = $user->id;
                    $planorder->store_id       = $store_id;
                    // dd($planorder);
                    $planorder->save();

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

    // Plan flutterwave  Payments methods
    public function flutterwavePlanGetPayment($code, $plan_id, Request $request)
    {
        // dd($code);
        $user                  = Auth::user();
        $store_id              = Auth::user()->current_store;
        $admin_payment_setting = Utility::getAdminPaymentSetting();
        $plan_id               = Plan::find(\Illuminate\Support\Facades\Crypt::decrypt($plan_id));
        $plan                  = Plan::find($plan_id)->first();
        $setting        = Utility::settings();

        if($plan)
        {
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

            $data = array(
                'txref' => $code,
                'SECKEY' => $admin_payment_setting['flutterwave_secret_key'],
                //secret key from pay button generated on rave dashboard
            );

            // make request to endpoint using unirest.
            $headers = array('Content-Type' => 'application/json');
            $body    = \Unirest\Request\Body::json($data);
            $url     = "https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify"; //please make sure to change this to production url when you go live

            // Make `POST` request and handle response with unirest
            $response = \Unirest\Request::post($url, $headers, $body);

            if($response->body->data->status === "successful" && $response->body->data->chargecode === "00")
            {

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
                $planorder                 = new PlanOrder();
                $planorder->order_id       = $orderID;
                $planorder->name           = $user->name;
                $planorder->card_number    = '';
                $planorder->card_exp_month = '';
                $planorder->card_exp_year  = '';
                $planorder->plan_name      = $plan->name;
                $planorder->plan_id        = $plan->id;
                $planorder->price          = $response->body->data->amount;
                $planorder->price_currency =  $setting['site_currency'];
                $planorder->txn_id         = $response->body->data->txid;
                $planorder->payment_type   = __('Flutterwave ');
                $planorder->payment_status = $response->body->data->status;
                $planorder->receipt        = '';
                $planorder->user_id        = $user->id;
                $planorder->store_id       = $store_id;
                $planorder->save();

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
        else
        {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }

    // Plan razorpay  Payments methods
    public function razorpayPlanGetPayment($pay_id, $plan_id, Request $request)
    {
        $user                  = Auth::user();
        $store_id              = Auth::user()->current_store;
        $admin_payment_setting = Utility::getAdminPaymentSetting();
        $plan_id               = Plan::find(\Illuminate\Support\Facades\Crypt::decrypt($plan_id));
        $plan                  = Plan::find($plan_id)->first();
        $setting = Utility::settings();
        if($plan)
        {

            try
            {
                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                $result = array();
                //The parameter after verify/ is the transaction reference to be verified
                $ch = curl_init('https://api.razorpay.com/v1/payments/' . $pay_id . '');
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_USERPWD, $admin_payment_setting['razorpay_public_key'] . ':' . $admin_payment_setting['razorpay_secret_key']); // Input your Razorpay Key Id and Secret Id here
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = json_decode(curl_exec($ch));
                // check that payment is authorized by razorpay or not

                if($response->status == 'authorized')
                {

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
                    $planorder                 = new PlanOrder(); 
                    $planorder->order_id       = $orderID;
                    $planorder->name           = $user->name;
                    $planorder->card_number    = '';
                    $planorder->card_exp_month = '';
                    $planorder->card_exp_year  = '';
                    $planorder->plan_name      = $plan->name;
                    $planorder->plan_id        = $plan->id;
                    $planorder->price          = $response->amount / 100;
                    $planorder->price_currency = $setting['site_currency'];
                    $planorder->txn_id         = $pay_id;
                    $planorder->payment_type   = __('Razorpay');
                    $planorder->payment_status = $response->status == 'authorized' ? 'success' : 'failed';
                    $planorder->receipt        = '';
                    $planorder->user_id        = $user->id;
                    $planorder->store_id       = $store_id;
                    $planorder->save();

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

    // Mercado Plan PreparePayment
    public function mercadopagoPaymentPrepare(Request $request)
    {
        // dd($request->all()); 
        // $this->secret_key = isset($admin_payment_setting['mercado_secret_key']) ? $admin_payment_setting['mercado_secret_key'] : '';
        // $this->app_id = isset($admin_payment_setting['mercado_app_id']) ? $admin_payment_setting['mercado_app_id'] : '';
        // $this->is_enabled = isset($admin_payment_setting['is_mercado_enabled']) ? $admin_payment_setting['is_mercado_enabled'] : 'off';

        $validator = \Validator::make(
            $request->all(), [
                               'plan' => 'required',
                               'total_price' => 'required',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return response()->json(
                [
                    'status' => 'error',
                    'error' => $messages->first(),
                ]
            );
        }
        $plan = Plan::find($request->plan)->first();
        if($plan)
        {
            // dd($this->token);
            // \MercadoPago\SDK::setAccessToken($this->token);
            $admin_payment_setting = Utility::getAdminPaymentSetting();
            // dd($admin_payment_setting); 
            \MercadoPago\SDK::setAccessToken($admin_payment_setting['mercado_access_token']);
            try
            {
                $amount = (float)$request->total_price;
                // Create a preference object
                $preference = new \MercadoPago\Preference();
                // Create an item in the preference
                $item              = new \MercadoPago\Item();
                $item->title       = "Plan : " . $plan->name;
                $item->quantity    = 1;
                $item->unit_price  = $amount;
                $preference->items = array($item);
                $coupons_id        = $request->input('coupon_id');
                $success_url       = route(
                    'plan.mercado.callback', [
                                               encrypt($request->plan),
                                               'coupon_id=' . $coupons_id,
                                               'flag' => 'success',
                                           ]
                );
                $failure_url       = route(
                    'plan.mercado.callback', [
                                               encrypt($request->plan),
                                               'flag' => 'failure',
                                           ]
                );
                $pending_url       = route(
                    'plan.mercado.callback', [
                                               encrypt($request->plan),
                                               'flag' => 'pending',
                                           ]
                );

                $preference->back_urls = array(
                    "success" => $success_url,
                    "failure" => $failure_url,
                    "pending" => $pending_url,
                );

                $preference->auto_return = "approved";
                $preference->save();

                // Create a customer object
                $payer = new \MercadoPago\Payer();
                // Create payer information
                $payer->name    = \Auth::user()->name;
                $payer->email   = \Auth::user()->email;
                $payer->address = array(
                    "street_name" => '',
                );
                if($admin_payment_setting['mercado_mode'] == 'live')
                {
                    $redirectUrl = $preference->init_point;
                }
                else
                {
                    $redirectUrl = $preference->sandbox_init_point;
                }

                return response()->json(
                    [
                        'status' => 'success',
                        'url' => $redirectUrl,
                    ]
                );
            }
            catch(Exception $e)
            {
                return response()->json(
                    [
                        'status' => 'error',
                        'error' => $e->getMessage(),
                    ]
                );
            }
        }

    }

    // Mercado mercadopagoPaymentCallback
    public function mercadopagoPaymentCallback($plan, Request $request)
    {
        $user                  = Auth::user();
        $store_id              = Auth::user()->current_store;
        $admin_payment_setting = Utility::getAdminPaymentSetting();
        $plan_id               = Crypt::decrypt($plan);
        $plan                  = Plan::find($plan_id);
        $setting               = Utility::settings();
        if($plan)
        {
            $orderID = time();
            if($plan && $request->has('status'))
            {
                $price = $plan->price;
                if($request->status == 'approved' && $request->flag == 'success')
                {
                    if($request->has('coupon_id') && $request->coupon_id != '')
                    {
                        $coupons = Coupon::find($request->coupon_id);
                        if(!empty($coupons))
                        {
                            $discount_value = ($price / 100) * $coupons->discount;

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
                            $price = $price - $discount_value;
                        }
                    }
                    $planorder                 = new PlanOrder();
                    $planorder->order_id       = $orderID;
                    $planorder->name           = $user->name;
                    $planorder->card_number    = '';
                    $planorder->card_exp_month = '';
                    $planorder->card_exp_year  = '';
                    $planorder->plan_name      = $plan->name;
                    $planorder->plan_id        = $plan->id;
                    $planorder->price          = $price;
                    // $planorder->price_currency = env('CURRENCY');
                    $planorder->price_currency =$setting['site_currency'];
                    $planorder->txn_id         = $request->has('preference_id') ? $request->preference_id : '';
                    $planorder->payment_type   = __('Mercado Pago');
                    $planorder->payment_status = 'success';
                    $planorder->receipt        = '';
                    $planorder->user_id        = $user->id;
                    $planorder->store_id       = $store_id;
                    $planorder->save();

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
            else
            {
                return redirect()->back()->with('error', __('Transaction Unsuccesfull'));
            }

            session()->forget('mollie_payment_id');
        }
        else
        {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }

    // Paytm Plan PreparePayment
    public function paytmPaymentPrepare(Request $request)
    {    

        $validator = \Validator::make(
            $request->all(), [
                               'plan_id' => 'required',
                               'total_price' => 'required',
                               'mobile_number' => 'required|numeric',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $user    = Auth::user()->current_store;
        $store   = Store::where('id', $user)->first();
        $plan_id = decrypt($request->plan_id);
        $plan    = Plan::find($plan_id)->first();
        $setting = Utility::settings(); 

        if($plan)
        {
            $admin_payment_setting = Utility::getAdminPaymentSetting();
            $order                 = $request->all();
            config(
                [
                    'services.paytm-wallet.env' => $admin_payment_setting['paytm_mode'],
                    'services.paytm-wallet.merchant_id' => $admin_payment_setting['paytm_merchant_id'],
                    'services.paytm-wallet.merchant_key' => $admin_payment_setting['paytm_merchant_key'],
                    'services.paytm-wallet.merchant_website' => 'WEBSTAGING',
                    'services.paytm-wallet.channel' => 'WEB',
                    'services.paytm-wallet.industry_type' => $admin_payment_setting['paytm_industry_type'],
                ]
            );

            $payment = \PaytmWallet::with('receive');             
    
            $payment->prepare(
                [
                    // 'order' => $plan_id,
                    'order' => uniqid().'_'.$plan_id,
                    'user' => Auth::user()->id,
                    'mobile_number' => $request->mobile_number,
                    'email' => Auth::user()->email,
                    'amount' => $request->total_price,
                    // 'callback_url' => route('plan.paytm.callback', 'store=' . $store->slug),
                    'callback_url' => route('plan.paytm.callback', '_token=' . Session::token().'&plan_id=' . $plan_id),
                ]
            );
          
            return $payment->receive();

        }

    }


    public function paytmPlanGetPayment(Request $request)
    {                
        $user                  = Auth::user();
        $store_id              = Auth::user()->current_store;
        $admin_payment_setting = Utility::getAdminPaymentSetting();
        // $plan_id               = Crypt::decrypt($request->plan_id);
        // $plan                  = Plan::find($plan_id);  
        $plan                  = Plan::find($request->plan_id);              
        $setting  = Utility::settings();

        // dd($planid , $plan,$store_id,$store_id , $user, $admin_payment_setting);
        if($plan)
        {
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            config(
                [
                    'services.paytm-wallet.env' => $admin_payment_setting['paytm_mode'],
                    'services.paytm-wallet.merchant_id' => $admin_payment_setting['paytm_merchant_id'],
                    'services.paytm-wallet.merchant_key' => $admin_payment_setting['paytm_merchant_key'],
                    'services.paytm-wallet.merchant_website' => 'WEBSTAGING',
                    'services.paytm-wallet.channel' => 'WEB',
                    'services.paytm-wallet.industry_type' => $admin_payment_setting['paytm_industry_type'],
                ]
            );
            $transaction = PaytmWallet::with('receive');
          

            // To get raw response as array
            $response = $transaction->response();
            
            if($transaction->isSuccessful())
            {
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
                $planorder                 = new PlanOrder();
                $planorder->order_id       = $orderID;
                $planorder->name           = $user->name;
                $planorder->card_number    = '';
                $planorder->card_exp_month = '';
                $planorder->card_exp_year  = '';
                $planorder->plan_name      = $plan->name;
                $planorder->plan_id        = $plan->id;
                $planorder->price          = $response['TXNAMOUNT'];
                $planorder->price_currency = $setting['site_currency'];
                // $planorder->price_currency = env('CURRENCY');
                $planorder->txn_id         = $response['MID'];
                $planorder->payment_type   = __('Paytm');
                $planorder->payment_status = 'success';
                $planorder->receipt        = '';
                $planorder->user_id        = $user->id;
                $planorder->store_id       = $store_id;
                $planorder->save();

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
                // dd('fgdkj');
                return redirect()->back()->with('error', __('Transaction Unsuccesfull'));
            }

            session()->forget('mollie_payment_id');
        }
        else
        {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }

    // Mollie Plan PreparePayment
    public function molliePaymentPrepare(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                               'plan_id' => 'required',
                               'total_price' => 'required',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return response()->json(
                [
                    'status' => 'error',
                    'error' => $messages->first(),
                ]
            );
        }
        $user    = Auth::user()->current_store;
        $store   = Store::where('id', $user)->first();
        $plan_id = decrypt($request->plan_id);
        
        // $plan    = Plan::find($plan_id);
        $plan    = Plan::where('id',$plan_id)->first();
        // dd($plan);

        $coupons_id = '';
        $setting = Utility::settings();
        // dd($request->all());
        if($plan)
        {
            $admin_payment_setting = Utility::getAdminPaymentSetting();

            $mollie = new \Mollie\Api\MollieApiClient();
            $mollie->setApiKey($admin_payment_setting['mollie_api_key']);
            //    dd($mollie);
            $payment = $mollie->payments->create(
                [
                    "amount" => [
                        "currency" => $setting['site_currency'],
                        // "currency" => env('CURRENCY'),
                        "value" => number_format($request->total_price, 2),
                    ],
                    "description" => $plan->name,
                    "redirectUrl" => route(
                        'plan.mollie.callback', [
                                                    $store->slug,
                                                    $request->plan_id,
                                                ]
                    ),
                ]
            );   
            // dd($payment);        
            session()->put('mollie_payment_id', $payment->id);

            return redirect($payment->getCheckoutUrl())->with('payment_id', $payment->id);
        }

    }

    public function molliePlanGetPayment(Request $request, $slug, $plan_id)
    {
   
        $user                  = Auth::user();
        $store_id              = Auth::user()->current_store;
        $admin_payment_setting = Utility::getAdminPaymentSetting();
        $plan_id               = Plan::find(\Illuminate\Support\Facades\Crypt::decrypt($plan_id));
        $plan                  = Plan::find($plan_id)->first();
        $setting = Utility::settings();
       
        if($plan)
        {
            try
            {
                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                $mollie = new \Mollie\Api\MollieApiClient();
                $mollie->setApiKey($admin_payment_setting['mollie_api_key']);

                if(session()->has('mollie_payment_id'))
                {
                    $payment = $mollie->payments->get(session()->get('mollie_payment_id'));

                    if($payment->isPaid())
                    {
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
                        $planorder                 = new PlanOrder();
                        $planorder->order_id       = $orderID;
                        $planorder->name           = $user->name;
                        $planorder->card_number    = '';
                        $planorder->card_exp_month = '';
                        $planorder->card_exp_year  = '';
                        $planorder->plan_name      = $plan->name;
                        $planorder->plan_id        = $plan->id;
                        $planorder->price          = $payment->amount->value;
                        $planorder->price_currency = $setting['site_currency'];
                        $planorder->txn_id         = $payment->id;
                        $planorder->payment_type   = __('Mollie');
                        $planorder->payment_status = $payment->status == 'authorized' ? 'success' : 'failed';
                        $planorder->receipt        = '';
                        $planorder->user_id        = $user->id;
                        $planorder->store_id       = $store_id;
                        $planorder->save();

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

                    session()->forget('mollie_payment_id');


                }
                else
                {
                    session()->flash('error', 'Transaction Error');

                    return redirect('/');
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

    // skrill Plan PreparePayment
    public function skrillPaymentPrepare(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                               'plan_id' => 'required',
                               'total_price' => 'required',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $user    = Auth::user()->current_store;
        $store   = Store::where('id', $user)->first();
        $plan_id = decrypt($request->plan_id);
        $plan    = Plan::find($plan_id)->first();
        $price   = $request->total_price;

        if($plan)
        {
            $admin_payment_setting = Utility::getAdminPaymentSetting();
            $order                 = $request->all();
            if(!empty($store->logo))
            {
                $logo = asset(Storage::url('uploads/store_logo/' . $store->logo));
            }
            else
            {
                $logo = asset(Storage::url('uploads/store_logo/logo.png'));
            }

            $skill               = new SkrillRequest();
            $skill->pay_to_email = $admin_payment_setting['skrill_email'];
            $skill->return_url   = route('skrill.callback') . '?transaction_id=' . MD5($request['transaction_id']);
            $skill->cancel_url   = route('skrill.callback');
            $skill->logo_url     = $logo;

            // create object instance of SkrillRequest
            $skill->transaction_id  = MD5($request['transaction_id']); // generate transaction id
            $skill->amount          = $price;
            $skill->currency        = env('CURRENCY');
            $skill->language        = 'EN';
            $skill->prepare_only    = '1';
            $skill->merchant_fields = 'site_name, customer_email';
            $skill->site_name       = $store->name;
            $skill->customer_email  = Auth::user()->email;

            // create object instance of SkrillClient
            $client = new SkrillClient($skill);
            $sid    = $client->generateSID(); //return SESSION ID

            // handle error
            $jsonSID = json_decode($sid);
            if($jsonSID != null && $jsonSID->code == "BAD_REQUEST")
            {
                return redirect()->back()->with('error', $jsonSID->message);
            }


            // do the payment
            $redirectUrl = $client->paymentRedirectUrl($sid); //return redirect url
            if($request['transaction_id'])
            {
                $data = [
                    'amount' => $price,
                    'trans_id' => MD5($request['transaction_id']),
                    'currency' => $store->currency_code,
                    'slug' => $store->slug,
                ];
                session()->put('skrill_data', $data);

            }

            return redirect($redirectUrl);


        }

    }

    public function skrillPlanGetPayment(Request $request)
    {
        $user                  = Auth::user();
        $store_id              = Auth::user()->current_store;
        $admin_payment_setting = Utility::getAdminPaymentSetting();
        $plan_id               = $request->ORDERID;
        $plan                  = Plan::find($plan_id);
        $setting = Utility::settings();

        if($plan)
        {
            if(session()->has('skrill_data'))
            {
                $get_data = session()->get('skrill_data');
                $orderID  = time();

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
                $planorder                 = new PlanOrder();
                $planorder->order_id       = $orderID;
                $planorder->name           = $user->name;
                $planorder->card_number    = '';
                $planorder->card_exp_month = '';
                $planorder->card_exp_year  = '';
                $planorder->plan_name      = $plan->name;
                $planorder->plan_id        = $plan->id;
                $planorder->price          = isset($get_data['amount']) ? $get_data['amount'] : 0;
                // $planorder->price_currency = env('CURRENCY');
                $planorder->price_currency = $setting['site_currency'];
                $planorder->txn_id         = $request->has('transaction_id') ? $request->transaction_id : '';;
                $planorder->payment_type   = __('Skrill');
                $planorder->payment_status = 'success';
                $planorder->receipt        = '';
                $planorder->user_id        = $user->id;
                $planorder->store_id       = $store_id;
                $planorder->save();

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

            session()->forget('mollie_payment_id');

        }
        else
        {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }

    //CoinGate
    public function coingatePaymentPrepare(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                               'plan_id' => 'required',
                               'total_price' => 'required',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $user    = Auth::user()->current_store;
        $store   = Store::where('id', $user)->first();
        $plan_id = decrypt($request->plan_id);
        $plan    = Plan::find($plan_id);
        $price   = $request->total_price;

        if($plan)
        {
            $admin_payment_setting = Utility::getAdminPaymentSetting();
            $order                 = $request->all();
            CoinGate::config(
                array(
                    'environment' => $admin_payment_setting['coingate_mode'],
                    // sandbox OR live
                    'auth_token' => $admin_payment_setting['coingate_auth_token'],
                    'curlopt_ssl_verifypeer' => FALSE
                    // default is false
                )
            );
            $post_params = array(
                'order_id' => time(),
                'price_amount' => $price,
                'price_currency' => env('CURRENCY'),
                'receive_currency' => env('CURRENCY'),
                'callback_url' => url('coingate-payment-plan') . '?plan_id=' . $plan->id . '&user_id=' . Auth::user()->id,
                'cancel_url' => route('plans.index'),
                'success_url' => url('coingate-payment-plan') . '?plan_id=' . $plan->id . '&user_id=' . Auth::user()->id,
                'title' => 'Order #' . time(),
            );

            $order = \CoinGate\Merchant\Order::create($post_params);
            if($order)
            {
                return redirect($order->payment_url);
            }
            else
            {
                return redirect()->back()->with('error', __('opps something wren wrong.'));
            }
        }
    }

    public function coingatePlanGetPayment(Request $request)
    {
        $user                  = Auth::user();
        $plan_id               = $request->plan_id;
        $store_id              = Auth::user()->current_store;
        $admin_payment_setting = Utility::getAdminPaymentSetting();
        $plan                  = Plan::find($plan_id);
        $setting      = Utility::settings();

        if($plan)
        {
            try
            {
                $orderID = time();
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

                $planorder                 = new PlanOrder();
                $planorder->order_id       = $orderID;
                $planorder->name           = $user->name;
                $planorder->card_number    = '';
                $planorder->card_exp_month = '';
                $planorder->card_exp_year  = '';
                $planorder->plan_name      = $plan->name;
                $planorder->plan_id        = $plan->id;
                $planorder->price          = $plan->price;
                // $planorder->price_currency = env('CURRENCY');
                $planorder->price_currency = $setting['site_currency'];
                $planorder->txn_id         = '-';
                $planorder->payment_type   = __('CoinGAte');
                $planorder->payment_status = 'success';
                $planorder->receipt        = '';
                $planorder->user_id        = $user->id;
                $planorder->store_id       = $store_id;
                $planorder->save();

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
