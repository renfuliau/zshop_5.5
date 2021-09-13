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
        $user_level = UserLevel::getUserLevel($profile->user_level_id);
        $next_user_level = UserLevel::orderBy('level_up_line', 'asc')->where('level_up_line', '>', $profile->total_shopping_amount)->first();
        if (! empty($next_user_level)) {
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
        $order = Order::with('orderItems')->with('coupon')->with('user')->where('order_number', $order_number)->first();
        // dd($order);
        $messages = Message::where('order_id', $order['id'])->orderBy('created_at', 'asc')->get();
        // dd($messages);
        $return_total = 0;
        if ($order['status'] > 4) {
            foreach ($order->orderItems as $orderItem) {
                if ($orderItem['is_return'] == 1) {
                    $return_total += ($orderItem['price'] * $orderItem['quantity']);
                }
            }
        }
        // dd($return_total);
        return view('user.order-detail', compact('order', 'messages', 'return_total'))
            ->with('categories', $this->categories)
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
        $order = Order::with('orderItems')->find($order_id);
        $orderItemArray = [];
        foreach ($order->orderItems as $orderItem) {
            if ($orderItem['is_return'] == 0) {
                // array_push($orderItemArray, [$orderItem['product_id'] => $orderItem['quantity']]);
                $orderItemArray[$orderItem['product_id']] = $orderItem['quantity'];
            } else {
                $orderItemArray[$orderItem['product_id']] -= $orderItem['quantity'];
            }
        }
        // dd($orderItemArray);

        return view('user.order-return', compact('order', 'orderItemArray'))
            ->with('categories', $this->categories);
    }

    public function orderReturnStore(Request $request)
    {
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
            $return_item->save();
        }

        // 變更訂單狀態
        $order = Order::getOrderWithReturnedItems($order_id);
        $return_total = Order::getReturnOrderTotal($order_id);
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
            $user_level = UserLevel::orderBy('level_up_line', 'desc')->where('level_up_line', '<', $user->total_shopping_amount)->first();
            $user->user_level_id = $user_level->id;
            // dd($user->user_level_id);
            $response .= __('frontend.response-return-level-down') . $user_level['name'] . '！ ';
            // dd($response);
        }
        $user->save();

        // 檢查購物金優惠是否失效
        if ($order->coupon) {
            if ($order->coupon['coupon_type'] == 2) {
                if ($order['subtotal'] - $return_total < $order->coupon['coupon_line']) {
                    $undo_reward_money = $order->coupon['coupon_amount'] * -1;

                    $user['reward_money'] += $undo_reward_money;
                    $user->save();
            
                    $reward_history = new RewardMoneyHistory();
                    $reward_history['user_id'] = $user['id'];
                    $reward_history['reward_item'] = $order['order_number'] . '，' . __('frontend.user-order-return');
                    $reward_history['amount'] = $undo_reward_money;
                    $reward_history['total'] = $user['reward_money'];
                    $reward_history->save();
                    return response($response . __('frontend.response-return-undo-reward') . $order->coupon['coupon_amount']);
                }
            }
        }

        return response($response);
    }

    public function returned()
    {
        $profile = Auth()->user();
        $return_orders = Order::getReturnedOrdersByUser($profile->id);
        foreach ($return_orders as $return_order) {
            $total = 0;
            foreach ($return_order->orderItems as $orderItem) {
                $total += $orderItem['price'] * $orderItem['quantity'];
            }
            $return_order['total'] = $total;
        }

        return view('user.returned')
            ->with('categories', $this->categories)
            ->with('order_status', $this->order_status_array)
            ->with('order_status_en', $this->order_status_array_en)
            ->with('profile', $profile)
            ->with('return_orders', $return_orders);
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
