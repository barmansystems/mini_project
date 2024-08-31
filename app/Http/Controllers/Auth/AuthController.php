<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\GoogleCaptcha;
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
            'remember' => 'boolean',
            'g-recaptcha-response' => ['required', new GoogleCaptcha()],
        ], [
            'phone.required' => 'شماره موبایل را وارد کنید.',
            'phone.digits' => 'شماره موبایل باید 11 رقمی باشد.',
            'phone.regex' => 'فرمت شماره تلفن صحیح نمیباشد.',
            'password.required' => 'رمزعبور را وارد کنید.',
            'g-recaptcha-response.required' => 'فیلد گوگل کپچا الزامی است',
        ]);


        $credentials = $request->only('phone', 'password');
        $remember = $request->input('remember');


        $user = User::where('phone', $credentials['phone'])->first();


        if ($user && $user->is_manager == 1) {

            if (Auth::attempt($credentials, $remember)) {
                return redirect()->to('/');
            } else {

                return back()->with([
                    'error' => 'نام کاربری و یا رمز عبور اشتباه است'
                ]);
            }
        } else {

            return back()->with([
                'error' => 'اجازه دسترسی ندارید.'
            ]);
        }
    }

}
