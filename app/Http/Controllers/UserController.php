<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Order;
use App\Models\Product;
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
    protected $categories;

    public function __construct()
    {
        $this->categories = Category::getAllParentCategory();
    }

    public function profile()
    {
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
        // dd($user);
        $data = $request->all();
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
                'same' => '確認密碼錯誤，請重新輸入!'
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
            ->with('profile', $profile)
            ->with('orders', $orders);
    }

    public function returned()
    {
        $profile = Auth()->user();
        // return $profile;
        return view('user.returned')
            ->with('categories', $this->categories)
            ->with('profile', $profile);
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

    // public function addToWishlist(Request $request)
    // {
    //     // dd($request->all());
    //     if (empty($request->slug)) {
    //         request()->session()->flash('error', '未知的產品，請聯絡客服');
    //         return back();
    //     }
    //     $product = Product::where('slug', $request->slug)->first();
    //     // return $product;
    //     if (empty($product)) {
    //         request()->session()->flash('error', '未知的產品，請聯絡客服');
    //         return back();
    //     }

    //     $already_wishlist = Wishlist::where('user_id', auth()->user()->id)->where('cart_id', null)->where('product_id', $product->id)->first();
    //     // return $already_wishlist;
    //     if ($already_wishlist) {
    //         request()->session()->flash('error', '此商品已經存在於收藏清單');
    //         return back();
    //     } else {

    //         $wishlist = new Wishlist;
    //         $wishlist->user_id = auth()->user()->id;
    //         $wishlist->product_id = $product->id;
    //         $wishlist->price = ($product->price - ($product->price * $product->discount) / 100);
    //         $wishlist->quantity = 1;
    //         $wishlist->amount = $wishlist->price * $wishlist->quantity;
    //         if ($wishlist->product->stock < $wishlist->quantity || $wishlist->product->stock <= 0) return back()->with('error', 'Stock not sufficient!.');
    //         $wishlist->save();
    //     }
    //     request()->session()->flash('success', '商品已加入收藏清單');
    //     return back();
    // }

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
            ->with('profile', $profile);
    }
}
