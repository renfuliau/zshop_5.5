<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = ['order_number', 'user_id', 'subtotal', 'shipping_id', 'coupon_id', 'reward_money', 'total', 'quantity', 'status', 'name', 'email', 'phone', 'post_code', 'address'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function orderItems()
    {
        return $this->hasMany('App\Models\OrderItem','order_id')->with('product');
    }

    public function coupon()
    {
        return $this->hasOne('App\Models\Coupon', 'id', 'coupon_id');
    }

    public function messages()
    {
        return $this->hasMany('App\Models\Message','order_id');
    }

    public static function getAllOrdersByUser($user_id)
    {
        return Order::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();
    }

    public static function getReturnedOrdersByUser($user_id)
    {
        return Order::with(['orderItems' => function($query){

            $query->where('is_return', 1);
        
        }])->where('status', '>', '4')->where('user_id', $user_id)->orderBy('id', 'DESC')->get();
    }

    public static function getOrderWithReturnedItems($id)
    {
        return Order::with(['orderItems' => function($query){

            $query->where('is_return', 1);
        
        }])->find($id);
    }

    public static function getReturnOrderTotal($id)
    {
        $order = Order::with(['orderItems' => function($query){

            $query->where('is_return', 1);
        
        }])->find($id);

        $return_total = 0;
        foreach ($order->orderItems as $return_order_item) {
            $item_subtotal = $return_order_item['price'] * $return_order_item['quantity'];
            $return_total += $item_subtotal;
        }

        return $return_total;
    }
}
