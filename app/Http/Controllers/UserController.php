<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Category;
use App\Models\UserLevel;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use App\Models\RewardMoneyHistory;
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
}
