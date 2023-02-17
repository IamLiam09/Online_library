<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Store;
use App\Models\Utility;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user  = \Auth::user();
        // dd($user);
        $data = Order::orderBy('id', 'DESC')->where('store_id', $user->current_store)->get();
     
        foreach($data as $k => $order)
        {
            unset( $order->card_number, $order->card_exp_month, $order->card_exp_year, $order->student_id, $order->course, $order->shipping_data, $order->coupon, $order->coupon_json, $order->discount_price, $order->plan_name, $order->plan_id, $order->price_currency, $order->txn_id, $order->payment_type, $order->payment_status, $order->receipt, $order->store_id,$order->user_id, $order->subscription_id, $order->payer_id, $order->payment_frequency, $order->created_by);

            $store=Store::find($order->store_id);
            $store_id=isset($store)?$store->name:'';

            $data[$k]["store_id"]=$store_id;
       
            $data[$k]["price"]     = Utility::priceFormat($order->price);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            "ID",
            "Order Id",
            "Name",
            "Price",
            "created_at",
            "updated_at",            
        ];
    }
}
