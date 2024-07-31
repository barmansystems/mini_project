<?php

use App\Http\Controllers\Api\v1\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('get-all-tickets', [TicketController::class, 'allTickets']);
Route::post('get-my-tickets', [TicketController::class, 'myTickets']);

Route::post('create-ticket', [TicketController::class, 'createTicket']);
Route::post('get-users', [TicketController::class, 'getUsers']);
