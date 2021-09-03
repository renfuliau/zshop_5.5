<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RewardMoneyHistory;
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
            return $next($request);
        });
        $this->product = $product;
        $this->categories = Category::getAllParentCategory();
    }

    public function cart()
    {
        $user = Auth()->user();
        $carts = \Cart::session($user->id)->getContent()->sort();
        // dd($carts);
        $total = \Cart::session($user->id)->getTotal();
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
        $coupon1 = $this->checkCoupon1($total);
        $coupon2 = $this->checkCoupon2($total);

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
            return response(__('response-no-login'));
        }
        $user_id = Auth::user()->id;
        $product_id = $request->product_id;
        $product = Product::with('productImg')->find($product_id);
        $cart_item = \Cart::session($user_id)->get($product_id);
        if (!empty($cart_item && $cart_item->quantity > $product->stock)) {
            return response(['status' => 0, 'message' => __('frontend.response-cart-out-of-stock'), 'qty' => $cart_item->quantity]);
        }
        \Cart::session($user_id)->add($product_id, $product->title, $product->special_price, 1, array(
            'photo' => $product->productImg[0]->filepath
        ));
        $cartTotalQuantity = \Cart::session($user_id)->getTotalQuantity();
        return response(['status' => 0, 'message' => __('frontend.response-cart-success'), 'qty' => $cartTotalQuantity]);
    }

    public function removeItem(Request $request)
    {
        $product_id = $request->product_id;
        \Cart::session(Auth::user()->id)->remove($product_id);
        return __('frontend.response-cart-remove');
    }

    public function changeProductQty(Request $request)
    {
        $user_id = Auth::user()->id;
        $product_id = $request->product_id;
        $new_qty = $request->new_qty;
        $coupon1_id = $request->coupon1_id;
        $coupon2_id = $request->coupon2_id;
        \Cart::session($user_id)->update($product_id, array(
            'quantity' => array(
                'relative' => false,
                'value' => $new_qty,
            ),
        ));
        $cart_item = \Cart::session($user_id)->get($product_id);
        $total_qty = \Cart::session($user_id)->getTotalQuantity($user_id);
        $total = \Cart::session($user_id)->getTotal();
        $response = [
            'total_qty' => $total_qty,
            'total' => $total,
            'qty' => $cart_item->quantity,
        ];
        $coupon1 = $this->checkCoupon1($total);
        if ($coupon1->id != $coupon1_id) {
            $response['coupon1'] = [
                'coupon_id' => $coupon1->id,
                'coupon_amount' => $coupon1->coupon_amount,
                'coupon_title' => $coupon1->name
            ];
        }
        $coupon2 = $this->checkCoupon2($total);
        if ($coupon2->id != $coupon2_id) {
            $response['coupon2'] = [
                'coupon_id' => $coupon2->id,
                'coupon_amount' => $coupon2->coupon_amount,
                'coupon_title' => $coupon2->name
            ];
        }
        // dd($response['total_qty']);
        // dd($response['coupon1']);
        return response($response);
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

    public function checkout(Request $request)
    {
        $user_id = Auth::user()->id;
        $data = $request->all();
        $carts = \Cart::session($user_id)->getContent()->sort();
        $total = \Cart::session($user_id)->getTotal();
        $coupon = Coupon::find($data['coupon_id']);
        // dd($coupon);
        $reward_money = $data['reward_money'];
        // dd($reward_money);
        return view('cart.checkout', compact('carts', 'total', 'coupon', 'reward_money'))
            ->with('categories', $this->categories);
    }

    public function checkoutStore(Request $request)
    {
        $this->validate($request, [
            'name' => 'string|required',
            'phone' => 'required',
            'post_code' => 'string|required',
            'address' => 'string|required',
            'reward_money' => 'required',
        ]);

        $user_id = Auth::user()->id;
        $carts = \Cart::session($user_id)->getContent()->sort();
        foreach ($carts as $cart) {
            $product = Product::find($cart->id);
            if ($cart->quantity > $product['stock']) {
                request()->session()->flash('error', $product['title'] . __('frontend.response-cart-out-of-stock'));
                return redirect()->route('cart');
            }
        }
        $subtotal = intval(\Cart::session($user_id)->getTotal());
        $reward_money = $request->reward_money;
        $total = $subtotal - $reward_money;
        $order = new Order();
        $order_data = $request->all();
        $order_data['order_number'] = date("Ymd") . time();
        $order_data['user_id'] = $user_id;
        $order_data['subtotal'] = $subtotal;
        if ($request->coupon_id) {
            $order_data['coupon_id'] = $request->coupon_id;
            $coupon = Coupon::find($request->coupon_id);
            if ($coupon['coupon_type'] == 1) {
                $total -= $coupon['coupon_amount'];
            }
        }
        $order_data['reward_money'] = $request->reward_money;
        $order_data['total'] = $total;
        $order_data['quantity'] = \Cart::session($user_id)->getTotalQuantity();
        $order_data['status'] = 1;
        // dd($order_data);
        $order->fill($order_data);
        // dd($order);
        $order_is_build = $order->save();

        foreach ($carts as $cart) {
            $order_item = new OrderItem();
            $order_item->order_id = $order->id;
            $order_item->product_id = $cart->id;
            $order_item->quantity = $cart->quantity;
            $order_item->price = $cart->price;
            $order_item->save();

            $product = Product::find($cart->id);
            $product->stock -= $cart->quantity;
            $product->save();
        }

        if ($reward_money > 0) {
            $user = User::find($user_id);
            $user->reward_money -= $reward_money;
            $user->save();

            $reward_money_history = new RewardMoneyHistory();
            $reward_money_history->user_id = $user_id;
            $reward_money_history->reward_item = $order->order_number . '結帳使用';
            $reward_money_history->amount = $reward_money * (-1);
            $reward_money_history->total = $user->reward_money;
            $reward_money_history->save();
        }

        \Cart::session($user_id)->clear();

        return view('cart.order-confirm', compact('order'))
            ->with('categories', $this->categories);;
    }

    public function checkCoupon1($total)
    {
        return Coupon::where([
            ['coupon_line', '=', Coupon::where([
                ['coupon_line', '<', $total],
                ['coupon_type', '=', 1],
                ['status', '=', 'active'],
            ])->get()->max('coupon_line')],
            ['coupon_type', '=', 1],
        ])->first();
    }

    public function checkCoupon2($total)
    {
        return Coupon::where([
            ['coupon_line', '=', Coupon::where([
                ['coupon_line', '<', $total],
                ['coupon_type', '=', 2],
                ['status', '=', 'active'],
            ])->get()->max('coupon_line')],
            ['coupon_type', '=', 2],
        ])->first();
    }
}
