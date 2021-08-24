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
        $carts = \Cart::session($user->id)->getContent()->sort();
        // dd($carts);
        $total = \Cart::session($user->id)->getTotal();
        // $carts = Cart::getCartByUser($user->id);
        // $total = 0;
        // $products = $cart->product;
        if ($total == 0) {
            $carts = null;
            return view('cart.cart', compact('carts', 'total'))
                ->with('categories', $this->categories);
        }

        foreach ($carts as $key => $value) {
            $product_stock = Product::getStock($value->id)->stock;
            $attributes = $value->attributes;
            $attributes['stock'] = $product_stock;
        }

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
        $user_id = Auth::user()->id;
        $product_id = $request->product_id;
        $product = Product::find($product_id);
        $cart_item = \Cart::session($user_id)->get($product_id);
        if (!empty($cart_item && $cart_item->quantity >= $product->stock)) {
            return response(['status' => 0, 'message' => '超出該商品庫存', 'qty' => $cart_item->quantity]);
        }
        \Cart::session($user_id)->add($product_id, $product->title, $product->special_price, 1, array(
            'photos' => $product->photo
        ));
        $cartTotalQuantity = \Cart::session($user_id)->getTotalQuantity();
        return response(['status' => 0, 'message' => '成功加入購物車', 'qty' => $cartTotalQuantity]);
    }

    // public function addToCart(Request $request)
    // {
    //     if (empty(Auth::user()->id)) {
    //         return response('請先登入');
    //     }

    //     $cart_item = Cart::getCartItem($request->user_id, $request->product_id);
    //     // return response(!empty($cart_item));


    //     if (!empty($cart_item)) {
    //         $product_stock = Product::getStock($request->product_id);
    //         // return response(['status' => 1, 'message' => $product_stock['stock']]);
    //         $new_qty = $cart_item->quantity + 1;
    //         // return response(['status' => 1, 'message' => $new_qty]);
    //         if ($new_qty > $product_stock['stock']) {
    //             return response(['status' => 1, 'message' => '加入購物車失敗，超過商品現有庫存量']);
    //         }
    //         $cart_item->quantity = $new_qty;
    //         $cart_item->save();
    //         // return response('成功加入購物車');
    //         return response(['status' => 0, 'message' => '成功加入購物車']);
    //     }

    //     $cart = new Cart;
    //     $cart->user_id = $request->user_id;
    //     $cart->product_id = $request->product_id;
    //     $cart->quantity = 1;
    //     $cart->save();
    //     // return json_encode(['status'=>'success']);
    //     // return response('成功加入購物車');
    //     return response(['status' => 0, 'message' => '成功加入購物車']);
    // }

    // public function removeCart(Request $request)
    // {
    //     $cart_item = Cart::getCartItem($request->user_id, $request->product_id);
    //     if ($cart_item->delete()) {
    //         return response('已移出購物車');
    //     }
    //     return response('錯誤！');
    // }

    public function removeItem(Request $request)
    {
        $product_id = $request->product_id;
        \Cart::session(Auth::user()->id)->remove($product_id);
        return "該商品已移出購物車";
    }

    public function changeProductQty(Request $request)
    {
        $user_id = Auth::user()->id;
        $product_id = $request->product_id;
        $new_qty = $request->new_qty;

        \Cart::session($user_id)->update($product_id, array(
            'quantity' => array(
                'relative' => false,
                'value' => $new_qty,
            ),
        ));
        $cart_item = \Cart::session($user_id)->get($product_id);
        $total_qty = \Cart::session($user_id)->getTotalQuantity($user_id);
        $total = \Cart::session($user_id)->getTotal();
        // dd($total);
        return response(['total_qty' => $total_qty, 'total' => $total, 'qty' => $cart_item->quantity]);
    }

    public function changeRewardMoney(Request $request)
    {
        $user_id = Auth::user()->id;
        $reward_money = $request->reward_money;
        $reward_money_condition = new \Darryldecode\Cart\CartCondition(array(
            'user' => $user_id,
            'name' => 'reward_money',
            'type' => 'reward_money',
            'value' => $reward_money
        ));
        \Cart::session($user_id)->condition($reward_money_condition);
        
        // $cart_conditions = \Cart::getConditions();
        // foreach ($cart_conditions as $condition) {
        //     $condition->getValue();
        //     dd($condition->getValue());
        // }
        return response("success");
    }

    public function changeCoupon(Request $request)
    {
        $user_id = Auth::user()->id;
        $coupon_id = $request->coupon_id;
        $coupon = Coupon::find($coupon_id);
        $coupon_condition = new \Darryldecode\Cart\CartCondition(array(
            'user' => $user_id,
            'name' => 'coupon',
            'type' => $coupon->coupon_type,
            'value' => $coupon->coupon_amount
        ));
        \Cart::session($user_id)->condition($coupon_condition);
        
        $cart_conditions = \Cart::getConditions();
        $c_group = array();
        foreach ($cart_conditions as $condition) {
            array_push($c_group, $condition);
        }
        dd($condition);
        return response("success");
    }

    public function checkout()
    {
        $user_id = Auth::user()->id;
        $carts = \Cart::session($user_id)->getContent()->sort();
        $total = \Cart::session($user_id)->getTotal();
        return view('cart.checkout', compact('carts', 'total'))
        ->with('categories', $this->categories);
    }
}
