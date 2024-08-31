<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\GoogleCaptcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use function Laravel\Prompts\password;

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
            'g-recaptcha-response' => ['required', new GoogleCaptcha()],
        ], [
            'phone.required' => 'شماره موبایل را وارد کنید.',
            'phone.digits' => 'شماره موبایل باید 11 رقمی باشد.',
            'phone.regex' => 'فرمت شماره تلفن صحیح نمیباشد.',
            'password.required' => 'رمزعبور را وارد کنید.',
            'g-recaptcha-response.required' => 'فیلد گوگل کپچا الزامی است',
        ]);

//        return $request->all();

        $credentials = $request->only('phone', 'password');
        $remember = $request->filled('remember');


        $user = User::where('phone', $credentials['phone'])->where('password', '!=', null)->first();


        if ($user && Hash::check($credentials['password'], $user->password) && $user->is_manager == 1) {


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
