<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class FrontendController extends Controller
{
    protected $categories;

    public function __construct()
    {
        $this->categories = Category::getAllParentCategory();
    }

    public function index()
    {
        return view('index')->with('categories', $this->categories);
    }

    public function loginRegister()
    {
        return view('z-auth.login-register');
    }

    public function login()
    {
        return view('z-auth.login')->with('categories', $this->categories);
    }

    public function loginSubmit(Request $request)
    {
        $data = $request->all();
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'status' => 'active'])) {
            Session::put('user', $data['email']);
            request()->session()->flash('success', '登入成功');
            return redirect()->route('user-profile');
        } else {
            request()->session()->flash('error', '無效 Email 或密碼');
            return redirect()->back();
        }
    }

    public function register()
    {
        return view('z-auth.register')->with('categories', $this->categories);
    }

    public function registerSubmit(Request $request)
    {
        $request->validate(
            [
                'email' => 'string|required|unique:users,email',
                'password' => 'required|min:6|confirmed',
            ],
            [
                'unique' => ':attribute 已存在，請更換 :attribute 註冊',
                'min' => '密碼最少 6 個字元',
                'confirmed' => '確認密碼錯誤，請重新輸入!'
            ]
        );

        $data = $request->all();
        $check = User::new($data);
        // dd($check);
        Session::put('user', $data['email']);
        if ($check) {
            request()->session()->flash('success', '註冊成功');
            return redirect()->route('z-login');
        } else {
            request()->session()->flash('error', '系統錯誤，請聯絡客服!');
            return back();
        }
    }

    public function logout()
    {
        Session::forget('user');
        Auth::logout();
        request()->session()->flash('success', '成功登出');
        return redirect()->route('index');
    }

    public function forgetPassword()
    {
        return view('z-auth.forget-password')->with('categories', $this->categories);
    }
}
