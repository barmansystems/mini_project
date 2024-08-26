<?php

use App\Http\Controllers\Api\v1\TaskController;
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

Route::group(['api_key_auth'], function () {
    Route::match(['get', 'post'], 'get-all-tickets', [TicketController::class, 'allTickets']);
    Route::match(['get', 'post'], 'get-my-tickets', [TicketController::class, 'myTickets']);

    Route::post('create-ticket', [TicketController::class, 'createTicket']);

    Route::post('get-users', [TicketController::class, 'getUsers']);
    Route::post('get-all-users', [TaskController::class, 'getAllUsers']);
    Route::post('show-task', [TaskController::class, 'showTask']);
    Route::post('change-task-status', [TaskController::class, 'changeTaskStatus']);
    Route::post('add-task-status', [TaskController::class, 'addTaskStatus']);
    Route::post('get-task-desc', [TaskController::class, 'getTaskDesc']);
    Route::post('update-task', [TaskController::class, 'updateTask']);
    Route::post('delete-task', [TaskController::class, 'deleteTask']);
    Route::post('add-task-desc', [TaskController::class, 'addTaskDesc']);
    Route::post('create-task', [TaskController::class, 'storeTask']);
    Route::match(['get', 'post'], 'get-all-tasks', [TaskController::class, 'index']);

    Route::match(['get', 'post'], 'get-messages', [TicketController::class, 'getMessages']);
    Route::match(['get', 'post'], 'chat-in-tickets', [TicketController::class, 'chatInTickets']);
    Route::post('delete-ticket', [TicketController::class, 'deleteTicket']);
    Route::post('change-status-ticket', [TicketController::class, 'changeStatusTicket']);
    Route::post('add-user', [TicketController::class, 'addUserToMoshrefi']);
    Route::post('edit-user', [TicketController::class, 'editUserToMoshrefi']);
    Route::post('delete-user', [TicketController::class, 'deleteUserToMoshrefi']);



});


