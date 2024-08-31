<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <title>ورود به پنل مدیریت</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="/assets/images/icons/favicon.ico"/>
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/assets/vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/assets/fonts/iconic/css/material-design-iconic-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/assets/vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/assets/vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/assets/vendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/assets/vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/assets/vendor/daterangepicker/daterangepicker.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/assets/css/util.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/main.css">
    <link rel="icon" href="https://moshrefiholding.com/wp-content/uploads/2023/08/logo-small-150x150.png" sizes="32x32">
    <link rel="icon" href="https://moshrefiholding.com/wp-content/uploads/2023/08/logo-small-300x300.png"
          sizes="192x192">
    <!--===============================================================================================-->
    <style>
        input:-webkit-autofill {
            background-color: transparent !important;
            /* سایر استایل‌های دلخواه */
        }

        /* برای Firefox */
        input:-moz-autofill {
            background-color: transparent !important;
            /* سایر استایل‌های دلخواه */
        }

        /* برای Edge */
        input:-ms-autofill {
            background-color: transparent !important;
            /* سایر استایل‌های دلخواه */
        }
    </style>
</head>
<body>


<div class="limiter">
    <div class="container-login100" style="background-image: url('/assets/images/bg-01.jpg');">
        <div class="wrap-login100">
            <form action="/login" method="post" class="login100-form validate-form">
                @CSRF
                <span class="login100-form-logo">
						<i class="zmdi zmdi-landscape"></i>
					</span>

                <span class="login100-form-title p-t-27">
						ورود
					</span>

                <div class="wrap-input100 validate-input">

                    <input class="input100" type="number" value="{{old('phone')}}" name="phone"
                           placeholder="شماره موبایل" autocomplete="off"
                           oninput="if(this.value.length > 11) this.value = this.value.slice(0, 11);">
                    <span class="focus-input100" data-placeholder="&#xf207;"></span>

                </div>
                @error('phone')
                <span class="text-light" style="font-size: .8rem">{{$message}}</span>
                @enderror

                <div class="wrap-input100 validate-input">
                    <input class="input100" type="password" value="{{old('password')}}" name="password"
                           placeholder="رمز عبور" autocomplete="off">
                    <span class="focus-input100" data-placeholder="&#xf191;"></span>
                </div>
                @error('password')
                <span class="text-white" style="font-size: .8rem">{{$message}}</span>
                @enderror

                <div class="contact100-form-checkbox">
                    <div class="g-recaptcha" data-sitekey="{{config('services.recaptcha.sitekey')}}"></div>
                </div>
                @error ('g-recaptcha-response')
                <span class="text-white" style="font-size: .8rem">{{$message}}</span>
                @enderror
                <div class="contact100-form-checkbox">
                    <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember">
                    <label class="label-checkbox100" for="ckb1">
                        منو بخاطر داشته باش
                    </label>


                </div>

                <div class="container-login100-form-btn">
                    <button class="login100-form-btn">
                        ورود
                    </button>

                </div>
                @if(session('error'))
                    <div style="text-align: center ;margin-top: .8rem">
                        <span class="text-white" style="font-size: .8rem">{{ session('error') }}</span>
                    </div>
                @endif

            </form>
        </div>
    </div>
</div>


<div id="dropDownSelect1"></div>

<!--===============================================================================================-->
<script src="/assets/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
<script src="/assets/vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
<script src="/assets/vendor/bootstrap/js/popper.js"></script>
<script src="/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
<script src="/assets/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
<script src="/assets/vendor/daterangepicker/moment.min.js"></script>
<script src="/assets/vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
<script src="/assets/vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
<script src="/assets/js/main.js"></script>

</body>
<script src="https://www.google.com/recaptcha/api.js?hl=fa" async defer></script>
</html>
