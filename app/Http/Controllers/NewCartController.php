<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Darryldecode\Cart\Cart;
use Illuminate\Http\Request;

class NewCartController extends Controller
{
    protected $product;
    protected $categories;

    public function __construct(Product $product)
    {
        $this->product = $product;
        $this->categories = Category::getAllParentCategory();
    }

    public function addToCart(Request $request)
    {
        $product_id = $request->product_id;
        $product = Product::find($product_id);
        Cart::add($product_id, $product->title, $product->price, 1, array());

        $cartTotalQuantity = Cart::getTotalQuantity();
        return $cartTotalQuantity;
    }

    public function cart()
    {
        $content = Cart::getContent()->sort(); //取得購物車產品後排序
        $total = Cart::getTotal();

        return view("front.cart", compact('content', 'total'));
    }

    public function changeProductQty(Request $request)
    {
        $product_id = $request->product_id;
        $new_qty = $request->new_qty;

        Cart::update($product_id, array(
            'quantity' => array(
                'relative' => false,
                'value' => $new_qty,
            ),
        ));

        return "suceess";
    }

    public function remove_item(Request $request)
    {
        $product_id = $request->product_id;

        Cart::remove($product_id);

        return "success";

    }

    public function cart_check_out()
    {
        $content = Cart::getContent()->sort(); //取得購物車產品後排序
        $total = Cart::getTotal();

        return view("front.cart_check_out", compact('content', 'total'));
    }
}
