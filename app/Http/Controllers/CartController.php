<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $product;
    protected $user;
    protected $cart_total_qty;
    protected $categories;

    public function __construct(Product $product)
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            if (!empty($this->user)) {
                $this->cart_total_qty = Cart::getTotalQty(Auth::user()->id);
            }
            // dd($this->cart_total_qty);
            return $next($request);
        });
        $this->product = $product;
        $this->categories = Category::getAllParentCategory();
    }

    public function cart()
    {
        $user = Auth()->user();
        // dd($user);
        $carts = Cart::getCartByUser($user->id);
        $total = 0;
        // $products = $cart->product;
        if ($carts->isEmpty()) {
            $carts = null;
            return view('cart.cart')
                ->with('categories', $this->categories)
                ->with('cart_total_qty', $this->cart_total_qty)
                ->with('carts', $carts)
                ->with('total', $total);
        }
        if (empty($carts)) {
            // dd($carts);
            return view('cart.cart')
                ->with('categories', $this->categories)
                ->with('cart_total_qty', $this->cart_total_qty);
        }

        $total = 0;
        foreach ($carts as $cart) {
            $total += ($cart->quantity * $cart->product->special_price);
        }
        // dd($total);

        $coupon1 = Coupon::where([
            ['coupon_line', '=', Coupon::where([
                ['coupon_line', '<', $total],
                ['coupon_type', '=', 1],
            ])->get()->max('coupon_line')],
            ['coupon_type', '=', 1],
        ])->first();
        // dd($coupon1->coupon_amount);

        $coupon2 = Coupon::where([
            ['coupon_line', '=', Coupon::where([
                ['coupon_line', '<', $total],
                ['coupon_type', '=', 2],
            ])->get()->max('coupon_line')],
            ['coupon_type', '=', 2],
        ])->first();
        // dd($coupon2);

        $user_info = User::findOrFail($user->id);
        // dd($user_info);

        return view('cart.cart')
            ->with('categories', $this->categories)
            ->with('cart_total_qty', $this->cart_total_qty)
            ->with('carts', $carts)
            ->with('total', $total)
            ->with('coupon1', $coupon1)
            ->with('coupon2', $coupon2)
            ->with('user_info', $user_info);
    }

    public function addToCart(Request $request)
    {
        if (empty(Auth::user()->id)) {
            return response('請先登入');
        }

        $cart_item = Cart::getCartItem($request->user_id, $request->product_id);
        // return response(!empty($cart_item));
        
        
        if (!empty($cart_item)) {
            $product_stock = Product::getStock($request->product_id);
            // return response(['status' => 1, 'message' => $product_stock['stock']]);
            $new_qty = $cart_item->quantity + 1;
            // return response(['status' => 1, 'message' => $new_qty]);
            if ($new_qty > $product_stock['stock']) {
                return response(['status' => 1, 'message' => '加入購物車失敗，超過商品現有庫存量']);
            }
            $cart_item->quantity = $new_qty;
            $cart_item->save();
            // return response('成功加入購物車');
            return response(['status' => 0, 'message' => '成功加入購物車']);
        }

        $cart = new Cart;
        $cart->user_id = $request->user_id;
        $cart->product_id = $request->product_id;
        $cart->quantity = 1;
        $cart->save();
        // return json_encode(['status'=>'success']);
        // return response('成功加入購物車');
        return response(['status' => 0, 'message' => '成功加入購物車']);
    }

    public function removeCart(Request $request)
    {
        $cart_item = Cart::getCartItem($request->user_id, $request->product_id);
        if ($cart_item->delete()) {
            return response('已移出購物車');
        }
        return response('錯誤！');
    }
    
    public function checkout()
    {
        $category = Category::getAllParentWithChild();
        return view('zshop.layouts.pages.checkout')->with('category', $category);
    }

}
