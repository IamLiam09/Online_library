<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Order;
use App\Exports\OrdersExport;
use App\Models\Product;
use App\Models\Store;
use App\Models\Student;
use App\Models\UserDetail;
use App\Models\UserStore;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->type == 'super admin')
        {
            $user  = \Auth::user();
            $store = Store::where('id', $user->current_store)->first();

            $orders = Order::orderBy('id', 'DESC')->get();
        }
        else
        {
            $user  = \Auth::user();
            $store = Store::where('id', $user->current_store)->first();

            $orders = Order::orderBy('id', 'DESC')->where('store_id', $store->id)->get();
        }

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Order $order
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $store = Store::where('id', $order->store_id)->first();        

        $order_products = json_decode($order->course);
        $sub_total = 0;                    
        if(!empty($order_products))
        {
            foreach($order_products as $product)
            {
                $totalprice = $product->price;
                $sub_total  += $totalprice;
            }
        }
        if(!empty($store->currency)){
            $currency = $store->currency;
        }else{
            $currency = '$';
        }

        if($order->discount_price == 'undefined'){
            $discount_price = 0;
        }else{
            $discount_price = str_replace('-' . $currency, '', $order->discount_price);
        }
        
        if(!empty($discount_price))
        {
            $grand_total = $sub_total - $discount_price;
        }
        else
        {
            $discount_price = 0;
            $grand_total    = $sub_total;
        }
        $student_data = Student::where('id', $order->student_id)->first();
        $order_id     = Crypt::encrypt($order->id);
        

        return view('orders.view', compact('student_data', 'discount_price', 'order', 'store', 'grand_total', 'order_products', 'sub_total', 'order_id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Order $order
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Order $order
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $order['status'] = $request->delivered;
        $order->update();

        return response()->json(
            [
                'success' => __('Successfully Updated!'),
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Order $order
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->back()->with(
            'success', __('Order Deleted!')
        );
    }

    public function receipt($id)
    {
        $order = Order::find($id);
        $store = Store::where('id', $order->user_id)->first();

        if(!empty($order->shipping_data))
        {
            $shipping_data = json_decode($order->shipping_data);
            $location_data = Location::where('id', $shipping_data->location_id)->first();
        }
        else
        {
            $shipping_data = '';
            $location_data = '';
        }

        $user_details = UserDetail::where('id', $order->user_address_id)->first();

        $order_products = json_decode($order->product);
        $sub_total      = 0;
        if(!empty($order_products))
        {
            $grand_total = 0;
            $total_taxs  = 0;
            foreach($order_products as $k => $product)
            {
                if(isset($product->variant_id) && $product->variant_id != 0)
                {
                    if(!empty($product->tax))
                    {
                        foreach($product->tax as $tax)
                        {
                            $sub_tax    = ($product->variant_price * $product->quantity * $tax->tax) / 100;
                            $total_taxs += $sub_tax;
                        }
                    }
                    $totalprice  = $product->variant_price * $product->quantity + $total_taxs;
                    $subtotal    = $product->variant_price * $product->quantity;
                    $sub_total   += $subtotal;
                    $grand_total += $totalprice;
                }
                else
                {
                    if(!empty($product->tax))
                    {
                        foreach($product->tax as $tax)
                        {
                            $sub_tax    = ($product->price * $product->quantity * $tax->tax) / 100;
                            $total_taxs += $sub_tax;
                        }
                    }

                    $totalprice  = $product->price * $product->quantity + $total_taxs;
                    $subtotal    = $product->price * $product->quantity;
                    $sub_total   += $subtotal;
                    $grand_total += $totalprice;
                }
            }
        }
        $discount_value = 0;
        $plan_price     = 0;
        if(!empty($order->coupon_json))
        {
            $coupons = json_decode($order->coupon_json);
            if(!empty($coupons))
            {
                if($coupons->enable_flat == 'on')
                {
                    $discount_value = $coupons->flat_discount;
                }
                else
                {
                    $discount_value = ($subtotal / 100) * $coupons->discount;
                }
            }

            $plan_price = $subtotal - $discount_value;
        }

        $order_id = Crypt::encrypt($order->id);

        return view('orders.receipt', compact('order', 'store', 'grand_total', 'order_products', 'sub_total', 'total_taxs', 'user_details', 'order_id', 'shipping_data', 'location_data', 'discount_value', 'plan_price'));
    }

    public function export()
    {
        $name = 'order_' . date('Y-m-d i:h:s');
        $data = Excel::download(new OrdersExport(), $name . '.xlsx'); ob_end_clean();
        return $data;
    }

}
