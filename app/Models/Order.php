<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = ['order_number', 'user_id', 'subtotal', 'shipping_id', 'coupon_id', ' reward-money', 'total', 'quantity', 'status', 'name', 'email', 'phone', 'post_code', 'address'];

    public function orderItems()
    {
       return $this->hasMany('App\Models\OrderItem','order_id')->with('product');
    }

    public static function getAllOrdersByUser($user_id)
    {
        return Order::where('user_id', $user_id)->get();
    }

    public static function getReturnedOrdersByUser($user_id)
    {
        return Order::where('status', '>', '4')->get();
    }
}
