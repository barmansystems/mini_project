<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\TasksRequests;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{


    public function index()
    {
        $userId = auth()->id();

        $tasks = Task::where('creator_id', $userId)->orWhereHas('users', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
            ->orderByDesc('created_at')
            ->paginate(30);
        return view('tasks.index', compact('tasks'));
    }



    public function create()
    {
        return view('tasks.create');
    }


    public function store(TasksRequests $request)
    {

        $task = Task::create([
            'creator_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'start_at' => verta()->parse($request->start_at)->formatGregorian('Y-m-d H:i:s'),
            'expire_at' => verta()->parse($request->expire_at)->formatGregorian('Y-m-d H:i:s'),
        ]);

        $this->assignTask($task, $request->users);
        $this->sendNotificationToUser($request->users);
        alert()->success('با موفقیت اضافه شد.', 'موفقیت آمیز');
        return redirect(route('tasks.index'));
    }


    public function show(Task $task)
    {
        return view('tasks.show', compact(['task']));
    }


    public function edit(Task $task)
    {
        return view('tasks.edit', compact(['task']));
    }

    public function update(TasksRequests $request, Task $task)
    {
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'start_at' => verta()->parse($request->start_at)->formatGregorian('Y-m-d H:i:s'),
            'expire_at' => verta()->parse($request->expire_at)->formatGregorian('Y-m-d H:i:s'),
        ]);

        $this->assignTask($task, $request->users);
        alert()->success('با موفقیت ویرایش شد.', 'موفقیت آمیز');
        return redirect(route('tasks.index'));
    }


    public function destroy(Task $task)
    {
        $task->delete();
        alert()->success('با موفقیت حذف شد.', 'موفقیت آمیز');
        return back();

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
