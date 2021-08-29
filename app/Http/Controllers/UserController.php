<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Message;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RewardMoneyHistory;
use App\Models\UserLevel;
use App\Models\Wishlist;
use App\Rules\MatchOldPassword;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $user;
    protected $categories;
    protected $order_status_array;
    protected $order_status_array_en;
    protected $title;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            if (!empty($this->user)) {
                $this->cart_total_qty = Cart::getTotalQty(Auth::user()->id);
            }
            return $next($request);
        });
        $this->categories = Category::getAllParentCategory();
        $this->order_status_array = [
            0 => '訂單取消',
            1 => '訂單處理中',
            2 => '訂單已確認',
            3 => '出貨中',
            4 => '訂單完成',
            5 => '退貨處理中',
            6 => '退貨完成',
        ];

        $this->order_status_array_en = [
            0 => 'Canceled',
            1 => 'Processing',
            2 => 'Confirmation',
            3 => 'Shipped',
            4 => 'Complete',
            5 => 'Return Processing',
            6 => 'Returned',
        ];

    }

    public function profile()
    {
        // dd(Auth()->user());
        $profile = Auth()->user();
        // dd($profile->user_level_id);
        $user_level = UserLevel::getUserLevelName($profile->user_level_id);
        // dd($user_level);
        // return $profile;
        return view('user.profile')
            ->with('categories', $this->categories)
            ->with('profile', $profile)
            ->with('user_level', $user_level);
    }

    public function profileUpdate(Request $request, $id)
    {
        // return $request->all();
        $user = User::findOrFail($id);
        $data = $request->all();
        // dd($data);
        $status = $user->fill($data)->save();
        if ($status) {
            request()->session()->flash('success', '個人資料已更新');
        } else {
            request()->session()->flash('error', '發生錯誤，請稍後再試!');
        }
        return redirect()->back();
    }

    public function changePassword()
    {
        $profile = Auth()->user();
        return view('user.change-password')
            ->with('categories', $this->categories)
            ->with('profile', $profile);
        // return view('user.change-password');
    }
    public function changPasswordStore(Request $request)
    {
        $request->validate(
            [
                'current_password' => ['required', new MatchOldPassword],
                'new_password' => ['required'],
                'new_confirm_password' => ['same:new_password'],
            ],
            [
                'same' => __('frontend.user-profile-reset-fail'),
            ]
        );

        User::find(auth()->user()->id)->update(['password' => Hash::make($request->new_password)]);

        return redirect()->route('user-profile')->with('success', __('frontend.user-profile-reset-success'));
    }

    public function rewardMoney()
    {
        $profile = Auth()->user();
        $reward_money_history = RewardMoneyHistory::where('user_id', $profile->id)
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();
        // dd($reward_money_history);
        // return $profile;
        return view('user.reward-money')
            ->with('categories', $this->categories)
            ->with('profile', $profile)
            ->with('reward_money_history', $reward_money_history);
    }

    public function orders()
    {
        $profile = Auth()->user();
        // return $profile;
        $orders = Order::getAllOrdersByUser($profile->id);
        return view('user.orders')
            ->with('categories', $this->categories)
            ->with('order_status', $this->order_status_array)
            ->with('order_status_en', $this->order_status_array_en)
            ->with('profile', $profile)
            ->with('orders', $orders);
    }

    public function orderDetail($order_number)
    {
        $order = Order::with('orderItems')->with('coupon')->where('order_number', $order_number)->first();
        // dd($order['id']);
        $messages = Message::where('order_id', $order['id'])->orderBy('created_at', 'asc')->get();
        // dd($messages);
        return view('user.order-detail', compact('order', 'messages'))
            ->with('categories', $this->categories)
            ->with('order_status', $this->order_status_array);
    }

    public function orderMessageStore(Request $request)
    {
        $user_id = Auth::user()->id;
        $message = new Message();
        $message_data = $request->all();
        $message_data['user_id'] = $user_id;
        $message_data['subject'] = 1;
        $message->fill($message_data);
        // dd($message);
        $message->save();

        return response(['message' => __('frontend.user-order-qa-confirm')]);
    }

    public function orderReceived(Request $request)
    {
        $user_id = Auth::user()->id;
        $order_id = $request->order_id;
        $order = Order::with('coupon')->where('user_id', $user_id)->find($order_id);
        // dd($order);
        $order->status = 4;
        $order->save();

        $user = User::find($user_id);
        $user->total_shopping_amount += $order->total;
        $user->save();

        $user_level = UserLevel::orderBy('level_up_line', 'desc')->where('level_up_line', '<', $user->total_shopping_amount)->first();
        if ($user_level->id != $user->user_level_id) {
            $user->user_level_id = $user_level->id;
            $user->save();
            $response['level_up'] = __('frontend.user-order-level-up') . $user_level->name;
        }

        if ($order->coupon['coupon_type'] == 2) {
            $user->reward_money += $order->coupon['coupon_amount'];
            $user->save();
            $reward_money_history = new RewardMoneyHistory();
            $reward_money_history->user_id = $user_id;
            $reward_money_history->reward_item = $order->order_number . '訂單優惠，贈送購物金';
            $reward_money_history->amount = $order->coupon['coupon_amount'];
            $reward_money_history->total = $user->reward_money;
            $reward_money_history->save();
            $response['reward_money'] = __('frontend.response-coupon-reward') . "： $" . $order->coupon['coupon_amount'];
        }
        $response['message'] = __('frontend.response-received');
        // dd($response);
        return response($response);
    }

    public function orderCancel(Request $request)
    {
        $user_id = Auth::user()->id;
        $order_id = $request->order_id;
        $order = Order::with('orderItems')->where('user_id', $user_id)->find($order_id);
        $order->status = 0;
        // dd($order->orderItems);
        $order->save();
        foreach ($order->orderItems as $order_item) {
            $order_item->product['stock'] += $order_item->quantity;
            $order_item->product->save();
            // dd($order_item->product);
        }

        if ($order->reward_money > 0) {
            $user = User::find($user_id);
            $user->reward_money += $order->reward_money;
            $user->save();

            $reward_money_history = new RewardMoneyHistory();
            $reward_money_history->user_id = $user_id;
            $reward_money_history->reward_item = $order->order_number . '訂單取消，購物金退回';
            $reward_money_history->amount = $order->reward_money;
            $reward_money_history->total = $user->reward_money;
            $reward_money_history->save();
        }

        return response(__('frontend.response-canceled') . $order->reward_money);
    }

    public function orderReturn($order_id, $order_number)
    {
        $user_id = Auth::user()->id;
        $order = Order::with('orderItems')->where('user_id', $user_id)->find($order_id);

        return view('user.order-return', compact('order'))
            ->with('categories', $this->categories);
    }

    public function orderReturnStore(Request $request)
    {
        $user_id = Auth::user()->id;
        $return_items = $request->all();
        foreach (array_keys($return_items) as $return_item_id) {
            $order_item = OrderItem::find($return_item_id);
            $order_id = $order_item->order_id;
            $return_item = new OrderItem();
            $return_item->order_id = $order_item->order_id;
            $return_item->product_id = $order_item->product_id;
            $return_item->quantity = $return_items[$return_item_id];
            $return_item->price = $order_item->price;
            $return_item->is_return = 1;
            // dd($return_item);
            $return_item->save();
        }

        $order = Order::with('coupon')->find($order_id);
        // dd($order['status']);
        $order['status'] = 5;
        $order->save();
        
        // $return_order_items = OrderItem::where('order_id', $order_id)->where('is_return', 1)->get();
        // dd($return_order_items);
        // $subtotal = 0;
        // foreach ($return_order_items as $return_order_item) {
        //     $item_subtotal = $return_order_item['price'] * $return_order_item['quantity'];
        //     $subtotal += $item_subtotal;
        // }
        // dd($subtotal);
        // if ($subtotal == $order['subtotal']) {
        //     // dd('h1');
        // }
        // dd($order);

        // dd($return_items);
        // dd($request->all());
        return response(__('frontend.response-return'));
    }

    public function returned()
    {
        $profile = Auth()->user();
        // return $profile;
        $return_orders = Order::getReturnedOrdersByUser($profile->id);
        // dd($return_orders);
        foreach ($return_orders as $return_order) {
            foreach ($return_order->orderItems as $orderItem) {

            }
        }

        return view('user.returned')
            ->with('categories', $this->categories)
            ->with('order_status', $this->order_status_array)
            ->with('profile', $profile)
            ->with('return_orders', $return_orders);
    }

    public function wishlist()
    {

        $profile = Auth()->user();
        // dd($profile);
        $wishlist = Wishlist::getWishlistByUser($profile->id);
        // dd($wishlist);
        // return $profile;
        return view('user.wishlist')
            ->with('categories', $this->categories)
            ->with('profile', $profile)
            ->with('wishlist', $wishlist);
    }

    public function addToWishlist(Request $request)
    {
        if (empty(Auth::user()->id)) {
            return response(__('frontend.response-no-login'));
        }
        if (Wishlist::checkItem($request->user_id, $request->product_id)) {
            return response(__('frontend.response-wishlist-added'));
        }
        $wishlist = new Wishlist;
        $wishlist->user_id = $request->user_id;
        $wishlist->product_id = $request->product_id;
        // return($wishlist);
        $wishlist->save();
        return response(__('frontend.response-wishlist-success'));
    }

    public function removeWishlist(Request $request)
    {
        $wishlist = Wishlist::getFirstWishlist($request->user_id, $request->product_id);
        if ($wishlist->delete()) {
            return response(__('frontend.response-wishlist-remove'));
        }
        return response(__('frontend.response-error'));
    }

    // public function wishlistDelete(Request $request)
    // {
    //     $wishlist = Wishlist::find($request->id);
    //     if ($wishlist) {
    //         $wishlist->delete();
    //         request()->session()->flash('success', '商品已經移出收藏清單');
    //         return back();
    //     }
    //     request()->session()->flash('error', '發生錯誤，請聯絡客服');
    //     return back();
    // }

    public function qaCenter()
    {
        $profile = Auth()->user();
        $messages = Message::with('order')->orderBy('id', 'asc')->groupBy('order_id')->where('user_id', $profile['id'])->get();
        return view('user.qa-center', compact('profile', 'messages'))
            ->with('categories', $this->categories);
    }
}
