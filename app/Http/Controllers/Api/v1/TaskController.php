<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use App\Notifications\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class TaskController extends Controller
{
    public function index(Request $request)
    {

        $user = User::where(['company_user_id' => $request->auth_id, 'company_name' => $request->company_name])->first();
        $userId = $user->id;
        $perPage = 10;
        $tasks = Task::where('creator_id', $userId)->orWhereHas('users', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->orderByDesc('created_at')
            ->paginate($perPage);


        $object_tasks = $tasks->getCollection()->map(function ($task) use ($userId) {
            $status = DB::table('task_user')->where(['task_id' => $task->id, 'user_id' => $userId])->first()->status ?? null;
            return (object)[
                "id" => $task->id,
                "creator" => $task->creator->name . ' ' . $task->creator->family,
                "status" => $status,
                "creator_id" => $task->creator->company_user_id,
                "title" => $task->title,
                "start_at" => $task->start_at,
                "expire_at" => $task->expire_at,
                "created_at" => $task->created_at,
            ];
        });
//        return $object_tasks;

        return response()->json([
            'data' => $object_tasks,
            'pagination' => [
                'total' => $tasks->total(),
                'per_page' => $tasks->perPage(),
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
                'next_page_url' => $tasks->nextPageUrl(),
                'prev_page_url' => $tasks->previousPageUrl(),
            ]
        ]);
    }


    public function storeTask(Request $request)
    {
        // get user who created this tasks?
        $creator_id = User::where(['company_user_id' => $request->creator_id, 'company_name' => $request->company_name])->first();
////        $users = User::whereIn('id',$request->users)->get();
//
//
        $task = Task::create([
            'creator_id' => $creator_id->id,
            'title' => $request->title,
            'description' => $request->description,
            'start_at' => $request->start_at,
            'expire_at' => $request->expire_at,
        ]);
//
        $this->assignTask($task, $request->users);
        $this->sendNotificationToUser($request->users);
//
        return "success";

    }


    public function getAllUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function showTask(Request $request)
    {

//        return $request->all();
        //user when login
        $user = User::where(['company_user_id' => $request->auth_id, 'company_name' => $request->company_name])->first();

        //get task
//        return $user;

        $task = Task::with('users')->findOrFail($request->task_id);
        //get creator

        $creator = User::whereId($task->creator_id)->first();
//        return $creator_user;


        $task_etc = DB::table('task_user')->where(['task_id' => $task->id, 'user_id' => $user->id])->first();
        $res = [
            'task' => $task,
            'task_user' => $task_etc,
            'auth_user' => $user,
            'creator' => $creator,
        ];

        return response()->json($res);
    }


    public function changeTaskStatus(Request $request)
    {

        $user = User::where(['company_user_id' => $request->user_id, 'company_name' => $request->company_name])->first();
//        return $user;
        $task = DB::table('task_user')->where(['task_id' => $request->task_id, 'user_id' => $user->id]);

        if ($task->first()->status == 'done') {
            $task_status = 'doing';
            $message = 'انجام نشده';
            $done_at = null;
        } else {
            $task_status = 'done';
            $message = 'انجام شده';
            $done_at = now();
        }

        $task->update(['status' => $task_status, 'done_at' => $done_at]);

        return response()->json(['task_status' => $task_status, 'message' => $message]);
    }

    public function addTaskDesc(Request $request)
    {
//        return $request->all();
        $authUser = User::where(['company_user_id' => $request->user_id, 'company_name' => $request->company_name])->first();
        $task = DB::table('task_user')->where(['task_id' => $request->task_id, 'user_id' => $authUser->id]);
        $task->update(['description' => $request->description]);
        return "success";
    }

    public function getTaskDesc(Request $request)
    {
//        return $request->pivot_id;
        $task = DB::table('task_user')->find($request->pivot_id);
        return response()->json(['data' => $task->description]);
    }


    public function deleteTask(Request $request)
    {
//        return $request->task_id;
        $task = Task::findOrfail($request->task_id);
        $task->delete();
        return $task;

    }


    public function updateTask(Request $request)
    {

        $task = Task::findOrfail($request->task_id);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'start_at' => $request->start_at,
            'expire_at' => $request->expire_at,
        ]);

        $this->assignTask($task, $request->users);
        return "success";

    }

    private function assignTask(Task $task, $users)
    {
        $task->users()->sync($users);
    }


    private function sendNotificationToUser($users)
    {
        $allUser = User::whereIn('id', $users)->get();
        foreach ($allUser as $user) {
            $message = 'یک وظیفه جدید برای شما ارسال شده است.';
            dispatch(new \App\Jobs\SendNotificationJob($user->company_user_id, $user->company_name, $message));
        }
        return True;
    }

}
