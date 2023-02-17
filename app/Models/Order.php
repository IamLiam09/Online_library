<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_id',
        'name',
        'card_number',
        'card_exp_month',
        'card_exp_year',
        'student_id',
        'course',
        'price',
        'coupon',
        'coupon_json',
        'discount_price',
        'plan_name',
        'plan_id',
        'price_currency',
        'payment_type',
        'payment_status',
        'receipt',
        'store_id',
        'user_id',
        'subscription_id',
        'payer_id',
        'payment_frequency',
        'txn_id',
        'created_by',
    ];


    public static function total_orders()
    {
        return Order::count();
    }

    public static function total_orders_price()
    {
        return Order::sum('price');
    }

    public function total_coupon_used()
    {
        return $this->hasOne('App\Models\UserCoupon', 'order', 'order_id');
    }
}
