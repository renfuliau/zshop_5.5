<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnOrder extends Model
{
    protected $table = 'return_orders';

    protected $fillable = ['user_id', 'order_id', 'is_refund'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function orderItems()
    {
        return $this->hasMany('App\Models\OrderItem', 'order_id')->where('is_return', 1)->with('product');
    }
}
