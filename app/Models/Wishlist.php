<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'wishlists';

    protected $fillable = ['product_id', 'user_id'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }   

    public static function checkItem($user_id, $product_id)
    {
        $item = Wishlist::where('user_id', $user_id)->where('product_id', $product_id)->first();
        if (!empty($item)) {
            return true;
        }
        return false;
    }
    
    public static function getWishlistByUser($user_id)
    {
        return Wishlist::with('product')->where('user_id', $user_id)->get();
    }

    public static function getFirstWishlist($user_id, $product_id)
    {
        return Wishlist::where('product_id', $product_id)->where('user_id', $user_id)->first();
    }
}
