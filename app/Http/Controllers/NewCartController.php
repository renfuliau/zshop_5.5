<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Darryldecode\Cart\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if (empty(Auth::user()->id)) {
            return response('請先登入');
        }
        $user_id = Auth::user()->id;
        $product_id = $request->product_id;
        $product = Product::find($product_id);
        $cart_item = \Cart::session($user_id)->get($product_id);
        if (! empty($cart_item && $cart_item->quantity >= $product->stock)) {
            return response(['status' => 0, 'message' => '超出該商品庫存', 'qty' => $cart_item->quantity]);
        }
        \Cart::session($user_id)->add($product_id, $product->title, $product->special_price, 1, array());
        $cartTotalQuantity = \Cart::getTotalQuantity();
        return response(['status' => 0, 'message' => '成功加入購物車', 'qty' => $cartTotalQuantity]);
    }

    public function cart()
    {
        $content = \Cart::getContent()->sort(); //取得購物車產品後排序
        // dd($content);
        $total = \Cart::getTotal();

        return view("cart.cart", compact('content', 'total'))
        ->with('categories', $this->categories);
    }

    public function changeProductQty(Request $request)
    {
        $product_id = $request->product_id;
        $new_qty = $request->new_qty;

        \Cart::update($product_id, array(
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

        \Cart::session(Auth::user()->id)->remove($product_id);

        return "該商品已移出購物車";

    }

    public function cart_check_out()
    {
        $content = \Cart::getContent()->sort(); //取得購物車產品後排序
        $total = \Cart::getTotal();

        return view("front.cart_check_out", compact('content', 'total'));
    }
}
