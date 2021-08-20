<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = ['order_number', 'user_id', 'sub_total', 'shipping_id', 'coupon_id', ' reward-money', 'total_amount', 'quantity', 'status', 'name', 'email', 'phone', 'post_code', 'address'];

    public static function getAllOrdersByUser($user_id)
    {
        return Order::where('user_id', $user_id)->get();
    }
}
