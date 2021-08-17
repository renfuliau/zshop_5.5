<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class FrontendController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function loginRegister()
    {
        return view('auth.login-register');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function loginSubmit(Request $request)
    {
        $data = $request->all();
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'status' => 'active'])) {
            Session::put('user', $data['email']);
            request()->session()->flash('success', 'Successfully login');
            return redirect()->route('home');
        } else {
            request()->session()->flash('error', 'Invalid email and password pleas try again!');
            return redirect()->back();
        }
    }

    public function register()
    {
        return view('auth.register');
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
                'min' => '最少 6 個字元',
                'confirmed' => '確認密碼錯誤，請重新輸入!'
            ]
        );

        $data = $request->all();
        $check = User::new($data);
        // dd($check);
        Session::put('user', $data['email']);
        if ($check) {
            request()->session()->flash('success', '註冊成功');
            return redirect()->route('login');
        } else {
            request()->session()->flash('error', '系統錯誤，請聯絡客服!');
            return back();
        }
    }
}
