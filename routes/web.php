<?php

use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\Home\TaskController;
use App\Http\Controllers\Home\UsersController;
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

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('home');
    });
});

Route::get('/test/{id}', function ($id) {
    return auth()->loginUsingId($id);
});

Route::get('/login', [AuthController::class, 'loginPage'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');


Route::get('/users', [UsersController::class, 'index'])->name('users.index');

Route::resource('/tasks', TaskController::class);


