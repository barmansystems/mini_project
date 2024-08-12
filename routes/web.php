<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return "test";
});

//Route::get('/{id}/{company_name}/{title}', function ($id, $company_name, $title) {
//
////        $d = '1'.$data->receiver_id.' '.'2:'.$data->url_name.' '.'3:'.$data->ticket_title;
//    $data = [
//        'user_id' => $id,
//        'url_name' => $company_name,
//        'ticket_title' => $title,
//    ];
////        return $data;
////        return "hello";
////        return $this->getPanelUrl($company_name);
//    try {
//        $response = Http::timeout(30)->post('http://127.0.0.1:9500/api/send-notification-to-user', $data);
//        if ($response->successful()) {
//            return $response->body();
//        } else {
//            return response()->json(['error' => 'Request-failed'], $response->status());
//        }
//    } catch (\Illuminate\Http\Client\RequestException $e) {
//        return response()->json(['error' => 'Request-timed-out-or-failed', 'message' => $e->getMessage()], 500);
//    }
//});
