<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Validator;

class Register
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // $request->validate(
        //     [
        //         'email' => 'string|required|unique:users,email',
        //         'password' => 'required|min:6|confirmed',
        //     ],
        //     [
        //         'unique' => 'Email 已存在，請更換 Email 註冊',
        //         'min' => '最少 6 個字元',
        //         'confirmed' => '確認密碼錯誤，請重新輸入!'
        //     ]
        // );

        $rules = [
            'email' => 'string|required|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ];

        $messages = [
            'required' => ':attribute 為必填',
            'unique' => ':attribute 已存在，請更換 :attribute 註冊',
            'min' => ':attribute 最少 6 個字元',
            'confirmed' => '確認密碼錯誤，請重新輸入!'
        ];

        $validator = Validator::make($request, $rules, $messages);

        return $next($request);
    }
}
