<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';

    protected $fillable = ['product_id', 'user_id', 'quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function getCartByUser($user_id)
    {
        return Cart::with('product')->where('user_id', $user_id)->get();
    }

    public static function getTotalQty($user_id)
    {
        return Cart::where('user_id', $user_id)->sum('quantity');
    }

    public static function getCartItem($user_id, $product_id)
    {
        return Cart::where('user_id', $user_id)->where('product_id', $product_id)->first();
    }
}
