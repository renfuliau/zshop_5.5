<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Message;
use App\Models\Category;
use App\Models\Wishlist;
use App\Models\UserLevel;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use App\Models\RewardMoneyHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $user;
    protected $cart_total_qty;
    protected $categories;
    protected $order_status_array;

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
            ->with('cart_total_qty', $this->cart_total_qty)
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
            ->with('cart_total_qty', $this->cart_total_qty)
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
                'same' => '確認密碼錯誤，請重新輸入!',
            ]
        );

        User::find(auth()->user()->id)->update(['password' => Hash::make($request->new_password)]);

        return redirect()->route('user-profile')->with('success', '變更密碼成功');
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
            ->with('cart_total_qty', $this->cart_total_qty)
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
            ->with('profile', $profile)
            ->with('orders', $orders);
    }

    public function orderDetail($order_number)
    {
        $order = Order::with('orderItems')->with('coupon')->where('order_number', $order_number)->first();
        // dd($order);
        return view('user.order-detail', compact('order'))
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

        return response(['message' => '成功送出，客服人員將盡快回覆', 'content' => $message->message]);
    }

    public function orderReceived(Request $request)
    {
        $user_id = Auth::user()->id;
        $order_id = $request->order_id;
        $order = Order::where('user_id', $user_id)->find($order_id);
        $order->status = 4;
        $order->save();

        $user = User::find($user_id);
        $user->total_shopping_amount += $order->total;
        $user->save();

        $user_level = UserLevel::orderBy('level_up_line', 'desc')->where('level_up_line', '<', $user->total_shopping_amount)->first();
        if ($user_level->id != $user->user_level_id) {
            $user->user_level_id = $user_level->id;
            $user->save();
            return response("商品收到後請立即檢查是否有瑕疵，若有問題請與客服聯絡\r恭喜您升級為" . $user_level->name);
        }
        // dd($user);
        return response('商品收到後請立即檢查是否有瑕疵，若有問題請與客服聯絡');
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
        $user = User::find($user_id);
        $user->reward_money += $order->reward_money;
        $user->save();
        // dd($user);
        // $product = 

        $reward_money_history = new RewardMoneyHistory();
        $reward_money_history->user_id = $user_id;
        $reward_money_history->reward_item = $order->order_number . '訂單取消，購物金退回';
        $reward_money_history->amount = $order->reward_money;
        $reward_money_history->total = $user->reward_money;
        $reward_money_history->save();

        return response('訂單已取消，退回購物金： ' . $order->reward_money . '，若有問題請與客服聯絡');
    }

    public function orderReturn(Request $request)
    {
        $user_id = Auth::user()->id;
        $order_id = $request->order_id;
        $order = Order::with('orderItems')->where('user_id', $user_id)->find($order_id);

        return view('user.order-return', compact('order'))
        ->with('categories', $this->categories);
    }

    public function returned()
    {
        $profile = Auth()->user();
        // return $profile;
        $return_orders = Order::getReturnedOrdersByUser($profile->id);
        return view('user.returned')
            ->with('categories', $this->categories)
            ->with('cart_total_qty', $this->cart_total_qty)
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
            ->with('cart_total_qty', $this->cart_total_qty)
            ->with('profile', $profile)
            ->with('wishlist', $wishlist);
    }

    public function addToWishlist(Request $request)
    {
        if (empty(Auth::user()->id)) {
            return response('請先登入');
        }
        if (Wishlist::checkItem($request->user_id, $request->product_id)) {
            return response('該商品已收藏');
        }
        $wishlist = new Wishlist;
        $wishlist->user_id = $request->user_id;
        $wishlist->product_id = $request->product_id;
        // return($wishlist);
        $wishlist->save();
        return response('成功加入收藏');
    }

    public function removeWishlist(Request $request)
    {
        $wishlist = Wishlist::getFirstWishlist($request->user_id, $request->product_id);
        if ($wishlist->delete()) {
            return response('已移出收藏');
        }
        return response('錯誤！');
    }

    public function wishlistDelete(Request $request)
    {
        $wishlist = Wishlist::find($request->id);
        if ($wishlist) {
            $wishlist->delete();
            request()->session()->flash('success', '商品已經移出收藏清單');
            return back();
        }
        request()->session()->flash('error', '發生錯誤，請聯絡客服');
        return back();
    }

    public function qaCenter()
    {
        $profile = Auth()->user();
        // return $profile;
        return view('user.qa-center')
            ->with('categories', $this->categories)
            ->with('cart_total_qty', $this->cart_total_qty)
            ->with('profile', $profile);
    }
}
