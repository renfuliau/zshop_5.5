<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Setting;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class FrontendController extends Controller
{
    protected $user;
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

    public function index()
    {
        return view('index')
            ->with('categories', $this->categories);
    }

    public function locale()
    {
        $language = App::getLocale();
        switch ($language) {
            case 'zh-tw':
                App::setLocale('en');
                Session::put('locale', 'en');
                // dd(App::getLocale());

                break;
            case 'en':
                App::setLocale('zh-tw');
                Session::put('locale', 'zh-tw');
                break;
        }
        return redirect()->back();
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
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            Session::put('user', $data['email']);
            request()->session()->flash('success', __('frontend.log-in-success'));
            return redirect()->route('index');
        } else {
            request()->session()->flash('error', __('frontend.log-in-error'));
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
                'unique' => ':attribute ' . __('frontend.email-existed') . ' :attribute',
                'min' => __('passwords.password'),
                'confirmed' => __('frontend.password-confirm-error'),
            ]
        );

        $data = $request->all();
        $check = User::new ($data);
        // dd($check);
        Session::put('user', $data['email']);
        if ($check) {
            request()->session()->flash('success', __('frontend.register-success'));
            return redirect()->route('z-login');
        } else {
            request()->session()->flash('error', __('frontend.register-error'));
            return back();
        }
    }

    public function logout()
    {
        Session::forget('user');
        Auth::logout();
        request()->session()->flash('success', __('frontend.log-out-success'));
        return redirect()->route('index');
    }

    public function forgetPassword()
    {
        return view('z-auth.forget-password')->with('categories', $this->categories);
    }

    public function contact()
    {
        $photo_path = Setting::getPhoto()->photo;
        return view('contact.contact')
            ->with('categories', $this->categories)
            ->with('photo_path', $photo_path);
    }
}
