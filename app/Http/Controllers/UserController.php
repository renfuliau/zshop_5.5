<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
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
    protected $cart_total_qty;
    protected $categories;

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
            ->with('cart_total_qty', $this->cart_total_qty)
            ->with('profile', $profile)
            ->with('orders', $orders);
    }

    public function orderDetail($order_number)
    {
        $order = Order::with('orderItems')->where('order_number', $order_number)->first();
        dd($order->orderItems);
        return view('user.order-detail', compact('order'));
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
