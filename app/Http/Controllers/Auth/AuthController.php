<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{


    public function loginPage()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // اعتبارسنجی ورودی‌ها
        $request->validate([
            'phone' => 'required|digits:11|regex:/^09[0-9]{9}$/',
            'password' => 'required|string',
            'remember' => 'boolean'
        ], [
            'phone.required' => 'شماره موبایل را وارد کنید.',
            'phone.digits' => 'شماره موبایل باید 11 رقمی باشد.',
            'phone.regex' => 'فرمت شماره تلفن صحیح نمیباشد.',
            'password.required' => 'رمزعبور را وارد کنید.',
        ]);


        $credentials = $request->only('phone', 'password');


        $remember = $request->input('remember');


        if (Auth::attempt($credentials, $remember)) {
            return redirect()->to('/');
        } else {
            // ورود ناموفق
            return back()->with([
                'error' => 'نام کاربری و یا رمز عبور اشتباه است'
            ]);
        }
    }
}
