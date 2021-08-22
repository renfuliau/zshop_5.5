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
        // $products = $cart->product;
        // dd($carts);
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

    public function checkout()
    {
        $category = Category::getAllParentWithChild();
        return view('zshop.layouts.pages.checkout')->with('category', $category);
    }

    // public function checkoutStore(Request $request)
    // {
    //     $this->validate($request, [
    //         'first_name' => 'string|required',
    //         'phone' => 'required',
    //         'post_code' => 'string|required',
    //         'address1' => 'string|required',
    //     ]);
    //     // return $request->all();

    //     if (empty(Cart::where('user_id', auth()->user()->id)->where('order_id', null)->first())) {
    //         request()->session()->flash('error', '購物車為空，請確認購物車商品!');
    //         return back();
    //     }
    //     $order = new Order();
    //     $order_data = $request->all();
    //     $order_data['order_number'] = 'ORD-' . strtoupper(Str::random(10));
    //     $order_data['user_id'] = $request->user()->id;
    //     $order_data['shipping_id'] = $request->shipping;
    //     $shipping = Shipping::where('id', $order_data['shipping_id'])->pluck('price');
    //     // return session('coupon')['value'];
    //     $order_data['sub_total'] = Helper::totalCartPrice();
    //     $order_data['quantity'] = Helper::cartCount();
    //     if (session('coupon')) {
    //         $order_data['coupon'] = session('coupon')['value'];
    //     }
    //     if ($request->shipping) {
    //         if (session('coupon')) {
    //             $order_data['total_amount'] = Helper::totalCartPrice() + $shipping[0] - session('coupon')['value'];
    //         } else {
    //             $order_data['total_amount'] = Helper::totalCartPrice() + $shipping[0];
    //         }
    //     } else {
    //         if (session('coupon')) {
    //             $order_data['total_amount'] = Helper::totalCartPrice() - session('coupon')['value'];
    //         } else {
    //             $order_data['total_amount'] = Helper::totalCartPrice();
    //         }
    //     }
    //     // return $order_data['total_amount'];
    //     $order_data['status'] = "new";
    //     if (request('payment_method') == 'paypal') {
    //         $order_data['payment_method'] = 'paypal';
    //         $order_data['payment_status'] = 'paid';
    //     } else {
    //         $order_data['payment_method'] = 'cod';
    //         $order_data['payment_status'] = 'Unpaid';
    //     }
    //     $order->fill($order_data);
    //     $status = $order->save();
    //     if ($order)
    //         // dd($order->id);
    //         $users = User::where('role', 'admin')->first();
    //     $details = [
    //         'title' => 'New order created',
    //         'actionURL' => route('order.show', $order->id),
    //         'fas' => 'fa-file-alt'
    //     ];
    //     Notification::send($users, new StatusNotification($details));
    //     if (request('payment_method') == 'paypal') {
    //         return redirect()->route('payment')->with(['id' => $order->id]);
    //     } else {
    //         session()->forget('cart');
    //         session()->forget('coupon');
    //     }
    //     Cart::where('user_id', auth()->user()->id)->where('order_id', null)->update(['order_id' => $order->id]);

    //     // dd($users);
    //     request()->session()->flash('success', '訂單已成功送出，感謝您的支持');
    //     return redirect()->route('home');
    // }

    // public function addToCart(Request $request)
    // {
    //     // dd($request->all());
    //     if (empty($request->slug)) {
    //         request()->session()->flash('error', '商品不存在，請聯繫客服！');
    //         return back();
    //     }
    //     $product = Product::where('slug', $request->slug)->first();
    //     // return $product;
    //     if (empty($product)) {
    //         request()->session()->flash('error', '商品不存在，請聯繫客服！');
    //         return back();
    //     }

    //     $already_cart = Cart::where('user_id', auth()->user()->id)->where('order_id', null)->where('product_id', $product->id)->first();
    //     // return $already_cart;
    //     if ($already_cart) {
    //         // dd($already_cart);
    //         $already_cart->quantity = $already_cart->quantity + 1;
    //         $already_cart->amount = ($product->price * (100 - $product->discount) * 0.01) + $already_cart->amount;
    //         // return $already_cart->quantity;
    //         if ($already_cart->product->stock < $already_cart->quantity || $already_cart->product->stock <= 0) return back()->with('error', 'Stock not sufficient!.');
    //         $already_cart->save();
    //     } else {

    //         $cart = new Cart;
    //         $cart->user_id = auth()->user()->id;
    //         $cart->product_id = $product->id;
    //         $cart->price = ($product->price - ($product->price * $product->discount) / 100);
    //         $cart->quantity = 1;
    //         $cart->amount = $cart->price * $cart->quantity;
    //         if ($cart->product->stock < $cart->quantity || $cart->product->stock <= 0) return back()->with('error', 'Stock not sufficient!.');
    //         $cart->save();
    //         $wishlist = Wishlist::where('user_id', auth()->user()->id)->where('cart_id', null)->update(['cart_id' => $cart->id]);
    //     }
    //     request()->session()->flash('success', '商品成功加入購物車');
    //     return back();
    // }

    public function addToCart(Request $request)
    {
        if (empty(Auth::user()->id)) {
            return response('請先登入');
        }

        $cart_item = Cart::getCartItem($request->user_id, $request->product_id);
        if (!empty($cart_item)) {
            $cart_item->quantity += 1;
            $cart_item->save();
            return response('成功加入購物車');
        }

        $cart = new Cart;
        $cart->user_id = $request->user_id;
        $cart->product_id = $request->product_id;
        $cart->quantity = 1;
        $cart->save();
        // return json_encode(['status'=>'success']);
        return response('成功加入購物車');
    }

    public function singleAddToCart(Request $request)
    {
        dd($request);
        $request->validate([
            'slug' => 'required',
            'quant' => 'required',
        ]);
        // dd($request->quant[1]);

        $product = Product::where('slug', $request->slug)->first();
        if ($product->stock < $request->quant[1]) {
            return back()->with('error', 'Out of stock, You can add other products.');
        }
        if (($request->quant[1] < 1) || empty($product)) {
            request()->session()->flash('error', 'Invalid Products');
            return back();
        }

        $already_cart = Cart::where('user_id', auth()->user()->id)->where('order_id', null)->where('product_id', $product->id)->first();

        // return $already_cart;

        if ($already_cart) {
            $already_cart->quantity = $already_cart->quantity + $request->quant[1];
            // $already_cart->price = ($product->price * $request->quant[1]) + $already_cart->price ;
            $already_cart->amount = ($product->price * $request->quant[1]) + $already_cart->amount;

            if ($already_cart->product->stock < $already_cart->quantity || $already_cart->product->stock <= 0) {
                return back()->with('error', 'Stock not sufficient!.');
            }

            $already_cart->save();
        } else {

            $cart = new Cart;
            $cart->user_id = auth()->user()->id;
            $cart->product_id = $product->id;
            $cart->price = ($product->price - ($product->price * $product->discount) / 100);
            $cart->quantity = $request->quant[1];
            $cart->amount = ($product->price * $request->quant[1]);
            if ($cart->product->stock < $cart->quantity || $cart->product->stock <= 0) {
                return back()->with('error', 'Stock not sufficient!.');
            }

            // return $cart;
            $cart->save();
        }
        request()->session()->flash('success', 'Product successfully added to cart.');
        return back();
    }

    public function removeCart(Request $request)
    {
        $cart_item = Cart::getCartItem($request->user_id, $request->product_id);
        if ($cart_item->delete()) {
            return response('已移出購物車');
        }
        return response('錯誤！');
    }
}
