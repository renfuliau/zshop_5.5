<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Message;
use App\Models\Category;
use App\Models\Wishlist;
use App\Models\OrderItem;
use App\Models\UserLevel;
use App\Models\ReturnOrder;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use App\Models\RewardMoneyHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $user;
    protected $categories;
    protected $order_status_array;
    protected $order_status_array_en;
    protected $return_status_array;
    protected $return_status_array_en;
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

        $this->return_status_array = [
            0 => '尚未退款',
            1 => '退款完成',
        ];

        $this->return_status_array_en = [
            0 => 'Porcessing',
            1 => 'refunded',
        ];
    }

    public function profile()
    {
        // dd(Auth()->user());
        $profile = Auth()->user();
        // dd($profile->user_level_id);
        $user_level = UserLevel::getUserLevel($profile->user_level_id);
        $next_user_level = UserLevel::orderBy('level_up_line', 'asc')->where('level_up_line', '>', $profile->total_shopping_amount)->first();
        if (!empty($next_user_level)) {
            # code...
            $amount_to_level_up = $next_user_level['level_up_line'] - $profile->total_shopping_amount;
        }
        // dd($next_user_level);
        // return $profile;
        return view('user.profile', compact('profile', 'user_level', 'next_user_level', 'amount_to_level_up'))
            ->with('categories', $this->categories);
    }

    public function profileUpdate(Request $request, $id)
    {
        // return $request->all();
        $user = User::findOrFail($id);
        $data = $request->all();
        // dd($data);
        $status = $user->fill($data)->save();
        if ($status) {
            request()->session()->flash('success', __('frontend.user-profile-update-success'));
        } else {
            request()->session()->flash('error', __('frontend.user-profile-update-error'));
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
        $order = Order::with('orderItems')->with('coupon')->with('user')->with('returnOrders')->where('order_number', $order_number)->first();
        // dd($order->returnOrders[0]->orderItems[0]->product->productImg[0]->filepath);
        $messages = Message::where('order_id', $order['id'])->orderBy('created_at', 'asc')->get();
        // dd($messages);
        $return_total_array = [];
        if ($order['status'] > 4) {
            foreach ($order->returnOrders as $returnOrder) {
                $return_total = 0;
                foreach ($returnOrder->orderItems as $orderItem) {
                    $return_total += ($orderItem['price'] * $orderItem['quantity']);
                }
                array_push($return_total_array, $return_total);
            }
        }
        // dd($return_total_array);                     
        return view('user.order-detail', compact('order', 'messages', 'return_total_array'))
            ->with('categories', $this->categories)
            ->with('return_status', $this->return_status_array)
            ->with('return_status_en', $this->return_status_array_en)
            ->with('order_status', $this->order_status_array)
            ->with('order_status_en', $this->order_status_array_en);
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

        // 變更訂單狀態
        $order->status = 4;
        $order->save();

        // 變更購物總金額
        $user = User::find($user_id);
        $user->total_shopping_amount += $order->total;
        $user->save();

        // 檢查會員是否升級
        $user_level = UserLevel::orderBy('level_up_line', 'desc')->where('level_up_line', '<=', $user->total_shopping_amount)->first();
        if ($user_level->id != $user->user_level_id) {
            $user->user_level_id = $user_level->id;
            $user->save();
            $response['level_up'] = __('frontend.user-order-level-up') . $user_level->name;
        }

        // 確認訂單優惠，發放購物金
        if ($order->coupon['coupon_type'] == 2) {
            $user->reward_money += $order->coupon['coupon_amount'];
            $user->save();
            $reward_money_history = new RewardMoneyHistory();
            $reward_money_history->user_id = $user_id;
            $reward_money_history->reward_item = $order->order_number . __('frontend.user-order-coupon-reward');
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
        // $user_id = Auth::user()->id;
        $order = Order::with('orderItems')->with('returnOrders')->find($order_id);
        $orderItemArray = [];
        foreach ($order->orderItems as $orderItem) {
            $orderItemArray[$orderItem['product_id']] = $orderItem['quantity'];
        }
        // dd($orderItemArray);
        if (!empty($order->returnOrders)) {
            foreach ($order->returnOrders as $returnOrder) {
                foreach ($returnOrder->orderItems as $orderItem) {
                    $orderItemArray[$orderItem['product_id']] -= $orderItem['quantity'];
                }
            }
        }
        // dd($orderItemArray);

        return view('user.order-return', compact('order', 'orderItemArray'))
            ->with('categories', $this->categories);
    }

    public function orderReturnStore(Request $request)
    {
        $return_items = $request->all();
        // dd(array_keys($return_items)[0]);
        // if (array_keys($return_items)[0]) {
        //     # code...
        // }
        $order_item_0 = OrderItem::with('order')->find(array_keys($return_items)[0]);
        $return_order = new ReturnOrder();
        $return_order['user_id'] = $order_item_0->order['user_id'];
        $return_order['order_id'] = $order_item_0->order['id'];
        $return_order['is_refund'] = 0;
        $return_order->save();
        // dd($return_order->id);
        foreach (array_keys($return_items) as $return_item_id) {
            $order_item = OrderItem::find($return_item_id);
            $order_id = $order_item->order_id;
            $return_item = new OrderItem();
            $return_item->order_id = $return_order->id;
            $return_item->product_id = $order_item->product_id;
            $return_item->quantity = $return_items[$return_item_id];
            $return_item->price = $order_item->price;
            $return_item->is_return = 1;
            $return_item->save();
        }
        // dd(ReturnOrder::with('orderItems')->find($return_order->id));

        // 變更訂單狀態
        $order = Order::getOrderWithReturnedItems($order_id);
        $return_total_all = Order::getReturnOrderTotalAll($order_id);
        $return_total = Order::getReturnOrderTotal($order_id, $return_order['id']);
        // dd($return_total);
        $user = User::find($order->user_id);
        $order['status'] = 5;
        $order->save();

        $response = __('frontend.response-return');

        // 檢查會員等級是否降級
        $user_id = Auth::user()->id;
        $user = User::with('userLevel')->find($user_id);
        $user['total_shopping_amount'] -= $return_total;
        if ($user['total_shopping_amount'] < $user->userLevel['level_up_line']) {
            // dd($user);
            $user_level = UserLevel::orderBy('level_up_line', 'desc')->where('status', 'active')->where('level_up_line', '<', $user->total_shopping_amount)->first();
            $user['user_level_id'] = $user_level['id'];
            // dd($user->user_level_id);
            $response .= __('frontend.response-return-level-down') . $user_level['name'] . '！ ';
            // dd($response);
        }
        $user->save();

        // 檢查購物金優惠是否失效
        if (
            $order->coupon && $order->coupon['coupon_type'] == 2 &&
            $order['subtotal'] - $return_total_all >= $order->coupon['coupon_line']
        ) {
            if ($order['subtotal'] - $return_total_all - $return_total < $order->coupon['coupon_line']) {
                $undo_reward_money = $order->coupon['coupon_amount'] * -1;

                $user['reward_money'] += $undo_reward_money;
                $user->save();

                $reward_history = new RewardMoneyHistory();
                $reward_history['user_id'] = $user['id'];
                $reward_history['reward_item'] = $order['order_number'] . '，' . __('frontend.user-order-coupon2-cancel');
                $reward_history['amount'] = $undo_reward_money;
                $reward_history['total'] = $user['reward_money'];
                $reward_history->save();
                return response($response . __('frontend.response-return-undo-reward') . $order->coupon['coupon_amount']);
            }
        }

        return response($response);
    }

    public function returned()
    {
        $profile = Auth()->user();
        $orders = Order::getReturnedOrdersByUser($profile->id);
        // dd($orders);
        // foreach ($orders as $order) {
        //     $total = 0;
        //     foreach ($order->returnOrders as $returnOrder) {
        //         foreach ($returnOrder->orderItems as $orderItem) {
        //             $total += $orderItem['price'] * $orderItem['quantity'];
        //         }
        //     }
        //     $order['total'] = $total;
        // }
        $orders_return_total_array = [];
        foreach ($orders as $order) {
            $return_total_array = [];
            foreach ($order->returnOrders as $returnOrder) {
                $return_total = 0;
                foreach ($returnOrder->orderItems as $orderItem) {
                    $return_total += ($orderItem['price'] * $orderItem['quantity']);
                }
                array_push($return_total_array, $return_total);
            }
            $orders_return_total_array[$order->id] = $return_total_array;
        }
        // dd($orders_return_total_array);

        return view('user.returned')
            ->with('categories', $this->categories)
            ->with('return_status', $this->return_status_array)
            ->with('return_status_en', $this->return_status_array_en)
            ->with('profile', $profile)
            ->with('orders', $orders)
            ->with('orders_total', $orders_return_total_array);
    }

    // public function returnDetail($order_number)
    // {

    // }

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
