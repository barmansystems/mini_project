<?php

use Illuminate\Support\Str;

use Illuminate\Support\Facades\Route;

if (!function_exists('active_sidebar')) {
    function active_sidebar(array $items)
    {
        $route = Route::current()->uri();

        foreach ($items as $value) {
            if (Str::is($value, $route)) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('company_name')) {
    function company_name($company)
    {
        $variable = '';

        switch ($company) {
            case 'parso':
                echo "پرسو تجارت";
                break;
            case 'adaktejarat':
                echo "آداک تجارت خورشید قشم";
                break;
            case 'adakhamrah':
                echo "آداک همراه خورشید قشم";
                break;
            case 'barman':
                echo "بارمان سیستم سرزمین پارس";
                break;
            case 'sayman':
                echo "فناوران رایانه سایمان داده";
                break;
            case 'adakpetro':
                echo "آداک پترو خورشید قشم";
                break;
        }
        return $variable;

    }
}

if (!function_exists('convert_number_to_persian')) {
    function convert_number_to_persian($number)
    {
        $englishToPersian = [
            '0' => '۰',
            '1' => '۱',
            '2' => '۲',
            '3' => '۳',
            '4' => '۴',
            '5' => '۵',
            '6' => '۶',
            '7' => '۷',
            '8' => '۸',
            '9' => '۹'
        ];

        $persianNumber = strtr($number, $englishToPersian);
        return $persianNumber;
    }
}


